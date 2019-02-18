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
use PrestaShop\PrestaShop\Core\Product\ProductPresenter;
use PrestaShop\PrestaShop\Adapter\Product\ProductColorsRetriever;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchContext;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchQuery;
use PrestaShop\PrestaShop\Core\Product\Search\SortOrder;
use PrestaShop\PrestaShop\Core\Product\ProductExtraContentFinder;
class dor_ajaxtabproductcategory extends Module {
    private $_html = '';
    private $_postErrors = array();
    private $_show_level = 1;
	private $pattern = '/^([A-Z_]*)[0-9]+/';
	private $_menuLink = '';
	private $_menuLinkMobile = '';
	private $spacer_size = '5';

    public function __construct() {
        $this->name = 'dor_ajaxtabproductcategory';
        $this->tab = 'front_office_features';
        $this->version = '2.0.0';
        $this->bootstrap =true;
        $this->author = 'Dorado Themes';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.5', 'max' => '1.7.99.99');
        parent::__construct();
        $this->displayName = $this->l('Dor Ajax Tab Product and Category');
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
        $tab->name[$language['id_lang']] = $this->l('Dor Ajax Tab Product and Category');
        $tab->class_name = 'AdminDorTabCategory';
        $tab->id_parent = (int)Tab::getIdFromClassName('AdminDorMenu'); 
        $tab->module = $this->name;
        $tab->add();
        $today = date("Y-m-d");
        Configuration::updateValue($this->name . '_show_all', 0);
        Configuration::updateValue($this->name . '_show_new', 1);
        Configuration::updateValue($this->name . '_show_sale', 0);
        Configuration::updateValue($this->name . '_show_feature', 1);
        Configuration::updateValue($this->name . '_show_toprated', 0);
        Configuration::updateValue($this->name . '_show_best', 0);
        Configuration::updateValue($this->name . '_show_mostview', 1);
        Configuration::updateValue($this->name . '_from_date', "2000-01-01");
        Configuration::updateValue($this->name . '_to_date', $today);
        Configuration::updateValue($this->name . '_show_icon', 0);
        Configuration::updateValue($this->name . '_cate_data', 0);
        Configuration::updateValue($this->name . '_merge_cate', 1);
        Configuration::updateValue($this->name . '_effect',2);
        Configuration::updateValue($this->name . '_number_product',8);
        Configuration::updateValue($this->name . '_number_per_page',8);
        Configuration::updateValue($this->name . '_default_tab_cate',0);
        Configuration::updateValue($this->name . '_auto_play',0);
        Configuration::updateValue($this->name . '_tab_quanlity_image',100);
        Configuration::updateValue($this->name . '_removeProductIDs',"");
        Configuration::updateValue($this->name . '_tab_thumb_width',450);
        Configuration::updateValue($this->name . '_tab_thumb_height',450);
        return parent::install() &&
                $this->registerHook('displayHeader')
                &&
                $this->registerHook('displayBackOfficeHeader')
                &&
				$this->registerHook('home')
                && 
                $this->registerHook('dorTabProductCate');
    }

    public function uninstall() {
        $today = date("Y-m-d");
        $tab = new Tab((int) Tab::getIdFromClassName('AdminDorTabCategory'));
        $tab->delete();
        Configuration::deleteByName($this->name . '_show_all', 1);
        Configuration::deleteByName($this->name . '_show_new', 1);
        Configuration::deleteByName($this->name . '_show_sale', 1);
        Configuration::deleteByName($this->name . '_show_feature', 0);
        Configuration::deleteByName($this->name . '_show_toprated', 0);
        Configuration::deleteByName($this->name . '_show_best', 1);
        Configuration::deleteByName($this->name . '_show_mostview', 0);
        Configuration::deleteByName($this->name . '_from_date', "2000-01-01");
        Configuration::deleteByName($this->name . '_to_date', $today);
        Configuration::deleteByName($this->name . '_show_icon');
        Configuration::deleteByName($this->name . '_cate_data');
        Configuration::deleteByName($this->name . '_merge_cate');
        Configuration::deleteByName($this->name . '_effect');
        Configuration::deleteByName($this->name . '_number_product');
        Configuration::deleteByName($this->name . '_number_per_page');
        Configuration::deleteByName($this->name . '_default_tab_cate');
        Configuration::deleteByName($this->name . '_auto_play');
        Configuration::deleteByName($this->name . '_tab_quanlity_image');
        Configuration::deleteByName($this->name . '_removeProductIDs');
        Configuration::deleteByName($this->name . '_tab_thumb_width');
        Configuration::deleteByName($this->name . '_tab_thumb_height');
        // Uninstall Module
        if (!parent::uninstall())
            return false;
        return true;
    }

