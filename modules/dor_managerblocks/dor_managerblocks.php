<?php

// Security
if (!defined('_PS_VERSION_'))
    exit;

// Checking compatibility with older PrestaShop and fixing it
if (!defined('_MYSQL_ENGINE_'))
    define('_MYSQL_ENGINE_', 'MyISAM');
if (!class_exists( 'DorCaches' )) {     
    require_once (_PS_ROOT_DIR_.'/override/Dor/Caches/DorCaches.php');
}

// Loading Models
require_once(_PS_MODULE_DIR_ . 'dor_managerblocks/models/Managerblock.php');

class Dor_managerblocks extends Module {
    public  $hookAssign   = array();
    public $_staticModel =  "";
    public function __construct() {
        $this->name = 'dor_managerblocks';
        $this->tab = 'front_office_features';
        $this->version = '2.0';
        $this->author = 'Dorado Themes';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.5', 'max' => '1.7');
        $this->hookAssign = array('rightcolumn','leftcolumn','home','top','displayTopColumn','footer','extraLeft');
        $this->_staticModel = new ManagerBlock();
        parent::__construct();
        $this->displayName = $this->l('Dor Manage Content blocks html');
        $this->description = $this->l('Dor Manage Content blocks html');
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
        $this->admin_tpl_path = _PS_MODULE_DIR_ . $this->name . '/views/templates/admin/';
    }

    public function install() {

        // Install SQL
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
        $tab->name[$language['id_lang']] = $this->l('Dor Manage Content Blocks Html');
        $tab->class_name = 'AdminDorManagerBlocks';
        $tab->id_parent = (int)Tab::getIdFromClassName('AdminDorMenu'); 
        $tab->module = $this->name;
        $tab->add();

        return parent::install() &&
            $this->registerHook('top') &&
            $this->registerHook('displayNav1') &&
            $this->registerHook('displayNav2') &&
            $this->registerHook('displayTop') &&
            $this->registerHook('displayTopColumn') &&
            $this->registerHook('topbarDorado1') &&
            $this->registerHook('topbarDorado2') &&
            $this->registerHook('topbarDorado3') &&
            $this->registerHook('topbarDorado4') &&
            $this->registerHook('topbarDorado5') &&
            $this->registerHook('topbarDorado6') &&
            $this->registerHook('topbarDorado7') &&
            $this->registerHook('topbarDorado8') &&
            $this->registerHook('headerDorado1') &&
            $this->registerHook('headerDorado2') &&
            $this->registerHook('headerDorado3') &&
            $this->registerHook('headerDorado4') &&
            $this->registerHook('headerDorado5') &&
            $this->registerHook('headerDorado6') &&
            $this->registerHook('headerDorado7') &&
            $this->registerHook('headerDorado8') &&
            $this->registerHook('blockDorado1') &&
    		$this->registerHook('blockDorado2') &&
    		$this->registerHook('blockDorado3') &&
    		$this->registerHook('blockDorado4') &&
    		$this->registerHook('blockDorado5') &&
    		$this->registerHook('blockDorado6') &&
            $this->registerHook('blockDorado7') &&
            $this->registerHook('blockDorado8') &&
            $this->registerHook('blockDorado9') &&
    		$this->registerHook('blockDorado10') &&
            $this->registerHook('displayDorRightColumn') &&
            $this->registerHook('displayDorLeftColumn') &&
            $this->registerHook('displaySmartBlogLeft') &&
            $this->registerHook('displaySmartBlogRight') &&
            $this->registerHook('dorHomepageBar') &&
            $this->registerHook('bannerSlide') &&
    		$this->registerHook('actionShopDataDuplication') &&
            $this->registerHook('leftColumn') &&
            $this->registerHook('rightColumn') &&
            $this->registerHook('home') &&
            $this->registerHook('footer') &&
            $this->registerHook('displayFooter') &&
            $this->registerHook('displayFooterBefore') &&
            $this->registerHook('displaySearch') &&
            $this->registerHook('displayHeader')&&
            $this->registerHook('displayNav')&&
            $this->registerHook('displayBackOfficeHeader');
        return (bool)$res;
    }
	
