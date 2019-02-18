<?php
/*
* 2007-2015 PrestaShop
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
*  @copyright  2007-2015 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;

//require(dirname(__FILE__).'/menutoplinks.class.php');
if (!defined('_PS_VERSION_'))
    exit;
if (!class_exists( 'DorCaches' )) {     
    require_once (_PS_ROOT_DIR_.'/override/Dor/Caches/DorCaches.php');
}
define('_DORMEGAMENU_MCRYPT_KEY_', md5('key_dormegamenu'));
define('_DORMEGAMENU_MCRYPT_IV_', md5('iv_dormegamenu'));

include_once(_PS_MODULE_DIR_.'dor_megamenu/classes/DorMegamenuMcrypt.php');
include_once(_PS_MODULE_DIR_.'dor_megamenu/classes/DorMegamenuHelper.php');
include_once(_PS_MODULE_DIR_.'dor_megamenu/classes/DorMegamenu.php');
include_once(_PS_MODULE_DIR_.'dor_megamenu/classes/DorMegamenuWidget.php');
include_once(_PS_MODULE_DIR_.'dor_megamenu/classes/DorMegamenuWidgetBase.php');
class Dor_MegaMenu extends Module
{
    const MENU_JSON_CACHE_KEY = 'MOD_BLOCKTOPMENU_MENU_JSON';

    protected $_menu = '';
    protected $_html = '';
    protected $user_groups;

    /*
     * Pattern for matching config values
     */
    protected $pattern = '/^([A-Z_]*)[0-9]+/';

    /*
     * Name of the controller
     * Used to set item selected or not in top menu
     */
    protected $page_name = '';

    /*
     * Spaces per depth in BO
     */
    protected $spacer_size = '5';

    public function __construct()
    {
        $this->name = 'dor_megamenu';
        $this->tab = 'front_office_features';
        $this->version = '2.0.0';
        $this->author = 'Dorado Themes';
        $this->need_instance = 0;
        $this->bootstrap = true;
        $this->secure_key = Tools::encrypt($this->name);
        $this->prefix = 'dormm_';
        parent::__construct();
        $this->displayName = $this->l('Dor Megamenu');
        $this->description = $this->l('Manager and display megamenu use bootstrap framework');
    }

    public function install()
    {
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
        $tab->name[$language['id_lang']] = $this->l('Dor Megamenu');
        $tab->class_name = 'AdminDorMegamenu';
        $tab->id_parent = (int)Tab::getIdFromClassName('AdminDorMenu'); 
        $tab->module = $this->name;
        $tab->add();

        if (!parent::install() || !$this->registerHook('actionAdminControllerSetMedia')
            || !$this->registerHook('displayTop') || !$this->registerHook('dormegamenu') || !$this->registerHook('displayHeader') ||

            !$this->registerHook('actionObjectCategoryUpdateAfter') ||
            !$this->registerHook('actionObjectCategoryDeleteAfter') ||
            !$this->registerHook('actionObjectCategoryAddAfter') ||

            !$this->registerHook('actionObjectCmsUpdateAfter') ||
            !$this->registerHook('actionObjectCmsDeleteAfter') ||
            !$this->registerHook('actionObjectCmsAddAfter') ||

            !$this->registerHook('actionObjectSupplierUpdateAfter') ||
            !$this->registerHook('actionObjectSupplierDeleteAfter') ||
            !$this->registerHook('actionObjectSupplierAddAfter') ||

            !$this->registerHook('actionObjectManufacturerUpdateAfter') ||
            !$this->registerHook('actionObjectManufacturerDeleteAfter') ||
            !$this->registerHook('actionObjectManufacturerAddAfter') ||

            !$this->registerHook('actionObjectProductUpdateAfter') ||
            !$this->registerHook('actionObjectProductDeleteAfter') ||
            !$this->registerHook('actionObjectProductAddAfter') ||

            !$this->registerHook('categoryUpdate') ||
            !$this->registerHook('actionShopDataDuplication') ||
            !$this->registerHook('actionObjectLanguageAddAfter')
            ) {
            return false;
        }
        include_once (_PS_MODULE_DIR_.'dor_megamenu/install/install.php');
        return true;
    }

    public function uninstall()
    {
        $tab = new Tab((int) Tab::getIdFromClassName('AdminDorMegamenu'));
        $tab->delete();
        if (parent::uninstall())
            return true;
        return false;
    }

    public function getConfigName($name) {
        return Tools::strtoupper($this->prefix . $name);
    }

    public function reset()
    {
        if (!$this->uninstall(false)) {
            return false;
        }
        if (!$this->install(false)) {
            return false;
        }

        return true;
    }

    public function getContent()
    {
        if (Tools::getValue('action') && Tools::getValue('secure_key') == $this->secure_key) {
            $fnc = (string)Tools::getValue('action');
            $this->{$fnc}();
        }
        
        $html = '<h2>'.$this->displayName.'</h2>';

        return $this->adminMainPage();
    }

    public function adminMainPage() {
        $base_config_url = 'index.php?controller=AdminModules&configure='.$this->name.'&token='.Tools::getValue('token');

        $this->context->smarty->assign(array(
            'html_choices_select' => $this->renderChoicesSelect(),
            'ajaxurl' => _MODULE_DIR_.$this->name.'/ajax.php',
            'adminajaxurl' => $base_config_url,
            'id_shop' => (int)Context::getContext()->shop->id,
            'secure_key' => $this->secure_key,
            'list_menu' => $this->listMenu(),
            'list_widgets' => DorMegamenuWidget::renderAdminListWidgets()
        ));

        return $this->display(__FILE__, 'admin_main_page.tpl');
    }

    public function widgetForm() {
        $obj = new DorMegamenuWidget();
        $obj->loadEngines();

        $id = (int)Tools::getValue('id');
        $type = trim(Tools::strtolower(Tools::getValue('widget_type')));
        $data = array();
        if ($id) {
            $data = $obj->getWidetById($id);
        }

        if (isset($data['type'])) {
            $type = $data['type'];
        }

        $data['params']['type'] = $type;
        echo $obj->getForm($type, $data);
        die();
    }

    public function getListWidgets() {
        $obj = new DorMegamenuWidget();
        $obj->loadEngines();
        return $obj->getListWidgets();
    }
    public function listMenu() {
        $obj = new ObjectDorMegamenu(1);
        $list_menu = $obj->recurseGetAdminMenuTree();

        return $list_menu;
    }

    public function getSubmenuSettingForm($id_dormegamenu) {
        $obj = new ObjectDorMegamenu($id_dormegamenu);
        $show_menumenu = false;
        if ($obj->id_parent == 1) {
            $show_menumenu = true;
        }
        $data = $obj->parserParams($obj->params);
        //d(json_decode($data['params']));
        $this->context->smarty->assign(array(
            'ajaxurl' => _MODULE_DIR_.$this->name.'/ajax.php',
            'secure_key' => $this->secure_key,
            'listWidgets' => DorMegamenuWidget::getWidgets(),
            'id_dormegamenu' => $id_dormegamenu,
            'data' => $data,
            'show_menumenu' => $show_menumenu
        ));
        return $this->display(__FILE__, 'admin_submenu_setting_form.tpl');
    }

    public function editMenu() {
        echo $this->getMenuForm( (int)Tools::getValue('id_menu') );
        die();
    }

    public function getMenuForm($id_dormegamenu) {
        $obj = new ObjectDorMegamenu( $id_dormegamenu );
        $inputs = array();
        $inputs[] = array(
            'type' => 'hidden',
            'label' => $this->l('Id Megamenu'),
            'name' => 'id_dormegamenu',
            'default' => ''
        );
        $inputs[] = array(
            'type' => 'text',
            'label' => $this->l('Name'),
            'name' => 'name',
            'default' => '',
            'lang' => true
        );
        $inputs[] = array(
            'type' => 'text',
            'label' => $this->l('Description'),
            'name' => 'description',
            'default' => '',
            'lang' => true
        );
        if ($obj->type == 'custom-link') {
            $inputs[] = array(
                'type' => 'text',
                'label' => $this->l('Custom Link'),
                'name' => 'url',
                'default' => '',
                'lang' => true
            );
        }
        $inputs[] = array(
            'type' => 'switch',
            'label' => $this->l('Active'),
            'name' => 'active',
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
            )
        );
        $fields_form[0]['form'] = array(
            'legend' => array(
                'title' => $obj->type . ' : '. $obj->name[$this->context->language->id],
            ),
            'input' => $inputs
        );
        $default_lang = $this->context->language->id;
        $helper = new HelperForm();
        $helper->module = new $this->name;
        $helper->name_controller = $this->name;
        $helper->identifier = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        foreach (Language::getLanguages(false) as $lang)
            $helper->languages[] = array(
                'id_lang' => $lang['id_lang'],
                'iso_code' => $lang['iso_code'],
                'name' => $lang['name'],
                'is_default' => ($default_lang == $lang['id_lang'] ? 1 : 0)
            );

        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name.'&widgets=1&rand='.rand().'&wtype='.Tools::getValue('wtype');
        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;
        $helper->toolbar_scroll = true;
        $helper->title = $this->name;
        $helper->submit_action = 'save'.$this->name;
        $helper->tpl_vars = array(
            'fields_value' => DorMegamenuHelper::getConfigFieldsValues($fields_form, $obj),
            'languages' => Language::getLanguages(false),
            'id_language' => $default_lang
        );
        $form = $helper->generateForm($fields_form);

        $this->context->smarty->assign(array(
            'ajaxurl' => _MODULE_DIR_.$this->name.'/ajax.php',
            'secure_key' => $this->secure_key,
            'form' => $form
        ));
        return $this->display(__FILE__, 'admin_menu_form.tpl');
    }

    public function renderChoicesSelect()
    {
        $html = '<ul id="list-to-choose">';

        // BEGIN Categories
        $html .= '<li id="add-categories" class="control-section accordion-section add-category">';

        $html .= '<h3 class="accordion-section-title hndle" tabindex="0">';
        $html .= $this->l('Categories');
        $html .= '</h3>';
        $html .= '<div class="accordion-section-content">';
            $html .= '<div class="inside">';

            $shops_to_get = Shop::getContextListShopID();
            foreach ($shops_to_get as $shop_id) {
                $html .= $this->generateCategoriesOption(DorMegamenuHelper::customGetNestedCategories($shop_id, null, (int)$this->context->language->id, false));
            }
            $html .= '</div>';
            $html .= '<p class="button-controls">';
            $html .= '<a class="select-all" data-type="category" href="#">'.$this->l('Select All').'</a>';
            $html .= '<button class="btn btn-default add-to-menu btn-xs pull-right" type="button" data-type="category">'.$this->l('Add To Menu').'</button>';
            $html .= '</p>';
        $html .= '</div>';

        $html .= '</li>';

        // CMS Category
        $html .= '<li id="add-cms-category" class="control-section accordion-section add-cms-category">';

        $html .= '<h3 class="accordion-section-title hndle" tabindex="0">';
        $html .= $this->l('Cms Category');
        $html .= '</h3>';
        $html .= '<div class="accordion-section-content">';
            $html .= '<div class="inside">';
            $html .= $this->getCMSCategoryOptions(0, 1, $this->context->language->id);
            
            $html .= '</div>';
            $html .= '<p class="button-controls">';
            $html .= '<a class="select-all" data-type="cms-category" href="#">'.$this->l('Select All').'</a>';
            $html .= '<button class="btn btn-default add-to-menu btn-xs pull-right" type="button" data-type="cms-category">'.$this->l('Add To Menu').'</button>';
            $html .= '</p>';
        $html .= '</div>';

        $html .= '</li>';

        // BEGIN CMS
        $cmss = CMS::listCms();
        if (!empty($cmss)) {
            $html .= '<li id="add-cms" class="control-section accordion-section add-cms">';
            $html .= '<h3 class="accordion-section-title hndle" tabindex="0">';
            $html .= $this->l('Cms');
            $html .= '</h3>';
            $html .= '<div class="accordion-section-content">';
                $html .= '<div class="inside">';
                $html .= '<ul>';
                foreach ($cmss as $cms) {
                    $html .= '<li>';
                        $html .= '<label class="checkbox-inline">';
                        $html .= '<input type="checkbox" name="id_cms['.$cms['id_cms'].']" value="'.$cms['id_cms'].'"> ';
                        $html .= $cms['meta_title'];
                        $html .= '</label>';

                    $html .= '</li>';
                }
                $html .= '</ul>';
                $html .= '</div>';
                $html .= '<p class="button-controls">';
                $html .= '<a class="select-all" data-type="cms" href="#">'.$this->l('Select All').'</a>';
                $html .= '<button class="btn btn-default add-to-menu btn-xs pull-right" type="button" data-type="cms">'.$this->l('Add To Menu').'</button>';
                $html .= '</p>';
            $html .= '</div>';

            $html .= '</li>';
        }

        // BEGIN SUPPLIER
        $suppliers = Supplier::getSuppliers(false, $this->context->language->id);
        if (!empty($suppliers)) {
            $html .= '<li id="add-supplier" class="control-section accordion-section add-supplier">';
            $html .= '<h3 class="accordion-section-title hndle" tabindex="0">';
            $html .= $this->l('Supplier');
            $html .= '</h3>';
            $html .= '<div class="accordion-section-content">';
                $html .= '<div class="inside">';
                $html .= '<ul>';
                foreach ($suppliers as $supplier) {
                    $html .= '<li>';
                        $html .= '<label class="checkbox-inline">';
                        $html .= '<input type="checkbox" name="id_supplier['.$supplier['id_supplier'].']" value="'.$supplier['id_supplier'].'"> ';
                        $html .= $supplier['name'];
                        $html .= '</label>';

                    $html .= '</li>';
                }
                $html .= '</ul>';
                $html .= '</div>';
                $html .= '<p class="button-controls">';
                $html .= '<a class="select-all" data-type="supplier" href="#">'.$this->l('Select All').'</a>';
                $html .= '<button class="btn btn-default add-to-menu btn-xs pull-right" type="button" data-type="supplier">'.$this->l('Add To Menu').'</button>';
                $html .= '</p>';
            $html .= '</div>';

            $html .= '</li>';
        }

        // BEGIN Manufacturer
        $manufacturers = Manufacturer::getManufacturers(false, $this->context->language->id);
        if (!empty($manufacturers)) {
            $html .= '<li id="add-manufacturer" class="control-section accordion-section add-manufacturer">';
            $html .= '<h3 class="accordion-section-title hndle" tabindex="0">';
            $html .= $this->l('Manufacturer');
            $html .= '</h3>';
            $html .= '<div class="accordion-section-content">';
                $html .= '<div class="inside">';
                $html .= '<ul>';
                foreach ($manufacturers as $manufacturer) {
                    $html .= '<li>';
                        $html .= '<label class="checkbox-inline">';
                        $html .= '<input type="checkbox" name="id_manufacturer['.$manufacturer['id_manufacturer'].']" value="'.$manufacturer['id_manufacturer'].'"> ';
                        $html .= $manufacturer['name'];
                        $html .= '</label>';
                    $html .= '</li>';
                }
                $html .= '</ul>';
                $html .= '</div>';
                $html .= '<p class="button-controls">';
                $html .= '<a class="select-all" data-type="manufacturer" href="#">'.$this->l('Select All').'</a>';
                $html .= '<button class="btn btn-default add-to-menu btn-xs pull-right" type="button" data-type="manufacturer">'.$this->l('Add To Menu').'</button>';
                $html .= '</p>';
            $html .= '</div>';

            $html .= '</li>';
        }

        // BEGIN Shops
        if (Shop::isFeatureActive()) {

            $html .= '<li id="add-shops" class="control-section accordion-section add-shop">';

            $html .= '<h3 class="accordion-section-title hndle" tabindex="0">';
            $html .= $this->l('Shops');
            $html .= '</h3>';
            $html .= '<div class="accordion-section-content">';
                $html .= '<div class="inside">';

                $shops = Shop::getShopsCollection();
                $html .= '<ul>';
                foreach ($shops as $shop) {
                    if (!$shop->setUrl() && !$shop->getBaseURL()) {
                        continue;
                    }
                    $html .= '<li>';
                    $html .= '<label class="checkbox-inline">';
                    $html .= '<input type="checkbox" name="id_shop['.$shop->id.']" value="'.$shop->id.'"> ';
                    $html .= $shop->name;
                    $html .= '<label>';
                    $html .= '</li>';
                }
                $html .= '</ul>';
                $html .= '</div>';
                $html .= '<p class="button-controls">';
                $html .= '<a class="select-all" data-type="shop" href="#">'.$this->l('Select All').'</a>';
                $html .= '<button class="btn btn-default add-to-menu btn-xs pull-right" type="button" data-type="shop">'.$this->l('Add To Menu').'</button>';
                $html .= '</p>';
            $html .= '</div>';

            $html .= '</li>';

        }

        // BEGIN Products
        $html .= '<li id="add-products" class="control-section accordion-section add-product">';

        $html .= '<h3 class="accordion-section-title hndle" tabindex="0">';
        $html .= $this->l('Products');
        $html .= '</h3>';
        $html .= '<div class="accordion-section-content">';
            $html .= '<div class="inside">';

                $html .= '<ul>';
                    $html .= '<li>';
                    $html .= '<div class="inner"><span>'.$this->l('Product ID').'</span>';
                    $html .= '<input type="text" name="id_product" class="id_product" placeholder="'.$this->l('Enter your product ID').'"> ';
                    $html .= '</div>';
                    $html .= '</li>';
                $html .= '</ul>';
            $html .= '</div>';
            $html .= '<p class="button-controls">';
            $html .= '<button class="btn btn-default add-to-menu btn-xs pull-right" type="button" data-type="product">'.$this->l('Add To Menu').'</button>';
            $html .= '</p>';
        $html .= '</div>';

        $html .= '</li>';

        // BEGIN Custom Url
        $html .= '<li id="add-custom-link" class="control-section accordion-section add-custom-link">';

        $html .= '<h3 class="accordion-section-title hndle" tabindex="0">';
        $html .= $this->l('Custom Link');
        $html .= '</h3>';
        $html .= '<div class="accordion-section-content">';
            $html .= '<div class="inside">';

                $html .= '<ul>';
                    $html .= '<li>';
                    $html .= '<div class="inner"><span>'.$this->l('Menu Name').'</span>';
                    $html .= '<input type="text" name="custom_name" class="custom_name" placeholder="'.$this->l('Enter Your Menu Name').'"> ';
                    $html .= '</div>';
                    $html .= '</li>';

                    $html .= '<li>';
                    $html .= '<div class="inner"><span>'.$this->l('Menu Url').'</span>';
                    $html .= '<input type="text" name="custom_link" class="custom_link" placeholder="'.$this->l('Enter Your Menu Url').'"> ';
                    $html .= '</div>';
                    $html .= '</li>';
                $html .= '</ul>';
            $html .= '</div>';
            $html .= '<p class="button-controls">';
            $html .= '<button class="btn btn-default add-to-menu btn-xs pull-right" type="button" data-type="custom-link">'.$this->l('Add To Menu').'</button>';
            $html .= '</p>';
        $html .= '</div>';

        $html .= '</li>';

        $html .= '</ul>';
        return $html;
    }
    protected function getCMSCategoryOptions($parent = 0, $depth = 1, $id_lang = false, $id_shop = false)
    {
        $html = '';
        $id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext()->language->id;
        $id_shop = ($id_shop !== false) ? $id_shop : Context::getContext()->shop->id;
        $categories = DorMegamenuHelper::getCMSCategories(false, (int)$parent, (int)$id_lang, (int)$id_shop);

        if (!empty($categories)) {
            $html .= '<ul>';
            foreach ($categories as $category) {
                $html .= '<li>';
                    $html .= '<label class="checkbox-inline">';
                    $html .= '<input type="checkbox" name="id_cms_category['.$category['id_cms_category'].']" value="'.$category['id_cms_category'].'"> ';
                    $html .= $category['name'];
                    $html .= '</label>';
                    $html .= $this->getCMSCategoryOptions($category['id_cms_category'], (int)$depth + 1, (int)$id_lang);
                $html .= '</li>';
            }
            $html .= '</ul>';
        }

        return $html;
    }

    protected function generateCategoriesOption($categories)
    {
        $html = '';
        $html .= '<ul>';
        foreach ($categories as $key => $category) {
            $html .= '<li>';
                $html .= '<label class="checkbox-inline">';
                $html .= '<input type="checkbox" name="id_category['.$category['id_category'].']" value="'.$category['id_category'].'"> ';
                $html .= $category['name'];
                $html .= '</label>';
            
                if (isset($category['children']) && !empty($category['children'])) {
                    $html .= $this->generateCategoriesOption($category['children']);
                }

            $html .= '</li>';
        }
        $html .= '</ul>';
        return $html;
    }

    public function renderWidgetContent($type, $data)
    {
        $output = '';
        if ($type) {
            $data['id_lang'] = $this->context->language->id;

            $this->smarty->assign($data);
            $output .= '<div class="widget-content">';
            $output .= $this->display(__FILE__, 'widgets/'.$type.'.tpl');
            $output .= '</div>';
        }
        return $output;
    }
    public function hookActionAdminControllerSetMedia() {
        if (get_class($this->context->controller) == 'AdminModulesController' && Tools::getValue('configure') == 'dor_megamenu')
        {
            if (method_exists($this->context->controller, 'addJquery'))
                $this->context->controller->addJquery();
            $this->context->controller->addJqueryUI(array(
                'ui.accordion',
                'ui.sortable'
            ));
            $this->context->controller->addCSS($this->_path.'views/css/admin/form.css');

            // admin tree js
            $admin_webpath = str_ireplace(_PS_CORE_DIR_, '', _PS_ADMIN_DIR_);
            $admin_webpath = preg_replace('/^'.preg_quote(DIRECTORY_SEPARATOR, '/').'/', '', $admin_webpath);
            $bo_theme = ((Validate::isLoadedObject(Context::getContext()->employee)
                && Context::getContext()->employee->bo_theme) ? Context::getContext()->employee->bo_theme : 'default');

            if (!file_exists(_PS_BO_ALL_THEMES_DIR_.$bo_theme.DIRECTORY_SEPARATOR.'template')) {
                $bo_theme = 'default';
            }
            $js_tree = __PS_BASE_URI__.$admin_webpath.'/themes/'.$bo_theme.'/js/tree.js';

            // add jss
            $this->context->controller->addJs(
                array(
                    $this->_path.'views/js/admin/jquery.nestable.js',
                    $this->_path.'views/js/admin/form.js',
                    _PS_JS_DIR_.'tiny_mce/tiny_mce.js',
                    _PS_JS_DIR_.'admin/tinymce.inc.js',
                    $js_tree
                )
            );
        }
    }
    public function hookDisplayHeader() {
        $this->context->controller->addCSS($this->_path.'views/css/style.css');
        $this->context->controller->addJS($this->_path.'views/js/script.js');
    }

    public function hookDisplayTop($params) {
        
        $tpl = 'megamenu.tpl';

        $id_shop = (int)Context::getContext()->shop->id;
        $id_lang = (int)Context::getContext()->language->id;
        $cache_id = $this->getCacheId($id_shop."-".$id_lang);

        if (!$this->isCached('megamenu.tpl', $cache_id))
        {
            $fileCache = "DorMegamenu-Shop".$id_shop.'-'.$id_lang;
            $dorTimeCache  = Configuration::get('dor_themeoptions_dorTimeCache',Configuration::get('dorTimeCache'));
            $dorTimeCache = $dorTimeCache?$dorTimeCache:86400;
            $dorCaches  = Configuration::get('dor_themeoptions_enableDorCache',Configuration::get('enableDorCache'));
            $cacheData = array();
            if($dorCaches){
                $objCache = new DorCaches(array('name'=>'default','path'=>_PS_ROOT_DIR_.'/override/Dor/Caches/smartcaches/megamenu/','extension'=>'.cache'));
                $objCache->setCache($fileCache);
                $cacheData = $objCache->renderData($fileCache);
            }
            if($cacheData && $dorCaches){
                $output = $cacheData;
            }else{
                $obj = new ObjectDorMegamenu(1);
                $output = $obj->renderMegaMenu($this);
                if($dorCaches){
                    $objCache->store($fileCache, $output, $expiration = $dorTimeCache);
                }
            }
            $this->smarty->assign([
                'output' => $output
            ]);
        }
        return $this->display(__FILE__, 'megamenu.tpl',$cache_id);
    }
    public function hookDormegamenu($params){
        return $this->hookDisplayTop($params);
    }


    public function hookActionObjectCategoryAddAfter($params)
    {
        $this->clearMenuCache();
    }

    public function hookActionObjectCategoryUpdateAfter($params)
    {
        $this->clearMenuCache();
    }

    public function hookActionObjectCategoryDeleteAfter($params)
    {
        $this->clearMenuCache();
    }

    public function hookActionObjectCmsUpdateAfter($params)
    {
        $this->clearMenuCache();
    }

    public function hookActionObjectCmsDeleteAfter($params)
    {
        $this->clearMenuCache();
    }

    public function hookActionObjectCmsAddAfter($params)
    {
        $this->clearMenuCache();
    }

    public function hookActionObjectSupplierUpdateAfter($params)
    {
        $this->clearMenuCache();
    }

    public function hookActionObjectSupplierDeleteAfter($params)
    {
        $this->clearMenuCache();
    }

    public function hookActionObjectSupplierAddAfter($params)
    {
        $this->clearMenuCache();
    }

    public function hookActionObjectManufacturerUpdateAfter($params)
    {
        $this->clearMenuCache();
    }

    public function hookActionObjectManufacturerDeleteAfter($params)
    {
        $this->clearMenuCache();
    }

    public function hookActionObjectManufacturerAddAfter($params)
    {
        $this->clearMenuCache();
    }

    public function hookActionObjectProductUpdateAfter($params)
    {
        $this->clearMenuCache();
    }

    public function hookActionObjectProductDeleteAfter($params)
    {
        $this->clearMenuCache();
    }

    public function hookActionObjectProductAddAfter($params)
    {
        $this->clearMenuCache();
    }

    public function hookCategoryUpdate($params)
    {
        $this->clearMenuCache();
    }

    protected function getCacheDirectory()
    {
        return _PS_CACHE_DIR_ . DIRECTORY_SEPARATOR . 'dor_megamenu';
    }

    protected function clearMenuCache()
    {
        $dir = $this->getCacheDirectory();

        if (!is_dir($dir)) {
            return;
        }

        foreach (scandir($dir) as $entry) {
            if (preg_match('/\.json$/', $entry)) {
                unlink($dir . DIRECTORY_SEPARATOR . $entry);
            }
        }
    }

}
