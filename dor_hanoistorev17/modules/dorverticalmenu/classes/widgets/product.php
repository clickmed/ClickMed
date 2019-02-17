<?php
/**
 * Manager and display verticalmenu use bootstrap framework
 *
 * @package   dorverticalmenu
 * @version   1.0.0
 * @author    http://www.doradothemes@gmail.com
 * @copyright Copyright (C) December 2015 doradothemes@gmail.com <@emai:doradothemes@gmail.com>
 *               <info@doradothemes@gmail.com>.All rights reserved.
 * @license   GNU General Public License version 2
 */

class DorVerticalmenuWidgetProduct extends DorVerticalmenuWidgetBase {

	public $name = 'product';

	public function getWidgetInfo()
	{
		return array('label' => $this->l('Product Item'), 'explain' => 'Product Item');
	}

	public function renderForm($data)
	{
		$helper = $this->getFormHelper();
		$input_fields = array(
			array(
				'type' => 'text',
				'label' => $this->l('Product ID'),
				'name' => 'product_id',
				'default' => 1,
			)
		);
		$fields = array_merge($this->input_fields, $input_fields, $this->input_fields_end);
		$this->fields_form[0]['form'] = array(
			'legend' => array(
				'title' => $this->l('Widget Infomation'),
			),
			'input' => $fields
		);
		$default_lang = (int)Configuration::get('PS_LANG_DEFAULT');

		$helper->tpl_vars = array(
			'fields_value' => $this->getConfigFieldsValues($data),
			'languages' => Context::getContext()->controller->getLanguages(),
			'id_language' => $default_lang
		);
		return $helper->generateForm($this->fields_form);
	}

	public function renderContent($setting)
	{
		$t = array(
			'product_id' => 0,
			'image_height' => '320',
			'image_width' => 300
		);
		$setting = array_merge($t, $setting);

		$id_lang = (int)$this->lang_id;
		$id_product = $setting['product_id'];

		$sql = 'SELECT p.*, product_shop.*, stock.`out_of_stock` out_of_stock, pl.`description`, pl.`description_short`,
						pl.`link_rewrite`, pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`, pl.`name`,
						p.`ean13`, p.`upc`, MAX(image_shop.`id_image`) id_image, il.`legend`,
						DATEDIFF(product_shop.`date_add`, DATE_SUB(NOW(),
						INTERVAL '.(Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).'
							DAY)) > 0 AS new
					FROM `'._DB_PREFIX_.'product` p
					LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (
						p.`id_product` = pl.`id_product`
						AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl').'
					)
					'.Shop::addSqlAssociation('product', 'p').'
					LEFT JOIN `'._DB_PREFIX_.'image` i ON (i.`id_product` = p.`id_product`)'.
						Shop::addSqlAssociation('image', 'i', false, 'image_shop.cover=1').'
					LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (i.`id_image` = il.`id_image` AND il.`id_lang` = '.(int)$id_lang.')
					'.Product::sqlStock('p', 0).'
					WHERE p.id_product = '.(int)$id_product.'
					GROUP BY product_shop.id_product';

		$row = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow($sql);
		if (!$row)
			return false;

		if (isset($row['id_product_attribute']) && $row['id_product_attribute'])
			$row['id_product_attribute'] = $row['id_product_attribute'];
		$p = Product::getProductProperties($id_lang, $row);
		$setting['product'] = $p;

		$output = array('type' => 'product', 'data' => $setting);
		return $output;
	}

}