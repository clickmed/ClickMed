<?php

if (!defined('_PS_VERSION_'))
    exit;
if (!defined('_MYSQL_ENGINE_'))
    define('_MYSQL_ENGINE_', 'MyISAM');
class dor_themeoptions extends Module
{
    var $prefix             = '';
    var $amounts            = 4;
    var $base_config_url    = '';
    var $overrideHooks      = array();
    public function __construct()
    {
        global $currentIndex;
        $this->name                     = 'dor_themeoptions';
        $this->tab                      = 'front_office_features';
        $this->version                  = '1.0';
        $this->bootstrap                = true ;
        $this->author                   = 'Dorado Themes';
        $this->need_instance            = 0;
        $this->ps_versions_compliancy   = array('min' => '1.5', 'max' => '1.7');
        $this->currentIndex             = $currentIndex;
        $this->assetsTheme              = 'assets/css/modules/'.$this->name;
        $this->dorThemeUrl              = __PS_BASE_URI__.'themes/'._THEME_NAME_.'/'.$this->assetsTheme;
        $this->directory                = _PS_ROOT_DIR_.'/themes/'._THEME_NAME_.'/assets/css/dorado/color';
        $this->directoryModule          = _PS_ROOT_DIR_.'/modules/'.$this->name.'/css/color';
        $this->pathTmpColor = 1;
        $this->scanned_directory = array_diff(scandir($this->directory), array('..', '.'));
        if(count($this->scanned_directory) == 0){
            $this->pathTmpColor = 0;
            $this->scanned_directory = array_diff(scandir($this->directoryModule), array('..', '.'));
        }
        parent::__construct();
        $this->displayName              = $this->l('Dor Theme Options');
        $this->description              = $this->l('Dor Theme Configuration');
        $this->confirmUninstall         = $this->l('Are you sure you want to uninstall?');
        if (!Configuration::get('THEMEOPTIONS'))
            $this->warning = $this->l('No name provided');

        $this->codeColor = array();
        if(count($this->scanned_directory) > 0){
            foreach ($this->scanned_directory as $key => $value) {
                $valCode = str_replace(".css","",$value);
                $this->codeColor[] = $valCode;
            }
        }
    }

   public function install()
    {
		if(!(int)Tab::getIdFromClassName('AdminDorMenu')) {
			$parent_tab = new Tab();
			$parent_tab->name[$this->context->language->id] = $this->l('Dor Extensions');
			$parent_tab->class_name = 'AdminDorMenu';
			$parent_tab->id_parent = 0;
			$parent_tab->module = $this->name;
			$parent_tab->add();
		}
        $tab = new Tab();
		foreach (Language::getLanguages() as $language)
        $tab->name[$language['id_lang']] = $this->l('Dor Theme Configuration');
        $tab->class_name = 'Admindorthemeoptions';
        $tab->id_parent = (int)Tab::getIdFromClassName('AdminDorMenu'); 
        $tab->module = $this->name;
        $tab->add();
        if (parent::install() && $this->registerHook('dorthemeoptions') && $this->registerHook('displayHeader') && $this->registerHook('displayTop') && $this->registerHook('displayBackOfficeHeader')) {
            $res = Configuration::updateValue($this->name . '_dorHeaderBgOutside','');
            $res &= Configuration::updateValue($this->name . '_dorHeaderBgColor','');
            $res &= Configuration::updateValue($this->name . '_dorHeaderColorIcon','');
            $res &= Configuration::updateValue($this->name . '_dorHeaderColorLink','');
            $res &= Configuration::updateValue($this->name . '_orHeaderColorLinkHover','');
            $res &= Configuration::updateValue($this->name . '_dorHeaderColorText','');
            $res &= Configuration::updateValue($this->name . '_dorMegamenuBgOutside','');
            $res &= Configuration::updateValue($this->name . '_dorMegamenuBgColor','');
            $res &= Configuration::updateValue($this->name . '_dorMegamenuColorText','');
            $res &= Configuration::updateValue($this->name . '_dorMegamenuColorLink','');
            $res &= Configuration::updateValue($this->name . '_dorMegamenuColorLinkHover','');
            $res &= Configuration::updateValue($this->name . '_dorMegamenuColorSubText','');
            $res &= Configuration::updateValue($this->name . '_dorMegamenuColorSubLink','');
            $res &= Configuration::updateValue($this->name . '_dorMegamenuColorSubLinkHover','');

            $res &= Configuration::updateValue($this->name . '_dorVermenuBgOutside','');
            $res &= Configuration::updateValue($this->name . '_dorVermenuBgColor','');
            $res &= Configuration::updateValue($this->name . '_dorVermenuColorText','');
            $res &= Configuration::updateValue($this->name . '_dorVermenuColorLink','');
            $res &= Configuration::updateValue($this->name . '_dorVermenuColorLinkHover','');
            $res &= Configuration::updateValue($this->name . '_dorVermenuColorSubText','');
            $res &= Configuration::updateValue($this->name . '_dorVermenuColorSubLink','');
            $res &= Configuration::updateValue($this->name . '_dorVermenuColorSubLinkHover','');




            $res &= Configuration::updateValue($this->name . '_dorFooterBgOutside','');
            $res &= Configuration::updateValue($this->name . '_dorTopbarBgOutside','');
            $res &= Configuration::updateValue($this->name . '_dorTimeCache',86400);
            $res &= Configuration::updateValue($this->name . '_dorFooterBgColor','');
            $res &= Configuration::updateValue($this->name . '_dorTopbarBgColor','');
            $res &= Configuration::updateValue($this->name . '_dorFooterColorText','');
            $res &= Configuration::updateValue($this->name . '_dorTopbarColorText','');
            $res &= Configuration::updateValue($this->name . '_dorFooterColorLink','');
            $res &= Configuration::updateValue($this->name . '_dorTopbarColorLink','');
            $res &= Configuration::updateValue($this->name . '_dorFooterColorLinkHover','');
            $res &= Configuration::updateValue($this->name . '_dorTopbarColorLinkHover','');
            $res &= Configuration::updateValue($this->name . '_dorthemecolor','');
            $res &= Configuration::updateValue($this->name . '_dorEnableBgImage','');
            $res &= Configuration::updateValue($this->name . '_dorthemebg','');
            $res &= Configuration::updateValue($this->name . '_dorEnableThemeColor','0');
            $res &= Configuration::updateValue($this->name . '_dorOptReload',1);
            $res &= Configuration::updateValue($this->name . '_dorFloatHeader',1);
            $res &= Configuration::updateValue($this->name . '_dorOptfrontend','0');
            $res &= Configuration::updateValue($this->name . '_dorSubscribe',1);
            $res &= Configuration::updateValue($this->name . '_dorEnableAwesome','1');
            $res &= Configuration::updateValue($this->name . '_dorHeaderSkin', 'headerskin1');
            $res &= Configuration::updateValue($this->name . '_dorFooterSkin', 'footerskin1');
            $res &= Configuration::updateValue($this->name . '_dorTopbarSkin','topbarskin1');
            $res &= Configuration::updateValue($this->name . '_dorCategoryThumb',1);
            $res &= Configuration::updateValue($this->name . '_dorCatQuanlity',100);
            $res &= Configuration::updateValue($this->name . '_dorCatThumbWidth',250);
            $res &= Configuration::updateValue($this->name . '_dorCatThumbHeight',250);
            $res &= Configuration::updateValue($this->name . '_dorlayoutmode','full');
            $res &= Configuration::updateValue($this->name . '_dorDetailThumbList','');
            $res &= Configuration::updateValue($this->name . '_dorDetailInfoStyle','');
            $res &= Configuration::updateValue($this->name . '_dorCategoryEffect',1);
            $res &= Configuration::updateValue($this->name . '_dorCategoryShow','grid');
            $res &= Configuration::updateValue($this->name . '_dorBlogsCols','proBlogCol3');
            $res &= Configuration::updateValue($this->name . '_dorBlogsDetailCols','proBlogDetailCol3');
            $res &= Configuration::updateValue($this->name . '_dorSubsPop',1);
            $res &= Configuration::updateValue($this->name . '_enableAngularJs',0);
            $res &= Configuration::updateValue($this->name . '_enableDorCache',0);
            $res &= Configuration::updateValue($this->name . '_dorDetailMainImage','left');
        return (bool)$res;
        }
    }