    public function uninstall() {
        Configuration::deleteByName('dor_managerblocks');
        $res = $this->uninstallDb();
        $tab = new Tab((int) Tab::getIdFromClassName('Admindormanagerblocks'));
        $tab->delete();

        if (!parent::uninstall())
            return false;
        return true;
        return (bool)$res;
    }

	/* database */
public function installDb(){
        $res = Db::getInstance()->execute(
            'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'dor_managerblock` (
			  `id_dor_managerblock` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `identify` varchar(128) NOT NULL,
			  `hook_position` varchar(128) NOT NULL,
			  `name_module` varchar(128) NOT NULL,
			  `hook_module` varchar(128) NOT NULL,
			  `position` int(10) unsigned NOT NULL,
			  `insert_module` int(10) unsigned NOT NULL,
			  `active` int(10) unsigned NOT NULL,
			  `showhook` int(10) unsigned NOT NULL,
			  PRIMARY KEY (`id_dor_managerblock`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 '
        );
        if ($res)
            $res &= Db::getInstance()->execute(
                'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'dor_managerblock_lang` (
			  `id_dor_managerblock` int(11) unsigned NOT NULL,
			  `id_lang` int(11) unsigned NOT NULL,
			  `title` varchar(128) NOT NULL,
			  `description` longtext,
			  PRIMARY KEY (`id_dor_managerblock`,`id_lang`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8');
		if ($res)
            $res &= Db::getInstance()->execute(
                'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'dor_managerblock_shop` (
				  `id_dor_managerblock` int(11) unsigned NOT NULL,
				  `id_shop` int(11) unsigned NOT NULL,
				  PRIMARY KEY (`id_dor_managerblock`,`id_shop`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8');
		
		$sql =  "INSERT INTO `"._DB_PREFIX_."dor_managerblock` (`id_dor_managerblock`, `identify`, `hook_position`, `name_module`, `hook_module`, `position`, `insert_module`, `active`, `showhook`) VALUES
			('13','dor_menu_idcat_3_right','top','Chose Module','top','0','0','0','0'),
			('14','dor_menu_idcat_4_right','top','Chose Module','top','0','0','0','0'),
			('25','static-block','blockDorado1','Chose Module','top','0','0','0','1'),
			('29','static-block','bannerSlide','Chose Module','top','0','0','0','1'),
			('30','static-block','blockDorado2','Chose Module','top','0','0','0','1')
            ('31','static-block','actionShopDataDuplication','Chose Module','top','0','0','0','1'),
			";
        $sql1 = "INSERT INTO `"._DB_PREFIX_."dor_managerblock_lang` (`id_dor_managerblock`, `id_lang`, `title`, `description`) VALUES
			('13','1','dor custum menu','<div class=\"dor_customhtml\">\r\n<div class=\"img\"><a title=\"\" href=\"#\"><img src=\"/dorado/img/cms/img_menu_right.jpg\" alt=\"img\" /></a></div>\r\n</div>'),
			('13','2','dor custum menu','<div class=\"dor_customhtml\">\r\n<div class=\"img\"><a title=\"\" href=\"#\"><img src=\"/dorado/img/cms/img_menu_right.jpg\" alt=\"img\" /></a></div>\r\n</div>'),
			('14','1','banner menu right','<div class=\"dor_customhtml\">\r\n<div class=\"img\"><a title=\"\" href=\"#\"><img src=\"/dorado/img/cms/img_menu_right.jpg\" alt=\"img\" /></a></div>\r\n</div>'),
			('14','2','banner menu right','<div class=\"dor_customhtml\">\r\n<div class=\"img\"><a title=\"\" href=\"#\"><img src=\"/dorado/img/cms/img_menu_right.jpg\" alt=\"img\" /></a></div>\r\n</div>'),
			('25','1','banner static blockDorado1','<div class=\"support-footer-inner\">\r\n<div class=\"col-lg-4 col-md-4 col-sm-4 col-xs-12\">\r\n<div class=\"row-normal clearfix\">\r\n<div class=\"support-info\">\r\n<div class=\"info-title\"><i class=\"pe-7s-plane\"></i>FREE SHIPPING WORLDWIDE</div>\r\n</div>\r\n</div>\r\n</div>\r\n<div class=\"col-lg-4 col-md-4 col-sm-4 col-xs-12\">\r\n<div class=\"row-normal clearfix\">\r\n<div class=\"support-info\">\r\n<div class=\"info-title\"><i class=\"pe-7s-headphones\"></i>24/7 CUSTOMER SERVICE</div>\r\n</div>\r\n</div>\r\n</div>\r\n<div class=\"col-lg-4 col-md-4 col-sm-4 col-xs-12\">\r\n<div class=\"row-normal clearfix\">\r\n<div class=\"support-info\">\r\n<div class=\"info-title\"><i class=\"pe-7s-refresh-2\"></i>Member Discount</div>\r\n</div>\r\n</div>\r\n</div>\r\n</div>'),
			('25','2','banner static blockDorado1','<div class=\"support-footer-inner\">\r\n<div class=\"col-lg-4 col-md-4 col-sm-4 col-xs-12\">\r\n<div class=\"row-normal clearfix\">\r\n<div class=\"support-info\">\r\n<div class=\"info-title\"><i class=\"pe-7s-plane\"></i>FREE SHIPPING WORLDWIDE</div>\r\n</div>\r\n</div>\r\n</div>\r\n<div class=\"col-lg-4 col-md-4 col-sm-4 col-xs-12\">\r\n<div class=\"row-normal clearfix\">\r\n<div class=\"support-info\">\r\n<div class=\"info-title\"><i class=\"pe-7s-headphones\"></i>24/7 CUSTOMER SERVICE</div>\r\n</div>\r\n</div>\r\n</div>\r\n<div class=\"col-lg-4 col-md-4 col-sm-4 col-xs-12\">\r\n<div class=\"row-normal clearfix\">\r\n<div class=\"support-info\">\r\n<div class=\"info-title\"><i class=\"pe-7s-refresh-2\"></i>Member Discount</div>\r\n</div>\r\n</div>\r\n</div>\r\n</div>'),
			('29','1','banner static bannerSlide','<div class=\"dor_static col-lg-4 col-md-4 col-sm-4 col-xs-12\">\r\n<div class=\"img\"><a href=\"#\" title=\"\"> <img src=\"/dorado/img/cms/img_6_1.jpg\" alt=\"images\" /> </a></div>\r\n<div class=\"img\"><a href=\"#\" title=\"\"> <img src=\"/dorado/img/cms/img_6_2.jpg\" alt=\"images\" /> </a></div>\r\n</div>'),
			('29','2','banner static bannerSlide','<div class=\"dor_static col-lg-4 col-md-4 col-sm-4 col-xs-12\">\r\n<div class=\"img\"><a href=\"#\" title=\"\"> <img src=\"/dorado/img/cms/img_6_1.jpg\" alt=\"images\" /> </a></div>\r\n<div class=\"img\"><a href=\"#\" title=\"\"> <img src=\"/dorado/img/cms/img_6_2.jpg\" alt=\"images\" /> </a></div>\r\n</div>'),
			('30','1','banner static blockDorado2','<div class=\"dor_static \">\r\n<div class=\"col-lg-8 col-md-8 col-sm-8 col-xs-12\">\r\n<div class=\"img\"><a href=\"#\" title=\"\"> <img src=\"/dorado/img/cms/img_6_3.jpg\" alt=\"images\" /> </a></div>\r\n<div class=\"row\">\r\n<div class=\"img col-lg-6 col-md-6 col-sm-6 col-xs-12\"><a href=\"#\" title=\"\"> <img src=\"/dorado/img/cms/img_6_5.jpg\" alt=\"images\" /> </a></div>\r\n<div class=\"img col-lg-6 col-md-6 col-sm-6 col-xs-12\"><a href=\"#\" title=\"\"> <img src=\"/dorado/img/cms/img_6_6.jpg\" alt=\"images\" /> </a></div>\r\n</div>\r\n</div>\r\n<div class=\"col-lg-4 col-md-4 col-sm-4 col-xs-12\">\r\n<div class=\"img \"><a href=\"#\" title=\"\"> <img src=\"/dorado/img/cms/img_6_4.jpg\" alt=\"images\" /> </a></div>\r\n</div>\r\n</div>'),
			('30','2','banner static blockDorado2','<div class=\"dor_static \">\r\n<div class=\"col-lg-8 col-md-8 col-sm-8 col-xs-12\">\r\n<div class=\"img\"><a href=\"#\" title=\"\"> <img src=\"/dorado/img/cms/img_6_3.jpg\" alt=\"images\" /> </a></div>\r\n<div class=\"row\">\r\n<div class=\"img col-lg-6 col-md-6 col-sm-6 col-xs-12\"><a href=\"#\" title=\"\"> <img src=\"/dorado/img/cms/img_6_5.jpg\" alt=\"images\" /> </a></div>\r\n<div class=\"img col-lg-6 col-md-6 col-sm-6 col-xs-12\"><a href=\"#\" title=\"\"> <img src=\"/dorado/img/cms/img_6_6.jpg\" alt=\"images\" /> </a></div>\r\n</div>\r\n</div>\r\n<div class=\"col-lg-4 col-md-4 col-sm-4 col-xs-12\">\r\n<div class=\"img \"><a href=\"#\" title=\"\"> <img src=\"/dorado/img/cms/img_6_4.jpg\" alt=\"images\" /> </a></div>\r\n</div>\r\n</div>')
			('31','1','banner static actionShopDataDuplication','<div class=\"dor_static col-lg-4 col-md-4 col-sm-4 col-xs-12\">\r\n<div class=\"img\"><a href=\"#\" title=\"\"> <img src=\"/dorado/img/cms/img_6_1.jpg\" alt=\"images\" /> </a></div>\r\n<div class=\"img\"><a href=\"#\" title=\"\"> <img src=\"/dorado/img/cms/img_6_2.jpg\" alt=\"images\" /> </a></div>\r\n</div>'),
            ('31','2','banner static actionShopDataDuplication','<div class=\"dor_static col-lg-4 col-md-4 col-sm-4 col-xs-12\">\r\n<div class=\"img\"><a href=\"#\" title=\"\"> <img src=\"/dorado/img/cms/img_6_1.jpg\" alt=\"images\" /> </a></div>\r\n<div class=\"img\"><a href=\"#\" title=\"\"> <img src=\"/dorado/img/cms/img_6_2.jpg\" alt=\"images\" /> </a></div>\r\n</div>'),
";
        $sql2 = "INSERT INTO `"._DB_PREFIX_."dor_managerblock_shop` (`id_dor_managerblock`, `id_shop`) VALUES
			(13, 1),
			(14, 1),
			(25, 1),
			(29, 1),
            (30, 1),
			(31, 1)
			";
        
		if ($res){
              /*$res &=  Db::getInstance()->Execute($sql);
              $res &=  Db::getInstance()->Execute($sql1);
              $res &=  Db::getInstance()->Execute($sql2);*/
        }
        return (bool)$res;
}

private function uninstallDb() {
    Db::getInstance()->execute('DROP TABLE `'._DB_PREFIX_.'dor_managerblock`');
    Db::getInstance()->execute('DROP TABLE `'._DB_PREFIX_.'dor_managerblock_lang`');
    Db::getInstance()->execute('DROP TABLE `'._DB_PREFIX_.'dor_managerblock_shop`');
    return true;
}

/*  */
    public function hookTop($param) {
        /*$id_shop = (int)Context::getContext()->shop->id;
        $staticBlocks = $this->_staticModel->getStaticblockLists($id_shop,'topbarDorado1');
        if(count($staticBlocks)<1) return null;*/
        $staticBlocks = $this->DorCacheManagerBlock('top');
        $this->smarty->assign(array(
            'staticblocks' => $staticBlocks,
        ));
       return $this->display(__FILE__, 'block.tpl');
    }
    public function hookHeaderDorado8($param) {
        $staticBlocks = $this->DorCacheManagerBlock('headerDorado8');
        $this->smarty->assign(array(
            'staticblocks' => $staticBlocks,
        ));
       return $this->display(__FILE__, 'block.tpl');
    }
    public function hookTopbarDorado1($param) {
        /*$id_shop = (int)Context::getContext()->shop->id;
        $staticBlocks = $this->_staticModel->getStaticblockLists($id_shop,'topbarDorado1');
        if(count($staticBlocks)<1) return null;*/
        $staticBlocks = $this->DorCacheManagerBlock('topbarDorado1');
        $this->smarty->assign(array(
            'staticblocks' => $staticBlocks,
        ));
       return $this->display(__FILE__, 'block.tpl');
    }
    public function hookTopbarDorado2($param) {
        $staticBlocks = $this->DorCacheManagerBlock('topbarDorado2');
        $this->smarty->assign(array(
            'staticblocks' => $staticBlocks,
        ));
       return $this->display(__FILE__, 'block.tpl');
    }
    public function hookdisplayTopColumn($param) {
        /*$id_shop = (int)Context::getContext()->shop->id;
        $staticBlocks = $this->_staticModel->getStaticblockLists($id_shop,'displayTopColumn');
        if(count($staticBlocks)<1) return null;*/
        $staticBlocks = $this->DorCacheManagerBlock('displayTopColumn');
        $this->smarty->assign(array(
            'staticblocks' => $staticBlocks,
        ));
       return $this->display(__FILE__, 'block.tpl');
    }
    
    public function hookLeftColumn($param) {
        /*$id_shop = (int)Context::getContext()->shop->id;
        $staticBlocks = $this->_staticModel->getStaticblockLists($id_shop,'leftColumn');
        if(count($staticBlocks)<1) return null;*/
        $staticBlocks = $this->DorCacheManagerBlock('leftColumn');
        $this->smarty->assign(array(
            'staticblocks' => $staticBlocks,
        ));
       return $this->display(__FILE__, 'block.tpl');
    }
    
     public function hookRightColumn($param) { 
        /*$id_shop = (int)Context::getContext()->shop->id;
        $staticBlocks = $this->_staticModel->getStaticblockLists($id_shop,'rightColumn');*/
        $staticBlocks = $this->DorCacheManagerBlock('rightColumn');
        $this->smarty->assign(array(
            'staticblocks' => $staticBlocks,
        ));
       return $this->display(__FILE__, 'block.tpl');
    }
    public function hookHome($param) {
        /*$id_shop = (int)Context::getContext()->shop->id;
        $staticBlocks = $this->_staticModel->getStaticblockLists($id_shop,'home');
        if(count($staticBlocks)<1) return null;*/
        $staticBlocks = $this->DorCacheManagerBlock('home');
        $this->smarty->assign(array(
            'staticblocks' => $staticBlocks,
        ));
       return $this->display(__FILE__, 'block.tpl');
    }
    
    public function hookblockDorado1($param) {
        //$id_shop = (int)Context::getContext()->shop->id;
        $staticBlocks = $this->DorCacheManagerBlock('blockDorado1');
        //echo "<pre>";print_r($staticBlocks);echo "</pre>";
        $this->smarty->assign(array(
            'staticblocks' => $staticBlocks,
        ));
       return $this->display(__FILE__, 'block.tpl');
    }
    
    public function hookblockDorado2($param) {
        //$id_shop = (int)Context::getContext()->shop->id;
        //$staticBlocks = $this->_staticModel->getStaticblockLists($id_shop,'blockDorado2');
        $staticBlocks = $this->DorCacheManagerBlock('blockDorado2');
        //if(count($staticBlocks)<1) return null;
        $this->smarty->assign(array(
            'staticblocks' => $staticBlocks,
        ));
       return $this->display(__FILE__, 'block.tpl');
    }
    
    public function hookblockDorado3($param) {
        /*$id_shop = (int)Context::getContext()->shop->id;
        $staticBlocks = $this->_staticModel->getStaticblockLists($id_shop,'blockDorado3');
        if(count($staticBlocks)<1) return null;*/
        $staticBlocks = $this->DorCacheManagerBlock('blockDorado3');
        $this->smarty->assign(array(
            'staticblocks' => $staticBlocks,
        ));
       return $this->display(__FILE__, 'block.tpl');
    }
    public function hookblockDorado4($param) {
        /*$id_shop = (int)Context::getContext()->shop->id;
        $staticBlocks = $this->_staticModel->getStaticblockLists($id_shop,'blockDorado4');
        if(count($staticBlocks)<1) return null;*/
        //if(is_array($staticBlocks))
        $staticBlocks = $this->DorCacheManagerBlock('blockDorado4');
        $this->smarty->assign(array(
            'staticblocks' => $staticBlocks,
        ));
       return $this->display(__FILE__, 'block.tpl');
    }
    public function hookblockDorado5($param) {
        /*$id_shop = (int)Context::getContext()->shop->id;
        $staticBlocks = $this->_staticModel->getStaticblockLists($id_shop,'blockDorado5');
        if(count($staticBlocks)<1) return null;*/
        $staticBlocks = $this->DorCacheManagerBlock('blockDorado5');
        $this->smarty->assign(array(
            'staticblocks' => $staticBlocks,
        ));
       return $this->display(__FILE__, 'block.tpl');
    }
    public function hookblockDorado6($param) {
        /*$id_shop = (int)Context::getContext()->shop->id;
        $staticBlocks = $this->_staticModel->getStaticblockLists($id_shop,'blockDorado5');
        if(count($staticBlocks)<1) return null;*/
        $staticBlocks = $this->DorCacheManagerBlock('blockDorado6');
        $this->smarty->assign(array(
            'staticblocks' => $staticBlocks,
        ));
       return $this->display(__FILE__, 'block.tpl');
    }
    public function hookblockDorado7($param) {
        /*$id_shop = (int)Context::getContext()->shop->id;
        $staticBlocks = $this->_staticModel->getStaticblockLists($id_shop,'blockDorado5');
        if(count($staticBlocks)<1) return null;*/
        $staticBlocks = $this->DorCacheManagerBlock('blockDorado7');
        $this->smarty->assign(array(
            'staticblocks' => $staticBlocks,
        ));
       return $this->display(__FILE__, 'block.tpl');
    }
    public function hookblockDorado8($param) {
        /*$id_shop = (int)Context::getContext()->shop->id;
        $staticBlocks = $this->_staticModel->getStaticblockLists($id_shop,'blockDorado5');
        if(count($staticBlocks)<1) return null;*/
        $staticBlocks = $this->DorCacheManagerBlock('blockDorado8');
        $this->smarty->assign(array(
            'staticblocks' => $staticBlocks,
        ));
       return $this->display(__FILE__, 'block.tpl');
    }
    public function hookblockDorado9($param) {
        /*$id_shop = (int)Context::getContext()->shop->id;
        $staticBlocks = $this->_staticModel->getStaticblockLists($id_shop,'blockDorado9');
        if(count($staticBlocks)<1) return null;*/
        $staticBlocks = $this->DorCacheManagerBlock('blockDorado9');
        $this->smarty->assign(array(
            'staticblocks' => $staticBlocks,
        ));
       return $this->display(__FILE__, 'block.tpl');
    }
    public function hookblockDorado10($param) {
        /*$id_shop = (int)Context::getContext()->shop->id;
        $staticBlocks = $this->_staticModel->getStaticblockLists($id_shop,'blockDorado10');
        if(count($staticBlocks)<1) return null;*/
        $staticBlocks = $this->DorCacheManagerBlock('blockDorado10');
        $this->smarty->assign(array(
            'staticblocks' => $staticBlocks,
        ));
       return $this->display(__FILE__, 'block.tpl');
    }
    
    public function hookDisplayDorRightColumn($param) {
        /*$id_shop = (int)Context::getContext()->shop->id;
        $staticBlocks = $this->_staticModel->getStaticblockLists($id_shop,'displayDorRightColumn');
        if(count($staticBlocks)<1) return null;*/
        $staticBlocks = $this->DorCacheManagerBlock('displayDorRightColumn');
        $this->smarty->assign(array(
            'staticblocks' => $staticBlocks,
        ));
       return $this->display(__FILE__, 'block.tpl');
    }
    public function hookDisplayDorLeftColumn($param) {
        /*$id_shop = (int)Context::getContext()->shop->id;
        $staticBlocks = $this->_staticModel->getStaticblockLists($id_shop,'displayDorLeftColumn');
        if(count($staticBlocks)<1) return null;*/
        $staticBlocks = $this->DorCacheManagerBlock('displayDorLeftColumn');
        $this->smarty->assign(array(
            'staticblocks' => $staticBlocks,
        ));
       return $this->display(__FILE__, 'block.tpl');
    }
    public function hookDisplaySmartBlogLeft($param) {
        /*$id_shop = (int)Context::getContext()->shop->id;
        $staticBlocks = $this->_staticModel->getStaticblockLists($id_shop,'displayDorLeftColumn');
        if(count($staticBlocks)<1) return null;*/
        $staticBlocks = $this->DorCacheManagerBlock('displaySmartBlogLeft');
        $this->smarty->assign(array(
            'staticblocks' => $staticBlocks,
        ));
       return $this->display(__FILE__, 'block.tpl');
    }
    public function hookDisplaySmartBlogRight($param) {
        /*$id_shop = (int)Context::getContext()->shop->id;
        $staticBlocks = $this->_staticModel->getStaticblockLists($id_shop,'displayDorLeftColumn');
        if(count($staticBlocks)<1) return null;*/
        $staticBlocks = $this->DorCacheManagerBlock('displaySmartBlogRight');
        $this->smarty->assign(array(
            'staticblocks' => $staticBlocks,
        ));
       return $this->display(__FILE__, 'block.tpl');
    }
	public function hookDorHomepageBar($param) {
        /*$id_shop = (int)Context::getContext()->shop->id;
        $staticBlocks = $this->_staticModel->getStaticblockLists($id_shop,'dorHomepageBar');
        if(count($staticBlocks)<1) return null;*/
        $staticBlocks = $this->DorCacheManagerBlock('dorHomepageBar');
        $this->smarty->assign(array(
            'staticblocks' => $staticBlocks,
        ));
       return $this->display(__FILE__, 'block.tpl');
    }
    public function hookactionShopDataDuplication($param) {
        $id_shop = (int)Context::getContext()->shop->id;
        $staticBlocks = $this->_staticModel->getStaticblockLists($id_shop,'actionShopDataDuplication');
        if(count($staticBlocks)<1) return null;
        $this->smarty->assign(array(
            'staticblocks' => $staticBlocks,
        ));
       return $this->display(__FILE__, 'block.tpl');
    }
    public function hookDisplayBackOfficeHeader($params) {
        $this->context->controller->addCSS($this->_path . 'css/managerblock.admin.css');
		if (method_exists($this->context->controller, 'addJquery'))
		{        
		$this->context->controller->addJquery();
		$this->context->controller->addJS(($this->_path).'js/staticblock.js');
		}
		return $this->display(__FILE__, 'views/templates/admin/fortawesome.tpl');
    }
    public function DorCacheManagerBlock($hookname){
        $dorCaches  = Tools::getValue('enableDorCache',Configuration::get('enableDorCache'));
        $id_shop = (int)Context::getContext()->shop->id;
        $objCache = new DorCaches(array('name'=>'default','path'=>_PS_ROOT_DIR_.'/override/Dor/Caches/smartcaches/managerblock/','extension'=>'.cache'));
        $fileCache = 'ManagerBlock-Shop'.$id_shop.'-'.$hookname;
        $objCache->setCache($fileCache);
        $cacheData = $objCache->renderData($fileCache);
        $staticBlocks = array();
        if($cacheData && $dorCaches){
            $staticBlocks = $cacheData['lists'];
        }else{
            $staticBlocks = $this->_staticModel->getStaticblockLists($id_shop,$hookname);
            if(count($staticBlocks)<1) return null;
            if($dorCaches){
                $data['lists'] = $staticBlocks;
                $objCache->store($fileCache, $data, $expiration = TIME_CACHE_HOME);
            }
        }
        return $staticBlocks;
    }
    
    public function getModulById($id_module) {
        return Db::getInstance()->getRow('
            SELECT m.*
            FROM `' . _DB_PREFIX_ . 'module` m
            JOIN `' . _DB_PREFIX_ . 'module_shop` ms ON (m.`id_module` = ms.`id_module` AND ms.`id_shop` = ' . (int) ($this->context->shop->id) . ')
            WHERE m.`id_module` = ' . $id_module);
    }

    public function getHooksByModuleId($id_module) {
        $module = self::getModulById($id_module);
        $moduleInstance = Module::getInstanceByName($module['name']);
        $hooks = array();
        if ($this->hookAssign)
            foreach ($this->hookAssign as $hook) {
                if (_PS_VERSION_ < "1.5") {
                    if (is_callable(array($moduleInstance, 'hook' . $hook))) {
                        $hooks[] = $hook;
                    }
                } else {
                    $retro_hook_name = Hook::getRetroHookName($hook);
                    if (is_callable(array($moduleInstance, 'hook' . $hook)) || is_callable(array($moduleInstance, 'hook' . $retro_hook_name))) {
                        $hooks[] = $retro_hook_name;
                    }
                }
            }
        $results = self::getHookByArrName($hooks);
        return $results;
    }

    public static function getHookByArrName($arrName) {
        $result = Db::getInstance()->ExecuteS('
		SELECT `id_hook`, `name`
		FROM `' . _DB_PREFIX_ . 'hook` 
		WHERE `name` IN (\'' . implode("','", $arrName) . '\')');
        return $result;
    }
  //$hooks = $this->getHooksByModuleId(10);
    public function getListModuleInstalled() {
        $mod = new dor_managerblocks();
        $modules = $mod->getModulesInstalled(0);
        $arrayModule = array();
        foreach($modules as $key => $module) {
            if($module['active']==1) {
                $arrayModule[0] = array('id_module'=>0, 'name'=>'Chose Module');
                $arrayModule[$key] = $module;
            }
        }
        if ($arrayModule)
            return $arrayModule;
        return array();
    }
    
    private function _installHookCustomer(){
		$hookspos = array(
                'topbarDorado1',
                'topbarDorado2',
                'topbarDorado3',
                'topbarDorado4',
                'topbarDorado5',
                'topbarDorado6',
                'topbarDorado7',
                'topbarDorado8',
                'headerDorado1',
                'headerDorado2',
                'headerDorado3',
                'headerDorado4',
                'headerDorado5',
                'headerDorado6',
                'headerDorado7',
                'headerDorado8',
				'blockDorado1',
				'blockDorado2',
				'blockDorado3',
				'blockDorado4',
                'blockDorado5',
                'blockDorado6',
                'blockDorado7',
                'blockDorado8',
                'blockDorado9',
                'blockDorado10',
                'displayNav',
                'displayDorRightColumn',
                'displayDorLeftColumn',
				'dorHomepageBar',
                'bannerSlide',
				'actionShopDataDuplication'
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
    public  function _displayForm() {

    }

}