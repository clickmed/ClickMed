<?php 
/**
 * $ModDesc
 * 
 * @version   $Id: file.php $Revision
 * @package   modules
 * @subpackage  $Subpackage.
 * @copyright Copyright (C) November 2010 LandOfCoder.com <@emai:landofcoder@gmail.com>.All rights reserved.
 * @license   GNU General Public License version 2
 */
if( !class_exists('Params', false) ){
class Params{
    /**
    * @var string name ;
    *
    * @access public;
    */
    public  $name = '';
    public  $module = '';
    public  $prefix = '';
    private $_languages = array();
	
    /**
    * @var string name ;
    *
    * @protected public;
    */

    protected $_data= array();
  
	/**
	 * Constructor
    */
	public function Params( $prefix, $module ){
		$this->name  = $prefix;
		$this->module  = $module;
	}
    
    public function renderName($key){
        return strtoupper($this->name.'_'.$key);
    }
	/**
	 * Get configuration's value
	 */
	public function get($key, $id_lang = null, $id_shop_group = null, $id_shop = null) {			
		$name = $this->renderName($key);
    	return ConfigurationCore::get($name, $id_lang, $id_shop_group, $id_shop);
	}
  
	/**
	 * Store configuration's value as temporary.
	 */
	public function set($key, $values, $id_shop_group = null, $id_shop = null){
        $name = $this->renderName($key);
    	return ConfigurationCore::get($name, $values, $id_shop_group, $id_shop);
    }
  
	/**
	 * Update value for single configuration.
	 */
	public function update( $key, $lang = true ){
        $name = $this->renderName($key);
        $values = Tools::getValue($key);
        if($lang){
            if(!$this->_languages || count($this->_languages) == 0)
                $this->_languages = Language::getLanguages(false);
            foreach($this->_languages as $language){
                $values[$language['id_lang']] = Tools::getValue($key.'_'.$language['id_lang']);
            }
        }
		Configuration::updateValue($name, $values, true);
	}

    public function refreshConfig(){
        foreach( $this->_data as $key => $value ){
            $this->_data[$key] = Configuration::get( $key );
        }
        return $this;
    }
    /**
    * Update value for list of configurations.
    */
    public function batchUpdate( $configurations = array() ){
        $return = true;
        foreach($configurations as $key => $val){
            $name = $this->renderName($key);
            $values = Tools::getValue($key, $val);
            if(is_array($val)){
                if(!$this->_languages || count($this->_languages) == 0)
                    $this->_languages = Language::getLanguages(false);
                foreach($this->_languages as $language){
                    $values[$language['id_lang']] = Tools::getValue($key.'_'.$language['id_lang'], (isset($val[$language['id_lang']]) ? $val[$language['id_lang']] : ''));
                }
            }elseif(is_array($values)){
                $values = implode(',', $values);
            }
            $return &= Configuration::updateValue($name, $values, true);
        }
        return $return;
    }
    
    public function getConfigFieldsValues($configs){
        $return = array();
        foreach($configs as $key => $val){
            if(!is_array($val)) {
                $return[$key] = $this->get($key);
            } else {
                if(!$this->_languages || count($this->_languages) == 0)
                    $this->_languages = Language::getLanguages(false);
                $values = array();
                foreach($this->_languages as $lang){
                    $values[$lang['id_lang']] = $this->get($key, $lang['id_lang']);
                }
                $return[$key] = $values;
            }
        }
        return $return;
    }

    /**
     * add swicht tags(radio)
     * @param type $name
     * @param type $title
     * @param type $des
     * @return type array
     */
    public function switchTags($name, $title, $des = ''){
        return array(
            'type' => 'switch',
            'label' => ($title ? $this->module->l($title) : ''),
            'name' => $name,
            'class' => 't',
            'is_bool' => true,
            'values' => array(
                array(
                    'id' => $name.'_on',
                    'value' => 1,
                    'label' => $this->module->l('Yes')
                ),
                array(
                    'id' => $name.'_off',
                    'value' => 0,
                    'label' => $this->module->l('No')
                    )
            ),
            'desc' => ($des ? $this->module->l($des) : ''),
        );
    }

    public function inputTags($name, $title, $lang = false, $des = '', $class = '', $suffix='') {
        $return = array(
            'type' => 'text',
            'label' => ($title ? $this->module->l($title) : ''),
            'name' => $name,
            'lang' => $lang,
            'class' => $class,
            'desc' => ($des ? $this->module->l($des) : ''),

        );
        if($suffix){
            $return['suffix'] = $suffix;
        }
        return $return;
    }
    
    public function textareaTags($name, $title, $lang = false, $text_editor = false, $rows = 5, $cols = 40, $des = '', $class = '') {
        return array(
            'type' => 'textarea',
            'label' => ($title ? $this->module->l($title) : ''),
            'name' => $name,
            'class' => $class,
            'autoload_rte' => $text_editor,
            'lang' => $lang,
            'rows' => $rows,
            'cols' => $cols,
            'desc' => ($des ? $this->module->l($des) : '')
        );
    }
    
    public function colorTags($name, $title, $des = '', $class = ''){
        return array(
            'type' => 'color',
            'label' => ($title ? $this->module->l($title) : ''),
            'name' => $name,
            'class' => $class,
            'desc' => $des,
            'hint' => ($title ? $this->module->l($title) : '').' '.$this->module->l('will be highlighted in this color. (HTML colors only)').' "lightblue", "#CC6600")'
        );
    }

    public function selectTags($name, $title, $queries, $option_key = array('id' => 'id', 'name' => 'name'), $default = false, $multiple = false, $des = '', $class = 'col-lg-5'){
        $return = array(
            'type' => 'select',
            'label' => ($title ? $this->module->l($title) : ''),
            'name' => $name.($multiple ? '[]' : ''),
            'id' => $name,
            'class' => $class,
            'options' => array(
                'query' => $queries,
                'id' => $option_key['id'],
                'name' => $option_key['name']
            ),
            'desc' => ($des ? $this->module->l($des) : ''),
        );
        if($default && is_array($default)){
            $return['options']['default'] = array( 'value' => (isset($default['value']) ? $default['value'] : ''), 
                                                   'label' => (isset($default['label']) ? $this->module->l($default['label']) : ''));
        }
        if($multiple)
            $return['multiple'] = $multiple;
        return $return;
    }
    
    public function fileTags($name, $title, $display_img = true, $img_url = false, $image_size = '', $delete_url = '', $hint = '', $des = ''){
        return array(
            'type' => 'file',
            'label' => ($title ? $this->module->l($title) : ''),
            'name' => $name,
            'display_image' => $display_img,
            'image' => $img_url ? $img_url : false,
            'size' => $image_size,
            'delete_url' => $delete_url,
            'hint' => ($hint ? $this->module->l($hint) : ''),
            'desc' => ($des ? $this->module->l($des) : ''),
            'lang' => true
        );
    }
    
    public function tagTags($name, $title, $lang = true, $des = ''){
        return array(
            'type' => 'tags',
            'label' => ($title ? $this->module->l($title) : ''),
            'name' => $name,
            'lang' => $lang,
            'hint' => $this->module->l('To add "tags," click in the field, write something, and then press "Enter."').'&nbsp;'.$this->module->l('Forbidden characters:').' <>;=#{}',
            'desc' => $des
        );
    }

    public function categoryTags($name, $title, $selected_categories = array(), $des = '') {
        return array(
            'type'  => 'categories',
            'label' => ($title ? $this->module->l($title) : ''),
            'name'  => $name,
            'desc' => $des,
            'tree'  => array(
                'id'                  => 'categories-tree',
                'selected_categories' => $selected_categories
            )
        );
    }

    public function categorySelectTags($name, $title, $selected_categories = '', $des = '') {
        $categories = array();
        if($selected_categories)
            $categories = explode (',', $selected_categories);
        $root = Category::getRootCategory();
        $tree = new HelperTreeCategories('associated-categories-tree', 'Categories');
        $tree->setRootCategory($root->id)
            ->setUseCheckBox(true)
            ->setUseSearch(true)
            ->setSelectedCategories($categories);
        $category_tpl = $tree->render();
        return array(
            'type'  => 'categories_select',
            'label' => ($title ? $this->module->l($title) : ''),
            'name'  => $name,
            'desc' => $des,
            'category_tree'  =>  $category_tpl,
        );
    }
    
    public function groupTags($name, $title){
        $context = Context::getContext();
        $unidentified = new Group(Configuration::get('PS_UNIDENTIFIED_GROUP'));
        $guest = new Group(Configuration::get('PS_GUEST_GROUP'));
        $default = new Group(Configuration::get('PS_CUSTOMER_GROUP'));

        $unidentified_group_information = sprintf($this->module->l('%s - All people without a valid customer account.'), '<b>'.$unidentified->name[$context->language->id].'</b>');
        $guest_group_information = sprintf($this->module->l('%s - Customer who placed an order with the guest checkout.'), '<b>'.$guest->name[$context->language->id].'</b>');
        $default_group_information = sprintf($this->module->l('%s - All people who have created an account on this site.'), '<b>'.$default->name[$context->language->id].'</b>');
        
        return array(
            'type' => 'group',
            'label' => ($title ? $this->module->l($title) : ''),
            'name' => $name,
            'values' => Group::getGroups($context->language->id),
            'info_introduction' => $this->module->l('You now have three default customer groups.'),
            'unidentified' => $unidentified_group_information,
            'guest' => $guest_group_information,
            'customer' => $default_group_information,
            'hint' => $this->module->l('Mark all of the customer groups you;d like to have access to this category.')
        );
    }
    
    public function getFolderList( $path ) {
		$items = array();
		$handle = opendir($path);
		if (! $handle) {
			return $items;
		}
		while (false !== ($file = readdir($handle))) {
			if (is_dir($path . $file))
				$items[$file] = array('id' => $file, 'name' => $file);
		}
		unset($items['.'], $items['..'], $items['.svn']);
		return $items;
	}
}
}