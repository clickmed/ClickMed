<?php
if (!defined('_PS_VERSION_'))
    exit;
require_once (_PS_MODULE_DIR_.'smartblog/classes/SmartBlogPost.php');
require_once (_PS_MODULE_DIR_.'smartblog/smartblog.php');
if (!class_exists( 'DorImageBase' )) {     
    require_once (_PS_ROOT_DIR_.'/override/Dor/DorImageBase.php');
}
class SmartBlogPopularPosts extends Module {

     public function __construct() {
            $this->name = 'smartblogpopularposts';
            $this->tab = 'front_office_features';
            $this->version = '2.0.1';
            $this->bootstrap = true;
            $this->author = 'Dorado Themes';
            $this->secure_key = Tools::encrypt($this->name);

            parent::__construct();

            $this->displayName = Context::getContext()->getTranslator()->trans('Smart Blog Popular Posts', array(), 'Modules.smartblogpopularposts');
            $this->description = Context::getContext()->getTranslator()->trans('The Most Powerfull Presta shop Blog  Module\'s tag - by doradothemes', array(), 'Modules.smartblogpopularposts');
            $this->confirmUninstall = Context::getContext()->getTranslator()->trans('Are you sure you want to delete your details ?', array(), 'Modules.smartblogpopularposts');
        }

            public function install(){
                if (!parent::install() || !$this->registerHook('leftColumn') 
				|| !$this->registerHook('displaySmartBlogLeft')
                || !$this->registerHook('displaySmartBlogRight')
				|| !$this->registerHook('actionsbdeletepost')
				|| !$this->registerHook('actionsbnewpost')
				|| !$this->registerHook('actionsbupdatepost')
				|| !$this->registerHook('actionsbtogglepost')
				)
        return false;
                Configuration::updateGlobalValue('smartshowpopularpost',5);
                Configuration::updateGlobalValue('popular_quanlity_image',90);
                Configuration::updateGlobalValue('popular_thumb_width',50);
                Configuration::updateGlobalValue('popular_thumb_height',70);
             return true;
        }
                
        public function uninstall() {
		 $this->DeleteCache();
            if (!parent::uninstall())
                 return false;
            Configuration::deleteByName('smartshowpopularpost');
            Configuration::deleteByName('popular_quanlity_image');
            Configuration::deleteByName('popular_thumb_width');
            Configuration::deleteByName('popular_thumb_height');
            return true;
                }
                
		public function DeleteCache()
            {
				return $this->_clearCache('smartblogpopularposts.tpl', $this->getCacheId());
			}
     public function hookLeftColumn($params)
            {
         
            }
            