    public function uninstall()
    {
        Configuration::deleteByName('THEMEOPTIONS');
        $tab = new Tab((int)Tab::getIdFromClassName('Admindorthemeoptions'));
        $tab->delete();
        if (!parent::uninstall())
            return false;
        return true;
    }

    function getContent()
    {
        $errors = array();
        $this->_html = '<h2>' . $this->displayName . '</h2>';
        if (Tools::isSubmit('submitUpdate')) {
            Configuration::updateValue($this->name . '_dorFooterBgOutside',Tools::getValue('dorFooterBgOutside'));
            Configuration::updateValue($this->name . '_dorTopbarBgOutside',Tools::getValue('dorTopbarBgOutside'));
            Configuration::updateValue($this->name . '_dorFooterBgColor',Tools::getValue('dorFooterBgColor'));
            Configuration::updateValue($this->name . '_dorTopbarBgColor',Tools::getValue('dorTopbarBgColor'));
            Configuration::updateValue($this->name . '_dorFooterColorText',Tools::getValue('dorFooterColorText'));
            Configuration::updateValue($this->name . '_dorTopbarColorText',Tools::getValue('dorTopbarColorText'));
            Configuration::updateValue($this->name . '_dorFooterColorLink',Tools::getValue('dorFooterColorLink'));
            Configuration::updateValue($this->name . '_dorTopbarColorLink',Tools::getValue('dorTopbarColorLink'));
            Configuration::updateValue($this->name . '_dorEnableThemeColor',Tools::getValue('dorEnableThemeColor'));
            Configuration::updateValue($this->name . '_dorOptfrontend',Tools::getValue('dorOptfrontend'));

            $this->_html .= $this->displayConfirmation($this->l('Settings updated successfully.'));
        }
        else if (Tools::isSubmit('submitUpdateFont')) {
            Configuration::updateValue($this->name . '_dorFont',Tools::getValue('dorFont'));
            Configuration::updateValue($this->name . '_dorEnableAwesome',Tools::getValue('dorEnableAwesome'));
            $this->_html .= $this->displayConfirmation($this->l('Settings updated successfully.'));
        }
        else if(Tools::isSubmit('submitUpdateheader')){
            Configuration::updateValue($this->name . '_dorHeaderBgOutside',Tools::getValue('dorHeaderBgOutside'));
            Configuration::updateValue($this->name . '_dorHeaderBgColor',Tools::getValue('dorHeaderBgColor'));
            Configuration::updateValue($this->name . '_dorHeaderColorIcon',Tools::getValue('dorHeaderColorIcon'));
            Configuration::updateValue($this->name . '_dorHeaderColorLink',Tools::getValue('dorHeaderColorLink'));
            Configuration::updateValue($this->name . '_dorHeaderColorLinkHover',Tools::getValue('dorHeaderColorLinkHover'));
            Configuration::updateValue($this->name . '_dorHeaderColorText',Tools::getValue('dorHeaderColorText'));
            Configuration::updateValue($this->name . '_dorHeaderSkin', Tools::getValue('dorHeaderSkin'));
            Configuration::updateValue($this->name . '_dorFloatHeader',Tools::getValue('dorFloatHeader'));
            $this->_html .= $this->displayConfirmation($this->l('Settings updated successfully.'));
        }
        else if(Tools::isSubmit('submitUpdateTopbar')){
            Configuration::updateValue($this->name . '_dorTopbarBgOutside',Tools::getValue('dorTopbarBgOutside'));
            Configuration::updateValue($this->name . '_dorTopbarBgColor',Tools::getValue('dorTopbarBgColor'));
            Configuration::updateValue($this->name . '_dorTopbarColorText',Tools::getValue('dorTopbarColorText'));
            Configuration::updateValue($this->name . '_dorTopbarColorLink',Tools::getValue('dorTopbarColorLink'));
            Configuration::updateValue($this->name . '_dorTopbarColorLinkHover',Tools::getValue('dorTopbarColorLinkHover'));
            Configuration::updateValue($this->name . '_dorTopbarSkin',Tools::getValue('dorTopbarSkin'));
            $this->_html .= $this->displayConfirmation($this->l('Settings updated successfully.'));
        }
        else if(Tools::isSubmit('submitUpdateMegamenu')){
            Configuration::updateValue($this->name . '_dorMegamenuBgOutside',Tools::getValue('dorMegamenuBgOutside'));
            Configuration::updateValue($this->name . '_dorMegamenuBgColor',Tools::getValue('dorMegamenuBgColor'));
            Configuration::updateValue($this->name . '_dorMegamenuColorText',Tools::getValue('dorMegamenuColorText'));
            Configuration::updateValue($this->name . '_dorMegamenuColorLink',Tools::getValue('dorMegamenuColorLink'));
            Configuration::updateValue($this->name . '_dorMegamenuColorLinkHover',Tools::getValue('dorMegamenuColorLinkHover'));
            Configuration::updateValue($this->name . '_dorMegamenuColorSubText',Tools::getValue('dorMegamenuColorSubText'));
            Configuration::updateValue($this->name . '_dorMegamenuColorSubLink',Tools::getValue('dorMegamenuColorSubLink'));
            Configuration::updateValue($this->name . '_dorMegamenuColorSubLinkHover',Tools::getValue('dorMegamenuColorSubLinkHover'));
            $this->_html .= $this->displayConfirmation($this->l('Settings updated successfully.'));
        }
        else if(Tools::isSubmit('submitUpdateVermenu')){
            Configuration::updateValue($this->name . '_dorVermenuBgOutside',Tools::getValue('dorVermenuBgOutside'));
            Configuration::updateValue($this->name . '_dorVermenuBgColor',Tools::getValue('dorVermenuBgColor'));
            Configuration::updateValue($this->name . '_dorVermenuColorText',Tools::getValue('dorVermenuColorText'));
            Configuration::updateValue($this->name . '_dorVermenuColorLink',Tools::getValue('dorVermenuColorLink'));
            Configuration::updateValue($this->name . '_dorVermenuColorLinkHover',Tools::getValue('dorVermenuColorLinkHover'));
            Configuration::updateValue($this->name . '_dorVermenuColorSubText',Tools::getValue('dorVermenuColorSubText'));
            Configuration::updateValue($this->name . '_dorVermenuColorSubLink',Tools::getValue('dorVermenuColorSubLink'));
            Configuration::updateValue($this->name . '_dorVermenuColorSubLinkHover',Tools::getValue('dorVermenuColorSubLinkHover'));
            $this->_html .= $this->displayConfirmation($this->l('Settings updated successfully.'));
        }
        else if(Tools::isSubmit('submitUpdateFooter')){
            Configuration::updateValue($this->name . '_dorFooterBgOutside',Tools::getValue('dorFooterBgOutside'));
            Configuration::updateValue($this->name . '_dorFooterBgColor',Tools::getValue('dorFooterBgColor'));
            Configuration::updateValue($this->name . '_dorFooterColorText',Tools::getValue('dorFooterColorText'));
            Configuration::updateValue($this->name . '_dorFooterColorLink',Tools::getValue('dorFooterColorLink'));
            Configuration::updateValue($this->name . '_dorFooterColorLinkHover',Tools::getValue('dorFooterColorLinkHover'));
            Configuration::updateValue($this->name . '_dorFooterSkin', Tools::getValue('dorFooterSkin'));
            $this->_html .= $this->displayConfirmation($this->l('Settings updated successfully.'));
        }
        else if(Tools::isSubmit('submitUpdateDorAdvance')){
            Configuration::updateValue($this->name . '_dorDetailCols',Tools::getValue('dorDetailCols'));
            Configuration::updateValue($this->name . '_dorCategoryCols',Tools::getValue('dorCategoryCols'));
            Configuration::updateValue($this->name . '_proCateRowNumber',Tools::getValue('proCateRowNumber'));
            Configuration::updateValue($this->name . '_detailReview',Tools::getValue('detailReview'));
            Configuration::updateValue($this->name . '_detailLabel',Tools::getValue('detailLabel'));
            Configuration::updateValue($this->name . '_detailReduction',Tools::getValue('detailReduction'));
            Configuration::updateValue($this->name . '_detailOldPrice',Tools::getValue('detailOldPrice'));
            Configuration::updateValue($this->name . '_detailpQuantityAvailable',Tools::getValue('detailpQuantityAvailable'));
            Configuration::updateValue($this->name . '_detailavailability_statut',Tools::getValue('detailavailability_statut'));
            Configuration::updateValue($this->name . '_detailcompare',Tools::getValue('detailcompare'));
            Configuration::updateValue($this->name . '_detailwishlist',Tools::getValue('detailwishlist'));
            Configuration::updateValue($this->name . '_detaillinkblock',Tools::getValue('detaillinkblock'));
            Configuration::updateValue($this->name . '_detailsocialsharing',Tools::getValue('detailsocialsharing'));
            Configuration::updateValue($this->name . '_dorCategoryThumb',Tools::getValue('dorCategoryThumb'));
            Configuration::updateValue($this->name . '_dorCatQuanlity',Tools::getValue('dorCatQuanlity'));
            Configuration::updateValue($this->name . '_dorCatThumbWidth',Tools::getValue('dorCatThumbWidth'));
            Configuration::updateValue($this->name . '_dorCatThumbHeight',Tools::getValue('dorCatThumbHeight'));
            Configuration::updateValue($this->name . '_dorDetailReference',Tools::getValue('dorDetailReference'));
            Configuration::updateValue($this->name . '_dorDetailCondition',Tools::getValue('dorDetailCondition'));
            Configuration::updateValue($this->name . '_dorDetailThumbList',Tools::getValue('dorDetailThumbList'));
            Configuration::updateValue($this->name . '_dorDetailInfoStyle',Tools::getValue('dorDetailInfoStyle'));
            Configuration::updateValue($this->name . '_dorCategoryEffect',Tools::getValue('dorCategoryEffect'));
            Configuration::updateValue($this->name . '_dorCategoryShow',Tools::getValue('dorCategoryShow'));
            Configuration::updateValue($this->name . '_dorBlogsCols',Tools::getValue('dorBlogsCols'));
            Configuration::updateValue($this->name . '_dorBlogsDetailCols',Tools::getValue('dorBlogsDetailCols'));
            Configuration::updateValue($this->name . '_dorSubsPop',Tools::getValue('dorSubsPop'));
            Configuration::updateValue($this->name . '_enableAngularJs',Tools::getValue('enableAngularJs'));
            Configuration::updateValue($this->name . '_enableDorCache',Tools::getValue('enableDorCache'));
            Configuration::updateValue($this->name . '_dorDetailMainImage',Tools::getValue('dorDetailMainImage'));
            Configuration::updateValue($this->name . '_dorTimeCache',Tools::getValue('dorTimeCache'));
            $this->_html .= $this->displayConfirmation($this->l('Settings updated successfully.'));
        }
        else if(Tools::isSubmit('submitUpdateThemeskin')){
            Configuration::updateValue($this->name . '_dorthemebg',Tools::getValue('dorthemebg'));
            Configuration::updateValue($this->name . '_dorthemecolor',Tools::getValue('dorthemecolor'));
            Configuration::updateValue($this->name . '_dorlayoutmode',Tools::getValue('dorlayoutmode'));
            Configuration::updateValue($this->name . '_dorOptfrontend',Tools::getValue('dorOptfrontend'));
            Configuration::updateValue($this->name . '_dorEnableBgImage',Tools::getValue('dorEnableBgImage'));
            Configuration::updateValue($this->name . '_dorEnableThemeColor',Tools::getValue('dorEnableThemeColor'));
            Configuration::updateValue($this->name . '_dorSubscribe',Tools::getValue('dorSubscribe'));
            Configuration::updateValue($this->name . '_dorOptReload',Tools::getValue('dorOptReload'));
            
            $this->_html .= $this->displayConfirmation($this->l('Settings updated successfully.'));
        }else if(Tools::isSubmit('submitDorClearCache')){
            $fullPath = _PS_ROOT_DIR_.'/override/Dor/Caches/smartcaches';
            $paths = glob($fullPath.'/*');
            if(count($paths) > 0){
                foreach ($paths as $key => $path) {
                    if (is_dir($path)) {
                        $files = glob($path.'/*');
                        foreach($files as $file){
                          if(is_file($file))
                            unlink($file);
                        }
                    }
                }
            }
        }
        if (sizeof($errors)) {
            foreach ($errors AS $err) {
                $this->_html .= '<div class="alert error">' . $err . '</div>';
            }
        }
        $this->_html .= $this->renderForm();
        return $this->_html;
    }


