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

if (!defined('_PS_VERSION_'))
	exit;
if (!class_exists( 'DorImageBase' )) {     
    require_once (_PS_ROOT_DIR_.'/override/Dor/DorImageBase.php');
}

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;
use PrestaShop\PrestaShop\Adapter\Category\CategoryProductSearchProvider;
use PrestaShop\PrestaShop\Adapter\Image\ImageRetriever;
use PrestaShop\PrestaShop\Adapter\Product\PriceFormatter;
use PrestaShop\PrestaShop\Core\Product\ProductListingPresenter;
use PrestaShop\PrestaShop\Adapter\Product\ProductColorsRetriever;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchContext;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchQuery;
use PrestaShop\PrestaShop\Core\Product\Search\SortOrder;
use PrestaShop\PrestaShop\Core\Product\ProductPresenter;
class dor_productsamecategory extends Module
{
	protected $html;

	public function __construct()
	{
		$this->name = 'dor_productsamecategory';
		$this->version = '1.0.0';
		$this->author = 'Dorado Themes';
		$this->tab = 'front_office_features';
		$this->need_instance = 0;

		$this->bootstrap = true;
		parent::__construct();

		$this->displayName = Context::getContext()->getTranslator()->trans('Dor Products in the same category', array(), 'Modules.dor_productsamecategory');
		$this->description = Context::getContext()->getTranslator()->trans('Adds a block on the product page that displays products from the same category.', array(), 'Modules.dor_productsamecategory');
	}

	public function install()
	{
		// Install Tabs
        if(!(int)Tab::getIdFromClassName('AdminDorMenu')) {
            $parent_tab = new Tab();
            // Need a foreach for the language
            $parent_tab->name[$this->context->language->id] = Context::getContext()->getTranslator()->trans('Dor Extensions', array(), 'Modules.dor_productsamecategory');
            $parent_tab->class_name = 'AdminDorMenu';
            $parent_tab->id_parent = 0; // Home tab
            $parent_tab->module = $this->name;
            $parent_tab->add();
        }
        $tab = new Tab();
        foreach (Language::getLanguages() as $language)
        $tab->name[$language['id_lang']] = Context::getContext()->getTranslator()->trans('Dor Related Products', array(), 'Modules.dor_productsamecategory');
        $tab->class_name = 'AdminDorRelated';
        $tab->id_parent = (int)Tab::getIdFromClassName('AdminDorMenu'); 
        $tab->module = $this->name;
        $tab->add();

		Configuration::updateValue('DOR_PRODUCTSCATEGORY_DISPLAY_PRICE', 0);
		Configuration::updateValue($this->name . '_same_limit',9);
		Configuration::updateValue($this->name . '_same_per_page',4);
		Configuration::updateValue($this->name . '_same_quanlity_image',100);
        Configuration::updateValue($this->name . '_same_thumb_width',250);
        Configuration::updateValue($this->name . '_same_thumb_height',250);
		$this->_clearCache('dor_productsamecategory.tpl');

		return (parent::install()
			&& $this->registerHook('productfooter')
			&& $this->registerHook('header')
			&& $this->registerHook('addproduct')
			&& $this->registerHook('updateproduct')
			&& $this->registerHook('deleteproduct')
			&& $this->registerHook('dorRelatedProduct')
		);
	}

