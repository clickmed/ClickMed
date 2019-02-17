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
class Dor_Tabproductcategory_Pro extends Module {
    private $_html = '';
    private $_postErrors = array();
    private $_show_level = 1;
	private $pattern = '/^([A-Z_]*)[0-9]+/';
	private $_menuLink = '';
	private $_menuLinkMobile = '';
	private $spacer_size = '5';
    public $image_format_cate = 'jpg';
    protected $existing_path_cate;

    public function __construct() {
        $this->name = 'dor_tabproductcategory_pro';
        $this->tab = 'front_office_features';
        $this->version = '2.0.0';
        $this->bootstrap =true;
        $this->author = 'Dorado Themes';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.5', 'max' => '1.7.99.99');
        parent::__construct();
        $this->displayName = $this->l('Dor Tab Product Pro');
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
        $tab->name[$language['id_lang']] = $this->l('Dor Tab Product and Category Pro');
        $tab->class_name = 'AdminDorTabPro';
        $tab->id_parent = (int)Tab::getIdFromClassName('AdminDorMenu'); 
        $tab->module = $this->name;
        $tab->add();
        $today = date("Y-m-d");
        Configuration::updateValue($this->name . '_pro_show_all', 1);
        Configuration::updateValue($this->name . '_pro_show_new', 1);
        Configuration::updateValue($this->name . '_pro_show_sale', 1);
        Configuration::updateValue($this->name . '_pro_show_feature', 0);
        Configuration::updateValue($this->name . '_pro_show_mostview', 0);
        Configuration::updateValue($this->name . '_pro_show_best', 1);
        Configuration::updateValue($this->name . '_pro_show_icon', 0);
        Configuration::updateValue($this->name . '_pro_cate_data', 0);
        Configuration::updateValue($this->name . '_pro_merge_cate', 1);
        Configuration::updateValue($this->name . '_pro_effect',2);
        Configuration::updateValue($this->name . '_pro_number_product',10);
        Configuration::updateValue($this->name . '_pro_number_per_page',4);
        Configuration::updateValue($this->name . '_pro_default_tab_cate',0);
        Configuration::updateValue($this->name . '_pro_auto_play',0);
        Configuration::updateValue($this->name . '_pro_tab_quanlity_image',100);
        Configuration::updateValue($this->name . '_pro_tab_thumb_width',250);
        Configuration::updateValue($this->name . '_pro_tab_thumb_height',250);
        Configuration::updateValue($this->name . '_pro_tab_thumb_width_cate',265);
        Configuration::updateValue($this->name . '_pro_tab_thumb_height_cate',95);
        Configuration::updateValue($this->name . '_pro_enabled_custom',0);
        Configuration::updateValue($this->name . '_pro_content_custom',"");
        Configuration::updateValue($this->name . '_pro_icon_tab',"");
        Configuration::updateValue($this->name . '_from_date_pro',"2000-01-01");
        Configuration::updateValue($this->name . '_to_date_pro',$today);
        Configuration::updateValue($this->name . '_pro_remove_proids',"");
        Configuration::updateValue($this->name . '_pro_only_proids',"");
        Configuration::updateValue($this->name . '_pro_link_imagecustom',"");
        Configuration::updateValue($this->name . '_pro_theme',"");
        Configuration::updateValue($this->name . '_all_title_pro',"");
        Configuration::updateValue($this->name . '_new_title_pro',"");
        Configuration::updateValue($this->name . '_special_title_pro',"");
        Configuration::updateValue($this->name . '_best_title_pro',"");
        Configuration::updateValue($this->name . '_feature_title_pro',"");
        Configuration::updateValue($this->name . '_mostview_title_pro',"");
        return parent::install() &&
                $this->registerHook('displayHeader')
                &&
                $this->registerHook('displayBackOfficeHeader')
                &&
				$this->registerHook('home')
                && 
                $this->registerHook('DorTabPro')
                && 
                $this->registerHook('blockDorado3');
    }

