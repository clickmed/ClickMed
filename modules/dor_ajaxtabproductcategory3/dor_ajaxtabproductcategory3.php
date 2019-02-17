<?php

// Security
if (!defined('_PS_VERSION_'))
    exit;
if (!class_exists( 'DorImageBase' )) {     
    require_once (_PS_ROOT_DIR_.'/override/Dor/DorImageBase.php');
}
// Checking compatibility with older PrestaShop and fixing it
if (!defined('_MYSQL_ENGINE_'))
    define('_MYSQL_ENGINE_', 'MyISAM');
// Loading Models
use PrestaShop\PrestaShop\Core\Module\WidgetInterface;
use PrestaShop\PrestaShop\Adapter\Category\CategoryProductSearchProvider;
use PrestaShop\PrestaShop\Adapter\Image\ImageRetriever;
use PrestaShop\PrestaShop\Adapter\Product\PriceFormatter;
use PrestaShop\PrestaShop\Core\Product\ProductListingPresenter;
use PrestaShop\PrestaShop\Adapter\Product\ProductColorsRetriever;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchContext;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchQuery;
use PrestaShop\PrestaShop\Core\Product\Search\SortOrder;
class dor_ajaxtabproductcategory3 extends Module {
    private $_html = '';
    private $_postErrors = array();
    private $_show_level = 1;
	private $pattern = '/^([A-Z_]*)[0-9]+/';
	private $_menuLink = '';
	private $_menuLinkMobile = '';
	private $spacer_size = '5';

    public function __construct() {
        $this->name = 'dor_ajaxtabproductcategory3';
        $this->tab = 'front_office_features';
        $this->version = '1.0';
        $this->bootstrap =true;
        $this->author = 'Dorado Themes';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.5', 'max' => '1.7.99.99');
        parent::__construct();
        $this->displayName = $this->l('Dor Ajax Tab Product and Category 3');
        $this->description = $this->l('block config');
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
        $this->admin_tpl_path = _PS_MODULE_DIR_ . $this->name . '/views/templates/admin/';
        $this->selfPath = dirname(__FILE__).'/views/templates/hook';
        $this->product_path = dirname(__FILE__).'/views/templates/hook/product-item.tpl';
        $this->tproduct_path = _PS_ALL_THEMES_DIR_._THEME_NAME_.'/modules/'.$this->name.'/views/templates/hook/product-item.tpl';
        if (file_exists($this->tproduct_path))
            $this->product_path = $this->tproduct_path;
    }

    public function install() {
        // Install Tabs
        if(!(int)Tab::getIdFromClassName('AdminDorMenu')) {
            $parent_tab = new Tab();
            // Need a foreach for the language
            $parent_tab->name[$this->context->language->id] = $this->l('Dor Extensions');
            $parent_tab->class_name = 'AdminDorMenu';
            $parent_tab->id_parent = 0; // Home tab
            $parent_tab->module = $this->name;
            $parent_tab->add();
        }
        $tab = new Tab();
        foreach (Language::getLanguages() as $language)
        $tab->name[$language['id_lang']] = $this->l('Dor Ajax Tab Product and Category 3');
        $tab->class_name = 'AdminDorTabCategory3';
        $tab->id_parent = (int)Tab::getIdFromClassName('AdminDorMenu'); 
        $tab->module = $this->name;
        $tab->add();

        $today = date("Y-m-d");
        Configuration::updateValue($this->name . '_show_all3', 0);
        Configuration::updateValue($this->name . '_show_new3', 0);
        Configuration::updateValue($this->name . '_show_sale3', 0);
        Configuration::updateValue($this->name . '_show_feature3', 1);
        Configuration::updateValue($this->name . '_show_best3', 0);
        Configuration::updateValue($this->name . '_show_mostview3', 1);
        Configuration::updateValue($this->name . '_from_date3', "2000-01-01");
        Configuration::updateValue($this->name . '_to_date3', $today);
        Configuration::updateValue($this->name . '_show_icon3', 0);
        Configuration::updateValue($this->name . '_cate_data3', 0);
        Configuration::updateValue($this->name . '_merge_cate3', 1);
        Configuration::updateValue($this->name . '_effect3',2);
        Configuration::updateValue($this->name . '_number_product3',9);
        Configuration::updateValue($this->name . '_number_per_page3',9);
        Configuration::updateValue($this->name . '_default_tab_cate3',0);
        Configuration::updateValue($this->name . '_auto_play3',0);
        Configuration::updateValue($this->name . '_tab_quanlity_image3',100);
        Configuration::updateValue($this->name . '_removeProductIDs3',"");
        Configuration::updateValue($this->name . '_tab_thumb_width3',200);
        Configuration::updateValue($this->name . '_tab_thumb_height3',200);
        return parent::install() &&
                $this->registerHook('displayHeader')
                &&
                $this->registerHook('displayBackOfficeHeader')
                && 
                $this->registerHook('dorAjaxTabPro3');
    }

    public function uninstall() {
        $today = date("Y-m-d");
        $tab = new Tab((int) Tab::getIdFromClassName('AdminDorTabCategory2'));
        $tab->delete();
        Configuration::deleteByName($this->name . '_show_all3', 0);
        Configuration::deleteByName($this->name . '_show_new3', 1);
        Configuration::deleteByName($this->name . '_show_sale3', 0);
        Configuration::deleteByName($this->name . '_show_feature3', 0);
        Configuration::deleteByName($this->name . '_show_best3', 0);
        Configuration::deleteByName($this->name . '_show_mostview3', 0);
        Configuration::deleteByName($this->name . '_from_date3', "2000-01-01");
        Configuration::deleteByName($this->name . '_to_date3', $today);
        Configuration::deleteByName($this->name . '_show_icon3');
        Configuration::deleteByName($this->name . '_cate_data3');
        Configuration::deleteByName($this->name . '_merge_cate3');
        Configuration::deleteByName($this->name . '_effect3');
        Configuration::deleteByName($this->name . '_number_product3');
        Configuration::deleteByName($this->name . '_number_per_page3');
        Configuration::deleteByName($this->name . '_default_tab_cate3');
        Configuration::deleteByName($this->name . '_auto_play3');
        Configuration::deleteByName($this->name . '_tab_quanlity_image3');
        Configuration::deleteByName($this->name . '_removeProductIDs3');
        Configuration::deleteByName($this->name . '_tab_thumb_width3');
        Configuration::deleteByName($this->name . '_tab_thumb_height3');
        // Uninstall Module
        if (!parent::uninstall())
            return false;
        return true;
    }

