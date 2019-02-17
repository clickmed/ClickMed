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
class Dor_searchcategories extends Module
{
	private $spacer_size = '1';
	public function __construct()
	{
		$this->name = 'dor_searchcategories';
		$this->tab = 'front_office_features';
		$this->version = 2.0;
		$this->author = 'Dorado Themes';
		$this->need_instance = 0;
		$this->bootstrap =true ;
		parent::__construct();
		$this->displayName = $this->l('Dor Search Categories Pro');
		$this->description = $this->l('Adds a quick search field categories to your website.');
		$this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
	}
	public function install()
	{
		Configuration::updateValue($this->name . '_ajax_search', 1);
		if (!parent::install() || !$this->registerHook('dorHeaderSearch') || !$this->registerHook('dorSearch') || !$this->registerHook('header') || !$this->registerHook('displayMobileTopSiteMap'))
			return false;
		return true;
	}

	public function uninstall(){
		Configuration::deleteByName($this->name . '_ajax_search', 1);
		return parent::uninstall();
	}
	public function hookHeader($params)
	{
		/*$this->context->controller->addJqueryPlugin('autocomplete');*/
		$this->context->controller->addCSS($this->_path . 'dorsearch.css');
    	$this->context->controller->addJS($this->_path . 'dorsearch.js');
	}
	public function hookDorHeaderSearch($params)
	{
		global $cookie ;
		$key = $this->getCacheId('blocksearch-top');
		$categories = $this->getCategories((int)($cookie->id_lang) ) ;
		$categories_option = $this->getCategoryOption(1, (int)$cookie->id_lang, (int) Shop::getContextShopID());
		if (Tools::getValue('search_query') || !$this->isCached('dorsearch-top.tpl', $key))
		{
			$this->calculHookCommon($params);
			$this->smarty->assign(array(
				'dorsearch_type' => 'top',
				'categories_option'=>$categories_option,
				'categories' =>$categories,
				'search_query' => (string)Tools::getValue('search_query')
				)
			);
		}
		Media::addJsDef(array('dorsearch_type' => 'top'));
		return $this->display(__FILE__, 'dorsearch-top.tpl', Tools::getValue('search_query') ? null : $key);
	}
	public function hookdisplayMobileTopSiteMap($params)
	{
		$this->smarty->assign(array('hook_mobile' => true, 'instantsearch' => false));
		$params['hook_mobile'] = true;
		return $this->hookDorHeaderSearch($params);
	}
	public function hookDorSearch($params)
    {
        global $cookie ;
		$key = $this->getCacheId('blocksearch-top');
		$categories = $this->getCategories((int)($cookie->id_lang) ) ;
		$categories_option = $this->getCategoryOption(1, (int)$cookie->id_lang, (int) Shop::getContextShopID());
		if (Tools::getValue('search_query') || !$this->isCached('dorsearch-top.tpl', $key))
		{
			$this->calculHookCommon($params);
			$this->smarty->assign(array(
				'dorsearch_type' => 'top',
				'categories_option'=>$categories_option,
				'categories' =>$categories,
				'search_query' => (string)Tools::getValue('search_query')
				)
			);
		}
		Media::addJsDef(array('dorsearch_type' => 'top'));
		return $this->display(__FILE__, 'dorsearch-home.tpl', Tools::getValue('search_query') ? null : $key);
    }
    public function hookTop($params)
    {
        return $this->hookDorHeaderSearch($params);
    }
	private function getCategoryOption($id_category = 1, $id_lang = false, $id_shop = false, $recursive = true) {
		$this->_html = isset($this->_html)?$this->_html:"";
		$id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext()->language->id;
		$category = new Category((int)$id_category, (int)$id_lang, (int)$id_shop);
		if (is_null($category->id))
			return;
		if ($recursive)
		{
			$children = Category::getChildren((int)$id_category, (int)$id_lang, true, (int)$id_shop);
			$spacer = str_repeat('&ndash;', $this->spacer_size * (int)$category->level_depth);
		}
		$shop = (object) Shop::getShop((int)$category->getShopID());
		if($category->name!='Root'){
			$this->_html .= '<li><a href="#" data-value="'.(int)$category->id.'">'.(isset($spacer) ? $spacer : '').$category->name.' </a></li>';
		}
			if (isset($children) && count($children))
			foreach ($children as $child)
				$this->getCategoryOption((int)$child['id_category'], (int)$id_lang, (int)$child['id_shop']);
		return $this->_html;
	}