    public function uninstall() {
        $today = date("Y-m-d");
        Configuration::deleteByName($this->name . '_pro_show_all', 1);
        Configuration::deleteByName($this->name . '_pro_show_new', 1);
        Configuration::deleteByName($this->name . '_pro_show_sale', 1);
        Configuration::deleteByName($this->name . '_pro_show_feature', 0);
        Configuration::deleteByName($this->name . '_pro_show_mostview', 0);
        Configuration::deleteByName($this->name . '_pro_show_best', 1);
        Configuration::deleteByName($this->name . '_pro_show_icon');
        Configuration::deleteByName($this->name . '_pro_cate_data');
        Configuration::deleteByName($this->name . '_pro_merge_cate');
        Configuration::deleteByName($this->name . '_pro_effect');
        Configuration::deleteByName($this->name . '_pro_number_product');
        Configuration::deleteByName($this->name . '_pro_number_per_page');
        Configuration::deleteByName($this->name . '_pro_default_tab_cate');
        Configuration::deleteByName($this->name . '_pro_auto_play');
        Configuration::deleteByName($this->name . '_pro_auto_play');
        Configuration::deleteByName($this->name . '_pro_tab_thumb_width');
        Configuration::deleteByName($this->name . '_pro_tab_thumb_height');
        Configuration::deleteByName($this->name . '_pro_tab_thumb_width_cate');
        Configuration::deleteByName($this->name . '_pro_tab_thumb_height_cate');
        Configuration::deleteByName($this->name . '_pro_enabled_custom');
        Configuration::deleteByName($this->name . '_pro_content_custom');
        Configuration::deleteByName($this->name . '_pro_icon_tab');
        Configuration::deleteByName($this->name . '_from_date_pro',"2000-01-01");
        Configuration::deleteByName($this->name . '_to_date_pro',$today);
        Configuration::deleteByName($this->name . '_pro_remove_proids',"");
        Configuration::deleteByName($this->name . '_pro_only_proids',"");
        Configuration::deleteByName($this->name . '_pro_link_imagecustom',"");
        Configuration::deleteByName($this->name . '_pro_theme',"");
        Configuration::deleteByName($this->name . '_all_title_pro',"");
        Configuration::deleteByName($this->name . '_new_title_pro',"");
        Configuration::deleteByName($this->name . '_special_title_pro',"");
        Configuration::deleteByName($this->name . '_best_title_pro',"");
        Configuration::deleteByName($this->name . '_feature_title_pro',"");
        Configuration::deleteByName($this->name . '_mostview_title_pro',"");
        // Uninstall Module
        $tab = new Tab((int) Tab::getIdFromClassName('AdminDorTabPro'));
        $tab->delete();
        if (!parent::uninstall())
            return false;
        return true;
    }