    private function _postProcess() {
        Configuration::updateValue($this->name . '_show_all3', Tools::getValue('show_all3'));
        Configuration::updateValue($this->name . '_show_new3', Tools::getValue('show_new3'));
        Configuration::updateValue($this->name . '_show_sale3', Tools::getValue('show_sale3'));
        Configuration::updateValue($this->name . '_show_feature3', Tools::getValue('show_feature3'));
        Configuration::updateValue($this->name . '_show_best3', Tools::getValue('show_best3'));
        Configuration::updateValue($this->name . '_show_mostview3', Tools::getValue('show_mostview3'));
        Configuration::updateValue($this->name . '_from_date3', Tools::getValue('from_date3'));
        Configuration::updateValue($this->name . '_to_date3', Tools::getValue('to_date3'));
        Configuration::updateValue($this->name . '_show_icon3', Tools::getValue('show_icon3'));
        Configuration::updateValue($this->name . '_cate_data3', implode(',', Tools::getValue('cate_data3')));
        Configuration::updateValue($this->name . '_merge_cate3', Tools::getValue('merge_cate3'));
        Configuration::updateValue($this->name . '_effect3', Tools::getValue('effect3'));
        Configuration::updateValue($this->name . '_number_product3', Tools::getValue('number_product3'));
        Configuration::updateValue($this->name . '_number_per_page3', Tools::getValue('number_per_page3'));
        Configuration::updateValue($this->name . '_default_tab_cate3', Tools::getValue('default_tab_cate3'));
        Configuration::updateValue($this->name . '_auto_play3', Tools::getValue('auto_play3'));
        Configuration::updateValue($this->name . '_tab_quanlity_image3', Tools::getValue('tab_quanlity_image3'));
        Configuration::updateValue($this->name . '_removeProductIDs3', Tools::getValue('removeProductIDs3'));
        Configuration::updateValue($this->name . '_tab_thumb_width3', Tools::getValue('tab_thumb_width3'));
        Configuration::updateValue($this->name . '_tab_thumb_height3', Tools::getValue('tab_thumb_height3'));
        $this->_html .= $this->displayConfirmation($this->l('Configuration updated'));
    }
    public function getContent() {
        $output = '<h2>' . $this->displayName . '</h2>';
        if (Tools::isSubmit('submitProductCategory2')) {
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
		$languages = $this->context->controller->getLanguages();
		$default_language = (int)Configuration::get('PS_LANG_DEFAULT');
		$spacer = str_repeat('&nbsp;', $this->spacer_size);
		$divLangName = 'link_label';
		
        $effect = array(
            array( 'id'=>'0','mode'=>'SlideDown'),
            array('id'=>'1','mode'=>'FadeIn'),
            array('id'=>'2','mode'=>'Show'),

        );
        $id_shop = (int)Context::getContext()->shop->id;
        $categories =    $this->getCategoryOption(2, (int)$id_lang, (int)$id_shop);
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-cogs'
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Show All Products:'),
                        'name' => 'show_all3',
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Show New Products:'),
                        'name' => 'show_new3',
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Show special Products:'),
                        'name' => 'show_sale3',
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Show Bestselling Products: '),
                        'name' => 'show_best3',
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Show Feature Products: '),
                        'name' => 'show_feature3',
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Show Most View Products: '),
                        'name' => 'show_mostview3',
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                       'type' => 'text',
                       'label' => 'Most View From Date:',
                       'name' => 'from_date3',
                    ),
                    array(
                       'type' => 'text',
                       'label' => 'Most View To Date:',
                       'name' => 'to_date3',
                    ),
                    
                    array(
                        'type' => 'listnew',
                        'label' => 'Select Categories:',
                        'name' => 'cate_data3[]',
                        'multiple'=>true,
                    ),
                    array(
                        'type' => 'text',
                        'label' => 'Default Category ID:',
                        'name' => 'default_tab_cate3',
                        'class' => 'fixed-width-md',
                    ),
                    array(
                        'type' => 'text',
                        'label' => 'Limit Products:',
                        'name' => 'number_product3',
                        'class' => 'fixed-width-md',
                    ),
                    array(
                        'type' => 'text',
                        'label' => 'Number Per Page:',
                        'name' => 'number_per_page3',
                        'class' => 'fixed-width-md',
                    ),
                    array(
                        'type' => 'select',
                        'label' => 'Effect: ',
                        'name' => 'effect3',
                        'options' => array(
                            'query' => $effect,
                            'id' => 'id',
                            'name'=>'mode',
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => 'Merge small subcategories :',
                        'name' => 'merge_cate3',
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'text',
                        'label' => 'Remove Product ID:',
                        'name' => 'removeProductIDs3',
                        'class' => 'fixed-width-md',
                    ),
                    array(
                        'type' => 'switch',
                        'label' => 'Auto play :',
                        'name' => 'auto_play3',
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'text',
                        'label' => 'Quanlity Image:',
                        'name' => 'tab_quanlity_image3',
                        'class' => 'fixed-width-md',
                    ),
                    array(
                        'type' => 'text',
                        'label' => 'Thumb width image:',
                        'name' => 'tab_thumb_width3',
                        'class' => 'fixed-width-md',
                    ),
                    array(
                        'type' => 'text',
                        'label' => 'Thumb height image:',
                        'name' => 'tab_thumb_height3',
                        'class' => 'fixed-width-md',
                    ),

                ),
                'submit' => array(
                    'title' => $this->l('Save'),
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
        $helper->submit_action = 'submitProductCategory2';
        $helper->module = $this;
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id ,
            'cate_data3' => $categories,
        );
        $helper->override_folder = '/';
        return $helper->generateForm(array($fields_form));
    }
    public function getConfigFieldsValues()
    {
        return array(
            'show_all3' =>     Tools::getValue('show_all3', Configuration::get($this->name . '_show_all3')),
            'show_new3' =>     Tools::getValue('show_new3', Configuration::get($this->name . '_show_new3')),
            'show_sale3' =>   Tools::getValue('show_sale3', Configuration::get($this->name . '_show_sale3')),
            'show_feature3' =>     Tools::getValue('show_feature3', Configuration::get($this->name . '_show_feature3')),
            'show_best3' =>    Tools::getValue('show_best3', Configuration::get($this->name . '_show_best3')),
            'show_mostview3' =>    Tools::getValue('show_mostview3', Configuration::get($this->name . '_show_mostview3')),
            'from_date3' =>    Tools::getValue('from_date3', Configuration::get($this->name . '_from_date3')),
            'to_date3' =>    Tools::getValue('to_date3', Configuration::get($this->name . '_to_date3')),
            'cate_data3' => Tools::getValue('cate_data3', Configuration::get($this->name . '_cate_data3')),
            'show_depth' => Tools::getValue('show_depth', Configuration::get($this->name . '_show_depth')),
            'merge_cate3' => Tools::getValue('merge_cate3', Configuration::get($this->name . '_merge_cate3')),
            'effect3' => Tools::getValue('effect3', Configuration::get($this->name . '_effect3')),
            'number_product3' => Tools::getValue('number_product3', Configuration::get($this->name . '_number_product3')),
            'number_per_page3' => Tools::getValue('number_per_page3', Configuration::get($this->name . '_number_per_page3')),
            'default_tab_cate3' => Tools::getValue('default_tab_cate3', Configuration::get($this->name . '_default_tab_cate3')),
            'auto_play3' => Tools::getValue('auto_play3', Configuration::get($this->name . '_auto_play3')),
            'tab_quanlity_image3' => Tools::getValue('tab_quanlity_image3', Configuration::get($this->name . '_tab_quanlity_image3')),
            'removeProductIDs3' => Tools::getValue('removeProductIDs3', Configuration::get($this->name . '_removeProductIDs3')),
            'tab_thumb_width3' => Tools::getValue('tab_thumb_width3', Configuration::get($this->name . '_tab_thumb_width3')),
            'tab_thumb_height3' => Tools::getValue('tab_thumb_height3', Configuration::get($this->name . '_tab_thumb_height3')),
        );
    }
    public function fetchCategoryTree($id_category = 1, $dataReturn, $id_lang = false, $id_shop = false,$recursive = true) {
        if (!is_array($dataReturn))
        $dataReturn = array();
        $id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext()->language->id;
        $category = new Category((int)$id_category, (int)$id_lang, (int)$id_shop);
        if (is_null($category->id))
            return;
        if ($recursive)
        {
            $children = Category::getChildren((int)$id_category, (int)$id_lang, true, (int)$id_shop);
        }
        $dataReturn[] = array(
            "id_category" => $category->id_category
        );
        if (isset($children) && count($children))
            foreach ($children as $child)
                $dataReturn = $this->fetchCategoryTree((int)$child['id_category'], $dataReturn, (int)$id_lang, (int)$child['id_shop']);

        return $dataReturn ;
    }
    public  function getCategoryOption($id_category = 1, $id_lang = false, $id_shop = false,$recursive = true) {
        $cate = Configuration::get($this->name . '_cate_data3');
        $cateCurrent_new = explode(',', $cate);
        $id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext()->language->id;
        $category = new Category((int)$id_category, (int)$id_lang, (int)$id_shop);
        if (is_null($category->id))
            return;
        if ($recursive)
        {
            $children = Category::getChildren((int)$id_category, (int)$id_lang, true, (int)$id_shop);
            $spacer = str_repeat('&nbsp;', $this->spacer_size * (int)$category->level_depth);
        }
        $shop = (object) Shop::getShop((int)$category->getShopID());
                if(in_array((int)$category->id, $cateCurrent_new)){
                    $this->_html .= '<option title="'.(int)$category->id.'" value="'.(int)$category->id.'" selected ="selected" >'.(isset($spacer) ? $spacer : '').$category->name.' ('.$shop->name.')</option>';
                }else{
                    $this->_html .= '<option title="'.(int)$category->id.'" value="'.(int)$category->id.'">'.(isset($spacer) ? $spacer : '').$category->name.' ('.$shop->name.')</option>';
                }

        if (isset($children) && count($children))
            foreach ($children as $child)
                $this->getCategoryOption((int)$child['id_category'], (int)$id_lang, (int)$child['id_shop']);
        return $this->_html ;
    }
    public function hookDisplayHeader() {

        if (!isset($this->context->controller->php_self) || $this->context->controller->php_self != 'index')
            return;
        $this->context->controller->addCSS($this->_path . 'assets/css/dor_ajaxtabproductcategory3.css');
        $this->context->controller->addJS($this->_path . 'assets/js/dor_ajaxtabproductcategory3.js');
    }
    public function hookDisplayBackOfficeHeader()
    {
    }
    
