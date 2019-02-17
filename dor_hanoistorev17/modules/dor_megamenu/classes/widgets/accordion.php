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

class DorMegamenuWidgetAccordion extends DorMegamenuWidgetBase {

	public $name = 'accordion';

	public function getWidgetInfo()
	{
		return array('label' => $this->l('Accordion'), 'explain' => 'Create Accordions List');
	}

	public function renderForm($data)
	{
		$helper = $this->getFormHelper();
		$input_fields = array();
		for ($i = 1; $i <= 6; $i++) {
			$input_fields[] = array(
				'type' => 'text',
				'label' => $this->l('Header').' '.$i,
				'name' => 'header_'.$i,
				'default' => 'Sample Header '.$i,
				'lang' => true
			);

			$input_fields[] = array(
				'type' => 'textarea',
				'label' => $this->l('Content').' '.$i,
				'name' => 'content_'.$i,
				'default' => 'Content Sample '.$i,
				'cols' => 40,
				'rows' => 10,
				'value' => true,
				'lang' => true,
				'autoload_rte' => true,
				'desc' => $this->l('Enter Your Content').' '.$i
			);
		}
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

		$html = '';
		$language_id = Context::getContext()->language->id;

		if (is_array($setting['html']) && isset($setting['html'][$language_id]))
		{
			$html = $setting['html'][$language_id];
			$html = html_entity_decode($html, ENT_QUOTES, 'UTF-8');
		}

		$header = '';
		$content = $html;

		$ac = array();
		$language_id = Context::getContext()->language->id;
		for ($i = 1; $i <= 6; $i++)
		{
			$header = isset($setting['header_'.$i.'_'.$language_id]) ? $setting['header_'.$i.'_'.$language_id] : '';
			if (!empty($header))
			{
				$content = isset($setting['content_'.$i.'_'.$language_id]) ? $setting['content_'.$i.'_'.$language_id] : '';
				$ac[] = array('header' => $header, 'content' => trim($content));
			}
		}
		$setting['accordions'] = $ac;
		$setting['id'] = rand() + rand() + count($ac);
		$output = array('type' => 'accordion', 'data' => $setting);

		return $output;
	}

}