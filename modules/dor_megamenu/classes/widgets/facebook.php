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

class DorMegamenuWidgetFacebook extends DorMegamenuWidgetBase {

	public $name = 'facebook';

	public function getWidgetInfo()
	{
		return array('label' => $this->l('Facebook'), 'explain' => 'Facebook Like Box');
	}

	public function renderForm($data)
	{
		$helper = $this->getFormHelper();
		$soption = array(
			array(
				'id' => 'active_on',
				'value' => 1,
				'label' => $this->l('Enabled')
			),
			array(
				'id' => 'active_off',
				'value' => 0,
				'label' => $this->l('Disabled')
			)
		);

		$input_fields = array(
			array(
				'type' => 'text',
				'label' => $this->l('Page URL'),
				'name' => 'page_url',
				'default' => 'https://www.facebook.com/prestashop',
			),
			array(
				'type' => 'switch',
				'label' => $this->l('Is Border'),
				'name' => 'border',
				'values' => $soption,
				'default' => '1',
			),
			array(
				'type' => 'select',
				'label' => $this->l('Color'),
				'name' => 'target',
				'options' => array('query' => array(
						array('id' => 'dark', 'name' => $this->l('Dark')),
						array('id' => 'light', 'name' => $this->l('Light')),
					),
					'id' => 'id',
					'name' => 'name'),
				'default' => '_self',
			),
			array(
				'type' => 'text',
				'label' => $this->l('Width'),
				'name' => 'width',
				'default' => '',
			),
			array(
				'type' => 'text',
				'label' => $this->l('Height'),
				'name' => 'height',
				'default' => '',
			),
			array(
				'type' => 'switch',
				'label' => $this->l('Show Stream'),
				'name' => 'show_stream',
				'values' => $soption,
				'default' => '0',
			),
			array(
				'type' => 'switch',
				'label' => $this->l('Show Faces'),
				'name' => 'show_faces',
				'values' => $soption,
				'default' => '1',
			),
			array(
				'type' => 'switch',
				'label' => $this->l('Show Header'),
				'name' => 'show_header',
				'values' => $soption,
				'default' => '0',
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
			'application_id' => '',
			'page_url' => 'https://www.facebook.com/Pavothemes',
			'border' => 0,
			'color' => 'light',
			'width' => 290,
			'height' => 200,
			'show_stream' => 0,
			'show_faces' => 1,
			'show_header' => 0,
			'displaylanguage' => 'en'
		);
		$setting = array_merge($t, $setting);

		$output = array('type' => 'facebook', 'data' => $setting);
		return $output;
	}

}