    private function _postProcess() {
        Configuration::updateValue($this->name . '_show_all', Tools::getValue('show_all'));
        Configuration::updateValue($this->name . '_show_new', Tools::getValue('show_new'));
        Configuration::updateValue($this->name . '_show_sale', Tools::getValue('show_sale'));
        Configuration::updateValue($this->name . '_show_feature', Tools::getValue('show_feature'));
        Configuration::updateValue($this->name . '_show_toprated', Tools::getValue('show_toprated'));
        Configuration::updateValue($this->name . '_show_best', Tools::getValue('show_best'));
        Configuration::updateValue($this->name . '_show_mostview', Tools::getValue('show_mostview'));
        Configuration::updateValue($this->name . '_from_date', Tools::getValue('from_date'));
        Configuration::updateValue($this->name . '_to_date', Tools::getValue('to_date'));
        Configuration::updateValue($this->name . '_show_icon', Tools::getValue('show_icon'));
        Configuration::updateValue($this->name . '_cate_data', implode(',', Tools::getValue('cate_data')));
        Configuration::updateValue($this->name . '_merge_cate', Tools::getValue('merge_cate'));
        Configuration::updateValue($this->name . '_effect', Tools::getValue('effect'));
        Configuration::updateValue($this->name . '_number_product', Tools::getValue('number_product'));
        Configuration::updateValue($this->name . '_number_per_page', Tools::getValue('number_per_page'));
        Configuration::updateValue($this->name . '_default_tab_cate', Tools::getValue('default_tab_cate'));
        Configuration::updateValue($this->name . '_auto_play', Tools::getValue('auto_play'));
        Configuration::updateValue($this->name . '_tab_quanlity_image', Tools::getValue('tab_quanlity_image'));
        Configuration::updateValue($this->name . '_removeProductIDs', Tools::getValue('removeProductIDs'));
        Configuration::updateValue($this->name . '_tab_thumb_width', Tools::getValue('tab_thumb_width'));
        Configuration::updateValue($this->name . '_tab_thumb_height', Tools::getValue('tab_thumb_height'));
        $this->_html .= $this->displayConfirmation($this->l('Configuration updated'));
    }
    public function getContent() {
        $output = '<h2>' . $this->displayName . '</h2>';
        if (Tools::isSubmit('submitProductCategory')) {
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
		
		if (Tools::isSubmit('submitBlocktopmegaEdit'))
		{
			$id_linksmegatop = (int)Tools::getValue('id_linksmegatop', 0);
			
			//$id_shop = (int)Shop::getContextShopID();
            $id_shop = (int)Context::getContext()->shop->id;

			if (!Tools::isSubmit('link'))
			{ 
				$tmp = MegaTopLinks::getLinkLang($id_linksmegatop, $id_shop);
				$links_label_edit = $tmp['link'];
				$labels_edit = $tmp['label'];
				$new_window_edit = $tmp['new_window'];
			}
			else
			{ 
				MegaTopLinks::update(Tools::getValue('link'), Tools::getValue('label'), Tools::getValue('new_window', 0), (int)$id_shop, (int)$id_linksmegatop, (int)$id_linksmegatop);
				$this->_html .= $this->displayConfirmation($this->l('The link has been edited'));
			}
		
		}
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
                        'name' => 'show_all',
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
                        'name' => 'show_new',
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
                        'name' => 'show_sale',
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
                        'name' => 'show_best',
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
                        'name' => 'show_feature',
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
                        'label' => $this->l('Show Top Rated Products: '),
                        'name' => 'show_toprated',
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
                        'name' => 'show_mostview',
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
                       'name' => 'from_date',
                    ),
                    array(
                       'type' => 'text',
                       'label' => 'Most View To Date:',
                       'name' => 'to_date',
                    ),
                    
                    array(
                        'type' => 'listnew',
                        'label' => 'Select Categories:',
                        'name' => 'cate_data[]',
                        'multiple'=>true,
                    ),
                    array(
                        'type' => 'text',
                        'label' => 'Default Category ID:',
                        'name' => 'default_tab_cate',
                        'class' => 'fixed-width-md',
                    ),
                    array(
                        'type' => 'text',
                        'label' => 'Limit Products:',
                        'name' => 'number_product',
                        'class' => 'fixed-width-md',
                    ),
                    array(
                        'type' => 'text',
                        'label' => 'Number Per Page:',
                        'name' => 'number_per_page',
                        'class' => 'fixed-width-md',
                    ),
                    array(
                        'type' => 'select',
                        'label' => 'Effect: ',
                        'name' => 'effect',
                        'options' => array(
                            'query' => $effect,
                            'id' => 'id',
                            'name'=>'mode',
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => 'Merge small subcategories :',
                        'name' => 'merge_cate',
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
                        'name' => 'removeProductIDs',
                        'class' => 'fixed-width-md',
                    ),
                    array(
                        'type' => 'switch',
                        'label' => 'Auto play :',
                        'name' => 'auto_play',
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
                        'name' => 'tab_quanlity_image',
                        'class' => 'fixed-width-md',
                    ),
                    array(
                        'type' => 'text',
                        'label' => 'Thumb width image:',
                        'name' => 'tab_thumb_width',
                        'class' => 'fixed-width-md',
                    ),
                    array(
                        'type' => 'text',
                        'label' => 'Thumb height image:',
                        'name' => 'tab_thumb_height',
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
        $helper->submit_action = 'submitProductCategory';
        $helper->module = $this;
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id ,
            'cate_data' => $categories,
        );
        $helper->override_folder = '/';
        return $helper->generateForm(array($fields_form));
    }
    public function getConfigFieldsValues()
    {
        return array(
            'show_all' =>     Tools::getValue('show_all', Configuration::get($this->name . '_show_all')),
            'show_new' =>     Tools::getValue('show_new', Configuration::get($this->name . '_show_new')),
            'show_sale' =>   Tools::getValue('show_sale', Configuration::get($this->name . '_show_sale')),
            'show_feature' =>     Tools::getValue('show_feature', Configuration::get($this->name . '_show_feature')),
            'show_toprated' =>     Tools::getValue('show_toprated', Configuration::get($this->name . '_show_toprated')),
            'show_best' =>    Tools::getValue('show_best', Configuration::get($this->name . '_show_best')),
            'show_mostview' =>    Tools::getValue('show_mostview', Configuration::get($this->name . '_show_mostview')),
            'from_date' =>    Tools::getValue('from_date', Configuration::get($this->name . '_from_date')),
            'to_date' =>    Tools::getValue('to_date', Configuration::get($this->name . '_to_date')),
            'cate_data' => Tools::getValue('cate_data', Configuration::get($this->name . '_cate_data')),
            'show_depth' => Tools::getValue('show_depth', Configuration::get($this->name . '_show_depth')),
            'merge_cate' => Tools::getValue('merge_cate', Configuration::get($this->name . '_merge_cate')),
            'effect' => Tools::getValue('effect', Configuration::get($this->name . '_effect')),
            'number_product' => Tools::getValue('number_product', Configuration::get($this->name . '_number_product')),
            'number_per_page' => Tools::getValue('number_per_page', Configuration::get($this->name . '_number_per_page')),
            'default_tab_cate' => Tools::getValue('default_tab_cate', Configuration::get($this->name . '_default_tab_cate')),
            'auto_play' => Tools::getValue('auto_play', Configuration::get($this->name . '_auto_play')),
            'tab_quanlity_image' => Tools::getValue('tab_quanlity_image', Configuration::get($this->name . '_tab_quanlity_image')),
            'removeProductIDs' => Tools::getValue('removeProductIDs', Configuration::get($this->name . '_removeProductIDs')),
            'tab_thumb_width' => Tools::getValue('tab_thumb_width', Configuration::get($this->name . '_tab_thumb_width')),
            'tab_thumb_height' => Tools::getValue('tab_thumb_height', Configuration::get($this->name . '_tab_thumb_height')),
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
        $cate = Configuration::get($this->name . '_cate_data');
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
        $this->context->controller->addCSS($this->_path . 'assets/css/dor_ajaxtabproductcategory.css');
        $this->context->controller->addJS($this->_path . 'assets/js/dor_ajaxtabproductcategory.js');
    }
    public function hookDisplayBackOfficeHeader()
    {
        $this->context->controller->addCSS($this->_path . 'assets/css/dor_ajaxtabproductcategory_admin.css');
        $this->context->controller->addJS($this->_path . 'assets/js/dor_ajaxtabproductcategory_admin.js');
    }
    public function hookdorTabProductCate($params){
        global $smarty, $cookie;
        $today = date("Y-m-d");
        $cateSelected = Configuration::get($this->name . '_cate_data');
        $defaultTabID = Configuration::get($this->name . '_default_tab_cate');
        $mergeCate = Configuration::get($this->name . '_merge_cate');
        $nb = Configuration::get($this->name . '_number_product');
        $per = Configuration::get($this->name.'_number_per_page');
        $quanlity_image = Configuration::get($this->name.'_tab_quanlity_image');
        $removeProductIDs = Configuration::get($this->name.'_removeProductIDs');
        $thumbWidth = Configuration::get($this->name.'_tab_thumb_width');
        $thumbHeight = Configuration::get($this->name.'_tab_thumb_height');
        $thumbWidth = (isset($thumbWidth) && $thumbWidth != "")?$thumbWidth:450;
        $thumbHeight = (isset($thumbHeight) && $thumbHeight != "")?$thumbHeight:450;
        $fromDate = Configuration::get($this->name . '_from_date');
        $toDate = Configuration::get($this->name . '_to_date');
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
        if(Configuration::get($this->name . '_show_all')) {
            $productTabslider[] = array('id'=>'all_product','default'=>'all_product','name' => $this->l('All products'));
        }
        if(Configuration::get($this->name . '_show_new')) {
            $productTabslider[] = array('id'=>'new_product','default'=>'new_product', 'name' => $this->l('New Products'));
        }
        if(Configuration::get($this->name . '_show_feature')) {
            $productTabslider[] = array('id'=>'feature_product','default'=>'feature_product','name' => $this->l('Featured'));
        }
        if(Configuration::get($this->name . '_show_toprated')) {
            $productTabslider[] = array('id'=>'toprated_product','default'=>'toprated_product','name' => $this->l('Top Rated'));
        }
        if(Configuration::get($this->name . '_show_best')) {
            $productTabslider[] = array('id'=>'besseller_product','default'=>'besseller_product','name' => $this->l('Best seller'));
        }
        if(Configuration::get($this->name . '_show_sale')) {
            $productTabslider[] = array('id'=> 'special_product','default'=>'special_product','name' => $this->l('Must Have'));
        }
        if(Configuration::get($this->name . '_show_mostview')) {
            $productTabslider[] = array('id'=> 'mostview_product','default'=>'mostview_product','name' => $this->l('Most View'));
        }
        $defaultTab = array();
        $cateInfo = $this->getCateInfo($cateSelected);

        if($defaultTabID != 0 && $defaultTabID != ""){
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
            $itemlists = array();
            $id_lang = (int)Context::getContext()->language->id;
            foreach ($productData as $key => $item) {
                $id_image = Product::getCover($item['id_product']);
                $images = "";
                if (sizeof($id_image) > 0){
                    $image = new Image($id_image['id_image']);
                    // get image full URL
                    $image_url = "/p/".$image->getExistingImgPath().".jpg";
                    $linkRewrite = $item['id_product']."_".$id_image['id_image']."_".$item['link_rewrite'];
                    $images = DorImageBase::renderThumbProduct($image_url,$linkRewrite,$thumbWidth,$thumbHeight,$quanlity_image);
                    $item['imageThumb'] = $images;
                }

                
                $itemlists[] = $item;
            }
            $assembler = new ProductAssembler($this->context);

            $presenterFactory = new ProductPresenterFactory($this->context);
            $presentationSettings = $presenterFactory->getPresentationSettings();
            $presenter = new ProductPresenter(
                new ImageRetriever(
                    $this->context->link
                ),
                $this->context->link,
                new PriceFormatter(),
                new ProductColorsRetriever(),
                $this->context->getTranslator()
            );

            $products_for_template = [];

            foreach ($itemlists as $rawProduct) {
                $products_for_template[] = $presenter->present(
                    $presentationSettings,
                    $assembler->assembleProduct($rawProduct),
                    $this->context->language
                ); 
            }
            $itemlists = $products_for_template;
        }

        $this->context->controller->addColorsToProductList($itemlists);
        //$products = array_chunk($itemlists, $per);
        $products = $itemlists;
        $options = array(
            'number_per_page'=>$per
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
        $this->context->smarty->assign('tproduct_path', $this->tproduct_path);
        return $this->display(__FILE__, 'dor_ajaxtabproductcategory.tpl', $this->getCacheId());
    }
    
    public function clearCache()
    {
        $this->_clearCache('dor_ajaxtabproductcategory.tpl');
    }
    public function hookAjaxCall($params)
    {
        global $smarty, $cookie;
        $varsData = "";
        $per = Configuration::get($this->name.'_number_per_page');
        if(isset($_POST['cateID']) && $_POST['cateID'] != ""){
            $today = date("Y-m-d");
            $mergeCate = Configuration::get($this->name . '_merge_cate');
            $nb = Configuration::get($this->name . '_number_product');
            $quanlity_image = Configuration::get($this->name . '_tab_quanlity_image');
            $removeProductIDs = Configuration::get($this->name . '_removeProductIDs');
            $thumbWidth = Configuration::get($this->name . '_tab_thumb_width');
            $thumbHeight = Configuration::get($this->name . '_tab_thumb_height');
            $thumbWidth = (isset($thumbWidth) && $thumbWidth != "")?$thumbWidth:450;
            $thumbHeight = (isset($thumbHeight) && $thumbHeight != "")?$thumbHeight:450;
            $fromDate = Configuration::get($this->name . '_from_date');
            $toDate = Configuration::get($this->name . '_to_date');
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
            }else if($_POST['type']==0 && $_POST['cateID'] == "toprated_product"){
                $productData =  $this->getTopRatedProduct ((int) Context::getContext()->language->id, $page, ($nb ? $nb : 10), null,  null);
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
                $itemlists = array();
                $id_lang = (int)Context::getContext()->language->id;
                foreach ($productData as $key => $item) {
                    $id_image = Product::getCover($item['id_product']);
                    $images = "";
                    if (sizeof($id_image) > 0){
                        $image = new Image($id_image['id_image']);
                        // get image full URL
                        $image_url = "/p/".$image->getExistingImgPath().".jpg";
                        $linkRewrite = $item['id_product']."_".$id_image['id_image']."_".$item['link_rewrite'];
                        $images = DorImageBase::renderThumbProduct($image_url,$linkRewrite,$thumbWidth,$thumbHeight,$quanlity_image);
                        $item['imageThumb'] = $images;
                    }
                    
                    $itemlists[] = $item;
                }
                $assembler = new ProductAssembler($this->context);

                $presenterFactory = new ProductPresenterFactory($this->context);
                $presentationSettings = $presenterFactory->getPresentationSettings();
                $presenter = new ProductPresenter(
                    new ImageRetriever(
                        $this->context->link
                    ),
                    $this->context->link,
                    new PriceFormatter(),
                    new ProductColorsRetriever(),
                    $this->context->getTranslator()
                );

                $products_for_template = [];

                foreach ($itemlists as $rawProduct) {
                    $products_for_template[] = $presenter->present(
                        $presentationSettings,
                        $assembler->assembleProduct($rawProduct),
                        $this->context->language
                    ); 
                }
                $itemlists = $products_for_template;
            }
            $this->context->controller->addColorsToProductList($itemlists);
            //$products = array_chunk($itemlists, $per);
            $products = $itemlists;
            
            $defaultTab = array('id'=>$_POST['cateID'], 'name' => "");
            $options = array(
                'number_per_page'=>$per
            );
            $cartUrl = $this->context->link->getPageLink('cart', true);
            $this->context->smarty->assign('static_token', Tools::getToken(false));
            $this->context->smarty->assign('cartUrl', $cartUrl);
            $this->context->smarty->assign('products', $products);
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
    public function getTopRatedProduct($id_lang, $page_number = 0, $nb_products = 10, $order_by = null, $order_way = null)
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
        
        $listProductIDs = $this->getIdProductTopRated($nb_products);
        if(count($listProductIDs) > 0){
            $idproducts = array();
            foreach ($listProductIDs as $i => $productid) {
                array_push($idproducts,$productid['id_product']);
            }
            $idproducts = implode($idproducts, ",");
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
                    AND product_shop.id_product IN ('.$idproducts.')
                GROUP BY product_shop.id_product
                LIMIT '.(int)($page_number * $nb_products).', '.(int)$nb_products;
            $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
            /*if ($final_order_by == 'price')
                Tools::orderbyPrice($result, $order_way);*/
            if (!$result)
                return false;
            return Product::getProductsProperties($id_lang, $result);
        }else{
            return false;
        }
    }
    public static function getIdProductTopRated($limit){
        $sql = 'SELECT `id_product`, (SUM(`grade`) / COUNT(`grade`)) AS grade FROM `'._DB_PREFIX_.'product_comment` WHERE 1 GROUP BY `id_product` ORDER BY grade DESC LIMIT '.(int)$limit;
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
        return $result;
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