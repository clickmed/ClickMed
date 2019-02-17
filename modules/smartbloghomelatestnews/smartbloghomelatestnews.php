<?php
if (!defined('_PS_VERSION_'))
    exit;
 
require_once (_PS_MODULE_DIR_.'smartblog/classes/SmartBlogPost.php');
require_once (_PS_MODULE_DIR_.'smartblog/smartblog.php');
if (!class_exists( 'DorImageBase' )) {     
    require_once (_PS_ROOT_DIR_.'/override/Dor/DorImageBase.php');
}
if (!class_exists( 'DorCaches' )) {     
    require_once (_PS_ROOT_DIR_.'/override/Dor/Caches/DorCaches.php');
}

class smartbloghomelatestnews extends Module {
    
     public function __construct(){
        $this->name = 'smartbloghomelatestnews';
        $this->tab = 'front_office_features';
        $this->version = '2.1.0';
        $this->bootstrap = true;
        $this->author = 'Dorado Themes';
        $this->secure_key = Tools::encrypt($this->name);

        parent::__construct();

        $this->displayName = $this->l('SmartBlog Home Latest');
        $this->description = $this->l('The Most Powerfull Presta shop Blog  Module\'s tag - by doradothemes');
        $this->confirmUninstall = $this->l('Are you sure you want to delete your details ?');
        }
        
     public function install(){
                $langs = Language::getLanguages();
                $id_lang = (int) Configuration::get('PS_LANG_DEFAULT');
                 if (!parent::install() 
                 || !$this->registerHook('header')
                 || !$this->registerHook('actionsbdeletepost')
                 || !$this->registerHook('actionsbnewpost')
                 || !$this->registerHook('actionsbupdatepost')
                 || !$this->registerHook('actionsbtogglepost')
                 || !$this->registerHook('rightColumn')
                 || !$this->registerHook('leftColumn')
                 || !$this->registerHook('DorHomeLatestNews')
                 )
            return false;
                 Configuration::updateGlobalValue('typeData',0);
                 Configuration::updateGlobalValue('smartshowhomepost',3);
                 Configuration::updateGlobalValue('blog_content_custom',"");
                 Configuration::updateGlobalValue('blog_quanlity_image',100);
                 Configuration::updateGlobalValue('blog_thumb_width',470);
                 Configuration::updateGlobalValue('blog_thumb_height',280);
                 return true;
            }
            
