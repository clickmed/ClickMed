<?php
if (!class_exists( 'DorImageBase' )) {     
    require_once (_PS_ROOT_DIR_.'/override/Dor/DorImageBase.php');
}
if (!class_exists( 'DorCaches' )) {     
    require_once (_PS_ROOT_DIR_.'/override/Dor/Caches/DorCaches.php');
}

class dor_categories extends Module {
	private $spacer_size = '5';	
	private $_postErrors  = array();
	public function __construct() {
		$this->name 		= 'dor_categories';
		$this->tab 			= 'front_office_features';
		$this->version 		= '2.0';
        $this->bootstrap    = true;
        $this->_html        = '';
		$this->author 		= 'Dorado Themes';
		$this->displayName 	= Context::getContext()->getTranslator()->trans('Dor List Categories', array(), 'Modules.dor_categories');
		$this->description 	= Context::getContext()->getTranslator()->trans('Dor Show List Categories', array(), 'Modules.dor_categories');
        $this->fieldImageSettings = array(
            'name' => 'image',
            'dir' => 'blocklogo'
        );
		parent :: __construct();
	}
	
	public function install() {
    // Install Tabs
    if(!(int)Tab::getIdFromClassName('AdminDorMenu')) {
        $parent_tab = new Tab();
        // Need a foreach for the language
        $parent_tab->name[$this->context->language->id] = Context::getContext()->getTranslator()->trans('Dor Extensions', array(), 'Modules.dor_categories');
        $parent_tab->class_name = 'AdminDorMenu';
        $parent_tab->id_parent = 0; // Home tab
        $parent_tab->module = $this->name;
        $parent_tab->add();
    }
    $tab = new Tab();
    foreach (Language::getLanguages() as $language)
    $tab->name[$language['id_lang']] = Context::getContext()->getTranslator()->trans('Dor List Categories', array(), 'Modules.dor_categories');
    $tab->class_name = 'AdminDorListCategory';
    $tab->id_parent = (int)Tab::getIdFromClassName('AdminDorMenu'); 
    $tab->module = $this->name;
    $tab->add();
	Configuration::updateValue($this->name . '_p_on_row', 4);
	Configuration::updateValue($this->name . '_p_limit', 8);
	Configuration::updateValue($this->name . '_tab_effect', 'wiggle');
    Configuration::updateValue($this->name . '_p_on_row', 4);
	Configuration::updateValue($this->name . '_p_width', 275);
	Configuration::updateValue($this->name . '_p_height', 200);
    Configuration::updateValue($this->name .'_min_item', 4);
    Configuration::updateValue($this->name .'_w_img', 270);
	Configuration::updateValue($this->name .'_h_img', 378);
	Configuration::updateValue($this->name .'_max_item', 4);
	Configuration::updateValue($this->name . '_speed_slide', 3000);
	Configuration::updateValue($this->name . '_a_speed', 500);
    Configuration::updateValue($this->name . '_show_arrow', 0);
	Configuration::updateValue($this->name . '_show_nav', 1);
    Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'pos_categorythumb` (
			  `id_categoryslider` int(10)  NOT NULL AUTO_INCREMENT,
			  `image` varchar(128) NOT NULL,
			  `name_category` varchar(128) NOT NULL,
			  `id_category` int(10)  NOT NULL,
			  PRIMARY KEY (`id_categoryslider`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 AUTO_INCREMENT=1'
        );
            $sql =  "INSERT INTO `"._DB_PREFIX_."pos_categorythumb`(`image`,`name_category`,`id_category`) VALUES
            ('demo-1.jpg', 'Women',3),
            ('demo-2.jpg', 'Tops',4),
            ('demo-3.jpg', 'T-shirts',5)";
               Db::getInstance()->Execute($sql);
	$arrayDefault = array('CAT3','CAT4','CAT5');
	$cateDefault = implode(',',$arrayDefault);
	Configuration::updateGlobalValue($this->name.'_list_cate',$cateDefault);
		return parent :: install()
			&& $this->registerHook('header')
            && $this->registerHook('dorListCategory');
	}
      public function uninstall() {
        $tab = new Tab((int) Tab::getIdFromClassName('AdminDorListCategory'));
        $tab->delete();
        Db::getInstance()->execute('DROP TABLE `'._DB_PREFIX_.'pos_categorythumb`');
		Configuration::deleteByName($this->name . '_list_cate');
        $this->_clearCache('poscategorythumb.tpl');
        return parent::uninstall();
    }
	public function psversion() {
		$version=_PS_VERSION_;
		$exp=$explode=explode(".",$version);
		return $exp[1];
	}

