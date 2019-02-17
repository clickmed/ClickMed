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
if (!class_exists( 'DorCaches' )) {     
    require_once (_PS_ROOT_DIR_.'/override/Dor/Caches/DorCaches.php');
}
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
class Dor_dailydeals extends Module
{
	protected $_prefix = '';
	protected $_html = '';
	protected $_postErrors = array();

	protected static $cache_products;

	public function __construct()
	{
		$this->name = 'dor_dailydeals';
		$this->tab = 'pricing_promotion';
		$this->version = '1.0.0';
		$this->author = 'Dorado Themes';
		$this->need_instance = 0;
		$this->_prefix = 'dorpcount_';
		$this->bootstrap = true;
		parent::__construct();

		$this->displayName = Context::getContext()->getTranslator()->trans('Dor Daily Deals', array(), 'Modules.Dor_dailydeals');
		$this->description = Context::getContext()->getTranslator()->trans('Adds a block displaying your current discounted products with countdown timer.', array(), 'Modules.Dor_dailydeals');
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
        $tab->name[$language['id_lang']] = $this->l('Dor Daily Deals');
        $tab->class_name = 'AdminDorDailyDeal';
        $tab->id_parent = (int)Tab::getIdFromClassName('AdminDorMenu'); 
        $tab->module = $this->name;
        $tab->add();

		$this->_clearCache('*');
		Configuration::updateValue($this->name . '_typeData', 0);
		Configuration::updateValue($this->name . '_nbr', 8);
		Configuration::updateValue($this->name . '_columns', 1);
		Configuration::updateValue($this->name . '_dealProductIDs', "");
		Configuration::updateValue($this->name . '_daily_deal_startdate', "");
		Configuration::updateValue($this->name . '_daily_deal_enddate', "");
		Configuration::updateValue($this->name . '_deal_content_custom', "");
		Configuration::updateValue($this->name . '_deal_content_html', "");
		Configuration::updateValue($this->name . '_deal_bg_image', "");
		Configuration::updateValue($this->name . '_deal_quanlity_image', 90);
		Configuration::updateValue($this->name . '_deal_thumb_width', 300);
		Configuration::updateValue($this->name . '_deal_thumb_height', 400);
		$success = parent::install()
			&& $this->registerHook('header')
			&& $this->registerHook('DisplayBackOfficeHeader')
			&& $this->registerHook('addproduct')
			&& $this->registerHook('updateproduct')
			&& $this->registerHook('deleteproduct')
			&& $this->registerHook('dorDailyDeal');

		return $success;
	}

