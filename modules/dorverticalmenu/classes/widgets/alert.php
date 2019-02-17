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

class DorVerticalmenuWidgetAlert extends DorVerticalmenuWidgetBase {

	public $name = 'alert';

	public function getWidgetInfo()
	{
		return array('label' => $this->l('Alert'), 'explain' => 'Create a Alert Message Box Based on Bootstrap 3 typo');
	}

	public function renderForm($data)
	{
		$helper = $this->getFormHelper();
		$types = array(
			array(
				'value' => 'alert-success',
				'text' => $this->l('Alert Success')
			),
			array(
				'value' => 'alert-info',
				'text' => $this->l('Alert Info')
			),
			array(
				'value' => 'alert-warning',
				'text' => $this->l('Alert Warning')
			),
			array(
				'value' => 'alert-danger',
				'text' => $this->l('Alert Danger')
			)
		);

		$input_fields = array(
			array(
				'type' => 'textarea',
				'label' => $this->l('Content'),
				'name' => 'htmlcontent',
				'cols' => 40,
				'rows' => 10,
				'value' => true,
				'lang' => true,
				'default' => '',
				'autoload_rte' => true,
			),
			array(
				'type' => 'select',
				'label' => $this->l('Alert Type'),
				'name' => 'alert_type',
				'options' => array('query' => $types,
					'id' => 'value',
					'name' => 'text'),
				'default' => '1',
				'desc' => $this->l('Select a alert style')
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
			'name' => '',
			'html' => '',
			'alert_type' => ''
		);
		$setting = array_merge($t, $setting);
		$language_id = Context::getContext()->language->id;
		$setting['html'] = isset($setting['htmlcontent_'.$language_id]) ?
			html_entity_decode($setting['htmlcontent_'.$language_id], ENT_QUOTES, 'UTF-8') : '';

		$output = array('type' => 'alert', 'data' => $setting);

		return $output;
	}

}