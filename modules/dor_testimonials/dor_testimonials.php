<?php
if (!defined('_PS_VERSION_'))
    exit;

// Loading Models
include_once(_PS_MODULE_DIR_ . 'dor_testimonials/libs/Params.php');
include_once(_PS_MODULE_DIR_ . 'dor_testimonials/classes/DorTestimonial.php');

class dor_testimonials extends Module
{
   private $_html = '';
   protected $params = null;
   const INSTALL_SQL_FILE = 'install.sql';
   const UNINSTALL_SQL_FILE = 'uninstall.sql';
   public function __construct()
        {
            $this->name ='dor_testimonials';
            $this->version = '2.0';
            $this->author = 'Dorado Themes';
            $this->bootstrap = true;
            $this->tab = 'front_office_features';
            $this->need_instance = 0;
            $this->ps_versions_compliancy = array('min' => '1.5', 'max' => '1.7');
            parent::__construct();
            $this->displayName = Context::getContext()->getTranslator()->trans('Dorado Testimonials', array(), 'Modules.dor_ajaxtabproductcategory');
            $this->description = Context::getContext()->getTranslator()->trans('Module manager Testimonials', array(), 'Modules.dor_ajaxtabproductcategory');
            $this->_searched_email = null;
            $this->confirmUninstall = Context::getContext()->getTranslator()->trans('Are you sure you want to uninstall?', array(), 'Modules.dor_ajaxtabproductcategory');
            $this->_params = new Params($this->name, $this);
        }

    public function initConfigs()
        {
            return array(
                'test_limit' => 10,
                'type_image' => 'png|jpg|gif',
                'type_video' => 'flv|mp4|avi',
                'size_limit' => 6,
                'captcha' => 1,
                'auto_post' => 1,
            );
        }

    public function install()
    {
        if (parent::install() &&  $this->registerHook('DorTestimonial') && $this->registerHook('header')) {
            $res = $this->installDb();
            if(!(int)Tab::getIdFromClassName('AdminDorMenu')) {
                $parent_tab = new Tab();
                $id_lang = (int)Context::getContext()->language->id;
                // Need a foreach for the language
                $parent_tab->name[$id_lang] = Context::getContext()->getTranslator()->trans('Dor Extensions', array(), 'Modules.dor_ajaxtabproductcategory');
                $parent_tab->class_name = 'AdminDorMenu';
                $parent_tab->id_parent = 0; // Home tab
                $parent_tab->module = $this->name;
                $parent_tab->add();
            }
            $tab = new Tab();
            // Need a foreach for the language
            foreach (Language::getLanguages() as $language)
                $tab->name[$language['id_lang']] = Context::getContext()->getTranslator()->trans('Dor Manage Testimonials', array(), 'Modules.dor_ajaxtabproductcategory');
            $tab->class_name = 'AdminTestimonials';
            $tab->id_parent = (int)Tab::getIdFromClassName('AdminDorMenu');
            $tab->module = $this->name;
            $tab->add();
            $configs = $this->initConfigs();
            $this->_params->batchUpdate($configs);
            return (bool)$res;
        }
        return false;
    }

