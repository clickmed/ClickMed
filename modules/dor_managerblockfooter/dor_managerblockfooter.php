<?php

// Security
if (!defined('_PS_VERSION_'))
    exit;

// Checking compatibility with older PrestaShop and fixing it
if (!defined('_MYSQL_ENGINE_'))
    define('_MYSQL_ENGINE_', 'MyISAM');
use PrestaShop\PrestaShop\Core\Module\WidgetInterface;
// Loading Models
require_once(_PS_MODULE_DIR_ .'dor_managerblockfooter/models/Managerblockfooter.php');

class Dor_managerblockfooter extends Module implements WidgetInterface {
    public  $hookAssign   = array();
    public $_staticModel =  "";
    public function __construct() {
        $this->name = 'dor_managerblockfooter';
        $this->tab = 'front_office_features';
        $this->version = '2.0';
        $this->author = 'Dorado Themes';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.5', 'max' => '1.7');
        $this->hookAssign = array('footer');
        $this->_staticModel = new Managerblockfooter();
        parent::__construct();

        $this->displayName = $this->getTranslator()->trans('Dor Manage Footer Blocks');
        $this->description = $this->getTranslator()->trans('Dor Manage Footer Blocks');

        $this->confirmUninstall = $this->getTranslator()->trans('Are you sure you want to uninstall?');
        $this->admin_tpl_path = _PS_MODULE_DIR_ . $this->name . '/views/templates/admin/';
    }
    public function install() {
		$res = $this->installDb();
          // Install Tabs
		if(!(int)Tab::getIdFromClassName('AdminDorMenu')) {
			$parent_tab = new Tab();
			$parent_tab->name[$this->context->language->id] = $this->getTranslator()->trans('Dor Extensions');
			$parent_tab->class_name = 'AdminDorMenu';
			$parent_tab->id_parent = 0; // Home tab
			$parent_tab->module = $this->name;
			$parent_tab->add();
		}

        $tab = new Tab();
        // Need a foreach for the language
		foreach (Language::getLanguages() as $language)
        $tab->name[$language['id_lang']] = $this->getTranslator()->trans('Dor Manage Footer Blocks');
        $tab->class_name = 'AdminDorManagerFooter';
        $tab->id_parent = (int)Tab::getIdFromClassName('AdminDorMenu'); 
        $tab->module = $this->name;
        $tab->add();
        // Set some defaults
        return parent::install() &&
                $this->registerHook('footer') &&
        		$this->_installHookCustomer()&&
                $this->registerHook('blockDoradoFooter')&&
                $this->registerHook('doradoFooterTop')&&
                $this->registerHook('doradoFooterAdv')&&
        		$this->registerHook('doradoFooter1')&&
        		$this->registerHook('doradoFooter2')&&
        		$this->registerHook('doradoFooter3')&&
                $this->registerHook('doradoFooter4')&&
                $this->registerHook('doradoFooter5')&&
                $this->registerHook('doradoFooter6')&&
                $this->registerHook('doradoFooter7')&&
                $this->registerHook('doradoFooter8')&&
                $this->registerHook('doradoFooter9')&&
        		$this->registerHook('doradoFooter10')&&
                $this->registerHook('displayBackOfficeHeader');
		return (bool)$res;
    }
    public function uninstall() {
        Configuration::deleteByName('dor_managerblockfooter');
		$res = $this->uninstallDb();
        $tab = new Tab((int) Tab::getIdFromClassName('Admindormanagerfooter'));
        $tab->delete();
        // Uninstall Module
        if (!parent::uninstall())
            return false;
        return true;
		return (bool)$res;
    }
/* database */
public function installDb(){
        $res = Db::getInstance()->execute(
            'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'dor_blockfooter` (
			  `id_dor_blockfooter` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `identify` varchar(128) NOT NULL,
			  `hook_position` varchar(128) NOT NULL,
			  `name_module` varchar(128) NOT NULL,
			  `hook_module` varchar(128) NOT NULL,
			  `order` int(10) unsigned NOT NULL,
			  `insert_module` int(10) unsigned NOT NULL,
			  `active` int(10) unsigned NOT NULL,
			  `is_default` int(10) unsigned NOT NULL DEFAULT "0",
			  `showhook` int(10) unsigned NOT NULL,
			  PRIMARY KEY (`id_dor_blockfooter`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15'
        );
        if ($res)
            $res &= Db::getInstance()->execute(
                'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'dor_blockfooter_lang` (
				  `id_dor_blockfooter` int(11) unsigned NOT NULL,
				  `id_lang` int(11) unsigned NOT NULL,
				  `title` varchar(128) NOT NULL,
				  `description` longtext,
				  PRIMARY KEY (`id_dor_blockfooter`,`id_lang`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8');
        if ($res)
            $res &= Db::getInstance()->execute(
                'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'dor_blockfooter_shop` (
                  `id_dor_blockfooter` int(11) unsigned NOT NULL,
                  `id_shop` int(11) unsigned NOT NULL,
                  PRIMARY KEY (`id_dor_blockfooter`,`id_shop`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8');

        return (bool)$res;
    }
	
private function uninstallDb() {
    Db::getInstance()->execute('DROP TABLE `'._DB_PREFIX_.'dor_blockfooter`');
    Db::getInstance()->execute('DROP TABLE `'._DB_PREFIX_.'dor_blockfooter_lang`');
    Db::getInstance()->execute('DROP TABLE `'._DB_PREFIX_.'dor_blockfooter_shop`');
    return true;
}

/*  */
      
    public function hookFooter($param) {
        $id_shop = (int)Context::getContext()->shop->id;
        $staticBlocks = $this->_staticModel->getDorBlockFooterLists($id_shop,'displayFooter');
        if(count($staticBlocks)<1) return null;
        $this->smarty->assign(array(
            'staticblocks' => $staticBlocks,
        ));
       return $this->display(__FILE__, 'block_footer.tpl');
    }
    
    
     public function hookDisplayBackOfficeHeader($params) {
	if (method_exists($this->context->controller, 'addJquery'))
	 {        
	  $this->context->controller->addJquery();
	  $this->context->controller->addJS(($this->_path).'js/dorblockfooter.js');
	 }
    }   
    /* define some hook customer */
    public function hookBlockDoradoFooter($param) {
        $id_shop = (int)Context::getContext()->shop->id;
        $staticBlocks = $this->_staticModel->getDorBlockFooterLists($id_shop,'blockDoradoFooter');
        if(count($staticBlocks)<1) return null;
        $this->smarty->assign(array(
            'staticblocks' => $staticBlocks,
        ));
       return $this->display(__FILE__, 'block_footer.tpl');
    } 
    /* define some hook customer */
    public function hookDoradoFooterTop($param) {
        $id_shop = (int)Context::getContext()->shop->id;
        $staticBlocks = $this->_staticModel->getDorBlockFooterLists($id_shop,'doradoFooterTop');
        if(count($staticBlocks)<1) return null;
        $this->smarty->assign(array(
            'staticblocks' => $staticBlocks,
        ));
       return $this->display(__FILE__, 'block_footer.tpl');
    }   
    /* define some hook customer */
    public function hookDoradoFooterAdv($param) {
        $id_shop = (int)Context::getContext()->shop->id;
        $staticBlocks = $this->_staticModel->getDorBlockFooterLists($id_shop,'doradoFooterAdv');
        if(count($staticBlocks)<1) return null;
        $this->smarty->assign(array(
            'staticblocks' => $staticBlocks,
        ));
       return $this->display(__FILE__, 'block_footer.tpl');
    }	
    /* define some hook customer */
	public function hookDoradoFooter1($param) {
        $id_shop = (int)Context::getContext()->shop->id;
        $staticBlocks = $this->_staticModel->getDorBlockFooterLists($id_shop,'doradoFooter1');
        if(count($staticBlocks)<1) return null;
        $this->smarty->assign(array(
            'staticblocks' => $staticBlocks,
        ));
       return $this->display(__FILE__, 'block_footer.tpl');
    }
    
	public function hookDoradoFooter2($param) {
        $id_shop = (int)Context::getContext()->shop->id;
        $staticBlocks = $this->_staticModel->getDorBlockFooterLists($id_shop,'doradoFooter2');
        if(count($staticBlocks)<1) return null;
        $this->smarty->assign(array(
            'staticblocks' => $staticBlocks,
        ));
       return $this->display(__FILE__, 'block_footer.tpl');
    }
    
	public function hookDoradoFooter3($param) {
        $id_shop = (int)Context::getContext()->shop->id;
        $staticBlocks = $this->_staticModel->getDorBlockFooterLists($id_shop,'doradoFooter3');
        if(count($staticBlocks)<1) return null;
        $this->smarty->assign(array(
            'staticblocks' => $staticBlocks,
        ));
       return $this->display(__FILE__, 'block_footer.tpl');
    }
    
    public function hookDoradoFooter4($param) {
        $id_shop = (int)Context::getContext()->shop->id;
        $staticBlocks = $this->_staticModel->getDorBlockFooterLists($id_shop,'doradoFooter4');
        if(count($staticBlocks)<1) return null;
        $this->smarty->assign(array(
            'staticblocks' => $staticBlocks,
        ));
       return $this->display(__FILE__, 'block_footer.tpl');
    }
    public function hookDoradoFooter10($param) {
        $id_shop = (int)Context::getContext()->shop->id;
        $staticBlocks = $this->_staticModel->getDorBlockFooterLists($id_shop,'doradoFooter10');
        if(count($staticBlocks)<1) return null;
        $this->smarty->assign(array(
            'staticblocks' => $staticBlocks,
        ));
       return $this->display(__FILE__, 'block_footer.tpl');
    }
    
    public function DorCacheManagerFooterBlock($hookname){
        $dorCaches  = Tools::getValue('enableDorCache',Configuration::get('enableDorCache'));
        $id_shop = (int)Context::getContext()->shop->id;
        $objCache = new DorCaches(array('name'=>'default','path'=>_PS_ROOT_DIR_.'/override/Dor/Caches/smartcaches/managerfooterblock/','extension'=>'.cache'));
        $fileCache = 'ManagerFooterBlock-Shop'.$id_shop.'-'.$hookname;
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
    public function getListModuleInstalled() {
        $mod = new Dor_managerblockfooter();
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
                'hookBlockDoradoFooter',
                'doradoFooterTop',
                'doradoFooterAdv',
				'doradoFooter1',
				'doradoFooter2',
				'doradoFooter3',
                'doradoFooter4',
                'doradoFooter5',
                'doradoFooter6',
                'doradoFooter7',
                'doradoFooter8',
                'doradoFooter9',
				'doradoFooter10',
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
    public function renderWidget($hookName, array $params)
    {
        return $this->display(__FILE__, 'block_footer.tpl');
    }

    public function getWidgetVariables($hookName, array $params)
    {
        
    }

}