	public function uninstall()
	{
        $tab = new Tab((int) Tab::getIdFromClassName('AdminDorRelated'));
        $tab->delete();

		Configuration::deleteByName('DOR_PRODUCTSCATEGORY_DISPLAY_PRICE');
		Configuration::deleteByName($this->name . '_same_limit');
		Configuration::deleteByName($this->name . '_same_per_page');
		Configuration::deleteByName($this->name . '_same_quanlity_image');
        Configuration::deleteByName($this->name . '_same_thumb_width');
        Configuration::deleteByName($this->name . '_same_thumb_height');
		$this->_clearCache('dor_productsamecategory.tpl');

		return parent::uninstall();
	}
	private function _postProcess() {
        Configuration::updateValue($this->name . '_same_per_page', Tools::getValue('same_per_page'));
        Configuration::updateValue($this->name . '_same_limit', Tools::getValue('same_limit'));
        Configuration::updateValue($this->name . '_same_quanlity_image', Tools::getValue('same_quanlity_image'));
        Configuration::updateValue($this->name . '_same_thumb_width', Tools::getValue('same_thumb_width'));
        Configuration::updateValue($this->name . '_same_thumb_height', Tools::getValue('same_thumb_height'));
        $this->_html .= $this->displayConfirmation(Context::getContext()->getTranslator()->trans('Configuration updated'));
    }
	public function getContent()
	{
		$this->html = '';
		if (Tools::isSubmit('submitCross') &&
			Tools::getValue('DOR_PRODUCTSCATEGORY_DISPLAY_PRICE') != 0 &&
			Tools::getValue('DOR_PRODUCTSCATEGORY_DISPLAY_PRICE') != 1
		)
			$this->html .= $this->displayError('Invalid displayPrice.');
		elseif (Tools::isSubmit('submitCross'))
		{
			Configuration::updateValue(
				'DOR_PRODUCTSCATEGORY_DISPLAY_PRICE',
				Tools::getValue('DOR_PRODUCTSCATEGORY_DISPLAY_PRICE')
			);
			if (!sizeof($this->_postErrors))
	            $this->_postProcess();
	        else {
	            foreach ($this->_postErrors AS $err) {
	                $this->_html .= '<div class="alert error">' . $err . '</div>';
	            }
	        }
			$this->_clearCache('dor_productsamecategory.tpl');
			$this->html .= $this->displayConfirmation(Context::getContext()->getTranslator()->trans('Settings updated successfully.', array(), 'Modules.dor_productsamecategory'));
		}

		$this->html .= $this->renderForm();

		return $this->html;
	}

	protected function getCurrentProduct($products, $id_current)
	{
		if ($products)
		{
			foreach ($products as $key => $product)
			{
				if ($product['id_product'] == $id_current)
					return $key;
			}
		}

		return false;
	}