    public function uninstall()
    {
        if (parent::uninstall()) {
            $res = $this->uninstallDb();
            $tab = new Tab((int) Tab::getIdFromClassName('AdminTestimonials'));
            $tab->delete();
          //  $res &= $this->uninstallModuleTab('AdminDorMenu');
            return (bool)$res;
        }
        return false;
    }
    public function installDb(){
        $res = Db::getInstance()->execute(
            'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'dortestimonial` (
            `id_dortestimonial` int(11) NOT NULL AUTO_INCREMENT,
            `id_lang` int(11) unsigned NOT NULL,
            `name_post` varchar(100) NOT NULL,
            `email` varchar(100) NOT NULL,
            `company` varchar(255) DEFAULT NULL,
            `address` varchar(500) NOT NULL,
            `media` varchar(255) DEFAULT NULL,
            `media_type` varchar(25) DEFAULT NULL,
            `title_post` varchar(355) DEFAULT NULL,
            `content` text NOT NULL,
            `rating` int(2) unsigned NOT NULL,
            `date_add` datetime DEFAULT NULL,
            `active` tinyint(1) DEFAULT NULL,
            `position` int(11) DEFAULT NULL ,
            PRIMARY KEY (`id_dortestimonial`))
            ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 AUTO_INCREMENT=1'
        );
        if ($res)
            $res &= Db::getInstance()->execute(
                'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'dortestimonial_shop` (
                `id_dortestimonial` int(10) unsigned NOT NULL,
                `id_shop` int(10) unsigned NOT NULL,
                PRIMARY KEY (`id_dortestimonial`,`id_shop`))
                ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8');
      $sql =  "INSERT INTO `"._DB_PREFIX_."dortestimonial`(`id_lang`,`name_post`,`email`,`company`,`address`,`title_post`,`content`,`rating`,`date_add`,`active`) VALUES
            (1,'Dorado Testimonial','doradothemes@gmail.com', 'Dorado Themes', 'Hanoi - Vietnam', 'The best organic store!', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.', 5, '2016-01-01', 1),
			(1,'Dorado Testimonial','doradothemes@gmail.com', 'Dorado Themes', 'Hanoi - Vietnam', 'The best organic store!', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.', 4, '2016-01-01', 1),
			(1,'Dorado Testimonial','doradothemes@gmail.com', 'Dorado Themes', 'Hanoi - Vietnam', 'The best organic store!', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.', 5, '2016-01-01', 1)";
        $sql1 = "INSERT INTO `"._DB_PREFIX_."dortestimonial_shop`(`id_shop`,`id_dortestimonial`) VALUES
        (1,1),
        (1,2),
        (1,3),
        (1,4)";
        if ($res){
              $res &=  Db::getInstance()->Execute($sql);
              $res &=  Db::getInstance()->Execute($sql1);

        }
        return (bool)$res;
    }
    private function uninstallDb() {
    Db::getInstance()->execute('DROP TABLE `'._DB_PREFIX_.'dortestimonial`');
    Db::getInstance()->execute('DROP TABLE `'._DB_PREFIX_.'dortestimonial_shop`');
    return true;
}

    public function getContent()
    {
        $this->_html .= '<h2>' . $this->displayName . ' and Custom Fields.</h2>';
        if (Tools::isSubmit('submitUpdate')) {
            if ($this->_postValidation()) {
                $configs = $this->initConfigs();
               $res = $this->_params->batchUpdate($configs);
                if (!$res) {
                    $this->_html .= $this->displayError(Context::getContext()->getTranslator()->trans('Configuration could not be updated', array(), 'Modules.dor_ajaxtabproductcategory'));
                } else {
                    $this->_html .= $this->displayConfirmation(Context::getContext()->getTranslator()->trans('Configuration updated', array(), 'Modules.dor_ajaxtabproductcategory'));
                }
            }
        }
        return $this->_html . $this->initForm();
    }

    protected function initForm()
    {
        $configs = $this->initConfigs();
        $params = $this->_params;
        $this->fields_form[0]['form'] = array(
            'legend' => array(
                'title' => Context::getContext()->getTranslator()->trans('Global Setting', array(), 'Modules.dor_ajaxtabproductcategory'),
                'icon' => 'icon-cogs'
            ),
            'input' => array(
                $params->inputTags('test_limit', 'Testimonial Limit:', false, 'The number items on a page.'),
                $params->inputTags('type_image', 'Image type:', false, 'allow upload image type.'),
                $params->inputTags('type_video', 'Video type:', false, 'allow upload video type.'),
                $params->inputTags('size_limit', 'Size limit upload:', false, 'Mb .Max size file upload.'),
                $params->switchTags('captcha', 'Display captcha:'),
                $params->switchTags('auto_post', 'Admin approve', 'Admin can set enable or disable auto post'),
            ),
            'submit' => array(
                'title' => Context::getContext()->getTranslator()->trans('Save', array(), 'Modules.dor_ajaxtabproductcategory'),
            )
        );
        $id_lang = (int)Context::getContext()->language->id;
        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitUpdate';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false) . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->default_form_language = $id_lang;
        $helper->allow_employee_form_lang = $id_lang;
        $helper->tpl_vars = array(
            'fields_value' => $params->getConfigFieldsValues($configs),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $id_lang
        );
        return $helper->generateForm($this->fields_form);
    }
    public function _postValidation()
    {
        $errors = array();
        if (Tools::isSubmit('submitUpdate')) {
            if (!Tools::getValue(('test_limit')) || !Validate::isInt(Tools::getValue('test_limit')))
                $errors[] = Context::getContext()->getTranslator()->trans('False! Check again with testimonial limit.', array(), 'Modules.dor_ajaxtabproductcategory');
            if (!Tools::getValue('size_limit') || !Validate::isInt(Tools::getValue('size_limit')))
                $errors[] = Context::getContext()->getTranslator()->trans('False! Check again with size upload limit.', array(), 'Modules.dor_ajaxtabproductcategory');
        }
        if (count($errors)) {
            $this->_html .= $this->displayError(implode('<br />', $errors));
            return false;
        }
        return true;
    }

    public function getParams()
    {
        return $this->_params;
    }
    public function hookHeader($params)
    {
        if (!isset($this->context->controller->php_self) || $this->context->controller->php_self != 'index')
            return;
        $this->context->controller->addCSS(_MODULE_DIR_ . $this->name. '/assets/front/css/testimonial.css', 'all');
    }
    public function hookdisplayLeftColumn($params)
    {
        $testLimit = $this->getParams()->get('test_limit');
        $get_testimonials = DorTestimonial::getAllTestimonials($testLimit);
        $img_types = explode('|', $this->getParams()->get('type_image'));
        $video_types = explode('|', $this->getParams()->get('type_video'));
        $this->context->smarty->assign(array(
           'testimonials' => $get_testimonials,
            'arr_img_type' => $img_types,
            'video_types' => $video_types,
            'mediaUrl' => _PS_IMG_ . 'dor_testimonial/',
            'video_post' => _MODULE_DIR_ . $this->name . '/assets/front/img/video.jpg',
            'video_youtube' => _MODULE_DIR_ . $this->name . '/assets/front/img/youtube.jpg',
            'video_vimeo' => _MODULE_DIR_ . $this->name . '/assets/front/img/vimeo.jpg',
        ));
        return $this->display(__FILE__,'/views/templates/front/testimonials_random.tpl');
    }

    public function hookDorTestimonial($params)
    {
        return $this->hookdisplayLeftColumn($params);
    }
    /*public function hookblockDorado5($params)
    {
        return $this->hookdisplayLeftColumn($params);
    }*/
}
