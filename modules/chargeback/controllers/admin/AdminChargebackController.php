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

class AdminChargebackController extends AdminController
{
    public function __construct()
    {
        $this->lang = (!isset($this->context->cookie)
            || !is_object($this->context->cookie)) ?
                (integer) Configuration::get('PS_LANG_DEFAULT') :
                (integer) $this->context->cookie->id_lang;
        parent::__construct();
    }
    public function display()
    {
        parent::display();
    }
    public function renderList()
    {
        $request_path = $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'];
        $apiPath = explode('/admin', $request_path)[0]  . '/api';
        // $token = Tools::getValue('token');
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
                    'configurations' => array('GET' => 'on'),
                    'contacts' => array('GET' => 'on'),
                    'content_management_system' => array('GET' => 'on'),
                    'countries' => array('GET' => 'on'),
                    'currencies' => array('GET' => 'on'),
                    'customer_messages' => array('GET' => 'on'),
                    'customer_threads' => array('GET' => 'on'),
                    'customers' => array('GET' => 'on'),
                    'customizations' => array('GET' => 'on'),
                    'deliveries' => array('GET' => 'on'),
                    'employees' => array('GET' => 'on'),
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
                    'search' => array('GET' => 'on'),
                    'shop_groups' => array('GET' => 'on'),
                    'shop_urls' => array('GET' => 'on'),
                    'shops' => array('GET' => 'on'),
                    'specific_price_rules' => array('GET' => 'on'),
                    'specific_prices' => array('GET' => 'on'),
                    'states' => array('GET' => 'on'),
                    'stock_availables' => array('GET' => 'on'),
                    'stock_movement_reasons' => array('GET' => 'on'),
                    'stock_movements' => array('GET' => 'on'),
                    'stocks' => array('GET' => 'on'),
                    'stores' => array('GET' => 'on'),
                    'suppliers' => array('GET' => 'on'),
                    'supply_order_details' => array('GET' => 'on'),
                    'supply_order_histories' => array('GET' => 'on'),
                    'supply_order_receipt_histories' => array('GET' => 'on'),
                    'supply_order_states' => array('GET' => 'on'),
                    'supply_orders' => array('GET' => 'on'),
                    'tags' => array('GET' => 'on'),
                    'tax_rule_groups' => array('GET' => 'on'),
                    'tax_rules' => array('GET' => 'on'),
                    'taxes' => array('GET' => 'on'),
                    'translated_configurations' => array('GET' => 'on'),
                    'warehouse_product_locations' => array('GET' => 'on'),
                    'warehouses' => array('GET' => 'on'),
                    'weight_ranges' => array('GET' => 'on'),
                    'zones' => array('GET' => 'on'),
            );
            WebserviceKey::setPermissionForAccount($wskc->id, $perms);

        }

        if ($disconnect == 'true' || $resetToken == 'true') {
            $stage = 'nothing';
            $cbConnected = false;
            Configuration::updateValue('CHARGEBACK_CONNECTED', $cbConnected);
            $cbStatus = null;
            Configuration::updateValue('CHARGEBACK_TOKEN', $cbStatus);
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
        $this->context->smarty->assign(array(
            'test'          => $test,
            'base_url'      => AdminController::$currentIndex.'&token='.Tools::getValue('token'),
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
            // 'cbUrl'         => 'https://staging-app.chargeback.com',
            'img_path'      => _PS_MODULE_DIR_.$this->name.'/views/img/connected.png'
        ));
        return $this->context->smarty->fetch(dirname(__FILE__).'/../../views/templates/admin/content.tpl');
    }
}
