<?php
/**
 * 2013-2016 Amazon Advanced Payment APIs Modul
 *
 * for Support please visit www.patworx.de
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
 *  @author    patworx multimedia GmbH <service@patworx.de>
 *  @copyright 2013-2016 patworx multimedia GmbH
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

use PrestaShop\PrestaShop\Core\Payment\PaymentOption;

if (!defined('_PS_VERSION_')) {
    exit();
}

/**
 * ensure the __DIR__ constant is defined for PHP 4.0.6 and newer
 * (@__DIR__ == '__DIR__') && define('__DIR__', realpath(dirname(__FILE__)));
 */
define('CURRENT_MODULE_DIR', realpath(dirname(__FILE__)));

require_once (CURRENT_MODULE_DIR . '/classes/AmazonTransactions.php');
require_once (CURRENT_MODULE_DIR . '/classes/AmazonPaymentsCustomerHelper.php');
require_once (CURRENT_MODULE_DIR . '/classes/AmazonPaymentsAddressHelper.php');

class AmzPayments extends PaymentModule
{

    public $merchant_id;

    public $access_key;

    public $secret_key;

    public $client_id;

    public $region;

    public $lpa_mode;

    public $button_visibility = 1;

    public $environment;
    
    public $order_status_id = 0;

    public $authorization_mode = 'after_checkout';

    public $authorized_status_id = 3;

    public $capture_mode = 'after_shipping';

    public $capture_status_id = 5;

    public $capture_success_status_id = 5;
    
    public $decline_status_id = 0;

    public $provocation = 0;

    public $popup = 1;

    public $shippings_not_allowed = '';

    public $products_not_allowed = '';

    public $allow_guests = 1;

    public $button_size_lpa = 'x-large';

    public $button_color_lpa = 'Gold';

    public $button_color_lpa_navi = 'Gold';

    public $type_login = 'LwA';

    public $type_pay = 'PwA';

    public $ipn_status = 0;

    public $cron_status = 0;

    public $cron_password = '';

    public $send_mails_on_decline = 1;

    public $preselect_create_account = 0;

    public $force_account_creation = 0;

    public $ca_bundle_file;

    private $_postErrors = array();
    
    private $_postSuccess = array();

    private $pfid = 'A1AOZCKI9MBRZA';

    protected static $table_columns = array();

    public static $config_array = array(
        'merchant_id' => 'AMZ_MERCHANT_ID',
        'access_key' => 'ACCESS_KEY',
        'secret_key' => 'SECRET_KEY',
        'client_id' => 'AMZ_CLIENT_ID',
        'region' => 'REGION',
        'lpa_mode' => 'LPA_MODE',
        'button_visibility' => 'BUTTON_VISIBILITY',
        'environment' => 'ENVIRONMENT',
        'authorization_mode' => 'AUTHORIZATION_MODE',
        'order_status_id' => 'AMZ_ORDER_STATUS_ID',
        'authorized_status_id' => 'AUTHORIZED_STATUS_ID',
        'capture_mode' => 'CAPTURE_MODE',
        'capture_status_id' => 'CAPTURE_STATUS_ID',
        'capture_success_status_id' => 'CAPTURE_SUCCESS_STATUS_ID',
        'decline_status_id' => 'AMZ_DECLINE_STATUS_ID',
        'provocation' => 'PROVOCATION',
        'popup' => 'POPUP',
        'shippings_not_allowed' => 'SHIPPINGS_NOT_ALLOWED',
        'products_not_allowed' => 'PRODUCTS_NOT_ALLOWED',
        'allow_guests' => 'ALLOW_GUEST',
        'button_size_lpa' => 'BUTTON_SIZE_LPA',
        'button_color_lpa' => 'BUTTON_COLOR_LPA',
        'button_color_lpa_navi' => 'BUTTON_COLOR_LPA_NAVI',
        'type_login' => 'TYPE_LOGIN',
        'type_pay' => 'TYPE_PAY',
        'ipn_status' => 'IPN_STATUS',
        'cron_status' => 'CRON_STATUS',
        'cron_password' => 'CRON_PASSWORD',
        'send_mails_on_decline' => 'SEND_MAILS_ON_DECLINE',
        'preselect_create_account' => 'PRESELECT_CREATE_ACCOUNT',
        'force_account_creation' => 'FORCE_ACCOUNT_CREATION',
    );

    public function __construct()
    {
        $this->name = 'amzpayments';
        $this->tab = 'payments_gateways';
        $this->version = '3.0.2';
        $this->author = 'patworx multimedia GmbH';
        $this->need_instance = 1;
        
        $this->bootstrap = true;
        $this->module_key = '26d778fa5cb6735a816107ce4345b32d';
        
        $this->ps_versions_compliancy = array(
            'min' => '1.7',
            'max' => _PS_VERSION_
        );
        $this->dependencies = array();
        $this->is_eu_compatible = 1;
        
        $this->has_curl = function_exists('curl_version');
        
        $this->reloadConfigVars();
        
        $this->currencies = true;
        $this->currencies_mode = 'checkbox';
        
        parent::__construct();
        
        $this->displayName = $this->l('Payments Advanced');
        $this->description = $this->l('Simple integration of Amazon Payments for your prestaShop.');
        
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
        
        if (!isset($this->merchant_id) || !isset($this->access_key) || !isset($this->secret_key) || !isset($this->region) || !isset($this->environment)) {
            $this->warning = $this->l('Your Amazon Payments details must be configured before using this module.');
        }
        if (!count(Currency::checkPaymentCurrencies($this->id))) {
            $this->warning = $this->l('No currency has been set for this payment module');
        }
        
        if (isset($this->context->cookie->amz_access_token_set_time)) {
            if ($this->context->cookie->amz_access_token_set_time < time() - 3000) {
                unset($this->context->cookie->amz_access_token);
            }
        }
    }

    private function reloadConfigVars()
    {
        $config = Configuration::getMultiple(self::$config_array);
        foreach (self::$config_array as $class_var => $config_var) {
            if (isset($config[$config_var])) {
                $this->$class_var = $config[$config_var];
            }
        }
    }

    public function getService($override = false, $serviceType = 'service')
    {
        include_once (CURRENT_MODULE_DIR . '/vendor/PayWithAmazon/Client.php');
        $config = array('merchant_id'   => $this->merchant_id,
            'access_key'    => $this->access_key,
            'secret_key'    => $this->secret_key,
            'client_id'     => $this->client_id,
            'region'        => $this->region,
            'sandbox'       => $this->environment == 'SANDBOX' ? true : false);
        
        if ($override && is_array($override)) {
            foreach ($override as $k => $v) {
                $config[$k] = $v;
            }
        }
        
        return new PayWithAmazon\Client($config);
    }

    public function getPfId()
    {
        return $this->pfid;
    }

    public function install()
    {
        if (version_compare(phpversion(), '5.3.0', '<')) {
            return false;
        }
        
        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }
        
