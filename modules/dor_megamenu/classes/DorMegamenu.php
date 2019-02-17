<?php
/*
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2015 PrestaShop SA
*  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

class ObjectDorMegamenu extends ObjectModel
{
    public $id;

    /** @var int megamenu ID */
    public $id_dormegamenu;
    
    public $active;

    public $type;

    public $value;

    public $params;

    /** @var string Name */
    public $name;

    /** @var string Description */
    public $description;

    public $url;

    /** @var int Parent megamenu ID */
    public $id_parent;

    /** @var  int menu position */
    public $position;

    /** @var int Parents number */
    public $level_depth;

    /** @var string Object creation date */
    public $date_add;

    /** @var string Object last modification date */
    public $date_upd;

    protected static $_links = array();

    public $obj_widget;
    public $obj_module;
    public $obj_link;
    public $lang_id;
    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'dormegamenu',
        'primary' => 'id_dormegamenu',
        'multilang' => true,
        //'multilang_shop' => true,
        'fields' => array(
            'active' =>            array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true),
            'id_parent' =>            array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true),
            'position' =>            array('type' => self::TYPE_INT),
            'level_depth' =>        array('type' => self::TYPE_INT),
            'date_add' =>            array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
            'date_upd' =>            array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
            'type' =>            array('type' => self::TYPE_STRING, 'validate' => 'isCatalogName'),
            'value' =>       array('type' => self::TYPE_STRING, 'validate' => 'isCatalogName'),
            'params' =>       array('type' => self::TYPE_STRING, 'validate' => 'isString'),

            /* Lang fields */
            'name' =>                array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCatalogName', 'required' => true),
            'url' =>        array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isUrl'),
            'description' =>        array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCleanHtml'),
        ),
    );
    
    public function copyFromPost()
    {
        /* Classical fields */
        $posts = Tools::getAllValues();
        foreach ($posts as $key => $value) {
            if (key_exists($key, $this) && $key != 'id_'.$this->table) {
                $this->{$key} = $value;
            }
        }

        /* Multilingual fields */
        if (count($this->fieldsValidateLang) > 0)
        {
            $languages = Language::getLanguages(false);
            foreach ($languages as $language) {
                foreach ($this->fieldsValidateLang as $field => $validation) {
                    if (Tools::getIsset($field.'_'.(int)$language['id_lang'])) {
                        $this->{$field}[(int)$language['id_lang']] = Tools::getValue($field.'_'.(int)$language['id_lang']);
                    }
                }
            }
        }
    }

    public function add($autodate = true, $null_values = false)
    {
        $this->position = ObjectDorMegamenu::getLastPosition((int)$this->id_parent);
        $this->level_depth = $this->calcLevelDepth();
        
        $ret = parent::add($autodate, $null_values);
        $id_shop = Context::getContext()->shop->id;
        $ret &= Db::getInstance()->execute('
            INSERT INTO `'._DB_PREFIX_.'dormegamenu_shop` (`id_shop`, `id_dormegamenu`)
            VALUES('.(int)$id_shop.', '.(int)$this->id.')'
        );
        $this->cleanPositions($this->id_parent);
        return $ret;
    }

    public function update($null_values = false)
    {
        $this->level_depth = $this->calcLevelDepth();
        
        return parent::update($null_values);
    }

    public function parserParams($params) {
        $data = array(
            'params' => '',
            'target' => '_self',
            'sticky_lable' => '',
            'icon_class' => '',
            'addition_class' => '',
            'menu_background' => '',
            'submenu_align' => '',
            'submenu_width' => 300
        );
        if (!empty($params)) {
            $mscript = new DorMegamenuMcrypt();
            $data = unserialize($mscript->decode($params));
        }
        return $data;
    }
    /**
     * Recursive scan of submenus
     *
     * @param int $max_depth Maximum depth of the tree (i.e. 2 => 3 levels depth)
     * @param int $currentDepth specify the current depth in the tree (don't use it, only for rucursivity!)
     * @param array $excluded_ids_array specify a list of ids to exclude of results
     * @param int $idLang Specify the id of the language used
     *
     * @return array Submenus lite tree
     */
    public function recurseLiteMenuTree($max_depth = 3, $currentDepth = 0, $id_lang = null, $excluded_ids_array = null, Link $link = null)
    {
        if (!$link) {
            $link = Context::getContext()->link;
        }

        if (is_null($id_lang)) {
            $id_lang = Context::getContext()->language->id;
        }

        // recursivity for submenus
        $children = array();
        $subcats = $this->getSubMenus($id_lang, true);
        if (($max_depth == 0 || $currentDepth < $max_depth) && $subcats && count($subcats)) {
            foreach ($subcats as &$subcat) {
                if (!$subcat['id_dormegamenu']) {
                    break;
                } elseif (!is_array($excluded_ids_array) || !in_array($subcat['id_dormegamenu'], $excluded_ids_array)) {
                    $categ = new ObjectDorMegamenu($subcat['id_dormegamenu'], $id_lang);
                    $categ->name = $categ->name;
                    $children[] = $categ->recurseLiteMenuTree($max_depth, $currentDepth + 1, $id_lang, $excluded_ids_array);
                }
            }
        }

        return array(
            'id' => $this->id_dormegamenu,
            //'link' => $link->getObjectDorMegamenuLink($this->id, $this->link_rewrite),
            'name' => $this->name,
            'desc'=> $this->description,
            'children' => $children
        );
    }

    public function recurseGetAdminMenuTree($max_depth = 4, $currentDepth = 0, $id_lang = null, $excluded_ids_array = null, Link $link = null)
    {
        if (is_null($id_lang)) {
            $id_lang = Context::getContext()->language->id;
        }
        $output = '';
        $subcats = $this->getSubMenus($id_lang, false);
        if (($max_depth == 0 || $currentDepth < $max_depth) && $subcats && count($subcats)) {
            $t = $currentDepth == 0 ? ' sortable' : '';
            $output = '<ol class="level_'.$currentDepth.$t.'">';
            foreach ($subcats as &$subcat) {
                if (!$subcat['id_dormegamenu']) {
                    break;
                } elseif (!is_array($excluded_ids_array) || !in_array($subcat['id_dormegamenu'], $excluded_ids_array)) {
                    
                    $output .= '<li id="list_'.$subcat['id_dormegamenu'].'">
                        <div class="menu-item-bar">
                            <span class="item-title">
                                <span class="menu-item-title">'.($subcat['name'] ? $subcat['name'] : '').' (ID:'.$subcat['id_dormegamenu'].')</span> 
                                <span class="sub-setting" data-value="'.$subcat['id_dormegamenu'].'"><i class="icon-cogs"> Submenu Settings</i></span> 
                            </span>
                            <span class="settings pull-right">
                                <span class="status">'.($subcat['active'] ? '<i class="icon-check"></i>' : '<i class="icon-remove"></i>').'</span>
                                <span class="type">'.$subcat['type'].'</span>
                                <span class="quickedit" data-value="'.$subcat['id_dormegamenu'].'" title="Edit">
                                    <i class="icon-pencil"></i></span>
                                <span class="quickdel" data-value="'.$subcat['id_dormegamenu'].'" title="Delete">
                                    <i class="icon-trash"></i></span>
                            </span>
                        </div>
                        ';

                    $categ = new ObjectDorMegamenu($subcat['id_dormegamenu'], $id_lang);
                    $output .= $categ->recurseGetAdminMenuTree($max_depth, $currentDepth + 1, $id_lang, $excluded_ids_array);

                    $output .= '</li>';
                }
            }
            $output .= '</ol>';
        }
        
        return $output;
    }

    public static function getRecurseMenu($id_lang = null, $current = 1, $active = 1, $links = 0, Link $link = null)
    {
        if (!$link) {
            $link = Context::getContext()->link;
        }
        if (is_null($id_lang)) {
            $id_lang = Context::getContext()->language->id;
        }

        $sql = 'SELECT c.`id_dormegamenu`, c.`id_parent`, c.`level_depth`, cl.`name`, cl.`link_rewrite`
				FROM `'._DB_PREFIX_.'dormegamenu` c
				JOIN `'._DB_PREFIX_.'dormegamenu_lang` cl ON c.`id_dormegamenu` = cl.`id_dormegamenu`
					WHERE c.`id_dormegamenu` = '.(int)$current.'
					AND `id_lang` = '.(int)$id_lang;
        $menu = Db::getInstance()->getRow($sql);

        $sql = 'SELECT c.`id_dormegamenu`
				FROM `'._DB_PREFIX_.'dormegamenu` c
				WHERE c.`id_parent` = '.(int)$current.
                    ($active ? ' AND c.`active` = 1' : '');
        $result = Db::getInstance()->executeS($sql);
        foreach ($result as $row) {
            $menu['children'][] = ObjectDorMegamenu::getRecurseMenu($id_lang, $row['id_dormegamenu'], $active, $links);
        }

        return $menu;
    }

    public static function recurseObjectDorMegamenu($menus, $current, $id_dormegamenu = 1, $id_selected = 1, $is_html = 0)
    {
        $html = '<option value="'.$id_dormegamenu.'"'.(($id_selected == $id_dormegamenu) ? ' selected="selected"' : '').'>'
            .str_repeat('&nbsp;', $current['infos']['level_depth'] * 5)
            .ObjectDorMegamenu::hideObjectDorMegamenuPosition(stripslashes($current['infos']['name'])).'</option>';
        if ($is_html == 0) {
            echo $html;
        }
        if (isset($menus[$id_dormegamenu])) {
            foreach (array_keys($menus[$id_dormegamenu]) as $key) {
                $html .= ObjectDorMegamenu::recurseObjectDorMegamenu($menus, $menus[$id_dormegamenu][$key], $key, $id_selected, $is_html);
            }
        }
        return $html;
    }

    /**
     * Recursively add specified ObjectDorMegamenu childs to $toDelete array
     *
     * @param array &$toDelete Array reference where menus ID will be saved
     * @param array|int $id_dormegamenu Parent ObjectDorMegamenu ID
     */
    protected function recursiveDelete(&$to_delete, $id_dormegamenu)
    {
        if (!is_array($to_delete) || !$id_dormegamenu) {
            die(Tools::displayError());
        }

        $result = Db::getInstance()->executeS('
		SELECT `id_dormegamenu`
		FROM `'._DB_PREFIX_.'dormegamenu`
		WHERE `id_parent` = '.(int)$id_dormegamenu);
        foreach ($result as $row) {
            $to_delete[] = (int)$row['id_dormegamenu'];
            $this->recursiveDelete($to_delete, (int)$row['id_dormegamenu']);
        }
    }

    public function delete()
    {
        if ($this->id == 1) {
            return false;
        }

        $this->clearCache();

        // Get children menus
        $to_delete = array((int)$this->id);
        $this->recursiveDelete($to_delete, (int)$this->id);
        $to_delete = array_unique($to_delete);

        // Delete Menu and its child from database
        $list = count($to_delete) > 1 ? implode(',', $to_delete) : (int)$this->id;
        $id_shop_list = Shop::getContextListShopID();
        if (count($this->id_shop_list)) {
            $id_shop_list = $this->id_shop_list;
        }

        Db::getInstance()->delete($this->def['table'].'_shop', '`'.$this->def['primary'].'` IN ('.$list.')');

        $has_multishop_entries = $this->hasMultishopEntries();
        if (!$has_multishop_entries) {
            Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'dormegamenu` WHERE `id_dormegamenu` IN ('.$list.')');
            Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'dormegamenu_lang` WHERE `id_dormegamenu` IN ('.$list.')');
        }

        ObjectDorMegamenu::cleanPositions($this->id_parent);

        return true;
    }

    /**
     * Delete several menus from database
     *
     * return boolean Deletion result
     */
    public function deleteSelection($menus)
    {
        $return = 1;
        foreach ($menus as $id_dormegamenu) {
            $menu = new ObjectDorMegamenu($id_dormegamenu);
            $return &= $menu->delete();
        }
        return $return;
    }

    /**
     * Get the number of parent menus
     *
     * @return int Level depth
     */
    public function calcLevelDepth()
    {
        $parentObjectDorMegamenu = new ObjectDorMegamenu($this->id_parent);
        if (!$parentObjectDorMegamenu) {
            die('parent Menu does not exist');
        }
        return $parentObjectDorMegamenu->level_depth + 1;
    }

    /**
     * Return available menus
     *
     * @param int $id_lang Language ID
     * @param bool $active return only active menus
     * @return array Menus
     */
    public static function getMenus($id_lang, $active = true, $order = true)
    {
        if (!Validate::isBool($active)) {
            die(Tools::displayError());
        }

        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
		SELECT *
		FROM `'._DB_PREFIX_.'dormegamenu` c
		LEFT JOIN `'._DB_PREFIX_.'dormegamenu_lang` cl ON c.`id_dormegamenu` = cl.`id_dormegamenu`
		WHERE `id_lang` = '.(int)$id_lang.'
		'.($active ? 'AND `active` = 1' : '').'
		ORDER BY `name` ASC');

        if (!$order) {
            return $result;
        }

        $menus = array();
        foreach ($result as $row) {
            $menus[$row['id_parent']][$row['id_dormegamenu']]['infos'] = $row;
        }
        return $menus;
    }

    public static function getSimpleMenus($id_lang)
    {
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
		SELECT c.`id_dormegamenu`, cl.`name`
		FROM `'._DB_PREFIX_.'dormegamenu` c
		LEFT JOIN `'._DB_PREFIX_.'dormegamenu_lang` cl ON (c.`id_dormegamenu` = cl.`id_dormegamenu`)
		WHERE cl.`id_lang` = '.(int)$id_lang.'
		ORDER BY cl.`name`');
    }

    /**
     * Return current ObjectDorMegamenu childs
     *
     * @param int $id_lang Language ID
     * @param bool $active return only active menus
     * @return array Menus
     */
    public function getSubMenus($id_lang, $active = true, $orderby = 'position', $orderway = 'ASC')
    {
        if (!Validate::isBool($active)) {
            die(Tools::displayError());
        }
        $id_shop = Context::getContext()->shop->id;
        $sqlSubMenu = '
        SELECT c.*, cl.id_lang, cl.name, cl.description, cl.url
        FROM `'._DB_PREFIX_.'dormegamenu` c
        LEFT JOIN `'._DB_PREFIX_.'dormegamenu_lang` cl ON (c.`id_dormegamenu` = cl.`id_dormegamenu` AND `id_lang` = '.(int)$id_lang.')
        LEFT JOIN `'._DB_PREFIX_.'dormegamenu_shop` cs ON (c.`id_dormegamenu` = cs.`id_dormegamenu` AND `id_shop` = '.(int)$id_shop.')
        WHERE `id_parent` = '.(int)$this->id.'
            AND cs.`id_shop` = '.(int)$id_shop.'
        '.($active ? 'AND `active` = 1' : '').'
        GROUP BY c.`id_dormegamenu`
        ORDER BY `'.pSQL($orderby).'` '.pSQL($orderway);
        //echo $sqlSubMenu;die;
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sqlSubMenu);

        return $result;
    }

    public static function getChildren($id_parent, $id_lang, $active = true, $orderby = 'position', $orderway = 'ASC')
    {
        if (!Validate::isBool($active)) {
            die(Tools::displayError());
        }

        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
		SELECT c.*, cl.*
		FROM `'._DB_PREFIX_.'dormegamenu` c
		LEFT JOIN `'._DB_PREFIX_.'dormegamenu_lang` cl ON c.`id_dormegamenu` = cl.`id_dormegamenu`
		WHERE `id_lang` = '.(int)$id_lang.'
		AND c.`id_parent` = '.(int)$id_parent.'
		'.($active ? 'AND `active` = 1' : '').'
		ORDER BY `'.pSQL($orderby).'` '.pSQL($orderway));

        // Modify SQL result
        $results_array = array();
        foreach ($result as $row) {
            $row['name'] = $row['name'];
            $results_array[] = $row;
        }
        return $results_array;
    }

    /**
     * Check if ObjectDorMegamenu can be moved in another one
     *
     * @param int $id_parent Parent candidate
     * @return bool Parent validity
     */
    public static function checkBeforeMove($id_dormegamenu, $id_parent)
    {
        if ($id_dormegamenu == $id_parent) {
            return false;
        }
        if ($id_parent == 1) {
            return true;
        }
        $i = (int)$id_parent;

        while (42) {
            $result = Db::getInstance()->getRow('SELECT `id_parent` FROM `'._DB_PREFIX_.'dormegamenu` WHERE `id_dormegamenu` = '.(int)$i);
            if (!isset($result['id_parent'])) {
                return false;
            }
            if ($result['id_parent'] == $id_dormegamenu) {
                return false;
            }
            if ($result['id_parent'] == 1) {
                return true;
            }
            $i = $result['id_parent'];
        }
    }

    public function getName($id_lang = null)
    {
        $context = Context::getContext();
        if (!$id_lang) {
            if (isset($this->name[$context->language->id])) {
                $id_lang = $context->language->id;
            } else {
                $id_lang = (int)Configuration::get('PS_LANG_DEFAULT');
            }
        }
        return isset($this->name[$id_lang]) ? $this->name[$id_lang] : '';
    }

    /**
      * Get Each parent ObjectDorMegamenu of this ObjectDorMegamenu until the root ObjectDorMegamenu
      *
      * @param int $id_lang Language ID
      * @return array Corresponding menus
      */
    public function getParentsMenus($id_lang = null)
    {
        if (is_null($id_lang)) {
            $id_lang = Context::getContext()->language->id;
        }

        $menus = null;
        $id_current = $this->id;
        while (true) {
            $query = '
				SELECT c.*, cl.*
				FROM `'._DB_PREFIX_.'dormegamenu` c
				LEFT JOIN `'._DB_PREFIX_.'dormegamenu_lang` cl ON (c.`id_dormegamenu` = cl.`id_dormegamenu` AND `id_lang` = '.(int)$id_lang.')
				WHERE c.`id_dormegamenu` = '.(int)$id_current.' AND c.`id_parent` != 0
			';
            $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);

            $menus[] = $result[0];
            if (!$result || $result[0]['id_parent'] == 1) {
                return $menus;
            }
            $id_current = $result[0]['id_parent'];
        }
    }

    public function updatePosition($way, $position)
    {
        if (!$res = Db::getInstance()->executeS('
			SELECT cp.`id_dormegamenu`, cp.`position`, cp.`id_parent`
			FROM `'._DB_PREFIX_.'dormegamenu` cp
			WHERE cp.`id_parent` = '.(int)$this->id_parent.'
			ORDER BY cp.`position` ASC'
        )) {
            return false;
        }
        foreach ($res as $menu) {
            if ((int)$menu['id_dormegamenu'] == (int)$this->id) {
                $moved_menu = $menu;
            }
        }

        if (!isset($moved_menu) || !isset($position)) {
            return false;
        }
        // < and > statements rather than BETWEEN operator
        // since BETWEEN is treated differently according to databases
        return (Db::getInstance()->execute('
			UPDATE `'._DB_PREFIX_.'dormegamenu`
			SET `position`= `position` '.($way ? '- 1' : '+ 1').'
			WHERE `position`
			'.($way
                ? '> '.(int)$moved_menu['position'].' AND `position` <= '.(int)$position
                : '< '.(int)$moved_menu['position'].' AND `position` >= '.(int)$position).'
			AND `id_parent`='.(int)$moved_menu['id_parent'])
        && Db::getInstance()->execute('
			UPDATE `'._DB_PREFIX_.'dormegamenu`
			SET `position` = '.(int)$position.'
			WHERE `id_parent` = '.(int)$moved_menu['id_parent'].'
			AND `id_dormegamenu`='.(int)$moved_menu['id_dormegamenu']));
    }

    public static function cleanPositions($id_menu_parent)
    {
        $result = Db::getInstance()->executeS('
		SELECT `id_dormegamenu`
		FROM `'._DB_PREFIX_.'dormegamenu`
		WHERE `id_parent` = '.(int)$id_menu_parent.'
		ORDER BY `position`');
        $sizeof = count($result);
        for ($i = 0; $i < $sizeof; ++$i) {
            $sql = '
			UPDATE `'._DB_PREFIX_.'dormegamenu`
			SET `position` = '.(int)$i.'
			WHERE `id_parent` = '.(int)$id_menu_parent.'
			AND `id_dormegamenu` = '.(int)$result[$i]['id_dormegamenu'];
            Db::getInstance()->execute($sql);
        }
        return true;
    }

    public static function getLastPosition($id_menu_parent)
    {
        return (Db::getInstance()->getValue('SELECT MAX(position)+1 FROM `'._DB_PREFIX_.'dormegamenu` WHERE `id_parent` = '.(int)$id_menu_parent));
    }

    public function renderMegaMenu($obj_widget, $id_lang = null) {

        $this->obj_widget = new DorMegamenuWidget();
        $this->obj_module = $obj_widget;
        $this->obj_widget->loadWidgets();
        $this->obj_widget->loadEngines();
        $this->obj_link = Context::getContext()->link;
        $this->id_shop = Context::getContext()->shop->id;
        if (is_null($id_lang)) {
            $id_lang = Context::getContext()->language->id;
        }
        $this->lang_id = $id_lang;
        $output = '';
        $subcats = $this->getSubMenus($id_lang, true);
        //echo "<pre>";print_r($subcats);die;
        if ($subcats && count($subcats)) {
            $output = '<ul class="nav navbar-nav megamenu">';
            foreach ($subcats as $menu) {
                $configs = $this->parserParams($menu['params']);
                $has_submenu = false;
                $submenu_attr = '';
                if (isset($configs['params']) && $configs['params']) {
                    $has_submenu = true;
                    $submenu_attr = ' class="dropdown-toggle" data-toggle="dropdown" ';
                } else {
                    $submenus = $this->getChildren($menu['id_dormegamenu'], $id_lang);
                    if (!empty($submenus)) {
                        $has_submenu = true;
                        $submenu_attr = ' class="dropdown-toggle" data-toggle="dropdown" ';
                    }
                }
                $menu_link = $this->getLink($menu);
                $output .= '<li class="'.$configs['addition_class'].' '.($has_submenu ? 'parent dropdown aligned-'.$configs['submenu_align'] : '').(isset($menu['addition_class']) ? ' '.$menu['addition_class'] : '').'">';
                $output .= '<a'.$submenu_attr.' target="'.$configs['target'].'" data-rel="'.$menu['id_dormegamenu'].'" href="'.$menu_link.'">';

                if ($configs['icon_class']) {
                    $output .= '<span class="menu-icon"><i class="'.$configs['icon_class'].'"></i></span>';
                }
                $output .= '<span class="menu-title">'.$menu['name'].'</span>';
                if ($menu['description']) {
                    $output .= '<span class="menu-desc">'.$menu['description'].'</span>';
                }
                if ($configs['sticky_lable']) {
                    $output .= '<span class="menu-label-'.$configs['sticky_lable'].'">'.(isset($menu['sticky_lable']) ? $menu['sticky_lable'] : $configs['sticky_lable']).'</span>';
                }
                if ($has_submenu) {
                    $output .= '<b class="caret"></b>';
                }

                $output .= '</a>';
                if ($has_submenu) {
                    $output .= '<span class="caretmobile hidden"></span>';
                }
                // sub menu
                if ($has_submenu) {
                    if (isset($configs['params']) && $configs['params']) {
                        $params = Tools::jsonDecode($configs['params']);
                        $output .= $this->renderSubMegaMenu($params, $configs);
                    } elseif (!empty($submenus)) {
                        
                        $output .= $this->renderSubMenu($submenus, $configs);
                                
                    }
                    
                }
                

                $output .= '</li>';
            }
            $output .= '</ul>';
        }
        
        return $output;
    }

    public function renderSubMegaMenu($params, $pconfigs) {
        if($pconfigs['menu_background'] != "")
            $style = "background-image:url(".$pconfigs['menu_background'].");background-repeat:no-repeat;width:".$pconfigs['submenu_width'].'px;';
        else
            $style = "width:".$pconfigs['submenu_width'].'px;';
        $output = '';
        $output .= '<ul class="dropdown-menu level1 megamenu-content" role="menu" style="'.$style.'">';
        $output .= '<li>';
        foreach ($params->rows as $row) {
            $output .= '<div class="row">';
                if (!empty($row->cols)) {
                    foreach ($row->cols as $col) {
                        $output .= '<div class="col-sm-'.(isset($col->col_width) ? $col->col_width : 4).'">';
                            if (isset($col->widget_key) && $col->widget_key) {
                                $output .= $this->renderWidgetContent($col->widget_key);
                            }
                        $output .= '</div>';
                    }
                }
            $output .= '</div>';
        }
        $output .= '</li>';
        $output .= '</ul>';
        return $output;
    }
    
    public function renderSubMenu($menus, $pconfigs, $level = 1) {
        $output = '';
        $output .= '<ul class="dropdown-menu level'.$level.'" role="menu" style="width:'.$pconfigs['submenu_width'].'px;">';
        //$output .= '<li class="dor-megamenu-content">';
        // $output .= '<div class="row">';
        // $output .= '<div class="mega-col col-sm-12">';
        //$output .= '<div class="mega-col-inner">';
        //$output .= '<ul>';
        foreach ($menus as $menu) {
            $configs = $this->parserParams($menu['params']);
            $submenus = $this->getChildren($menu['id_dormegamenu'], $this->lang_id);
            $submenu_attr = '';
            $has_submenu = false;
            if (!empty($submenus)) {
                $has_submenu = true;
                $submenu_attr = ' class="parent dropdown-submenu" ';
            }

            $menu_link = $this->getLink($menu);
            $output .= '<li'.$submenu_attr.'>';
                $output .= '<a'.($has_submenu ? ' class="dropdown-toggle" data-toggle="dropdown"' : '').' target="'.$configs['target'].'" href="'.$menu_link.'">';
                    if ($configs['icon_class']) {
                        $output .= '<span class="menu-icon"><i class="'.$configs['icon_class'].'"></i></span>';
                    }
                    $output .= '<span class="menu-title">'.$menu['name'].'</span>';
                    if ($menu['description']) {
                        $output .= '<span class="menu-desc">'.$menu['description'].'</span>';
                    }
                    if ($configs['sticky_lable']) {
                        $output .= '<span class="menu-label-'.$configs['sticky_lable'].'">'.(isset($menu['sticky_lable']) ? $menu['sticky_lable'] : $configs['sticky_lable']).'</span>';
                    }
                    if ($has_submenu) {
                        $output .= '<b class="caret"></b>';
                    }
                $output .= '</a>';
                if ($has_submenu) {
                    $slevel = $level + 1;
                    $output .= $this->renderSubMenu($submenus, $configs, $slevel);
                }
            $output .= '</li>';
        }
        //$output .= '</ul>';
        //$output .= '</div>';
        // $output .= '</div>';
        // $output .= '</div>';
        //$output .= '</li>';
        $output .= '</ul>';
        return $output;
    }

    public function renderWidgetContent($widget_key) {
        $data = $this->obj_widget->getWidetByKey($widget_key);
        $content = $this->obj_widget->renderContent($widget_key);
        return $this->obj_module->renderWidgetContent($content['type'], $content['data']);
    }

    public function getLink($menu)
    {
        $link = Context::getContext()->link;

        $isoLang = isset(Context::getContext()->language->iso_code) && Context::getContext()->language->iso_code != ""?Context::getContext()->language->iso_code.'/':"";
        $value = (int)$menu['value'];
        $result = '';
        switch ($menu['type'])
        {
            case 'product':
                $result = Tools::HtmlEntitiesUTF8($link->getProductLink( (int)$value ));
            break;
            case 'category':
                $result = Tools::HtmlEntitiesUTF8($link->getCategoryLink( (int)$value ));
            break;
            case 'cms-category':
                $result = Tools::HtmlEntitiesUTF8($link->getCMSCategoryLink((int)$value));
            break;
            case 'cms':
                $result = Tools::HtmlEntitiesUTF8($link->getCMSLink((int)$value));
            break;
            case 'url':
                $value = $menu['url'];
                if ($value == '#') {
                    $result = $value;
                } else {
                    $result = Tools::HtmlEntitiesUTF8($value);
                }
            break;
            case 'manufacturer':
                $result = Tools::HtmlEntitiesUTF8($link->getManufacturerLink( (int)$value ));
            break;
            case 'supplier':
                $result = Tools::HtmlEntitiesUTF8($link->getSupplierLink((int)$value));
            break;
            case 'shop':
                $shop = new Shop((int)$value);
                if (Validate::isLoadedObject($shop)) {
                    $result = Tools::HtmlEntitiesUTF8($shop->getBaseURL());
                }
            break;
            default:
                $typeCus = Tools::strtolower(Tools::substr(strrchr($menu['url'], '#/'), 1));
                $typeCus = substr($typeCus, 1);
                $result = $menu['url'] != ""?($menu['url']=='#/home'?__PS_BASE_URI__.$isoLang:($typeCus != ""?__PS_BASE_URI__.$isoLang.$typeCus:$menu['url'])):"#";
            break;
        }
        return $result;
    }
}
