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

class DorSlide extends ObjectModel
{
	public $title;
	public $description;
	public $url;
	public $legend;
	public $image;
	public $active;
	public $effect;
	public $price;
	public $imageproduct;
	public $position;
	public $id_shop;
	public $txtReadmore1;
	public $txtReadmore2;
	public $UrlReadmore1;
	public $UrlReadmore2;
	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table' => 'dorimageslider_items',
		'primary' => 'id_slides',
		'multilang' => true,
		'fields' => array(
			'active' =>			array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true),
			'position' =>		array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => true),
			'effect' =>			array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => false),
			'imageproduct' =>	array('type' => self::TYPE_STRING, 'validate' => 'isUrl', 'required' => false),
			'price' 		=>	array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCleanHtml', 'size' => 100),
			// Lang fields
			'description' =>	array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml', 'size' => 4000),
			'title' =>			array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCleanHtml', 'size' => 255),
			'legend' =>			array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCleanHtml', 'size' => 255),
			'url' =>			array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isUrl', 'required' => true, 'size' => 255),
			'image' =>			array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCleanHtml', 'size' => 255),
			'txtReadmore1' =>	array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCleanHtml', 'size' => 355),
			'txtReadmore2' =>	array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCleanHtml', 'size' => 255),
			'UrlReadmore1' =>	array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCleanHtml', 'size' => 355),
			'UrlReadmore2' =>	array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCleanHtml', 'size' => 355),
		)
	);

	public	function __construct($id_slide = null, $id_lang = null, $id_shop = null, Context $context = null)
	{
		parent::__construct($id_slide, $id_lang, $id_shop);
	}

	public function add($autodate = true, $null_values = false)
	{
		$context = Context::getContext();
		$id_shop = $context->shop->id;

		$res = parent::add($autodate, $null_values);
		$res &= Db::getInstance()->execute('
			INSERT INTO `'._DB_PREFIX_.'dorimageslider` (`id_shop`, `id_slides`)
			VALUES('.(int)$id_shop.', '.(int)$this->id.')'
		);
		return $res;
	}

	public function delete()
	{
		$res = true;

		$images = $this->image;
		foreach ($images as $image)
		{
			if (preg_match('/sample/', $image) === 0)
				if ($image && file_exists(dirname(__FILE__).'/images/'.$image))
					$res &= @unlink(dirname(__FILE__).'/images/'.$image);
		}

		$res &= $this->reOrderPositions();

		$res &= Db::getInstance()->execute('
			DELETE FROM `'._DB_PREFIX_.'dorimageslider`
			WHERE `id_slides` = '.(int)$this->id
		);

		$res &= parent::delete();
		return $res;
	}

	public function reOrderPositions()
	{
		$id_slide = $this->id;
		$context = Context::getContext();
		$id_shop = $context->shop->id;

		$max = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
			SELECT MAX(hss.`position`) as position
			FROM `'._DB_PREFIX_.'dorimageslider_items` hss, `'._DB_PREFIX_.'dorimageslider` hs
			WHERE hss.`id_slides` = hs.`id_slides` AND hs.`id_shop` = '.(int)$id_shop
		);

		if ((int)$max == (int)$id_slide)
			return true;

		$rows = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
			SELECT hss.`position` as position, hss.`id_slides` as id_slide
			FROM `'._DB_PREFIX_.'dorimageslider_items` hss
			LEFT JOIN `'._DB_PREFIX_.'dorimageslider` hs ON (hss.`id_slides` = hs.`id_slides`)
			WHERE hs.`id_shop` = '.(int)$id_shop.' AND hss.`position` > '.(int)$this->position
		);

		foreach ($rows as $row)
		{
			$current_slide = new HomeSlide($row['id_slide']);
			--$current_slide->position;
			$current_slide->update();
			unset($current_slide);
		}

		return true;
	}

	public static function getAssociatedIdsShop($id_slide)
	{
		$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
			SELECT hs.`id_shop`
			FROM `'._DB_PREFIX_.'dorimageslider` hs
			WHERE hs.`id_slides` = '.(int)$id_slide
		);

		if (!is_array($result))
			return false;

		$return = array();

		foreach ($result as $id_shop)
			$return[] = (int)$id_shop['id_shop'];

		return $return;
	}

}
