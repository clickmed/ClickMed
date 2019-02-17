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

class DorVerticalmenuWidgetSocial extends DorVerticalmenuWidgetBase {

	public $name = 'map';

	public function getWidgetInfo()
	{
		return array('label' => $this->l('Google Map'), 'explain' => 'Create A Google Map');
	}

	public function renderForm($data)
	{
		$helper = $this->getFormHelper();
		$input_fields = array(
			array(
				'type' => 'text',
				'label' => $this->l('Latitude'),
				'name' => 'latitude',
				'default' => 21.010904,
			),
			array(
				'type' => 'text',
				'label' => $this->l('Longitude'),
				'name' => 'longitude',
				'default' => 105.787736,
			),
			array(
				'type' => 'text',
				'label' => $this->l('Zoom'),
				'name' => 'zoom',
				'default' => 11,
			),
			array(
				'type' => 'text',
				'label' => $this->l('Width'),
				'name' => 'width',
				'default' => 250,
			),
			array(
				'type' => 'text',
				'label' => $this->l('Height'),
				'name' => 'height',
				'default' => 200,
			),
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
			'latitude' => '21.010904',
			'longitude' => '105.787736',
			'zoom' => 11,
			'width' => '250',
			'height' => '200',
			'is_preview' => trim(Tools::getValue('controller')) == 'widget' ? 1 : 0
		);
		$setting = array_merge($t, $setting);

		$setting['height'] = $setting['height'].'px';
		$setting['width'] = $setting['width'] == '100%' ? '100%' : $setting['width'].'px';
		$output = array('type' => 'map', 'data' => $setting);

		return $output;
	}

}