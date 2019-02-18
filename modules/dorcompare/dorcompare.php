<?php
if (!defined('_PS_VERSION_'))
	exit;
require_once(_PS_MODULE_DIR_ . 'dorcompare/models/CompareProduct.php');
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
class Dorcompare extends Module
{
	public function __construct()
	{
		$this->name = 'dorcompare';
		$this->tab = 'front_office_features';
		$this->version = '2.0.0';
		$this->author = 'Dorado Themes';
		$this->controllers = array('compare');
		$this->bootstrap = true;
		parent::__construct();
		$this->_staticModel = new CompareProduct();
		$this->displayName = $this->l('Dor Comparison');
		$this->description = $this->l('Dor Comparison Products');
		$this->confirmUninstall = $this->l('Are you sure about removing these details?');
	}

	public function install()
	{
        $res = $this->installDb();
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
        $tab->name[$language['id_lang']] = $this->l('Dor Comparison');
        $tab->class_name = 'AdminDorCompare';
        $tab->id_parent = (int)Tab::getIdFromClassName('AdminDorMenu'); 
        $tab->module = $this->name;
        $tab->add();
        $today = date("Y-m-d");
        Configuration::updateValue($this->name . '_maxitem',3);
        Configuration::updateValue($this->name . '_thumbwidth',450);
        Configuration::updateValue($this->name . '_thumbheight',450);
        Configuration::updateValue($this->name . '_quanlity',100);
        return parent::install() &&
                $this->registerHook('header')
                &&
                $this->registerHook('backOfficeHeader')
                &&
				$this->registerHook('dorCompare')
                && 
                $this->registerHook('dorCompareProductDetail')
                && 
                $this->registerHook('dorCompareCustom')
                && 
                $this->registerHook('leftColumn')
                && 
                $this->registerHook('rightColumn');
	}
	public function uninstall()
	{
        $res = $this->uninstallDb();
		$tab = new Tab((int) Tab::getIdFromClassName('AdminDorCompare'));
        $tab->delete();
        Configuration::deleteByName($this->name . '_maxitem');
        Configuration::deleteByName($this->name . '_thumbwidth');
        Configuration::deleteByName($this->name . '_thumbheight');
        Configuration::deleteByName($this->name . '_quanlity');
		if (!parent::uninstall() ||
            !$this->unregisterHook('header') ||
            !$this->unregisterHook('backOfficeHeader') ||
            !$this->unregisterHook('dorCompare') ||
            !$this->unregisterHook('dorCompareProductDetail') ||
            !$this->unregisterHook('dorCompareCustom') ||
            !$this->unregisterHook('leftColumn') ||
            !$this->unregisterHook('rightColumn')) {
            return false;
        }
        return true;
        return (bool)$res;
	}

