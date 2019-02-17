<?php
/**
 * 2016 Chargeback
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 *  @author    Chargeback <it@chargeback.com>
 *  @copyright 2016 Chargeback
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

// http://doc.prestashop.com/display/PS16/Creating+a+first+module
class Chargeback extends Module
{
    public function __construct()
    {
        $this->name = 'chargeback';
        $this->tab = 'payments_gateways';
        $this->version = '1.0.4';
        $this->author = 'Chargeback.com';
        $this->need_instance = 1;
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
        $this->bootstrap = true;
        $this->module_key = '5d8ae152708f294cdf737e1f7b4ed823';

        parent::__construct();

        $this->displayName = $this->l('Chargeback');
        $this->description = $this->l('Recover revenue lost to chargeback fraud.');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

        // if (!Configuration::get('CHARGEBACK_TOKEN')) {
        //     $this->warning = $this->l('No name provided');
        // }
    }
    public function install()
    {
        if (!parent::install() ||
                // !$this->alterTable('add') ||
                !$this->registerHook('leftColumn') ||
                !$this->registerHook('header') ||
                !$this->installModuleTab('AdminChargeback', array(1=>'Chargebacks'), 0) ||
                !Configuration::updateValue('CHARGEBACK_TOKEN', null) ||
                !Configuration::updateValue('CHARGEBACK_CONNECTED', false)
                ) {
            return false;
        }
        return true;
    }
    public function uninstall()
    {
        if (!parent::uninstall() ||
                // !$this->alterTable('remove') ||
                !$this->uninstallModuleTab('AdminChargeback') ||
                !Configuration::deleteByName('CHARGEBACK_TOKEN') ||
                !Configuration::deleteByName('CHARGEBACK_CONNECTED')
                ) {
            return false;
        }

        return true;
    }
    private function installModuleTab($tabClass, $tabName, $idTabParent)
    {
        $tab = new Tab();
        $tab->name = $tabName;
        $tab->class_name = $tabClass;
        $tab->module = $this->name;
        $tab->id_parent = $idTabParent;
        if (!$tab->save()) {
            return false;
        }
        return true;
    }
    private function uninstallModuleTab($tabClass)
    {
        $idTab = Tab::getIdFromClassName($tabClass);
        if ($idTab != 0) {
            $tab = new Tab($idTab);
            $tab->delete();
            return true;
        }
        return false;
    }
    public function alterTable($method = 'add')
    {
        $sql_base = 'ALTER TABLE ' . _DB_PREFIX_ . 'orders ';
        if ($method == 'add') {
            $sql_action = 'ADD `chargeback_id` VARCHAR (255) NULL';
        } else {
            $sql_action = 'DROP COLUMN `chargeback_id`';
        }
        if (!Db::getInstance()->Execute($sql_base . $sql_action)) {
            return false;
        }
        return true;
    }
    public function getContent()
    {
        $output = '';
        if (Tools::isSubmit('submit'.$this->name)) {
            $my_module_name = (string) Tools::getValue('CHARGEBACK_TOKEN');
            // if (!$my_module_name
            //     || empty($my_module_name)
            //     || !Validate::isGenericName($my_module_name)
            //     )
            //   $output .= $this->displayError($this->l('Invalid auth token'));
            // else
                // {
                    Configuration::updateValue('CHARGEBACK_TOKEN', $my_module_name);
                    $output .= $this->displayConfirmation($this->l('Settings updated'));
                // }
        }
        return $output.$this->renderPage();
    }
    public function renderPage()
    {
        // $configure_page = true;
        $disconnect = Tools::getValue('disconnect');
        $resetToken = Tools::getValue('resetToken');
        $cbReturnStatus = Tools::getValue('cb_return_status');
        $cbError = Tools::getValue('cb_error_message');
        $authToken = Tools::getValue('_cb_auth_token');
        $cbConnected = Configuration::get('CHARGEBACK_CONNECTED');
        $stage = null;

        $conf = ConfigurationCore::get('PS_WEBSERVICE');
        if ($conf && is_object($conf)) {
            $conf->value = 1;
            $conf->save();
        } else {
            $conf = new ConfigurationCore();
            $conf->name = 'PS_WEBSERVICE';
            $conf->value = 1;
            $conf->id_shop_group = null;
            $conf->id_shop = null;
            $conf->save();
        }

        $sql = "SELECT * FROM `"._DB_PREFIX_ .
               "webservice_account` WHERE `description` = 'Chargeback' OR `description` = 'chargeback'";
        $row = Db::getInstance()->getRow($sql);
        $apiKey = $row['key'];
        if (!$apiKey) {
            $wskc = new WebserviceKeyCore();
            $wskc->key = Chargeback::getRandomString(32);
            $wskc->description = 'Chargeback';
            $wskc->add();
            $apiKey = $wskc->id;
            $perms = array(
                    'addresses' => array('GET' => 'on'),
                    'carriers' => array('GET' => 'on'),
                    'cart_rules' => array('GET' => 'on'),
                    'carts' => array('GET' => 'on'),
                    'categories' => array('GET' => 'on'),
                    'combinations' => array('GET' => 'on'),
                    'configurations' => array('GET' => 'off'),
                    'contacts' => array('GET' => 'on'),
                    'content_management_system' => array('GET' => 'off'),
                    'countries' => array('GET' => 'on'),
                    'currencies' => array('GET' => 'on'),
                    'customer_messages' => array('GET' => 'on'),
                    'customer_threads' => array('GET' => 'on'),
                    'customers' => array('GET' => 'on'),
                    'customizations' => array('GET' => 'on'),
                    'deliveries' => array('GET' => 'on'),
                    'employees' => array('GET' => 'off'),
                    'groups' => array('GET' => 'on'),
                    'guests' => array('GET' => 'on'),
                    'image_types' => array('GET' => 'on'),
                    'images' => array('GET' => 'on'),
                    'languages' => array('GET' => 'on'),
                    'manufacturers' => array('GET' => 'on'),
                    'order_carriers' => array('GET' => 'on'),
                    'order_details' => array('GET' => 'on'),
                    'order_discounts' => array('GET' => 'on'),
                    'order_histories' => array('GET' => 'on'),
                    'order_invoices' => array('GET' => 'on'),
                    'order_payments' => array('GET' => 'on'),
                    'order_slip' => array('GET' => 'on'),
                    'order_states' => array('GET' => 'on'),
                    'orders' => array('GET' => 'on'),
                    'price_ranges' => array('GET' => 'on'),
                    'product_customization_fields' => array('GET' => 'on'),
                    'product_feature_values' => array('GET' => 'on'),
                    'product_features' => array('GET' => 'on'),
                    'product_option_values' => array('GET' => 'on'),
                    'product_options' => array('GET' => 'on'),
                    'product_suppliers' => array('GET' => 'on'),
                    'products' => array('GET' => 'on'),
                    'search' => array('GET' => 'off'),
                    'shop_groups' => array('GET' => 'on'),
                    'shop_urls' => array('GET' => 'on'),
                    'shops' => array('GET' => 'on'),
                    'specific_price_rules' => array('GET' => 'on'),
                    'specific_prices' => array('GET' => 'on'),
                    'states' => array('GET' => 'on'),
                    'stock_availables' => array('GET' => 'off'),
                    'stock_movement_reasons' => array('GET' => 'off'),
                    'stock_movements' => array('GET' => 'off'),
                    'stocks' => array('GET' => 'off'),
                    'stores' => array('GET' => 'on'),
                    'suppliers' => array('GET' => 'off'),
                    'supply_order_details' => array('GET' => 'on'),
                    'supply_order_histories' => array('GET' => 'on'),
                    'supply_order_receipt_histories' => array('GET' => 'on'),
                    'supply_order_states' => array('GET' => 'on'),
                    'supply_orders' => array('GET' => 'on'),
                    'tags' => array('GET' => 'on'),
                    'tax_rule_groups' => array('GET' => 'on'),
                    'tax_rules' => array('GET' => 'on'),
                    'taxes' => array('GET' => 'on'),
                    'translated_configurations' => array('GET' => 'off'),
                    'warehouse_product_locations' => array('GET' => 'off'),
                    'warehouses' => array('GET' => 'off'),
                    'weight_ranges' => array('GET' => 'off'),
                    'zones' => array('GET' => 'off'),
            );
            WebserviceKey::setPermissionForAccount($wskc->id, $perms);

        }

        if ($disconnect == 'true' || $resetToken == 'true') {
            $stage = 'nothing';
            $cbConnected = false;
            Configuration::updateValue('CHARGEBACK_CONNECTED', $cbConnected);
            $cbStatus = null;
            Configuration::updateValue('CHARGEBACK_TOKEN', $cbStatus);
            // Delete key from webservice account...
            $sql = "DELETE FROM `"._DB_PREFIX_ .
                   "webservice_account` WHERE `description` = 'Chargeback' OR `description` = 'chargeback'";
            $row = Db::getInstance()->query($sql);
        } elseif ($cbReturnStatus == 'success') {
            $stage = 'connected';
            $cbConnected = true;
            Configuration::updateValue('CHARGEBACK_CONNECTED', $cbConnected);
            $authToken = null;
            Configuration::updateValue('CHARGEBACK_TOKEN', $authToken);
        } elseif ($cbReturnStatus == 'failure') {
            $stage = 'authed';
            $authToken = Configuration::get('CHARGEBACK_TOKEN');
        } elseif ($authToken) {
            $stage = 'authed';
            $authToken = str_replace(' ', '+', $authToken);
            Configuration::updateValue('CHARGEBACK_TOKEN', $authToken);
        } elseif ($cbConnected) {
            $stage = 'connected';
            // can differentiate from the moment we connected via $cbReturnStatus
            // we are connected, not doing anything with the page.
            // i.e. we entered this page in a session after the session we connected
        } else {
            $stage = 'nothing';
            // not connected, not in the process of connecting
        }

        $test = WebserviceKeyCore::keyExists($apiKey);
        $request_path = $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'];
        $apiPath = explode('/admin', $request_path)[0]  . '/api';
        $this->context->smarty->assign(array(
            'test'          => $test,
            'base_url'      => AdminController::$currentIndex,
            'token'         => Tools::getValue('token'),
            // 'our_url'       => $this->context->link->getModuleLink('chargeback','AdminChargebackController'),
            'cbToken'       => $authToken,
            'apiKey'        => $apiKey,
            'cbStatus'      => $cbReturnStatus,
            'cbConnected'   => $cbConnected,
            'cbError'       => $cbError,
            'stage'         => $stage,
            'apiPath'       => $apiPath,
            'link'          => $this->context->link,
            // 'cbUrl'         => 'http://localhost:3000',
            'cbUrl'         => 'https://app.chargeback.com',
            'cbUrl'         => 'https://staging-app.chargeback.com',
            'img_path'      => _PS_MODULE_DIR_.$this->name.'/views/img/connected.png'
        ));
        return $this->context->smarty->fetch(dirname(__FILE__).'/views/templates/admin/content.tpl');
    }
    // public function displayForm()
    // {
    //     $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');
    //         Init Fields form array
    //         $fields_form[0]['form'] = array(
    //             'legend' => array(
    //                 'title' => $this->l('Settings')
    //                 ),
    //             'input' => array(
    //                 array(
    //                     'type' => 'text',
    //                     'label' => $this->l('Chargeback Auth Token'),
    //                     'name' => 'CHARGEBACK_TOKEN',
    //                     'size' => 20,
    //                     'required' => true
    //                     ),
    //                 array(
    //                     'type' => 'text',
    //                     'label' => $this->l('Chargeback Connected'),
    //                     'name' => 'CHARGEBACK_CONNECTED',
    //                     'size' => 20,
    //                     'required' => true
    //                     )
    //                 ),
    //             'submit' => array(
    //                 'title' => $this->l('Save'),
    //                 'class' => 'btn btn-default pull-right'
    //                 )
    //             );

    //     $helper = new HelperForm();

    //     // Module, Token and currentIndex
    //     $helper->module = $this;
    //     $helper->name_controller = $this->name;
    //     $helper->token = Tools::getAdminTokenLite('AdminModules');
    //     $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;

    //     // Language
    //     $helper->default_form_language = $default_lang;
    //     $helper->allow_employee_form_lang = $default_lang;

    //     // title and Toolbar
    //     $helper->title = $this->displayName;
    //     $helper->show_toolbar = true; // false -> remove toolbar
    //     $helper->toolbar_scroll = true; // yes - > Toolbar is always visible on the top of the screen.
    //     $helper->submit_action = 'submit'.$this->name;
    //     $helper->toolbar_btn = array(
    //         'save' =>
    //         array(
    //             'desc' => $this->l('Save'),
    //             'href' => AdminController::$currentIndex.'&configure='.$this->name.'&save'.$this->name.
    //             '&token='.Tools::getAdminTokenLite('AdminModules'),
    //             ),
    //         'back' => array(
    //             'href' => AdminController::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules'),
    //             'desc' => $this->l('Back to list')
    //             )
    //         );

    //     // Load current value
    //     $helper->fields_value['CHARGEBACK_TOKEN'] = Configuration::get('CHARGEBACK_TOKEN');

    //     return $helper->generateForm($fields_form);
    // }

    public static function getRandomString($length = 32)
    {
        $result = Tools::passwdGen($length);
        return $result;
    }
}
