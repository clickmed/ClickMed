<?php
if (!defined('_PS_VERSION_'))
    exit;
 
require_once (_PS_MODULE_DIR_.'smartblog/classes/SmartBlogPost.php');
require_once (_PS_MODULE_DIR_.'smartblog/smartblog.php');
class smartblogsearch extends Module {
    
        public function __construct() {
        $this->name = 'smartblogsearch';
        $this->tab = 'front_office_features';
        $this->version = '2.1.0';
        $this->bootstrap = true;
        $this->author = 'Dorado Themes';
        $this->secure_key = Tools::encrypt($this->name);
        
        parent::__construct();
        
        $this->displayName = $this->l('Smart Blog Search');
        $this->description = $this->l('The Most Powerfull Presta shop Blog Search Module\'s - by doradothemes');
        }
        
        public function install(){
                $langs = Language::getLanguages();
                $id_lang = (int) Configuration::get('PS_LANG_DEFAULT');
                 if (!parent::install() || !$this->registerHook('leftColumn') 
                    || !$this->registerHook('displaySmartBlogLeft')
                    || !$this->registerHook('displaySmartBlogRight')
                 )
            return false;
                 return true;
            }
            
                public function uninstall() {
            if (!parent::uninstall())
                 return false;
            return true;
                }
                
         public function hookdisplaySmartBlogLeft($params)
            {
                
                 if(Module::isInstalled('smartblog') != 1){
                 $this->smarty->assign( array(
                              'smartmodname' => $this->name
                     ));
                        return $this->display(__FILE__, 'views/templates/front/install_required.tpl');
                }
                else
                {
                    return $this->display(__FILE__, 'views/templates/front/smartblogsearch.tpl');
                }
            }
         public function hookdisplaySmartBlogRight($params)
            {
                 return $this->hookdisplaySmartBlogLeft($params);
            }   
}