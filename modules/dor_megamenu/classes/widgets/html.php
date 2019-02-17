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

class DorMegamenuWidgetHtml extends DorMegamenuWidgetBase {

	public $name = 'html';

	public function getWidgetInfo()
	{
		return array('label' => $this->l('HTML'), 'explain' => 'Create HTML With multiple Language');
	}

	public function renderForm($data)
	{
		$helper = $this->getFormHelper();

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
		$lang_id = Context::getContext()->language->id;
		$setting['html'] = isset($setting['htmlcontent_'.$lang_id]) ? ($setting['htmlcontent_'.$lang_id]) : '';

		$output = array('type' => 'html', 'data' => $setting);

		return $output;
	}

}