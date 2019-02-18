<?php
// Security
if (!defined('_PS_VERSION_'))
    exit;
if (!class_exists( 'DorImageBase' )) {     
    require_once (_PS_ROOT_DIR_.'/override/Dor/DorImageBase.php');
}
if (!class_exists( 'DorCaches' )) {     
    require_once (_PS_ROOT_DIR_.'/override/Dor/Caches/DorCaches.php');
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
class dor_tablistcategory3 extends Module {
    private $_html = '';
    private $_postErrors = array();
    private $_show_level = 1;
	private $pattern = '/^([A-Z_]*)[0-9]+/';
	private $_menuLink = '';
	private $_menuLinkMobile = '';
	private $spacer_size = '5';

    public function __construct() {
        $this->name = 'dor_tablistcategory3';
        $this->tab = 'front_office_features';
        $this->version = '2.0.0';
        $this->bootstrap =true;
        $this->author = 'Dorado Themes';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.5', 'max' => '1.7.99.99');
        parent::__construct();
        $this->displayName = $this->l('Dor Tab List Category 3');
        $this->description = $this->l('Dor Tab List Category 3');
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
        $this->admin_tpl_path = _PS_MODULE_DIR_ . $this->name . '/views/templates/admin/';
        $this->selfPath = dirname(__FILE__).'/views/templates/hook';
        $this->product_path = dirname(__FILE__).'/views/templates/hook/product-item.tpl';
        $this->tproduct_path = _PS_ALL_THEMES_DIR_._THEME_NAME_.'/modules/'.$this->name.'/views/templates/hook/product-item.tpl';
        if (file_exists($this->tproduct_path)){
            $this->product_path = $this->tproduct_path;
            $this->selfPath = _PS_ALL_THEMES_DIR_._THEME_NAME_.'/modules/'.$this->name.'/views/templates/hook';
        }
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
        $tab->name[$language['id_lang']] = $this->l('Dor Tab List Category 3');
        $tab->class_name = 'AdminDorTabListCategory3';
        $tab->id_parent = (int)Tab::getIdFromClassName('AdminDorMenu'); 
        $tab->module = $this->name;
        $tab->add();

        $today = date("Y-m-d");
        Configuration::updateValue($this->name . '_listtab_module_title3', "");
        Configuration::updateValue($this->name . '_tablist_content_custom3', "");
        Configuration::updateValue($this->name . '_tablist_show_all3', 0);
        Configuration::updateValue($this->name . '_show_latest3', 0);
        Configuration::updateValue($this->name . '_tablist_show_new3', 1);
        Configuration::updateValue($this->name . '_tablist_show_sale3', 0);
        Configuration::updateValue($this->name . '_tablist_show_feature3', 0);
        Configuration::updateValue($this->name . '_tablist_show_best3', 0);
        Configuration::updateValue($this->name . '_tablist_show_mostview3', 0);
        Configuration::updateValue($this->name . '_tablist_show_toprated3', 0);
        Configuration::updateValue($this->name . '_tablist_from_date3', "2000-01-01");
        Configuration::updateValue($this->name . '_tablist_to_date3', $today);
        Configuration::updateValue($this->name . '_tablist_show_icon3', 0);
        Configuration::updateValue($this->name . '_tablist_cate_data3', 0);
        Configuration::updateValue($this->name . '_tablist_merge_cate3', 1);
        Configuration::updateValue($this->name . '_tablist_effect3',2);
        Configuration::updateValue($this->name . '_tablist_number_product3',10);
        Configuration::updateValue($this->name . '_tablist_number_per_page3',4);
        Configuration::updateValue($this->name . '_tablist_default_tab_cate3',0);
        Configuration::updateValue($this->name . '_tablist_auto_play3',0);
        Configuration::updateValue($this->name . '_tablist_quanlity_image3',100);
        Configuration::updateValue($this->name . '_tablist_removeProductIDs3',"");
        Configuration::updateValue($this->name . '_tablist_thumb_width3',450);
        Configuration::updateValue($this->name . '_tablist_thumb_height3',450);
        return parent::install() &&
                $this->registerHook('displayHeader')
                &&
                $this->registerHook('displayBackOfficeHeader')
                && 
                $this->registerHook('tablistcategory3');
    }

    public function uninstall() {
        $today = date("Y-m-d");
        $tab = new Tab((int) Tab::getIdFromClassName('AdminDorTabListCategory3'));
        $tab->delete();
        Configuration::deleteByName($this->name . '_listtab_module_title3', "");
        Configuration::deleteByName($this->name . '_tablist_content_custom3', "");
        Configuration::deleteByName($this->name . '_tablist_show_all3', 0);
        Configuration::deleteByName($this->name . '_show_latest3', 0);
        Configuration::deleteByName($this->name . '_tablist_show_new3', 1);
        Configuration::deleteByName($this->name . '_tablist_show_sale3', 0);
        Configuration::deleteByName($this->name . '_tablist_show_feature3', 0);
        Configuration::deleteByName($this->name . '_tablist_show_best3', 0);
        Configuration::deleteByName($this->name . '_tablist_show_mostview3', 0);
        Configuration::deleteByName($this->name . '_tablist_show_toprated3', 0);
        Configuration::deleteByName($this->name . '_tablist_from_date3', "2000-01-01");
        Configuration::deleteByName($this->name . '_tablist_to_date3', $today);
        Configuration::deleteByName($this->name . '_tablist_show_icon3');
        Configuration::deleteByName($this->name . '_tablist_cate_data3');
        Configuration::deleteByName($this->name . '_tablist_merge_cate3');
        Configuration::deleteByName($this->name . '_tablist_effect3');
        Configuration::deleteByName($this->name . '_tablist_number_product3');
        Configuration::deleteByName($this->name . '_tablist_number_per_page3');
        Configuration::deleteByName($this->name . '_tablist_default_tab_cate3');
        Configuration::deleteByName($this->name . '_tablist_auto_play3');
        Configuration::deleteByName($this->name . '_tablist_quanlity_image3');
        Configuration::deleteByName($this->name . '_tablist_removeProductIDs3');
        Configuration::deleteByName($this->name . '_tablist_thumb_width3');
        Configuration::deleteByName($this->name . '_tablist_thumb_height3');
        // Uninstall Module
        if (!parent::uninstall())
            return false;
        return true;
    }

    private function _postProcess() {
        Configuration::updateValue($this->name . '_tablist_show_all3', Tools::getValue('tablist_show_all3'));
        Configuration::updateValue($this->name . '_show_latest3', Tools::getValue('show_latest3'));
        Configuration::updateValue($this->name . '_listtab_module_title3', Tools::getValue('listtab_module_title3'));
        Configuration::updateValue($this->name . '_tablist_content_custom3', Tools::getValue('tablist_content_custom3'));
        Configuration::updateValue($this->name . '_tablist_show_new3', Tools::getValue('tablist_show_new3'));
        Configuration::updateValue($this->name . '_tablist_show_sale3', Tools::getValue('tablist_show_sale3'));
        Configuration::updateValue($this->name . '_tablist_show_feature3', Tools::getValue('tablist_show_feature3'));
        Configuration::updateValue($this->name . '_tablist_show_best3', Tools::getValue('tablist_show_best3'));
        Configuration::updateValue($this->name . '_tablist_show_mostview3', Tools::getValue('tablist_show_mostview3'));
        Configuration::updateValue($this->name . '_tablist_show_toprated3', Tools::getValue('tablist_show_toprated3'));
        Configuration::updateValue($this->name . '_tablist_from_date3', Tools::getValue('tablist_from_date3'));
        Configuration::updateValue($this->name . '_tablist_to_date3', Tools::getValue('tablist_to_date3'));
        Configuration::updateValue($this->name . '_tablist_show_icon3', Tools::getValue('tablist_show_icon3'));
        Configuration::updateValue($this->name . '_tablist_cate_data3', Tools::getValue('tablist_cate_data3'));
        Configuration::updateValue($this->name . '_tablist_merge_cate3', Tools::getValue('tablist_merge_cate3'));
        Configuration::updateValue($this->name . '_tablist_effect3', Tools::getValue('tablist_effect3'));
        Configuration::updateValue($this->name . '_tablist_number_product3', Tools::getValue('tablist_number_product3'));
        Configuration::updateValue($this->name . '_tablist_number_per_page3', Tools::getValue('tablist_number_per_page3'));
        Configuration::updateValue($this->name . '_tablist_default_tab_cate3', Tools::getValue('tablist_default_tab_cate3'));
        Configuration::updateValue($this->name . '_tablist_auto_play3', Tools::getValue('tablist_auto_play3'));
        Configuration::updateValue($this->name . '_tablist_quanlity_image3', Tools::getValue('tablist_quanlity_image3'));
        Configuration::updateValue($this->name . '_tablist_removeProductIDs3', Tools::getValue('tablist_removeProductIDs3'));
        Configuration::updateValue($this->name . '_tablist_thumb_width3', Tools::getValue('tablist_thumb_width3'));
        Configuration::updateValue($this->name . '_tablist_thumb_height3', Tools::getValue('tablist_thumb_height3'));
        $this->_html .= $this->displayConfirmation($this->l('Configuration updated'));
    }
    public function getContent() {
        $output = '<h2>' . $this->displayName . '</h2>';
        if (Tools::isSubmit('submitTabListCategory3')) {
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
		
        $tablist_effect3 = array(
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
                       'type' => 'text',
                       'label' => 'Module Title',
                       'name' => 'listtab_module_title3',
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Show All Products:'),
                        'name' => 'tablist_show_all3',
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
                        'label' => $this->l('Show Latest Products:'),
                        'name' => 'show_latest3',
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
                        'name' => 'tablist_show_new3',
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
                        'name' => 'tablist_show_sale3',
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
                        'name' => 'tablist_show_best3',
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
                        'name' => 'tablist_show_feature3',
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
                        'name' => 'tablist_show_mostview3',
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
                        'name' => 'tablist_show_toprated3',
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
                       'name' => 'tablist_from_date3',
                    ),
                    array(
                       'type' => 'text',
                       'label' => 'Most View To Date:',
                       'name' => 'tablist_to_date3',
                    ),
                    
                    array(
                        'type' => 'listnew',
                        'label' => 'Select Categories:',
                        'name' => 'tablist_cate_data3',
                        'multiple'=>false,
                    ),
                    array(
                        'type' => 'text',
                        'label' => 'Default Category ID:',
                        'name' => 'tablist_default_tab_cate3',
                        'class' => 'fixed-width-md',
                    ),
                    array(
                        'type' => 'text',
                        'label' => 'Limit Products:',
                        'name' => 'tablist_number_product3',
                        'class' => 'fixed-width-md',
                    ),
                    array(
                        'type' => 'text',
                        'label' => 'Number Per Page:',
                        'name' => 'tablist_number_per_page3',
                        'class' => 'fixed-width-md',
                    ),
                    array(
                        'type' => 'select',
                        'label' => 'tablist_effect3: ',
                        'name' => 'tablist_effect3',
                        'options' => array(
                            'query' => $tablist_effect3,
                            'id' => 'id',
                            'name'=>'mode',
                        ),
                    ),
                    array(
                        'type' => 'textarea',
                        'label' => 'Custom Text Content:',
                        'name' => 'tablist_content_custom3',
                        'class' => 'fixed-width-full',
                        'cols' => 60,
                        'rows' => 5,
                    ),
                    array(
                        'type' => 'switch',
                        'label' => 'Merge small subcategories :',
                        'name' => 'tablist_merge_cate3',
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
                        'name' => 'tablist_removeProductIDs3',
                        'class' => 'fixed-width-md',
                    ),
                    array(
                        'type' => 'switch',
                        'label' => 'Auto play :',
                        'name' => 'tablist_auto_play3',
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
                        'name' => 'tablist_quanlity_image3',
                        'class' => 'fixed-width-md',
                    ),
                    array(
                        'type' => 'text',
                        'label' => 'Thumb width image:',
                        'name' => 'tablist_thumb_width3',
                        'class' => 'fixed-width-md',
                    ),
                    array(
                        'type' => 'text',
                        'label' => 'Thumb height image:',
                        'name' => 'tablist_thumb_height3',
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
        $helper->submit_action = 'submitTabListCategory3';
        $helper->module = $this;
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id ,
            'tablist_cate_data3' => $categories,
        );
        $helper->override_folder = '/';
        return $helper->generateForm(array($fields_form));
    }
    public function getConfigFieldsValues()
    {
        return array(
            'listtab_module_title3' =>     Tools::getValue('listtab_module_title3', Configuration::get($this->name . '_listtab_module_title3')),
            'tablist_content_custom3' =>     Tools::getValue('tablist_content_custom3', Configuration::get($this->name . '_tablist_content_custom3')),
            'tablist_show_all3' =>     Tools::getValue('tablist_show_all3', Configuration::get($this->name . '_tablist_show_all3')),
            'show_latest3' =>     Tools::getValue('show_latest3', Configuration::get($this->name . '_show_latest3')),
            'tablist_show_new3' =>     Tools::getValue('tablist_show_new3', Configuration::get($this->name . '_tablist_show_new3')),
            'tablist_show_sale3' =>   Tools::getValue('tablist_show_sale3', Configuration::get($this->name . '_tablist_show_sale3')),
            'tablist_show_feature3' =>     Tools::getValue('tablist_show_feature3', Configuration::get($this->name . '_tablist_show_feature3')),
            'tablist_show_best3' =>    Tools::getValue('tablist_show_best3', Configuration::get($this->name . '_tablist_show_best3')),
            'tablist_show_mostview3' =>    Tools::getValue('tablist_show_mostview3', Configuration::get($this->name . '_tablist_show_mostview3')),
            'tablist_show_toprated3' =>    Tools::getValue('tablist_show_toprated3', Configuration::get($this->name . '_tablist_show_toprated3')),
            'tablist_from_date3' =>    Tools::getValue('tablist_from_date3', Configuration::get($this->name . '_tablist_from_date3')),
            'tablist_to_date3' =>    Tools::getValue('tablist_to_date3', Configuration::get($this->name . '_tablist_to_date3')),
            'tablist_cate_data3' => Tools::getValue('tablist_cate_data3', Configuration::get($this->name . '_tablist_cate_data3')),
            'show_depth' => Tools::getValue('show_depth', Configuration::get($this->name . '_show_depth')),
            'tablist_merge_cate3' => Tools::getValue('tablist_merge_cate3', Configuration::get($this->name . '_tablist_merge_cate3')),
            'tablist_effect3' => Tools::getValue('tablist_effect3', Configuration::get($this->name . '_tablist_effect3')),
            'tablist_number_product3' => Tools::getValue('tablist_number_product3', Configuration::get($this->name . '_tablist_number_product3')),
            'tablist_number_per_page3' => Tools::getValue('tablist_number_per_page3', Configuration::get($this->name . '_tablist_number_per_page3')),
            'tablist_default_tab_cate3' => Tools::getValue('tablist_default_tab_cate3', Configuration::get($this->name . '_tablist_default_tab_cate3')),
            'tablist_auto_play3' => Tools::getValue('tablist_auto_play3', Configuration::get($this->name . '_tablist_auto_play3')),
            'tablist_quanlity_image3' => Tools::getValue('tablist_quanlity_image3', Configuration::get($this->name . '_tablist_quanlity_image3')),
            'tablist_removeProductIDs3' => Tools::getValue('tablist_removeProductIDs3', Configuration::get($this->name . '_tablist_removeProductIDs3')),
            'tablist_thumb_width3' => Tools::getValue('tablist_thumb_width3', Configuration::get($this->name . '_tablist_thumb_width3')),
            'tablist_thumb_height3' => Tools::getValue('tablist_thumb_height3', Configuration::get($this->name . '_tablist_thumb_height3')),
        );
    }
    public static function fetchCategoryTree($id_category = 1, $dataReturn, $id_lang = false, $id_shop = false,$recursive = true) {
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
                $dataReturn = self::fetchCategoryTree((int)$child['id_category'], $dataReturn, (int)$id_lang, (int)$child['id_shop']);

        return $dataReturn ;
    }
    public  function getCategoryOption($id_category = 1, $id_lang = false, $id_shop = false,$recursive = true) {
        $cate = Configuration::get($this->name . '_tablist_cate_data3');
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
                if((int)$category->id == $cate){
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

        //$this->context->controller->addCSS($this->_path . 'assets/css/dor_tablistcategory2.css');
        $this->context->controller->addJS($this->_path . 'assets/js/dor_tablistcategory3.js');
    }
    public function hookDisplayBackOfficeHeader()
    {
        $this->context->controller->addCSS($this->_path . 'assets/css/dor_tablistcategory_admin.css');
        $this->context->controller->addJS($this->_path . 'assets/js/dor_tablistcategory3_admin.js');
    }
    public function hookHome(){
        global $smarty, $cookie;
        $today = date("Y-m-d");
        $id_shop = (int)Context::getContext()->shop->id;
        $id_lang = (int)Context::getContext()->language->id;
        $moduleTitle = Configuration::get($this->name . '_listtab_module_title3');
        $cateID = Configuration::get($this->name . '_tablist_cate_data3');
        $defaultTabID = Configuration::get($this->name . '_tablist_default_tab_cate3');
        $mergeCate = Configuration::get($this->name . '_tablist_merge_cate3');
        $nb = Configuration::get($this->name . '_tablist_number_product3');
        $per = Configuration::get($this->name.'_tablist_number_per_page3');
        $quanlity_image = Configuration::get($this->name.'_tablist_quanlity_image3');
        $tablist_removeProductIDs3 = Configuration::get($this->name.'_tablist_removeProductIDs3');
        $thumbWidth = Configuration::get($this->name.'_tablist_thumb_width3');
        $thumbHeight = Configuration::get($this->name.'_tablist_thumb_height3');
        $thumbWidth = (isset($thumbWidth) && $thumbWidth != "")?$thumbWidth:450;
        $thumbHeight = (isset($thumbHeight) && $thumbHeight != "")?$thumbHeight:450;
        $fromDate = Configuration::get($this->name . '_tablist_from_date3');
        $toDate = Configuration::get($this->name . '_tablist_to_date3');
        if($fromDate == ""){
            $fromDate = "2000-01-01";
        }
        if($toDate == ""){
            $toDate = $today;
        }

        $fullUrl = _PS_BASE_URL_.__PS_BASE_URI__;
        $contentCustom = Configuration::get($this->name . '_tablist_content_custom3');
        $cusImages = array();
        if($contentCustom != ""){
            $contentCustom = explode(PHP_EOL, $contentCustom);
            foreach ($contentCustom as $key => $image) {
                $images = explode("=>", $image);
                $ik = $images[0];
                $file = $images[1];
                $cusImages[$ik] = $fullUrl.$file;
            }
        }

        /****Tab Select****/
        $languages = Language::getLanguages(true, $this->context->shop->id);
        $productData = "";
        if(!$productData) $productData = null;
        $productTabslider = array();
        if(Configuration::get($this->name . '_tablist_show_all3')) {
            $productTabslider[] = array('id'=>'all_product','default'=>'all_product','name' => $this->l('All products'));
        }
        if(Configuration::get($this->name . '_tablist_show_feature3')) {
            $productTabslider[] = array('id'=>'feature_product','default'=>'feature_product','name' => $this->l('Featured'));
        }
        if(Configuration::get($this->name . '_show_latest3')) {
            $productTabslider[] = array('id'=>'latest_product','default'=>'latest_product','name' => $this->l('Latest products'));
        }
        if(Configuration::get($this->name . '_tablist_show_new3')) {
            $productTabslider[] = array('id'=>'new_product','default'=>'new_product', 'name' => $this->l('New Arrivals'));
        }
        if(Configuration::get($this->name . '_tablist_show_best3')) {
            $productTabslider[] = array('id'=>'besseller_product','default'=>'besseller_product','name' => $this->l('Best seller'));
        }
        if(Configuration::get($this->name . '_tablist_show_toprated3')) {
            $productTabslider[] = array('id'=> 'toprated_product','default'=>'toprated_product','name' => $this->l('Top Rated'));
        }
        if(Configuration::get($this->name . '_tablist_show_sale3')) {
            $productTabslider[] = array('id'=> 'special_product','default'=>'special_product','name' => $this->l('Sale Off'));
        }
        if(Configuration::get($this->name . '_tablist_show_mostview3')) {
            $productTabslider[] = array('id'=> 'mostview_product','default'=>'mostview_product','name' => $this->l('Most View'));
        }
        
        $defaultTab = array();
        $cateInfo = array();
        $cateInfoData = $this->getAllCateInfoWithParentID($cateID);

 
        $defaultTabID = $defaultTabID == ""?0:$defaultTabID;
        $defaultTabID = $productTabslider[$defaultTabID]['id'];
        $defaultTab = array('id'=>$defaultTabID, 'name' => '');
        $listTabs = array();
        
        if(count($cateInfo) > 0){
            foreach ($cateInfo as $key => $cate) {
                $listTabs[] = array('id'=>$cate['id_category'], 'name' => $cate['name']);
                if($cate['id_category'] == $defaultTabID && empty($defaultTab)){
                    $defaultTab = array('id'=>$cate['id_category'], 'name' => $cate['name']);
                }
            }
        }
        if($defaultTabID != -1){
            $itemlists = $this->DorCacheTablist3($cateID, $defaultTabID, $defaultTabID, $productTabslider);
            $products = $itemlists;
        }else{
            $productArray = array();
            if(Configuration::get($this->name . '_tablist_show_all3')) {
                $productData =  $this->getAllProductCategory ((int) Context::getContext()->language->id, $page, ($nb ? $nb : 10), null,  null);
                $productArray['all_product']['tabs'] = $this->l('All products');
                $productArray['all_product']['data'] = $productData;
            }
            if(Configuration::get($this->name . '_tablist_show_new3')) {
                $productData = self::getNewProducts((int) Context::getContext()->language->id, $page, ($nb ? $nb : 5));
                $productArray['new_product']['tabs'] = $this->l('New products');
                $productArray['new_product']['data'] = $productData;
            }
            if(Configuration::get($this->name . '_tablist_show_feature3')) {
                $category = new Category(Context::getContext()->shop->getCategory(), (int) Context::getContext()->language->id);
                $productData = $category->getProducts((int) Context::getContext()->language->id, $page, ($nb ? $nb : 5));
                $productArray['feature_product']['tabs'] = $this->l('Top Featured');
                $productArray['feature_product']['data'] = $productData;
            }
            if(Configuration::get($this->name . '_tablist_show_sale3')) {
                $productData = Product::getPricesDrop((int) Context::getContext()->language->id, $page, ($nb ? $nb : 5));
                $productArray['special_product']['tabs'] = $this->l('Special products');
                $productArray['special_product']['data'] = $productData;
            }
            if(Configuration::get($this->name . '_tablist_show_best3')) {
                ProductSale::fillProductSales();
                $productData =  $this->getBestSales ((int) Context::getContext()->language->id, $page, ($nb ? $nb : 5), null,  null);
                $productArray['besseller_product']['tabs'] = $this->l('Best Sellers');
                $productArray['besseller_product']['data'] = $productData;
            }
            if(Configuration::get($this->name . '_tablist_show_mostview3')) {
                $productData = $this->getTableMostViewed($fromDate,$toDate,$page,($nb ? $nb : 5));
                $productData = $productData['products'];
                $productArray['mostview_product']['tabs'] = $this->l('Mostview products');
                $productArray['mostview_product']['data'] = $productData;
            }
            if($productArray){
                $id_lang = (int)Context::getContext()->language->id;
                $products = array();
                foreach ($productArray as $k => $itemArray) {
                    $itemlists = array();
                    foreach ($itemArray['data'] as $key => $item) {
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
                    $id_lang = (int)Context::getContext()->language->id;

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
                    $this->context->controller->addColorsToProductList($products_for_template);
                    $products[$k]['tabs'] = $itemArray['tabs'];
                    $products[$k]['data'] = $products_for_template;
                }
            }

        }
        
        $options = array(
            'listLimit3'=>$per
        );
        $cartUrl = $this->context->link->getPageLink('cart', true);
        $this->context->smarty->assign('static_token', Tools::getToken(false));
        $this->context->smarty->assign('cartUrl', $cartUrl);
        $this->context->smarty->assign('products', $products);
        $this->context->smarty->assign('listTabs', $listTabs);
        $this->context->smarty->assign('listTabsProduct', $productTabslider);
        $this->context->smarty->assign('tabID', $defaultTab);
        $this->context->smarty->assign('moduleTitle', $moduleTitle);
        $this->context->smarty->assign('cateInfo', $cateInfoData);
        $this->context->smarty->assign('slideImages', $cusImages);
        $this->context->smarty->assign('optionsConfig', $options);
        $this->context->smarty->assign('self', $this->selfPath);
        $this->context->smarty->assign('productItemPath', $this->product_path);
        $this->context->smarty->assign('page_name', $this->context->controller->php_self);
        if($defaultTabID != -1){
            return $this->display(__FILE__, 'dor_tablistcategory3.tpl', $this->getCacheId()."-".$defaultTabID."-".$id_shop."-".$id_lang);
        }else{
            return $this->display(__FILE__, 'dor_tablistcategory3_all.tpl', $this->getCacheId()."-".$defaultTabID."-".$id_shop."-".$id_lang);
        }
    }

    public function DorCacheTablist3($mainCateID, $cateID,$defaultTabID, $productTabslider){
        $page = isset($_POST['page'])?$_POST['page']-1:0;
        $dorTimeCache  = Configuration::get('dor_themeoptions_dorTimeCache',Configuration::get('dorTimeCache'));
        $dorTimeCache = $dorTimeCache?$dorTimeCache:86400;
        $dorCaches  = Configuration::get('dor_themeoptions_enableDorCache',Configuration::get('enableDorCache'));
        $id_shop = (int)Context::getContext()->shop->id;
        $id_lang = (int)Context::getContext()->language->id;
        $objCache = new DorCaches(array('name'=>'default','path'=>_PS_ROOT_DIR_.'/override/Dor/Caches/smartcaches/tablist3/','extension'=>'.cache'));
        $fileCache = 'HomeTabList3Caches-Shop'.$id_shop."-".$id_lang.'-'.$cateID.'-'.$page;
        $objCache->setCache($fileCache);
        $cacheData = $objCache->renderData($fileCache);

        if($cacheData && $dorCaches && $page == 0){
            $productData = $cacheData['lists'];
        }else{
            $mergeCate = Configuration::get($this->name . '_tablist_merge_cate3');
            $nb = Configuration::get($this->name . '_tablist_number_product3');
            $per = Configuration::get($this->name.'_tablist_number_per_page3');
            $quanlity_image = Configuration::get($this->name.'_tablist_quanlity_image3');
            $tablist_removeProductIDs3 = Configuration::get($this->name.'_tablist_removeProductIDs3');
            $thumbWidth = Configuration::get($this->name.'_tablist_thumb_width3');
            $thumbHeight = Configuration::get($this->name.'_tablist_thumb_height3');
            $thumbWidth = (isset($thumbWidth) && $thumbWidth != "")?$thumbWidth:450;
            $thumbHeight = (isset($thumbHeight) && $thumbHeight != "")?$thumbHeight:450;
            $fromDate = Configuration::get($this->name . '_tablist_from_date3');
            $toDate = Configuration::get($this->name . '_tablist_to_date3');
            if($fromDate == ""){
                $fromDate = "2000-01-01";
            }
            if($toDate == ""){
                $toDate = $today;
            }
            $cateID = $mainCateID;
            if(isset($_POST['cateID']) && $_POST['cateID'] != ""){
                if($_POST['type']==1){
                    $productData =  $this->getProductCategory ($_POST['cateID'], $mergeCate, (int) Context::getContext()->language->id, $page, ($nb ? $nb : 10), null,  null);
                }else if($_POST['type']==0 && $_POST['cateID'] == "all_product"){
                    $productData =  $this->getAllProductCategory ((int) Context::getContext()->language->id, $page, ($nb ? $nb : 10), null,  null);
                }else if($_POST['type']==0 && $_POST['cateID'] == "latest_product"){
                    $productData =  $this->getProductByCategory ((int)$cateID, $mergeCate, (int) Context::getContext()->language->id, $page, ($nb ? $nb : 10), null,  null);
                }else if($_POST['type']==0 && $_POST['cateID'] == "new_product"){
                    $productData = self::getNewProducts((int)$cateID, (int) Context::getContext()->language->id, $page, ($nb ? $nb : 5));
                }else if($_POST['type']==0 && $_POST['cateID'] == "feature_product"){
                    $page = $page+1;
                    $category = new Category((int)$cateID, (int) Context::getContext()->language->id);
                    $productData = self::getFeaturedProducts((int)$cateID,(int) Context::getContext()->language->id, $page, ($nb ? $nb : 5));
                }else if($_POST['type']==0 && $_POST['cateID'] == "special_product"){
                    $productData = self::getPricesDrop((int)$cateID, (int) Context::getContext()->language->id, $page, ($nb ? $nb : 5));
                }else if($_POST['type']==0 && $_POST['cateID'] == "besseller_product"){
                    ProductSale::fillProductSales();
                    $productData =  $this->getBestSales ((int)$cateID,(int) Context::getContext()->language->id, $page, ($nb ? $nb : 5), null,  null);
                }else if($_POST['type']==0 && $_POST['cateID'] == "mostview_product"){
                    $productData = $this->getTableMostViewed((int)$cateID, $fromDate,$toDate,$page,($nb ? $nb : 5));
                    $productData = $productData['products'];
                }
            }else{
                if(count($productTabslider) > 0){
                    if($defaultTabID=="all_product"){
                        $productData =  $this->getAllProductCategory ((int) Context::getContext()->language->id, $page, ($nb ? $nb : 10), null,  null);
                    }else if($defaultTabID=="new_product"){
                        $productData = self::getNewProducts((int)$cateID, (int) Context::getContext()->language->id, $page, ($nb ? $nb : 5));
                    }else if($defaultTabID=="feature_product"){
                        $category = new Category((int)$cateID, (int) Context::getContext()->language->id);
                        $productData = self::getFeaturedProducts((int)$cateID,(int) Context::getContext()->language->id, $page, ($nb ? $nb : 5));
                    }else if($defaultTabID=="latest_product"){
                        $productData =  $this->getProductByCategory ((int)$cateID, $mergeCate, (int) Context::getContext()->language->id, $page, ($nb ? $nb : 10), null,  null);
                    }else if($defaultTabID=="special_product"){
                        $productData = self::getPricesDrop((int)$cateID, (int) Context::getContext()->language->id, $page, ($nb ? $nb : 5));
                    }else if($defaultTabID=="besseller_product"){
                        ProductSale::fillProductSales();
                        $productData =  $this->getBestSales ((int)$cateID,(int) Context::getContext()->language->id, $page, ($nb ? $nb : 5), null,  null);
                    }else if($defaultTabID=="mostview_product"){
                        $productData = $this->getTableMostViewed((int)$cateID, $fromDate,$toDate,$page,($nb ? $nb : 5));
                        $productData = $productData['products'];
                    }
                }else{
                    $productData =  $this->getProductCategory ($defaultTabID, $mergeCate, (int) Context::getContext()->language->id, 0, ($nb ? $nb : 10), null,  null);
                }
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
                $id_lang = (int)Context::getContext()->language->id;

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
                $productData = $products_for_template;
                $this->context->controller->addColorsToProductList($productData);
                if($dorCaches){
                    $data['lists'] = $productData;
                    $objCache->store($fileCache, $data, $expiration = $dorTimeCache);
                }
            }
        }
        return $productData;
    }

    public function hooktablistcategory3($params){
        return $this->hookHome($params);
    }
    public function clearCache()
    {
        $this->_clearCache('dor_tablistcategory3.tpl');
    }
    public function hookAjaxCall($params)
    {
        global $smarty, $cookie;
        $varsData = "";
        $id_shop = (int)Context::getContext()->shop->id;
        $id_lang = Context::getContext()->language->id;
        $per = Configuration::get($this->name.'_tablist_number_per_page3');
        if(isset($_POST['cateID']) && $_POST['cateID'] != ""){
            $today = date("Y-m-d");
            $mergeCate = Configuration::get($this->name . '_tablist_merge_cate3');
            $cateID = Configuration::get($this->name . '_tablist_cate_data3');
            $nb = Configuration::get($this->name . '_tablist_number_product3');
            $quanlity_image = Configuration::get($this->name . '_tablist_quanlity_image3');
            $tablist_removeProductIDs3 = Configuration::get($this->name . '_tablist_removeProductIDs3');
            $thumbWidth = Configuration::get($this->name . '_tablist_thumb_width3');
            $thumbHeight = Configuration::get($this->name . '_tablist_thumb_height3');
            $thumbWidth = (isset($thumbWidth) && $thumbWidth != "")?$thumbWidth:450;
            $thumbHeight = (isset($thumbHeight) && $thumbHeight != "")?$thumbHeight:450;
            $fromDate = Configuration::get($this->name . '_tablist_from_date3');
            $toDate = Configuration::get($this->name . '_tablist_to_date3');
            if($fromDate == ""){
                $fromDate = "2000-01-01";
            }
            if($toDate == ""){
                $toDate = $today;
            }

            $fullUrl = _PS_BASE_URL_.__PS_BASE_URI__;
            $contentCustom = Configuration::get($this->name . '_tablist_content_custom3');
            $cusImages = array();
            if($contentCustom != ""){
                $contentCustom = explode(PHP_EOL, $contentCustom);
                foreach ($contentCustom as $key => $image) {
                    $images = explode("=>", $image);
                    $ik = $images[0];
                    $file = $images[1];
                    $cusImages[$ik] = $fullUrl.$file;
                }
            }
            $page = isset($_POST['page'])?$_POST['page']-1:0;
            $itemlists = $this->DorCacheTablist3($cateID, $_POST['cateID'], $defaultTabID=0, $productTabslider=array());
            $products = $itemlists;
            $defaultTab = array('id'=>$_POST['cateID'], 'name' => "");
            $options = array(
                'listLimit3'=>$per
            );
            $listTabs = array();
            if(empty($itemlists)){
                return Tools::jsonEncode("");
            }
            $cartUrl = $this->context->link->getPageLink('cart', true);
            $this->context->smarty->assign('static_token', Tools::getToken(false));
            $this->context->smarty->assign('products', $products);
            $this->context->smarty->assign('listTabs', $listTabs);
            $this->context->smarty->assign('options', $options);
            $this->context->smarty->assign('tabID', $defaultTab);
            $this->context->smarty->assign('cartUrl', $cartUrl);
            $this->context->smarty->assign('slideImages', $cusImages);
            $this->context->smarty->assign('self', $this->selfPath);
            $this->context->smarty->assign('page_name', "index");
            if($page > 0){
                $varsData = $this->display(__FILE__, '/views/templates/hook/product-field.tpl', $this->getCacheId()."-".$_POST['cateID']."-".$id_shop."-".$id_lang);
            }else{
                $varsData = $this->display(__FILE__, '/views/templates/hook/product-item.tpl', $this->getCacheId()."-".$_POST['cateID']."-".$id_shop."-".$id_lang);
            }
            
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
    public function getAllCateInfoWithParentID($CateID){
        if($CateID == null || $CateID == "") return;
        if (!$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
                SELECT DISTINCT c.id_parent, c.id_category, cl.name, cl.description, cl.link_rewrite
                FROM `' . _DB_PREFIX_ . 'category` c
                INNER JOIN `' . _DB_PREFIX_ . 'category_lang` cl ON (c.`id_category` = cl.`id_category` AND cl.`id_lang` = ' . (int) $this->context->language->id . Shop::addSqlRestrictionOnLang('cl') . ')
                INNER JOIN `' . _DB_PREFIX_ . 'category_shop` cs ON (cs.`id_category` = c.`id_category` AND cs.`id_shop` = ' . (int) $this->context->shop->id . ')
                WHERE (c.`active` = 1 AND c.`id_parent` = ' . pSQL($CateID) . ')'))
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
	public function getProductByCategory($idCate, $mergeCate, $id_lang, $page_number = 0, $nb_products = 10, $order_by = null, $order_way = null)
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
        $whereCate = " ";
        if($mergeCate){
            $categories =  $this->fetchCategoryTree((int)$idCate,"",(int) Context::getContext()->language->id,0);
            $arrayCates = array();
            foreach ($categories as $key => $cate) {
                $arrayCates[] = $cate['id_category'];
            }
            $whereCate = ' AND cp.`id_category` IN ('.implode(',', array_map('intval', $arrayCates)).')';
        }

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
                    '.$whereCate.'
                    AND product_shop.`visibility` != \'none\'
                GROUP BY product_shop.id_product
                ORDER BY product_shop.`date_add` '.pSQL($order_way).'
                LIMIT '.(int)($page_number * $nb_products).', '.(int)$nb_products;
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
        if ($final_order_by == 'price')
            Tools::orderbyPrice($result, $order_way);
        if (!$result)
            return false;
        return Product::getProductsProperties($id_lang, $result);
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

    public static function getBestSales($cateID, $id_lang, $page_number = 0, $nb_products = 10, $order_by = null, $order_way = null)
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
        $whereCate = '';
        $categories =  self::fetchCategoryTree((int)$cateID,"",(int)$id_lang,0);
        $arrayCates = array();
        foreach ($categories as $key => $cate) {
            $arrayCates[] = $cate['id_category'];
        }
        $whereCate .= ' AND cp.`id_category` IN ('.implode(',', array_map('intval', $arrayCates)).')';
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
                        WHERE cg.`id_group` '.$sql_groups.$whereCate.'
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

    public function getTableMostViewed($cateID, $date_from, $date_to, $page, $limit=2)
    {
        if (Configuration::get('PS_STATSDATA_PAGESVIEWS'))
            $data = $this->getTotalViewed($cateID, $date_from, $date_to, $page, $limit);
        else
            $data = '<div class="alert alert-info">'.$this->l('You must enable the "Save global page views" option from the "Data mining for statistics" module in order to display the most viewed products, or use the Google Analytics module.').'</div>';
        return array('products' => $data);
    }

    public function getTotalViewed($cateID, $date_from, $date_to, $page, $limit = 10)
    {
        $id_lang = (int)Context::getContext()->language->id;

        $whereCate = '';
        $categories =  self::fetchCategoryTree((int)$cateID,"",(int)$id_lang,0);
        $arrayCates = array();
        foreach ($categories as $key => $cate) {
            $arrayCates[] = $cate['id_category'];
        }
        $whereCate .= ' AND cp.`id_category` IN ('.implode(',', array_map('intval', $arrayCates)).')';

        $sql = '
            SELECT p.*,pa.id_object, pv.counter, pl.link_rewrite, pl.`name`, product_shop.*
            FROM `'._DB_PREFIX_.'page_viewed` pv
            LEFT JOIN `'._DB_PREFIX_.'date_range` dr ON pv.`id_date_range` = dr.`id_date_range`
            LEFT JOIN `'._DB_PREFIX_.'page` pa ON pv.`id_page` = pa.`id_page`
            LEFT JOIN `'._DB_PREFIX_.'product` p ON p.`id_product` = pa.`id_object`
            INNER JOIN `'._DB_PREFIX_.'category_product` cp ON (cp.`id_product` = p.`id_product`)
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
            '.$whereCate.'
            GROUP BY product_shop.id_product
            ORDER BY pv.counter DESC
            LIMIT '.(int)($page * $limit).', '.(int)$limit;
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
        return Product::getProductsProperties($id_lang, $result);
    }

    /**
    * Get new products
    *
    * @param int $id_lang Language id
    * @param int $pageNumber Start from (optional)
    * @param int $nbProducts Number of products to return (optional)
    * @return array New products
    */
    public static function getNewProducts($cateID, $id_lang, $page_number = 0, $nb_products = 10, $count = false, $order_by = null, $order_way = null, Context $context = null)
    {
        if (!$context) {
            $context = Context::getContext();
        }
        $front = true;
        if (!in_array($context->controller->controller_type, array('front', 'modulefront'))) {
            $front = false;
        }

        if ($page_number < 0) {
            $page_number = 0;
        }
        if ($nb_products < 1) {
            $nb_products = 10;
        }
        if (empty($order_by) || $order_by == 'position') {
            $order_by = 'date_add';
        }
        if (empty($order_way)) {
            $order_way = 'DESC';
        }
        if ($order_by == 'id_product' || $order_by == 'price' || $order_by == 'date_add' || $order_by == 'date_upd') {
            $order_by_prefix = 'product_shop';
        } elseif ($order_by == 'name') {
            $order_by_prefix = 'pl';
        }
        if (!Validate::isOrderBy($order_by) || !Validate::isOrderWay($order_way)) {
            die(Tools::displayError());
        }

        $sql_groups = '';
        if (Group::isFeatureActive()) {
            $groups = FrontController::getCurrentCustomerGroups();
            $sql_groups = ' AND EXISTS(SELECT 1 FROM `'._DB_PREFIX_.'category_product` cp
                JOIN `'._DB_PREFIX_.'category_group` cg ON (cp.id_category = cg.id_category AND cg.`id_group` '.(count($groups) ? 'IN ('.implode(',', $groups).')' : '= 1').')
                WHERE cp.`id_product` = p.`id_product`)';
        }

        if (strpos($order_by, '.') > 0) {
            $order_by = explode('.', $order_by);
            $order_by_prefix = $order_by[0];
            $order_by = $order_by[1];
        }

        if ($count) {
            $sql = 'SELECT COUNT(p.`id_product`) AS nb
                    FROM `'._DB_PREFIX_.'product` p
                    '.Shop::addSqlAssociation('product', 'p').'
                    WHERE product_shop.`active` = 1
                    AND product_shop.`date_add` > "'.date('Y-m-d', strtotime('-'.(Configuration::get('PS_NB_DAYS_NEW_PRODUCT') ? (int)Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).' DAY')).'"
                    '.($front ? ' AND product_shop.`visibility` IN ("both", "catalog")' : '').'
                    '.$sql_groups;
            return (int)Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);
        }

        $sql = new DbQuery();
        $sql->select(
            'p.*, product_shop.*, stock.out_of_stock, IFNULL(stock.quantity, 0) as quantity, pl.`description`, pl.`description_short`, pl.`link_rewrite`, pl.`meta_description`,
            pl.`meta_keywords`, pl.`meta_title`, pl.`name`, pl.`available_now`, pl.`available_later`, image_shop.`id_image` id_image, il.`legend`, m.`name` AS manufacturer_name,
            product_shop.`date_add` > "'.date('Y-m-d', strtotime('-'.(Configuration::get('PS_NB_DAYS_NEW_PRODUCT') ? (int)Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).' DAY')).'" as new'
        );

        $sql->from('product', 'p');
        $sql->join(Shop::addSqlAssociation('product', 'p'));
        $sql->leftJoin('product_lang', 'pl', '
            p.`id_product` = pl.`id_product`
            AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl')
        );
        $sql->leftJoin('image_shop', 'image_shop', 'image_shop.`id_product` = p.`id_product` AND image_shop.cover=1 AND image_shop.id_shop='.(int)$context->shop->id);
        $sql->leftJoin('image_lang', 'il', 'image_shop.`id_image` = il.`id_image` AND il.`id_lang` = '.(int)$id_lang);
        $sql->leftJoin('manufacturer', 'm', 'm.`id_manufacturer` = p.`id_manufacturer`');
        $sql->innerJoin('category_product', 'cp', 'cp.`id_product` = p.`id_product`');
        $sql->where('product_shop.`active` = 1');
        if ($front) {
            $sql->where('product_shop.`visibility` IN ("both", "catalog")');
        }
        $sql->where('product_shop.`date_add` > "'.date('Y-m-d', strtotime('-'.(Configuration::get('PS_NB_DAYS_NEW_PRODUCT') ? (int)Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).' DAY')).'"');
        $categories =  self::fetchCategoryTree($cateID,"",(int)$id_lang,0);
        $arrayCates = array();
        foreach ($categories as $key => $cate) {
            $arrayCates[] = $cate['id_category'];
        }
        $sql->where('cp.`id_category` IN ('.implode(',', array_map('intval', $arrayCates)).')');

        if (Group::isFeatureActive()) {
            $groups = FrontController::getCurrentCustomerGroups();
            $sql->where('EXISTS(SELECT 1 FROM `'._DB_PREFIX_.'category_product` cp
                JOIN `'._DB_PREFIX_.'category_group` cg ON (cp.id_category = cg.id_category AND cg.`id_group` '.(count($groups) ? 'IN ('.implode(',', $groups).')' : '= 1').')
                WHERE cp.`id_product` = p.`id_product`)');
        }

        $sql->orderBy((isset($order_by_prefix) ? pSQL($order_by_prefix).'.' : '').'`'.pSQL($order_by).'` '.pSQL($order_way));
        $sql->groupBy('product_shop.id_product');
        $sql->limit($nb_products, $page_number * $nb_products);

        if (Combination::isFeatureActive()) {
            $sql->select('product_attribute_shop.minimal_quantity AS product_attribute_minimal_quantity, IFNULL(product_attribute_shop.id_product_attribute,0) id_product_attribute');
            $sql->leftJoin('product_attribute_shop', 'product_attribute_shop', 'p.`id_product` = product_attribute_shop.`id_product` AND product_attribute_shop.`default_on` = 1 AND product_attribute_shop.id_shop='.(int)$context->shop->id);
        }
        $sql->join(Product::sqlStock('p', 0));
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

        if (!$result) {
            return false;
        }

        if ($order_by == 'price') {
            Tools::orderbyPrice($result, $order_way);
        }

        $products_ids = array();
        foreach ($result as $row) {
            $products_ids[] = $row['id_product'];
        }
        // Thus you can avoid one query per product, because there will be only one query for all the products of the cart
        Product::cacheFrontFeatures($products_ids, $id_lang);
        return Product::getProductsProperties((int)$id_lang, $result);
    }


    /**
    * Get prices drop
    *
    * @param int $id_lang Language id
    * @param int $pageNumber Start from (optional)
    * @param int $nbProducts Number of products to return (optional)
    * @param bool $count Only in order to get total number (optional)
    * @return array Prices drop
    */
    public static function getPricesDrop($cateID, $id_lang, $page_number = 0, $nb_products = 10, $count = false,
        $order_by = null, $order_way = null, $beginning = false, $ending = false, Context $context = null)
    {
        if (!Validate::isBool($count)) {
            die(Tools::displayError());
        }

        if (!$context) {
            $context = Context::getContext();
        }
        if ($page_number < 0) {
            $page_number = 0;
        }
        if ($nb_products < 1) {
            $nb_products = 10;
        }
        if (empty($order_by) || $order_by == 'position') {
            $order_by = 'price';
        }
        if (empty($order_way)) {
            $order_way = 'DESC';
        }
        if ($order_by == 'id_product' || $order_by == 'price' || $order_by == 'date_add' || $order_by == 'date_upd') {
            $order_by_prefix = 'product_shop';
        } elseif ($order_by == 'name') {
            $order_by_prefix = 'pl';
        }
        if (!Validate::isOrderBy($order_by) || !Validate::isOrderWay($order_way)) {
            die(Tools::displayError());
        }
        $current_date = date('Y-m-d H:i:00');
        $ids_product = self::_getProductIdByDate((!$beginning ? $current_date : $beginning), (!$ending ? $current_date : $ending), $context);

        $tab_id_product = array();
        foreach ($ids_product as $product) {
            if (is_array($product)) {
                $tab_id_product[] = (int)$product['id_product'];
            } else {
                $tab_id_product[] = (int)$product;
            }
        }

        $front = true;
        if (!in_array($context->controller->controller_type, array('front', 'modulefront'))) {
            $front = false;
        }

        $sql_groups = '';
        if (Group::isFeatureActive()) {
            $groups = FrontController::getCurrentCustomerGroups();
            $sql_groups = ' AND EXISTS(SELECT 1 FROM `'._DB_PREFIX_.'category_product` cp
                JOIN `'._DB_PREFIX_.'category_group` cg ON (cp.id_category = cg.id_category AND cg.`id_group` '.(count($groups) ? 'IN ('.implode(',', $groups).')' : '= 1').')
                WHERE cp.`id_product` = p.`id_product`)';
        }

        if ($count) {
            return Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('
            SELECT COUNT(DISTINCT p.`id_product`)
            FROM `'._DB_PREFIX_.'product` p
            '.Shop::addSqlAssociation('product', 'p').'
            WHERE product_shop.`active` = 1
            AND product_shop.`show_price` = 1
            '.($front ? ' AND product_shop.`visibility` IN ("both", "catalog")' : '').'
            '.((!$beginning && !$ending) ? 'AND p.`id_product` IN('.((is_array($tab_id_product) && count($tab_id_product)) ? implode(', ', $tab_id_product) : 0).')' : '').'
            '.$sql_groups);
        }

        if (strpos($order_by, '.') > 0) {
            $order_by = explode('.', $order_by);
            $order_by = pSQL($order_by[0]).'.`'.pSQL($order_by[1]).'`';
        }
        $categories =  self::fetchCategoryTree((int)$cateID,"",(int)$id_lang,0);

        $arrayCates = array();
        foreach ($categories as $key => $cate) {
            $arrayCates[] = $cate['id_category'];
        }
        $whereCate = ' AND cp.`id_category` IN ('.implode(',', array_map('intval', $arrayCates)).') ';
        $sql = '
        SELECT
            p.*, product_shop.*, stock.out_of_stock, IFNULL(stock.quantity, 0) as quantity, pl.`description`, pl.`description_short`, pl.`available_now`, pl.`available_later`,
            IFNULL(product_attribute_shop.id_product_attribute, 0) id_product_attribute,
            pl.`link_rewrite`, pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`,
            pl.`name`, image_shop.`id_image` id_image, il.`legend`, m.`name` AS manufacturer_name,
            DATEDIFF(
                p.`date_add`,
                DATE_SUB(
                    "'.date('Y-m-d').' 00:00:00",
                    INTERVAL '.(Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).' DAY
                )
            ) > 0 AS new
        FROM `'._DB_PREFIX_.'product` p
        '.Shop::addSqlAssociation('product', 'p').'
        INNER JOIN `'._DB_PREFIX_.'category_product` cp ON (cp.`id_product` = p.`id_product`)
        LEFT JOIN `'._DB_PREFIX_.'product_attribute_shop` product_attribute_shop
            ON (p.`id_product` = product_attribute_shop.`id_product` AND product_attribute_shop.`default_on` = 1 AND product_attribute_shop.id_shop='.(int)$context->shop->id.')
        '.Product::sqlStock('p', 0, false, $context->shop).'
        LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (
            p.`id_product` = pl.`id_product`
            AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl').'
        )
        LEFT JOIN `'._DB_PREFIX_.'image_shop` image_shop
            ON (image_shop.`id_product` = p.`id_product` AND image_shop.cover=1 AND image_shop.id_shop='.(int)$context->shop->id.')
        LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (image_shop.`id_image` = il.`id_image` AND il.`id_lang` = '.(int)$id_lang.')
        LEFT JOIN `'._DB_PREFIX_.'manufacturer` m ON (m.`id_manufacturer` = p.`id_manufacturer`)
        WHERE product_shop.`active` = 1
        '.$whereCate.'
        AND product_shop.`show_price` = 1
        '.($front ? ' AND p.`visibility` IN ("both", "catalog")' : '').'
        '.((!$beginning && !$ending) ? ' AND p.`id_product` IN ('.((is_array($tab_id_product) && count($tab_id_product)) ? implode(', ', $tab_id_product) : 0).')' : '').'
        '.$sql_groups.'
        GROUP BY product_shop.id_product
        ORDER BY '.(isset($order_by_prefix) ? pSQL($order_by_prefix).'.' : '').pSQL($order_by).' '.pSQL($order_way).'
        LIMIT '.(int)($page_number * $nb_products).', '.(int)$nb_products;

        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

        if (!$result) {
            return false;
        }

        if ($order_by == 'price') {
            Tools::orderbyPrice($result, $order_way);
        }

        return Product::getProductsProperties($id_lang, $result);
    }
    public static function _getProductIdByDate($beginning, $ending, Context $context = null, $with_combination = false)
    {
        if (!$context) {
            $context = Context::getContext();
        }
        $id_address = $context->cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')};
        $ids = Address::getCountryAndState($id_address);
        $id_country = $ids['id_country'] ? (int)$ids['id_country'] : (int)Configuration::get('PS_COUNTRY_DEFAULT');
        return SpecificPrice::getProductIdByDate(
            $context->shop->id,
            $context->currency->id,
            $id_country,
            $context->customer->id_default_group,
            $beginning,
            $ending,
            0,
            $with_combination
        );
    }

    public static function getFeaturedProducts(
        $cateID,
        $idLang,
        $p,
        $n,
        $orderyBy = null,
        $orderWay = null,
        $getTotal = false,
        $active = true,
        $random = false,
        $randomNumberProducts = 1,
        $checkAccess = true,
        Context $context = null
    ) {
        if (!$context) {
            $context = Context::getContext();
        }
        $idCateFeatured = $context->shop->getCategory();
        $category = new Category((int)$cateID, (int)$idLang, (int)$context->shop->id);
        if ($checkAccess && !$category->checkAccess($context->customer->id)) {
            return false;
        }

        $front = in_array($context->controller->controller_type, array('front', 'modulefront'));
        $idSupplier = (int) Tools::getValue('id_supplier');

        /** Return only the number of products */
        if ($getTotal) {
            $sql = 'SELECT COUNT(cp.`id_product`) AS total
                    FROM `'._DB_PREFIX_.'product` p
                    '.Shop::addSqlAssociation('product', 'p').'
                    LEFT JOIN `'._DB_PREFIX_.'category_product` cp ON p.`id_product` = cp.`id_product`
                    WHERE cp.`id_category` = '.(int) $this->id.
                ($front ? ' AND product_shop.`visibility` IN ("both", "catalog")' : '').
                ($active ? ' AND product_shop.`active` = 1' : '').
                ($idSupplier ? 'AND p.id_supplier = '.(int) $idSupplier : '');

            return (int) Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);
        }

        if ($p < 1) {
            $p = 1;
        }

        /** Tools::strtolower is a fix for all modules which are now using lowercase values for 'orderBy' parameter */
        $orderyBy  = Validate::isOrderBy($orderyBy)   ? Tools::strtolower($orderyBy)  : 'position';
        $orderWay = Validate::isOrderWay($orderWay) ? Tools::strtoupper($orderWay) : 'ASC';

        $orderByPrefix = false;
        if ($orderyBy == 'id_product' || $orderyBy == 'date_add' || $orderyBy == 'date_upd') {
            $orderByPrefix = 'p';
        } elseif ($orderyBy == 'name') {
            $orderByPrefix = 'pl';
        } elseif ($orderyBy == 'manufacturer' || $orderyBy == 'manufacturer_name') {
            $orderByPrefix = 'm';
            $orderyBy = 'name';
        } elseif ($orderyBy == 'position') {
            $orderByPrefix = 'cp';
        }

        if ($orderyBy == 'price') {
            $orderyBy = 'orderprice';
        }

        $nbDaysNewProduct = Configuration::get('PS_NB_DAYS_NEW_PRODUCT');
        if (!Validate::isUnsignedInt($nbDaysNewProduct)) {
            $nbDaysNewProduct = 20;
        }
        $id_productFeatured = self::getFeaturesList();
        
        $sql = 'SELECT p.*, product_shop.*, stock.out_of_stock, IFNULL(stock.quantity, 0) AS quantity'.(Combination::isFeatureActive() ? ', IFNULL(product_attribute_shop.id_product_attribute, 0) AS id_product_attribute,
                    product_attribute_shop.minimal_quantity AS product_attribute_minimal_quantity' : '').', pl.`description`, pl.`description_short`, pl.`available_now`,
                    pl.`available_later`, pl.`link_rewrite`, pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`, pl.`name`, image_shop.`id_image` id_image,
                    il.`legend` as legend, m.`name` AS manufacturer_name, cl.`name` AS category_default,
                    DATEDIFF(product_shop.`date_add`, DATE_SUB("'.date('Y-m-d').' 00:00:00",
                    INTERVAL '.(int) $nbDaysNewProduct.' DAY)) > 0 AS new, product_shop.price AS orderprice
                FROM `'._DB_PREFIX_.'category_product` cp
                LEFT JOIN `'._DB_PREFIX_.'product` p
                    ON p.`id_product` = cp.`id_product`
                '.Shop::addSqlAssociation('product', 'p').
                (Combination::isFeatureActive() ? ' LEFT JOIN `'._DB_PREFIX_.'product_attribute_shop` product_attribute_shop
                ON (p.`id_product` = product_attribute_shop.`id_product` AND product_attribute_shop.`default_on` = 1 AND product_attribute_shop.id_shop='.(int) $context->shop->id.')':'').'
                '.Product::sqlStock('p', 0).'
                LEFT JOIN `'._DB_PREFIX_.'category_lang` cl
                    ON (product_shop.`id_category_default` = cl.`id_category`
                    AND cl.`id_lang` = '.(int) $idLang.Shop::addSqlRestrictionOnLang('cl').')
                LEFT JOIN `'._DB_PREFIX_.'product_lang` pl
                    ON (p.`id_product` = pl.`id_product`
                    AND pl.`id_lang` = '.(int) $idLang.Shop::addSqlRestrictionOnLang('pl').')
                LEFT JOIN `'._DB_PREFIX_.'image_shop` image_shop
                    ON (image_shop.`id_product` = p.`id_product` AND image_shop.cover=1 AND image_shop.id_shop='.(int)$context->shop->id.')
                LEFT JOIN `'._DB_PREFIX_.'image_lang` il
                    ON (image_shop.`id_image` = il.`id_image`
                    AND il.`id_lang` = '.(int) $idLang.')
                LEFT JOIN `'._DB_PREFIX_.'manufacturer` m
                    ON m.`id_manufacturer` = p.`id_manufacturer`
                WHERE product_shop.`id_shop` = '.(int) $context->shop->id.'
                    AND cp.`id_category` = '.(int) $cateID
                    .($active ? ' AND product_shop.`active` = 1' : '')
                    .($front ? ' AND product_shop.`visibility` IN ("both", "catalog")' : '')
                    .($idSupplier ? ' AND p.id_supplier = '.(int)$idSupplier : '')
                    .((is_array($id_productFeatured) && count($id_productFeatured))?' AND cp.`id_product` IN ('.implode(', ', $id_productFeatured).')' : '');
        if ($random === true) {
            $sql .= ' ORDER BY RAND() LIMIT '.(int) $randomNumberProducts;
        } else {
            $sql .= ' ORDER BY '.(!empty($orderByPrefix) ? $orderByPrefix.'.' : '').'`'.bqSQL($orderyBy).'` '.pSQL($orderWay).'
            LIMIT '.(((int) $p - 1) * (int) $n).','.(int) $n;
        }

        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql, true, false);
        
        if (!$result) {
            return array();
        }

        if ($orderyBy == 'orderprice') {
            Tools::orderbyPrice($result, $orderWay);
        }

        // Modify SQL result
        return Product::getProductsProperties($idLang, $result);
    }

    public static function getFeaturesList()
    {
        $cateID = (int) Configuration::get('HOME_FEATURED_CAT');
        $idProducts = array();
        $sql = 'SELECT cp.`id_product`
                FROM `'._DB_PREFIX_.'category_product` cp
                WHERE cp.`id_category` = '.(int) $cateID;
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql, true, false);
        if (!$result) {
            return array();
        }
        foreach ($result as $key => $item) {
            $idProducts[] = $item['id_product'];
        }
        return $idProducts;
    }
}