    public function hookdorAjaxTabPro3($params){
        global $smarty, $cookie;
        $today = date("Y-m-d");
        $cateSelected = Configuration::get($this->name . '_cate_data3');
        $defaultTabID = Configuration::get($this->name . '_default_tab_cate3');
        $mergeCate = Configuration::get($this->name . '_merge_cate3');
        $nb = Configuration::get($this->name . '_number_product3');
        $per = Configuration::get($this->name.'_number_per_page3');
        $quanlity_image = Configuration::get($this->name.'_tab_quanlity_image3');
        $removeProductIDs3 = Configuration::get($this->name.'_removeProductIDs3');
        $thumbWidth = Configuration::get($this->name.'_tab_thumb_width3');
        $thumbHeight = Configuration::get($this->name.'_tab_thumb_height3');
        $thumbWidth = (isset($thumbWidth) && $thumbWidth != "")?$thumbWidth:450;
        $thumbHeight = (isset($thumbHeight) && $thumbHeight != "")?$thumbHeight:450;
        $fromDate = Configuration::get($this->name . '_from_date3');
        $toDate = Configuration::get($this->name . '_to_date3');
        $page = 0;
        if($fromDate == ""){
            $fromDate = "2000-01-01";
        }
        if($toDate == ""){
            $toDate = $today;
        }
        /****Tab Select****/
        $languages = Language::getLanguages(true, $this->context->shop->id);
        $productData = "";

        if(!$productData) $productData = null;
        $productTabslider = array();
        if(Configuration::get($this->name . '_show_all3')) {
            $productTabslider[] = array('id'=>'all_product','default'=>'all_product','name' => $this->l('All products'));
        }
        if(Configuration::get($this->name . '_show_new3')) {
            $productTabslider[] = array('id'=>'new_product','default'=>'new_product', 'name' => $this->l('New Products'));
        }
        if(Configuration::get($this->name . '_show_feature3')) {
            $productTabslider[] = array('id'=>'feature_product','default'=>'feature_product','name' => $this->l('Featured'));
        }
        if(Configuration::get($this->name . '_show_best3')) {
            $productTabslider[] = array('id'=>'besseller_product','default'=>'besseller_product','name' => $this->l('Best seller'));
        }
        if(Configuration::get($this->name . '_show_sale3')) {
            $productTabslider[] = array('id'=> 'special_product','default'=>'special_product','name' => $this->l('Must Have'));
        }
        if(Configuration::get($this->name . '_show_mostview3')) {
            $productTabslider[] = array('id'=> 'mostview_product','default'=>'mostview_product','name' => $this->l('Most View'));
        }
        $defaultTab = array();
        $cateInfo = $this->getCateInfo($cateSelected);
        if($defaultTabID != 0 && $defaultTabID != "" && count($productTabslider) == 0){
            $defaultTabID = explode(",", $defaultTabID);
            $defaultTabID = $defaultTabID[0];
        }else if(count($productTabslider) > 0 && $defaultTabID >= 0){
            $defaultTabID = $productTabslider[$defaultTabID]['id'];
            $defaultTab = array('id'=>$defaultTabID, 'name' => '');
        }else{
            $defaultTabID = $cateInfo[0]['id_category'];
        }
        $listTabs = array();
        
        if(count($cateInfo) > 0){
            foreach ($cateInfo as $key => $cate) {
                $listTabs[] = array('id'=>$cate['id_category'], 'name' => $cate['name']);
                if($cate['id_category'] == $defaultTabID && empty($defaultTab)){
                    $defaultTab = array('id'=>$cate['id_category'], 'name' => $cate['name']);
                }
            }
        }
        if(count($productTabslider) > 0){
            if($defaultTabID=="all_product"){
                $productData =  $this->getAllProductCategory ((int) Context::getContext()->language->id, $page, ($nb ? $nb : 10), null,  null);
            }else if($defaultTabID=="new_product"){
                $productData = Product::getNewProducts((int) Context::getContext()->language->id, $page, ($nb ? $nb : 5));
            }else if($defaultTabID=="feature_product"){
                $category = new Category(Context::getContext()->shop->getCategory(), (int) Context::getContext()->language->id);
                $productData = $category->getProducts((int) Context::getContext()->language->id, $page, ($nb ? $nb : 5));
            }else if($defaultTabID=="special_product"){
                $productData = Product::getPricesDrop((int) Context::getContext()->language->id, $page, ($nb ? $nb : 5));
            }else if($defaultTabID=="besseller_product"){
                ProductSale::fillProductSales();
                $productData =  $this->getBestSales ((int) Context::getContext()->language->id, $page, ($nb ? $nb : 5), null,  null);
            }else if($defaultTabID=="mostview_product"){
                $productData = $this->getTableMostViewed($fromDate,$toDate,($nb ? $nb : 5));
                $productData = $productData['products'];
            }
        }else{
            $productData =  $this->getProductCategory ($defaultTabID, $mergeCate, (int) Context::getContext()->language->id, 0, ($nb ? $nb : 10), null,  null);
        }

        if($productData){
            $assembler = new ProductAssembler($this->context);

            $presenterFactory = new ProductPresenterFactory($this->context);
            $presentationSettings = $presenterFactory->getPresentationSettings();
            $presenter = new ProductListingPresenter(
                new ImageRetriever(
                    $this->context->link
                ),
                $this->context->link,
                new PriceFormatter(),
                new ProductColorsRetriever(),
                $this->context->getTranslator()
            );

            $products_for_template = [];

            foreach ($productData as $rawProduct) {
                $products_for_template[] = $presenter->present(
                    $presentationSettings,
                    $assembler->assembleProduct($rawProduct),
                    $this->context->language
                );
            }
            $itemlists = array();
            $id_lang = (int)Context::getContext()->language->id;
            foreach ($products_for_template as $key => $item) {
                $id_image = Product::getCover($item['id_product']);
                $images = "";
                if (sizeof($id_image) > 0){
                    $image = new Image($id_image['id_image']);
                    // get image full URL
                    $image_url = "/p/".$image->getExistingImgPath().".jpg";
                    $linkRewrite = $item['id_product']."_".$id_image['id_image']."_".$item['link_rewrite'];
                    $images = DorImageBase::renderThumbProduct($image_url,$linkRewrite,$thumbWidth,$thumbHeight,$quanlity_image);
                }
                $item['imageThumb'] = $images;
                $itemlists[] = $item;
            }
        }

        $this->context->controller->addColorsToProductList($itemlists);
        $products = array_chunk($itemlists, 3);
        //$products = $itemlists;
        $options = array(
            'number_per_page3'=>$per
        );
        $cartUrl = $this->context->link->getPageLink('cart', true);
        $this->context->smarty->assign('static_token', Tools::getToken(false));
        $this->context->smarty->assign('cartUrl', $cartUrl);
        $this->context->smarty->assign('products', $products);
        $this->context->smarty->assign('listTabs', $listTabs);
        $this->context->smarty->assign('listTabsProduct', $productTabslider);
        $this->context->smarty->assign('tabID', $defaultTab);
        $this->context->smarty->assign('optionsConfig', $options);
        $this->context->smarty->assign('self', $this->selfPath);
        $this->context->smarty->assign('productItemPath', $this->product_path);
        $this->context->smarty->assign('page_name', $this->context->controller->php_self);
        return $this->display(__FILE__, 'dor_ajaxtabproductcategory3.tpl', $this->getCacheId());
    }
    public function clearCache()
    {
        $this->_clearCache('dor_ajaxtabproductcategory3.tpl');
    }
    public function hookAjaxCall($params)
    {
        global $smarty, $cookie;
        $varsData = "";
        $per = Configuration::get($this->name.'_number_per_page3');
        if(isset($_POST['cateID']) && $_POST['cateID'] != ""){
            $today = date("Y-m-d");
            $mergeCate = Configuration::get($this->name . '_merge_cate3');
            $nb = Configuration::get($this->name . '_number_product3');
            $quanlity_image = Configuration::get($this->name . '_tab_quanlity_image3');
            $removeProductIDs3 = Configuration::get($this->name . '_removeProductIDs3');
            $thumbWidth = Configuration::get($this->name . '_tab_thumb_width3');
            $thumbHeight = Configuration::get($this->name . '_tab_thumb_height3');
            $thumbWidth = (isset($thumbWidth) && $thumbWidth != "")?$thumbWidth:450;
            $thumbHeight = (isset($thumbHeight) && $thumbHeight != "")?$thumbHeight:450;
            $fromDate = Configuration::get($this->name . '_from_date3');
            $toDate = Configuration::get($this->name . '_to_date3');
            if($fromDate == ""){
                $fromDate = "2000-01-01";
            }
            if($toDate == ""){
                $toDate = $today;
            }
            $page = isset($_POST['page'])?$_POST['page']-1:0;
            if($_POST['type']==1){
                $productData =  $this->getProductCategory ($_POST['cateID'], $mergeCate, (int) Context::getContext()->language->id, $page, ($nb ? $nb : 10), null,  null);
            }else if($_POST['type']==0 && $_POST['cateID'] == "all_product"){
                $productData =  $this->getAllProductCategory ((int) Context::getContext()->language->id, $page, ($nb ? $nb : 10), null,  null);
            }else if($_POST['type']==0 && $_POST['cateID'] == "new_product"){
                $productData = Product::getNewProducts((int) Context::getContext()->language->id, $page, ($nb ? $nb : 5));
            }else if($_POST['type']==0 && $_POST['cateID'] == "feature_product"){
                $category = new Category(Context::getContext()->shop->getCategory(), (int) Context::getContext()->language->id);
                $productData = $category->getProducts((int) Context::getContext()->language->id, $page, ($nb ? $nb : 5));
            }else if($_POST['type']==0 && $_POST['cateID'] == "special_product"){
                $productData = Product::getPricesDrop((int) Context::getContext()->language->id, $page, ($nb ? $nb : 5));
            }else if($_POST['type']==0 && $_POST['cateID'] == "besseller_product"){
                ProductSale::fillProductSales();
                $productData =  $this->getBestSales ((int) Context::getContext()->language->id, $page, ($nb ? $nb : 5), null,  null);
            }else if($_POST['type']==0 && $_POST['cateID'] == "mostview_product"){
                $productData = $this->getTableMostViewed($fromDate,$toDate,($nb ? $nb : 5));
                $productData = $productData['products'];
            }

            if($productData){
                $assembler = new ProductAssembler($this->context);
                $presenterFactory = new ProductPresenterFactory($this->context);
                $presentationSettings = $presenterFactory->getPresentationSettings();
                $presenter = new ProductListingPresenter(
                    new ImageRetriever(
                        $this->context->link
                    ),
                    $this->context->link,
                    new PriceFormatter(),
                    new ProductColorsRetriever(),
                    $this->context->getTranslator()
                );

                $products_for_template = [];

                foreach ($productData as $rawProduct) {
                    $products_for_template[] = $presenter->present(
                        $presentationSettings,
                        $assembler->assembleProduct($rawProduct),
                        $this->context->language
                    );
                }
                $itemlists = array();
                $id_lang = (int)Context::getContext()->language->id;
                foreach ($products_for_template as $key => $item) {
                    $id_image = Product::getCover($item['id_product']);
                    $images = "";
                    if (sizeof($id_image) > 0){
                        $image = new Image($id_image['id_image']);
                        // get image full URL
                        $image_url = "/p/".$image->getExistingImgPath().".jpg";
                        $linkRewrite = $item['id_product']."_".$id_image['id_image']."_".$item['link_rewrite'];
                        $images = DorImageBase::renderThumbProduct($image_url,$linkRewrite,$thumbWidth,$thumbHeight,$quanlity_image);
                    }
                    $item['imageThumb'] = $images;
                    $itemlists[] = $item;
                }
            }
            $this->context->controller->addColorsToProductList($itemlists);
            $products = array();
            if($itemlists){
                $products = array_chunk($itemlists, 3);
            }
            
            
            $defaultTab = array('id'=>$_POST['cateID'], 'name' => "");
            $options = array(
                'number_per_page3'=>$per
            );
            $cartUrl = $this->context->link->getPageLink('cart', true);
            $this->context->smarty->assign('static_token', Tools::getToken(false));
            $this->context->smarty->assign('cartUrl', $cartUrl);
            $this->context->smarty->assign('lists', $products);
            $this->context->smarty->assign('options', $options);
            $this->context->smarty->assign('tabID', $defaultTab);
            $this->context->smarty->assign('self', $this->selfPath);
            $this->context->smarty->assign('page_name', "index");
            $varsData = $this->display(__FILE__, '/views/templates/hook/product-item.tpl', $this->getCacheId()."-".$_POST['cateID']);
        }
        $dataResult = Tools::jsonEncode($varsData);
        return $dataResult;
    }