    public function hookHeader($params){
        $this->context->controller->addCSS(($this->_path).'dor_categories.css', 'all');
        $this->context->controller->addCSS(($this->_path).'animate.delay.css', 'all');
        $this->context->controller->addCSS(($this->_path).'animate.min.css', 'all');
    }
    // Hook Home
	public function hookdorListCategory($params) {
            $nb = Configuration::get($this->name . '_p_limit');
            $imgWidth = Configuration::get($this->name . '_w_img');
	        $imgHeight = Configuration::get($this->name . '_h_img');
			$product_on_row = Configuration::get($this->name . '_p_on_row');
		    $arrayCategory = array();
			$catSelected = Configuration::get($this->name . '_list_cate');
			$cateArray = explode(',', $catSelected); 
			$id_lang =(int) Context::getContext()->language->id;
			$id_shop = (int) Context::getContext()->shop->id;
			$arrayProductCate = array();
            $dorCaches  = Tools::getValue('enableDorCache',Configuration::get('enableDorCache'));
            $objCache = new DorCaches(array('name'=>'default','path'=>_PS_ROOT_DIR_.'/override/Dor/Caches/smartcaches/categorylists/','extension'=>'.cache'));
            $fileCache = 'HomeCategoryListsCaches-Shop'.$id_shop;
            $objCache->setCache($fileCache);
            $cacheData = $objCache->renderData($fileCache);
            if($cacheData && $dorCaches){
                $arrayProductCate = $cacheData['lists'];
            }else{
                foreach($cateArray as $id_category) {
                    $id_category = str_replace('CAT','',$id_category);
                    $category = new Category((int) $id_category, (int) $id_lang, (int) $id_shop);
                    $link_cate = Context::getContext()->link->getCategoryLink((int) $id_category, null, null, ltrim("", '/'));
                    $totalProducts = $category->getProducts($this->context->language->id, 0, ($nb ? $nb : 5), $order_by = null, $order_way = null, $get_total = true);
                    $categories = $this->getimage($id_category);
                    if($categories){
                        $categories['link_cate'] = $link_cate;
                        $pathImg = "dor_categories/images/".$categories['image'];
                        $images = DorImageBase::renderThumb($pathImg,$imgWidth,$imgHeight);
                        $categories['thumb_image'] = $images;
                        $arrayProductCate[] = array('cate' =>$categories,'totalProduct'=>$totalProducts);
                    }
                }
                if($dorCaches){
                    $data['lists'] = $arrayProductCate;
                    $objCache->store($fileCache, $data, $expiration = TIME_CACHE_HOME);
                }
            }
			
			$options = array(
                'titleModule' => Configuration::get($this->name . '_title'),
				'p_width' => Configuration::get($this->name . '_p_width'),
				'p_height' => Configuration::get($this->name . '_p_height'),
				'speed_slide' => Configuration::get($this->name . '_speed_slide'),
				'a_speed' => Configuration::get($this->name . '_a_speed'),
				'show_des' => Configuration::get($this->name . '_show_des'),
				'show_arrow' => Configuration::get($this->name . '_show_arrow'),
				'show_ctr' => Configuration::get($this->name . '_show_ctr'),
                'min_item' => Configuration::get($this->name . '_min_item'),
                'w_img' => Configuration::get($this->name . '_w_img'),
				'h_img' => Configuration::get($this->name . '_h_img'),
				'max_item' => Configuration::get($this->name . '_max_item'),
                'show_price' => Configuration::get($this->name . '_show_price'),
                'show_nav' => Configuration::get($this->name . '_show_nav'),
			);
			$this->context->smarty->assign('slideOptions', $options);
            //echo "<pre>".print_r($arrayProductCate,1);die;
            $this->smarty->assign(array(
				'productCates' => $arrayProductCate,
                'add_prod_display' => Configuration::get('PS_ATTRIBUTE_CATEGORY_DISPLAY'),
				'product_on_row' => $product_on_row,
				'tab_effect' => Configuration::get($this->name . '_tab_effect'),
				'title' => Configuration::get($this->name . '_title'),
            ));
		return $this->display(__FILE__, 'dor_categories.tpl');
	}
	private function _installHookCustomer(){
		$hookspos = array(
				'tabCategory',
			); 
		foreach( $hookspos as $hook ){
			if( Hook::getIdByName($hook) ){
			} else {
				$new_hook = new Hook();
				$new_hook->name = pSQL($hook);
				$new_hook->title = pSQL($hook);
				$new_hook->add();
				$id_hook = $new_hook->id;
			}
		}
		return true;
	}
	  public function getContent() {
        $output = '<h2>' . $this->displayName . '</h2>';
        if (Tools::isSubmit('submitUpdate')) {
            if (!sizeof($this->_postErrors))
                    $this->_postProcess();
            else {
                foreach ($this->_postErrors AS $err) {
                    $this->_html .= '<div class="alert error">' . $err . '</div>';
                }
            }
        }
         if(Tools::isSubmit('submitUpdatethumb')){
             $id_cate = Tools::getValue('id_cate');
             $id_lang = (int) Context::getContext()->language->id;
             $id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext()->language->id;
             $id_shop = (int) Context::getContext()->shop->id;
             $category = new Category((int)$id_cate, (int)$id_lang, (int)$id_shop);
             $name_cate = $category->name;
             if($_FILES['imagethumb']['tmp_name']!=''){
                 $upload_path = _PS_MODULE_DIR_.$this->name.'/images/';
                 $filename = $refile_name = rand(0,1000).'-'.strtolower($_FILES['imagethumb']['name']);
                 if(move_uploaded_file($_FILES['imagethumb']['tmp_name'],$upload_path .$filename)){
                     $cate_exit = $this->getimage($id_cate);
                     if($cate_exit ==null){
                        $this->addcategoryicon($id_cate,$name_cate,$filename);
                        $this->_html .= $this->displayConfirmation(Context::getContext()->getTranslator()->trans('Update success', array(), 'Modules.dor_categories'));
                     }else{
                        $this->updatecategoryicon($id_cate,$name_cate,$filename);
                        $this->_html .= $this->displayConfirmation(Context::getContext()->getTranslator()->trans('Add images success', array(), 'Modules.dor_categories'));
                     }
                 }
             }
         }
          if (Tools::isSubmit('deletepos_categorythumb') && Tools::getValue('id_categoryslider')) {
             $delete = $this->deleteCategoryId(Tools::getValue('id_categoryslider'));
              $this->_html .= $this->displayConfirmation(Context::getContext()->getTranslator()->trans('Delete success', array(), 'Modules.dor_categories'));
          }
        return $output .$this->_html .$this->imageForm(). $this->_displayForm().$this->renderList();
    }