     public function hookRightColumn($params)
            {
                 return $this->hookLeftColumn($params);
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
                 if (!$this->isCached('smartblogpopularposts.tpl', $this->getCacheId()))
                    {
                           global $smarty;
                           $id_lang = $this->context->language->id;
                           $posts =  SmartBlogPost::getPopularPosts($id_lang);
                           $i = 0;
                           $postsItem = array();
                           $thumbWidth = Configuration::get('popular_thumb_width');
                           $thumbHeight = Configuration::get('popular_thumb_height');
                           foreach($posts as $post) {
                               if (file_exists(_PS_MODULE_DIR_.'smartblog/images/' . $post['id_smart_blog_post'] . '.jpg') )
                               {
                                    $image =   $post['id_smart_blog_post'];
                                    $posts[$i]['post_img'] = $image;
                                    $pathImg = "smartblog/images/".$post['id_smart_blog_post'].".jpg";
                                    $width=$thumbWidth;$height=$thumbHeight;
                                    $images = DorImageBase::renderThumb($pathImg,$width,$height);
                                    $post['thumb_image'] = $images;
                               }
                               else
                               {
                                  $posts[$i]['post_img'] ='no';
                                  $posts[$i]['thumb_image'] ='no';
                               }
                               $postsItem[] = $post;
                               $i++;

                           }
                           $posts = $postsItem;
                           $smarty->assign( array(
                                         'posts' => $posts
                               ));
                      }
                  return $this->display(__FILE__, 'views/templates/front/smartblogpopularposts.tpl',$this->getCacheId());
                 
                }
            }
         public function hookdisplaySmartBlogRight($params)
            {
                 return $this->hookdisplaySmartBlogLeft($params);
            }        
            
         public function getContent(){
         
                $html = '';
                if(Tools::isSubmit('save'.$this->name))
                {
                    Configuration::updateValue('smartshowpopularpost', Tools::getvalue('smartshowpopularpost'));
                    Configuration::updateValue('popular_quanlity_image', Tools::getvalue('popular_quanlity_image'));
                    Configuration::updateValue('popular_thumb_width', Tools::getvalue('popular_thumb_width'));
                    Configuration::updateValue('popular_thumb_height', Tools::getvalue('popular_thumb_height'));
                    $html = $this->displayConfirmation(Context::getContext()->getTranslator()->trans('The settings have been updated successfully.', array(), 'Modules.smartblogpopularposts'));
                    $helper = $this->SettingForm();
                    $html .= $helper->generateForm($this->fields_form); 
                    return $html;
                }
                else
                {
                   $helper = $this->SettingForm();
                   $html .= $helper->generateForm($this->fields_form);
                   return $html;
                }
            }
            
     public function SettingForm() {
        $default_lang = (int) Configuration::get('PS_LANG_DEFAULT');
        $this->fields_form[0]['form'] = array(
          'legend' => array(
          'title' => Context::getContext()->getTranslator()->trans('General Setting', array(), 'Modules.smartblogpopularposts'),
            ),
            'input' => array(
                
                array(
                    'type' => 'text',
                    'label' => Context::getContext()->getTranslator()->trans('Show Number Of Popular Posts', array(), 'Modules.smartblogpopularposts'),
                    'name' => 'smartshowpopularpost',
                    'size' => 15,
                    'required' => true
                ),
                array(
                    'type' => 'text',
                    'label' => Context::getContext()->getTranslator()->trans('Quanlity Image:', array(), 'Modules.smartblogpopularposts'),
                    'name' => 'popular_quanlity_image',
                    'class' => 'fixed-width-md',
                ),
                array(
                    'type' => 'text',
                    'label' => Context::getContext()->getTranslator()->trans('Thumb width image:', array(), 'Modules.smartblogpopularposts'),
                    'name' => 'popular_thumb_width',
                    'class' => 'fixed-width-md',
                ),
                array(
                    'type' => 'text',
                    'label' => Context::getContext()->getTranslator()->trans('Thumb height image:', array(), 'Modules.smartblogpopularposts'),
                    'name' => 'popular_thumb_height',
                    'class' => 'fixed-width-md',
                ),          
            ),
            'submit' => array(
                'title' => Context::getContext()->getTranslator()->trans('Save', array(), 'Modules.smartblogpopularposts'),
                'class' => 'button'
            )
        );

        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;
        foreach (Language::getLanguages(false) as $lang)
                            $helper->languages[] = array(
                                    'id_lang' => $lang['id_lang'],
                                    'iso_code' => $lang['iso_code'],
                                    'name' => $lang['name'],
                                    'is_default' => ($default_lang == $lang['id_lang'] ? 1 : 0)
                            );
        $helper->toolbar_btn = array(
            'save' =>
            array(
                'desc' => Context::getContext()->getTranslator()->trans('Save', array(), 'Modules.smartblogpopularposts'),
                'href' => AdminController::$currentIndex . '&configure=' . $this->name . '&save'.$this->name.'token=' . Tools::getAdminTokenLite('AdminModules'),
            )
        );
        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;
        $helper->title = $this->displayName;
        $helper->show_toolbar = true;       
        $helper->toolbar_scroll = true;    
        $helper->submit_action = 'save'.$this->name;
        
        $helper->fields_value['smartshowpopularpost'] = Configuration::get('smartshowpopularpost');
        $helper->fields_value['popular_quanlity_image'] = Configuration::get('popular_quanlity_image');
        $helper->fields_value['popular_thumb_width'] = Configuration::get('popular_thumb_width');
        $helper->fields_value['popular_thumb_height'] = Configuration::get('popular_thumb_height');
        return $helper;
      }
		public function hookactionsbdeletepost($params)
            {
                 return $this->DeleteCache();
            }
		public function hookactionsbnewpost($params)
            {
                 return $this->DeleteCache();
            }
		public function hookactionsbupdatepost($params)
            {
                 return $this->DeleteCache();
            }
		public function hookactionsbtogglepost($params)
            {
                 return $this->DeleteCache();
            }
}