	public static function getCategories($id_lang = false, $active = true, $order = true, $sql_filter = '', $sql_sort = '', $sql_limit = '')
	{
		if (!Validate::isBool($active))
			die(Tools::displayError());
		$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
			SELECT *
			FROM `'._DB_PREFIX_.'category` c
			'.Shop::addSqlAssociation('category', 'c').'
			LEFT JOIN `'._DB_PREFIX_.'category_lang` cl ON c.`id_category` = cl.`id_category`'.Shop::addSqlRestrictionOnLang('cl').'
			WHERE 1 '.$sql_filter.' '.($id_lang ? 'AND `id_lang` = '.(int)$id_lang : '').'
			'.($active ? 'AND `active` = 1' : '').'
			'.(!$id_lang ? 'GROUP BY c.id_category' : '').'
			'.($sql_sort != '' ? $sql_sort : 'ORDER BY c.`level_depth` ASC, category_shop.`position` ASC').'
			'.($sql_limit != '' ? $sql_limit : '')
		);
		if (!$order)
			return $result;
		$categories = array();
		foreach ($result as $row)
			$categories[$row['id_parent']][$row['id_category']]['infos'] = $row;
		return $categories;
	}

	private function calculHookCommon($params)
	{
		$this->smarty->assign(array(
			'ENT_QUOTES' =>		ENT_QUOTES,
			'search_ssl' =>		Tools::usingSecureMode(),
			'ajaxsearch' =>		Configuration::get('ajax_search'),
			'self' =>			dirname(__FILE__),
		));
		return true;
	}
	private function _postProcess() {
        Configuration::updateValue($this->name . '_ajax_search', Tools::getValue('ajax_search'));
        $this->_html .= $this->displayConfirmation($this->l('Configuration updated'));
    }
	public function getContent() {
        $output = '<h2>' . $this->displayName . '</h2>';
        if (Tools::isSubmit('submitSearchCategory')) {
            if (!sizeof($this->_postErrors))
                $this->_postProcess();
            else {
                foreach ($this->_postErrors AS $err) {
                    $this->_html .= '<div class="alert error">' . $err . '</div>';
                }
            }
        }
        return $output . $this->_displayForm();
    }
	public  function _displayForm() {
		$id_lang = (int)Context::getContext()->language->id;
		$id_shop = (int)Context::getContext()->shop->id;
		$languages = $this->context->controller->getLanguages();
		$default_language = (int)Configuration::get('PS_LANG_DEFAULT');
		
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => Context::getContext()->getTranslator()->trans('Settings', array(), 'Modules.dor_searchcategories'),
                    'icon' => 'icon-cogs'
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => Context::getContext()->getTranslator()->trans('Enabled Ajax Search:', array(), 'Modules.dor_searchcategories'),
                        'name' => 'ajax_search',
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => Context::getContext()->getTranslator()->trans('Enabled', array(), 'Modules.dor_searchcategories')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => Context::getContext()->getTranslator()->trans('Disabled', array(), 'Modules.dor_searchcategories')
                            )
                        ),
                    )

                ),
                'submit' => array(
                    'title' => Context::getContext()->getTranslator()->trans('Save', array(), 'Modules.dor_searchcategories'),
                )
            ),
        );
        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $this->fields_form = array();
        $helper->id = (int)Tools::getValue('id_carrier');
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitSearchCategory';
        $helper->module = $this;
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id ,
        );
        $helper->override_folder = '/';
        return $helper->generateForm(array($fields_form));
    }
    public function getConfigFieldsValues()
    {
        return array(
            'ajax_search' =>     Tools::getValue('ajax_search', Configuration::get($this->name . '_ajax_search')),
        );
    }
}

