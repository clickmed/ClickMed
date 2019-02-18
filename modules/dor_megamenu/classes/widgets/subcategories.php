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

class DorMegamenuWidgetSubcategories extends DorMegamenuWidgetBase {

	public $name = 'subcategories';

	public function getWidgetInfo()
	{
		return array('label' => $this->l('Sub Categories In Parent'), 'explain' => 'Show List Of Categories Links Of Parent');
	}

	public function renderForm($data)
	{
		$helper = $this->getFormHelper();
		$id_parent = isset($data['params']) && isset($data['params']['id_parent']) ? $data['params']['id_parent'] : 3;

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
				'type' => 'categories',
				'label' => $this->l('Parent Category ID'),
				'name' => 'id_parent',
				'tree' => array(
					'id' => 'categories-tree',
					'selected_categories' => array($id_parent),
					'root_category' => Context::getContext()->shop->getCategory()
				),
				'default' => '3,4,8',
			),
			array(
				'type' => 'text',
				'label' => $this->l('Limit'),
				'name' => 'limit',
				'default' => '6',
			),
			array(
				'type' => 'switch',
				'label' => $this->l('Show Category Image'),
				'name' => 'show_image',
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
			'id_parent' => '',
			'category_id' => '',
			'limit' => '12',
			'show_image' => 0
		);
		$setting = array_merge($t, $setting);
		$setting['category_id'] = $setting['id_parent'];
		$nb = (int)$setting['limit'];
		$category = new Category($setting['category_id'], $this->lang_id);
		$sub_categories = self::getSubCategories($this->lang_id, true, $setting['category_id'], 0, $nb);

		$setting['title'] = $category->name;
		$setting['ocategory'] = $category;

		$setting['subcategories'] = $sub_categories;
		$output = array('type' => 'subcategories', 'data' => $setting);

		return $output;
	}

	public static function getSubCategories($id_lang, $active = true, $id_category = 2, $p = 0, $n = 6)
	{
		$sql_groups_where = '';
		$sql_groups_join = '';
		if (Group::isFeatureActive())
		{
			$sql_groups_join = 'LEFT JOIN `'._DB_PREFIX_.'category_group` cg ON (cg.`id_category` = c.`id_category`)';
			$groups = FrontController::getCurrentCustomerGroups();
			$sql_groups_where = 'AND cg.`id_group` '.(count($groups) ? 'IN ('.pSQL(implode(',', $groups)).')' : '='.(int)Group::getCurrent()->id);
		}

		$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
        SELECT c.*, cl.id_lang, cl.name, cl.description, cl.link_rewrite, cl.meta_title, cl.meta_keywords, cl.meta_description
        FROM `'._DB_PREFIX_.'category` c
        '.Shop::addSqlAssociation('category', 'c').'
        LEFT JOIN `'._DB_PREFIX_.'category_lang` cl ON (c.`id_category` = cl.`id_category` 
    		AND `id_lang` = '.(int)$id_lang.' '.Shop::addSqlRestrictionOnLang('cl').')
        '.$sql_groups_join.'
        WHERE `id_parent` = '.(int)$id_category.'
        '.($active ? 'AND `active` = 1' : '').'
        '.$sql_groups_where.'
        GROUP BY c.`id_category`
        ORDER BY `level_depth` ASC, category_shop.`position` ASC
        LIMIT '.(int)$p.', '.(int)$n);

		foreach ($result as &$row)
		{
			$row['id_image'] = Tools::file_exists_cache(_PS_CAT_IMG_DIR_.$row['id_category'].'.jpg')
				? (int)$row['id_category'] : Language::getIsoById($id_lang).'-default';
			$row['legend'] = 'no picture';
		}
		return $result;
	}

}