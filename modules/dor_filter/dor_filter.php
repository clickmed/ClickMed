<?php
/*
* 2007-2014 PrestaShop
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
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2014 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/
if (!defined('_PS_VERSION_'))
	exit;
class Dor_filter extends Module
{
	private $spacer_size = '1';
	public function __construct()
	{
		$this->name = 'dor_filter';
		$this->tab = 'front_office_features';
		$this->version = '2.0.0';
		$this->author = 'Dorado Themes';
		$this->need_instance = 0;
		$this->bootstrap =true ;
		parent::__construct();
		$this->displayName = $this->l('Dor Filter Search Custom');
		$this->description = $this->l('Block Filter Search Custom');
		$this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
	}
	public function install()
	{
		if (!parent::install() || !$this->registerHook('displayLeftColumn') || !$this->registerHook('displayRightColumn') || !$this->registerHook('dorPriceRange') || !$this->registerHook('dorFilter') || !$this->registerHook('dorFilter2') || !$this->registerHook('header'))
			return false;
		return true;
	}
	public function uninstall(){
		return parent::uninstall();
	}
	public function hookHeader($params){
		if (!isset($this->context->controller->php_self) || $this->context->controller->php_self != 'category')
            return;
		$id_shop = (int)Context::getContext()->shop->id;
		$priceRange = $this->getPrices($id_shop)[0];
		Media::addJsDef(
            array(
                'DORRANGE' => array(
                    "price_min"=>($priceRange["price_min"] != "")?$priceRange["price_min"]:0,
                    "price_max"=>($priceRange["price_max"] != "")?$priceRange["price_max"]:1000
                )
            )
        );
		
		$this->context->controller->addCSS($this->_path . 'assets/css/dorFilter.css');
    	//$this->context->controller->addJS($this->_path . 'assets/js/jquery-ui.min.js');
    	$this->context->controller->addJS($this->_path . 'assets/js/dorFilter.js');
	}
	public function hookDorPriceRange($params)
	{
		$this->context->smarty->assign(array(
			'price_total' => 1
		));
		return $this->display(__FILE__, '/dorPriceRange.tpl');
	}
	public function hookDisplayLeftColumn($params)
    {
        return $this->hookDorPriceRange($params);
    }
	public function getContent() {
    }
	public  function _displayForm() {
		
    }
    public function getConfigFieldsValues()
    {
        
    }
    public static function getPrices($id_shop = 1)
	{
		$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
			SELECT MIN(psi.price_min) price_min, MAX(psi.price_max) price_max
			FROM `'._DB_PREFIX_.'layered_price_index` psi
			WHERE 1=1 AND psi.`id_shop` = '.(int)$id_shop.' AND psi.`id_currency` = 1'
		);
		return $result;
	}
}

