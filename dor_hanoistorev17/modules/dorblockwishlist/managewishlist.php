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

/* SSL Management */
$useSSL = true;

require_once(dirname(__FILE__).'/../../config/config.inc.php');
require_once(dirname(__FILE__).'/../../init.php');
require_once(dirname(__FILE__).'/DorWishList.php');
require_once(dirname(__FILE__).'/dorblockwishlist.php');
$context = Context::getContext();
if ($context->customer->isLogged())
{
	$action = Tools::getValue('action');
	$id_dorwishlist = (int)Tools::getValue('id_dorwishlist');
	$id_product = (int)Tools::getValue('id_product');
	$id_product_attribute = (int)Tools::getValue('id_product_attribute');
	$quantity = (int)Tools::getValue('quantity');
	$priority = Tools::getValue('priority');
	$wishlist = new DorWishList((int)($id_dorwishlist));
	$refresh = (($_GET['refresh'] == 'true') ? 1 : 0);
	if (empty($id_dorwishlist) === false)
	{
		if (!strcmp($action, 'update'))
		{
			DorWishList::updateProduct($id_dorwishlist, $id_product, $id_product_attribute, $priority, $quantity);
		}
		else
		{
			if (!strcmp($action, 'delete'))
				DorWishList::removeProduct($id_dorwishlist, (int)$context->customer->id, $id_product, $id_product_attribute);

			$products = DorWishList::getProductByIdCustomer($id_dorwishlist, $context->customer->id, $context->language->id);
			$bought = DorWishList::getBoughtProduct($id_dorwishlist);

			for ($i = 0; $i < sizeof($products); ++$i)
			{
				$obj = new Product((int)($products[$i]['id_product']), false, $context->language->id);
				if (!Validate::isLoadedObject($obj))
					continue;
				else
				{
					if ($products[$i]['id_product_attribute'] != 0)
					{
						$combination_imgs = $obj->getCombinationImages($context->language->id);
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
						$images = $obj->getImages($context->language->id);
						foreach ($images AS $k => $image)
							if ($image['cover'])
							{
								$products[$i]['cover'] = $obj->id.'-'.$image['id_image'];
								break;
							}
					}
					if (!isset($products[$i]['cover']))
						$products[$i]['cover'] = $context->language->iso_code.'-default';
				}
				$products[$i]['bought'] = false;
				for ($j = 0, $k = 0; $j < sizeof($bought); ++$j)
				{
					if ($bought[$j]['id_product'] == $products[$i]['id_product'] AND
						$bought[$j]['id_product_attribute'] == $products[$i]['id_product_attribute'])
						$products[$i]['bought'][$k++] = $bought[$j];
				}
			}

			$productBoughts = array();

			foreach ($products as $product)
				if (sizeof($product['bought']))
					$productBoughts[] = $product;
			$context->smarty->assign(array(
					'products' => $products,
					'productsBoughts' => $productBoughts,
					'id_dorwishlist' => $id_dorwishlist,
					'refresh' => $refresh,
					'token_wish' => $wishlist->token,
					'wishlists' => DorWishList::getByIdCustomer($cookie->id_customer)
				));

			// Instance of module class for translations
			$module = new DorBlockWishList();

			if (Tools::file_exists_cache(_PS_THEME_DIR_.'modules/dorblockwishlist/views/templates/front/managewishlist.tpl'))
				$context->smarty->display(_PS_THEME_DIR_.'modules/dorblockwishlist/views/templates/front/managewishlist.tpl');
			elseif (Tools::file_exists_cache(dirname(__FILE__).'/views/templates/front/managewishlist.tpl'))
				$context->smarty->display(dirname(__FILE__).'/views/templates/front/managewishlist.tpl');
			elseif (Tools::file_exists_cache(dirname(__FILE__).'/managewishlist.tpl'))
				$context->smarty->display(dirname(__FILE__).'/managewishlist.tpl');
			else
				echo $module->l('No template found', 'managewishlist');
		}
	}
}

