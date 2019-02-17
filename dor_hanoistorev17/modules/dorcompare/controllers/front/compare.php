<?php
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
if (!class_exists( 'DorImageBase' )) {     
    require_once (_PS_ROOT_DIR_.'/override/Dor/DorImageBase.php');
}
require_once(_PS_MODULE_DIR_ . 'dorcompare/models/CompareProduct.php');
class dorcompareCompareModuleFrontController extends ModuleFrontController
{
	public $ssl = true;

	public function __construct()
	{
		parent::__construct();
		$this->_staticModel = new CompareProduct();
		$this->context = Context::getContext();
		$this->nameModule = "dorcompare";
	}

	public function initContent()
	{
		parent::initContent();
		$this->context->smarty->assign(array());
		$this->context->smarty->assign(array('page_name'=>"dor-page-comparison"));
		$compares = "";

		if (Tools::getValue('ajax')) {
            return;
        }
        parent::initContent();

        //Clean compare product table
        $this->_staticModel->cleanCompareProducts('week');

        $hasProduct = false;
        $maxItem = Configuration::get($this->nameModule . '_maxitem');
        $thumbWidth = Configuration::get($this->nameModule . '_thumbwidth');
        $thumbHeight = Configuration::get($this->nameModule . '_thumbheight');
        $quanlity_image = Configuration::get($this->nameModule . '_quanlity');
        if (!$maxItem) {
            return Tools::redirect('index.php?controller=404');
        }
        $ids = null;
        if (($product_list = urldecode(Tools::getValue('compare_product_list'))) && ($postProducts = (isset($product_list) ? rtrim($product_list, '|') : ''))) {
            $ids = array_unique(explode('|', $postProducts));
        } elseif (isset($this->context->cookie->id_compare)) {
            $ids = $this->_staticModel->getCompareProducts($this->context->cookie->id_compare);
            if (count($ids)) {
            	Tools::redirect($this->context->link->getModuleLink('dorcompare', 'compare')."?compare_product_list=".implode('|', $ids));
            }
        }

        if ($ids) {
            if (count($ids) > 0) {
                if (count($ids) > $maxItem) {
                    $ids = array_slice($ids, 0, $maxItem);
                }

                $listProducts = array();
                $listFeatures = array();

                foreach ($ids as $k => &$id) {
                    $curProduct = new Product((int)$id, true, $this->context->language->id);
                    if (!Validate::isLoadedObject($curProduct) || !$curProduct->active || !$curProduct->isAssociatedToShop()) {
                        if (isset($this->context->cookie->id_compare)) {
                            $this->_staticModel->removeCompareProduct($this->context->cookie->id_compare, $id);
                        }
                        unset($ids[$k]);
                        continue;
                    }

                    foreach ($curProduct->getFrontFeatures($this->context->language->id) as $feature) {
                        $listFeatures[$curProduct->id][$feature['id_feature']] = $feature['value'];
                    }

                    $cover = Product::getCover((int)$id);

                    $curProduct->id_image = Tools::htmlentitiesUTF8(Product::defineProductImage(array('id_image' => $cover['id_image'], 'id_product' => $id), $this->context->language->id));
                    $curProduct->allow_oosp = Product::isAvailableWhenOutOfStock($curProduct->out_of_stock);
                    $listProducts[] = $curProduct;
                }

                if (count($listProducts) > 0) {
                    $width = 80 / count($listProducts);
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
		            foreach ($listProducts as $rawProduct) {
		            	if(isset($rawProduct->id) && $rawProduct->id > 0){
		            		$rawProduct->id_product = $rawProduct->id;

		            		$id_image = Product::getCover($rawProduct->id);
			                $images = "";
			                if (sizeof($id_image) > 0){
			                    $image = new Image($id_image['id_image']);
			                    // get image full URL
			                    $image_url = "/p/".$image->getExistingImgPath().".jpg";
			                    $linkRewrite = $rawProduct->id."_".$id_image['id_image']."_".$rawProduct->link_rewrite;
			                    $images = DorImageBase::renderThumbProduct($image_url,$linkRewrite,$thumbWidth,$thumbHeight,$quanlity_image);
			                    $rawProduct->imageThumb = $images;
			                }

			                $products_for_template[] = $presenter->present(
			                    $presentationSettings,
			                    $assembler->assembleProduct((array)$rawProduct),
			                    $this->context->language
			                ); 
		                }
		            }
                    $hasProduct = true;
                    $ordered_features = $this->getFeaturesForComparison($ids, $this->context->language->id);

                    $cartUrl = $this->context->link->getPageLink('cart', true);
        			$this->context->smarty->assign('static_token', Tools::getToken(false));
                    $this->context->smarty->assign(array(
                        'ordered_features' => $ordered_features,
                        'product_features' => $listFeatures,
                        'products' => $products_for_template,
                        'width' => $width,
                        'cartUrl' => $cartUrl,
                        'HOOK_COMPARE_EXTRA_INFORMATION' => Hook::exec('displayCompareExtraInformation', array('list_ids_product' => $ids)),
                        'HOOK_EXTRA_PRODUCT_COMPARISON' => Hook::exec('displayProductComparison', array('list_ids_product' => $ids))
                    ));
                } elseif (isset($this->context->cookie->id_compare)) {
                    $object = new CompareProduct((int)$this->context->cookie->id_compare);
                    if (Validate::isLoadedObject($object)) {
                        $object->delete();
                    }
                }
            }
        }
        $this->context->smarty->assign('hasProduct', $hasProduct);






		$this->context->smarty->assign( array(
                                            'page_name'=>"dor-page-comparison",
                                            'compares'=>$compares
                                            ));
		$this->setTemplate('compare/compare.tpl'); 
	}
	public static function getFeaturesForComparison($list_ids_product, $id_lang)
    {
        if (!Configuration::get('PS_FEATURE_FEATURE_ACTIVE')) {
            return false;
        }

        $ids = '';
        foreach ($list_ids_product as $id) {
            $ids .= (int)$id.',';
        }

        $ids = rtrim($ids, ',');

        if (empty($ids)) {
            return false;
        }

        return Db::getInstance()->executeS('
			SELECT f.*, fl.*
			FROM `'._DB_PREFIX_.'feature` f
			LEFT JOIN `'._DB_PREFIX_.'feature_product` fp
				ON f.`id_feature` = fp.`id_feature`
			LEFT JOIN `'._DB_PREFIX_.'feature_lang` fl
				ON f.`id_feature` = fl.`id_feature`
			WHERE fp.`id_product` IN ('.$ids.')
			AND `id_lang` = '.(int)$id_lang.'
			GROUP BY f.`id_feature`
			ORDER BY f.`position` ASC
		');
    }
    
	public function setMedia()
    {
        parent::setMedia();
        $this->addjQuery();
    }
}