    public  function renderForm(){
        $this->context->controller->addJqueryPlugin('colorpicker');
        $action                     = 'index.php?controller=AdminModules&configure='.$this->name.'&tab_module=front_office_features&module_name='.$this->name.'&token='.Tools::getValue('token').' ';
        $dorHeaderBgOutside         = Configuration::get($this->name . '_dorHeaderBgOutside',Configuration::get('dorHeaderBgOutside'));
        $dorHeaderBgColor           = Configuration::get($this->name . '_dorHeaderBgColor',Configuration::get('dorHeaderBgColor'));
        $dorHeaderColorIcon           = Configuration::get($this->name . '_dorHeaderColorIcon',Configuration::get('dorHeaderColorIcon'));
        $dorHeaderColorLink         = Configuration::get($this->name . '_dorHeaderColorLink', Configuration::get('dorHeaderColorLink'));
        $dorHeaderColorLinkHover    =  Configuration::get($this->name . '_dorHeaderColorLinkHover',Configuration::get('dorHeaderColorLinkHover'));
        $dorHeaderColorText         = Configuration::get($this->name . '_dorHeaderColorText',Configuration::get('dorHeaderColorText'));
        // footer
        $dorthemebg                 = Configuration::get($this->name . '_dorthemebg',Configuration::get('dorthemebg'));
        $dorlayoutmode              = Configuration::get($this->name . '_dorlayoutmode',Configuration::get('dorlayoutmode'));
        $dorOptfrontend             = Configuration::get($this->name . '_dorOptfrontend',Configuration::get('dorOptfrontend'));
        $dorSubscribe             = Configuration::get($this->name . '_dorSubscribe',Configuration::get('dorSubscribe'));
        $dorEnableThemeColor        = Configuration::get($this->name . '_dorEnableThemeColor',Configuration::get('dorEnableThemeColor'));
        $dorOptReload               = Configuration::get($this->name . '_dorOptReload',Configuration::get('dorOptReload'));
        $dorEnableAwesome           = Configuration::get($this->name . '_dorEnableAwesome',Configuration::get('dorEnableAwesome'));
        $dorEnableBgImage           = Configuration::get($this->name . '_dorEnableBgImage',Configuration::get('dorEnableBgImage'));
        $dorthemecolor              = Configuration::get($this->name . '_dorthemecolor',Configuration::get('dorthemecolor'));
        $dorFooterBgOutside         = Configuration::get($this->name . '_dorFooterBgOutside',Configuration::get('dorFooterBgOutside'));
        $dorTopbarBgOutside         = Configuration::get($this->name . '_dorTopbarBgOutside',Configuration::get('dorTopbarBgOutside'));
        $dorFooterBgColor           = Configuration::get($this->name . '_dorFooterBgColor',Configuration::get('dorFooterBgColor'));
        $dorTopbarBgColor           = Configuration::get($this->name . '_dorTopbarBgColor',Configuration::get('dorTopbarBgColor'));
        $dorFooterColorText         = Configuration::get($this->name . '_dorFooterColorText',Configuration::get('dorFooterColorText'));
        $dorTopbarColorText         = Configuration::get($this->name . '_dorTopbarColorText',Configuration::get('dorTopbarColorText'));
        $dorFooterColorLink         = Configuration::get($this->name . '_dorFooterColorLink',Configuration::get('dorFooterColorLink'));
        $dorTopbarColorLink         = Configuration::get($this->name . '_dorTopbarColorLink',Configuration::get('dorTopbarColorLink'));
        $dorFooterColorLinkHover    = Configuration::get($this->name . '_dorFooterColorLinkHover',Configuration::get('dorFooterColorLinkHover'));
        $dorTopbarColorLinkHover    = Configuration::get($this->name . '_dorTopbarColorLinkHover',Configuration::get('dorTopbarColorLinkHover'));


        $dorMegamenuBgOutside         = Configuration::get($this->name . '_dorMegamenuBgOutside',Configuration::get('dorMegamenuBgOutside'));
        $dorMegamenuBgColor           = Configuration::get($this->name . '_dorMegamenuBgColor',Configuration::get('dorMegamenuBgColor'));
        $dorMegamenuColorText         = Configuration::get($this->name . '_dorMegamenuColorText',Configuration::get('dorMegamenuColorText'));
        $dorMegamenuColorLink         = Configuration::get($this->name . '_dorMegamenuColorLink',Configuration::get('dorMegamenuColorLink'));
        $dorMegamenuColorLinkHover    = Configuration::get($this->name . '_dorMegamenuColorLinkHover',Configuration::get('dorMegamenuColorLinkHover'));
        $dorMegamenuColorSubText         = Configuration::get($this->name . '_dorMegamenuColorSubText',Configuration::get('dorMegamenuColorSubText'));
        $dorMegamenuColorSubLink         = Configuration::get($this->name . '_dorMegamenuColorSubLink',Configuration::get('dorMegamenuColorSubLink'));
        $dorMegamenuColorSubLinkHover    = Configuration::get($this->name . '_dorMegamenuColorSubLinkHover',Configuration::get('dorMegamenuColorSubLinkHover'));


        $dorVermenuBgOutside         = Configuration::get($this->name . '_dorVermenuBgOutside',Configuration::get('dorVermenuBgOutside'));
        $dorVermenuBgColor           = Configuration::get($this->name . '_dorVermenuBgColor',Configuration::get('dorVermenuBgColor'));
        $dorVermenuColorText         = Configuration::get($this->name . '_dorVermenuColorText',Configuration::get('dorVermenuColorText'));
        $dorVermenuColorLink         = Configuration::get($this->name . '_dorVermenuColorLink',Configuration::get('dorVermenuColorLink'));
        $dorVermenuColorLinkHover    = Configuration::get($this->name . '_dorVermenuColorLinkHover',Configuration::get('dorVermenuColorLinkHover'));
        $dorVermenuColorSubText         = Configuration::get($this->name . '_dorVermenuColorSubText',Configuration::get('dorVermenuColorSubText'));
        $dorVermenuColorSubLink         = Configuration::get($this->name . '_dorVermenuColorSubLink',Configuration::get('dorVermenuColorSubLink'));
        $dorVermenuColorSubLinkHover    = Configuration::get($this->name . '_dorVermenuColorSubLinkHover',Configuration::get('dorVermenuColorSubLinkHover'));


        // Get font data
        $dorFont                    = Configuration::get($this->name . '_dorFont',Configuration::get('dorFont'));
        // Get skin header
        $dorHeaderSkin              = Configuration::get($this->name . '_dorHeaderSkin',Configuration::get('dorHeaderSkin'));
        $dorFloatHeader              = Configuration::get($this->name . '_dorFloatHeader',Configuration::get('dorFloatHeader'));
        // Get skin footer
        $dorFooterSkin              = Configuration::get($this->name . '_dorFooterSkin',Configuration::get('dorFooterSkin'));
        $dorDetailCols              = Configuration::get($this->name . '_dorDetailCols',Configuration::get('dorDetailCols'));
        $dorCategoryCols              = Configuration::get($this->name . '_dorCategoryCols',Configuration::get('dorCategoryCols'));
        $proCateRowNumber              = Configuration::get($this->name . '_proCateRowNumber',Configuration::get('proCateRowNumber'));
        $dorTopbarSkin              = Configuration::get($this->name . '_dorTopbarSkin',Configuration::get('dorTopbarSkin'));
        $dorCategoryThumb              = Configuration::get($this->name . '_dorCategoryThumb',Configuration::get('dorCategoryThumb'));
        $dorCatQuanlity              = Configuration::get($this->name . '_dorCatQuanlity',Configuration::get('dorCatQuanlity'));
        $dorCatThumbWidth              = Configuration::get($this->name . '_dorCatThumbWidth',Configuration::get('dorCatThumbWidth'));
        $dorCatThumbHeight              = Configuration::get($this->name . '_dorCatThumbHeight',Configuration::get('dorCatThumbHeight'));
        $dorDetailLabel              = Configuration::get($this->name . '_detailLabel',Configuration::get('detailLabel'));
        $dorDetailReview            = Configuration::get($this->name . '_detailReview',Configuration::get('detailReview'));
        $dorDetailOldPrice            = Configuration::get($this->name . '_detailOldPrice',Configuration::get('detailOldPrice'));
        $dorDetailReduction           = Configuration::get($this->name . '_detailReduction',Configuration::get('detailReduction'));
        $dorDetailpQuantityAvailable           = Configuration::get($this->name . '_detailpQuantityAvailable',Configuration::get('detailpQuantityAvailable'));
        $dorDetailavailabilityStatut           = Configuration::get($this->name . '_detailavailability_statut',Configuration::get('detailavailability_statut'));
        $dorDetailcompare           = Configuration::get($this->name . '_detailcompare',Configuration::get('detailcompare'));
        $dorDetailwishlist           = Configuration::get($this->name . '_detailwishlist',Configuration::get('detailwishlist'));
        $dorDetaillinkblock           = Configuration::get($this->name . '_detaillinkblock',Configuration::get('detaillinkblock'));
        $dorDetailsocialsharing           = Configuration::get($this->name . '_detailsocialsharing',Configuration::get('detailsocialsharing'));
        $dorDetailReference           = Configuration::get($this->name . '_dorDetailReference',Configuration::get('dorDetailReference'));
        $dorDetailCondition           = Configuration::get($this->name . '_dorDetailCondition',Configuration::get('dorDetailCondition'));
        $dorDetailThumbList           = Configuration::get($this->name . '_dorDetailThumbList',Configuration::get('dorDetailThumbList'));
        $dorDetailInfoStyle           = Configuration::get($this->name . '_dorDetailInfoStyle',Configuration::get('dorDetailInfoStyle'));
        $dorCategoryEffect           = Configuration::get($this->name . '_dorCategoryEffect',Configuration::get('dorCategoryEffect'));
        $dorCategoryShow           = Configuration::get($this->name . '_dorCategoryShow',Configuration::get('dorCategoryShow'));
        $dorBlogsCols               = Configuration::get($this->name . '_dorBlogsCols',Configuration::get('dorBlogsCols'));
        $dorBlogsDetailCols               = Configuration::get($this->name . '_dorBlogsDetailCols',Configuration::get('dorBlogsDetailCols'));
        $dorSubsPop               = Configuration::get($this->name . '_dorSubsPop',Configuration::get('dorSubsPop'));
        $enableAngularJs               = Configuration::get($this->name . '_enableAngularJs',Configuration::get('enableAngularJs'));
        $enableDorCache               = Configuration::get($this->name . '_enableDorCache',Configuration::get('enableDorCache'));
        $dorDetailMainImage           = Configuration::get($this->name . '_dorDetailMainImage',Configuration::get('dorDetailMainImage'));
        $dorTimeCache           = Configuration::get($this->name . '_dorTimeCache',Configuration::get('dorTimeCache'));
        
        
        $this->smarty->assign(array(
                'codeColor'                     =>$this->codeColor, // color themes
                'dorHeaderBgOutside'            =>$dorHeaderBgOutside, // header
                'dorHeaderBgColor'              =>$dorHeaderBgColor,
                'dorHeaderColorIcon'              =>$dorHeaderColorIcon,
                'dorHeaderColorLink'            =>$dorHeaderColorLink,
                'dorHeaderColorLinkHover'       =>$dorHeaderColorLinkHover,
                'dorHeaderColorText'            =>$dorHeaderColorText,

                'dorMegamenuBgOutside'          =>$dorMegamenuBgOutside, // Megamenu
                'dorMegamenuBgColor'            =>$dorMegamenuBgColor,
                'dorMegamenuColorLink'          =>$dorMegamenuColorLink,
                'dorMegamenuColorLinkHover'     =>$dorMegamenuColorLinkHover,
                'dorMegamenuColorText'          =>$dorMegamenuColorText,
                'dorMegamenuColorSubLink'          =>$dorMegamenuColorSubLink,
                'dorMegamenuColorSubLinkHover'     =>$dorMegamenuColorSubLinkHover,
                'dorMegamenuColorSubText'          =>$dorMegamenuColorSubText,

                'dorVermenuBgOutside'          =>$dorVermenuBgOutside, // Vermenu
                'dorVermenuBgColor'            =>$dorVermenuBgColor,
                'dorVermenuColorLink'          =>$dorVermenuColorLink,
                'dorVermenuColorLinkHover'     =>$dorVermenuColorLinkHover,
                'dorVermenuColorText'          =>$dorVermenuColorText,
                'dorVermenuColorSubLink'          =>$dorVermenuColorSubLink,
                'dorVermenuColorSubLinkHover'     =>$dorVermenuColorSubLinkHover,
                'dorVermenuColorSubText'          =>$dorVermenuColorSubText,

                //footer
                'dorFooterBgOutside'            => $dorFooterBgOutside,
                'dorTopbarBgOutside'            => $dorTopbarBgOutside,
                'dorFooterBgColor'              => $dorFooterBgColor,
                'dorTopbarBgColor'              => $dorTopbarBgColor,
                'dorFooterColorText'            =>$dorFooterColorText ,
                'dorTopbarColorText'            =>$dorTopbarColorText ,
                'dorFooterColorLink'            =>$dorFooterColorLink ,
                'dorTopbarColorLink'            =>$dorTopbarColorLink ,
                'dorFooterColorLinkHover'       =>$dorFooterColorLinkHover ,
                'dorTopbarColorLinkHover'       =>$dorTopbarColorLinkHover ,
                //skin
                'dorthemebg'                    => $dorthemebg,
                'dorlayoutmode'                 => $dorlayoutmode,
                'dorthemecolor'                 => $dorthemecolor,
                'dorEnableBgImage'              => $dorEnableBgImage,
                'dorEnableThemeColor'           => $dorEnableThemeColor,
                'dorOptReload'                  => $dorOptReload,
                'dorEnableAwesome'              => $dorEnableAwesome,
                'dorOptfrontend'                => $dorOptfrontend,
                'dorSubscribe'                => $dorSubscribe,
                // Font
                'dorFont'                       => $dorFont,
                // Skin Footer
                'dorFooterSkin'                 => $dorFooterSkin,
                'dorDetailCols'                 => $dorDetailCols,
                'dorCategoryCols'               => $dorCategoryCols,
                'proCateRowNumber'               => $proCateRowNumber,
                'dorDetailReview'               => $dorDetailReview,
                'dorDetailLabel'                => $dorDetailLabel,
                'dorDetailReduction'                => $dorDetailReduction,
                'dorDetailOldPrice'                => $dorDetailOldPrice,
                'dorDetailReference'                => $dorDetailReference,
                'dorDetailCondition'                => $dorDetailCondition,
                'dorDetailThumbList'                => $dorDetailThumbList,
                'dorDetailInfoStyle'                => $dorDetailInfoStyle,
                'dorCategoryEffect'                => $dorCategoryEffect,
                'dorCategoryShow'                => $dorCategoryShow,
                'dorBlogsCols'                => $dorBlogsCols,
                'dorBlogsDetailCols'                => $dorBlogsDetailCols,
                'dorSubsPop'                => $dorSubsPop,
                'enableAngularJs'                => $enableAngularJs,
                'enableDorCache'                => $enableDorCache,
                'dorDetailMainImage'                => $dorDetailMainImage,
                'dorTimeCache'                => $dorTimeCache,
                'dorDetailpQuantityAvailable'                => $dorDetailpQuantityAvailable,
                'dorDetailavailabilityStatut'                => $dorDetailavailabilityStatut,
                'dorDetailcompare'                => $dorDetailcompare,
                'dorDetailwishlist'                => $dorDetailwishlist,
                'dorDetaillinkblock'                => $dorDetaillinkblock,
                'dorDetailsocialsharing'                => $dorDetailsocialsharing,
                'dorTopbarSkin'                 => $dorTopbarSkin,
                'dorCategoryThumb'                 => $dorCategoryThumb,
                'dorCatQuanlity'                 => $dorCatQuanlity,
                'dorCatThumbWidth'                 => $dorCatThumbWidth,
                'dorCatThumbHeight'                 => $dorCatThumbHeight,
                // Skin Header
                'dorHeaderSkin'                 => $dorHeaderSkin,
                'dorFloatHeader'                 => $dorFloatHeader,
                'action'                        => $action,

                'search_query' => (string)Configuration::get($this->name . '_search_query')
            )
        );
		return $this->display(__FILE__, 'views/templates/admin/adminform.tpl');
    }
    public function hookDisplayBackOfficeHeader($params) {
        $this->context->controller->addCSS($this->_path . 'css/admin.doradotheme.css');
        $this->context->controller->addCSS($this->_path . 'font/css/font-awesome.min.css');
        if (method_exists($this->context->controller, 'addJquery'))
        {        
            $this->context->controller->addJquery();
            $this->context->controller->addJS(($this->_path).'js/admin.doradotheme.js');
        }
        return $this->display(__FILE__, 'views/templates/admin/fortawesome.tpl');
    }
    function hookdisplayHeader()
    {
        
       //$this->context->controller->addJS($this->_path. "js/dorthemes.js");
       //$this->context->controller->addJS($this->_path. "js/jquery.tinycolorpicker.min.js");
        if (isset($this->context->controller->php_self) && $this->context->controller->php_self == 'category'){
            $this->context->controller->addCSS($this->_path . 'css/jquery-ui.min.css');
            $this->context->controller->addCSS($this->_path . 'css/jquery.ui.theme.min.css');
            $this->context->controller->addJS($this->_path. "js/jquery-ui.min.js");
        }

       $this->context->controller->addJS($this->_path. "js/owl.carousel.min.js");
       $this->context->controller->addJS($this->_path. "js/jquery.bpopup.min.js");
       
       $this->context->controller->addJS($this->_path. "plugins/bootstrap-select-1.9.3/js/bootstrap-select.js");
       $this->context->controller->addJS($this->_path. "plugins/scrollbar/jquery.mCustomScrollbar.concat.min.js");
       $this->context->controller->addCSS($this->_path. "plugins/bootstrap-select-1.9.3/dist/css/bootstrap-select.min.css");
	   $this->context->controller->addCSS($this->_path. "plugins/scrollbar/jquery.mCustomScrollbar.min.css");
       $this->context->controller->addCSS($this->_path . 'font/css/font-awesome.min.css');
       $this->context->controller->addCSS($this->_path. 'css/dorthemes.css' );
	   $this->context->controller->addCSS($this->_path. 'css/owl.carousel.css' );
       $this->context->controller->addCSS($this->_path. 'bootstrap/css/bootstrap.min.css' );
       $dorFloatHeader              = Configuration::get($this->name . '_dorFloatHeader');
       $dorHeaderSkin                = Configuration::get($this->name . '_dorHeaderSkin');
       $dorFooterSkin                = Configuration::get($this->name . '_dorFooterSkin');
       $dorFont                     = Configuration::get($this->name . '_dorFont');
       $dorthemecolor               = Configuration::get($this->name . '_dorthemecolor');
       $dorEnableThemeColor         = Configuration::get($this->name . '_dorEnableThemeColor');
       $dorOptReload                = Configuration::get($this->name . '_dorOptReload');
       $dorTopbarSkin               = Configuration::get($this->name . '_dorTopbarSkin');
       $dorlayoutmode               = Configuration::get($this->name . '_dorlayoutmode');
       $dorOptfrontend              = Configuration::get($this->name . '_dorOptfrontend');
       $dorSubscribe              = Configuration::get($this->name . '_dorSubscribe');
       
       
       $dorFont = isset($dorFont) && $dorFont != "" ? $dorFont : "font";
       $font_path = _PS_ALL_THEMES_DIR_._THEME_NAME_.'/css/modules/'.$this->name.'/fonts/'.$dorFont.'.css';
       if (!file_exists($font_path)){
            if($dorFont != ""){
                $this->context->controller->addCSS($this->_path. 'css/fonts/'.$dorFont.'.css' );
           }else{
                $this->context->controller->addCSS($this->_path. 'css/fonts/font.css' );
           }
       }
       /*$dorthemecolor = isset($dorthemecolor) && $dorthemecolor != "" ? $dorthemecolor : "color";
       $css_path = _PS_ALL_THEMES_DIR_._THEME_NAME_.'/css/modules/'.$this->name.'/color/'.$dorthemecolor.'.css';
       if (!file_exists($css_path)){
            if($dorthemecolor != "" && $dorEnableThemeColor == 1){
                $this->context->controller->addCSS($this->_path. 'css/color/'.$dorthemecolor.'.css' );
            }else{
                $this->context->controller->addCSS($this->_path. 'css/color/color.css' );
            }
       }*/
       
       if($dorOptfrontend == 1){
            $this->context->controller->addCSS($this->_path. 'css/dor-tool.css' );
            $this->context->controller->addJS(($this->_path).'js/dorthemes.js');
       }

       if (isset($this->context->controller->php_self) && $this->context->controller->php_self != 'product'){
            $this->context->controller->addJS(($this->_path).'bootstrap/js/bootstrap.min.js');
       }
       
	   $this->display(__FILE__, 'views/templates/admin/fortawesome.tpl');
	   

       

		global  $smarty;
        //header
        $dorHeaderBgOutside                 = Configuration::get($this->name . '_dorHeaderBgOutside');
        $dorHeaderBgColor                   = Configuration::get($this->name . '_dorHeaderBgColor');
        $dorHeaderColorIcon                   = Configuration::get($this->name . '_dorHeaderColorIcon');
        $dorHeaderColorLink                 = Configuration::get($this->name . '_dorHeaderColorLink');
        $dorHeaderColorLinkHover            = Configuration::get($this->name . '_dorHeaderColorLinkHover');
        $dorHeaderColorText                 = Configuration::get($this->name . '_dorHeaderColorText');

        //Megamenu
        $dorMegamenuBgOutside                 = Configuration::get($this->name . '_dorMegamenuBgOutside');
        $dorMegamenuBgColor                   = Configuration::get($this->name . '_dorMegamenuBgColor');
        $dorMegamenuColorLink                 = Configuration::get($this->name . '_dorMegamenuColorLink');
        $dorMegamenuColorLinkHover            = Configuration::get($this->name . '_dorMegamenuColorLinkHover');
        $dorMegamenuColorText                 = Configuration::get($this->name . '_dorMegamenuColorText');
        $dorMegamenuColorSubLink                 = Configuration::get($this->name . '_dorMegamenuColorSubLink');
        $dorMegamenuColorSubLinkHover            = Configuration::get($this->name . '_dorMegamenuColorSubLinkHover');
        $dorMegamenuColorSubText                 = Configuration::get($this->name . '_dorMegamenuColorSubText');

        //Vermenu
        $dorVermenuBgOutside                 = Configuration::get($this->name . '_dorVermenuBgOutside');
        $dorVermenuBgColor                   = Configuration::get($this->name . '_dorVermenuBgColor');
        $dorVermenuColorLink                 = Configuration::get($this->name . '_dorVermenuColorLink');
        $dorVermenuColorLinkHover            = Configuration::get($this->name . '_dorVermenuColorLinkHover');
        $dorVermenuColorText                 = Configuration::get($this->name . '_dorVermenuColorText');
        $dorVermenuColorSubLink                 = Configuration::get($this->name . '_dorVermenuColorSubLink');
        $dorVermenuColorSubLinkHover            = Configuration::get($this->name . '_dorVermenuColorSubLinkHover');
        $dorVermenuColorSubText                 = Configuration::get($this->name . '_dorVermenuColorSubText');

        // footer
        $dorthemebg                         = Configuration::get($this->name . '_dorthemebg');
        
        $dorEnableThemeColor                = Configuration::get($this->name . '_dorEnableThemeColor');
        $dorOptReload                       = Configuration::get($this->name . '_dorOptReload');
        $dorEnableAwesome                   = Configuration::get($this->name . '_dorEnableAwesome');
        $dorEnableBgImage                   = Configuration::get($this->name . '_dorEnableBgImage');
        $dorthemecolor                      = Configuration::get($this->name . '_dorthemecolor');
        $dorFooterBgOutside                 = Configuration::get($this->name . '_dorFooterBgOutside');
        $dorTopbarBgOutside                 = Configuration::get($this->name . '_dorTopbarBgOutside');
        $dorFooterBgColor                   = Configuration::get($this->name . '_dorFooterBgColor');
        $dorTopbarBgColor                   = Configuration::get($this->name . '_dorTopbarBgColor');
        $dorFooterColorText                 = Configuration::get($this->name . '_dorFooterColorText');
        $dorTopbarColorText                 = Configuration::get($this->name . '_dorTopbarColorText');
        $dorFooterColorLink                 = Configuration::get($this->name . '_dorFooterColorLink');
        $dorTopbarColorLink                 = Configuration::get($this->name . '_dorTopbarColorLink');
        $dorFooterColorLinkHover            = Configuration::get($this->name . '_dorFooterColorLinkHover');
        $dorTopbarColorLinkHover            = Configuration::get($this->name . '_dorTopbarColorLinkHover');
        $dorFont                            = Configuration::get($this->name . '_dorFont');
        $dorFloatHeader                     = Configuration::get($this->name . '_dorFloatHeader');
        $dorHeaderSkin                      = Configuration::get($this->name . '_dorHeaderSkin');
        $dorFooterSkin                      = Configuration::get($this->name . '_dorFooterSkin');
        $dorDetailCols                      = Configuration::get($this->name . '_dorDetailCols');
        $dorCategoryCols                    = Configuration::get($this->name . '_dorCategoryCols');
        $proCateRowNumber                    = Configuration::get($this->name . '_proCateRowNumber');
        $dorDetailReview                    = Configuration::get($this->name . '_detailReview');
        $dorDetailLabel                     = Configuration::get($this->name . '_detailLabel');
        $dorDetailOldPrice                  = Configuration::get($this->name . '_detailOldPrice');
        $dorDetailReference                  = Configuration::get($this->name . '_dorDetailReference');
        $dorDetailCondition                  = Configuration::get($this->name . '_dorDetailCondition');
        $dorDetailThumbList                  = Configuration::get($this->name . '_dorDetailThumbList');
        $dorDetailInfoStyle                  = Configuration::get($this->name . '_dorDetailInfoStyle');
        $dorCategoryEffect                  = Configuration::get($this->name . '_dorCategoryEffect');
        $dorCategoryShow                  = Configuration::get($this->name . '_dorCategoryShow');
        $dorBlogsCols                       = Configuration::get($this->name . '_dorBlogsCols') != ""?Configuration::get($this->name . '_dorBlogsCols'):"proBlogCol3";
        $dorBlogsDetailCols                  = Configuration::get($this->name . '_dorBlogsDetailCols') != ""?Configuration::get($this->name . '_dorBlogsDetailCols'):"proBlogDetailCol3";
        $dorDetailMainImage                  = Configuration::get($this->name . '_dorDetailMainImage');
        $dorTimeCache                  = Configuration::get($this->name . '_dorTimeCache');
        $dorSubsPop                  = Configuration::get($this->name . '_dorSubsPop');
        $enableAngularJs                  = Configuration::get($this->name . '_enableAngularJs');
        $enableDorCache                  = Configuration::get($this->name . '_enableDorCache');
        $dorDetailReduction                 = Configuration::get($this->name . '_detailReduction');
        $dorDetailpQuantityAvailable        = Configuration::get($this->name . '_detailpQuantityAvailable');
        $dorDetailavailabilityStatut       = Configuration::get($this->name . '_detailavailability_statut');
        $dorDetailcompare                   = Configuration::get($this->name . '_detailcompare');
        $dorDetailwishlist                  = Configuration::get($this->name . '_detailwishlist');
        $dorDetaillinkblock                 = Configuration::get($this->name . '_detaillinkblock');
        $dorDetailsocialsharing             = Configuration::get($this->name . '_detailsocialsharing');
        $dorTopbarSkin                      = Configuration::get($this->name . '_dorTopbarSkin');
        $dorCategoryThumb                      = Configuration::get($this->name . '_dorCategoryThumb');
        $dorCatQuanlity                      = Configuration::get($this->name . '_dorCatQuanlity');
        $dorCatThumbWidth                      = Configuration::get($this->name . '_dorCatThumbWidth');
        $dorCatThumbHeight                      = Configuration::get($this->name . '_dorCatThumbHeight');
        $dorSubscribe                      = Configuration::get($this->name . '_dorSubscribe');
        $dorCategoryShow                      = Configuration::get($this->name . '_dorCategoryShow');
        $id_shop = (int) Context::getContext()->shop->id;
        Media::addJsDef(
            array(
                'DOR' => array(
                    "dorFloatHeader"=>(int)$dorFloatHeader,
                    "dorSubscribe"=>(int)$dorSubscribe,
                    "dorOptReload"=>(int)$dorOptReload,
                    "dorCategoryShow"=>$dorCategoryShow,
                    "id_shop"=>(int)$id_shop
                )
            )
        );

        if($dorthemecolor == ""){$dorthemecolor="1696ef";}
        if($dorFont == ""){$dorFont="font";}
        if (!defined('TIME_CACHE_HOME'))
            define('TIME_CACHE_HOME', $dorTimeCache);
        $headerSkin = dirname(__FILE__).'/options/header/'.$dorHeaderSkin.'.tpl';
        $tpheaderSkin = _PS_ALL_THEMES_DIR_._THEME_NAME_.'/modules/'.$this->name.'/options/header/'.$dorHeaderSkin.'.tpl';
        if (file_exists($tpheaderSkin))
            $headerSkin = $tpheaderSkin;

        $footerSkin = dirname(__FILE__).'/options/footer/'.$dorFooterSkin.'.tpl';
        $tpfooterSkin = _PS_ALL_THEMES_DIR_._THEME_NAME_.'/modules/'.$this->name.'/options/footer/'.$dorFooterSkin.'.tpl';
        if($dorOptfrontend) $this->context->controller->addCSS($this->_path. 'css/dor-tool.css');

        if (file_exists($tpfooterSkin))
            $footerSkin = $tpfooterSkin;

        $id_product = (int)Tools::getValue('id_product');
        $tagProducts = array();
        if($id_product > 0){
            $tags = Tag::getMainTags((int)($this->context->language->id), 3);
            if(count($tags) > 0){
                $max = -1;
                $min = -1;
                foreach ($tags as $tag)
                {
                    if ($tag['times'] > $max)
                        $max = $tag['times'];
                    if ($tag['times'] < $min || $min == -1)
                        $min = $tag['times'];
                }

                if ($min == $max)
                    $coef = $max;
                else
                    $coef = (Configuration::get('BLOCKTAGS_MAX_LEVEL') - 1) / ($max - $min);

                if (!count($tags))
                    return false;
                if (Configuration::get('BLOCKTAGS_RANDOMIZE'))
                    shuffle($tags);
                foreach ($tags as &$tag){
                    $tag['class'] = 'tag_level'.(int)(($tag['times'] - $min) * $coef + 1);
                }
                $tagProducts = $tags;
            }
        }
          $ps = array(
              'DorRtl'                      => Context::getContext()->language->is_rtl,
              'DORTHEMENAME'                => _THEME_NAME_,
              'PS_BASE_URL'                 => _PS_BASE_URL_,
              'PS_BASE_URI'                 => __PS_BASE_URI__,
              'PS_BASE_URL'                 => _PS_BASE_URL_,
              //start color
              'dorHeaderBgOutside'          => $dorHeaderBgOutside, // header
              'dorHeaderBgColor'            => $dorHeaderBgColor,
              'dorHeaderColorIcon'            => $dorHeaderColorIcon,
              'dorHeaderColorLink'          => $dorHeaderColorLink,
              'dorHeaderColorLinkHover'     => $dorHeaderColorLinkHover,
              'dorHeaderColorText'          => $dorHeaderColorText,

              //start Megamenu
              'dorMegamenuBgOutside'        => $dorMegamenuBgOutside, // Megamenu
              'dorMegamenuBgColor'          => $dorMegamenuBgColor,
              'dorMegamenuColorLink'        => $dorMegamenuColorLink,
              'dorMegamenuColorLinkHover'   => $dorMegamenuColorLinkHover,
              'dorMegamenuColorText'        => $dorMegamenuColorText,
              'dorMegamenuColorSubLink'        => $dorMegamenuColorSubLink,
              'dorMegamenuColorSubLinkHover'   => $dorMegamenuColorSubLinkHover,
              'dorMegamenuColorSubText'        => $dorMegamenuColorSubText,

              //start Vermenu
              'dorVermenuBgOutside'        => $dorVermenuBgOutside, // Vermenu
              'dorVermenuBgColor'          => $dorVermenuBgColor,
              'dorVermenuColorLink'        => $dorVermenuColorLink,
              'dorVermenuColorLinkHover'   => $dorVermenuColorLinkHover,
              'dorVermenuColorText'        => $dorVermenuColorText,
              'dorVermenuColorSubLink'        => $dorVermenuColorSubLink,
              'dorVermenuColorSubLinkHover'   => $dorVermenuColorSubLinkHover,
              'dorVermenuColorSubText'        => $dorVermenuColorSubText,

              //footer
              'dorFooterBgOutside'          => $dorFooterBgOutside,
              'dorTopbarBgOutside'          => $dorTopbarBgOutside,
              'dorFooterBgColor'            => $dorFooterBgColor,
              'dorTopbarBgColor'            => $dorTopbarBgColor,
              'dorFooterColorText'          => $dorFooterColorText ,
              'dorTopbarColorText'          => $dorTopbarColorText ,
              'dorFooterColorLink'          => $dorFooterColorLink ,
              'dorTopbarColorLink'          => $dorTopbarColorLink ,
              'dorFooterColorLinkHover'     => $dorFooterColorLinkHover ,
              'dorTopbarColorLinkHover'     => $dorTopbarColorLinkHover ,
              // end color
              'dorEnableThemeColor'         => (int)$dorEnableThemeColor, //show color
              'dorOptReload'                => (int)$dorOptReload, //show color
              'dorEnableAwesome'            => (int)$dorEnableAwesome, //show color
              'dorEnableBgImage'            => $dorEnableBgImage, // color skin
              'dorthemecolor'               => $dorthemecolor, // color skin
              'dorPathColor'                => $this->_path. 'css/color/'.$dorthemecolor.'.css',
              'dorlayoutmode'               => $dorlayoutmode, // mode theme
              'dorFloatHeader'              => $dorFloatHeader, // mode theme
              'dorHeaderSkinName'           => $dorHeaderSkin, // mode theme
              'dorHeaderSkin'               => $dorHeaderSkin != ""?$headerSkin:"", // Skin Header
              'dorFont'                     => $dorFont, // Font Theme
              'dorPathFont'                => $this->_path. 'css/fonts/'.$dorFont.'.css',
              'dorFooterSkinName'           => $dorFooterSkin,
              'dorTopbarSkinName'           => $dorTopbarSkin,
              'dorCategoryThumb'            => $dorCategoryThumb,
              'dorCatQuanlity'            => $dorCatQuanlity,
              'dorCatThumbWidth'            => $dorCatThumbWidth,
              'dorCatThumbHeight'           => $dorCatThumbHeight,
              'dorFooterSkin'               => $dorFooterSkin != ""?$footerSkin:"", // Skin Footer
              'dorDetailCols'               => $dorDetailCols, // Skin Product Detail
              'dorCategoryCols'             => $dorCategoryCols, // Skin Category Lists
              'proCateRowNumber'             => $proCateRowNumber,
              'dorDetailReview'             => $dorDetailReview,
              'dorDetailLabel'              => $dorDetailLabel,
              'dorDetailavailabilityStatut'              => $dorDetailavailabilityStatut,
              'dorDetailcompare'              => $dorDetailcompare,
              'dorDetailwishlist'              => $dorDetailwishlist,
              'dorDetaillinkblock'              => $dorDetaillinkblock,
              'dorDetailsocialsharing'              => $dorDetailsocialsharing,
              'dorDetailOldPrice'              => $dorDetailOldPrice,
              'dorDetailReference'              => $dorDetailReference,
              'dorDetailCondition'              => $dorDetailCondition,
              'dorDetailThumbList'              => $dorDetailThumbList,
              'dorDetailInfoStyle'              => $dorDetailInfoStyle,
              'dorCategoryEffect'              => $dorCategoryEffect,
              'dorCategoryShow'              => $dorCategoryShow,
              'dorBlogsCols'              => $dorBlogsCols,
              'dorBlogsDetailCols'              => $dorBlogsDetailCols,
              'dorSubsPop'              => $dorSubsPop,
              'enableAngularJs'              => $enableAngularJs,
              'enableDorCache'              => $enableDorCache,
              'dorDetailMainImage'              => $dorDetailMainImage,
              'dorTimeCache'              => $dorTimeCache,
              'dorDetailReduction'          => $dorDetailReduction,
              'dorDetailpQuantityAvailable'          => $dorDetailpQuantityAvailable,
              'dorTopbarSkin'               => $dorTopbarSkin != ""?_PS_ROOT_DIR_."/modules/".$this->name."/options/topbar/".$dorTopbarSkin.".tpl":"", // Skin Topbar
              'dorthemebg'                  => Configuration::get('dorthemebg'), // bachground skin
              'tagProducts'                        => $tagProducts,
              'pathTmpColor'                        => (int)$this->pathTmpColor,

          );
        $smarty->assign($ps);
        return $this->display(__FILE__, 'dor_themeoptions.tpl');
    }
	function hookdorthemeoptions($params) {
        $dorOptfrontend                     = Configuration::get($this->name . '_dorOptfrontend');
        $dorSubscribe                     = Configuration::get($this->name . '_dorSubscribe');
        if($dorOptfrontend){
            global  $smarty;
            $ps = array(
                'dorthemebg'        => Configuration::get($this->name . '_dorthemebg'), // name skin
                'dorOptfrontend'    => Configuration::get($this->name . '_dorOptfrontend'), // ennable /disable name skin show_fontend
                'dorSubscribe'    => Configuration::get($this->name . '_dorSubscribe'), // ennable 
                'this_path'         => $this->_path,
                'DORTHEMENAME'      => _THEME_NAME_,
                'PS_BASE_URL'       => _PS_BASE_URL_,
                'PS_BASE_URI'       => __PS_BASE_URI__,
                'dorHeaderBgOutside'=> Configuration::get($this->name . '_dorHeaderBgOutside'),
                'PS_BASE_URL'       => _PS_BASE_URL_,
                'codeColor'         =>$this->codeColor
            );
            $smarty->assign($ps);
            return $this->display(__FILE__, 'dortool.tpl');
        }
    }
}