	public function hookProductFooter($params)
	{
		$same_per_page = Configuration::get($this->name.'_same_per_page');
		$same_limit = Configuration::get($this->name.'_same_limit');
		$quanlity_image = Configuration::get($this->name.'_same_quanlity_image');
        $thumbWidth = Configuration::get($this->name.'_same_thumb_width');
        $thumbHeight = Configuration::get($this->name.'_same_thumb_height');
		$id_product = (int)$params['product']['id'];
		$product = $params['product'];

		$cache_id = 'dor_productsamecategory|'.$id_product.'|'.(isset($params['category']->id_category) ? (int)$params['category']->id_category : (int)$product->id_category_default);

		if (!$this->isCached('dor_productsamecategory.tpl', $this->getCacheId($cache_id)))
		{
			$category = false;
			if (isset($params['category']->id_category))
				$category = $params['category'];
			else
			{
				if (isset($product->id_category_default) && $product->id_category_default > 1)
					$category = new Category((int)$product->id_category_default);
			}

			if (!Validate::isLoadedObject($category) || !$category->active)
				return false;

			// Get infos
			if($same_limit == 0 || $same_limit == "") $same_limit = 9;
			$category_products = $category->getProducts($this->context->language->id, 1, $same_limit); /* 100 products max. */
			$nb_category_products = (int)count($category_products);
			$middle_position = 0;
			// Remove current product from the list
			if (is_array($category_products) && count($category_products))
			{
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

		        foreach ($category_products as $rawProduct) {
		            $products_for_template[] = $presenter->present(
		                $presentationSettings,
		                $assembler->assembleProduct($rawProduct),
		                $this->context->language
		            );
		        }
				$productCategory = array();
				foreach ($products_for_template as $key => $category_product)
				{
					if ($category_product['id_product'] == $id_product)
					{
						unset($category_products[$key]);
						continue;
					}else{
						$id_image = Product::getCover($category_product['id_product']);
		                $images = "";
		                if (sizeof($id_image) > 0){
		                    $image = new Image($id_image['id_image']);
		                    // get image full URL
		                    $image_url = "/p/".$image->getExistingImgPath().".jpg";
		                    $linkRewrite = $category_product['id_product']."_".$id_image['id_image']."_".$category_product['link_rewrite'];
		                    $images = DorImageBase::renderThumbProduct($image_url,$linkRewrite,$thumbWidth,$thumbHeight,$quanlity_image);
		                }
		                $category_product['imageThumb'] = $images;
					}
					$productCategory[] = $category_product;
				}
				$category_products = $productCategory;
				$taxes = Product::getTaxCalculationMethod();
				if (Configuration::get('DOR_PRODUCTSCATEGORY_DISPLAY_PRICE'))
				{
					foreach ($category_products as $key => $category_product)
					{
						if ($category_product['id_product'] != $id_product)
						{
							if ($taxes == 0 || $taxes == 2)
							{
								$category_products[$key]['displayed_price'] = Product::getPriceStatic(
									(int)$category_product['id_product'],
									true,
									null,
									2
								);
							} elseif ($taxes == 1)
							{
								$category_products[$key]['displayed_price'] = Product::getPriceStatic(
									(int)$category_product['id_product'],
									false,
									null,
									2
								);
							}
						}
					}
				}

				// Get positions
				$middle_position = (int)round($nb_category_products / 2, 0);
				$product_position = $this->getCurrentProduct($category_products, (int)$id_product);

				// Flip middle product with current product
				if ($product_position)
				{
					$tmp = $category_products[$middle_position - 1];
					$category_products[$middle_position - 1] = $category_products[$product_position];
					$category_products[$product_position] = $tmp;
				}

				// If products tab higher than 30, slice it
				if ($nb_category_products > 30)
				{
					$category_products = array_slice($category_products, $middle_position - 15, 30, true);
					$middle_position = 15;
				}
			}
			$this->context->controller->addColorsToProductList($category_products);
			$cartUrl = $this->context->link->getPageLink('cart', true);
			// Display tpl
			$this->smarty->assign(
				array(
					'products' => $category_products,
					'cartUrl' => $cartUrl,
					'per_page' => $same_per_page,
					'middlePosition' => (int)$middle_position,
					'ProdDisplayPrice' => Configuration::get('DOR_PRODUCTSCATEGORY_DISPLAY_PRICE')
				)
			);
		}

		return $this->display(__FILE__, 'dor_productsamecategory.tpl', $this->getCacheId($cache_id));
	}

	public function hookHeader($params)
	{
		if (!isset($this->context->controller->php_self) || $this->context->controller->php_self != 'product')
			return;
		$this->context->controller->addCSS($this->_path.'css/dor_productsamecategory.css', 'all');
		$this->context->controller->addCSS($this->_path.'css/owl.carousel.css', 'all');
		$this->context->controller->addJS($this->_path.'js/dor_productsamecategory.js');
	}

	public function hookAddProduct($params)
	{
		if (!isset($params['product']))
			return;
		$id_product = (int)$params['product']->id;
		$product = $params['product'];

		$cache_id = 'dor_productsamecategory|'.$id_product.'|'.(isset($params['category']->id_category) ? (int)$params['category']->id_category : (int)$product->id_category_default);
		$this->_clearCache('dor_productsamecategory.tpl', $this->getCacheId($cache_id));
	}

	public function hookUpdateProduct($params)
	{
		if (!isset($params['product']))
			return;
		$id_product = (int)$params['product']->id;
		$product = $params['product'];

		$cache_id = 'dor_productsamecategory|'.$id_product.'|'.(isset($params['category']->id_category) ? (int)$params['category']->id_category : (int)$product->id_category_default);
		$this->_clearCache('dor_productsamecategory.tpl', $this->getCacheId($cache_id));
	}

