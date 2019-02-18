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

if (!defined('_PS_VERSION_'))
	exit;

class DorWishList extends ObjectModel
{
	/** @var integer Wishlist ID */
	public $id;

	/** @var integer Customer ID */
	public $id_customer;

	/** @var integer Token */
	public $token;

	/** @var integer Name */
	public $name;

	/** @var string Object creation date */
	public $date_add;

	/** @var string Object last modification date */
	public $date_upd;

	/** @var string Object last modification date */
	public $id_shop;

	/** @var string Object last modification date */
	public $id_shop_group;

	/** @var integer default */
	public $default;

	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table' => 'dorwishlist',
		'primary' => 'id_dorwishlist',
		'fields' => array(
			'id_customer' =>	array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
			'token' =>			array('type' => self::TYPE_STRING, 'validate' => 'isMessage', 'required' => true),
			'name' =>			array('type' => self::TYPE_STRING, 'validate' => 'isMessage', 'required' => true),
			'date_add' =>		array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
			'date_upd' =>		array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
			'id_shop' =>		array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
			'id_shop_group' =>	array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
			'default' => array('type' => self::TYPE_BOOL, 'validate' => 'isUnsignedId'),
		)
	);

	public function delete()
	{
		Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'dorwishlist_email` WHERE `id_dorwishlist` = '.(int)($this->id));
		Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'dorwishlist_product` WHERE `id_dorwishlist` = '.(int)($this->id));
		if ($this->default)
		{
			$result = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'dorwishlist` WHERE `id_customer` = '.(int)$this->id_customer.' AND `id_dorwishlist` != '.(int)$this->id.' LIMIT 1');
			foreach ($result as $res)
				Db::getInstance()->update('wishlist', array('default' => '1'), 'id_dorwishlist = '.(int)$res['id_dorwishlist']);
		}
		if (isset($this->context->cookie->id_dorwishlist))
			unset($this->context->cookie->id_dorwishlist);

		return (parent::delete());
	}

	/**
	 * Increment counter
	 *
	 * @return boolean succeed
	 */
	public static function incCounter($id_dorwishlist)
	{
		if (!Validate::isUnsignedId($id_dorwishlist))
			die (Tools::displayError());
		$result = Db::getInstance()->getRow('
			SELECT `counter`
			FROM `'._DB_PREFIX_.'dorwishlist`
			WHERE `id_dorwishlist` = '.(int)$id_dorwishlist
		);
		if ($result == false || !count($result) || empty($result) === true)
			return (false);

		return Db::getInstance()->execute('
			UPDATE `'._DB_PREFIX_.'dorwishlist` SET
			`counter` = '.(int)($result['counter'] + 1).'
			WHERE `id_dorwishlist` = '.(int)$id_dorwishlist
		);
	}


	public static function isExistsByNameForUser($name)
	{
		if (Shop::getContextShopID())
			$shop_restriction = 'AND id_shop = '.(int)Shop::getContextShopID();
		elseif (Shop::getContextShopGroupID())
			$shop_restriction = 'AND id_shop_group = '.(int)Shop::getContextShopGroupID();
		else
			$shop_restriction = '';

		$context = Context::getContext();
		return Db::getInstance()->getValue('
			SELECT COUNT(*) AS total
			FROM `'._DB_PREFIX_.'dorwishlist`
			WHERE `name` = \''.pSQL($name).'\'
				AND `id_customer` = '.(int)$context->customer->id.'
				'.$shop_restriction
		);
	}

	/**
	 * Return true if wishlist exists else false
	 *
	 *  @return boolean exists
	 */
	public static function exists($id_dorwishlist, $id_customer, $return = false)
	{
		if (!Validate::isUnsignedId($id_dorwishlist) OR
			!Validate::isUnsignedId($id_customer))
			die (Tools::displayError());
		$result = Db::getInstance()->getRow('
		SELECT `id_dorwishlist`, `name`, `token`
		  FROM `'._DB_PREFIX_.'dorwishlist`
		WHERE `id_dorwishlist` = '.(int)($id_dorwishlist).'
		AND `id_customer` = '.(int)($id_customer).'
		AND `id_shop` = '.(int)Context::getContext()->shop->id);
		if (empty($result) === false AND $result != false AND sizeof($result))
		{
			if ($return === false)
				return (true);
			else
				return ($result);
		}
		return (false);
	}

	/**
	* Get Customers having a wishlist
     	*
     	* @return array Results
     	*/
	public static function getCustomers()
	{
		$cache_id = 'WhishList::getCustomers';
		if (!Cache::isStored($cache_id))
		{
			$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
				SELECT c.`id_customer`, c.`firstname`, c.`lastname`
				  FROM `'._DB_PREFIX_.'dorwishlist` w
				INNER JOIN `'._DB_PREFIX_.'customer` c ON c.`id_customer` = w.`id_customer`
				ORDER BY c.`firstname` ASC'
			);
			Cache::store($cache_id, $result);
		}
		return Cache::retrieve($cache_id);
	}

	/**
	 * Get ID wishlist by Token
	 *
	 * @return array Results
	 */
	public static function getByToken($token)
	{
		if (!Validate::isMessage($token))
			die (Tools::displayError());
		return (Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('
		SELECT w.`id_dorwishlist`, w.`name`, w.`id_customer`, c.`firstname`, c.`lastname`
		  FROM `'._DB_PREFIX_.'dorwishlist` w
		INNER JOIN `'._DB_PREFIX_.'customer` c ON c.`id_customer` = w.`id_customer`
		WHERE `token` = \''.pSQL($token).'\''));
	}

	/**
	 * Get Wishlists by Customer ID
	 *
	 * @return array Results
	 */
	public static function getByIdCustomer($id_customer)
	{
		if (!Validate::isUnsignedId($id_customer))
			die (Tools::displayError());
		if (Shop::getContextShopID())
			$shop_restriction = 'AND id_shop = '.(int)Shop::getContextShopID();
		elseif (Shop::getContextShopGroupID())
			$shop_restriction = 'AND id_shop_group = '.(int)Shop::getContextShopGroupID();
		else
			$shop_restriction = '';

		$cache_id = 'WhishList::getByIdCustomer_'.(int)$id_customer.'-'.(int)Shop::getContextShopID().'-'.(int)Shop::getContextShopGroupID();
		if (!Cache::isStored($cache_id))
		{
			$result = Db::getInstance()->executeS('
			SELECT w.`id_dorwishlist`, w.`name`, w.`token`, w.`date_add`, w.`date_upd`, w.`counter`, w.`default`
			FROM `'._DB_PREFIX_.'dorwishlist` w
			WHERE `id_customer` = '.(int)($id_customer).'
			'.$shop_restriction.'
			ORDER BY w.`name` ASC');
			Cache::store($cache_id, $result);
		}
		return Cache::retrieve($cache_id);
	}

	public static function refreshWishList($id_dorwishlist)
	{
		$old_carts = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
		SELECT wp.id_product, wp.id_product_attribute, wpc.id_cart, UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(wpc.date_add) AS timecart
		FROM `'._DB_PREFIX_.'dorwishlist_product_cart` wpc
		JOIN `'._DB_PREFIX_.'dorwishlist_product` wp ON (wp.id_dorwishlist_product = wpc.id_dorwishlist_product)
		JOIN `'._DB_PREFIX_.'cart` c ON  (c.id_cart = wpc.id_cart)
		JOIN `'._DB_PREFIX_.'cart_product` cp ON (wpc.id_cart = cp.id_cart)
		LEFT JOIN `'._DB_PREFIX_.'orders` o ON (o.id_cart = c.id_cart)
		WHERE (wp.id_dorwishlist='.(int)($id_dorwishlist).' AND o.id_cart IS NULL)
		HAVING timecart  >= 3600*6');

		if (isset($old_carts) AND $old_carts != false)
			foreach ($old_carts AS $old_cart)
				Db::getInstance()->execute('
					DELETE FROM `'._DB_PREFIX_.'cart_product`
					WHERE id_cart='.(int)($old_cart['id_cart']).' AND id_product='.(int)($old_cart['id_product']).' AND id_product_attribute='.(int)($old_cart['id_product_attribute'])
				);

		$freshwish = Db::getInstance()->executeS('
			SELECT  wpc.id_cart, wpc.id_dorwishlist_product
			FROM `'._DB_PREFIX_.'dorwishlist_product_cart` wpc
			JOIN `'._DB_PREFIX_.'dorwishlist_product` wp ON (wpc.id_dorwishlist_product = wp.id_dorwishlist_product)
			JOIN `'._DB_PREFIX_.'cart` c ON (c.id_cart = wpc.id_cart)
			LEFT JOIN `'._DB_PREFIX_.'cart_product` cp ON (cp.id_cart = wpc.id_cart AND cp.id_product = wp.id_product AND cp.id_product_attribute = wp.id_product_attribute)
			WHERE (wp.id_dorwishlist = '.(int)($id_dorwishlist).' AND ((cp.id_product IS NULL AND cp.id_product_attribute IS NULL)))
			');
		$res = Db::getInstance()->executeS('
			SELECT wp.id_dorwishlist_product, cp.quantity AS cart_quantity, wpc.quantity AS wish_quantity, wpc.id_cart
			FROM `'._DB_PREFIX_.'dorwishlist_product_cart` wpc
			JOIN `'._DB_PREFIX_.'dorwishlist_product` wp ON (wp.id_dorwishlist_product = wpc.id_dorwishlist_product)
			JOIN `'._DB_PREFIX_.'cart` c ON (c.id_cart = wpc.id_cart)
			JOIN `'._DB_PREFIX_.'cart_product` cp ON (cp.id_cart = wpc.id_cart AND cp.id_product = wp.id_product AND cp.id_product_attribute = wp.id_product_attribute)
			WHERE wp.id_dorwishlist='.(int)($id_dorwishlist)
		);

		if (isset($res) AND $res != false)
			foreach ($res AS $refresh)
				if ($refresh['wish_quantity'] > $refresh['cart_quantity'])
				{
					Db::getInstance()->execute('
						UPDATE `'._DB_PREFIX_.'dorwishlist_product`
						SET `quantity`= `quantity` + '.((int)($refresh['wish_quantity']) - (int)($refresh['cart_quantity'])).'
						WHERE id_dorwishlist_product='.(int)($refresh['id_dorwishlist_product'])
					);
					Db::getInstance()->execute('
						UPDATE `'._DB_PREFIX_.'dorwishlist_product_cart`
						SET `quantity`='.(int)($refresh['cart_quantity']).'
						WHERE id_dorwishlist_product='.(int)($refresh['id_dorwishlist_product']).' AND id_cart='.(int)($refresh['id_cart'])
					);
				}
		if (isset($freshwish) AND $freshwish != false)
			foreach ($freshwish AS $prodcustomer)
			{
				Db::getInstance()->execute('
					UPDATE `'._DB_PREFIX_.'dorwishlist_product` SET `quantity`=`quantity` +
					(
						SELECT `quantity` FROM `'._DB_PREFIX_.'dorwishlist_product_cart`
						WHERE `id_dorwishlist_product`='.(int)($prodcustomer['id_dorwishlist_product']).' AND `id_cart`='.(int)($prodcustomer['id_cart']).'
					)
					WHERE `id_dorwishlist_product`='.(int)($prodcustomer['id_dorwishlist_product']).' AND `id_dorwishlist`='.(int)($id_dorwishlist)
					);
				Db::getInstance()->execute('
					DELETE FROM `'._DB_PREFIX_.'dorwishlist_product_cart`
					WHERE `id_dorwishlist_product`='.(int)($prodcustomer['id_dorwishlist_product']).' AND `id_cart`='.(int)($prodcustomer['id_cart'])
					);
			}
	}

	/**
	 * Get Wishlist products by Customer ID
	 *
	 * @return array Results
	 */
	public static function getProductByIdCustomer($id_dorwishlist, $id_customer, $id_lang, $id_product = null, $quantity = false)
	{
		if (!Validate::isUnsignedId($id_customer) OR
			!Validate::isUnsignedId($id_lang) OR
			!Validate::isUnsignedId($id_dorwishlist))
			die (Tools::displayError());
		$sqlProductWishlist = '
		SELECT wp.`id_product`, wp.`quantity`, p.`quantity` AS product_quantity, pl.`name`, wp.`id_product_attribute`, wp.`priority`, pl.link_rewrite, cl.link_rewrite AS category_rewrite
		FROM `'._DB_PREFIX_.'dorwishlist_product` wp
		LEFT JOIN `'._DB_PREFIX_.'product` p ON p.`id_product` = wp.`id_product`
		'.Shop::addSqlAssociation('product', 'p').'
		LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON pl.`id_product` = wp.`id_product`'.Shop::addSqlRestrictionOnLang('pl').'
		LEFT JOIN `'._DB_PREFIX_.'dorwishlist` w ON w.`id_dorwishlist` = wp.`id_dorwishlist`
		LEFT JOIN `'._DB_PREFIX_.'category_lang` cl ON cl.`id_category` = product_shop.`id_category_default` AND cl.id_lang='.(int)$id_lang.Shop::addSqlRestrictionOnLang('cl').'
		WHERE w.`id_customer` = '.(int)($id_customer).'
		AND pl.`id_lang` = '.(int)($id_lang).
		(empty($id_product) === false ? ' AND wp.`id_product` = '.(int)($id_product) : '').
		($quantity == true ? ' AND wp.`quantity` != 0': '').'
		GROUP BY p.id_product, wp.id_product_attribute';

		$products = Db::getInstance()->executeS($sqlProductWishlist);



		return $products;
	}

	/**
	 * Get Wishlists number products by Customer ID
	 *
	 * @return array Results
	 */
	public static function getInfosByIdCustomer($id_customer)
	{
		if (Shop::getContextShopID())
			$shop_restriction = 'AND id_shop = '.(int)Shop::getContextShopID();
		elseif (Shop::getContextShopGroupID())
			$shop_restriction = 'AND id_shop_group = '.(int)Shop::getContextShopGroupID();
		else
			$shop_restriction = '';

		if (!Validate::isUnsignedId($id_customer))
			die (Tools::displayError());
		return (Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
		SELECT SUM(wp.`quantity`) AS nbProducts, wp.`id_dorwishlist`
		  FROM `'._DB_PREFIX_.'dorwishlist_product` wp
		INNER JOIN `'._DB_PREFIX_.'dorwishlist` w ON (w.`id_dorwishlist` = wp.`id_dorwishlist`)
		WHERE w.`id_customer` = '.(int)($id_customer).'
		'.$shop_restriction.'
		GROUP BY w.`id_dorwishlist`
		ORDER BY w.`name` ASC'));
	}

	/**
	 * Add product to ID wishlist
	 *
	 * @return boolean succeed
	 */
	public static function addProduct($id_dorwishlist, $id_customer, $id_product, $id_product_attribute, $quantity)
	{
		if (!Validate::isUnsignedId($id_dorwishlist) OR
			!Validate::isUnsignedId($id_customer) OR
			!Validate::isUnsignedId($id_product) OR
			!Validate::isUnsignedId($quantity))
			die (Tools::displayError());
		$result = Db::getInstance()->getRow('
		SELECT wp.`quantity`
		  FROM `'._DB_PREFIX_.'dorwishlist_product` wp
		JOIN `'._DB_PREFIX_.'dorwishlist` w ON (w.`id_dorwishlist` = wp.`id_dorwishlist`)
		WHERE wp.`id_dorwishlist` = '.(int)($id_dorwishlist).'
		AND w.`id_customer` = '.(int)($id_customer).'
		AND wp.`id_product` = '.(int)($id_product).'
		AND wp.`id_product_attribute` = '.(int)($id_product_attribute));
		if (empty($result) === false AND sizeof($result))
		{
			if (($result['quantity'] + $quantity) <= 0)
				return (DorWishList::removeProduct($id_dorwishlist, $id_customer, $id_product, $id_product_attribute));
			else
				return (Db::getInstance()->execute('
				UPDATE `'._DB_PREFIX_.'dorwishlist_product` SET
				`quantity` = '.(int)($quantity + $result['quantity']).'
				WHERE `id_dorwishlist` = '.(int)($id_dorwishlist).'
				AND `id_product` = '.(int)($id_product).'
				AND `id_product_attribute` = '.(int)($id_product_attribute)));
		}
		else
			return (Db::getInstance()->execute('
			INSERT INTO `'._DB_PREFIX_.'dorwishlist_product` (`id_dorwishlist`, `id_product`, `id_product_attribute`, `quantity`, `priority`) VALUES(
			'.(int)($id_dorwishlist).',
			'.(int)($id_product).',
			'.(int)($id_product_attribute).',
			'.(int)($quantity).', 1)'));

	}

	/**
	 * Update product to wishlist
	 *
	 * @return boolean succeed
	 */
	public static function updateProduct($id_dorwishlist, $id_product, $id_product_attribute, $priority, $quantity)
	{
		if (!Validate::isUnsignedId($id_dorwishlist) OR
			!Validate::isUnsignedId($id_product) OR
			!Validate::isUnsignedId($quantity) OR
			$priority < 0 OR $priority > 2)
			die (Tools::displayError());
		return (Db::getInstance()->execute('
		UPDATE `'._DB_PREFIX_.'dorwishlist_product` SET
		`priority` = '.(int)($priority).',
		`quantity` = '.(int)($quantity).'
		WHERE `id_dorwishlist` = '.(int)($id_dorwishlist).'
		AND `id_product` = '.(int)($id_product).'
		AND `id_product_attribute` = '.(int)($id_product_attribute)));
	}

	/**
	 * Remove product from wishlist
	 *
	 * @return boolean succeed
	 */
	public static function removeProduct($id_dorwishlist, $id_customer, $id_product, $id_product_attribute)
	{
		if (!Validate::isUnsignedId($id_dorwishlist) OR
			!Validate::isUnsignedId($id_customer) OR
			!Validate::isUnsignedId($id_product))
			die (Tools::displayError());
		$result = Db::getInstance()->getRow('
		SELECT w.`id_dorwishlist`, wp.`id_dorwishlist_product`
		FROM `'._DB_PREFIX_.'dorwishlist` w
		LEFT JOIN `'._DB_PREFIX_.'dorwishlist_product` wp ON (wp.`id_dorwishlist` = w.`id_dorwishlist`)
		WHERE w.`id_customer` = '.(int)($id_customer).'
		AND wp.`id_product_attribute` = '.(int)($id_product_attribute).'
		AND wp.`id_product` = '.(int)($id_product));
		if (empty($result) === true OR
			$result === false OR
			!sizeof($result))
			return (false);
		// Delete product in wishlist_product_cart
		Db::getInstance()->execute('
		DELETE FROM `'._DB_PREFIX_.'dorwishlist_product_cart`
		WHERE `id_dorwishlist_product` = '.(int)($result['id_dorwishlist_product'])
		);
		return Db::getInstance()->execute('
		DELETE FROM `'._DB_PREFIX_.'dorwishlist_product`
		WHERE `id_dorwishlist_product` = '.(int)($result['id_dorwishlist_product']).'
		AND `id_product` = '.(int)($id_product).'
		AND `id_product_attribute` = '.(int)($id_product_attribute)
		);
	}

	/**
	 * Return bought product by ID wishlist
	 *
	 * @return Array results
	 */
	public static function getBoughtProduct($id_dorwishlist)
	{

		if (!Validate::isUnsignedId($id_dorwishlist))
			die (Tools::displayError());
		return (Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
		SELECT wp.`id_product`, wp.`id_product_attribute`, wpc.`quantity`, wpc.`date_add`, cu.`lastname`, cu.`firstname`
		FROM `'._DB_PREFIX_.'dorwishlist_product_cart` wpc
		JOIN `'._DB_PREFIX_.'dorwishlist_product` wp ON (wp.id_dorwishlist_product = wpc.id_dorwishlist_product)
		JOIN `'._DB_PREFIX_.'cart` ca ON (ca.id_cart = wpc.id_cart)
		JOIN `'._DB_PREFIX_.'customer` cu ON (cu.`id_customer` = ca.`id_customer`)
		WHERE wp.`id_dorwishlist` = '.(int)($id_dorwishlist)));
	}

	/**
	 * Add bought product
	 *
	 * @return boolean succeed
	 */
	public static function addBoughtProduct($id_dorwishlist, $id_product, $id_product_attribute, $id_cart, $quantity)
	{
		if (!Validate::isUnsignedId($id_dorwishlist) OR
			!Validate::isUnsignedId($id_product) OR
			!Validate::isUnsignedId($quantity))
			die (Tools::displayError());
		$result = Db::getInstance()->getRow('
			SELECT `quantity`, `id_dorwishlist_product`
		  FROM `'._DB_PREFIX_.'dorwishlist_product` wp
			WHERE `id_dorwishlist` = '.(int)($id_dorwishlist).'
			AND `id_product` = '.(int)($id_product).'
			AND `id_product_attribute` = '.(int)($id_product_attribute));

		if (!sizeof($result) OR
			($result['quantity'] - $quantity) < 0 OR
			$quantity > $result['quantity'])
			return (false);

			Db::getInstance()->executeS('
			SELECT *
			FROM `'._DB_PREFIX_.'dorwishlist_product_cart`
			WHERE `id_dorwishlist_product`='.(int)($result['id_dorwishlist_product']).' AND `id_cart`='.(int)($id_cart)
			);

		if (Db::getInstance()->NumRows() > 0)
			$result2= Db::getInstance()->execute('
				UPDATE `'._DB_PREFIX_.'dorwishlist_product_cart`
				SET `quantity`=`quantity` + '.(int)($quantity).'
				WHERE `id_dorwishlist_product`='.(int)($result['id_dorwishlist_product']).' AND `id_cart`='.(int)($id_cart)
				);

		else
			$result2 = Db::getInstance()->execute('
				INSERT INTO `'._DB_PREFIX_.'dorwishlist_product_cart`
				(`id_dorwishlist_product`, `id_cart`, `quantity`, `date_add`) VALUES(
				'.(int)($result['id_dorwishlist_product']).',
				'.(int)($id_cart).',
				'.(int)($quantity).',
				\''.pSQL(date('Y-m-d H:i:s')).'\')');

		if ($result2 === false)
			return (false);
		return (Db::getInstance()->execute('
			UPDATE `'._DB_PREFIX_.'dorwishlist_product` SET
			`quantity` = '.(int)($result['quantity'] - $quantity).'
			WHERE `id_dorwishlist` = '.(int)($id_dorwishlist).'
			AND `id_product` = '.(int)($id_product).'
			AND `id_product_attribute` = '.(int)($id_product_attribute)));
	}

	/**
	 * Add email to wishlist
	 *
	 * @return boolean succeed
	 */
	public static function addEmail($id_dorwishlist, $email)
	{
		if (!Validate::isUnsignedId($id_dorwishlist) OR empty($email) OR !Validate::isEmail($email))
			return false;
		return (Db::getInstance()->execute('
		INSERT INTO `'._DB_PREFIX_.'dorwishlist_email` (`id_dorwishlist`, `email`, `date_add`) VALUES(
		'.(int)($id_dorwishlist).',
		\''.pSQL($email).'\',
		\''.pSQL(date('Y-m-d H:i:s')).'\')'));
	}

	/**
	 * Get email from wishlist
	 *
	 * @return Array results
	 */
	public static function getEmail($id_dorwishlist, $id_customer)
	{
		if (!Validate::isUnsignedId($id_dorwishlist) OR
			!Validate::isUnsignedId($id_customer))
			die (Tools::displayError());
		return (Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
		SELECT we.`email`, we.`date_add`
		  FROM `'._DB_PREFIX_.'dorwishlist_email` we
		INNER JOIN `'._DB_PREFIX_.'dorwishlist` w ON w.`id_dorwishlist` = we.`id_dorwishlist`
		WHERE we.`id_dorwishlist` = '.(int)($id_dorwishlist).'
		AND w.`id_customer` = '.(int)($id_customer)));
	}

	/**
	* Return if there is a default already set
	*
	* @return boolean
	*/
	public static function isDefault($id_customer)
	{
		return (Bool)Db::getInstance()->getValue('SELECT * FROM `'._DB_PREFIX_.'dorwishlist` WHERE `id_customer` = '.$id_customer.' AND `default` = 1');
	}

	public static function getDefault($id_customer)
	{
		return Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'dor
			wishlist` WHERE `id_customer` = '.$id_customer.' AND `default` = 1');
	}

	/**
	* Set current WishList as default
	*
	* @return boolean
	*/
	public function setDefault()
	{
		if ($default = $this->getDefault($this->id_customer))
			Db::getInstance()->update('wishlist', array('default' => '0'), 'id_dorwishlist = '.$default[0]['id_dorwishlist']);

		return Db::getInstance()->update('wishlist', array('default' => '1'), 'id_dorwishlist = '.$this->id);
	}
};
