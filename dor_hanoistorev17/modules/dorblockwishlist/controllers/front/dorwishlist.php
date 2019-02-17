<?php
/*
* 2007-2016 PrestaShop
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
*  @copyright  2007-2016 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

/**
 * @since 1.5.0
 */
/*if (!class_exists( 'DorImageBase' )) {     
    require_once (_PS_ROOT_DIR_.'/override/Dor/DorImageBase.php');
}*/
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
class DorBlockWishListDorWishListModuleFrontController extends ModuleFrontController
{
	public $ssl = true;

	public function __construct()
	{
		parent::__construct();
		$this->context = Context::getContext();
		include_once($this->module->getLocalPath().'DorWishList.php');
	}

	/**
	 * @see FrontController::initContent()
	 */
	public function initContent()
	{
		parent::initContent();
		$action = Tools::getValue('action');

		if (!Tools::isSubmit('myajax'))
			$this->assign();
		elseif (!empty($action) && method_exists($this, 'ajaxProcess'.Tools::toCamelCase($action)))
			$this->{'ajaxProcess'.Tools::toCamelCase($action)}();
		else
			die(Tools::jsonEncode(array('error' => 'method doesn\'t exist')));
	}

	/**
	 * Assign wishlist template
	 */
	public function assign()
	{
		$errors = array();
		if ($this->context->customer->isLogged())
		{
			$add = Tools::getIsset('add');
			$add = (empty($add) === false ? 1 : 0);
			$delete = Tools::getIsset('deleted');
			$delete = (empty($delete) === false ? 1 : 0);
			$default = Tools::getIsset('default');
			$default = (empty($default) === false ? 1 : 0);
			$id_dorwishlist = Tools::getValue('id_dorwishlist');
			if (Tools::isSubmit('submitWishlist'))
			{
				if (Configuration::get('PS_TOKEN_ACTIVATED') == 1 && strcmp(Tools::getToken(), Tools::getValue('token')))
					$errors[] = $this->module->l('Invalid token', 'mywishlist');
				if (!count($errors))
				{
					$name = Tools::getValue('name');
					if (empty($name))
						$errors[] = $this->module->l('You must specify a name.', 'mywishlist');
					if (DorWishList::isExistsByNameForUser($name))
						$errors[] = $this->module->l('This name is already used by another list.', 'mywishlist');

					if (!count($errors))
					{
						$wishlist = new DorWishList();
						$wishlist->id_shop = $this->context->shop->id;
						$wishlist->id_shop_group = $this->context->shop->id_shop_group;
						$wishlist->name = $name;
						$wishlist->id_customer = (int)$this->context->customer->id;
						!$wishlist->isDefault($wishlist->id_customer) ? $wishlist->default = 1 : '';
						list($us, $s) = explode(' ', microtime());
						srand($s * $us);
						$wishlist->token = strtoupper(substr(sha1(uniqid(rand(), true)._COOKIE_KEY_.$this->context->customer->id), 0, 16));
						$wishlist->add();
						Mail::Send(
							$this->context->language->id,
							'wishlink',
							Mail::l('Your wishlist\'s link', $this->context->language->id),
							array(
							'{wishlist}' => $wishlist->name,
							'{message}' => $this->context->link->getModuleLink('dorblockwishlist', 'view', array('token' => $wishlist->token))
							),
							$this->context->customer->email,
							$this->context->customer->firstname.' '.$this->context->customer->lastname,
							null,
							strval(Configuration::get('PS_SHOP_NAME')),
							null,
							null,
							$this->module->getLocalPath().'mails/');

						Tools::redirect($this->context->link->getModuleLink('dorblockwishlist', 'dorwishlist'));
					}
				}
			}
			else if ($add)
				DorWishList::addCardToWishlist($this->context->customer->id, Tools::getValue('id_dorwishlist'), $this->context->language->id);
			elseif ($delete && empty($id_dorwishlist) === false)
			{
				$wishlist = new DorWishList((int)$id_dorwishlist);
				if ($this->context->customer->isLogged() && $this->context->customer->id == $wishlist->id_customer && Validate::isLoadedObject($wishlist))
					$wishlist->delete();
				else
					$errors[] = $this->module->l('Cannot delete this wishlist', 'mywishlist');
			}
			elseif ($default)
			{
				$wishlist = new DorWishList((int)$id_dorwishlist);
				if ($this->context->customer->isLogged() && $this->context->customer->id == $wishlist->id_customer && Validate::isLoadedObject($wishlist))
					$wishlist->setDefault();
				else
					$errors[] = $this->module->l('Cannot delete this wishlist', 'mywishlist');
			}
			$customerWishlist = DorWishList::getByIdCustomer($this->context->customer->id);
			$products = DorWishList::getProductByIdCustomer((int)$id_dorwishlist, $this->context->customer->id, $this->context->language->id);
			for ($i = 0; $i < sizeof($products); ++$i)
			{
				$obj = new Product((int)($products[$i]['id_product']), false, $this->context->language->id);
				if (!Validate::isLoadedObject($obj))
					continue;
				else
				{
					if ($products[$i]['id_product_attribute'] != 0)
					{
						$combination_imgs = $obj->getCombinationImages($this->context->language->id);
						if (isset($combination_imgs[$products[$i]['id_product_attribute']][0]))
							$products[$i]['cover'] = $obj->id.'-'.$combination_imgs[$products[$i]['id_product_attribute']][0]['id_image'];
						else
						{
							$cover = Product::getCover($obj->id);
							$products[$i]['cover'] = $obj->id.'-'.$cover['id_image'];
						}
					}
					else
					{
						$images = $obj->getImages($this->context->language->id);
						foreach ($images AS $k => $image)
							if ($image['cover'])
							{
								$products[$i]['cover'] = $obj->id.'-'.$image['id_image'];
								break;
							}
					}
					if (!isset($products[$i]['cover']))
						$products[$i]['cover'] = $this->context->language->iso_code.'-default';
				}
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

            foreach ($products as $rawProduct) {
                $products_for_template[] = $presenter->present(
                    $presentationSettings,
                    $assembler->assembleProduct($rawProduct),
                    $this->context->language
                ); 
            }
			$this->context->smarty->assign('wishlists', DorWishList::getByIdCustomer($this->context->customer->id));
			$this->context->smarty->assign('products', $products_for_template);
			$this->context->smarty->assign('nbProducts', DorWishList::getInfosByIdCustomer($this->context->customer->id));
		}
		else
			Tools::redirect('index.php?controller=authentication&back='.urlencode($this->context->link->getModuleLink('dorblockwishlist', 'dorwishlist')));

		$this->context->smarty->assign(array(
			'id_customer' => (int)$this->context->customer->id,
			'errors' => $errors,
			'form_link' => $errors,
		));
		//$this->context->smarty->display(dirname(__FILE__).'/mywishlist.tpl');
		$this->setTemplate('dorwishlist/mywishlist.tpl');
	}

	public function ajaxProcessDeleteList()
	{
		if (!$this->context->customer->isLogged())
			die(Tools::jsonEncode(array('success' => false,
				'error' => $this->module->l('You aren\'t logged in', 'mywishlist'))));

		$default = Tools::getIsset('default');
		$default = (empty($default) === false ? 1 : 0);
		$id_dorwishlist = Tools::getValue('id_dorwishlist');

		$wishlist = new DorWishList((int)$id_dorwishlist);
		if (Validate::isLoadedObject($wishlist) && $wishlist->id_customer == $this->context->customer->id)
		{
			$default_change = $wishlist->default ? true : false;
			$id_customer = $wishlist->id_customer;
			$wishlist->delete();
		}
		else
			die(Tools::jsonEncode(array('success' => false,
				'error' => $this->module->l('Cannot delete this wishlist', 'mywishlist'))));

		if ($default_change)
		{
			$array = DorWishList::getDefault($id_customer);

			if (count($array))
				die(Tools::jsonEncode(array(
					'success' => true,
					'id_default' => $array[0]['id_dorwishlist']
					)));
		}

		die(Tools::jsonEncode(array('success' => true)));
	}

	public function ajaxProcessSetDefault()
	{
		if (!$this->context->customer->isLogged())
			die(Tools::jsonEncode(array('success' => false,
				'error' => $this->module->l('You aren\'t logged in', 'mywishlist'))));

		$default = Tools::getIsset('default');
		$default = (empty($default) === false ? 1 : 0);
		$id_dorwishlist = Tools::getValue('id_dorwishlist');

		if ($default)
		{
			$wishlist = new DorWishList((int)$id_dorwishlist);
			if (Validate::isLoadedObject($wishlist) && $wishlist->id_customer == $this->context->customer->id && $wishlist->setDefault())
				die(Tools::jsonEncode(array('success' => true)));
		}

		die(Tools::jsonEncode(array('error' => true)));
	}

	public function ajaxProcessProductChangeWishlist()
	{
		if (!$this->context->customer->isLogged())
			die(Tools::jsonEncode(array('success' => false,
				'error' => $this->module->l('You aren\'t logged in', 'mywishlist'))));

		$id_product = (int)Tools::getValue('id_product');
		$id_product_attribute = (int)Tools::getValue('id_product_attribute');
		$quantity = (int)Tools::getValue('quantity');
		$priority = (int)Tools::getValue('priority');
		$id_old_wishlist = (int)Tools::getValue('id_old_wishlist');
		$id_new_wishlist = (int)Tools::getValue('id_new_wishlist');
		$new_wishlist = new DorWishList((int)$id_new_wishlist);
		$old_wishlist = new DorWishList((int)$id_old_wishlist);

		//check the data is ok
		if (!$id_product || !is_int($id_product_attribute) || !$quantity ||
			!is_int($priority) || ($priority < 0 && $priority > 2) || !$id_old_wishlist || !$id_new_wishlist ||
			(Validate::isLoadedObject($new_wishlist) && $new_wishlist->id_customer != $this->context->customer->id) ||
			(Validate::isLoadedObject($old_wishlist) && $old_wishlist->id_customer != $this->context->customer->id))
			die(Tools::jsonEncode(array('success' => false, 'error' => $this->module->l('Error while moving product to another list', 'mywishlist'))));

		$res = true;
		$check = (int)Db::getInstance()->getValue('SELECT quantity FROM '._DB_PREFIX_.'wishlist_product
			WHERE `id_product` = '.$id_product.' AND `id_product_attribute` = '.$id_product_attribute.' AND `id_dorwishlist` = '.$id_new_wishlist);

		if ($check)
		{
			$res &= $old_wishlist->removeProduct($id_old_wishlist, $this->context->customer->id, $id_product, $id_product_attribute);
			$res &= $new_wishlist->updateProduct($id_new_wishlist, $id_product, $id_product_attribute, $priority, $quantity + $check);
		}
		else
		{
			$res &= $old_wishlist->removeProduct($id_old_wishlist, $this->context->customer->id, $id_product, $id_product_attribute);
			$res &= $new_wishlist->addProduct($id_new_wishlist, $this->context->customer->id, $id_product, $id_product_attribute, $quantity);
		}

		if (!$res)
			die(Tools::jsonEncode(array('success' => false, 'error' => $this->module->l('Error while moving product to another list', 'mywishlist'))));
		die(Tools::jsonEncode(array('success' => true, 'msg' => $this->module->l('The product has been correctly moved', 'mywishlist'))));
	}
}