    private function _postProcess() {
        Configuration::updateValue($this->name . '_pro_show_all', Tools::getValue('pro_show_all'));
        Configuration::updateValue($this->name . '_pro_show_new', Tools::getValue('pro_show_new'));
        Configuration::updateValue($this->name . '_pro_show_sale', Tools::getValue('pro_show_sale'));
        Configuration::updateValue($this->name . '_pro_show_feature', Tools::getValue('pro_show_feature'));
        Configuration::updateValue($this->name . '_pro_show_mostview', Tools::getValue('pro_show_mostview'));
        Configuration::updateValue($this->name . '_pro_show_best', Tools::getValue('pro_show_best'));
        Configuration::updateValue($this->name . '_pro_show_icon', Tools::getValue('pro_show_icon'));
        Configuration::updateValue($this->name . '_pro_cate_data', implode(',', Tools::getValue('pro_cate_data')));
        Configuration::updateValue($this->name . '_pro_merge_cate', Tools::getValue('pro_merge_cate'));
        Configuration::updateValue($this->name . '_pro_effect', Tools::getValue('pro_effect'));
        Configuration::updateValue($this->name . '_pro_number_product', Tools::getValue('pro_number_product'));
        Configuration::updateValue($this->name . '_pro_number_per_page', Tools::getValue('pro_number_per_page'));
        Configuration::updateValue($this->name . '_pro_default_tab_cate', Tools::getValue('pro_default_tab_cate'));
        Configuration::updateValue($this->name . '_pro_auto_play', Tools::getValue('pro_auto_play'));
        Configuration::updateValue($this->name . '_pro_tab_quanlity_image', Tools::getValue('pro_tab_quanlity_image'));
        Configuration::updateValue($this->name . '_pro_tab_thumb_width', Tools::getValue('pro_tab_thumb_width'));
        Configuration::updateValue($this->name . '_pro_tab_thumb_height', Tools::getValue('pro_tab_thumb_height'));
        Configuration::updateValue($this->name . '_pro_tab_thumb_width_cate', Tools::getValue('pro_tab_thumb_width_cate'));
        Configuration::updateValue($this->name . '_pro_tab_thumb_height_cate', Tools::getValue('pro_tab_thumb_height_cate'));
        Configuration::updateValue($this->name . '_pro_enabled_custom', Tools::getValue('pro_enabled_custom'));
        Configuration::updateValue($this->name . '_pro_content_custom', Tools::getValue('pro_content_custom'));
        Configuration::updateValue($this->name . '_pro_icon_tab', Tools::getValue('pro_icon_tab'));
        Configuration::updateValue($this->name . '_from_date_pro', Tools::getValue('from_date_pro'));
        Configuration::updateValue($this->name . '_to_date_pro', Tools::getValue('to_date_pro'));
        Configuration::updateValue($this->name . '_pro_remove_proids', Tools::getValue('pro_remove_proids'));
        Configuration::updateValue($this->name . '_pro_only_proids', Tools::getValue('pro_only_proids'));
        Configuration::updateValue($this->name . '_pro_link_imagecustom', Tools::getValue('pro_link_imagecustom'));
        Configuration::updateValue($this->name . '_pro_theme', Tools::getValue('pro_theme'));
        Configuration::updateValue($this->name . '_all_title_pro', Tools::getValue('all_title_pro'));
        Configuration::updateValue($this->name . '_new_title_pro', Tools::getValue('new_title_pro'));
        Configuration::updateValue($this->name . '_special_title_pro', Tools::getValue('special_title_pro'));
        Configuration::updateValue($this->name . '_best_title_pro', Tools::getValue('best_title_pro'));
        Configuration::updateValue($this->name . '_feature_title_pro', Tools::getValue('feature_title_pro'));
        Configuration::updateValue($this->name . '_mostview_title_pro', Tools::getValue('mostview_title_pro'));
        $this->_html .= $this->displayConfirmation($this->l('Configuration updated'));
    }
    public function getContent() {
        $output = '<h2>' . $this->displayName . '</h2>';
        if (Tools::isSubmit('submitProductCategoryPro')) {
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
        $themes = array(
            array( 'id'=>'theme1','mode'=>'Theme 1'),
            array('id'=>'theme2','mode'=>'Theme 2'),
            array('id'=>'theme3','mode'=>'Theme 3'),

        );
        $id_shop = (int)Context::getContext()->shop->id;
        $categories =    $this->getCategoryOption(2, (int)$id_lang, (int)$id_shop);
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Module Settings'),
                    'icon' => 'icon-cogs'
                ),
                'input' => array(
                    array(
                        'type' => 'select',
                        'label' => 'Chose a Theme: ',
                        'name' => 'pro_theme',
                        'options' => array(
                            'query' => $themes,
                            'id' => 'id',
                            'name'=>'mode',
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Show All Products:'),
                        'name' => 'pro_show_all',
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
                       'label' => 'Title:',
                       'name' => 'all_title_pro',
                       'class' => 'fixed-width-md',
                       'default' => "All Products"
                    ),


                    array(
                        'type' => 'switch',
                        'label' => $this->l('Show New Products:'),
                        'name' => 'pro_show_new',
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
                       'label' => 'Title:',
                       'name' => 'new_title_pro',
                       'class' => 'fixed-width-md',
                       'default' => "New Products"
                    ),


                    array(
                        'type' => 'switch',
                        'label' => $this->l('Show Special Products:'),
                        'name' => 'pro_show_sale',
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
                       'label' => 'Title:',
                       'name' => 'special_title_pro',
                       'class' => 'fixed-width-md',
                       'default' => "Special Products"
                    ),

                    array(
                        'type' => 'switch',
                        'label' => $this->l('Show Bestselling Products: '),
                        'name' => 'pro_show_best',
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
                       'label' => 'Title:',
                       'name' => 'best_title_pro',
                       'class' => 'fixed-width-md',
                       'default' => "Bestselling Products"
                    ),


                    array(
                        'type' => 'switch',
                        'label' => $this->l('Show Feature Products: '),
                        'name' => 'pro_show_feature',
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
                       'label' => 'Title:',
                       'name' => 'feature_title_pro',
                       'class' => 'fixed-width-md',
                       'default' => "Feature Products"
                    ),



                    array(
                        'type' => 'switch',
                        'label' => $this->l('Show Most View Products: '),
                        'name' => 'pro_show_mostview',
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
                       'label' => 'Title:',
                       'name' => 'mostview_title_pro',
                       'class' => 'fixed-width-md',
                       'default' => "Most View"
                    ),
                    array(
                       'type' => 'text',
                       'label' => 'Most View From Date:',
                       'name' => 'from_date_pro',
                    ),
                    array(
                       'type' => 'text',
                       'label' => 'Most View To Date:',
                       'name' => 'to_date_pro',
                    ),
                    
                    array(
                        'type' => 'listcatePro',
                        'label' => 'Select Categories:',
                        'name' => 'pro_cate_data[]',
                        'multiple'=>true,
                    ),
                    array(
                        'type' => 'text',
                        'label' => 'Default Category ID:',
                        'name' => 'pro_default_tab_cate',
                        'class' => 'fixed-width-md',
                    ),
                    array(
                        'type' => 'text',
                        'label' => 'Limit Products:',
                        'name' => 'pro_number_product',
                        'class' => 'fixed-width-md',
                    ),
                    array(
                        'type' => 'text',
                        'label' => 'Number Per Page:',
                        'name' => 'pro_number_per_page',
                        'class' => 'fixed-width-md',
                    ),
                    /*array(
                        'type' => 'text',
                        'label' => 'Link Image Custom:',
                        'name' => 'pro_link_imagecustom',
                        'class' => 'fixed-width-md',
                    ),
                    array(
                        'type' => 'text',
                        'label' => 'Only Display With ProductIDs:',
                        'name' => 'pro_only_proids',
                        'class' => 'fixed-width-md',
                    ),
                    array(
                        'type' => 'text',
                        'label' => 'Remove With ProductIDs:',
                        'name' => 'pro_remove_proids',
                        'class' => 'fixed-width-md',
                    ),*/
                    array(
                        'type' => 'select',
                        'label' => 'Effect: ',
                        'name' => 'pro_effect',
                        'options' => array(
                            'query' => $effect,
                            'id' => 'id',
                            'name'=>'mode',
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => 'Merge small subcategories :',
                        'name' => 'pro_merge_cate',
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
                        'label' => 'Auto play :',
                        'name' => 'pro_auto_play',
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
                        'name' => 'pro_tab_quanlity_image',
                        'class' => 'fixed-width-md',
                    ),
                    array(
                        'type' => 'text',
                        'label' => 'Thumb width image:',
                        'name' => 'pro_tab_thumb_width',
                        'class' => 'fixed-width-md',
                    ),
                    array(
                        'type' => 'text',
                        'label' => 'Thumb height image:',
                        'name' => 'pro_tab_thumb_height',
                        'class' => 'fixed-width-md',
                    ),
                    /*array(
                        'type' => 'text',
                        'label' => 'Thumb width Category:',
                        'name' => 'pro_tab_thumb_width_cate',
                        'class' => 'fixed-width-md',
                    ),
                    array(
                        'type' => 'text',
                        'label' => 'Thumb height Category:',
                        'name' => 'pro_tab_thumb_height_cate',
                        'class' => 'fixed-width-md',
                    ),*/
                    /*array(
                        'type' => 'switch',
                        'label' => 'Enabled custom text:',
                        'name' => 'pro_enabled_custom',
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
                        'type' => 'textarea',
                        'label' => 'Custom Text Content:',
                        'name' => 'pro_content_custom',
                        'class' => 'fixed-width-full',
                        'cols' => 60,
                        'rows' => 10,
                    ),*/
                    array(
                        'type' => 'textarea',
                        'label' => 'Custom Icon Tabs:',
                        'name' => 'pro_icon_tab',
                        'class' => 'fixed-width-full',
                        'cols' => 60,
                        'rows' => 10,
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
        $helper->submit_action = 'submitProductCategoryPro';
        $helper->module = $this;
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id ,
            'pro_cate_data' => $categories,
        );
        $helper->override_folder = '/';
        return $helper->generateForm(array($fields_form));
    }
    public function getConfigFieldsValues()
    {
        return array(
            'pro_show_all' =>     Tools::getValue('pro_show_all', Configuration::get($this->name . '_pro_show_all')),
            'pro_show_new' =>     Tools::getValue('pro_show_new', Configuration::get($this->name . '_pro_show_new')),
            'pro_show_sale' =>   Tools::getValue('pro_show_sale', Configuration::get($this->name . '_pro_show_sale')),
            'pro_show_feature' =>     Tools::getValue('pro_show_feature', Configuration::get($this->name . '_pro_show_feature')),
            'pro_show_mostview' =>     Tools::getValue('pro_show_mostview', Configuration::get($this->name . '_pro_show_mostview')),
            'pro_show_best' =>    Tools::getValue('pro_show_best', Configuration::get($this->name . '_pro_show_best')),
            'pro_cate_data' => Tools::getValue('pro_cate_data', Configuration::get($this->name . '_pro_cate_data')),
            'show_depth' => Tools::getValue('show_depth', Configuration::get($this->name . '_show_depth')),
            'pro_merge_cate' => Tools::getValue('pro_merge_cate', Configuration::get($this->name . '_pro_merge_cate')),
            'pro_effect' => Tools::getValue('pro_effect', Configuration::get($this->name . '_pro_effect')),
            'pro_number_product' => Tools::getValue('pro_number_product', Configuration::get($this->name . '_pro_number_product')),
            'pro_number_per_page' => Tools::getValue('pro_number_per_page', Configuration::get($this->name . '_pro_number_per_page')),
            'pro_default_tab_cate' => Tools::getValue('pro_default_tab_cate', Configuration::get($this->name . '_pro_default_tab_cate')),
            'pro_auto_play' => Tools::getValue('pro_auto_play', Configuration::get($this->name . '_pro_auto_play')),
            'pro_tab_quanlity_image' => Tools::getValue('pro_tab_quanlity_image', Configuration::get($this->name . '_pro_tab_quanlity_image')),
            'pro_tab_thumb_width' => Tools::getValue('pro_tab_thumb_width', Configuration::get($this->name . '_pro_tab_thumb_width')),
            'pro_tab_thumb_height' => Tools::getValue('pro_tab_thumb_height', Configuration::get($this->name . '_pro_tab_thumb_height')),
            'pro_tab_thumb_width_cate' => Tools::getValue('pro_tab_thumb_width_cate', Configuration::get($this->name . '_pro_tab_thumb_width_cate')),
            'pro_tab_thumb_height_cate' => Tools::getValue('pro_tab_thumb_height_cate', Configuration::get($this->name . '_pro_tab_thumb_height_cate')),
            'pro_enabled_custom' => Tools::getValue('pro_enabled_custom', Configuration::get($this->name . '_pro_enabled_custom')),
            'pro_content_custom' => Tools::getValue('pro_content_custom', Configuration::get($this->name . '_pro_content_custom')),
            'pro_icon_tab' => Tools::getValue('pro_icon_tab', Configuration::get($this->name . '_pro_icon_tab')),
            'from_date_pro' => Tools::getValue('from_date_pro', Configuration::get($this->name . '_from_date_pro')),
            'to_date_pro' => Tools::getValue('to_date_pro', Configuration::get($this->name . '_to_date_pro')),
            'pro_link_imagecustom' => Tools::getValue('pro_link_imagecustom', Configuration::get($this->name . '_pro_link_imagecustom')),
            'pro_remove_proids' => Tools::getValue('pro_remove_proids', Configuration::get($this->name . '_pro_remove_proids')),
            'pro_only_proids' => Tools::getValue('pro_only_proids', Configuration::get($this->name . '_pro_only_proids')),
            'pro_theme' => Tools::getValue('pro_theme', Configuration::get($this->name . '_pro_theme')),
            'mostview_title_pro' => Tools::getValue('mostview_title_pro', Configuration::get($this->name . '_mostview_title_pro')),
            'all_title_pro' => Tools::getValue('all_title_pro', Configuration::get($this->name . '_all_title_pro')),
            'new_title_pro' => Tools::getValue('new_title_pro', Configuration::get($this->name . '_new_title_pro')),
            'special_title_pro' => Tools::getValue('special_title_pro', Configuration::get($this->name . '_special_title_pro')),
            'best_title_pro' => Tools::getValue('best_title_pro', Configuration::get($this->name . '_best_title_pro')),
            'feature_title_pro' => Tools::getValue('feature_title_pro', Configuration::get($this->name . '_feature_title_pro')),
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
        $cate = Configuration::get($this->name . '_pro_cate_data');
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

        $this->context->controller->addCSS($this->_path . 'assets/css/dor_tabproductcategory_pro.css');
        $this->context->controller->addJS($this->_path . 'assets/js/dor_tabproductcategory_pro.js');
    }
    public function hookDisplayBackOfficeHeader()
    {
        $this->context->controller->addCSS($this->_path . 'assets/css/dor_tabproductcategory_pro_admin.css');
        $this->context->controller->addJS($this->_path . 'assets/js/dor_tabproductcategory_pro_admin.js');
    }
 
    public function hookDorTabPro($params){
        global $smarty, $cookie;
        $today = date("Y-m-d");
        $cateSelected = Configuration::get($this->name . '_pro_cate_data');
        $defaultTabID = Configuration::get($this->name . '_pro_default_tab_cate');
        $mergeCate = Configuration::get($this->name . '_pro_merge_cate');
        $nb = Configuration::get($this->name . '_pro_number_product');
        $per = Configuration::get($this->name.'_pro_number_per_page');
        $quanlity_image = Configuration::get($this->name.'_pro_tab_quanlity_image');
        $thumbWidth = Configuration::get($this->name.'_pro_tab_thumb_width');
        $thumbHeight = Configuration::get($this->name.'_pro_tab_thumb_height');
        $thumbWidthCate = Configuration::get($this->name.'_pro_tab_thumb_width_cate');
        $thumbHeightCate = Configuration::get($this->name.'_pro_tab_thumb_height_cate');
        $enabledCustom = Configuration::get($this->name.'_pro_enabled_custom');
        $contentCustom = Configuration::get($this->name.'_pro_content_custom');
        $icontabs = Configuration::get($this->name.'_pro_icon_tab');
        $fromDate = Configuration::get($this->name . '_from_date_pro');
        $toDate = Configuration::get($this->name . '_to_date_pro');
        $imageCustom = Configuration::get($this->name . '_pro_link_imagecustom');
        $onlyShowProductIDs = Configuration::get($this->name . '_pro_only_proids');
        $removeProductIDs = Configuration::get($this->name . '_pro_remove_proids');
        $themes = Configuration::get($this->name . '_pro_theme');
        $id_shop = (int)Context::getContext()->shop->id;
        $id_lang = Context::getContext()->language->id;
        if($fromDate == ""){
            $fromDate = "2000-01-01";
        }
        if($toDate == ""){
            $toDate = $today;
        }
        /****Tab Select****/
        $languages = Language::getLanguages(true, $this->context->shop->id);
        $productData = "";
        if(!empty($icontabs)){
            $icontabs = explode(PHP_EOL, $icontabs);
            $cusIconTabs = array();
            if( !empty($icontabs) && $icontabs != ""){
                foreach ($icontabs as $key => $icon) {
                    $icons = explode("=>", $icon);
                    $ik = $icons[0];
                    $file = $icons[1];
                    $cusIconTabs[$ik] = $file;
                }
            }
        }
        if(!$productData) $productData = null;
        $productTabslider = array();
        $tabChose = "";
        $tabChoseName = "";
        if(Configuration::get($this->name . '_pro_show_all')) {
            $productTabslider[] = array('id'=>'all_product','default'=>'all_product','name' => $this->l('All products'));
            $tabChose = 'all_product';
            $tabChoseName = $this->l('All Products');
        }
        if(Configuration::get($this->name . '_pro_show_new')) {
            $productTabslider[] = array('id'=>'new_product','default'=>'new_product', 'name' => $this->l('New Products'));
            $tabChose = 'new_product';
            $tabChoseName = $this->l('New Products');
        }
        if(Configuration::get($this->name . '_pro_show_feature')) {
            $productTabslider[] = array('id'=>'feature_product','default'=>'feature_product','name' => $this->l('Featured'));
            $tabChose = 'feature_product';
            $tabChoseName = $this->l('Featured Products');
        }
        if(Configuration::get($this->name . '_pro_show_best')) {
            $productTabslider[] = array('id'=>'besseller_product','default'=>'besseller_product','name' => $this->l('Best seller'));
            $tabChose = 'besseller_product';
            $tabChoseName = $this->l('Best Selling');
        }
        if(Configuration::get($this->name . '_pro_show_mostview')) {
            $productTabslider[] = array('id'=>'mostview_product','default'=>'mostview_product','name' => $this->l('Most View'));
            $tabChose = 'mostview_product';
            $tabChoseName = $this->l('Most View');
        }
        if(Configuration::get($this->name . '_pro_show_sale')) {
            $productTabslider[] = array('id'=> 'special_product','default'=>'special_product','name' => $this->l('Must Have'));
            $tabChose = 'special_product';
            $tabChoseName = $this->l('Special Products');
        }
        $defaultTab = array();
        $cateInfo = $this->getCateInfo($cateSelected);
        if($themes == "theme2"){
            if($defaultTabID != 0 && $defaultTabID != ""){
                $defaultTabID = explode(",", $defaultTabID);
                $defaultTabID = $defaultTabID[0];
            }else if(count($productTabslider) > 0 && $defaultTabID >= 0){
                $defaultTabID = $productTabslider[$defaultTabID]['id'];
                $defaultTab = array('id'=>$defaultTabID, 'name' => '');
            }else{
                $defaultTabID = $cateInfo[0]['id_category'];
            }
        }


        $itemlists = array();
        $listTabs = array();
        if(count($cateInfo) > 0){
            foreach ($cateInfo as $key => $cate) {
                $cateCheck = $this->getExistingImgPathCategory($cate['id_category']);
                $cate['thumbCate'] = "";
                
                if($cate['id_category'] == $defaultTabID && empty($defaultTab)){
                    $defaultTab = array('id'=>$cate['id_category'], 'name' => $cate['name']);
                }
                $cate['icontab'] = "";
                if( !empty($cusIconTabs) && count($cusIconTabs) > 0 && isset($cusIconTabs[$cate['id_category']]) && $cusIconTabs[$cate['id_category']] != ""){
                    $cate['icontab'] = $cusIconTabs[$cate['id_category']];
                }
                if($themes == "theme2"){
                    $productData =  $this->getProductCategory ($cate['id_category'], $mergeCate, $onlyShowProductIDs, $removeProductIDs, (int) Context::getContext()->language->id, 0, ($nb ? $nb : 10), null,  null);
                    $itemlists = array();
                    foreach ($productData as $i => $item) {
                        $id_image = Product::getCover($item['id_product']);
                        $images = "";
                        if (sizeof($id_image) > 0){
                            $image = new Image($id_image['id_image']);
                            // get image full URL
                            $image_url = "/p/".$image->getExistingImgPath().".jpg";
                            $linkRewrite = $item['id_product']."_".$item['link_rewrite'];
                            $images = DorImageBase::renderThumbProduct($image_url,$linkRewrite,$thumbWidth,$thumbHeight,$quanlity_image);
                        }
                        
                        $item['imageThumb'] = $images;
                        $itemlists[] = $item;
                    }
                }
                $listTabs[] = array(
                    'id'=>$cate['id_category'], 
                    'name' => $cate['name'],
                    'link_rewrite' => $cate['link_rewrite'],
                    'description' => $cate['description'],
                    'thumbCate' => $cate['thumbCate'],
                    'icontab' => $cate['icontab'],
                    'productData' => $itemlists,
                );
            }
            if($themes == "theme2"){
                $itemlists = $listTabs;
            }
        }
        if($themes != "theme2"){
            $itemlists = $this->DorCacheTabCategoryPro($tabChose,$defaultTabID, $productTabslider);
            $this->context->controller->addColorsToProductList($itemlists);
        }
        //$products = array_chunk($itemlists, $per);
        $products = $itemlists;
        $options = array(
            'pro_number_per_page'=>$per
        );
        $this->context->smarty->assign('products', $products);
        $this->context->smarty->assign('listTabs', $listTabs);
        $this->context->smarty->assign('listTabsProduct', $productTabslider);
        $this->context->smarty->assign('tabID', $defaultTab);
        $this->context->smarty->assign('tabChose', $tabChose);
        $this->context->smarty->assign('tabChoseName', $tabChoseName);
        $this->context->smarty->assign('optionsConfig', $options);
        $this->context->smarty->assign('self', $this->selfPath);
        $this->context->smarty->assign('productItemPath', $this->product_path);
        $this->context->smarty->assign('page_name', $this->context->controller->php_self);
        return $this->display(__FILE__, 'dor_tabproductcategory_pro.tpl', $this->getCacheId()."-".$defaultTabID.'-'.$id_shop.'-'.$id_lang);
    }
    
    public function clearCache()
    {
        $this->_clearCache('dor_tabproductcategory_pro.tpl');
    }
    public function DorCacheTabCategoryPro($tabChose, $cateID, $productTabslider){
        $today = date("Y-m-d");
        $page = 0;
        $cateSelected = Configuration::get($this->name . '_pro_cate_data');
        $defaultTabID = Configuration::get($this->name . '_pro_default_tab_cate');
        $mergeCate = Configuration::get($this->name . '_pro_merge_cate');
        $nb = Configuration::get($this->name . '_pro_number_product');
        $per = Configuration::get($this->name.'_pro_number_per_page');
        $quanlity_image = Configuration::get($this->name.'_pro_tab_quanlity_image');
        $thumbWidth = Configuration::get($this->name.'_pro_tab_thumb_width');
        $thumbHeight = Configuration::get($this->name.'_pro_tab_thumb_height');
        $thumbWidthCate = Configuration::get($this->name.'_pro_tab_thumb_width_cate');
        $thumbHeightCate = Configuration::get($this->name.'_pro_tab_thumb_height_cate');
        $enabledCustom = Configuration::get($this->name.'_pro_enabled_custom');
        $contentCustom = Configuration::get($this->name.'_pro_content_custom');
        $icontabs = Configuration::get($this->name.'_pro_icon_tab');
        $fromDate = Configuration::get($this->name . '_from_date_pro');
        $toDate = Configuration::get($this->name . '_to_date_pro');
        $imageCustom = Configuration::get($this->name . '_pro_link_imagecustom');
        $onlyShowProductIDs = Configuration::get($this->name . '_pro_only_proids');
        $removeProductIDs = Configuration::get($this->name . '_pro_remove_proids');
        $themes = Configuration::get($this->name . '_pro_theme');
        if($fromDate == ""){
            $fromDate = "2000-01-01";
        }
        if($toDate == ""){
            $toDate = $today;
        }

        $dorTimeCache  = Configuration::get('dor_themeoptions_dorTimeCache',Configuration::get('dorTimeCache'));
        $dorTimeCache = $dorTimeCache?$dorTimeCache:86400;
        $dorCaches  = Configuration::get('dor_themeoptions_enableDorCache',Configuration::get('enableDorCache'));
        $id_shop = (int)Context::getContext()->shop->id;
        $id_lang = Context::getContext()->language->id;
        $objCache = new DorCaches(array('name'=>'default','path'=>_PS_ROOT_DIR_.'/override/Dor/Caches/smartcaches/tabcategorypro/','extension'=>'.cache'));
        $fileCache = 'HomeTabCategoryProCaches-Shop'.$id_shop.'-tabchose'.$tabChose.'-'.$cateID.'-'.$page;
        $objCache->setCache($fileCache);
        $cacheData = $objCache->renderData($fileCache);
        if($cacheData && $dorCaches ){
            $productData = $cacheData['lists'];
        }else{
            if($tabChose=="all_product"){
                $productData =  $this->getAllProductCategory ((int) Context::getContext()->language->id, $page, ($nb ? $nb : 10), null,  null);
            }else if($tabChose=="new_product"){
                $productData = Product::getNewProducts((int) Context::getContext()->language->id, $page, ($nb ? $nb : 5));
            }else if($tabChose=="feature_product"){
                $category = new Category(Context::getContext()->shop->getCategory(), (int) Context::getContext()->language->id);
                $productData = $category->getProducts((int) Context::getContext()->language->id, $page, ($nb ? $nb : 5));
            }else if($tabChose=="special_product"){
                $productData = Product::getPricesDrop((int) Context::getContext()->language->id, $page, ($nb ? $nb : 5));
            }else if($tabChose=="besseller_product"){
                ProductSale::fillProductSales();
                $productData =  $this->getBestSales ((int) $cateID,(int) Context::getContext()->language->id, $page, ($nb ? $nb : 5), null,  null);
            }else if($tabChose=="mostview_product"){
                $productData = $this->getTableMostViewed($fromDate,$toDate,($nb ? $nb : 5));
                $productData = $productData['products'];
            }
            
            if($productData){
                $id_lang = (int)Context::getContext()->language->id;
                foreach ($productData as $key => $item) {
                    $id_image = Product::getCover($item['id_product']);
                    $images = "";
                    if (sizeof($id_image) > 0){
                        $image = new Image($id_image['id_image']);
                        // get image full URL
                        $image_url = "/p/".$image->getExistingImgPath().".jpg";
                        $linkRewrite = $item['id_product']."_".$item['link_rewrite'];
                        $images = DorImageBase::renderThumbProduct($image_url,$linkRewrite,$thumbWidth,$thumbHeight,$quanlity_image);
                    }
                    
                    $item['imageThumb'] = $images;
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
    public function hookAjaxCall($params)
    {
        global $smarty, $cookie;
        $varsData = "";
        $id_shop = (int)Context::getContext()->shop->id;
        $id_lang = Context::getContext()->language->id;
        $per = Configuration::get($this->name.'_pro_number_per_page');
        if(isset($_POST['cateID']) && $_POST['cateID'] != ""){
            $today = date("Y-m-d");
            $mergeCate = Configuration::get($this->name . '_pro_merge_cate');
            $nb = Configuration::get($this->name . '_pro_number_product');
            $quanlity_image = Configuration::get($this->name . '_pro_tab_quanlity_image');
            $thumbWidth = Configuration::get($this->name . '_pro_tab_thumb_width');
            $thumbHeight = Configuration::get($this->name . '_pro_tab_thumb_height');
            $thumbWidthCate = Configuration::get($this->name . '_pro_tab_thumb_width_cate');
            $thumbHeightCate = Configuration::get($this->name . '_pro_tab_thumb_height_cate');
            $enabledCustom = Configuration::get($this->name.'_pro_enabled_custom');
            $contentCustom = Configuration::get($this->name . '_pro_content_custom');
            $fromDate = Configuration::get($this->name . '_from_date_pro');
            $toDate = Configuration::get($this->name . '_to_date_pro');
            if($fromDate == ""){
                $fromDate = "2000-01-01";
            }
            if($toDate == ""){
                $toDate = $today;
            }
            $page = isset($_POST['page'])?$_POST['page']-1:0;
            $tabchose = $_POST['tabchose'];
            $itemlists = $this->DorCacheTabCategoryPro($tabchose,$_POST['cateID'], $productTabslider=array());
            
            $products = $itemlists;
            $defaultTab = array('id'=>$_POST['cateID'], 'name' => "");
            $options = array(
                'pro_number_per_page'=>$per
            );
            $cartUrl = $this->context->link->getPageLink('cart', true);
            $this->context->smarty->assign('products', $products);
            $this->context->smarty->assign('options', $options);
            $this->context->smarty->assign('cartUrl', $cartUrl);
            $this->context->smarty->assign('static_token', Tools::getToken(false));
            $this->context->smarty->assign('tabID', $defaultTab);
            $this->context->smarty->assign('self', $this->selfPath);
            $this->context->smarty->assign('page_name', "index");
            $varsData = $this->display(__FILE__, '/views/templates/hook/product-item.tpl', $this->getCacheId()."-".$_POST['cateID'].'-'.$id_shop.'-'.$id_lang);
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
    /**
     * Returns image path in the old or in the new filesystem
     *
     * @ returns string image path
     */
    public function getExistingImgPathCategory($id_category)
    {
        if (!$this->existing_path_cate) {
            if (file_exists(_PS_CAT_IMG_DIR_.$id_category.'.'.$this->image_format_cate)) {
                $this->existing_path_cate = $id_category;
            } else {
                $this->existing_path_cate = "";
            }
        }

        return $this->existing_path_cate;
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
	
    public function getProductCategory($idCate, $mergeCate, $onlyShowProductIDs="", $removeProductIDs="", $id_lang, $page_number = 0, $nb_products = 10, $order_by = null, $order_way = null)
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
        if(trim($removeProductIDs) != ""){
            $whereCate .= ' AND p.`id_product` NOT IN ('.$removeProductIDs.')';
        }
        if(trim($onlyShowProductIDs) != ""){
            $whereCate .= ' AND p.`id_product` IN ('.$onlyShowProductIDs.')';
        }

        if ($order_by == 'date_add')
            $prefix = 'p.';
        
        $sql = 'SELECT p.*, product_shop.*, stock.out_of_stock, IFNULL(stock.quantity, 0) as quantity, pl.`link_rewrite`, pl.`name`,
                    MAX(image_shop.`id_image`) id_image, il.`legend`, t.`rate`,
                    DATEDIFF(p.`date_add`, DATE_SUB(NOW(),
                    INTERVAL '.$interval.' DAY)) > 0 AS new
                FROM `'._DB_PREFIX_.'product` p
                '.Shop::addSqlAssociation('product', 'p', false).'
                INNER JOIN `ps_category_product` cp ON (cp.`id_product` = p.`id_product`)
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