     public function RotatorImg($idproduct) {
            $id_shop = (int)Context::getContext()->shop->id;
            $sql = 'SELECT * FROM `' . _DB_PREFIX_ . 'image` img'; 
            $sql .= ' LEFT JOIN `'. _DB_PREFIX_ . 'image_shop` imgs';
            $sql .= ' ON img.id_image = imgs.id_image';
            $sql .= ' where imgs.`id_shop` ='.$id_shop ;
            $sql .= ' AND img.`id_product` ='.$idproduct ;
            $sql .= ' AND imgs.`rotator` =1' ;
            $imageNew = Db::getInstance()->ExecuteS($sql);
            if(!$imageNew) {
                  $sql = 'SELECT * FROM `' . _DB_PREFIX_ . 'image` img'; 
                  $sql .= ' where img.`rotator` =1';
                  $sql .= ' AND img.`id_product` ='.$idproduct ;
                  $imageNew = Db::getInstance()->ExecuteS($sql);
            }

            $images = array(
                'rotator_img'=>$imageNew,
                'idproduct'=>$idproduct
            );

        return $images;
    }

    public function getCateInfo($CateID){
        if($CateID == null || $CateID == "") return;
        if (!$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
                SELECT DISTINCT c.id_parent, c.id_category, cl.name, cl.description, cl.link_rewrite
                FROM `' . _DB_PREFIX_ . 'category` c
                INNER JOIN `' . _DB_PREFIX_ . 'category_lang` cl ON (c.`id_category` = cl.`id_category` AND cl.`id_lang` = ' . (int) $this->context->language->id . Shop::addSqlRestrictionOnLang('cl') . ')
                INNER JOIN `' . _DB_PREFIX_ . 'category_shop` cs ON (cs.`id_category` = c.`id_category` AND cs.`id_shop` = ' . (int) $this->context->shop->id . ')
                WHERE (c.`active` = 1 AND c.`id_category` IN (' . pSQL($CateID) . '))'))
            return;
        return $result;
    }

    public function getblockCategTree() {

        // Get all groups for this customer and concatenate them as a string: "1,2,3..."
        $groups = implode(', ', Customer::getGroupsStatic((int) $this->context->customer->id));
        $maxdepth = Configuration::get('BLOCK_CATEG_MAX_DEPTH');
        if (!$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
				SELECT DISTINCT c.id_parent, c.id_category, cl.name, cl.description, cl.link_rewrite
				FROM `' . _DB_PREFIX_ . 'category` c
				INNER JOIN `' . _DB_PREFIX_ . 'category_lang` cl ON (c.`id_category` = cl.`id_category` AND cl.`id_lang` = ' . (int) $this->context->language->id . Shop::addSqlRestrictionOnLang('cl') . ')
				INNER JOIN `' . _DB_PREFIX_ . 'category_shop` cs ON (cs.`id_category` = c.`id_category` AND cs.`id_shop` = ' . (int) $this->context->shop->id . ')
				WHERE (c.`active` = 1 OR c.`id_category` = ' . (int) Configuration::get('PS_HOME_CATEGORY') . ')
				AND c.`id_category` != ' . (int) Configuration::get('PS_ROOT_CATEGORY') . '
				' . ((int) $maxdepth != 0 ? ' AND `level_depth` <= ' . (int) $maxdepth : '') . '
				AND c.id_category IN (SELECT id_category FROM `' . _DB_PREFIX_ . 'category_group` WHERE `id_group` IN (' . pSQL($groups) . '))
				ORDER BY `level_depth` ASC, ' . (Configuration::get('BLOCK_CATEG_SORT') ? 'cl.`name`' : 'cs.`position`') . ' ' . (Configuration::get('BLOCK_CATEG_SORT_WAY') ? 'DESC' : 'ASC')))
            return;

        $resultParents = array();
        $resultIds = array();

        foreach ($result as &$row) {
            $resultParents[$row['id_parent']][] = &$row;
            $resultIds[$row['id_category']] = &$row;
        }

        $blockCategTree = $this->getTree($resultParents, $resultIds, Configuration::get('BLOCK_CATEG_MAX_DEPTH'));
        unset($resultParents, $resultIds);

        $id_category = (int) Tools::getValue('id_category');
        $id_product = (int) Tools::getValue('id_product');


        if (Tools::isSubmit('id_category')) {
            $this->context->cookie->last_visited_category = $id_category;
            $this->smarty->assign('currentCategoryId', $this->context->cookie->last_visited_category);
        }
        if (Tools::isSubmit('id_product')) {
            if (!isset($this->context->cookie->last_visited_category)
                    || !Product::idIsOnCategoryId($id_product, array('0' => array('id_category' => $this->context->cookie->last_visited_category)))
                    || !Category::inShopStatic($this->context->cookie->last_visited_category, $this->context->shop)) {
                $product = new Product($id_product);
                if (isset($product) && Validate::isLoadedObject($product))
                    $this->context->cookie->last_visited_category = (int) $product->id_category_default;
            }
            $this->smarty->assign('currentCategoryId', (int) $this->context->cookie->last_visited_category);
        }
        return $blockCategTree;
    }
	
    public function getProductCategory($idCate, $mergeCate, $id_lang, $page_number = 0, $nb_products = 10, $order_by = null, $order_way = null)
    {
        if ($page_number < 0) $page_number = 0;
        if ($nb_products < 1) $nb_products = 10;
        $final_order_by = $order_by;
        $order_table = '';      
        if (is_null($order_by) || $order_by == 'position' || $order_by == 'price') $order_by = 'p.id_product';
        if ($order_by == 'date_add' || $order_by == 'date_upd')
            $order_table = 'product_shop';              
        if (is_null($order_way) || $order_by == 'p.id_product') $order_way = 'DESC';
        $groups = FrontController::getCurrentCustomerGroups();
        $sql_groups = (count($groups) ? 'IN ('.implode(',', $groups).')' : '= 1');
        $interval = Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20;
        
        $prefix = '';
        $whereCate = ' AND cp.`id_category` = '.$idCate;
        if($mergeCate){
            $categories =  $this->fetchCategoryTree((int)$idCate,"",(int) Context::getContext()->language->id,0);
            $arrayCates = array();
            foreach ($categories as $key => $cate) {
                $arrayCates[] = $cate['id_category'];
            }
            $whereCate = ' AND cp.`id_category` IN ('.implode(',', array_map('intval', $arrayCates)).')';
        }


        if ($order_by == 'date_add')
            $prefix = 'p.';
        
        $sql = 'SELECT p.*, product_shop.*, stock.out_of_stock, IFNULL(stock.quantity, 0) as quantity, pl.`link_rewrite`, pl.`name`,
                    MAX(image_shop.`id_image`) id_image, il.`legend`, t.`rate`,
                    DATEDIFF(p.`date_add`, DATE_SUB(NOW(),
                    INTERVAL '.$interval.' DAY)) > 0 AS new
                FROM `'._DB_PREFIX_.'product` p
                '.Shop::addSqlAssociation('product', 'p', false).'
                INNER JOIN `'._DB_PREFIX_.'category_product` cp ON (cp.`id_product` = p.`id_product`)
                LEFT JOIN `'._DB_PREFIX_.'product_lang` pl
                    ON p.`id_product` = pl.`id_product`
                    AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl').'
                LEFT JOIN `'._DB_PREFIX_.'image` i ON (i.`id_product` = p.`id_product`)'.
                Shop::addSqlAssociation('image', 'i', false, 'image_shop.cover=1').'
                LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (i.`id_image` = il.`id_image` AND il.`id_lang` = '.(int)$id_lang.')
                LEFT JOIN `'._DB_PREFIX_.'tax_rule` tr ON (product_shop.`id_tax_rules_group` = tr.`id_tax_rules_group`)
                    AND tr.`id_country` = '.(int)Context::getContext()->country->id.'
                    AND tr.`id_state` = 0
                LEFT JOIN `'._DB_PREFIX_.'tax` t ON (t.`id_tax` = tr.`id_tax`)
                '.Product::sqlStock('p').'
                WHERE product_shop.`active` = 1
                    AND product_shop.`visibility` != \'none\'
                    '.$whereCate.'
                GROUP BY product_shop.id_product
                ORDER BY '.(!empty($order_table) ? '`'.pSQL($order_table).'`.' : '').'p.`id_product` '.pSQL($order_way).'
                LIMIT '.(int)($page_number * $nb_products).', '.(int)$nb_products;
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
        if ($final_order_by == 'price')
            Tools::orderbyPrice($result, $order_way);
        if (!$result)
            return false;
        return Product::getProductsProperties($id_lang, $result);
    }


    public function getAllProductCategory($id_lang, $page_number = 0, $nb_products = 10, $order_by = null, $order_way = null)
    {
        if ($page_number < 0) $page_number = 0;
        if ($nb_products < 1) $nb_products = 10;
        $final_order_by = $order_by;
        $final_order_by = 'price';
        $order_table = 'product_shop';    
        if (is_null($order_by) || $order_by == 'position' || $order_by == 'price') $order_by = 'p.id_product';
        if ($order_by == 'date_add' || $order_by == 'date_upd')
            $order_table = 'product_shop';              
        if (is_null($order_way) || $order_by == 'p.id_product') $order_way = 'DESC';
        $groups = FrontController::getCurrentCustomerGroups();
        $sql_groups = (count($groups) ? 'IN ('.implode(',', $groups).')' : '= 1');
        $interval = Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20;
        $prefix = '';
        if ($order_by == 'date_add')
            $prefix = 'p.';
        
        $sql = 'SELECT p.*, product_shop.*, stock.out_of_stock, IFNULL(stock.quantity, 0) as quantity, pl.`link_rewrite`, pl.`name`,
                    MAX(image_shop.`id_image`) id_image, il.`legend`, t.`rate`,
                    DATEDIFF(p.`date_add`, DATE_SUB(NOW(),
                    INTERVAL '.$interval.' DAY)) > 0 AS new
                FROM `'._DB_PREFIX_.'product` p
                '.Shop::addSqlAssociation('product', 'p', false).'
                LEFT JOIN `'._DB_PREFIX_.'product_lang` pl
                    ON p.`id_product` = pl.`id_product`
                    AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl').'
                LEFT JOIN `'._DB_PREFIX_.'image` i ON (i.`id_product` = p.`id_product`)'.
                Shop::addSqlAssociation('image', 'i', false, 'image_shop.cover=1').'
                LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (i.`id_image` = il.`id_image` AND il.`id_lang` = '.(int)$id_lang.')
                LEFT JOIN `'._DB_PREFIX_.'tax_rule` tr ON (product_shop.`id_tax_rules_group` = tr.`id_tax_rules_group`)
                    AND tr.`id_country` = '.(int)Context::getContext()->country->id.'
                    AND tr.`id_state` = 0
                LEFT JOIN `'._DB_PREFIX_.'tax` t ON (t.`id_tax` = tr.`id_tax`)
                '.Product::sqlStock('p').'
                WHERE product_shop.`active` = 1
                    AND product_shop.`visibility` != \'none\'
                GROUP BY product_shop.id_product
                ORDER BY product_shop.`price` '.pSQL($order_way).'
                LIMIT '.(int)($page_number * $nb_products).', '.(int)$nb_products;
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
        if ($final_order_by == 'price')
            Tools::orderbyPrice($result, $order_way);
        if (!$result)
            return false;
        return Product::getProductsProperties($id_lang, $result);
    }

    public static function getBestSales($id_lang, $page_number = 0, $nb_products = 10, $order_by = null, $order_way = null)
    {
        if ($page_number < 0) $page_number = 0;
        if ($nb_products < 1) $nb_products = 10;
        $final_order_by = $order_by;
        $order_table = '';      
        if (is_null($order_by) || $order_by == 'position' || $order_by == 'price') $order_by = 'sales';
        if ($order_by == 'date_add' || $order_by == 'date_upd')
            $order_table = 'product_shop';              
        if (is_null($order_way) || $order_by == 'sales') $order_way = 'DESC';
        $groups = FrontController::getCurrentCustomerGroups();
        $sql_groups = (count($groups) ? 'IN ('.implode(',', $groups).')' : '= 1');
        $interval = Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20;
        
        $prefix = '';
        if ($order_by == 'date_add')
            $prefix = 'p.';
        
        $sql = 'SELECT p.*, product_shop.*, stock.out_of_stock, IFNULL(stock.quantity, 0) as quantity, pl.`link_rewrite`, pl.`name`,
                    MAX(image_shop.`id_image`) id_image, il.`legend`,
                    ps.`quantity` AS sales, t.`rate`,
                    DATEDIFF(p.`date_add`, DATE_SUB(NOW(),
                    INTERVAL '.$interval.' DAY)) > 0 AS new
                FROM `'._DB_PREFIX_.'product_sale` ps
                LEFT JOIN `'._DB_PREFIX_.'product` p ON ps.`id_product` = p.`id_product`
                '.Shop::addSqlAssociation('product', 'p', false).'
                LEFT JOIN `'._DB_PREFIX_.'product_lang` pl
                    ON p.`id_product` = pl.`id_product`
                    AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl').'
                LEFT JOIN `'._DB_PREFIX_.'image` i ON (i.`id_product` = p.`id_product`)'.
                Shop::addSqlAssociation('image', 'i', false, 'image_shop.cover=1').'
                LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (i.`id_image` = il.`id_image` AND il.`id_lang` = '.(int)$id_lang.')
                LEFT JOIN `'._DB_PREFIX_.'tax_rule` tr ON (product_shop.`id_tax_rules_group` = tr.`id_tax_rules_group`)
                    AND tr.`id_country` = '.(int)Context::getContext()->country->id.'
                    AND tr.`id_state` = 0
                LEFT JOIN `'._DB_PREFIX_.'tax` t ON (t.`id_tax` = tr.`id_tax`)
                '.Product::sqlStock('p').'
                WHERE product_shop.`active` = 1
                    AND product_shop.`visibility` != \'none\'
                    AND p.`id_product` IN (
                        SELECT cp.`id_product`
                        FROM `'._DB_PREFIX_.'category_group` cg
                        LEFT JOIN `'._DB_PREFIX_.'category_product` cp ON (cp.`id_category` = cg.`id_category`)
                        WHERE cg.`id_group` '.$sql_groups.'
                    )
                GROUP BY product_shop.id_product
                ORDER BY '.(!empty($order_table) ? '`'.pSQL($order_table).'`.' : '').'`'.pSQL($order_by).'` '.pSQL($order_way).'
                LIMIT '.(int)($page_number * $nb_products).', '.(int)$nb_products;

        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

        if ($final_order_by == 'price')
            Tools::orderbyPrice($result, $order_way);
        if (!$result)
            return false;
        return Product::getProductsProperties($id_lang, $result);
    }

    public function getTableMostViewed($date_from, $date_to, $limit=2)
    {
        if (Configuration::get('PS_STATSDATA_PAGESVIEWS'))
            $data = $this->getTotalViewed($date_from, $date_to, $limit);
        else
            $data = '<div class="alert alert-info">'.$this->l('You must enable the "Save global page views" option from the "Data mining for statistics" module in order to display the most viewed products, or use the Google Analytics module.').'</div>';
        return array('products' => $data);
    }

    public function getTotalViewed($date_from, $date_to, $limit = 10)
    {
        $id_lang = (int)Context::getContext()->language->id;
        $sql = '
            SELECT p.*,pa.id_object, pv.counter, pl.link_rewrite, pl.`name`, product_shop.*
            FROM `'._DB_PREFIX_.'page_viewed` pv
            LEFT JOIN `'._DB_PREFIX_.'date_range` dr ON pv.`id_date_range` = dr.`id_date_range`
            LEFT JOIN `'._DB_PREFIX_.'page` pa ON pv.`id_page` = pa.`id_page`
            LEFT JOIN `'._DB_PREFIX_.'product` p ON p.`id_product` = pa.`id_object`
            '.Shop::addSqlAssociation('product', 'p', false).'
            LEFT JOIN `'._DB_PREFIX_.'product_lang` pl
                    ON p.`id_product` = pl.`id_product`
                    AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl').'
            LEFT JOIN `'._DB_PREFIX_.'page_type` pt ON pt.`id_page_type` = pa.`id_page_type`
            LEFT JOIN `'._DB_PREFIX_.'tax_rule` tr ON (product_shop.`id_tax_rules_group` = tr.`id_tax_rules_group`)
            WHERE pt.`name` = \'product\'
                    AND product_shop.`active` = 1
                    AND product_shop.`visibility` != \'none\'
            '.Shop::addSqlRestriction(false, 'pv').'
            AND dr.`time_start` BETWEEN "'.pSQL($date_from).'" AND "'.pSQL($date_to).'"
            AND dr.`time_end` BETWEEN "'.pSQL($date_from).'" AND "'.pSQL($date_to).'"
            GROUP BY product_shop.id_product
            ORDER BY pv.counter DESC
            LIMIT '.(int)$limit;
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
        return Product::getProductsProperties($id_lang, $result);
    }
}