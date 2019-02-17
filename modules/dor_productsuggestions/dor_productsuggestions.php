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
class Dor_Productsuggestions extends Module {
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
        $this->name = 'dor_productsuggestions';
        $this->tab = 'front_office_features';
        $this->version = '2.0.0';
        $this->bootstrap =true;
        $this->author = 'Dorado Themes';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.5', 'max' => '1.7.99.99');
        parent::__construct();
        $this->displayName = $this->l('Dor Product Suggestions');
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
        $tab->name[$language['id_lang']] = $this->l('Dor Product Suggestions');
        $tab->class_name = 'AdminDorSuggestions';
        $tab->id_parent = (int)Tab::getIdFromClassName('AdminDorMenu'); 
        $tab->module = $this->name;
        $tab->add();
        $today = date("Y-m-d");
        Configuration::updateValue($this->name . '_suggestions_theme','theme1');
        Configuration::updateValue($this->name . '_suggestions_title','Product Suggestions');
        Configuration::updateValue($this->name . '_suggestions_productIds','1,2,3,4,5');
        Configuration::updateValue($this->name . '_suggestions_per_page',4);
        Configuration::updateValue($this->name . '_suggestions_auto_play',0);
        Configuration::updateValue($this->name . '_suggestions_quanlity_image',100);
        Configuration::updateValue($this->name . '_suggestions_thumb_width',450);
        Configuration::updateValue($this->name . '_suggestions_thumb_height',450);
        return parent::install() &&
                $this->registerHook('displayHeader')
                &&
                $this->registerHook('displayBackOfficeHeader')
                && 
                $this->registerHook('DorSuggestions');
    }

    public function uninstall() {
        $today = date("Y-m-d");
        Configuration::deleteByName($this->name . '_suggestions_theme','theme1');
        Configuration::deleteByName($this->name . '_suggestions_title','Product Suggestions');
        Configuration::deleteByName($this->name . '_suggestions_productIds','1,2,3,4,5');
        Configuration::deleteByName($this->name . '_suggestions_per_page',4);
        Configuration::deleteByName($this->name . '_suggestions_auto_play',0);
        Configuration::deleteByName($this->name . '_suggestions_quanlity_image',100);
        Configuration::deleteByName($this->name . '_suggestions_thumb_width',450);
        Configuration::deleteByName($this->name . '_suggestions_thumb_height',450);
        // Uninstall Module
        $tab = new Tab((int) Tab::getIdFromClassName('AdminDorSuggestions'));
        $tab->delete();
        if (!parent::uninstall())
            return false;
        return true;
    }

    private function _postProcess() {
        Configuration::updateValue($this->name . '_suggestions_theme', Tools::getValue('suggestions_theme'));
        Configuration::updateValue($this->name . '_suggestions_title', Tools::getValue('suggestions_title'));
        Configuration::updateValue($this->name . '_suggestions_productIds', Tools::getValue('suggestions_productIds'));
        Configuration::updateValue($this->name . '_suggestions_per_page', Tools::getValue('suggestions_per_page'));
        Configuration::updateValue($this->name . '_suggestions_auto_play', Tools::getValue('suggestions_auto_play'));
        Configuration::updateValue($this->name . '_suggestions_quanlity_image', Tools::getValue('suggestions_quanlity_image'));
        Configuration::updateValue($this->name . '_suggestions_thumb_width', Tools::getValue('suggestions_thumb_width'));
        Configuration::updateValue($this->name . '_suggestions_thumb_height', Tools::getValue('suggestions_thumb_height'));
        $this->_html .= $this->displayConfirmation($this->l('Configuration updated'));
    }
    public function getContent() {
        $output = '<h2>' . $this->displayName . '</h2>';
        if (Tools::isSubmit('submitProductSuggestions')) {
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
        
        $themes = array(
            array( 'id'=>'theme1','mode'=>'Theme 1')
        );
        $id_shop = (int)Context::getContext()->shop->id;
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
                        'name' => 'suggestions_theme',
                        'options' => array(
                            'query' => $themes,
                            'id' => 'id',
                            'name'=>'mode',
                        ),
                    ),
                    array(
                        'type' => 'text',
                        'label' => 'Module Title:',
                        'name' => 'suggestions_title',
                        'class' => 'fixed-width-md',
                    ),
                    array(
                        'type' => 'text',
                        'label' => 'Product IDs:',
                        'name' => 'suggestions_productIds',
                        'class' => 'fixed-width-md',
                    ),
                    array(
                        'type' => 'text',
                        'label' => 'Number Per Page:',
                        'name' => 'suggestions_per_page',
                        'class' => 'fixed-width-md',
                    ),
                    array(
                        'type' => 'switch',
                        'label' => 'Auto play :',
                        'name' => 'suggestions_auto_play',
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
                        'name' => 'suggestions_quanlity_image',
                        'class' => 'fixed-width-md',
                    ),
                    array(
                        'type' => 'text',
                        'label' => 'Thumb width image:',
                        'name' => 'suggestions_thumb_width',
                        'class' => 'fixed-width-md',
                    ),
                    array(
                        'type' => 'text',
                        'label' => 'Thumb height image:',
                        'name' => 'suggestions_thumb_height',
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
        $helper->submit_action = 'submitProductSuggestions';
        $helper->module = $this;
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );
        $helper->override_folder = '/';
        return $helper->generateForm(array($fields_form));
    }
    public function getConfigFieldsValues()
    {
        return array(
            'suggestions_theme' =>     Tools::getValue('suggestions_theme', Configuration::get($this->name . '_suggestions_theme')),
            'suggestions_title' =>     Tools::getValue('suggestions_title', Configuration::get($this->name . '_suggestions_title')),
            'suggestions_productIds' =>     Tools::getValue('suggestions_productIds', Configuration::get($this->name . '_suggestions_productIds')),
            'suggestions_per_page' =>   Tools::getValue('suggestions_per_page', Configuration::get($this->name . '_suggestions_per_page')),
            'suggestions_auto_play' =>     Tools::getValue('suggestions_auto_play', Configuration::get($this->name . '_suggestions_auto_play')),
            'suggestions_quanlity_image' =>     Tools::getValue('suggestions_quanlity_image', Configuration::get($this->name . '_suggestions_quanlity_image')),
            'suggestions_thumb_width' =>    Tools::getValue('suggestions_thumb_width', Configuration::get($this->name . '_suggestions_thumb_width')),
            'suggestions_thumb_height' => Tools::getValue('suggestions_thumb_height', Configuration::get($this->name . '_suggestions_thumb_height')),
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

        $this->context->controller->addCSS($this->_path . 'assets/css/dor_productsuggestions.css');
        $this->context->controller->addJS($this->_path . 'assets/js/dor_productsuggestions.js');
    }
    public function hookDisplayBackOfficeHeader()
    {
       
    }
 
    public function hookDorSuggestions($params){
        global $smarty, $cookie;
        $suggestions_title = Configuration::get($this->name.'_suggestions_title');
        $per = Configuration::get($this->name.'_suggestions_per_page');
        $productIds = Configuration::get($this->name.'_suggestions_productIds');
        $quanlity_image = Configuration::get($this->name.'_suggestions_quanlity_image');
        $thumbWidth = Configuration::get($this->name.'_suggestions_thumb_width');
        $thumbHeight = Configuration::get($this->name.'_suggestions_thumb_height');
        $themes = Configuration::get($this->name . '_suggestions_theme');
        /****Tab Select****/
        $languages = Language::getLanguages(true, $this->context->shop->id);
        $productData = "";
        
        $itemlists = array();
        $listTabs = array();
        $itemlists = $this->DorCacheProductSuggestions();
            $this->context->controller->addColorsToProductList($itemlists);
        $products = $itemlists;
        $this->context->smarty->assign('products', $products);
        $this->context->smarty->assign('suggestions_title', $suggestions_title);
        $this->context->smarty->assign('self', $this->selfPath);
        $this->context->smarty->assign('productItemPath', $this->product_path);
        $this->context->smarty->assign('page_name', $this->context->controller->php_self);
        return $this->display(__FILE__, 'dor_productsuggestions.tpl', $this->getCacheId());
    }
    
    public function clearCache()
    {
        $this->_clearCache('dor_productsuggestions.tpl');
    }
    public function DorCacheProductSuggestions(){
        $page = 0;
        $productIds = Configuration::get($this->name.'_suggestions_productIds');
        $quanlity_image = Configuration::get($this->name.'_suggestions_quanlity_image');
        $thumbWidth = Configuration::get($this->name.'_suggestions_thumb_width');
        $thumbHeight = Configuration::get($this->name.'_suggestions_thumb_height');
        $themes = Configuration::get($this->name . '_suggestions_theme');

        $dorCaches  = Tools::getValue('enableDorCache',Configuration::get('enableDorCache'));
        $id_shop = (int)Context::getContext()->shop->id;
        $objCache = new DorCaches(array('name'=>'default','path'=>_PS_ROOT_DIR_.'/override/Dor/Caches/smartcaches/productsuggestions/','extension'=>'.cache'));
        $fileCache = 'HomeProductSuggestionCaches-Shop'.$id_shop;
        $objCache->setCache($fileCache);
        $cacheData = $objCache->renderData($fileCache);
        if($cacheData && $dorCaches ){
            $productData = $cacheData['lists'];
        }else{
            if(trim($productIds) != ""){
                $productIds = explode(",", $productIds);
                $nb=0;
                if(count($productIds) > 0) $nb=count($productIds);
            }
            $productData =  $this->getAllProductCategory ($productIds, (int) Context::getContext()->language->id, $page, ($nb ? $nb : 10), null,  null);
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
                if($dorCaches){
                    $data['lists'] = $productData;
                    $objCache->store($fileCache, $data, $expiration = TIME_CACHE_HOME);
                }
            }
        }
        return $productData;
        
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


    public function getAllProductCategory($productIds=array(), $id_lang, $page_number = 0, $nb_products = 10, $order_by = null, $order_way = null)
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
        $where = ' AND p.`id_product` IN ('.implode(',', array_map('intval', $productIds)).')';
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
                    AND product_shop.`visibility` != \'none\''.$where.'
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