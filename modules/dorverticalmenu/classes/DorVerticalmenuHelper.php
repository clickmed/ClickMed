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

class DorVerticalmenuHelper {


	public static function getCMSCategories($recursive = false, $parent = 1, $id_lang = false, $id_shop = false)
    {
        $id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext()->language->id;
        $id_shop = ($id_shop !== false) ? $id_shop : Context::getContext()->shop->id;
        $join_shop = '';
        $where_shop = '';

        if (Tools::version_compare(_PS_VERSION_, '1.6.0.12', '>=') == true) {
            $join_shop = ' INNER JOIN `'._DB_PREFIX_.'cms_category_shop` cs
			ON (bcp.`id_cms_category` = cs.`id_cms_category`)';
            $where_shop = ' AND cs.`id_shop` = '.(int)$id_shop.' AND cl.`id_shop` = '.(int)$id_shop;
        }

        if ($recursive === false) {
            $sql = 'SELECT bcp.`id_cms_category`, bcp.`id_parent`, bcp.`level_depth`, bcp.`active`, bcp.`position`, cl.`name`, cl.`link_rewrite`
				FROM `'._DB_PREFIX_.'cms_category` bcp'.
                $join_shop.'
				INNER JOIN `'._DB_PREFIX_.'cms_category_lang` cl
				ON (bcp.`id_cms_category` = cl.`id_cms_category`)
				WHERE cl.`id_lang` = '.(int)$id_lang.'
				AND bcp.`id_parent` = '.(int)$parent.
                $where_shop;

            return Db::getInstance()->executeS($sql);
        } else {
            $sql = 'SELECT bcp.`id_cms_category`, bcp.`id_parent`, bcp.`level_depth`, bcp.`active`, bcp.`position`, cl.`name`, cl.`link_rewrite`
				FROM `'._DB_PREFIX_.'cms_category` bcp'.
                $join_shop.'
				INNER JOIN `'._DB_PREFIX_.'cms_category_lang` cl
				ON (bcp.`id_cms_category` = cl.`id_cms_category`)
				WHERE cl.`id_lang` = '.(int)$id_lang.'
				AND bcp.`id_parent` = '.(int)$parent.
                $where_shop;

            $results = Db::getInstance()->executeS($sql);
            foreach ($results as $result) {
                $sub_categories = self::getCMSCategories(true, $result['id_cms_category'], (int)$id_lang);
                if ($sub_categories && count($sub_categories) > 0) {
                    $result['sub_categories'] = $sub_categories;
                }
                $categories[] = $result;
            }

            return isset($categories) ? $categories : false;
        }
    }

    public static function customGetNestedCategories($shop_id, $root_category = null, $id_lang = false, $active = false, $groups = null, $use_shop_restriction = true, $sql_filter = '', $sql_sort = '', $sql_limit = '')
    {
        if (isset($root_category) && !Validate::isInt($root_category)) {
            die(Tools::displayError());
        }

        if (!Validate::isBool($active)) {
            die(Tools::displayError());
        }

        if (isset($groups) && Group::isFeatureActive() && !is_array($groups)) {
            $groups = (array)$groups;
        }

        $cache_id = 'Category::getNestedCategories_'.md5((int)$shop_id.(int)$root_category.(int)$id_lang.(int)$active.(int)$active
            .(isset($groups) && Group::isFeatureActive() ? implode('', $groups) : ''));

        if (!Cache::isStored($cache_id)) {
            $result = Db::getInstance()->executeS('
							SELECT c.*, cl.*
				FROM `'._DB_PREFIX_.'category` c
				INNER JOIN `'._DB_PREFIX_.'category_shop` category_shop ON (category_shop.`id_category` = c.`id_category` AND category_shop.`id_shop` = "'.(int)$shop_id.'")
				LEFT JOIN `'._DB_PREFIX_.'category_lang` cl ON (c.`id_category` = cl.`id_category` AND cl.`id_shop` = "'.(int)$shop_id.'")
				WHERE 1 '.$sql_filter.' '.($id_lang ? 'AND cl.`id_lang` = '.(int)$id_lang : '').'
				'.($active ? ' AND (c.`active` = 1 OR c.`is_root_category` = 1)' : '').'
				'.(isset($groups) && Group::isFeatureActive() ? ' AND cg.`id_group` IN ('.implode(',', $groups).')' : '').'
				'.(!$id_lang || (isset($groups) && Group::isFeatureActive()) ? ' GROUP BY c.`id_category`' : '').'
				'.($sql_sort != '' ? $sql_sort : ' ORDER BY c.`level_depth` ASC').'
				'.($sql_sort == '' && $use_shop_restriction ? ', category_shop.`position` ASC' : '').'
				'.($sql_limit != '' ? $sql_limit : '')
            );

            $categories = array();
            $buff = array();

            foreach ($result as $row) {
                $current = &$buff[$row['id_category']];
                $current = $row;

                if ($row['id_parent'] == 0) {
                    $categories[$row['id_category']] = &$current;
                } else {
                    $buff[$row['id_parent']]['children'][$row['id_category']] = &$current;
                }
            }

            Cache::store($cache_id, $categories);
        }

        return Cache::retrieve($cache_id);
    }

    public static function getLangValueByType($value, $type) {
        if ($type == 'category') {
            $obj = new Category($value);
            if (Validate::isLoadedObject($obj)) {
                return $obj->name;
            }
        } elseif ($type == 'cms-category') {
            $obj = new CMSCategory($value);
            if (Validate::isLoadedObject($obj)) {
                return $obj->name;
            }
        } elseif ($type == 'cms') {
            $obj = new CMS($value);
            if (Validate::isLoadedObject($obj)) {
                return $obj->meta_title;
            }
        } elseif ($type == 'supplier') {
            $obj = new Supplier($value);
            if (Validate::isLoadedObject($obj)) {
                $languages = Language::getLanguages(false);
                $name = array();
                foreach ($languages as $lang)
                {
                    $name[$lang['id_lang']] = $obj->name;
                }

                return $name;
            }
        } elseif ($type == 'manufacturer') {
            $obj = new Manufacturer($value);
            if (Validate::isLoadedObject($obj)) {
                $languages = Language::getLanguages(false);
                $name = array();
                foreach ($languages as $lang)
                {
                    $name[$lang['id_lang']] = $obj->name;
                }

                return $name;
            }
        } elseif ($type == 'shop') {
            $obj = new Shop($value);
            if (Validate::isLoadedObject($obj)) {
                $languages = Language::getLanguages(false);
                $name = array();
                foreach ($languages as $lang)
                {
                    $name[$lang['id_lang']] = $obj->name;
                }

                return $name;
            }
        } elseif ($type == 'product') {
            $obj = new Product($value);
            if (Validate::isLoadedObject($obj)) {
                return $obj->name;
            }
        }

        return array();
    }

    public static function getConfigFieldsValues($fields_form, $obj) {
        $languages = Language::getLanguages(false);
        $fields_values = array();
        foreach ($fields_form as $k => $f)
        {
            foreach ($f['form']['input'] as $j => $input) {
                $fields_values[$input['name']] = $obj->{$input['name']};
            }
        }
        $fields_values['id_dorverticalmenu'] = $obj->id;
        return $fields_values;
    }

}