	public function hookDeleteProduct($params)
	{
		if (!isset($params['product']))
			return;
		$id_product = (int)$params['product']->id;
		$product = $params['product'];

		$cache_id = 'dor_productsamecategory|'.$id_product.'|'.(isset($params['category']->id_category) ? (int)$params['category']->id_category : (int)$product->id_category_default);
		$this->_clearCache('dor_productsamecategory.tpl', $this->getCacheId($cache_id));
	}

	public function hookDorRelatedProduct($params)
	{
		return $this->hookProductFooter($params);
	}

	public function renderForm()
	{
		$fields_form = array(
			'form' => array(
				'legend' => array(
					'title' => Context::getContext()->getTranslator()->trans('Settings', array(), 'Modules.dor_productsamecategory'),
					'icon' => 'icon-cogs'
				),
				'input' => array(
					array(
						'type' => 'switch',
						'label' => Context::getContext()->getTranslator()->trans('Display products\' prices', array(), 'Modules.dor_productsamecategory'),
						'desc' => Context::getContext()->getTranslator()->trans('Show the prices of the products displayed in the block.', array(), 'Modules.dor_productsamecategory'),
						'name' => 'DOR_PRODUCTSCATEGORY_DISPLAY_PRICE',
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => 1,
								'label' => Context::getContext()->getTranslator()->trans('Enabled', array(), 'Modules.dor_productsamecategory')
							),
							array(
								'id' => 'active_off',
								'value' => 0,
								'label' => Context::getContext()->getTranslator()->trans('Disabled', array(), 'Modules.dor_productsamecategory')
							)
						),
					),
					array(
                        'type' => 'text',
                        'label' => 'Limit Product:',
                        'name' => 'same_limit',
                        'class' => 'fixed-width-md',
                    ),
					array(
                        'type' => 'text',
                        'label' => 'Product Per Page:',
                        'name' => 'same_per_page',
                        'class' => 'fixed-width-md',
                    ),
					array(
                        'type' => 'text',
                        'label' => 'Quanlity Image:',
                        'name' => 'same_quanlity_image',
                        'class' => 'fixed-width-md',
                    ),
                    array(
                        'type' => 'text',
                        'label' => 'Thumb width image:',
                        'name' => 'same_thumb_width',
                        'class' => 'fixed-width-md',
                    ),
                    array(
                        'type' => 'text',
                        'label' => 'Thumb height image:',
                        'name' => 'same_thumb_height',
                        'class' => 'fixed-width-md',
                    ),
				),
				'submit' => array(
					'title' => Context::getContext()->getTranslator()->trans('Save', array(), 'Modules.dor_productsamecategory'),
				)
			),
		);

		$helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table = $this->table;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get(
			'PS_BO_ALLOW_EMPLOYEE_FORM_LANG'
		) : 0;
		$helper->identifier = $this->identifier;
		$helper->submit_action = 'submitCross';
		$helper->currentIndex = $this->context->link->getAdminLink(
				'AdminModules',
				false
			).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => $this->getConfigFieldsValues(),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		);

		return $helper->generateForm(array($fields_form));
	}

	public function getConfigFieldsValues()
	{
		return array(
			'DOR_PRODUCTSCATEGORY_DISPLAY_PRICE' => Tools::getValue(
					'DOR_PRODUCTSCATEGORY_DISPLAY_PRICE',
					Configuration::get('DOR_PRODUCTSCATEGORY_DISPLAY_PRICE')
				),
			'same_per_page' => Tools::getValue('same_per_page', Configuration::get($this->name . '_same_per_page')),
			'same_quanlity_image' => Tools::getValue('same_quanlity_image', Configuration::get($this->name . '_same_quanlity_image')),
            'same_thumb_width' => Tools::getValue('same_thumb_width', Configuration::get($this->name . '_same_thumb_width')),
            'same_thumb_height' => Tools::getValue('same_thumb_height', Configuration::get($this->name . '_same_thumb_height')),
		);
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
}