     public function uninstall() {
     $this->DeleteCache();
            if (!parent::uninstall() || !Configuration::deleteByName('typeData') || !Configuration::deleteByName('smartshowhomepost')|| !Configuration::deleteByName('blog_content_custom') || !Configuration::deleteByName('blog_quanlity_image')  || !Configuration::deleteByName('blog_thumb_width')  || !Configuration::deleteByName('blog_thumb_height') || !Configuration::deleteByName('smartshowhomepostcolumn'))
                 return false;
            return true;
                }
     public function hookdorHomeLatestNews($params) {
                $dorTimeCache  = Configuration::get('dor_themeoptions_dorTimeCache',Configuration::get('dorTimeCache'));
                $dorTimeCache = $dorTimeCache?$dorTimeCache:86400;
                $id_shop = (int) Context::getContext()->shop->id;
                $id_lang = (int)Context::getContext()->language->id;
                $languages = Language::getLanguages(true, $this->context->shop->id);
                if(Module::isInstalled('smartblog') != 1){
                        $this->smarty->assign( array(
                                  'smartmodname' => $this->name
                         ));
                        return $this->display(__FILE__, 'views/templates/front/install_required.tpl');
                }
                else
                {
                    $styleTmp = "smartblog_latest_news_home.tpl";
                    $typeData = Configuration::get('typeData');
                            if (!$this->isCached('smartblog_latest_news.tpl', $this->getCacheId()))
                                {
                                    $blogcomment = new Blogcomment();
                                    
                                    $thumbWidth = Configuration::get('blog_thumb_width');
                                    $thumbHeight = Configuration::get('blog_thumb_height');
                                    $quanlity = Configuration::get('blog_quanlity_image');
                                    $dorCaches  = Configuration::get('dor_themeoptions_enableDorCache',Configuration::get('enableDorCache'));
                                    $objCache = new DorCaches(array('name'=>'default','path'=>_PS_ROOT_DIR_.'/override/Dor/Caches/smartcaches/homeblogs/','extension'=>'.cache'));
                                    $fileCache = 'SmartBlogHomeCaches-Shop'.$id_shop.'-'.$id_lang;
                                    $objCache->setCache($fileCache);
                                    $cacheData = $objCache->renderData($fileCache);
                                    if($cacheData && $dorCaches){
                                        $dataItems = $cacheData['lists'];
                                    }else{
                                        //Configuration::get('smartshowhomepost')
                                        $view_data['posts'] = SmartBlogPost::GetPostLatestHome(Configuration::get('smartshowhomepost')); 
                                        $dataItems = array();
                                        if(!empty($view_data['posts'])){
                                            foreach ($view_data['posts'] as $key => $item) {
                                                $totalCmt = $blogcomment->getToltalComment($item['id']);
                                                $item['totalcomment'] = $totalCmt == ""?0:$totalCmt;
                                                $pathImg = "smartblog/images/".$item['post_img'].".jpg";
                                                $width=isset($thumbWidth) && $thumbWidth != ""?$thumbWidth:370;
                                                $height=isset($thumbHeight) && $thumbHeight != ""?$thumbHeight:211;
                                                $images = DorImageBase::renderThumb($pathImg,$width,$height,'','',true,$quanlity);
                                                $item['thumb_image'] = $images;
                                                $dataItems[$key] = $item;
                                            }
                                            if($dorCaches){
                                                $data['lists'] = $dataItems;
                                                $objCache->store($fileCache, $data, $expiration = $dorTimeCache);
                                            }
                                        }
                                    }
                                    $this->smarty->assign( array(
                                            'view_data'          => $dataItems,
                                            'languages'          => $languages,
                                            'smartshowauthorstyle'=>Configuration::get('smartshowauthorstyle'),
                                            'smartshowauthor'=>Configuration::get('smartshowauthor'),
                                            'description' => (string)Configuration::get('blog_content_custom'),
                                            'column' => (int)Configuration::get('smartshowhomepostcolumn')
                                    ));
                                }
                        if($typeData == 1) $styleTmp = "dorblog_latest_news_home.tpl";
                        elseif ($typeData == 2) $styleTmp = "dorblog_latest_news_home_style3.tpl";
                        elseif ($typeData == 3) $styleTmp = "dorblog_latest_news_home_style4.tpl";
                            return $this->display(__FILE__, 'views/templates/front/'.$styleTmp, $this->getCacheId());
                }  
            }
     public function getContent(){
                $html = '';
                if(Tools::isSubmit('save'.$this->name))
                {
                    Configuration::updateValue('typeData', Tools::getvalue('typeData'));
                    Configuration::updateValue('smartshowhomepost', Tools::getvalue('smartshowhomepost'));
                    Configuration::updateValue('blog_content_custom', Tools::getvalue('blog_content_custom'));
                    Configuration::updateValue('blog_quanlity_image', Tools::getvalue('blog_quanlity_image'));
                    Configuration::updateValue('blog_thumb_width', Tools::getvalue('blog_thumb_width'));
                    Configuration::updateValue('blog_thumb_height', Tools::getvalue('blog_thumb_height'));
                    $html = $this->displayConfirmation($this->l('The settings have been updated successfully.'));
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
        $typeData = array(
            array( 'id'=>'0','mode'=>'Style 1'),
            array('id'=>'1','mode'=>'Style 2'),
            array('id'=>'2','mode'=>'Style 3'),
            array('id'=>'3','mode'=>'Style 4'),
        );
        $this->fields_form[0]['form'] = array(
          'legend' => array(
          'title' => Context::getContext()->getTranslator()->trans('General Setting', array(), 'Modules.smartbloghomelatestnews'),
            ),
            'input' => array(
                array(
                        'type' => 'select',
                        'label' => 'Style: ',
                        'name' => 'typeData',
                        'options' => array(
                            'query' => $typeData,
                            'id' => 'id',
                            'name'=>'mode',
                        ),
                    ),
                array(
                    'type' => 'text',
                    'label' => Context::getContext()->getTranslator()->trans('Number of posts to dispay in Lastest News', array(), 'Modules.smartbloghomelatestnews'),
                    'name' => 'smartshowhomepost',
                    'size' => 15,
                    'required' => true
                ),
                array(
                    'type' => 'textarea',
                    'label' => Context::getContext()->getTranslator()->trans('Custom Text Content:', array(), 'Modules.smartbloghomelatestnews'),
                    'name' => 'blog_content_custom',
                    'class' => 'fixed-width-full',
                    'cols' => 60,
                    'rows' => 10,
                ),
                array(
                    'type' => 'text',
                    'label' => Context::getContext()->getTranslator()->trans('Quanlity Image:', array(), 'Modules.smartbloghomelatestnews'),
                    'name' => 'blog_quanlity_image',
                    'class' => 'fixed-width-md',
                ),
                array(
                    'type' => 'text',
                    'label' => Context::getContext()->getTranslator()->trans('Thumb width image:', array(), 'Modules.smartbloghomelatestnews'),
                    'name' => 'blog_thumb_width',
                    'class' => 'fixed-width-md',
                ),
                array(
                    'type' => 'text',
                    'label' => Context::getContext()->getTranslator()->trans('Thumb height image:', array(), 'Modules.smartbloghomelatestnews'),
                    'name' => 'blog_thumb_height',
                    'class' => 'fixed-width-md',
                ),
            ),
            'submit' => array(
                'title' => Context::getContext()->getTranslator()->trans('Save', array(), 'Modules.smartbloghomelatestnews'),
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
                'desc' => Context::getContext()->getTranslator()->trans('Save', array(), 'Modules.smartbloghomelatestnews'),
                'href' => AdminController::$currentIndex . '&configure=' . $this->name . '&save'.$this->name.'token=' . Tools::getAdminTokenLite('AdminModules'),
            )
        );
        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;
        $helper->title = $this->displayName;
        $helper->show_toolbar = true;       
        $helper->toolbar_scroll = true;    
        $helper->submit_action = 'save'.$this->name;
        
        $helper->fields_value['typeData'] = Configuration::get('typeData');
        $helper->fields_value['smartshowhomepost'] = Configuration::get('smartshowhomepost');
        $helper->fields_value['blog_content_custom'] = Configuration::get('blog_content_custom');
        $helper->fields_value['smartshowhomepostcolumn'] = Configuration::get('smartshowhomepostcolumn');
        $helper->fields_value['blog_quanlity_image'] = Configuration::get('blog_quanlity_image');
        $helper->fields_value['blog_thumb_width'] = Configuration::get('blog_thumb_width');
        $helper->fields_value['blog_thumb_height'] = Configuration::get('blog_thumb_height');
        return $helper;
      }
    public function DeleteCache()
            {
                return $this->_clearCache('smartblog_latest_news.tpl', $this->getCacheId());
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
    public function hookHeader($params)
    {
        if (!isset($this->context->controller->php_self) || $this->context->controller->php_self != 'index')
            return;
        $this->context->controller->addCSS(($this->_path).'css/dor_homeblog.css', 'all');
    }
}