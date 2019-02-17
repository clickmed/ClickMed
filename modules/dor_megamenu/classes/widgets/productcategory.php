<?php
/**
 * Manager and display megamenu use bootstrap framework
 *
 * @package   dormegamenu
 * @version   1.0.0
 * @author    http://www.doradothemes@gmail.com
 * @copyright Copyright (C) December 2015 doradothemes@gmail.com <@emai:doradothemes@gmail.com>
 *               <info@doradothemes@gmail.com>.All rights reserved.
 * @license   GNU General Public License version 2
 */

class DorMegamenuWidgetProductcategory extends DorMegamenuWidgetBase {

	public $name = 'productcategory';

	public function getWidgetInfo()
	{
		return array('label' => $this->l('Products By Category ID'), 'explain' => 'Created Product List From Category ID');
	}

	public function renderForm($data)
	{
		$helper = $this->getFormHelper();
		$id_parent = isset($data['params']) && isset($data['params']['id_parent']) ? $data['params']['id_parent'] : 3;
		
		$input_fields = array(
			array(
				'type' => 'categories',
				'label' => $this->l('Parent category'),
				'name' => 'id_parent',
				'tree' => array(
					'id' => 'categories-tree',
					'selected_categories' => array($id_parent),
					'disabled_categories' => null,
					'root_category' => Context::getContext()->shop->getCategory()
				)
			),
			array(
				'type' => 'text',
				'label' => $this->l('Limit'),
				'name' => 'limit',
				'default' => 6,
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
			'id_parent' => '',
			'limit' => '12',
			'image_width' => '200',
			'image_height' => '200',
		);
		$setting = array_merge($t, $setting);
		$nb = (int)$setting['limit'];

		$category = new Category($setting['id_parent'], $this->lang_id);
		$products = $category->getProducts((int)$this->lang_id, 1, ($nb ? $nb : 8));

		$setting['products'] = $products;
		$output = array('type' => 'productcategory', 'data' => $setting);

		return $output;
	}

}