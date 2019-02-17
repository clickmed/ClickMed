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

class DorMegamenuWidget extends ObjectModel {

	public $id_dormegamenu_widget;
	public $name;
	public $type;
	public $params;
	public $wkey;
	public $id_shop;

	private $widgets = array();
	public $mod_name = 'dormegamenu';

	public $lang_id = 1;
	public $engines = array();
	public $engine_types = array();
	public $mscript;

	public static $definition = array(
		'table' => 'dormegamenu_widget',
		'primary' => 'id_dormegamenu_widget',
		'fields' => array(
			'name' => array('type' => self::TYPE_STRING, 'validate' => 'isString', 'size' => 255),
			'type' => array('type' => self::TYPE_STRING, 'validate' => 'isString', 'size' => 255),
			'params' => array('type' => self::TYPE_HTML, 'validate' => 'isString'),
			'id_shop' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => true),
			'wkey' => array('type' => self::TYPE_STRING, 'validate' => 'isunsignedInt', 'size' => 11)
		)
	);

	public function __construct($id = null, $id_lang = null, $id_shop = null)
	{
		parent::__construct($id, $id_lang, $id_shop);
		$this->mscript = new DorMegamenuMcrypt();
	}

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

	public function add($autodate = true, $null_values = false) {
        $this->id_shop = Context::getContext()->shop->id;
        if ($this->params) {
			$this->params = $this->mscript->encode(serialize($this->params));
        }
        $this->wkey = time();

        return parent::add($autodate, $null_values);
	}

	public function update($null_values = false) {
        if ($this->params) {
			$this->params = $this->mscript->encode(serialize($this->params));
        }

        return parent::update($null_values);
	}

	public function l($string, $specific = false)
	{
		return Translate::getModuleTranslation($this->mod_name, $string, ($specific) ? $specific : $this->mod_name);
	}

	public function loadEngines()
	{
		if (!$this->engines)
		{
			$wds = glob(dirname(__FILE__).'/widgets/*.php');
			foreach ($wds as $w)
			{
				$paths = explode('/', $w);
				$last = array_pop($paths);
				if ($last != 'index.php')
				{
					require_once($w);
					$f = str_replace('.php', '', basename($w));
					$class = 'DorMegamenuWidget'.Tools::ucfirst($f);

					if (class_exists($class))
					{
						$this->engines[$f] = new $class;
						$this->engines[$f]->id_shop = Context::getContext()->shop->id;
						$this->engines[$f]->lang_id = Context::getContext()->language->id;

						$this->engine_types[$f] = $this->engines[$f]->getWidgetInfo();
						$this->engine_types[$f]['type'] = $f;
					}
				}
			}
		}
	}

	/**
	 * get list of supported widget types.
	 */
	public function getTypes()
	{
		return $this->engine_types;
	}

	/**
	 * get list of widget rows. 
	 */
	public static function getWidgets($idShop=1)
	{

		$sql = ' SELECT * FROM '._DB_PREFIX_.'dormegamenu_widget WHERE `id_shop` = '.(int)Context::getContext()->shop->id;
		return Db::getInstance()->executeS($sql);
	}

	public function deleteItem($id)
	{
		$sql = ' DELETE FROM '._DB_PREFIX_.'dormegamenu_widget WHERE id_dormegamenu_widget='.(int)$id;
		return Db::getInstance()->execute($sql);
	}

	/**
	 * get widget data row by id
	 */
	public function getWidetById($id)
	{
		$output = array(
			'id' => '',
			'id_dormegamenu_widget' => '',
			'name' => '',
			'params' => '',
			'type' => '',
		);
		if (!$id) {
			return $output;
		}
		$sql = ' SELECT * FROM '._DB_PREFIX_.'dormegamenu_widget WHERE id_dormegamenu_widget='.(int)$id;

		$row = Db::getInstance()->getRow($sql);
		if ($row)
		{
			$output = array_merge($output, $row);
			$params = unserialize($this->mscript->decode($output['params']));
			if ($params) {
				foreach ($params as $wkey => $value) {
					$params[$wkey] = htmlspecialchars_decode(Tools::stripslashes($value));
				}
			}
			$output['params'] = $params;
			$output['id'] = $output['id_dormegamenu_widget'];
		}
		return $output;
	}

	/**
	 * get widget data row by id
	 */
	public function getWidetByKey($wkey)
	{
		$output = array(
			'id' => '',
			'id_dormegamenu_widget' => '',
			'name' => '',
			'params' => '',
			'type' => '',
			'wkey' => '',
		);
		if (!$wkey) {
			return $output;
		}
		$sql = ' SELECT * FROM '._DB_PREFIX_.'dormegamenu_widget WHERE 1 AND wkey=\''.pSQL($wkey).'\'';

		$row = Db::getInstance()->getRow($sql);
		if ($row)
		{
			$output = array_merge($output, $row);
			$params = unserialize($this->mscript->decode($output['params']));
			if ($params)
				foreach ($params as $wkey => $value)
					$params[$wkey] = htmlspecialchars_decode(Tools::stripslashes($value));
			$output['params'] = $params;
			$output['id'] = $output['id_dormegamenu_widget'];
		}
		return $output;
	}

	public function getForm($type, $data = array())
	{
		if (isset($this->engines[$type]))
		{
			$this->engines[$type]->types = $this->getTypes();
			return $this->engines[$type]->renderForm($data);
		}
		return $this->l('Sorry, Form Setting is not avairiable for this type');
	}

	public function getWidgetInfo($type)
	{
		if (isset($this->engines[$type]))
			return $this->engines[$type]->getWidgetInfo();
		return null;
	}

	/**
	 *
	 */
	public function getWidgetContent($type, $data)
	{
		$data['widget_heading'] = isset($data['widget_title_'.$this->lang_id]) ? $data['widget_title_'.$this->lang_id] : '';
		if (isset($this->engines[$type]))
			return $this->engines[$type]->renderContent($data);
		return '';
	}

	/**
	 *
	 */
	public function renderContent($id)
	{
		$output = array('id' => $id, 'type' => '', 'data' => '');

		if (isset($this->widgets[$id]))
		{
			$data = unserialize($this->mscript->decode($this->widgets[$id]['params']));
			if ($data)
				foreach ($data as $key => $value)
					$data[$key] = htmlspecialchars_decode(Tools::stripslashes($value));
			$output = $this->getWidgetContent($this->widgets[$id]['type'], $data);
		}
		return $output;
	}

	public function loadWidgets()
	{
		if (empty($this->widgets))
		{
			$widgets = $this->getWidgets();
			foreach ($widgets as $widget)
			{
				$widget['id'] = $widget['id_dormegamenu_widget'];
				$this->widgets[$widget['wkey']] = $widget;
			}
		}
	}

	public function getWidgetsList()
	{
		return $this->widgets;
	}

	public static function renderAdminListWidgets()
    {
        $output = '';
        $widgets = self::getWidgets();
        if ($widgets && count($widgets)) {
            $output = '<ol>';
            foreach ($widgets as &$widget) {
                $output .= '<li id="list_'.$widget['id_dormegamenu_widget'].'">
                    <div class="menu-item-bar">
                        <span class="item-title">
                            <span class="menu-item-title">'.($widget['name'] ? $widget['name'] : '').' (ID:'.$widget['id_dormegamenu_widget'].')</span> 
                        </span>
                        <span class="settings pull-right">
                        	<span class="type">'.$widget['type'].'</span>
                            <span class="quickedit" data-value="'.$widget['id_dormegamenu_widget'].'" title="Edit">
                                <i class="icon-pencil"></i></span>
                            <span class="quickdel" data-value="'.$widget['id_dormegamenu_widget'].'" title="Delete">
                                <i class="icon-trash"></i></span>
                        </span>
                    </div>
                    ';
                $output .= '</li>';
            }
            $output .= '</ol>';
        }
        
        return $output;
    }

    public function getListWidgets()
    {
        $engine_types = $this->engine_types;
        $engines = array_chunk($engine_types, 4);
        $output = '';
        if (!empty($engines)) {
        	foreach ($engines as $engines_t) {
        		$output .= '<div class="row">';
	        	foreach ($engines_t as $key => $value) {
	        		$output .= '<div class="col-sm-3">';
	        		$output .= '<div class="widget-item" data-widget-type="'.$value['type'].'">';
		        	$output .= '<p class="name"><b>'. $value['label'] .'</b></p>';
		        	$output .= '<i class="desc">'. $value['explain'] .'</i>';
		        	$output .= '</div>';
		        	$output .= '</div>';
	        	}
	        	$output .= '</div>';
        	}
        }
        
        return $output;
    }

}