    public function installDb(){
        $res = Db::getInstance()->execute(
            'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'dorcompare` (
              `id_compare` int(10) unsigned NOT NULL AUTO_INCREMENT,
              `id_customer` int(10) unsigned NOT NULL,
              PRIMARY KEY (`id_compare`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 '
        );
        if ($res)
            $res &= Db::getInstance()->execute(
                'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'dorcompare_product` (
                  `id_compare` int(11) unsigned NOT NULL,
                  `id_product` int(11) unsigned NOT NULL,
                  `date_add` datetime NOT NULL,
                  `date_upd` datetime NOT NULL,
                  PRIMARY KEY (`id_compare`,`id_product`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8');
        
        return (bool)$res;
    }

    private function uninstallDb() {
        Db::getInstance()->execute('DROP TABLE `'._DB_PREFIX_.'dorcompare`');
        Db::getInstance()->execute('DROP TABLE `'._DB_PREFIX_.'dorcompare_product`');
        return true;
    }

	public function hookHeader($params)
	{
		$this->context->controller->addCSS(($this->_path).'assets/css/dorproducts-comparison.css', 'all');
		$this->context->controller->addJS(($this->_path).'assets/js/dorproducts-comparison.js');

		$compared_products = array();
        if (isset($this->context->cookie->id_compare)) {
            $compared_products = $this->_staticModel->getCompareProducts($this->context->cookie->id_compare);
        }
        $maxitem = Configuration::get($this->name . '_maxitem');
        if($maxitem == "" || $maxitem == 0){
        	$maxitem = 3;
        }
        $linkModule = $this->context->link->getModuleLink('dorcompare', 'compare');
        Media::addJsDef(
            array(
                'DORCOMPARE' => array(
                    "compared_products"=>$compared_products,
                    "maxitem"=>(int)$maxitem,
                    "linkModule"=>$linkModule
                )
            )
        );


	}
	public function hookBackOfficeHeader()
	{
		if(!Tools::getIsset('configure') || Tools::getValue('configure') != $this->name)
			return;

		$this->context->controller->addCSS(array());
		$this->context->controller->addJquery();
		$this->context->controller->addJS(array());
	}
	public function hookDorCompare($params)
	{
		$product = $params['product'];
		$this->context->smarty->assign('product', $product);
		return $this->display(__FILE__, 'comparebutton.tpl', $this->getCacheId($product['id_product']));
	}
	private function _postProcess() {
        Configuration::updateValue($this->name . '_maxitem', Tools::getValue('maxitem'));
        Configuration::updateValue($this->name . '_thumbwidth', Tools::getValue('thumbwidth'));
        Configuration::updateValue($this->name . '_thumbheight', Tools::getValue('thumbheight'));
        Configuration::updateValue($this->name . '_quanlity', Tools::getValue('quanlity'));
        $this->_html .= $this->displayConfirmation($this->l('Configuration updated'));
    }
	public function getContent()
	{
		$this->_postErrors = isset($this->_postErrors)?$this->_postErrors:array();
		$this->_html = isset($this->_html)?$this->_html:"";
		$this->context->smarty->assign(array());
		$output = '<h2>' . $this->displayName . '</h2>';
        if (Tools::isSubmit('submitProductCompare')) {
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
        $id_shop = (int)Context::getContext()->shop->id;
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-cogs'
                ),
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => 'Limit Items:',
                        'name' => 'maxitem',
                        'class' => 'fixed-width-md',
                    ),
                    array(
                        'type' => 'text',
                        'label' => 'Thumb Width:',
                        'name' => 'thumbwidth',
                        'class' => 'fixed-width-md',
                    ),
                    array(
                        'type' => 'text',
                        'label' => 'Thumb Height:',
                        'name' => 'thumbheight',
                        'class' => 'fixed-width-md',
                    ),
                    array(
                        'type' => 'text',
                        'label' => 'Quanlity Image:',
                        'name' => 'quanlity',
                        'class' => 'fixed-width-md',
                    )

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
        $helper->submit_action = 'submitProductCompare';
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
            'maxitem' => Tools::getValue('maxitem', Configuration::get($this->name . '_maxitem')),
            'thumbwidth' => Tools::getValue('thumbwidth', Configuration::get($this->name . '_thumbwidth')),
            'thumbheight' => Tools::getValue('thumbheight', Configuration::get($this->name . '_thumbheight')),
            'quanlity' => Tools::getValue('quanlity', Configuration::get($this->name . '_quanlity')),
        );
    }



    public function hookleftColumn($params)
	{
		$listProducts = array();
		$hasProduct = false;
		if(isset($this->context->cookie->id_compare)){
			$maxItem = Configuration::get($this->name . '_maxitem');
			$ids = $this->_staticModel->getCompareProducts($this->context->cookie->id_compare);
			if ($ids) {
	            if (count($ids) > 0) {
	                if (count($ids) > $maxItem) {
	                    $ids = array_slice($ids, 0, $maxItem);
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

		            

	                foreach ($ids as $k => &$id) {
	                    $curProduct = new Product((int)$id, true, $this->context->language->id);
	                    if (!Validate::isLoadedObject($curProduct) || !$curProduct->active || !$curProduct->isAssociatedToShop()) {
	                        if (isset($this->context->cookie->id_compare)) {
	                            $this->_staticModel->removeCompareProduct($this->context->cookie->id_compare, $id);
	                        }
	                        unset($ids[$k]);
	                        continue;
	                    }


	                    $listProducts[] = $curProduct;
	                }

	                if (count($listProducts) > 0) {
	                    $hasProduct = true;
	                    $products_for_template = [];
	                    foreach ($listProducts as $key => $productItem) {
	                    	$productItem->id_product = $productItem->id;
	                    	$products_for_template[] = $presenter->present(
			                    $presentationSettings,
			                    $assembler->assembleProduct((array)$productItem),
			                    $this->context->language
			                ); 
	                    }
	                    $listProducts = $products_for_template;
	                }
	            }
	        }
		}
		$this->context->smarty->assign(array(
			'hasProduct' => $hasProduct,
            'compares' => $listProducts
        ));
		return $this->display(__FILE__, 'compareleft.tpl');
	}



	public function hookAjaxCall($params)
    {
        global $smarty, $cookie;
        // Add or remove product with Ajax
        if (Tools::getValue('ajax') && Tools::getValue('id_product') && Tools::getValue('action')) {
        	$maxItem = Configuration::get($this->name . '_maxitem');
            if (Tools::getValue('action') == 'add') {
                $id_compare = isset($this->context->cookie->id_compare) ? $this->context->cookie->id_compare: false;
                if ($this->_staticModel->getNumberProducts($id_compare) < $maxItem) {
                    $this->_staticModel->addCompareProduct($id_compare, (int)Tools::getValue('id_product'));
                } else {
                    echo 0;die;
                }
            } elseif (Tools::getValue('action') == 'remove') {
                if (isset($this->context->cookie->id_compare)) {
                    $this->_staticModel->removeCompareProduct((int)$this->context->cookie->id_compare, (int)Tools::getValue('id_product'));
                } else {
                    echo 0;die;
                }
            } else {
                echo 0;die;
            }
            echo 1;die;
        }
        echo 0;die;
    }
}