        Db::getInstance()->execute('DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'amz_transactions`;');
        Db::getInstance()->execute('DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'amz_orders`;');
        Db::getInstance()->execute('DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'amz_address`;');
        Db::getInstance()->execute('DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'amz_customer`;');
        
        Db::getInstance()->execute('
				CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'amz_transactions` (
				`amz_tx_id` int(11) NOT NULL AUTO_INCREMENT,
				`amz_tx_order_reference` varchar(255) NOT NULL,
				`amz_tx_type` varchar(16) NOT NULL,
				`amz_tx_time` int(11) NOT NULL,
				`amz_tx_expiration` varchar(255) NOT NULL,
				`amz_tx_amount` float NOT NULL,
				`amz_tx_amount_refunded` float NOT NULL,
				`amz_tx_status` varchar(32) NOT NULL,
				`amz_tx_reference` varchar(255) NOT NULL,
				`amz_tx_code` varchar(64) NOT NULL,
				`amz_tx_amz_id` varchar(255) NOT NULL,
				`amz_tx_customer_informed` int(11) NOT NULL,
				`amz_tx_last_change` int(11) NOT NULL,
				`amz_tx_last_update` int(11) NOT NULL,
				`amz_tx_order` int(11) NOT NULL,
				PRIMARY KEY (`amz_tx_id`),
				KEY `amz_tx_order_reference` (`amz_tx_order_reference`),
				KEY `amz_tx_type` (`amz_tx_type`)
		) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
				');
        
        Db::getInstance()->execute('
				CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'amz_orders` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
                `id_order` int(11) NOT NULL,
				`amazon_auth_reference_id` varchar(255) NOT NULL,
				`amazon_authorization_id` varchar(255) NOT NULL,
				`amazon_order_reference_id` varchar(255) NOT NULL,
				`amazon_capture_id` varchar(255) NOT NULL,
				`amazon_capture_reference_id` varchar(255) NOT NULL,
				PRIMARY KEY (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
				');
        
        Db::getInstance()->execute('
				CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'amz_address` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
                `id_address` int(11) NOT NULL,
				`amazon_order_reference_id` varchar(255) NOT NULL,
				PRIMARY KEY (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
				');
        
        Db::getInstance()->execute('
				CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'amz_customer` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
                `id_customer` int(11) NOT NULL,
				`amazon_customer_id` varchar(255) NOT NULL,
				PRIMARY KEY (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
				');

        $this->installOrderStates();

        Configuration::updateValue('BUTTON_VISIBILITY', true);
        Configuration::updateValue('POPUP', true);
        Configuration::updateValue('ALLOW_GUEST', true);
        Configuration::updateValue('ENVIRONMENT', 'LIVE');
        Configuration::updateValue('BUTTON_SIZE_LPA', 'medium');
        Configuration::updateValue('SEND_MAILS_ON_DECLINE', '1');

        return parent::install() &&
            $this->registerHook('paymentOptions') &&
            $this->registerHook('displayProductButtons') &&
            $this->registerHook('displayBackOfficeHeader') &&
            $this->registerHook('displayShoppingCartFooter') &&
            $this->registerHook('displayCustomerLoginFormAfter') &&
            $this->registerHook('displayNav') &&
            $this->registerHook('adminOrder') &&
            $this->registerHook('displayBackOfficeFooter') &&
            $this->registerHook('displayPayment') &&
            $this->registerHook('paymentReturn') &&
            $this->registerHook('payment') &&
            $this->registerHook('header');
    }

    protected function installOrderStates()
    {
        $values_to_insert = array(
            'invoice' => 0,
            'send_email' => 0,
            'module_name' => pSQL($this->name),
            'color' => 'RoyalBlue',
            'unremovable' => 0,
            'hidden' => 0,
            'logable' => 1,
            'delivery' => 0,
            'shipped' => 0,
            'paid' => 0,
            'deleted' => 0,
        );
        if (!Db::getInstance()->insert('order_state', $values_to_insert)) {
            return false;
        }
        $id_order_state = (int) Db::getInstance()->Insert_ID();
        $languages = Language::getLanguages(false);
        foreach ($languages as $language) {
            Db::getInstance()->insert('order_state_lang', array(
                'id_order_state' => $id_order_state,
                'id_lang' => $language['id_lang'],
                'name' => $this->l('Amazon Payments - Authorized'),
                'template' => '',
            ));
        }
        Configuration::updateValue('AUTHORIZED_STATUS_ID', $id_order_state);
        unset($id_order_state);
        
        $values_to_insert = array(
            'invoice' => 0,
            'send_email' => 0,
            'module_name' => pSQL($this->name),
            'color' => 'RoyalBlue',
            'unremovable' => 0,
            'hidden' => 0,
            'logable' => 1,
            'delivery' => 0,
            'shipped' => 0,
            'paid' => 1,
            'deleted' => 0,
        );
        if (!Db::getInstance()->insert('order_state', $values_to_insert)) {
            return false;
        }
        $id_order_state = (int) Db::getInstance()->Insert_ID();
        $languages = Language::getLanguages(false);
        foreach ($languages as $language) {
            Db::getInstance()->insert('order_state_lang', array(
                'id_order_state' => $id_order_state,
                'id_lang' => $language['id_lang'],
                'name' => $this->l('Amazon Payments - Payment received'),
                'template' => '',
            ));
        }
        Configuration::updateValue('CAPTURE_STATUS_ID', $id_order_state);
        unset($id_order_state);
    }

    public function checkTableForColumn($table, $column)
    {
        if (!isset(self::$table_columns[$table][$column])) {
            $res = Db::getInstance()->executeS('SHOW COLUMNS FROM `' . pSQL($table) . '` LIKE \'' . pSQL($column) . '\'');
            if ($res) {
                self::$table_columns[$table][$column] = true;
            } else {
                self::$table_columns[$table][$column] = false;
            }
        }
        return self::$table_columns[$table][$column];
    }

    public function uninstall()
    {
        if (!Configuration::deleteByName('AMZ_MERCHANT_ID') ||
            !Configuration::deleteByName('ACCESS_KEY') ||
            !Configuration::deleteByName('SECRET_KEY') ||
            !Configuration::deleteByName('REGION') ||
            !Configuration::deleteByName('BUTTON_VISIBILITY') ||
            !Configuration::deleteByName('ENVIRONMENT') ||
            !Configuration::deleteByName('AMZ_DECLINE_STATUS_ID') ||
            !Configuration::deleteByName('AUTHORIZATION_MODE') ||
            !Configuration::deleteByName('CAPTURE_MODE') ||
            !Configuration::deleteByName('CAPTURE_STATUS_ID') ||
            !parent::uninstall()
            ) {
            return false;
        }
        return true;
    }

    private function _postValidation()
    {
        if (Tools::isSubmit('submitAmzpaymentsModule')) {
            foreach (self::$config_array as $name => $f) {
                if (Tools::getValue($f) === false) {
                    $this->_postErrors[] = $this->l($name) . ' ' . $this->l(': details are required.');
                }
            }
            if (Tools::getValue('REGION') == '') {
                $this->_postErrors[] = $this->l('Region is wrong.');
            } else {
                $service = $this->getService(array(
                    'merchant_id' => Tools::getValue('AMZ_MERCHANT_ID'),
                    'access_key' => Tools::getValue('ACCESS_KEY'),
                    'sandbox' => Tools::getValue('ENVIRONMENT') == 'SANDBOX',
                    'region' => Tools::getValue('REGION'),
                    'secret_key' => Tools::getValue('SECRET_KEY')
                ));
                $requestParameters = array();
                $requestParameters['amazon_order_reference_id'] = 'S00-0000000-0000000';
                $requestParameters['merchant_id'] = Tools::getValue('AMZ_MERCHANT_ID');
                try {
                    $order_ref_request = $service->getOrderReferenceDetails($requestParameters);
                    $response = $order_ref_request->toArray();
                    if (isset($response['Error']) && $response['ResponseStatus'] == '403') {
                        switch ($response['Error']['Code']) {
                            case 'InvalidAccessKeyId':
                                $this->_postErrors[] = $this->l('MWS Access Key is wrong.');
                                break;
                        
                            case 'SignatureDoesNotMatch':
                                $this->_postErrors[] = $this->l('MWS Secret Key is wrong.');
                                break;
                        
                            case 'InvalidParameterValue':
                                if (strpos($response['Error']['Message'], 'Invalid seller id') !== false) {
                                    $this->_postErrors[] = $this->l('Merchant ID is wrong.');
                                }
                                break;
                        }
                    }
                } catch (Exception $e) {
                    $this->_postErrors[] = $this->l('There is a temporary error, no connect possible.');
                }
            }
        }
    }

    private function _postProcess()
    {
        if (Tools::isSubmit('submitAmzpaymentsModule')) {
            foreach (self::$config_array as $f => $conf_key) {
                Configuration::updateValue($conf_key, trim(Tools::getValue($conf_key)));
            }
        }
        $this->_postSuccess[] = $this->l('Settings updated');
    }

    private function _displayForm()
    {
        $helper = new HelperForm();
        
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);
        
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitAmzpaymentsModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false) . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );
        
        return $helper->generateForm(array(
            $this->getConfigForm()
        ));
    }

    protected function getPossibleRegionEntries()
    {
        return 'DE, UK, US, FR, IT, ES';
    }

    protected function getCronURL()
    {
        return $this->context->link->getModuleLink('amzpayments', 'cron.php', array('pw' => $this->cron_password));
    }

    protected function getIPNURL()
    {
        return str_replace('http://', 'https://', $this->context->link->getModuleLink('amzpayments', 'ipn.php'));
    }

    protected function getAllowedReturnUrls($type = 1, $joined = false)
    {
        $urls = array();
        $language_ids = Language::getLanguages(true, false, true);
        foreach ($language_ids as $id_lang) {
            $url = str_replace('http://', 'https://', $this->context->link->getModuleLink('amzpayments', 'processlogin', array(), null, (int)$id_lang));
            if ($type == 2) {
                $url .= '?toCheckout=1';
            }
            $urls[] = $url;
        }
        if ($joined) {
            return join($joined, $urls);
        } else {
            return $urls;
        }
    }

    public function getConfigFormValues()
    {
        $return = array();
        foreach (self::$config_array as $name => $key) {
            $return[$key] = Configuration::get($key);
        }
        return $return;
    }

    public function getConfigForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-cogs'
                ),
                'input' => array(
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'prefix' => '<i class="icon icon-tag"></i>',
                        'name' => 'AMZ_MERCHANT_ID',
                        'label' => $this->l('merchant_id')
                    ),
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'prefix' => '<i class="icon icon-tag"></i>',
                        'name' => 'ACCESS_KEY',
                        'label' => $this->l('access_key')
                    ),
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'prefix' => '<i class="icon icon-tag"></i>',
                        'name' => 'SECRET_KEY',
                        'label' => $this->l('secret_key')
                    ),
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'prefix' => '<i class="icon icon-tag"></i>',
                        'name' => 'AMZ_CLIENT_ID',
                        'label' => $this->l('client_id')
                    ),
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'prefix' => '<i class="icon icon-tag"></i>',
                        'name' => 'REGION',
                        'hint' => $this->l('Allowed values: ') . $this->getPossibleRegionEntries(),
                        'label' => $this->l('region')
                    ),
                    array(
                        'col' => 3,
                        'type' => 'select',
                        'prefix' => '<i class="icon icon-tag"></i>',
                        'name' => 'LPA_MODE',
                        'label' => $this->l('lpa_mode'),
                        'options' => array(
                            'query' => array(
                                array(
                                    'id_lpa_mode' => 'pay',
                                    'name' => $this->l('mode_pay')
                                ),
                                array(
                                    'id_lpa_mode' => 'login',
                                    'name' => $this->l('mode_login')
                                ),
                                array(
                                    'id_lpa_mode' => 'login_pay',
                                    'name' => $this->l('mode_login_pay')
                                )
                            ),
                            'id' => 'id_lpa_mode',
                            'name' => 'name'
                        )
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('button_visibility'),
                        'name' => 'BUTTON_VISIBILITY',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on_bv',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off_bv',
                                'value' => '0',
                                'label' => $this->l('Disabled')
                            )
                        )
                    ),
                    array(
                        'col' => 3,
                        'type' => 'select',
                        'prefix' => '<i class="icon icon-tag"></i>',
                        'name' => 'ENVIRONMENT',
                        'label' => $this->l('environment'),
                        'options' => array(
                            'query' => array(
                                array(
                                    'id_lpa_environment' => 'SANDBOX',
                                    'name' => $this->l('Test mode')
                                ),
                                array(
                                    'id_lpa_environment' => 'LIVE',
                                    'name' => $this->l('Live mode')
                                )
                            ),
                            'id' => 'id_lpa_environment',
                            'name' => 'name'
                        )
                    ),
                    array(
                        'col' => 3,
                        'type' => 'select',
                        'prefix' => '<i class="icon icon-tag"></i>',
                        'name' => 'AUTHORIZATION_MODE',
                        'label' => $this->l('authorization_mode'),
                        'options' => array(
                            'query' => array(
                                array(
                                    'id_lpa_auth_mode' => 'fast_auth',
                                    'name' => $this->l('during checkout / before completing the order')
                                ),
                                array(
                                    'id_lpa_auth_mode' => 'after_checkout',
                                    'name' => $this->l('immediately after the order')
                                ),
                                array(
                                    'id_lpa_auth_mode' => 'manually',
                                    'name' => $this->l('manual')
                                )
                            ),
                            'id' => 'id_lpa_auth_mode',
                            'name' => 'name'
                        )
                    ),
                    array(
                        'col' => 3,
                        'type' => 'select',
                        'prefix' => '<i class="icon icon-tag"></i>',
                        'name' => 'AMZ_ORDER_STATUS_ID',
                        'label' => $this->l('Status after order'),
                        'options' => array(
                            'query' => array_merge(array(array('id_order_state' => 0, 'id_lang' => (int) Configuration::get('PS_LANG_DEFAULT'), 'name' => '')), OrderState::getOrderStates((int) Configuration::get('PS_LANG_DEFAULT'))),
                            'id' => 'id_order_state',
                            'name' => 'name'
                        )
                    ),
                    array(
                        'col' => 3,
                        'type' => 'select',
                        'prefix' => '<i class="icon icon-tag"></i>',
                        'name' => 'AUTHORIZED_STATUS_ID',
                        'label' => $this->l('authorized_status_id'),
                        'options' => array(
                            'query' => OrderState::getOrderStates((int) Configuration::get('PS_LANG_DEFAULT')),
                            'id' => 'id_order_state',
                            'name' => 'name'
                        )
                    ),
                    array(
                        'col' => 3,
                        'type' => 'select',
                        'prefix' => '<i class="icon icon-tag"></i>',
                        'name' => 'CAPTURE_MODE',
                        'label' => $this->l('capture_mode'),
                        'options' => array(
                            'query' => array(
                                array(
                                    'id_lpa_capt_mode' => 'after_shipping',
                                    'name' => $this->l('after delivery')
                                ),
                                array(
                                    'id_lpa_capt_mode' => 'after_auth',
                                    'name' => $this->l('directly after the authorisation')
                                ),
                                array(
                                    'id_lpa_capt_mode' => 'manually',
                                    'name' => $this->l('manual')
                                )
                            ),
                            'id' => 'id_lpa_capt_mode',
                            'name' => 'name'
                        )
                    ),
                    array(
                        'col' => 3,
                        'type' => 'select',
                        'prefix' => '<i class="icon icon-tag"></i>',
                        'name' => 'CAPTURE_STATUS_ID',
                        'label' => $this->l('capture_status_id'),
                        'options' => array(
                            'query' => OrderState::getOrderStates((int) Configuration::get('PS_LANG_DEFAULT')),
                            'id' => 'id_order_state',
                            'name' => 'name'
                        )
                    ),
                    array(
                        'col' => 3,
                        'type' => 'select',
                        'prefix' => '<i class="icon icon-tag"></i>',
                        'name' => 'CAPTURE_SUCCESS_STATUS_ID',
                        'label' => $this->l('capture_success_status_id'),
                        'options' => array(
                            'query' => OrderState::getOrderStates((int) Configuration::get('PS_LANG_DEFAULT')),
                            'id' => 'id_order_state',
                            'name' => 'name'
                        )
                    ),
                    array(
                        'col' => 3,
                        'type' => 'select',
                        'prefix' => '<i class="icon icon-tag"></i>',
                        'name' => 'AMZ_DECLINE_STATUS_ID',
                        'label' => $this->l('decline_status_id'),
                        'options' => array(
                            'query' => array_merge(array(array('id_order_state' => 0, 'id_lang' => (int) Configuration::get('PS_LANG_DEFAULT'), 'name' => '')), OrderState::getOrderStates((int) Configuration::get('PS_LANG_DEFAULT'))),
                            'id' => 'id_order_state',
                            'name' => 'name'
                        )
                    ),
                    array(
                        'col' => 3,
                        'type' => 'select',
                        'prefix' => '<i class="icon icon-tag"></i>',
                        'name' => 'PROVOCATION',
                        'label' => $this->l('provocation'),
                        'options' => array(
                            'query' => array(
                                array(
                                    'id_lpa_prov' => '0',
                                    'name' => $this->l('No')
                                ),
                                array(
                                    'id_lpa_prov' => 'hard_decline',
                                    'name' => $this->l('Hard Decline')
                                ),
                                array(
                                    'id_lpa_prov' => 'soft_decline',
                                    'name' => $this->l('Soft Decline (2min)')
                                ),
                                array(
                                    'id_lpa_prov' => 'capture_decline',
                                    'name' => $this->l('Capture Decline')
                                )
                            ),
                            'id' => 'id_lpa_prov',
                            'name' => 'name'
                        )
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('popup'),
                        'name' => 'POPUP',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on_popup',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off_popup',
                                'value' => '0',
                                'label' => $this->l('Disabled')
                            )
                        )
                    ),
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'prefix' => '<i class="icon icon-tag"></i>',
                        'name' => 'SHIPPINGS_NOT_ALLOWED',
                        'label' => $this->l('shippings_not_allowed')
                    ),
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'prefix' => '<i class="icon icon-tag"></i>',
                        'name' => 'PRODUCTS_NOT_ALLOWED',
                        'label' => $this->l('products_not_allowed')
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('allow_guests'),
                        'name' => 'ALLOW_GUEST',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on_guests',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off_guests',
                                'value' => '0',
                                'label' => $this->l('Disabled')
                            )
                        )
                    ),
                    array(
                        'col' => 3,
                        'type' => 'select',
                        'prefix' => '<i class="icon icon-tag"></i>',
                        'name' => 'BUTTON_SIZE_LPA',
                        'label' => $this->l('button_size_lpa'),
                        'options' => array(
                            'query' => array(
                                array(
                                    'id_buttonsize' => 'small',
                                    'name' => $this->l('small')
                                ),
                                array(
                                    'id_buttonsize' => 'medium',
                                    'name' => $this->l('normal')
                                ),
                                array(
                                    'id_buttonsize' => 'large',
                                    'name' => $this->l('big')
                                ),
                                array(
                                    'id_buttonsize' => 'x-large',
                                    'name' => $this->l('very big')
                                )
                            ),
                            'id' => 'id_buttonsize',
                            'name' => 'name'
                        )
                    ),
                    array(
                        'col' => 3,
                        'type' => 'select',
                        'prefix' => '<i class="icon icon-tag"></i>',
                        'name' => 'BUTTON_COLOR_LPA',
                        'label' => $this->l('button_color_lpa'),
                        'options' => array(
                            'query' => array(
                                array(
                                    'id_buttonsize' => 'Gold',
                                    'name' => $this->l('Amazon yellow')
                                ),
                                array(
                                    'id_buttonsize' => 'LightGray',
                                    'name' => $this->l('Light grey')
                                ),
                                array(
                                    'id_buttonsize' => 'DarkGray',
                                    'name' => $this->l('Dark grey')
                                )
                            ),
                            'id' => 'id_buttonsize',
                            'name' => 'name'
                        )
                    ),
                    array(
                        'col' => 3,
                        'type' => 'select',
                        'prefix' => '<i class="icon icon-tag"></i>',
                        'name' => 'BUTTON_COLOR_LPA_NAVI',
                        'label' => $this->l('button_color_lpa_navi'),
                        'options' => array(
                            'query' => array(
                                array(
                                    'id_buttonsize' => 'Gold',
                                    'name' => $this->l('Amazon yellow')
                                ),
                                array(
                                    'id_buttonsize' => 'LightGray',
                                    'name' => $this->l('Light grey')
                                ),
                                array(
                                    'id_buttonsize' => 'DarkGray',
                                    'name' => $this->l('Dark grey')
                                )
                            ),
                            'id' => 'id_buttonsize',
                            'name' => 'name'
                        )
                    ),
                    array(
                        'col' => 3,
                        'type' => 'select',
                        'prefix' => '<i class="icon icon-tag"></i>',
                        'name' => 'TYPE_LOGIN',
                        'label' => $this->l('type_login'),
                        'options' => array(
                            'query' => array(
                                array(
                                    'id_buttonsize' => 'LwA',
                                    'name' => $this->l('Login with Amazon')
                                ),
                                array(
                                    'id_buttonsize' => 'Login',
                                    'name' => $this->l('Login')
                                ),
                                array(
                                    'id_buttonsize' => 'A',
                                    'name' => $this->l('Just an "A"')
                                )
                            ),
                            'id' => 'id_buttonsize',
                            'name' => 'name'
                        )
                    ),
                    array(
                        'col' => 3,
                        'type' => 'select',
                        'prefix' => '<i class="icon icon-tag"></i>',
                        'name' => 'TYPE_PAY',
                        'label' => $this->l('type_pay'),
                        'options' => array(
                            'query' => array(
                                array(
                                    'id_buttonsize' => 'PwA',
                                    'name' => $this->l('Pay with Amazon')
                                ),
                                array(
                                    'id_buttonsize' => 'Pay',
                                    'name' => $this->l('Pay')
                                ),
                                array(
                                    'id_buttonsize' => 'A',
                                    'name' => $this->l('Just an "A"')
                                )
                            ),
                            'id' => 'id_buttonsize',
                            'name' => 'name'
                        )
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('ipn_status'),
                        'name' => 'IPN_STATUS',
                        'is_bool' => true,
                        'desc' => $this->l('Use this URL for IPN: ') . ' ' . $this->getIPNURL(),
                        'values' => array(
                            array(
                                'id' => 'active_on_ipn',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off_ipn',
                                'value' => '0',
                                'label' => $this->l('Disabled')
                            )
                        )
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('cron_status'),
                        'name' => 'CRON_STATUS',
                        'is_bool' => true,
                        'desc' => $this->l('Use this URL for your cronjob: ') . ' ' . $this->getCronURL(),
                        'values' => array(
                            array(
                                'id' => 'active_on_cron',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off_cron',
                                'value' => '0',
                                'label' => $this->l('Disabled')
                            )
                        )
                    ),
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'prefix' => '<i class="icon icon-tag"></i>',
                        'name' => 'CRON_PASSWORD',
                        'label' => $this->l('cron_password')
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('send_mails_on_decline'),
                        'name' => 'SEND_MAILS_ON_DECLINE',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on_send_decline',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off_send_decline',
                                'value' => '0',
                                'label' => $this->l('Disabled')
                            )
                        )
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('preselect_create_account'),
                        'name' => 'PRESELECT_CREATE_ACCOUNT',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on_preselect',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off_preselect',
                                'value' => '0',
                                'label' => $this->l('Disabled')
                            )
                        )
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('force_account_creation'),
                        'name' => 'FORCE_ACCOUNT_CREATION',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on_force',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off_force',
                                'value' => '0',
                                'label' => $this->l('Disabled')
                            )
                        )
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save')
                )
            )
        );
    }

    public function getContent()
    {
        
        if (Tools::isSubmit('submitAmzpaymentsModule')) {
            $this->_postValidation();
            if (!count($this->_postErrors)) {
                $this->_postProcess();
            } else {
                $this->context->smarty->assign(array('postErrors' => $this->_postErrors));
            }
            if (count($this->_postSuccess)) {
                $this->context->smarty->assign(array('postSuccess' => $this->_postSuccess));
            }
        }
        
        $this->context->smarty->assign('displayName', $this->displayName);
        $this->context->smarty->assign('module_name', $this->name);
        $this->context->smarty->assign('current_version', $this->version);
        $this->context->smarty->assign('allowed_return_url_1', $this->getAllowedReturnUrls(1));
        $this->context->smarty->assign('allowed_return_url_2', $this->getAllowedReturnUrls(2));
        $this->context->smarty->assign('allowed_js_origins', str_replace('http://', 'https://', _PS_BASE_URL_));
        
        $register_link = 'https://sellercentral-europe.amazon.com/hz/me/sp/redirect?ld=';

        $this->context->smarty->assign('lang_iso_code', $this->context->language->iso_code);
        switch ($this->context->language->iso_code) {
            case 'de':
                $register_link.= 'SPEXDEAPA-PrestashopPL';
                $let_customer_know_link = 'https://payments.amazon.de/merchant/tools?ld=SPEXDEAPA-prestashop-2016-03-Configuration';
                $integration_guide_link = 'http://www.patworx.de/LoginUndBezahlen/MitAmazon/PrestaShop/Dokumentation';
                $youtube_video_link = 'https://www.youtube.com/watch?v=pbv64mDMqc8';
                $youtube_video_embed_link = 'https://www.youtube.com/embed/pbv64mDMqc8?rel=0&showinfo=0';
            break;
            case 'en':
                if (isset($this->context->language->local) && Tools::strtolower($this->context->language->local) == 'en-us') {
                    $register_link.= 'SPEXUSAPA-PrestashopPL';
                } else {                    
                    $register_link.= 'SPEXUKAPA-PrestashopPL';
                }
                $let_customer_know_link = 'https://payments.amazon.co.uk/merchant/tools?ld=SPEXUKAPA-prestashop-2016-03-Configuration';
                $integration_guide_link = 'http://www.patworx.de/LoginAndPay/WithAmazon/PrestaShopUK/Documentation';
                $youtube_video_link = false;
                $youtube_video_embed_link = false;
            break;
            case 'fr':
                $register_link.= 'SPEXFRAPA-PrestashopPL';
                $let_customer_know_link = 'https://images-na.ssl-images-amazon.com/images/G/03/amazonservices/payments/website/Amazon_Payments_MarketingGuide_UK_July2015_OLD._V283105627_.pdf?ld=SPEXFRAPA-prestashop-CP-DP';
                $integration_guide_link = 'http://www.patworx.de/LoginAndPay/WithAmazon/PrestaShopUK/Documentation?ld=SPEXFRAPA-prestashop-CP-DP';
                $youtube_video_link = false;
                $youtube_video_embed_link = false;
            break;
            case 'it':
                $register_link.= 'SPEXITAPA-PrestashopPL';
                $let_customer_know_link = 'https://images-na.ssl-images-amazon.com/images/G/03/amazonservices/payments/website/Amazon_Payments_MarketingGuide_UK_July2015_OLD._V283105627_.pdf?ld=SPEXITAPA-prestashop-CP-DP';
                $integration_guide_link = 'http://www.patworx.de/LoginAndPay/WithAmazon/PrestaShopUK/Documentation?ld=SPEXITAPA-prestashop-CP-DP';
                $youtube_video_link = false;
                $youtube_video_embed_link = false;
            break;
            case 'es':
                $register_link.= 'SPEXESAPA-PrestashopPL';
                $let_customer_know_link = 'https://payments.amazon.co.uk/merchant/tools?ld=SPEXUKAPA-prestashop-2016-03-Configuration';
                $integration_guide_link = 'http://www.patworx.de/LoginAndPay/WithAmazon/PrestaShopUK/Documentation';
                $youtube_video_link = false;
                $youtube_video_embed_link = false;
            break;
            default:
                $register_link.= 'SPEXDEAPA-PrestashopPL';
                $let_customer_know_link = 'https://payments.amazon.co.uk/merchant/tools?ld=SPEXUKAPA-prestashop-2016-03-Configuration';
                $integration_guide_link = 'http://www.patworx.de/LoginAndPay/WithAmazon/PrestaShopUK/Documentation';
                $youtube_video_link = false;
                $youtube_video_embed_link = false;                
            break;
        }

        $this->context->smarty->assign('register_link', $register_link);
        $this->context->smarty->assign('let_customer_know_link', $let_customer_know_link);
        $this->context->smarty->assign('youtube_video_link', $youtube_video_link);
        $this->context->smarty->assign('youtube_video_embed_link', $youtube_video_embed_link);
        $this->context->smarty->assign('integration_guide_link', $integration_guide_link);
        
        $this->context->smarty->assign('use_simple_path', true);
        $simple_path_data = array('spId' => $this->getPfId(),
            'uniqueId' => Tools::encryptIV('amzPaymentsSimplePath'),
            'locale' =>  $this->getLocalCodeForSimplePath(),            
            'loginRedirectURLs_1' => $this->getAllowedReturnUrls(1),
            'loginRedirectURLs_2' => $this->getAllowedReturnUrls(2),
            'allowedLoginDomains' => str_replace('http://', 'https://', _PS_BASE_URL_),
            'storeDescription' => Configuration::get('PS_SHOP_NAME'),
            'language' => $this->getLanguageCodeForSimplePath(),
            'returnMethod' => 'GET',
            'Source' => 'SPPL',
            'sandboxMerchantIPNURL' => $this->getIPNURL(),
            'productionMerchantIPNURL' => $this->getIPNURL(),
        );
        
        $this->context->smarty->assign('simple_path', $simple_path_data);
        
        $this->reloadConfigVars();
        $this->context->smarty->assign('module_dir', $this->_path);
        $this->context->smarty->assign('configform', $this->_displayForm());
        
        $output = $this->context->smarty->fetch($this->local_path . 'views/templates/admin/configuration.tpl');
        
        return $output;
    }

    public function hookDisplayNav()
    {
        if ($this->lpa_mode != 'pay' &&
            !$this->context->customer->isLogged() &&
            ((isset($this->context->controller->module->name) &&
              $this->context->controller->module->name != 'amzpayments') ||
              !(isset($this->context->controller->module->name)))
            ) {
            $this->smarty->assign(array(
                'button_hidden' => $this->button_visibility == '0'
            ));
            return $this->display(__FILE__, 'views/templates/hooks/displaynav.tpl');
        }
        return '';
    }
    
    public function hookDisplayCustomerLoginFormAfter()
    {
        if ($this->lpa_mode != 'pay' && !$this->context->customer->isLogged()) {
            $this->smarty->assign(array(
                'button_hidden' => $this->button_visibility == '0'
            ));
            return $this->display(__FILE__, 'views/templates/hooks/display_after_login_form.tpl');
        }
        return '';
    }

    public function hookDisplayBackOfficeHeader()
    {
        $this->context->controller->addJquery();
        $this->context->controller->addJS(($this->_path) . 'views/js/admin.js');
        $this->context->controller->addCSS(($this->_path) . 'views/css/admin.css');
    }
    
    public function hookDisplayBackOfficeFooter()
    {
        $this->context->controller->addCSS(($this->_path) . 'views/css/admin.css');
        if ($this->capture_mode == 'after_shipping') {
            $this->shippingCapture();
        }
    }

    public function getRegionalCodeForURL()
    {
        if (in_array(Tools::strtolower($this->region), array('de', 'fr', 'it', 'es'))) {
            return 'de';
        } elseif (Tools::strtolower($this->region) == 'uk') {
            return 'uk';
        } elseif (Tools::strtolower($this->region) == 'us') {
            return 'us';
        }
        return 'de';
    }

    private function getLocalCodeForSimplePath()
    {
        $currency = Context::getContext()->currency;        
        if ($currency->iso_code == 'EUR') {
            return 'EUR';
        } elseif ($currency->iso_code == 'GBP') {
            return 'GBP';
        } elseif ($currency->iso_code == 'USD') {
            return 'USD';
        }
        return 'USD';
    }

    public function getButtonURL()
    {
        $this->registerHook('paymentReturn');
        if ($this->environment == 'SANDBOX') {
            if (in_array(Tools::strtolower($this->region), array('de', 'fr', 'it', 'es'))) {
                return 'https://payments-sandbox.amazon.de/gp/widgets/button';
            } elseif (Tools::strtolower($this->region) == 'uk') {
                return 'https://payments-sandbox.amazon.co.uk/gp/widgets/button';
            } elseif (Tools::strtolower($this->region) == 'us') {
                return 'https://payments-sandbox.amazon.com/gp/widgets/button';
            }
        } else {
            if (in_array(Tools::strtolower($this->region), array('de', 'fr', 'it', 'es'))) {
                return 'https://payments.amazon.de/gp/widgets/button';
            } elseif (Tools::strtolower($this->region) == 'uk') {
                return 'https://payments.amazon.co.uk/gp/widgets/button';
            } elseif (Tools::strtolower($this->region) == 'us') {
                return 'https://payments.amazon.com/gp/widgets/button';
            }
        }
    }

    public function getLpaApiUrl()
    {
        if ($this->environment == 'SANDBOX') {
            if (in_array(Tools::strtolower($this->region), array('de', 'fr', 'it', 'es'))) {
                return 'https://api.sandbox.amazon.de';
            } elseif (Tools::strtolower($this->region) == 'uk') {
                return 'https://api.sandbox.amazon.co.uk';
            } elseif (Tools::strtolower($this->region) == 'us') {
                return 'https://api.sandbox.amazon.com';
            }
        } else {
            if (in_array(Tools::strtolower($this->region), array('de', 'fr', 'it', 'es'))) {
                return 'https://api.amazon.de';
            } elseif (Tools::strtolower($this->region) == 'uk') {
                return 'https://api.amazon.co.uk';
            } elseif (Tools::strtolower($this->region) == 'us') {
                return 'https://api.amazon.com';
            }
        }
    }

    protected function checkForTemporarySessionVarsAndKillThem()
    {
        $need_update = false;
        if (isset($this->context->cart->id_address_delivery)) {
            $check_address = new Address((int) $this->context->cart->id_address_delivery);
            if ($check_address->lastname == 'amzLastname' ||
                $check_address->firstname == 'amzFirstname' ||
                $check_address->address1 == 'amzAddress1'
                ) {
                $check_address->delete();
                $this->context->cart->id_address_delivery = 0;

                $sql = 'UPDATE `'._DB_PREFIX_.'cart_product`
                SET `id_address_delivery` = NULL
                WHERE  `id_cart` = '.(int)$this->context->cart->id;
                Db::getInstance()->execute($sql);

                $sql = 'UPDATE `'._DB_PREFIX_.'customization`
                SET `id_address_delivery` = NULL
                WHERE  `id_cart` = '.(int)$this->context->cart->id;
                Db::getInstance()->execute($sql);

                $need_update = true;
            }
        }
        if (isset($this->context->cart->id_address_invoice)) {
            $check_address = new Address((int) $this->context->cart->id_address_invoice);
            if ($check_address->lastname == 'amzLastname' ||
                $check_address->firstname == 'amzFirstname' ||
                $check_address->address1 == 'amzAddress1'
                ) {
                $check_address->delete();
                $this->context->cart->id_address_invoice = 0;

                $sql = 'UPDATE `'._DB_PREFIX_.'cart_product`
                SET `id_address_delivery` = NULL
                WHERE  `id_cart` = '.(int)$this->context->cart->id;
                Db::getInstance()->execute($sql);

                $sql = 'UPDATE `'._DB_PREFIX_.'customization`
                SET `id_address_delivery` = NULL
                WHERE  `id_cart` = '.(int)$this->context->cart->id;
                Db::getInstance()->execute($sql);

                $need_update = true;
            }
        }
        if ($need_update) {
            $this->context->cart->update();
        }
    }
    
    public function hookPaymentOptions($params)
    {
        if (!$this->active) {
            return;
        }
        
        if ($this->lpa_mode == 'login') {
            return;
        }
        
        if ($this->button_visibility == '0') {
            return;
        }
    
        if (!$this->checkCurrency($params['cart'])) {
            return;
        }
    
        $payment_options = [
            $this->getEmbeddedPaymentOption(),
        ];
    
        return $payment_options;
    }
    
    public function getEmbeddedPaymentOption()
    {
        $this->context->smarty->assign(array(
            'this_path' => $this->_path,
            'this_hide_button' => $this->button_visibility == '0',
            'this_path_amzpayments' => $this->_path,
            'this_path_ssl' => Tools::getShopDomainSsl(true, true) . __PS_BASE_URI__ . 'modules/' . $this->name . '/'
        ));

        $embeddedOption = new PaymentOption();
        $embeddedOption->setCallToActionText($this->l('Pay with Amazon'))
        ->setForm('')
        ->setModuleName($this->name)
        ->setAdditionalInformation($this->context->smarty->fetch('module:amzpayments/views/templates/hooks/embedded_payment_option.tpl'))
        ->setLogo(Media::getMediaPath(_PS_MODULE_DIR_.$this->name.'/payment.jpg'));
        
        return $embeddedOption;
    }
    
    public function hookDisplayExpressCheckout($params)
    {
        return $this->hookDisplayShoppingCartFooter($params);
    }

    public function hookDisplayShoppingCartFooter($params)
    {
        $show_amazon_button = true;
        if (isset($this->context->controller->module)) {
            if ($this->context->controller->module->name == 'amzpayments') {
                $show_amazon_button = false;
            }
        }
        
        if (!$this->checkIfCurrencyMatchesModuleRegion()) {
            $show_amazon_button = false;
        }
        
        if (($this->allow_guests == '0') && (!$this->context->customer->isLogged())) {
            $show_amazon_button = false;
        }
        
        if (!$this->checkCurrency($params['cart'])) {
            $show_amazon_button = false;
        }
        
        if ($this->lpa_mode == 'login') {
            $show_amazon_button = false;
        }
        
        $summary = $this->context->cart->getSummaryDetails();
        
        if ($this->context->cart->nbProducts() == 0) {
            $show_amazon_button = false;    
        }
        
        foreach ($summary['products'] as &$product_update) {
            $product_id = (int)(isset($product_update['id_product']) ? $product_update['id_product'] : $product_update['product_id']);
            if ($this->productNotAllowed($product_id)) {
                $show_amazon_button = false;
            }
        }
        if ($show_amazon_button) {
            $this->context->smarty->assign('sellerID', $this->merchant_id);
            $this->context->smarty->assign('btn_url', $this->getButtonURL());
            $this->context->smarty->assign('hide_button', $this->button_visibility == '0');
            return $this->display(__FILE__, 'views/templates/hooks/amzpayments.tpl');
        }
    }
    
    public function hookDisplayProductButtons($params)
    {
        $show_amazon_button = true;
        
        if (!$this->checkIfCurrencyMatchesModuleRegion()) {
            $show_amazon_button = false;
        }
        
        if (($this->allow_guests == '0') && (!$this->context->customer->isLogged())) {
            $show_amazon_button = false;
        }
        
        if (!$this->checkCurrency($params['cart'])) {
            $show_amazon_button = false;
        }
        
        if ($this->lpa_mode == 'login') {
            $show_amazon_button = false;
        }
        if ($this->productNotAllowed($params['product']['id_product'])) {
            $show_amazon_button = false;
        }
        
        if ($show_amazon_button) {
            $this->context->smarty->assign('id_product_amz_widget', $params['product']['id_product']);
            $this->context->smarty->assign('sellerID', $this->merchant_id);
            $this->context->smarty->assign('btn_url', $this->getButtonURL());
            $this->context->smarty->assign('hide_button', $this->button_visibility == '0');
            return $this->display(__FILE__, 'views/templates/hooks/display_product_button.tpl');
        }
    }

    public function hookPayment($params)
    {
        if (!$this->active) {
            return;
        }
        
        if (!$this->checkCurrency($params['cart'])) {
            return;
        }
        
        $this->smarty->assign(array(
            'this_path' => $this->_path,
            'this_hide_button' => $this->button_visibility == '0',
            'this_path_amzpayments' => $this->_path,
            'this_path_ssl' => Tools::getShopDomainSsl(true, true) . __PS_BASE_URI__ . 'modules/' . $this->name . '/'
        ));
        return $this->display(__FILE__, 'views/templates/hooks/payment.tpl');
    }

    public function checkIfCurrencyMatchesModuleRegion()
    {
        $currency = new Currency((int) (Context::getContext()->cart->id_currency));
        
        if (Tools::strtolower($this->region) == 'de' && Tools::strtoupper($currency->iso_code) == 'EUR') {
            return true;
        } elseif (Tools::strtolower($this->region) == 'fr' && Tools::strtoupper($currency->iso_code) == 'EUR') {
            return true;
        } elseif (Tools::strtolower($this->region) == 'it' && Tools::strtoupper($currency->iso_code) == 'EUR') {
            return true;
        } elseif (Tools::strtolower($this->region) == 'es' && Tools::strtoupper($currency->iso_code) == 'EUR') {
            return true;
        } elseif (Tools::strtolower($this->region) == 'uk' && Tools::strtoupper($currency->iso_code) == 'GBP') {
            return true;
        } elseif (Tools::strtolower($this->region) == 'us' && Tools::strtoupper($currency->iso_code) == 'USD') {
            return true;
        }
        return false;
    }

    public function checkCurrency($cart)
    {
        $currency_order = new Currency((int) ($cart->id_currency));
        $currencies_module = $this->getCurrency((int) $cart->id_currency);
        
        if (is_array($currencies_module)) {
            foreach ($currencies_module as $currency_module) {
                if ($currency_order->id == $currency_module['id_currency']) {
                    return true;
                }
            }
        }
        return false;
    }

    protected function productNotAllowed($product_id)
    {
        if ($this->products_not_allowed != '') {
            $products_not_allowed_ids = explode(',', $this->products_not_allowed);
            foreach ($products_not_allowed_ids as $k => $v) {
                $products_not_allowed_ids[$k] = (int) $v;
            }
            if (in_array($product_id, $products_not_allowed_ids)) {
                return true;
            }
        }
    }

    public function hookDisplayPayment($params)
    {
        return $this->hookPayment($params);
    }

    public function hookDisplayHeader($params)
    {
        $this->context->controller->addJquery();
        if (Tools::getValue('controller') == 'order') {
            $this->checkForTemporarySessionVarsAndKillThem();
        }
        
        $show_amazon_button = true;
        if (($this->allow_guests == '0') && (!$this->context->customer->isLogged())) {
            $show_amazon_button = false;
        }
        
        if (!$this->checkCurrency($params['cart'])) {
            $show_amazon_button = false;
        }
        
        if (!$this->checkIfCurrencyMatchesModuleRegion()) {
            $show_amazon_button = false;
        }
        
        $this->context->controller->addCSS($this->_path . 'views/css/amzpayments.css', 'all');
        $redirect = $this->context->link->getModuleLink('amzpayments', 'amzpayments');
        
        if (Configuration::get('PS_SSL_ENABLED')) {
            $redirect = str_replace('http://', 'https://', $redirect);
        }
        
        if (strpos($redirect, '?') > 0) {
            $redirect .= '&session=';
        } else {
            $redirect .= '?session=';
        }
        
        $login_redirect = $this->context->link->getModuleLink('amzpayments', 'processlogin');
        
        // always SSL, as amazon has nothing else allowed!
        $login_redirect = str_replace('http://', 'https://', $login_redirect);
        
        if (strpos($login_redirect, '?') > 0) {
            $login_checkout_redirect = $login_redirect . '&toCheckout=1';
        } else {
            $login_checkout_redirect = $login_redirect . '?toCheckout=1';
        }
        
        $set_user_ajax = $this->context->link->getModuleLink('amzpayments', 'usertoshop');
        
        // always SSL, as amazon has nothing else allowed!
        $set_user_ajax = str_replace('http://', 'https://', $set_user_ajax);
        
        $ext_js = '';
        
        if ($this->getRegionalCodeForURL() == 'us') {
            if ($this->environment == 'SANDBOX') {
                $ext_js = 'https://static-na.payments-amazon.com/OffAmazonPayments/us/sandbox/js/Widgets.js ';
            } else {
                $ext_js = 'https://static-na.payments-amazon.com/OffAmazonPayments/us/js/Widgets.js ';
            }
        } else {
            if ($this->environment == 'SANDBOX') {
                $ext_js = 'https://static-eu.payments-amazon.com/OffAmazonPayments/' . $this->getRegionalCodeForURL() . '/sandbox/lpa/js/Widgets.js?sellerId=' . $this->merchant_id;
            } else {
                $ext_js = 'https://static-eu.payments-amazon.com/OffAmazonPayments/' . $this->getRegionalCodeForURL() . '/lpa/js/Widgets.js?sellerId=' . $this->merchant_id;
            }
        }
        
        $ext_js = '<script type="text/javascript" src="' . $ext_js . '"></script>';
        
        $amz_login_ready = ' window.onAmazonLoginReady = function() { amazon.Login.setClientId("' . $this->client_id . '"); }; ';
        
        $acc_tk = '';
        $is_logged = 'false';
        
        if (isset($this->context->cookie->amz_access_token) && $this->context->cookie->amz_access_token != '') {
            $is_logged = 'true';
            if (!isset($this->context->cookie->amazon_id)) {
                $acc_tk = self::prepareCookieValueForAmazonPaymentsUse($this->context->cookie->amz_access_token);
                $amz_login_ready = '
				var accessToken = "' . $acc_tk . '";
				if (typeof accessToken === \'string\' && accessToken.match(/^Atza/)) {
				document.cookie = "amazon_Login_accessToken=" + accessToken +";secure";
			}
			window.onAmazonLoginReady = function() {
			amazon.Login.setClientId("' . $this->client_id . '");
			    amazon.Login.setUseCookie(true);
			};
			';
            }
        }        
        
        $logout_str = '';
        if ($this->context->controller->php_self == 'guest-tracking') {
            if ($this->lpa_mode != 'pay') {
                $logout_str .= '<script type="text/javascript"> amazonLogout(); </script>';
            }
        }
        
        if ($this->button_visibility == '0') {
            $css_string = '<style> #jsLoginAuthPage,#payWithAmazonCartDiv,#HOOK_ADVANCED_PAYMENT #payWithAmazonListDiv { display: none; } </style>';
        } else {
            $css_string = '';
        }
        
        $js_file = 'views/js/amzpayments.js';
        $js_file = Tools::file_get_contents(_PS_MODULE_DIR_ . $this->name . '/' . $js_file);
        $js_file = str_replace(array(
            "\t",
            "\r\n",
            "\n"
        ), array(
            ' ',
            ' ',
            ' '
        ), $js_file);
        $this->context->cookie->amz_js_string = self::prepareCookieValueForPrestaShopUse($amz_login_ready);
        $amz_login_ready = '<script type="text/javascript" src="' . Tools::str_replace_once((Configuration::get('PS_SSL_ENABLED') ? 'http://' : ''), (Configuration::get('PS_SSL_ENABLED') ? 'https://' : ''), $this->context->link->getModuleLink('amzpayments', 'jsmode', array('c' => 'amz_js_string', 't' => time()))) . '"></script>';
        return $css_string . $amz_login_ready . $ext_js . '<script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script><script type="text/javascript"> var AMZACTIVE = \'' . ($show_amazon_button ? '1' : '0') . '\'; var AMZSELLERID = "' . $this->merchant_id . '"; var AMZ_BUTTON_TYPE_LOGIN = "' . $this->type_login . '"; var AMZ_BUTTON_TYPE_PAY = "' . $this->type_pay . '"; var AMZ_BUTTON_SIZE_LPA = "' . $this->button_size_lpa . '"; var AMZ_BUTTON_COLOR_LPA = "' . $this->button_color_lpa . '"; var AMZ_BUTTON_COLOR_LPA_NAVI = "' . $this->button_color_lpa_navi . '"; var AMZ_WIDGET_LANGUAGE = "' . $this->getWidgetLanguageCode() . '"; var CLIENT_ID = "' . $this->client_id . '"; var useRedirect = ' . (!self::currentSiteIsSSL() || $this->popup == '0' ? 'true' : 'false') . '; var LPA_MODE = "' . $this->lpa_mode . '"; var REDIRECTAMZ = "' . $redirect . '"; var LOGINREDIRECTAMZ_CHECKOUT = "' . $login_checkout_redirect . '"; var LOGINREDIRECTAMZ = "' . $login_redirect . '"; var is_logged = ' . $is_logged . '; var AMZACCTK = "' . $acc_tk . '"; var SETUSERAJAX = "' . $set_user_ajax . '";' . $js_file . ' </script>' . $logout_str;
    }

    public function hookDisplayAdminOrder($params)
    {
        $order = new Order($params['id_order']);
        if ($order->module == $this->name) {
            $q = 'SELECT ao.`amazon_order_reference_id` 
                    FROM `' . _DB_PREFIX_ . 'amz_orders` ao
                   WHERE `id_order` = ' . (int) $params['id_order'];
            $r = Db::getInstance()->getRow($q);
            $amz_reference_id = $r['amazon_order_reference_id'];
            
            $q = 'SELECT * FROM ' . _DB_PREFIX_ . 'amz_transactions 
                   WHERE amz_tx_order_reference = \'' . pSQL($amz_reference_id) . '\' AND
                     (
                       (amz_tx_status != \'Closed\' AND amz_tx_status != \'Declined\') 
                       OR
                       (amz_tx_status = \'Closed\' AND amz_tx_type = \'auth\' AND NOT EXISTS
                         (SELECT amz_tx_id FROM ' . _DB_PREFIX_ . 'amz_transactions WHERE amz_tx_order_reference = \'' . pSQL($amz_reference_id) . '\' AND amz_tx_type = \'capture\')
                       )
                     )';
            $rs = Db::getInstance()->ExecuteS($q);
            foreach ($rs as $r) {
                $this->intelligentRefresh($r);
            }            
            
            return $this->getAdminSkeleton($params['id_order'], true);
        }
    }

    public function hookPaymentReturn($params)
    {
        $this->context->smarty->assign('shop_name', Configuration::get('PS_SHOP_NAME'));
        return $this->display(__FILE__, 'views/templates/hooks/confirmation.tpl');
    }

    public function setAmzOrdersReferences($order_id, $value, $field)
    {
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('
            SELECT * FROM `' . _DB_PREFIX_ . 'amz_orders` WHERE `id_order` = \'' . (int) $order_id . '\'
        ');
        
        if ($result) {
            return Db::getInstance(_PS_USE_SQL_SLAVE_)->update('amz_orders', array(
                $field => pSQL($value)
            ), 'id_order = \'' . (int) $order_id . '\'');
        } else {
            return Db::getInstance(_PS_USE_SQL_SLAVE_)->insert('amz_orders', array(
                'id_order' => pSQL((int) $order_id),
                $field => pSQL($value)
            ));
        }
    }

    public function setAmazonReferenceIdForOrderId($amazon_reference_id, $order_id)
    {
        return $this->setAmzOrdersReferences($order_id, $amazon_reference_id, 'amazon_order_reference_id');
    }

    public function setAmazonAuthorizationReferenceIdForOrderId($authorization_reference_id, $order_id)
    {
        return $this->setAmzOrdersReferences($order_id, $authorization_reference_id, 'amazon_auth_reference_id');
    }

    public function setAmazonAuthorizationIdForOrderId($authorization_id, $order_id)
    {
        return $this->setAmzOrdersReferences($order_id, $authorization_id, 'amazon_authorization_id');
    }

    public function setAmazonCaptureIdForOrderId($amazon_capture_id, $order_id)
    {
        return $this->setAmzOrdersReferences($order_id, $amazon_capture_id, 'amazon_capture_id');
    }

    public function setAmazonCaptureReferenceIdForOrderId($amazon_capture_reference_id, $order_id)
    {
        return $this->setAmzOrdersReferences($order_id, $amazon_capture_reference_id, 'amazon_capture_reference_id');
    }

    public function setAmazonReferenceIdForOrderTransactionId($amazon_reference_id, $order_id)
    {
        $q = 'SELECT `reference` FROM ' . _DB_PREFIX_ . 'orders WHERE `id_order` = ' . (int) $order_id;
        if ($r = Db::getInstance()->getRow($q)) {
            return Db::getInstance()->execute('UPDATE `' . _DB_PREFIX_ . 'order_payment`
                                                  SET `transaction_id` = \'' . pSQL($amazon_reference_id) . '\'
                                                WHERE `order_reference` = \'' . pSQL($r['reference']) . '\'');
        }
        
        return false;
    }

    public function getAmazonReferenceIdForOrderTransactionId($order_id)
    {
        $q = 'SELECT `reference` FROM ' . _DB_PREFIX_ . 'orders WHERE `id_order` = ' . (int) $order_id;
        if ($r = Db::getInstance()->getRow($q)) {
            return $r['reference'];
        }
        
        return false;
    }

    public function createUniqueOrderId($cart_id)
    {
        return 'AP' . $cart_id . '-' . Tools::substr(Tools::getToken(false), 0, 8);
    }

    public function getAdminSkeleton($orders_id, $direct_include = false)
    {
        $q = 'SELECT `amazon_order_reference_id` FROM `' . _DB_PREFIX_ . 'amz_orders` WHERE `id_order` = ' . (int) $orders_id;
        $r = Db::getInstance()->getRow($q);
        if ($r['amazon_order_reference_id']) {
            
            $this->smarty->assign(array(
                'displayName' => $this->displayName,
                'amazon_order_reference_id' => $r['amazon_order_reference_id'],
                'orderHistory' => $direct_include ? $this->getOrderHistory($r['amazon_order_reference_id']) : '',
                'orderSummary' => $direct_include ? $this->getOrderSummary($r['amazon_order_reference_id']) : '',
                'orderActions' => $direct_include ? $this->getOrderActions($r['amazon_order_reference_id']) : ''
            ));
            return $this->display(__FILE__, 'views/templates/admin/skeleton.tpl');
        }
    }

    public function getOrderHistory($order_ref)
    {
        $q = 'SELECT * FROM ' . _DB_PREFIX_ . 'amz_transactions WHERE amz_tx_order_reference = \'' . pSQL($order_ref) . '\' ORDER BY amz_tx_time';
        $rs = Db::getInstance()->ExecuteS($q);
        $ret = '';
        
        $rs_to_assign = array();
        foreach ($rs as $r) {
            if ($r['amz_tx_type'] == 'order_ref') {
                $reference_status = $r['amz_tx_status'];
            }
            
            $rs_to_assign[] = array(
                'transaction_type' => $this->translateTransactionType($r['amz_tx_type']),
                'amount' => self::formatAmount($r['amz_tx_amount']),
                'date' => date('Y-m-d H:i:s', $r['amz_tx_time']),
                'status' => $r['amz_tx_status'],
                'last_change' => date('Y-m-d H:i:s', $r['amz_tx_last_change']),
                'tx_id' => $r['amz_tx_amz_id'],
                'tx_expiration' => ($r['amz_tx_expiration'] != 0 ? date('Y-m-d H:i:s', $r['amz_tx_expiration']) : '-')
            );
        }
        
        if (sizeof($rs_to_assign) > 0) {
            $this->smarty->assign(array(
                'rs' => $rs_to_assign,
                'order_ref' => $order_ref,
                'reference_status' => $reference_status
            ));
            return $this->display(__FILE__, 'views/templates/admin/order_history.tpl');
        }
    }

    public function getOrderAuthorizedAmount($order_ref)
    {
        $q = 'SELECT SUM(amz_tx_amount) AS auth_sum FROM ' . _DB_PREFIX_ . 'amz_transactions WHERE
		amz_tx_order_reference = \'' . pSQL($order_ref) . '\'
		AND
		amz_tx_type=\'auth\'
		AND
		amz_tx_status = \'Open\'';
        
        $r = Db::getInstance()->getRow($q);
        return (float) $r['auth_sum'];
    }

    public function getOrderCapturedAmount($order_ref)
    {
        $q = 'SELECT SUM(amz_tx_amount) AS capture_sum FROM ' . _DB_PREFIX_ . 'amz_transactions WHERE
		amz_tx_order_reference = \'' . pSQL($order_ref) . '\'
		AND
		amz_tx_type=\'capture\'
		AND
		amz_tx_status = \'Completed\'';
        $r = Db::getInstance()->getRow($q);
        return (float) $r['capture_sum'];
    }

    public function getOrderRefundedAmount($order_ref)
    {
        $q = 'SELECT SUM(amz_tx_amount) AS refund_sum FROM ' . _DB_PREFIX_ . 'amz_transactions WHERE
		amz_tx_order_reference = \'' . pSQL($order_ref) . '\'
		AND
		amz_tx_type=\'refund\'
		AND
		amz_tx_status = \'Completed\'';
        $r = Db::getInstance()->getRow($q);
        return (float) $r['refund_sum'];
    }

    public static function getOrderOpenAuthorizations($order_ref)
    {
        $q = 'SELECT * FROM ' . _DB_PREFIX_ . 'amz_transactions WHERE
		amz_tx_order_reference = \'' . pSQL($order_ref) . '\'
		AND
		amz_tx_type=\'auth\'
		AND
		amz_tx_status = \'Open\'';
        $rs = Db::getInstance()->ExecuteS($q);
        $ret = array();
        foreach ($rs as $r) {
            $ret[] = $r;
        }
        return $ret;
    }

    public static function getOrderCaptures($order_ref)
    {
        $q = 'SELECT * FROM ' . _DB_PREFIX_ . 'amz_transactions WHERE
		amz_tx_order_reference = \'' . pSQL($order_ref) . '\'
		AND
		amz_tx_type=\'capture\'';
        $rs = Db::getInstance()->ExecuteS($q);
        $ret = array();
        foreach ($rs as $r) {
            $ret[] = $r;
        }
        return $ret;
    }

    public static function getOrderUnclosedCaptures($order_ref)
    {
        $q = 'SELECT * FROM ' . _DB_PREFIX_ . 'amz_transactions WHERE
		amz_tx_order_reference = \'' . pSQL($order_ref) . '\'
		AND
		amz_tx_status != \'Closed\'
		AND
		amz_tx_type=\'capture\'';
        $rs = Db::getInstance()->ExecuteS($q);
        $ret = array();
        foreach ($rs as $r) {
            $ret[] = $r;
        }
        return $ret;
    }

    public static function getOrderState($order_ref)
    {
        $q = 'SELECT * FROM ' . _DB_PREFIX_ . 'amz_transactions WHERE
		amz_tx_order_reference = \'' . pSQL($order_ref) . '\'
		AND
		amz_tx_type=\'order_ref\'';
        $r = Db::getInstance()->getRow($q);
        return $r['amz_tx_status'];
    }

    public function intelligentRefresh($r)
    {
        switch ($r['amz_tx_type']) {
            case 'refund':
                $this->refreshRefund($r['amz_tx_amz_id']);
                break;
            
            case 'capture':
                $this->refreshCapture($r['amz_tx_amz_id']);
                break;
            
            case 'auth':
                $this->refreshAuthorization($r['amz_tx_amz_id']);
                break;
            
            case 'order_ref':
                $this->refreshOrderReference($r['amz_tx_amz_id']);
                break;
        }
    }

    public function refreshRefund($refund_id)
    {
        $service = $this->getService();
        $requestParameters = array();
        $requestParameters['merchant_id'] = $this->merchant_id;
        $requestParameters['amazon_refund_id'] = $refund_id;
        $response = $service->GetRefundDetails($requestParameters);
        
        if ($service->success) {
            $responsearray = $response->toArray();
            $details = $responsearray['GetRefundDetailsResult']['RefundDetails'];
            $sql_arr = array(
                'amz_tx_status' => pSQL((string) $details['RefundStatus']['State']),
                'amz_tx_last_change' => pSQL(strtotime((string) $details['RefundStatus']['LastUpdateTimestamp'])),
                'amz_tx_last_update' => pSQL(time())
            );
            Db::getInstance()->update('amz_transactions', $sql_arr, " amz_tx_amz_id = '" . pSQL($refund_id) . "'");
            
        } else {
            echo 'ERROR';
        }
    }

    public function refreshCapture($capture_id)
    {
        $service = $this->getService();
        $requestParameters = array();
        $requestParameters['merchant_id'] = $this->merchant_id;
        $requestParameters['amazon_capture_id'] = $capture_id;
        $response = $service->getCaptureDetails($requestParameters);
        
        if ($service->success) {
            $responsearray = $response->toArray();
            $details = $responsearray['GetCaptureDetailsResult']['CaptureDetails'];
            $sql_arr = array(
                'amz_tx_status' => pSQL((string) $details['CaptureStatus']['State']),
                'amz_tx_last_change' => pSQL(strtotime((string) $details['CaptureStatus']['LastUpdateTimestamp'])),
                'amz_tx_amount_refunded' => pSQL((float) $details['RefundedAmount']['Amount']),
                'amz_tx_last_update' => pSQL(time())
            );
            Db::getInstance()->update('amz_transactions', $sql_arr, " amz_tx_amz_id = '" . pSQL($capture_id) . "'");
        
            if ($sql_arr['amz_tx_status'] == 'Completed') {
                $q = 'SELECT * FROM ' . _DB_PREFIX_ . 'amz_transactions WHERE amz_tx_type=\'capture\'
				AND amz_tx_amz_id = \'' . pSQL($capture_id) . '\'';
                $r = Db::getInstance()->getRow($q);
                if ($r) {
                    $total = AmazonTransactions::getOrderRefTotal($r['amz_tx_order_reference']);
                    if ($r['amz_tx_amount'] == $total) {
                        AmazonTransactions::closeOrder($this, $service, $r['amz_tx_order_reference']);
                    }
                }
            }
        
        } else {
            echo 'ERROR: Capture refresh not successful';
        }
    }

    public function refreshAuthorization($auth_id)
    {
        $service = $this->getService();
        $requestParameters = array();
        $requestParameters['merchant_id'] = $this->merchant_id;
        $requestParameters['amazon_authorization_id'] = $auth_id;
        $response = $service->getAuthorizationDetails($requestParameters);
        if ($service->success) {
            $responsearray = $response->toArray();
            $details = $responsearray['GetAuthorizationDetailsResult']['AuthorizationDetails'];
            $sql_arr = array(
                'amz_tx_status' => pSQL((string) $details['AuthorizationStatus']['State']),
                'amz_tx_last_change' => pSQL(strtotime((string) $details['AuthorizationStatus']['LastUpdateTimestamp'])),
                'amz_tx_last_update' => pSQL(time())
            );
            Db::getInstance()->update('amz_transactions', $sql_arr, " amz_tx_amz_id = '" . pSQL($auth_id) . "'");
            if ((string) $details['AuthorizationStatus']['State'] == 'Declined') {
                $reason = (string) $details['AuthorizationStatus']['ReasonCode'];
            
                if ($reason == 'AmazonRejected') {
                    $order_ref = AmazonTransactions::getOrderRefFromAmzId($auth_id);
                    $this->cancelOrder($order_ref);
                }
                $this->intelligentDeclinedMail($auth_id, $reason);
                if ($this->decline_status_id > 0) {
                    $order_ref = AmazonTransactions::getOrderRefFromAmzId($auth_id);
                    AmazonTransactions::setOrderStatusDeclined($order_ref, true);
                }
            } elseif ((string) $details['AuthorizationStatus']['State'] == 'Open') {
                $order_ref = AmazonTransactions::getOrderRefFromAmzId($auth_id);
                AmazonTransactions::setOrderStatusAuthorized($order_ref, true);
            } elseif ((string) $details['AuthorizationStatus']['State'] == 'Closed' && (string) $details['AuthorizationStatus']['ReasonCode'] == 'MaxCapturesProcessed') {
                $captureId = (string) $details['IdList']['member'];
                if ($captureId != '') {                    
                    $order_ref = AmazonTransactions::getOrderRefFromAmzId($auth_id);
                    $requestParameters = array();
                    $requestParameters['merchant_id'] = $this->merchant_id;
                    $requestParameters['amazon_capture_id'] = $captureId;
                    try {
                        $response = $service->getCaptureDetails($requestParameters);     
                        $responsearray = $response->toArray();
                        $details = $responsearray['GetCaptureDetailsResult']['CaptureDetails'];

                        $sql_arr = array(
                            'amz_tx_order_reference' => pSQL($order_ref),
                            'amz_tx_type' => 'capture',
                            'amz_tx_time' => pSQL(time()),
                            'amz_tx_expiration' => 0,
                            'amz_tx_amount' => pSQL((string)$details['CaptureAmount']['Amount']),
                            'amz_tx_status' => pSQL((string)$details['CaptureStatus']['State']),
                            'amz_tx_reference' => pSQL((string)$details['CaptureReferenceId']),
                            'amz_tx_amz_id' => pSQL((string)$details['AmazonCaptureId']),
                            'amz_tx_last_change' => pSQL(time()),
                            'amz_tx_last_update' => pSQL(time())
                        );
                        
                        $checkQuery = 'SELECT * FROM ' . _DB_PREFIX_ . 'amz_transactions
                            WHERE `amz_tx_order_reference` = \'' . pSQL($order_ref) . '\'
                            AND `amz_tx_type` = \'capture\' 
                            ';
                        if ($row = Db::getInstance()->getRow($checkQuery)) {
                            return;
                        } else {                        
                            Db::getInstance()->insert('amz_transactions', $sql_arr);
                            AmazonTransactions::setOrderStatusCapturedSuccesfully($order_ref);
                        }
                    } catch (OffAmazonPaymentsService_Exception $e) {
                        echo 'ERROR: ' . $e->getMessage();
                    }
                }
            }
        } else {
            echo 'ERROR: ' . $e->getErrorMessage();
        }
    }

    public function refreshOrderReference($order_ref)
    {
        $service = $this->getService();
        $requestParameters = array();
        $requestParameters['merchant_id'] = $this->merchant_id;
        $requestParameters['amazon_order_reference_id'] = $order_ref;
        $response = $service->GetOrderReferenceDetails($requestParameters);
        if ($service->success) {
            $responsearray = $response->toArray();
            $details = $responsearray['GetOrderReferenceDetailsResult']['OrderReferenceDetails'];
            $sql_arr = array(
                'amz_tx_status' => pSQL((string) $details['OrderReferenceStatus']['State']),
                'amz_tx_last_change' => pSQL(strtotime((string) $details['OrderReferenceStatus']['LastUpdateTimestamp'])),
                'amz_tx_last_update' => pSQL(time())
            );
            Db::getInstance()->update('amz_transactions', $sql_arr, " amz_tx_amz_id = '" . pSQL($order_ref) . "'");
            
        } else {
            echo 'ERROR: Refresh Order Reference not succesful';
        }
    }

    public function closeOrder($order_ref)
    {
        $service = $this->getService();
        $requestParameters = array();
        $requestParameters['merchant_id'] = $this->merchant_id;
        $requestParameters['amazon_order_reference_id'] = $order_ref;
        
        $response = $service->closeOrderReference($requestParameters);
        if ($service->success) {
            return $response->toArray();
        } else {
            echo 'ERROR';
        }
        return false;
    }

    public function cancelOrder($order_ref)
    {
        $service = $this->getService();
        $requestParameters = array();
        $requestParameters['merchant_id'] = $this->merchant_id;
        $requestParameters['amazon_order_reference_id'] = $order_ref;
        
        $response = $service->cancelOrderReference($requestParameters);
        if ($service->success) {
            return $response->toArray();
        } else {
            echo 'ERROR';
        }
        return false;
    }

    public static function getClassForStatus($status)
    {
        switch ($status) {
            case 'Open':
            case 'Completed':
            case 'Closed':
                return 'amzGreen';
            
            case 'Pending':
                return 'amzOrange';
            
            default:
                return 'amzRed';
        }
    }

    public function getOrderActions($order_ref)
    {
        $order_state = $this->getOrderState($order_ref);
        $got_something = false;
        $this->smarty->assign(array(
            'order_state' => $order_state
        ));
        if ($order_state == 'Open' || $order_state == 'Closed') {
            $open_auth = self::getOrderOpenAuthorizations($order_ref);
            if (count($open_auth) > 0) {
                $open_auth_assigns = array();
                foreach ($open_auth as $r) {
                    $open_auth_assigns[] = array(
                        'amount' => self::formatAmount($r['amz_tx_amount']),
                        'date' => date('Y-m-d H:i:s', $r['amz_tx_time']),
                        'tx_id' => $r['amz_tx_amz_id'],
                        'tx_expiration' => ($r['amz_tx_expiration'] != 0 ? date('Y-m-d H:i:s', $r['amz_tx_expiration']) : '-')
                    );
                }
                $got_something = true;
                $this->smarty->assign(array(
                    'open_auth' => $open_auth_assigns
                ));
            }
        }
        if ($order_state == 'Open') {
            $amount_left_to_authorize = $this->getAmountLeftToAuthorize($order_ref);
            $amount_left_to_over_authorize = $this->getAmountLeftToOverAuthorize($order_ref);
            if ($amount_left_to_authorize > 0 || $amount_left_to_over_authorize > 0) {
                if ($amount_left_to_authorize + $amount_left_to_over_authorize > 0) {
                    $this->smarty->assign(array(
                        'authorize_tab' => true
                    ));
                    $this->smarty->assign(array(
                        'amount_left_to_authorize_raw' => $amount_left_to_authorize,
                        'amount_left_to_authorize' => self::formatAmount($amount_left_to_authorize),
                        'amount_maximum' => self::formatAmount($amount_left_to_authorize + $amount_left_to_over_authorize),
                        'amount_field' => self::formatAmount(($amount_left_to_authorize > 0 ? $amount_left_to_authorize : $amount_left_to_over_authorize)),
                        'order_ref' => $order_ref
                    ));
                    $got_something = true;
                }
            }
        }
        
        $captures = self::getOrderUnclosedCaptures($order_ref);
        if (count($captures) > 0) {
            $this->smarty->assign(array(
                'refunds_tab' => true
            ));
            $captures_to_assign = array();
            foreach ($captures as $r) {
                
                $captures_to_assign[] = array(
                    'amount' => self::formatAmount($r['amz_tx_amount']),
                    'amount_refunded' => self::formatAmount($r['amz_tx_amount_refunded']),
                    'amount_possible' => self::formatAmount(($refundable = (min((75 + $r['amz_tx_amount']), (round($r['amz_tx_amount'] * 1.15, 2))) - $r['amz_tx_amount_refunded']))),
                    'date' => date('Y-m-d H:i:s', $r['amz_tx_time']),
                    'status_class' => self::getClassForStatus($r['amz_tx_status']),
                    'status' => $r['amz_tx_status'],
                    'last_change' => date('Y-m-d H:i:s', $r['amz_tx_last_change']),
                    'tx_id' => $r['amz_tx_amz_id'],
                    'total_refund_button' => $r['amz_tx_amount'] - $r['amz_tx_amount_refunded'] > 0,
                    'total_refund_button_value' => $r['amz_tx_amount'] - $r['amz_tx_amount_refunded'],
                    'field_value' => self::formatAmount(($r['amz_tx_amount'] - $r['amz_tx_amount_refunded'] > 0 ? ($r['amz_tx_amount'] - $r['amz_tx_amount_refunded']) : $refundable))
                );
            }
            $got_something = true;
            $this->smarty->assign('captures', $captures_to_assign);
        }
        
        if ($got_something) {
            return $this->display(__FILE__, 'views/templates/admin/order_actions.tpl');
        }
        return false;
    }

    public function getAmountLeftToAuthorize($order_ref)
    {
        $total = AmazonTransactions::getOrderRefTotal($order_ref);
        $authorized = $this->getOrderAuthorizedAmount($order_ref);
        $captured = $this->getOrderCapturedAmount($order_ref);
        $left = $total - $authorized - $captured;
        $left = min($left, $total);
        $left = round(max(0, $left), 2);
        return $left;
    }

    public function getAmountLeftToOverAuthorize($order_ref)
    {
        $total = AmazonTransactions::getOrderRefTotal($order_ref);
        $authorized = $this->getOrderAuthorizedAmount($order_ref);
        $captured = $this->getOrderCapturedAmount($order_ref);
        
        $left = round(($total * 1.15), 2) - $authorized - $captured;
        
        $left -= self::getAmountLeftToAuthorize($order_ref);
        $left = round(max(0, $left), 2);
        
        if ($left > 75) {
            $left = 75;
        }
        
        return $left;
    }

    protected function hasNoPendingRefund($amz_reference_id)
    {
        $current_refund_state_and_id = AmazonTransactions::getCurrentAmzTransactionRefundStateAndId($amz_reference_id);
        return $current_refund_state_and_id['amz_tx_status'] != 'Pending';
    }

    public function getOrderRefundMaximum($order_ref)
    {
        $captured = $this->getOrderCapturedAmount($order_ref);
        $refunded = $this->getOrderRefundedAmount($order_ref);
        return $captured - $refunded;
    }

    public function getOrderSummary($order_ref)
    {
        $this->smarty->assign(array(
            'authorized_amount' => self::formatAmount(self::getOrderAuthorizedAmount($order_ref)),
            'captured_amount' => self::formatAmount(self::getOrderCapturedAmount($order_ref)),
            'refunded_amount' => self::formatAmount(self::getOrderRefundedAmount($order_ref))
        ));
        
        return $this->display(__FILE__, 'views/templates/admin/order_summary.tpl');
    }

    public static function formatAmount($amount)
    {
        return Tools::displayPrice($amount, Context::getContext()->currency);
    }

    public function translateTransactionType($str)
    {
        switch ($str) {
            case 'auth':
                $str = $this->l('Authorisation');
                break;
            case 'order_ref':
                $str = $this->l('Order');
                break;
            case 'capture':
                $str = $this->l('Withdrawal');
                break;
            case 'refund':
                $str = $this->l('Refund');
                break;
        }
        
        return $str;
    }

    public function shippingCapture()
    {
        if ($this->capture_mode == 'after_shipping') {
            $q = 'SELECT DISTINCT ao.amazon_order_reference_id FROM  ' . _DB_PREFIX_ . 'orders o
            JOIN ' . _DB_PREFIX_ . 'amz_orders ao ON o.id_order = ao.id_order 
			JOIN ' . _DB_PREFIX_ . 'amz_transactions AS a1 ON (ao.amazon_order_reference_id = a1.amz_tx_order_reference AND a1.amz_tx_type = \'auth\' AND a1.amz_tx_status = \'Open\')
			LEFT JOIN ' . _DB_PREFIX_ . 'amz_transactions AS a2 ON (ao.amazon_order_reference_id = a2.amz_tx_order_reference AND a2.amz_tx_type = \'capture\')
			WHERE
			ao.amazon_order_reference_id != \'\'
			AND
			o.current_state = \'' . pSQL($this->capture_status_id) . '\'
			AND
			a2.amz_tx_id IS NULL';
            $rs = Db::getInstance()->ExecuteS($q);
            foreach ($rs as $r) {
                $ramz = AmazonTransactions::getAuthorizationForCapture($r['amazon_order_reference_id']);
                $auth_id = $ramz['amz_tx_amz_id'];
                AmazonTransactions::captureTotalFromAuth($this, $this->getService(), $auth_id);
            }
        }
    }

    public function sendSoftDeclinedMail($order_ref)
    {
        $this->sendDeclinedMail($order_ref, 'soft');
    }

    public function sendHardDeclinedMail($order_ref)
    {
        $this->sendDeclinedMail($order_ref, 'hard');
    }

    public function sendDeclinedMail($order_ref, $type)
    {
        $q = 'SELECT * FROM ' . _DB_PREFIX_ . 'amz_orders WHERE amazon_order_reference_id = \'' . pSQL($order_ref) . '\'';
        $rs = Db::getInstance()->ExecuteS($q);
        foreach ($rs as $r) {
            
            $order = new Order($r['id_order']);
            
            $lang_id = $order->id_lang;
            $reference = $order->reference;
            $order_date = $order->date_add;
            $customer = new Customer($order->id_customer);
            $email = $customer->email;
            
            if ($type == 'soft') {
                $subject = $this->l('Your payment was rejected by Amazon');
            } elseif ($type == 'hard') {
                $subject = $this->l('Your payment was rejected by Amazon - please contact us');
            }
            
            Mail::Send($lang_id, 'amazon_' . $type . '_decline', $subject, array(
                '{$ORDER_NR}' => $reference,
                '{$ORDER_DATE}' => $order_date
            ), $email, null, null, null, null, null, dirname(__FILE__) . '/mails/', false, $this->context->shop->id);
            
            $str = 'Mail sent: ' . 'amazon_' . $type . '_decline' . ' -> ' . $subject . ' -> ' . $email;
            file_put_contents('amz.log', $str, FILE_APPEND);
        }
    }

    public function intelligentDeclinedMail($amz_id, $reason)
    {
        if ($this->send_mails_on_decline == '1') {
            $q = 'SELECT * FROM ' . _DB_PREFIX_ . 'amz_transactions WHERE amz_tx_amz_id = \'' . pSQL($amz_id) . '\'';
            $rs = Db::getInstance()->ExecuteS($q);
            foreach ($rs as $r) {
                if ($r['amz_tx_status'] == 'Declined' && $r['amz_tx_customer_informed'] == 0) {
                    $informed = 0;
                    if ($reason == 'InvalidPaymentMethod') {
                        $this->sendSoftDeclinedMail($r['amz_tx_order_reference']);
                        $informed = 1;
                    } elseif ($reason == 'AmazonRejected') {
                        $this->sendHardDeclinedMail($r['amz_tx_order_reference']);
                        $informed = 1;
                    }
                    
                    if ($informed == 1) {
                        $q = 'UPDATE ' . _DB_PREFIX_ . 'amz_transactions SET amz_tx_customer_informed = 1 WHERE amz_tx_id = \'' . (int) $r['amz_tx_id'] . '\'';
                        Db::getInstance()->execute($q);
                    }
                }
            }
        }
    }

    public static function currentSiteIsSSL()
    {
        return Tools::usingSecureMode();
    }

    public static function prepareCookieValueForPrestaShopUse($str)
    {
        return str_replace('|', '-HORDIV-', $str);
    }

    public static function prepareCookieValueForAmazonPaymentsUse($str)
    {
        return str_replace('-HORDIV-', '|', $str);
    }

    public static function addressAlreadyExists($address, $customer)
    {
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('
				SELECT a.`id_address`
				FROM `' . _DB_PREFIX_ . 'address` a
				WHERE a.`lastname` = "' . pSQL($address->lastname) . '"
				  AND a.`firstname` = "' . pSQL($address->firstname) . '"
				  AND a.`address1` = "' . pSQL($address->address1) . '"
				  AND a.`postcode` = "' . pSQL($address->postcode) . '"
				  AND a.`city` = "' . pSQL($address->city) . '"
				  AND a.`phone` = "' . pSQL($address->phone) . '"
				  AND a.`id_customer` = "' . pSQL($customer->id) . '"		
				  AND a.`deleted` = 0
				');
        
        return $result['id_address'] ? true : false;
    }

    public static function switchOrderToCustomer($customer_id, $order_id, $unset_cookie = false, $switch_addresses = true)
    {
        $order = new Order($order_id);
        $order->id_customer = $customer_id;
        $order->save();
        if ($switch_addresses) {
            self::switchAddressToCustomer($customer_id, $order_id);
        }
        if ($unset_cookie) {
            unset(Context::getContext()->cookie->amz_connect_order);
        }
        return true;
    }
    
    public static function switchAddressToCustomer($customer_id, $order_id)
    {
        $order = new OrderCore($order_id);
        $address_delivery = new AddressCore((int)$order->id_address_delivery);
        $address_delivery->id_customer = (int)$customer_id;
        $address_delivery->save();
        if ((int)$order->id_address_delivery != (int)$order->id_address_invoice) {
            $address_invoice = new AddressCore((int)$order->id_address_invoice);
            $address_invoice->id_customer = (int)$customer_id;
            $address_invoice->save();
        }
        return true;
    }
    
    public function getLanguageCodeForSimplePath()
    {
        return str_replace('-', '_', $this->getWidgetLanguageCode());
    }

    public function getWidgetLanguageCode()
    {
        switch ($this->context->language->iso_code) {
            case 'de':
                return 'de-DE';
            case 'us':
            case 'en':
                return 'en-GB';
            case 'fr':
                return 'fr-FR';
            case 'it':
                return 'it-IT';
            case 'es':
                return 'es-ES';
            default:
                return 'en-GB';
        }
    }
    
    public function requestTokenInfo($accessTokenValue)
    {
        $c = curl_init($this->getLpaApiUrl() . '/auth/o2/tokeninfo?access_token=' . urlencode($accessTokenValue));
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        $r = curl_exec($c);
        curl_close($c);
        $d = json_decode($r);
        return $d;
    }
    
    public function requestProfile($accessTokenValue)
    {
        $c = curl_init($this->getLpaApiUrl() . '/user/profile');
        curl_setopt($c, CURLOPT_HTTPHEADER, array(
            'Authorization: bearer ' . $accessTokenValue
        ));
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        $r = curl_exec($c);
        curl_close($c);
        $d = json_decode($r);
        return $d;
    }

    public static function getFromArray(array $arr, $field = '', $allow_array_return = false)
    {
        if (isset($arr[$field])) {
            if (is_array($arr[$field])) {
                if (!$allow_array_return) {
                    return false;
                }
            }
            return $arr[$field];
        } else {
            return false;
        }
    }
}
