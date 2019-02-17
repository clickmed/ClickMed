<?php

// Security
if (!defined('_PS_VERSION_'))
    exit;

// Checking compatibility with older PrestaShop and fixing it
if (!defined('_MYSQL_ENGINE_'))
    define('_MYSQL_ENGINE_', 'MyISAM');

// Loading Models
require_once(_PS_MODULE_DIR_ . 'dor_videoproducts/models/DorVideoProduct.php');

class Dor_videoproducts extends Module {
    public function __construct() {
        $this->name = 'dor_videoproducts';
        $this->tab = 'front_office_features';
        $this->version = '2.0';
        $this->author = 'Dorado Themes';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.5', 'max' => '1.7');
        parent::__construct();
        $this->displayName = $this->l('Dor Manage Video Products');
        $this->description = $this->l('Create and Management Video Products');
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
        $this->admin_tpl_path = _PS_MODULE_DIR_ . $this->name . '/views/templates/admin/';
    }

    public function install() {
        $res = $this->installDb();
          // Install Tabs
        if(!(int)Tab::getIdFromClassName('AdminDorMenu')) {
            $parent_tab = new Tab();
            // Need a foreach for the language
            $parent_tab->name[$this->context->language->id] = $this->l('Dor Extensions');
            $parent_tab->class_name = 'AdminDorMenu';
            $parent_tab->id_parent = 0; // Home tab
            $parent_tab->module = $this->name;
            $parent_tab->add();
        }


        $tab = new Tab();
        // Need a foreach for the language
    foreach (Language::getLanguages() as $language)
        $tab->name[$language['id_lang']] = $this->l('Dor Manage Video Products');
        $tab->class_name = 'AdminDorVideoProducts';
        $tab->id_parent = (int)Tab::getIdFromClassName('AdminDorMenu'); 
        $tab->module = $this->name;
        $tab->add();
        return parent::install() &&
                $this->registerHook('productfooter')
                &&
                $this->registerHook('displayHeader')
                &&
                $this->registerHook('displayBackOfficeHeader')
                &&
                $this->registerHook('dorTabVideoProduct')
                &&
                $this->registerHook('dorVideoProduct');
        return (bool)$res;
    }
	
    public function uninstall() {
        Configuration::deleteByName('dor_videoproducts');
        $tab = new Tab((int) Tab::getIdFromClassName('AdminDorVideoProducts'));
        $tab->delete();
        $res = $this->uninstallDb();
        if (!parent::uninstall())
            return false;
        return true;
        return (bool)$res;
    }

    public function installDb(){
        $res = Db::getInstance()->execute(
            'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'dor_videoproducts` (
              `videoId` int(11) unsigned NOT NULL AUTO_INCREMENT,
              `id_product` int(11) unsigned NOT NULL,
              `url` varchar(500) DEFAULT NULL,
              `width` int(10) DEFAULT NULL,
              `height` int(10) DEFAULT NULL,
              PRIMARY KEY (`videoId`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 '
        );
        return (bool)$res;
    }

    private function uninstallDb() {
        Db::getInstance()->execute('DROP TABLE `'._DB_PREFIX_.'dor_videoproducts`');
        return true;
    }


    public function hookDorTabVideoProduct($params)
    {
        $id_product = (int)$params['product']['id'];
        $hasVideo = 0;
        if($id_product == 0) return;
        $videoProduct = $this->getVideoProduct($id_product);
        if(count($videoProduct) > 0){
            $hasVideo = 1;
        }
        $this->smarty->assign(
            array(
                'hasVideo' => $hasVideo
            )
        );
        if($hasVideo)
            return $this->display(__FILE__, 'dor_tabvideoproducts.tpl', $this->getCacheId($id_product));
    }
    public function hookDorVideoProduct($params)
    {
        $id_product = (int)$params['product']['id'];
        $hasVideo = 0;
        if($id_product == 0) return;
        $videoProduct = $this->getVideoProduct($id_product);
        if(count($videoProduct) > 0){
            $hasVideo = 1;
        }
        $videos = array();
        if($videoProduct){
            foreach ($videoProduct as $key => $video) {
                $url = $video['url'];
                preg_match('/[\\?\\&]v=([^\\?\\&]+)/', $url, $matches);
                $videos[] = array("videoCode"=>$matches[1],"width"=>$video['width'],"height"=>$video['height']);
            }
        }
        $this->smarty->assign(
            array(
                'hasVideo'  => $hasVideo,
                'videos'    => $videos
            )
        );
        if($hasVideo)
            return $this->display(__FILE__, 'dor_videoproducts.tpl', $this->getCacheId($id_product));
    }
    public function getVideoProduct($id_product){
        if($id_product == null || $id_product == "") return;
        if (!$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
                SELECT *
                FROM `' . _DB_PREFIX_ . 'dor_videoproducts` v
                WHERE v.`id_product` = '.$id_product))
            return;
        return $result;
    }
    public  function _displayForm() {

    }

}