    public static function deleteCategoryId($id)
    {
        $sql = 'DELETE FROM `'._DB_PREFIX_.'pos_categorythumb`
				WHERE `id_categoryslider` = '.(int)$id;
        Db::getInstance()->execute($sql);
    }


    public function getSelectOptionsHtml($options = NULL, $name = NULL, $selected = NULL) {
        $html = "";
        $html .='<select name =' . $name . ' style="width:130px">';
        if (count($options) > 0) {
            foreach ($options as $key => $val) {
                if (trim($key) == trim($selected)) {
                    $html .='<option value=' . $key . ' selected="selected">' . $val . '</option>';
                } else {
                    $html .='<option value=' . $key . '>' . $val . '</option>';
                }
            }
        }
        $html .= '</select>';
        return $html;
    }
    private function _postProcess() {
        Configuration::updateValue($this->name . '_list_cate', implode(',', Tools::getValue('list_cate')));
        Configuration::updateValue($this->name . '_p_on_row', Tools::getValue('p_on_row'));
        Configuration::updateValue($this->name .'_p_limit', Tools::getValue('p_limit'));
		Configuration::updateValue($this->name .'_tab_effect', Tools::getValue('tab_effect'));
		Configuration::updateValue($this->name .'_title', Tools::getValue('title'));
		Configuration::updateValue($this->name . '_p_height', Tools::getValue('p_height'));
		Configuration::updateValue($this->name . '_p_width', Tools::getValue('p_width'));
        Configuration::updateValue($this->name . '_p_limit', Tools::getValue('p_limit'));
        Configuration::updateValue($this->name . '_speed_slide', Tools::getValue('speed_slide'));
        Configuration::updateValue($this->name . '_a_speed', Tools::getValue('a_speed'));
        Configuration::updateValue($this->name . '_show_arrow', Tools::getValue('show_arrow'));
        Configuration::updateValue($this->name . '_show_ctr', Tools::getValue('show_ctr'));
        Configuration::updateValue($this->name .'_min_item', Tools::getValue('min_item'));
        Configuration::updateValue($this->name .'_w_img', Tools::getValue('w_img'));
        Configuration::updateValue($this->name .'_h_img', Tools::getValue('h_img'));
        Configuration::updateValue($this->name .'_show_nav', Tools::getValue('show_nav'));
        Configuration::updateValue($this->name .'_max_item', Tools::getValue('max_item'));
        $this->_html .= $this->displayConfirmation(Context::getContext()->getTranslator()->trans('Configuration updated', array(), 'Modules.dor_categories'));
    }
    public  function addcategoryicon($id_cate,$name_cate,$filename){
        return   $res = Db::getInstance()->execute('INSERT  INTO `'._DB_PREFIX_.'pos_categorythumb`(`id_category`,`name_category`,`image`)
                        VALUES ('.(int)$id_cate.', \''.pSQL($name_cate).'\', \''.pSQL($filename).'\')');
    }
    public function  updatecategoryicon($id_cate,$name_cate,$filename){
    $res = Db::getInstance()->execute('
			UPDATE `'._DB_PREFIX_.'pos_categorythumb` SET `name_category` = \''.pSQL($name_cate).'\',`image` =\''.pSQL($filename).'\'
			WHERE `id_category` = '.(int)$id_cate
    );
        return $res ;

    }
    public  function imageForm(){
        $id_lang = (int)Context::getContext()->language->id;
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => Context::getContext()->getTranslator()->trans('Upload Image Thumb Categories', array(), 'Modules.dor_categories'),
                    'icon' => 'icon-link'
                ),
                'input' => array(
                    array(
                        'type' => 'file',
                        'label' => 'Image Thumb Category',
                        'name' => 'imagethumb',
                        'id' => 'imagethumb',
                        'display_image' => true,
                        'size' => 100
                        ),
                    array(
                        'type' => 'cateimage',
                        'label' => 'Show Category Icon:',
                        'name' => 'id_cate',
                    ),
                ),
                'submit' => array(
                    'title' => Context::getContext()->getTranslator()->trans('Save', array(), 'Modules.dor_categories'),
                    'name' => 'submitUpdatethumb',
                ),
            ));
        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->submit_action = 'submitUpdatethumb';
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $this->fields_form = array();
        $helper->module = $this;
        $helper->identifier = $this->identifier;
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false) . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $module = _PS_MODULE_DIR_ ;
        $helper->tpl_vars = array(
            'module' =>$module,
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
            'options_image' => $this->getCategoryOptions(1, (int)$id_lang, (int)Shop::getContextShopID()),
        );
        $helper->override_folder = '/';
        return $helper->generateForm(array($fields_form));
    }
	public  function _displayForm(){
		$spacer = str_repeat('&nbsp;', $this->spacer_size);
	    $tabEffect = array();
		$tabEffect = array(
            array( 'id'=>'none','mode'=>'None'),
            array('id'=>'hinge','mode'=>'Hinge'),
            array('id'=>'flash','mode'=>'Flash'),
            array('id'=>'shake','mode'=>'Shake'),
            array('id'=>'bounce','mode'=>'bounce'),
            array('id'=>'tada','mode'=>'Tada'),
            array('id'=>'swing','mode'=>'Swing'),
            array('id'=>'wobble','mode'=>'Wobble'),
            array('id'=>'pulse','mode'=>'Pulse'),
            array('id'=>'flip','mode'=>'Flip'),
            array('id'=>'flipInX','mode'=>'flipInX'),
            array('id'=>'flipInY','mode'=>'flipInY'),
            array('id'=>'fadeIn','mode'=>'fadeIn'),
            array('id'=>'bounceInUp','mode'=>'bounceInUp'),
            array('id'=>'fadeInLeft','mode'=>'fadeInLeft'),
            array('id'=>'rollIn','mode'=>'rollIn'),
            array('id'=>'lightSpeedIn','mode'=>'lightSpeedIn'),
            array('id'=>'wiggle','mode'=>'wiggle'),
            array('id'=>'rotateIn','mode'=>'rotateIn'),
            array('id'=>'rotateInUpLeft','mode'=>'rotateInUpLeft'),
            array('id'=>'rotateInUpRight','mode'=>'rotateInUpRight'),
		);
        $id_lang = (int) Context::getContext()->language->id;
        $options =    $this->getCategoryOption(1, (int)$id_lang, (int)Shop::getContextShopID());
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => Context::getContext()->getTranslator()->trans('Categories Slider', array(), 'Modules.dor_categories'),
                    'icon' => 'icon-link'
                ),
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => 'Title',
                        'name' => 'title',
                    ),
                    array(
                        'type' => 'switch',
                        'label' => 'autoPlay',
                        'name' => 'show_arrow',
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => Context::getContext()->getTranslator()->trans('Enabled', array(), 'Modules.dor_categories')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => Context::getContext()->getTranslator()->trans('Disabled', array(), 'Modules.dor_categories')
                            )
                        ),
                    ),
                    array(
                        'type' => 'select',
                        'label' => 'Effect Tab: ',
                        'name' => 'tab_effect',
                        'options' => array(                                  // This is only useful if type == select
                            'query' => $tabEffect,                           // $array_of_rows must contain an array of arrays, inner arrays (rows) being mode of many fields
                            'id' => 'id',                           // The key that will be used for each option "value" attribute
                            'name'=>'mode',
                        ),
                    ),
                    array(
                        'type' => 'selectlist',
                        'label' => 'Show Link/Label Category:',
                        'name' => 'list_cate[]',
                        'multiple'=>true,
                    ),
                    array(
                        'type' => 'text',
                        'label' => 'Min Items:',
                        'name' => 'min_item',
                    ),
                    array(
                        'type' => 'switch',
                        'label' => 'Show Page Control',
                        'name' => 'show_nav',
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => Context::getContext()->getTranslator()->trans('Enabled', array(), 'Modules.dor_categories')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => Context::getContext()->getTranslator()->trans('Disabled', array(), 'Modules.dor_categories')
                            )
                        ),
                    ),
                    array(
                        'type' => 'text',
                        'label' => 'Category Width Image:',
                        'name' => 'w_img',
                    ),
                    array(
                        'type' => 'text',
                        'label' => 'Category Height Image:',
                        'name' => 'h_img',
                    ),
                ),
                'submit' => array(
                    'title' => Context::getContext()->getTranslator()->trans('Save', array(), 'Modules.dor_categories'),
                    'name' => 'submitUpdate',
                ),
            ));
        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->submit_action = 'submitUpdate';
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $this->fields_form = array();
        $helper->module = $this;
        $helper->options = $options;
        $helper->identifier = $this->identifier;
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false) . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $module = _PS_MODULE_DIR_ ;
        $helper->tpl_vars = array(
            'module' =>$module,
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
            'options' => $options,
        );
        $helper->override_folder = '/';
        return $helper->generateForm(array($fields_form));
	}

    public function renderList()
    {
        $links = $this->getcategoryicon();
        $fields_list = array(
            'id_categoryslider' => array(
                'title' => Context::getContext()->getTranslator()->trans(' ID', array(), 'Modules.dor_categories'),
                'type' => 'text',
            ),
            'image' => array(
                'title' => Context::getContext()->getTranslator()->trans('Icon Category ', array(), 'Modules.dor_categories'),
                'type' => 'text',
            ),
            'id_category' => array(
                'title' => Context::getContext()->getTranslator()->trans('ID Category', array(), 'Modules.dor_categories'),
                'type' => 'text',
            ),
            'name_category' => array(
                'title' => Context::getContext()->getTranslator()->trans('Name Category', array(), 'Modules.dor_categories'),
                'type' => 'text',
            ),
        );
        $helper = new HelperList();
        $helper->shopLinkType = '';
        $helper->simple_header = true;
        $helper->identifier = 'id_categoryslider';
        $helper->table = 'pos_categorythumb';
        $helper->actions = array('edit', 'delete');
        $helper->show_toolbar = false;
        $helper->module = $this;
        $helper->title = Context::getContext()->getTranslator()->trans('Link list', array(), 'Modules.dor_categories');
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
        return $helper->generateList($links, $fields_list);
    }

    public function  getcategoryicon(){
        $sql = 'SELECT id_categoryslider ,image,id_category,name_category
				FROM '._DB_PREFIX_.'pos_categorythumb';
        return Db::getInstance()->executeS($sql);
    }
    public function  getimage($id){
        $sql = 'SELECT id_categoryslider ,image,id_category,name_category
				FROM '._DB_PREFIX_.'pos_categorythumb WHERE id_category = '.$id.'' ;
        return Db::getInstance()->getRow($sql);
    }

    public function getImageId($id)
    {
        if ((int)$id > 0)
        {
            $sql = 'SELECT b.`image`, b.`id_category`, FROM `'._DB_PREFIX_.'pos_categorythumb` b WHERE b.id_categoryslider ='.$id;

            if (!$results = Db::getInstance()->getRow($sql))
                return false;
            $link['image'] = $results['image'];
            $link['id_cate'] = $results['id_category'];
            return $link;
        }
        return false;
    }
    public function getConfigFieldsValues()
    {
        $fields_values = array('title' => Tools::getValue('title', Configuration::get($this->name .'_title')),
            'show_arrow' => Tools::getValue('show_arrow', Configuration::get($this->name .'_show_arrow')),
            'show_nav' => Tools::getValue('show_nav', Configuration::get($this->name .'_show_nav')),
            'tab_effect' => Tools::getValue('tab_effect', Configuration::get($this->name .'_tab_effect')),
            'list_cate[]' => Tools::getValue('list_cate', Configuration::get($this->name .'_list_cate')),
            'id_cate' => Tools::getValue('id_cate', Configuration::get('id_cate')),
            'name_category' => Tools::getValue('name_category', Configuration::get($this->name .'_name_category')),
            'min_item' => Tools::getValue('min_item', Configuration::get($this->name .'_min_item')),
            'w_img' => Tools::getValue('w_img', Configuration::get($this->name .'_w_img')),
            'h_img' => Tools::getValue('h_img', Configuration::get($this->name .'_h_img')),
        );
        if (Tools::getIsset('updatepos_categorythumb') && (int)Tools::getValue('id_categoryslider') > 0)
            $fields_values = array_merge($fields_values, $this->getImageId((int)Tools::getValue('id_categoryslider')));
        return $fields_values ;

    }

     public function getCategoryOption($id_category = 1, $id_lang = false, $id_shop = false, $recursive = true) {
		$cateCurrent = Configuration::get($this->name . '_list_cate');
		$cateCurrent = explode(',', $cateCurrent);
		$id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext()->language->id;
		$category = new Category((int)$id_category, (int)$id_lang, (int)$id_shop);
		if (is_null($category->id))
			return;
		if ($recursive)
		{
			$children = Category::getChildren((int)$id_category, (int)$id_lang, true, (int)$id_shop);
			$spacer = str_repeat('&nbsp;', $this->spacer_size * (int)$category->level_depth);
		}
		$shop = (object) Shop::getShop((int)$category->getShopID());
		        if (in_array('CAT'.(int)$category->id, $cateCurrent)) {
					$this->_html .= '<option value="CAT'.(int)$category->id.'" selected ="selected" >'.(isset($spacer) ? $spacer : '').$category->name.' ('.$shop->name.')</option>';
				} else {
					$this->_html .= '<option value="CAT'.(int)$category->id.'">'.(isset($spacer) ? $spacer : '').$category->name.' ('.$shop->name.')</option>';
				}
		if (isset($children) && count($children))
			foreach ($children as $child)
				$this->getCategoryOption((int)$child['id_category'], (int)$id_lang, (int)$child['id_shop']);
         return $this->_html ;
    }
     public function getCategoryOptions($id_category = 1, $id_lang = false, $id_shop = false, $recursive = true) {
		$id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext()->language->id;
		$category = new Category((int)$id_category, (int)$id_lang, (int)$id_shop);
		if (is_null($category->id))
			return;
		if ($recursive)
		{
			$children = Category::getChildren((int)$id_category, (int)$id_lang, true, (int)$id_shop);
			$spacer = str_repeat('&nbsp;', $this->spacer_size * (int)$category->level_depth);
		}
		$shop = (object)Shop::getShop((int)$category->getShopID());
					$this->html .= '<option value="'.(int)$category->id.'">'.(isset($spacer) ? $spacer : '').$category->name.' ('.$shop->name.')</option>';
		if (isset($children) && count($children))
			foreach ($children as $child)
				$this->getCategoryOptions((int)$child['id_category'], (int)$id_lang, (int)$child['id_shop']);
         return $this->html ;
    }

}