	public function uninstall()
	{
		$this->_clearCache('*');
		$tab = new Tab((int) Tab::getIdFromClassName('AdminDorDailyDeal'));
        $tab->delete();
		Configuration::deleteByName($this->name . '_typeData', 0);
		Configuration::deleteByName($this->name . '_nbr', 8);
		Configuration::deleteByName($this->name . '_columns', 1);
		Configuration::deleteByName($this->name . '_dealProductIDs', "");
		Configuration::deleteByName($this->name . '_daily_deal_startdate', "");
		Configuration::deleteByName($this->name . '_daily_deal_enddate', "");
		Configuration::deleteByName($this->name . '_deal_content_custom', "");
		Configuration::deleteByName($this->name . '_deal_content_html', "");
		Configuration::deleteByName($this->name . '_deal_bg_image', "");
		Configuration::deleteByName($this->name . '_deal_quanlity_image', 90);
		Configuration::deleteByName($this->name . '_deal_thumb_width', 300);
		Configuration::deleteByName($this->name . '_deal_thumb_height', 400);
		return parent::uninstall();
	}
	private function _postProcess() {
        Configuration::updateValue($this->name . '_typeData', Tools::getValue('typeData'));
        Configuration::updateValue($this->name . '_nbr', Tools::getValue('nbr'));
        Configuration::updateValue($this->name . '_columns', Tools::getValue('columns'));
        Configuration::updateValue($this->name . '_dealProductIDs', Tools::getValue('dealProductIDs'));
        Configuration::updateValue($this->name . '_daily_deal_startdate', Tools::getValue('daily_deal_startdate'));
        Configuration::updateValue($this->name . '_daily_deal_enddate', Tools::getValue('daily_deal_enddate'));
        Configuration::updateValue($this->name . '_deal_content_custom', Tools::getValue('deal_content_custom'));
        Configuration::updateValue($this->name . '_deal_content_html', Tools::getValue('deal_content_html'));
        Configuration::updateValue($this->name . '_deal_bg_image', Tools::getValue('deal_bg_image'));
        Configuration::updateValue($this->name . '_deal_quanlity_image', Tools::getValue('deal_quanlity_image'));
        Configuration::updateValue($this->name . '_deal_thumb_width', Tools::getValue('deal_thumb_width'));
        Configuration::updateValue($this->name . '_deal_thumb_height', Tools::getValue('deal_thumb_height'));
        $this->_html .= $this->displayConfirmation($this->l('Configuration updated'));
    }
	public function getContent()
	{
		$output = '';
		if (Tools::isSubmit('submitUpdate'))
		{
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

	public function _displayForm()
	{

		$typeData = array(
            array( 'id'=>'0','mode'=>'Data'),
            array('id'=>'1','mode'=>'Custom Html'),
        );
		$id_lang = (int)Context::getContext()->language->id;
		$languages = $this->context->controller->getLanguages();
		$default_language = (int)Configuration::get('PS_LANG_DEFAULT');
		$fields_form = array(
			'form' => array(
				'legend' => array(
					'title' => $this->l('Settings'),
					'icon' => 'icon-cogs'
				),
				'input' => array(
					array(
                        'type' => 'select',
                        'label' => 'Type Data: ',
                        'name' => 'typeData',
                        'options' => array(
                            'query' => $typeData,
                            'id' => 'id',
                            'name'=>'mode',
                        ),
                    ),
					array(
						'type' => 'text',
						'label' => Context::getContext()->getTranslator()->trans('Products to display', array(), 'Modules.Dor_dailydeals'),
						'name' => 'nbr',
						'class' => 'dor-data fixed-width-xs',
						'desc' => Context::getContext()->getTranslator()->trans('Define the number of products to be displayed in this block on home page.', array(), 'Modules.Dor_dailydeals'),
						'default' => 8
					),
					array(
						'type' => 'text',
						'label' => Context::getContext()->getTranslator()->trans('Number Columns', array(), 'Modules.Dor_dailydeals'),
						'name' => 'columns',
						'class' => 'dor-data fixed-width-xs',
						'desc' => Context::getContext()->getTranslator()->trans('Define the number of columns to be displayed products.', array(), 'Modules.Dor_dailydeals'),
						'default' => 4
					),
					
                    array(
                       'type' => 'text',
                       'label' => Context::getContext()->getTranslator()->trans('Daily Deals StartDate Set:', array(), 'Modules.Dor_dailydeals'),
                       'name' => 'daily_deal_startdate',
                       'class' => 'dor-daily-custom',
                    ),
                    array(
                       'type' => 'text',
                       'label' => Context::getContext()->getTranslator()->trans('Daily Deals EndDate Set:', array(), 'Modules.Dor_dailydeals'),
                       'name' => 'daily_deal_enddate',
                       'class' => 'dor-daily-custom',
                    ),
                    array(
                        'type' => 'text',
                        'label' => Context::getContext()->getTranslator()->trans('Only Product IDs:', array(), 'Modules.Dor_dailydeals'),
                        'name' => 'dealProductIDs',
                        'desc' => Context::getContext()->getTranslator()->trans('When input value "1,2,3..." then only display product selected.', array(), 'Modules.Dor_dailydeals'),
                        'class' => 'dor-data fixed-width-md',
                    ),
                    array(
                        'type' => 'textarea',
                        'label' => Context::getContext()->getTranslator()->trans('Custom Html Content:', array(), 'Modules.Dor_dailydeals'),
                        'name' => 'deal_content_html',
                        'class' => 'fixed-width-full dor-daily-custom',
                        'cols' => 60,
                        'rows' => 10,
                        'desc' => Context::getContext()->getTranslator()->trans('Format: "title=>Title Content, caption=>Caption Content, desc=>Description Content, price=>$26"', array(), 'Modules.Dor_dailydeals'),
                    ),
	                array(
                        'type' => 'textarea',
                        'label' => Context::getContext()->getTranslator()->trans('Custom Text Content:', array(), 'Modules.Dor_dailydeals'),
                        'name' => 'deal_content_custom',
                        'class' => 'fixed-width-full dor-daily-custom',
                        'cols' => 60,
                        'rows' => 10,
                    ),
					array(
                        'type' => 'text',
                        'label' => Context::getContext()->getTranslator()->trans('Background Image:', array(), 'Modules.Dor_dailydeals'),
                        'name' => 'deal_bg_image',
                        'class' => 'fixed-width-md dor-daily-custom',
                        'default' => ""
                    ),
					array(
                        'type' => 'text',
                        'label' => Context::getContext()->getTranslator()->trans('Quanlity Image:', array(), 'Modules.Dor_dailydeals'),
                        'name' => 'deal_quanlity_image',
                        'class' => 'fixed-width-md',
                        'default' => 90
                    ),
                    array(
                        'type' => 'text',
                        'label' => Context::getContext()->getTranslator()->trans('Thumb width image:', array(), 'Modules.Dor_dailydeals'),
                        'name' => 'deal_thumb_width',
                        'class' => 'fixed-width-md',
                        'default' => 300
                    ),
                    array(
                        'type' => 'text',
                        'label' => Context::getContext()->getTranslator()->trans('Thumb height image:', array(), 'Modules.Dor_dailydeals'),
                        'name' => 'deal_thumb_height',
                        'class' => 'fixed-width-md',
                        'default' => 400
                    ),
				),
				'submit' => array(
					'title' => Context::getContext()->getTranslator()->trans('Save'),
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
        $helper->submit_action = 'submitUpdate';
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
            'typeData' =>     Tools::getValue('typeData', Configuration::get($this->name . '_typeData')),
            'nbr' =>     Tools::getValue('nbr', Configuration::get($this->name . '_nbr')),
            'columns' =>     Tools::getValue('columns', Configuration::get($this->name . '_columns')),
            'dealProductIDs' =>   Tools::getValue('dealProductIDs', Configuration::get($this->name . '_dealProductIDs')),
            'daily_deal_startdate' =>   Tools::getValue('daily_deal_startdate', Configuration::get($this->name . '_daily_deal_startdate')),
            'daily_deal_enddate' =>   Tools::getValue('daily_deal_enddate', Configuration::get($this->name . '_daily_deal_enddate')),
            'deal_content_custom' =>     Tools::getValue('deal_content_custom', Configuration::get($this->name . '_deal_content_custom')),
            'deal_content_html' =>     Tools::getValue('deal_content_html', Configuration::get($this->name . '_deal_content_html')),
            'deal_bg_image' =>     Tools::getValue('deal_bg_image', Configuration::get($this->name . '_deal_bg_image')),
            'deal_quanlity_image' =>     Tools::getValue('deal_quanlity_image', Configuration::get($this->name . '_deal_quanlity_image')),
            'deal_thumb_width' =>    Tools::getValue('deal_thumb_width', Configuration::get($this->name . '_deal_thumb_width')),
            'deal_thumb_height' =>    Tools::getValue('deal_thumb_height', Configuration::get($this->name . '_deal_thumb_height')),
        );
    }
    public function FilterProductSales($productID)
    {
        $sql = 'SELECT SUM(od.product_quantity) totalSales
							FROM '._DB_PREFIX_.'order_detail od WHERE od.product_id = '.$productID.' GROUP BY od.product_id';
        $result = Db::getInstance()->getValue($sql);
        return $result;
    }
    public function DorCacheDaily($fileCache, $id_lang){
    	/****Dor Caches****/
        $cusDatas = array();
    	$id_shop = (int)Context::getContext()->shop->id;
    	$typeData = Configuration::get($this->name . '_typeData');
        $dorTimeCache  = Configuration::get('dor_themeoptions_dorTimeCache',Configuration::get('dorTimeCache'));
        $dorTimeCache = $dorTimeCache?$dorTimeCache:86400;
        $cusDatas = array();
        $id_shop = (int)Context::getContext()->shop->id;
        $id_lang = (int)Context::getContext()->language->id;
        $fileCache = $fileCache."-Shop".$id_shop."-".$id_lang."-".$typeData;
        $dorCaches  = Configuration::get('dor_themeoptions_enableDorCache',Configuration::get('enableDorCache'));
        $cacheData = array();
        if($dorCaches){
            $objCache = new DorCaches(array('name'=>'default','path'=>_PS_ROOT_DIR_.'/override/Dor/Caches/smartcaches/dailydeals/','extension'=>'.cache'));
            $objCache->setCache($fileCache);
            $cacheData = $objCache->renderData($fileCache);
        }
        $productsData = array();
        if($cacheData && $dorCaches){
                $productsData = $cacheData['lists'];
        }else{
        	$nbr = Configuration::get($this->name . '_nbr');
        	$typeData = Configuration::get($this->name . '_typeData');
			$quanlity_image = Configuration::get($this->name . '_deal_quanlity_image');
			$thumbWidth = Configuration::get($this->name . '_deal_thumb_width');
			$thumbHeight = Configuration::get($this->name . '_deal_thumb_height');
			$dealProductIDs = Configuration::get($this->name . '_dealProductIDs');
			$columns = Configuration::get($this->name . '_columns');
			$deal_content_custom = Configuration::get($this->name . '_deal_content_custom');
        	if($typeData == 0){
        		if(trim($dealProductIDs) == ""){
					$productsData = Product::getPricesDrop($id_lang, 0, $nbr, $count = false, $order_by = "date_add", $order_way = "DESC");
				}else{
					$dealProductIDs = explode(",", $dealProductIDs);

					$productsData = $this->getPricesDrop($dealProductIDs,$id_lang, 0, $nbr, $count = false, $order_by = "date_add", $order_way = "DESC");
				}
        	}else{
				$productsData =  $this->getProductByIds($dealProductIDs, (int) Context::getContext()->language->id, $page=0, (isset($nb) ? $nb : 10), null,  null);
            }

			$products = array();
			if($deal_content_custom != ""){
				$contentCustom = explode(PHP_EOL, $deal_content_custom);
				
				foreach ($contentCustom as $k => $content) {
					$customs = explode("=>", $content);
					$idp = isset($customs[0]) && $customs[0] != ""?$customs[0]:0;
					$images = isset($customs[1])?$customs[1]:"";
					if($images != ""){
						$image = json_decode($images);
						$image1 = isset($image->image1) && $image->image1 != ""?$image->image1:"";
						$image2 = isset($image->image2) && $image->image2 != ""?$image->image2:"";
					}
					if($idp > 0 && $images != ""){
						$cusDatas[$idp]['image1'] = isset($image1)?$image1:"";
						$cusDatas[$idp]['image2'] = isset($image2)?$image2:"";
					}
					
				}
			}

			$fullUrl = _PS_BASE_URL_.__PS_BASE_URI__;
            if(is_array($productsData)){
    			if($typeData == 0){
    				foreach ($productsData as &$product) {
    					$proAccept = 0;
    					$totalSales = $this->FilterProductSales($product['id_product']);
                        $product['add_to_cart_url'] = $this->getAddToCartURL($product);
    					$product['totalSales'] = $totalSales;
    					if(!empty($dealProductIDs) && count($dealProductIDs) > 0 && !in_array($product['id_product'], $dealProductIDs)){
    						$proAccept = 1;
    					}
    					if ($product['specific_prices']['to'] != '0000-00-00 00:00:00' && $proAccept == 0) {
    						$time = strtotime($product['specific_prices']['to']);
    						$product['special_finish_time'] = date('m', $time).'/'.date('d', $time).'/'.date('Y', $time).' '.date('H', $time).':'.date('i', $time).':'.date('s', $time);
    						$idProduct = $product['id_product'];
    						if(count($cusDatas) > 0 && isset($cusDatas[$idProduct])){
    							$images = $cusDatas[$idProduct];
    							$product['image1'] = $fullUrl.$images['image1'];
    							$product['image2'] = $fullUrl.$images['image2'];
    						}else{
    							$id_image = Product::getCover($product['id_product']);
                                $images = "";
                                if (sizeof($id_image) > 0){
                                    $image = new Image($id_image['id_image']);
                                    // get image full URL
                                    $image_url = "/p/".$image->getExistingImgPath().".jpg";
                                    $linkRewrite = $product['id_product']."_".$id_image['id_image']."_".$product['link_rewrite'];
                                    $images = DorImageBase::renderThumbProduct($image_url,$linkRewrite,$thumbWidth,$thumbHeight,$quanlity_image);
                                    $product['imageThumb'] = $images;
                                }
    		                    
    	                    }
    	                    $products[] = $product;
    					}
    				}
    			}else{

    				foreach ($productsData as &$product) {
    					$idProduct = $product['id_product'];
                        $product['add_to_cart_url'] = $this->getAddToCartURL($product);
    					if(isset($product['specific_prices']) && $product['specific_prices'] != "" && $product['specific_prices']['to'] != '0000-00-00 00:00:00'){
    						$time = strtotime($product['specific_prices']['to']);
    						$product['special_finish_time'] = date('m', $time).'/'.date('d', $time).'/'.date('Y', $time).' '.date('H', $time).':'.date('i', $time).':'.date('s', $time);
    					}else{
    						$product['special_finish_time'] = "";
    					}
    					if(count($cusDatas) > 0 && isset($cusDatas[$idProduct])){
    						$images = $cusDatas[$idProduct];
    						$product['image1'] = $fullUrl.$images['image1'];
    						$product['image2'] = $fullUrl.$images['image2'];
    					}else{
    						$id_image = Product::getCover($product['id_product']);
                            $images = "";
                            if (sizeof($id_image) > 0){
                                $image = new Image($id_image['id_image']);
                                // get image full URL
                                $image_url = "/p/".$image->getExistingImgPath().".jpg";
                                $linkRewrite = $product['id_product']."_".$id_image['id_image']."_".$product['link_rewrite'];
                                $images = DorImageBase::renderThumbProduct($image_url,$linkRewrite,$thumbWidth,$thumbHeight,$quanlity_image);
                                $product['imageThumb'] = $images;
                            }
    	                    
                        }
                        $products[] = $product;
    				}
    			}
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

            foreach ($products as $rawProduct) {
                $products_for_template[] = $presenter->present(
                    $presentationSettings,
                    $assembler->assembleProduct($rawProduct),
                    $this->context->language
                ); 
            }


            $productsData = $products_for_template;
            $cartUrl = $this->context->link->getPageLink('cart', true);
            $this->context->smarty->assign('cartUrl', $cartUrl);
			if($dorCaches){
				$data['lists'] = $productsData;
	            $objCache->store($fileCache, $data, $expiration = TIME_CACHE_HOME);
        	}
        }
        return $productsData;
    }
    private function getAddToCartURL(array $product)
    {
        return $this->context->link->getAddToCartURL(
            $product['id_product'],
            $product['id_product_attribute']
        );
    }
	public function hookDorDailyDeal($params)
	{
        $id_shop = (int)Context::getContext()->shop->id;
        $id_lang = (int)Context::getContext()->language->id;
        $typeData = Configuration::get($this->name . '_typeData');
		$deal_startdate = Configuration::get($this->name . '_daily_deal_startdate');
		$deal_enddate = Configuration::get($this->name . '_daily_deal_enddate');
		$deal_bg_image = Configuration::get($this->name . '_deal_bg_image');
		$deal_content_html = Configuration::get($this->name . '_deal_content_html');
        $dealProductIDs = Configuration::get($this->name . '_dealProductIDs');
		/*$startDate = date('m', $deal_startdate).'/'.date('d', $deal_startdate).'/'.date('Y', $deal_startdate).' '.date('H', $deal_startdate).':'.date('i', $deal_startdate).':'.date('s', $deal_startdate);
        $endDate = date('m', $deal_enddate).'/'.date('d', $deal_enddate).'/'.date('Y', $deal_enddate).' '.date('H', $deal_enddate).':'.date('i', $deal_enddate).':'.date('s', $deal_enddate);*/
		$startDate = $deal_startdate;
		$endDate = $deal_enddate;
		if (!$this->isCached('dor_dailydeals.tpl', $this->getCacheId('Dor_dailydeals'))) {
			$fileCache = 'HomeDailyDealsCaches';
			$id_lang = (int)$params['cookie']->id_lang;
			$products = $this->DorCacheDaily($fileCache,$id_lang);
			Dor_dailydeals::$cache_products = $products;
		}

		if (Dor_dailydeals::$cache_products === false)
			return false;

		$product_path = dirname(__FILE__).'/views/templates/hook/product.tpl';
		$tproduct_path = _PS_ALL_THEMES_DIR_._THEME_NAME_.'/modules/'.$this->name.'/views/templates/hook/product.tpl';
		if (file_exists($tproduct_path))
			$product_path = $tproduct_path;
		$cusHtml = array();
		if($deal_content_html != ""){
			$contentHtml = explode(PHP_EOL, $deal_content_html);

            foreach ($contentHtml as $key => $content) {
                $contents = explode("=>", $content);
                if(isset($contents[0]) && $contents[0] == "title"){
                    $products[0]['name'] = $contents[1];
                }
                if(isset($contents[0]) && $contents[0] == "image"){
                    $products[0]['thumb_image'] = $contents[1];
                }
                if(isset($contents[0]) && $contents[0] == "desc"){
                    $products[0]['description_short'] = $contents[1];
                }
                if(isset($contents[0]) && $contents[0] == "price"){
                    $products[0]['price_custom'] = $contents[1];
                }
            }
		}
        
        $exDate = "";
        if(count($products) > 0){
            $exDate = $products[0]['special_finish_time'];
        }
        if(isset($endDate) && $endDate != ""){
            $exDate = $endDate;
        }

        Dor_dailydeals::$cache_products =  $products;
        $cartUrl = $this->context->link->getPageLink('cart', true);
        $this->context->smarty->assign('cartUrl', $cartUrl);
		$this->smarty->assign(array(
			'products' => Dor_dailydeals::$cache_products,
			'startDate' => $startDate,
			'endDate' => $exDate,
			'typeData' => $typeData,
			'product_path' => $product_path
		));
        if($typeData == 1){
            return $this->display(__FILE__, 'dor_dailydeals_custom.tpl', $this->getCacheId('Dor_dailydeals-'.$id_shop."-".$id_lang));
        }else{
            return $this->display(__FILE__, 'dor_dailydeals.tpl', $this->getCacheId('Dor_dailydeals-'.$id_shop."-".$id_lang));
        }
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
    public static function getPricesDrop($productIds, $id_lang, $page_number = 0, $nb_products = 10, $count = false,
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
        
        $tab_id_product = $productIds;
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

        if (strpos($order_by, '.') > 0) {
            $order_by = explode('.', $order_by);
            $order_by = pSQL($order_by[0]).'.`'.pSQL($order_by[1]).'`';
        }

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
		AND product_shop.`show_price` = 1
		'.($front ? ' AND p.`visibility` IN ("both", "catalog")' : '').'
		'.((!$beginning && !$ending) ? ' AND p.`id_product` IN ('.((is_array($tab_id_product) && count($tab_id_product)) ? implode(', ', $tab_id_product) : 0).')' : '').'
		'.$sql_groups.'
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
	public function _getProductIdByDate($beginning, $ending, Context $context = null, $with_combination = false)
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
	public function getProductByIds($idproducts, $id_lang, $page_number = 0, $nb_products = 10, $order_by = null, $order_way = null)
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
        
        if($idproducts && $idproducts != ""){
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
            if ($final_order_by == 'price')
                Tools::orderbyPrice($result, $order_way);
            if (!$result)
                return false;
            return Product::getProductsProperties($id_lang, $result);
        }else{
            return false;
        }
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

	public function hookHeader($params)
	{
        if (!isset($this->context->controller->php_self) || $this->context->controller->php_self != 'index')
            return;
		$this->context->controller->addCSS(($this->_path).'views/css/dor_dailydeals.css', 'all');
		$this->context->controller->addJS(($this->_path).'views/js/countdown.js');
		
	}
	public function hookDisplayBackOfficeHeader()
    {
        $this->context->controller->addCSS($this->_path . 'views/css/dor_dailydeals_admin.css');
        $this->context->controller->addJS($this->_path . 'views/js/dor_dailydeals_admin.js');
    }
	public function hookAddProduct($params)
	{
		$this->_clearCache('*');
	}

	public function hookUpdateProduct($params)
	{
		$this->_clearCache('*');
	}

	public function hookDeleteProduct($params)
	{
		$this->_clearCache('*');
	}

	public function getConfigValue($key)
    {
      return Configuration::get($this->getName($key));
    }

    public function getName($name) {
		return Tools::strtoupper($this->_prefix.$name);
	}

	protected function getCacheId($name = null)
	{
		if ($name === null) {
			$name = 'Dor_dailydeals';
		}
		return parent::getCacheId($name.'|'.date('Ymd'));
	}

	public function _clearCache($template, $cache_id = null, $compile_id = null)
	{
		parent::_clearCache('dor_dailydeals.tpl');
	}
}
