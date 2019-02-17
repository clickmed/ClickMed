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

abstract class DorMegamenuWidgetBase {

	public $mod_name = 'dormegamenu';
	public $name = 'base';
	public $id_shop = 0;
	public $fields_form = array();
	public $input_fields = array();
	public $input_fields_end = array();
	public $types = array();

	/**
	 * abstract method to return html widget form
	 */
	public function getWidgetInfo()
	{
		return array('key' => 'base', 'label' => 'Widget Base');
	}

	/**
	 * abstract method to return html widget form
	 */
	abstract protected function renderForm($data);

	/**
	 * abstract method to return widget data 
	 */
	abstract protected function renderContent($data);

	public function l($string, $specific = false)
	{
		return Translate::getModuleTranslation($this->mod_name, $string, ($specific) ? $specific : $this->mod_name);
	}

	public function getFormHelper()
	{
		$default_lang = (int)Configuration::get('PS_LANG_DEFAULT');
		$this->input_fields = array(
			array(
				'type' => 'hidden',
				'label' => $this->l('ID'),
				'name' => 'id_dormegamenu_widget',
				'default' => 0,
			),
			array(
				'type' => 'hidden',
				'label' => $this->l('Widget Type'),
				'id' => 'type',
				'name' => 'type',
				'options' => array(
					'query' => $this->types,
					'id' => 'type',
					'name' => 'label'
				),
				'default' => Tools::getValue('wtype'),
				'desc' => $this->l('Select a alert style')
			),
			array(
				'type' => 'text',
				'label' => $this->l('Widget Name'),
				'name' => 'name',
				'default' => '',
				'desc' => $this->l('Using for show in Listing Widget Management')
			),
			array(
				'type' => 'text',
				'label' => $this->l('Widget Title'),
				'name' => 'widget_title',
				'default' => '',
				'lang' => true,
				'desc' => $this->l('This tile will be showed as header of widget block. Empty to disable')
			)
		);
		
		$this->input_fields_end = array(
			array(
				'type' => 'text',
				'label' => $this->l('Addition Class'),
				'name' => 'additionclss',
				'default' => '',
				'desc' => $this->l('all your addition to make own style')
			)
		);

		$helper = new HelperForm();
		$helper->module = $this;
		$helper->name_controller = $this->mod_name;
		$helper->identifier = $this->mod_name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		foreach (Language::getLanguages(false) as $lang)
			$helper->languages[] = array(
				'id_lang' => $lang['id_lang'],
				'iso_code' => $lang['iso_code'],
				'name' => $lang['name'],
				'is_default' => ($default_lang == $lang['id_lang'] ? 1 : 0)
			);

		$helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->mod_name.'&widgets=1&rand='.rand().'&wtype='.Tools::getValue('wtype');
		$helper->default_form_language = $default_lang;
		$helper->allow_employee_form_lang = $default_lang;
		$helper->toolbar_scroll = true;
		$helper->title = $this->mod_name;
		$helper->submit_action = 'save'.$this->mod_name;

		return $helper;
	}

	public function getConfigFieldsValues($data = null)
	{
		$languages = Language::getLanguages(false);
		$fields_values = array();
		$obj = isset($data['params']) ? $data['params'] : array();

		foreach ($this->fields_form as $k => $f)
		{
			foreach ($f['form']['input'] as $j => $input)
			{
				if (isset($input['lang']))
				{
					foreach ($languages as $lang) {
						$fields_values[$input['name']][$lang['id_lang']] = isset($obj[$input['name'].'_'.$lang['id_lang']]) ? $obj[$input['name'].'_'.$lang['id_lang']] : $input['default'];
					}
				}
				else
				{
					if (isset($obj[trim($input['name'])]))
					{
						$value = $obj[trim($input['name'])];
						if ($input['name'] == 'image' && $value)
						{
							$thumb = _DORMEGAMENU_IMAGE_URL_.$value;
							$this->fields_form[$k]['form']['input'][$j]['thumb'] = $thumb;
						}
						$fields_values[$input['name']] = $value;
					}
					else
					{
						$v = Tools::getValue($input['name'], Configuration::get($input['name']));
						$fields_values[$input['name']] = $v ? $v : (isset($input['default']) ? $input['default'] : '');
					}
				}
			}
		}
		if (isset($data['id_dormegamenu_widget'])) {
			$fields_values['id_dormegamenu_widget'] = $data['id_dormegamenu_widget'];
		}

		return $fields_values;
	}
}
