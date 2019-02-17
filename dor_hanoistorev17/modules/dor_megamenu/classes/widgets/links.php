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

class DorMegamenuWidgetLinks extends DorMegamenuWidgetBase {

	public $name = 'link';

	public function getWidgetInfo()
	{
		return array('label' => $this->l('Block Links'), 'explain' => 'Create List Block Links');
	}

	public function renderForm($data)
	{
		$helper = $this->getFormHelper();
		$input_fields = array();

		for ($i=1; $i <= 10; $i++) {
			$input_fields[] = array(
				'type' => 'text',
				'label' => $this->l('Text Link').' '.$i,
				'name' => 'text_link_'.$i,
				'default' => 'link '.$i,
				'lang' => true
			);

			$input_fields[] = array(
				'type' => 'text',
				'label' => $this->l('Link').' '.$i,
				'name' => 'link_'.$i,
				'default' => '#',
				'desc' => $this->l('Enter Your Content Link')
			);
			$input_fields[] = array(
				'type' => 'text',
				'label' => $this->l('Class Icon').' '.$i,
				'name' => 'icon_link_'.$i,
				'default' => '',
				'desc' => $this->l('Enter Your Icon Link')
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

		$ac = array();
		$language_id = Context::getContext()->language->id;

		for ($i = 1; $i <= 10; $i++)
		{
			if (isset($setting['link_'.$i]) && trim($setting['link_'.$i]))
			{
				$link = isset($setting['text_link_'.$i.'_'.$language_id]) ?
					html_entity_decode($setting['text_link_'.$i.'_'.$language_id], ENT_QUOTES, 'UTF-8') : 'No Link Title';
					$iconLink = isset($setting['icon_link_'.$i]) ?"<i class='".$setting['icon_link_'.$i]."'></i>":"";
				$ac[] = array('text' => $link, 'link' => trim($setting['link_'.$i]), 'icon_class'=>$iconLink);
			}
		}

		$setting['id'] = rand();
		$setting['links'] = $ac;

		$output = array('type' => 'links', 'data' => $setting);

		return $output;
	}

}