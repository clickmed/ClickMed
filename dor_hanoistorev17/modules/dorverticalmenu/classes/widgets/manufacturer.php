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

class DorVerticalmenuWidgetManufacturer extends DorVerticalmenuWidgetBase {

	public $name = 'Manufacturer';

	public function getWidgetInfo()
	{
		return array('label' => $this->l('Manufacturer Logos'), 'explain' => 'Manufacturer Logo');
	}

	public function renderForm($data)
	{
		$helper = $this->getFormHelper();
		$input_fields = array(
			array(
				'type' => 'text',
				'label' => $this->l('Limit'),
				'name' => 'limit',
				'default' => 10,
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
			'name' => '',
			'html' => '',
		);
		$setting = array_merge($t, $setting);

		$data = Manufacturer::getManufacturers(false, 0, true, 1, $setting['limit']);

		foreach ($data as $key => $item)
		{
			$item['image'] = !file_exists(_PS_MANU_IMG_DIR_.$item['id_manufacturer'].'-'.ImageType::getFormatedName('medium').'.jpg') ? Context::getContext()->language->iso_code.'-default' : $item['id_manufacturer'];
			$data[$key] = $item;
		}

		$setting['manufacturers'] = $data;
		$output = array('type' => 'manufacturer', 'data' => $setting);

		return $output;
	}

}