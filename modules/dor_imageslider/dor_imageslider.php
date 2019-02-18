<?php
/*
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2015 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

/**
 * @since   1.5.0
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;

include_once(_PS_MODULE_DIR_.'dor_imageslider/DorSlide.php');

class Dor_ImageSlider extends Module implements WidgetInterface
{
    protected $_html = '';
    protected $default_theme = 1;
    protected $default_width = 779;
    protected $default_speed = 5000;
    protected $default_pause_on_hover = 1;
    protected $default_wrap = 1;
    protected $default_thumbquanity = 100;
    protected $default_thumbwidth = 900;
    protected $default_thumbheight = 460;

    public function __construct()
    {
        $this->name = 'dor_imageslider';
        $this->tab = 'front_office_features';
        $this->version = '2.0.0';
        $this->author = 'Dorado Themes';
        $this->need_instance = 0;
        $this->secure_key = Tools::encrypt($this->name);
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->getTranslator()->trans('Dor Image Slider Pro');
        $this->description = $this->getTranslator()->trans('Adds an image slider to your site.');
        $this->ps_versions_compliancy = array('min' => '1.7.0.0', 'max' => _PS_VERSION_);
    }

    /**
     * @see Module::install()
     */
    public function install()
    {
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
        foreach (Language::getLanguages() as $language)
        $tab->name[$language['id_lang']] = $this->l('Dor Slider Homepage');
        $tab->class_name = 'AdminDorHomeSlider';
        $tab->id_parent = (int)Tab::getIdFromClassName('AdminDorMenu'); 
        $tab->module = $this->name;
        $tab->add();
        /* Adds Module */
        if (parent::install() &&
            $this->registerHook('displayHeader') &&
            $this->registerHook('dorHomeSlider') &&
            $this->registerHook('actionShopDataDuplication')
        ) {
            $shops = Shop::getContextListShopID();
            $shop_groups_list = array();

            /* Setup each shop */
            foreach ($shops as $shop_id) {
                $shop_group_id = (int)Shop::getGroupFromShop($shop_id, true);

                if (!in_array($shop_group_id, $shop_groups_list)) {
                    $shop_groups_list[] = $shop_group_id;
                }

                /* Sets up configuration */
                $res = Configuration::updateValue('DOR_HOMESLIDER_THEME', $this->default_theme, false, $shop_group_id, $shop_id);
                $res &= Configuration::updateValue('DOR_HOMESLIDER_SPEED', $this->default_speed, false, $shop_group_id, $shop_id);
                $res &= Configuration::updateValue('DOR_HOMESLIDER_PAUSE_ON_HOVER', $this->default_pause_on_hover, false, $shop_group_id, $shop_id);
                $res &= Configuration::updateValue('DOR_HOMESLIDER_WRAP', $this->default_wrap, false, $shop_group_id, $shop_id);
                $res &= Configuration::updateValue('DOR_HOMESLIDER_AUTOPLAY', $this->default_wrap, false, $shop_group_id, $shop_id);
                $res &= Configuration::updateValue('DOR_HOMESLIDER_ARROW_BUTTON', $this->default_wrap, false, $shop_group_id, $shop_id);
                $res &= Configuration::updateValue('DOR_HOMESLIDER_NAVIGATOR', $this->default_wrap, false, $shop_group_id, $shop_id);
                $res &= Configuration::updateValue('DOR_HOMESLIDER_THUMB_QUANITY', $this->default_thumbquanity, false, $shop_group_id, $shop_id);
                $res &= Configuration::updateValue('DOR_HOMESLIDER_THUMB_WIDTH', $this->default_thumbwidth, false, $shop_group_id, $shop_id);
                $res &= Configuration::updateValue('DOR_HOMESLIDER_THUMB_HEIGHT', $this->default_thumbheight, false, $shop_group_id, $shop_id);
            }

            /* Sets up Shop Group configuration */
            if (count($shop_groups_list)) {
                foreach ($shop_groups_list as $shop_group_id) {
                    $res &= Configuration::updateValue('DOR_HOMESLIDER_THEME', $this->default_theme, false, $shop_group_id);
                    $res &= Configuration::updateValue('DOR_HOMESLIDER_SPEED', $this->default_speed, false, $shop_group_id);
                    $res &= Configuration::updateValue('DOR_HOMESLIDER_PAUSE_ON_HOVER', $this->default_pause_on_hover, false, $shop_group_id);
                    $res &= Configuration::updateValue('DOR_HOMESLIDER_WRAP', $this->default_wrap, false, $shop_group_id);
                    $res &= Configuration::updateValue('DOR_HOMESLIDER_AUTOPLAY', $this->default_wrap, false, $shop_group_id);
                    $res &= Configuration::updateValue('DOR_HOMESLIDER_ARROW_BUTTON', $this->default_wrap, false, $shop_group_id);
                    $res &= Configuration::updateValue('DOR_HOMESLIDER_NAVIGATOR', $this->default_wrap, false, $shop_group_id);
                    $res &= Configuration::updateValue('DOR_HOMESLIDER_THUMB_QUANITY', $this->default_thumbquanity, false, $shop_group_id);
                    $res &= Configuration::updateValue('DOR_HOMESLIDER_THUMB_WIDTH', $this->default_thumbwidth, false, $shop_group_id);
                    $res &= Configuration::updateValue('DOR_HOMESLIDER_THUMB_HEIGHT', $this->default_thumbheight, false, $shop_group_id);
                }
            }

            /* Sets up Global configuration */
            $res &= Configuration::updateValue('DOR_HOMESLIDER_THEME', $this->default_theme);
            $res &= Configuration::updateValue('DOR_HOMESLIDER_SPEED', $this->default_speed);
            $res &= Configuration::updateValue('DOR_HOMESLIDER_PAUSE_ON_HOVER', $this->default_pause_on_hover);
            $res &= Configuration::updateValue('DOR_HOMESLIDER_WRAP', $this->default_wrap);
            $res &= Configuration::updateValue('DOR_HOMESLIDER_AUTOPLAY', $this->default_wrap);
            $res &= Configuration::updateValue('DOR_HOMESLIDER_ARROW_BUTTON', $this->default_wrap);
            $res &= Configuration::updateValue('DOR_HOMESLIDER_NAVIGATOR', $this->default_wrap);
            $res &= Configuration::updateValue('DOR_HOMESLIDER_THUMB_QUANITY', $this->default_thumbquanity);
            $res &= Configuration::updateValue('DOR_HOMESLIDER_THUMB_WIDTH', $this->default_thumbwidth);
            $res &= Configuration::updateValue('DOR_HOMESLIDER_THUMB_HEIGHT', $this->default_thumbheight);

            /* Creates tables */
            $res &= $this->createTables();

            /* Adds samples */
            if ($res) {
                $this->installSamples();
            }

            // Disable on mobiles and tablets
           // $this->disableDevice(Context::DEVICE_MOBILE);

            return (bool)$res;
        }

        return false;
    }

    /**
     * Adds samples
     */
    protected function installSamples()
    {
        $languages = Language::getLanguages(false);
        for ($i = 1; $i <= 3; ++$i) {
            $slide = new DorSlide();
            $slide->position = $i;
            $slide->active = 1;
            $slide->effect = 0;
            $slide->imageproduct = "";
            foreach ($languages as $language) {
                $slide->title[$language['id_lang']] = 'Good for nature '.$i;
                $slide->description[$language['id_lang']] = 'We launch a new drink as a blend of nuts, cereals and fruits. We need the kind of packaging design that will introduce it as a healthy drink with important, natural ingredients.';
                $slide->legend[$language['id_lang']] = 'good for you'.$i;
                $slide->txtReadmore1[$language['id_lang']] = 'Shop now';
                $slide->txtReadmore2[$language['id_lang']] = '';
                $slide->UrlReadmore1[$language['id_lang']] = 'http://doradothemes.com';
                $slide->UrlReadmore2[$language['id_lang']] = '';
                $slide->url[$language['id_lang']] = 'http://doradothemes.com';
                $slide->image[$language['id_lang']] = 'sample-'.$i.'.jpg';
                $slide->price[$language['id_lang']] = '';
            }
            $slide->add();
        }
    }

    /**
     * @see Module::uninstall()
     */
    public function uninstall()
    {
        /* Deletes Module */
        if (parent::uninstall()) {
            /* Deletes tables */
            $tab = new Tab((int) Tab::getIdFromClassName('AdminDorHomeSlider'));
            $tab->delete();
            /* Deletes tables */
            $res = $this->uninstallDb();

            /* Unsets configuration */
            $res &= Configuration::deleteByName('DOR_HOMESLIDER_THEME');
            $res &= Configuration::deleteByName('DOR_HOMESLIDER_SPEED');
            $res &= Configuration::deleteByName('DOR_HOMESLIDER_PAUSE_ON_HOVER');
            $res &= Configuration::deleteByName('DOR_HOMESLIDER_WRAP');
            $res &= Configuration::deleteByName('DOR_HOMESLIDER_AUTOPLAY');
            $res &= Configuration::deleteByName('DOR_HOMESLIDER_ARROW_BUTTON');
            $res &= Configuration::deleteByName('DOR_HOMESLIDER_NAVIGATOR');
            $res &= Configuration::deleteByName('DOR_HOMESLIDER_THUMB_QUANITY');
            $res &= Configuration::deleteByName('DOR_HOMESLIDER_THUMB_WIDTH');
            $res &= Configuration::deleteByName('DOR_HOMESLIDER_THUMB_HEIGHT');

            return (bool)$res;
        }

        return false;
    }

    /**
     * Creates tables
     */
    protected function createTables()
    {
        /* Slides */
        $res = (bool)Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'dorimageslider` (
                `id_slides` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `id_shop` int(10) unsigned NOT NULL,
                PRIMARY KEY (`id_slides`, `id_shop`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
        ');

        /* Slides configuration */
        $res &= Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'dorimageslider_items` (
              `id_slides` int(10) unsigned NOT NULL AUTO_INCREMENT,
              `position` int(10) unsigned NOT NULL DEFAULT \'0\',
              `active` tinyint(1) unsigned NOT NULL DEFAULT \'0\',
              `effect` int(10) unsigned NOT NULL,
              `imageproduct` varchar(255) NOT NULL,
              PRIMARY KEY (`id_slides`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
        ');

        /* Slides lang configuration */
        $res &= Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'dorimageslider_items_lang` (
              `id_slides` int(10) unsigned NOT NULL,
              `id_lang` int(10) unsigned NOT NULL,
              `title` varchar(255) NOT NULL,
              `description` text NOT NULL,
              `legend` varchar(255) NOT NULL,
              `url` varchar(255) NOT NULL,
              `image` varchar(255) NOT NULL,
              `price` varchar(100) NOT NULL,
              `txtReadmore1` varchar(255) NOT NULL,
              `txtReadmore2` varchar(255) NOT NULL,
              `UrlReadmore1` varchar(355) NOT NULL,
              `UrlReadmore2` varchar(355) NOT NULL,
              PRIMARY KEY (`id_slides`,`id_lang`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
        ');

        return $res;
    }
    private function uninstallDb() {
        Db::getInstance()->execute('DROP TABLE `'._DB_PREFIX_.'dorimageslider`');
        Db::getInstance()->execute('DROP TABLE `'._DB_PREFIX_.'dorimageslider_items`');
        Db::getInstance()->execute('DROP TABLE `'._DB_PREFIX_.'dorimageslider_items_lang`');
        return true;
    }
    /**
     * deletes tables
     */
    protected function deleteTables()
    {

        $slides = $this->getSlides();
        foreach ($slides as $slide) {
            $to_del = new DorSlide($slide['id_slide']);
            $to_del->delete();
        }
        return Db::getInstance()->execute('
            DROP TABLE IF EXISTS `'._DB_PREFIX_.'dorimageslider`, `'._DB_PREFIX_.'dorimageslider_items`, `'._DB_PREFIX_.'dorimageslider_items_lang`;
        ');
    }

    public function getContent()
    {
        $this->_html .= $this->headerHTML();

        /* Validate & process */
        if (Tools::isSubmit('submitSlide') || Tools::isSubmit('delete_id_slide') ||
            Tools::isSubmit('submitSlider') ||
            Tools::isSubmit('changeStatus')
        ) {
            if ($this->_postValidation()) {
                $this->_postProcess();
                $this->_html .= $this->renderForm();
                $this->_html .= $this->renderList();
            } else {
                $this->_html .= $this->renderAddForm();
            }

            $this->clearCache();
        } elseif (Tools::isSubmit('addSlide') || (Tools::isSubmit('id_slide') && $this->slideExists((int)Tools::getValue('id_slide')))) {
            if (Tools::isSubmit('addSlide')) {
                $mode = 'add';
            } else {
                $mode = 'edit';
            }

            if ($mode == 'add') {
                if (Shop::getContext() != Shop::CONTEXT_GROUP && Shop::getContext() != Shop::CONTEXT_ALL) {
                    $this->_html .= $this->renderAddForm();
                } else {
                    $this->_html .= $this->getShopContextError(null, $mode);
                }
            } else {
                $associated_shop_ids = DorSlide::getAssociatedIdsShop((int)Tools::getValue('id_slide'));
                $context_shop_id = (int)Shop::getContextShopID();

                if ($associated_shop_ids === false) {
                    $this->_html .= $this->getShopAssociationError((int)Tools::getValue('id_slide'));
                } elseif (Shop::getContext() != Shop::CONTEXT_GROUP && Shop::getContext() != Shop::CONTEXT_ALL && in_array($context_shop_id, $associated_shop_ids)) {
                    if (count($associated_shop_ids) > 1) {
                        $this->_html = $this->getSharedSlideWarning();
                    }
                    $this->_html .= $this->renderAddForm();
                } else {
                    $shops_name_list = array();
                    foreach ($associated_shop_ids as $shop_id) {
                        $associated_shop = new Shop((int)$shop_id);
                        $shops_name_list[] = $associated_shop->name;
                    }
                    $this->_html .= $this->getShopContextError($shops_name_list, $mode);
                }
            }
        } else {
            $this->_html .= $this->getWarningMultishopHtml().$this->getCurrentShopInfoMsg().$this->renderForm();

            if (Shop::getContext() != Shop::CONTEXT_GROUP && Shop::getContext() != Shop::CONTEXT_ALL) {
                $this->_html .= $this->renderList();
            }
        }

        return $this->_html;
    }

    protected function _postValidation()
    {
        $errors = array();

        /* Validation for Slider configuration */
        if (Tools::isSubmit('submitSlider')) {
            if (!Validate::isInt(Tools::getValue('DOR_HOMESLIDER_SPEED'))) {
                $errors[] = $this->getTranslator()->trans('Invalid values', array(), 'Modules.ImageSlider');
            }
        } elseif (Tools::isSubmit('changeStatus')) {
            if (!Validate::isInt(Tools::getValue('id_slide'))) {
                $errors[] = $this->getTranslator()->trans('Invalid slide', array(), 'Modules.ImageSlider');
            }
        } elseif (Tools::isSubmit('submitSlide')) {
            /* Checks state (active) */
            if (!Validate::isInt(Tools::getValue('active_slide')) || (Tools::getValue('active_slide') != 0 && Tools::getValue('active_slide') != 1)) {
                $errors[] = $this->getTranslator()->trans('Invalid slide state.', array(), 'Modules.ImageSlider');
            }
            /* Checks position */
            if (!Validate::isInt(Tools::getValue('position')) || (Tools::getValue('position') < 0)) {
                $errors[] = $this->getTranslator()->trans('Invalid slide position.', array(), 'Modules.ImageSlider');
            }
            /* If edit : checks id_slide */
            if (Tools::isSubmit('id_slide')) {
                if (!Validate::isInt(Tools::getValue('id_slide')) && !$this->slideExists(Tools::getValue('id_slide'))) {
                    $errors[] = $this->getTranslator()->trans('Invalid slide ID', array(), 'Modules.ImageSlider');
                }
            }
            /* Checks title/url/legend/description/image */
            $languages = Language::getLanguages(false);
            foreach ($languages as $language) {
                if (Tools::strlen(Tools::getValue('title_' . $language['id_lang'])) > 255) {
                    $errors[] = $this->getTranslator()->trans('The title is too long.', array(), 'Modules.ImageSlider');
                }
                if (Tools::strlen(Tools::getValue('legend_' . $language['id_lang'])) > 255) {
                    $errors[] = $this->getTranslator()->trans('The caption is too long.', array(), 'Modules.ImageSlider');
                }
                if (Tools::strlen(Tools::getValue('url_' . $language['id_lang'])) > 255) {
                    $errors[] = $this->getTranslator()->trans('The URL is too long.', array(), 'Modules.ImageSlider');
                }
                if (Tools::strlen(Tools::getValue('description_' . $language['id_lang'])) > 4000) {
                    $errors[] = $this->getTranslator()->trans('The description is too long.', array(), 'Modules.ImageSlider');
                }
                if (Tools::strlen(Tools::getValue('url_' . $language['id_lang'])) > 0 && !Validate::isUrl(Tools::getValue('url_' . $language['id_lang']))) {
                    $errors[] = $this->getTranslator()->trans('The URL format is not correct.', array(), 'Modules.ImageSlider');
                }
                if (Tools::getValue('image_' . $language['id_lang']) != null && !Validate::isFileName(Tools::getValue('image_' . $language['id_lang']))) {
                    $errors[] = $this->getTranslator()->trans('Invalid filename.', array(), 'Modules.ImageSlider');
                }
                if (Tools::getValue('image_old_' . $language['id_lang']) != null && !Validate::isFileName(Tools::getValue('image_old_' . $language['id_lang']))) {
                    $errors[] = $this->getTranslator()->trans('Invalid filename.', array(), 'Modules.ImageSlider');
                }
            }

            /* Checks title/url/legend/description for default lang */
            $id_lang_default = (int)Configuration::get('PS_LANG_DEFAULT');
            if (Tools::strlen(Tools::getValue('url_' . $id_lang_default)) == 0) {
                $errors[] = $this->getTranslator()->trans('The URL is not set.', array(), 'Modules.ImageSlider');
            }
            if (!Tools::isSubmit('has_picture') && (!isset($_FILES['image_' . $id_lang_default]) || empty($_FILES['image_' . $id_lang_default]['tmp_name']))) {
                $errors[] = $this->getTranslator()->trans('The image is not set.', array(), 'Modules.ImageSlider');
            }
            if (Tools::getValue('image_old_'.$id_lang_default) && !Validate::isFileName(Tools::getValue('image_old_'.$id_lang_default))) {
                $errors[] = $this->getTranslator()->trans('The image is not set.', array(), 'Modules.ImageSlider');
            }
        } elseif (Tools::isSubmit('delete_id_slide') && (!Validate::isInt(Tools::getValue('delete_id_slide')) || !$this->slideExists((int)Tools::getValue('delete_id_slide')))) {
            $errors[] = $this->getTranslator()->trans('Invalid slide ID', array(), 'Modules.ImageSlider');
        }

        /* Display errors if needed */
        if (count($errors)) {
            $this->_html .= $this->displayError(implode('<br />', $errors));

            return false;
        }

        /* Returns if validation is ok */

        return true;
    }

    protected function _postProcess()
    {
        $errors = array();
        $shop_context = Shop::getContext();

        /* Processes Slider */
        if (Tools::isSubmit('submitSlider')) {
            $shop_groups_list = array();
            $shops = Shop::getContextListShopID();

            foreach ($shops as $shop_id) {
                $shop_group_id = (int)Shop::getGroupFromShop($shop_id, true);

                if (!in_array($shop_group_id, $shop_groups_list)) {
                    $shop_groups_list[] = $shop_group_id;
                }

                $res = Configuration::updateValue('DOR_HOMESLIDER_THEME', (int)Tools::getValue('DOR_HOMESLIDER_THEME'), false, $shop_group_id, $shop_id);
                $res = Configuration::updateValue('DOR_HOMESLIDER_SPEED', (int)Tools::getValue('DOR_HOMESLIDER_SPEED'), false, $shop_group_id, $shop_id);
                $res &= Configuration::updateValue('DOR_HOMESLIDER_PAUSE_ON_HOVER', (int)Tools::getValue('DOR_HOMESLIDER_PAUSE_ON_HOVER'), false, $shop_group_id, $shop_id);
                $res &= Configuration::updateValue('DOR_HOMESLIDER_WRAP', (int)Tools::getValue('DOR_HOMESLIDER_WRAP'), false, $shop_group_id, $shop_id);
                $res &= Configuration::updateValue('DOR_HOMESLIDER_AUTOPLAY', (int)Tools::getValue('DOR_HOMESLIDER_AUTOPLAY'), false, $shop_group_id, $shop_id);
                $res &= Configuration::updateValue('DOR_HOMESLIDER_ARROW_BUTTON', (int)Tools::getValue('DOR_HOMESLIDER_ARROW_BUTTON'), false, $shop_group_id, $shop_id);
                $res &= Configuration::updateValue('DOR_HOMESLIDER_NAVIGATOR', (int)Tools::getValue('DOR_HOMESLIDER_NAVIGATOR'), false, $shop_group_id, $shop_id);
                $res &= Configuration::updateValue('DOR_HOMESLIDER_THUMB_QUANITY', (int)Tools::getValue('DOR_HOMESLIDER_THUMB_QUANITY'), false, $shop_group_id, $shop_id);
                $res &= Configuration::updateValue('DOR_HOMESLIDER_THUMB_WIDTH', (int)Tools::getValue('DOR_HOMESLIDER_THUMB_WIDTH'), false, $shop_group_id, $shop_id);
                $res &= Configuration::updateValue('DOR_HOMESLIDER_THUMB_HEIGHT', (int)Tools::getValue('DOR_HOMESLIDER_THUMB_HEIGHT'), false, $shop_group_id, $shop_id);
            }

            /* Update global shop context if needed*/
            switch ($shop_context) {
                case Shop::CONTEXT_ALL:
                    $res &= Configuration::updateValue('DOR_HOMESLIDER_THEME', (int)Tools::getValue('DOR_HOMESLIDER_THEME'));
                    $res &= Configuration::updateValue('DOR_HOMESLIDER_SPEED', (int)Tools::getValue('DOR_HOMESLIDER_SPEED'));
                    $res &= Configuration::updateValue('DOR_HOMESLIDER_PAUSE_ON_HOVER', (int)Tools::getValue('DOR_HOMESLIDER_PAUSE_ON_HOVER'));
                    $res &= Configuration::updateValue('DOR_HOMESLIDER_WRAP', (int)Tools::getValue('DOR_HOMESLIDER_WRAP'));
                    $res &= Configuration::updateValue('DOR_HOMESLIDER_AUTOPLAY', (int)Tools::getValue('DOR_HOMESLIDER_AUTOPLAY'));
                    $res &= Configuration::updateValue('DOR_HOMESLIDER_ARROW_BUTTON', (int)Tools::getValue('DOR_HOMESLIDER_ARROW_BUTTON'));
                    $res &= Configuration::updateValue('DOR_HOMESLIDER_NAVIGATOR', (int)Tools::getValue('DOR_HOMESLIDER_NAVIGATOR'));
                    $res &= Configuration::updateValue('DOR_HOMESLIDER_THUMB_QUANITY', (int)Tools::getValue('DOR_HOMESLIDER_THUMB_QUANITY'));
                    $res &= Configuration::updateValue('DOR_HOMESLIDER_THUMB_WIDTH', (int)Tools::getValue('DOR_HOMESLIDER_THUMB_WIDTH'));
                    $res &= Configuration::updateValue('DOR_HOMESLIDER_THUMB_HEIGHT', (int)Tools::getValue('DOR_HOMESLIDER_THUMB_HEIGHT'));
                    if (count($shop_groups_list)) {
                        foreach ($shop_groups_list as $shop_group_id) {
                            $res &= Configuration::updateValue('DOR_HOMESLIDER_THEME', (int)Tools::getValue('DOR_HOMESLIDER_THEME'), false, $shop_group_id);
                            $res &= Configuration::updateValue('DOR_HOMESLIDER_SPEED', (int)Tools::getValue('DOR_HOMESLIDER_SPEED'), false, $shop_group_id);
                            $res &= Configuration::updateValue('DOR_HOMESLIDER_PAUSE_ON_HOVER', (int)Tools::getValue('DOR_HOMESLIDER_PAUSE_ON_HOVER'), false, $shop_group_id);
                            $res &= Configuration::updateValue('DOR_HOMESLIDER_WRAP', (int)Tools::getValue('DOR_HOMESLIDER_WRAP'), false, $shop_group_id);
                            $res &= Configuration::updateValue('DOR_HOMESLIDER_AUTOPLAY', (int)Tools::getValue('DOR_HOMESLIDER_AUTOPLAY'), false, $shop_group_id);
                            $res &= Configuration::updateValue('DOR_HOMESLIDER_ARROW_BUTTON', (int)Tools::getValue('DOR_HOMESLIDER_ARROW_BUTTON'), false, $shop_group_id);
                            $res &= Configuration::updateValue('DOR_HOMESLIDER_NAVIGATOR', (int)Tools::getValue('DOR_HOMESLIDER_NAVIGATOR'), false, $shop_group_id);
                            $res &= Configuration::updateValue('DOR_HOMESLIDER_THUMB_QUANITY', (int)Tools::getValue('DOR_HOMESLIDER_THUMB_QUANITY'), false, $shop_group_id);
                            $res &= Configuration::updateValue('DOR_HOMESLIDER_THUMB_WIDTH', (int)Tools::getValue('DOR_HOMESLIDER_THUMB_WIDTH'), false, $shop_group_id);
                            $res &= Configuration::updateValue('DOR_HOMESLIDER_THUMB_HEIGHT', (int)Tools::getValue('DOR_HOMESLIDER_THUMB_HEIGHT'), false, $shop_group_id);
                        }
                    }
                    break;
                case Shop::CONTEXT_GROUP:
                    if (count($shop_groups_list)) {
                        foreach ($shop_groups_list as $shop_group_id) {
                            $res &= Configuration::updateValue('DOR_HOMESLIDER_THEME', (int)Tools::getValue('DOR_HOMESLIDER_THEME'), false, $shop_group_id);
                            $res &= Configuration::updateValue('DOR_HOMESLIDER_SPEED', (int)Tools::getValue('DOR_HOMESLIDER_SPEED'), false, $shop_group_id);
                            $res &= Configuration::updateValue('DOR_HOMESLIDER_PAUSE_ON_HOVER', (int)Tools::getValue('DOR_HOMESLIDER_PAUSE_ON_HOVER'), false, $shop_group_id);
                            $res &= Configuration::updateValue('DOR_HOMESLIDER_WRAP', (int)Tools::getValue('DOR_HOMESLIDER_WRAP'), false, $shop_group_id);
                            $res &= Configuration::updateValue('DOR_HOMESLIDER_AUTOPLAY', (int)Tools::getValue('DOR_HOMESLIDER_AUTOPLAY'), false, $shop_group_id);
                            $res &= Configuration::updateValue('DOR_HOMESLIDER_ARROW_BUTTON', (int)Tools::getValue('DOR_HOMESLIDER_ARROW_BUTTON'), false, $shop_group_id);
                            $res &= Configuration::updateValue('DOR_HOMESLIDER_NAVIGATOR', (int)Tools::getValue('DOR_HOMESLIDER_NAVIGATOR'), false, $shop_group_id);
                            $res &= Configuration::updateValue('DOR_HOMESLIDER_THUMB_QUANITY', (int)Tools::getValue('DOR_HOMESLIDER_THUMB_QUANITY'), false, $shop_group_id);
                            $res &= Configuration::updateValue('DOR_HOMESLIDER_THUMB_WIDTH', (int)Tools::getValue('DOR_HOMESLIDER_THUMB_WIDTH'), false, $shop_group_id);
                            $res &= Configuration::updateValue('DOR_HOMESLIDER_THUMB_HEIGHT', (int)Tools::getValue('DOR_HOMESLIDER_THUMB_HEIGHT'), false, $shop_group_id);
                        }
                    }
                    break;
            }

            $this->clearCache();

            if (!$res) {
                $errors[] = $this->displayError($this->getTranslator()->trans('The configuration could not be updated.', array(), 'Modules.ImageSlider'));
            } else {
                Tools::redirectAdmin($this->context->link->getAdminLink('AdminModules', true) . '&conf=6&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name);
            }
        } elseif (Tools::isSubmit('changeStatus') && Tools::isSubmit('id_slide')) {
            $slide = new DorSlide((int)Tools::getValue('id_slide'));
            if ($slide->active == 0) {
                $slide->active = 1;
            } else {
                $slide->active = 0;
            }
            $res = $slide->update();
            $this->clearCache();
            $this->_html .= ($res ? $this->displayConfirmation($this->getTranslator()->trans('Configuration updated', array(), 'Admin.Notifications.Success')) : $this->displayError($this->getTranslator()->trans('The configuration could not be updated.', array(), 'Modules.ImageSlider')));
        } elseif (Tools::isSubmit('submitSlide')) {
            /* Sets ID if needed */
            if (Tools::getValue('id_slide')) {
                $slide = new DorSlide((int)Tools::getValue('id_slide'));
                if (!Validate::isLoadedObject($slide)) {
                    $this->_html .= $this->displayError($this->getTranslator()->trans('Invalid slide ID', array(), 'Modules.ImageSlider'));
                    return false;
                }
            } else {
                $slide = new DorSlide();
            }
            /* Sets position */
            $slide->position = (int)Tools::getValue('position');
            /* Sets active */
            $slide->active = (int)Tools::getValue('active_slide');
            $slide->effect = (int)Tools::getValue('effect');
            $slide->imageproduct = Tools::getValue('imageproduct');
            /* Sets each langue fields */
            $languages = Language::getLanguages(false);

            foreach ($languages as $language) {
                $slide->title[$language['id_lang']] = Tools::getValue('title_'.$language['id_lang']);
                $slide->url[$language['id_lang']] = Tools::getValue('url_'.$language['id_lang']);
                $slide->legend[$language['id_lang']] = Tools::getValue('legend_'.$language['id_lang']);
                $slide->description[$language['id_lang']] = Tools::getValue('description_'.$language['id_lang']);
                $slide->txtReadmore1[$language['id_lang']] = Tools::getValue('txtReadmore1_'.$language['id_lang']);
                $slide->txtReadmore2[$language['id_lang']] = Tools::getValue('txtReadmore2_'.$language['id_lang']);
                $slide->UrlReadmore1[$language['id_lang']] = Tools::getValue('UrlReadmore1_'.$language['id_lang']);
                $slide->UrlReadmore2[$language['id_lang']] = Tools::getValue('UrlReadmore2_'.$language['id_lang']);
                $slide->price[$language['id_lang']] = Tools::getValue('price_'.$language['id_lang']);
                /* Uploads image and sets slide */
                $type = Tools::strtolower(Tools::substr(strrchr($_FILES['image_'.$language['id_lang']]['name'], '.'), 1));
                $imagesize = @getimagesize($_FILES['image_'.$language['id_lang']]['tmp_name']);
                if (isset($_FILES['image_'.$language['id_lang']]) &&
                    isset($_FILES['image_'.$language['id_lang']]['tmp_name']) &&
                    !empty($_FILES['image_'.$language['id_lang']]['tmp_name']) &&
                    !empty($imagesize) &&
                    in_array(
                        Tools::strtolower(Tools::substr(strrchr($imagesize['mime'], '/'), 1)), array(
                            'jpg',
                            'gif',
                            'jpeg',
                            'png'
                        )
                    ) &&
                    in_array($type, array('jpg', 'gif', 'jpeg', 'png'))
                ) {
                    $temp_name = tempnam(_PS_TMP_IMG_DIR_, 'PS');
                    $salt = sha1(microtime());
                    if ($error = ImageManager::validateUpload($_FILES['image_'.$language['id_lang']])) {
                        $errors[] = $error;
                    } elseif (!$temp_name || !move_uploaded_file($_FILES['image_'.$language['id_lang']]['tmp_name'], $temp_name)) {
                        return false;
                    } elseif (!ImageManager::resize($temp_name, dirname(__FILE__).'/images/'.$salt.'_'.$_FILES['image_'.$language['id_lang']]['name'], null, null, $type)) {
                        $errors[] = $this->displayError($this->getTranslator()->trans('An error occurred during the image upload process.', array(), 'Admin.Notifications.Error'));
                    }
                    if (isset($temp_name)) {
                        @unlink($temp_name);
                    }
                    $slide->image[$language['id_lang']] = $salt.'_'.$_FILES['image_'.$language['id_lang']]['name'];
                } elseif (Tools::getValue('image_old_'.$language['id_lang']) != '') {
                    $slide->image[$language['id_lang']] = Tools::getValue('image_old_' . $language['id_lang']);
                }
            }

            /* Processes if no errors  */
            if (!$errors) {
                /* Adds */
                if (!Tools::getValue('id_slide')) {
                    if (!$slide->add()) {
                        $errors[] = $this->displayError($this->getTranslator()->trans('The slide could not be added.', array(), 'Modules.ImageSlider'));
                    }
                } elseif (!$slide->update()) {
                    $errors[] = $this->displayError($this->getTranslator()->trans('The slide could not be updated.', array(), 'Modules.ImageSlider'));
                }
                $this->clearCache();
            }
        } elseif (Tools::isSubmit('delete_id_slide')) {
            $slide = new DorSlide((int)Tools::getValue('delete_id_slide'));
            $res = $slide->delete();
            $this->clearCache();
            if (!$res) {
                $this->_html .= $this->displayError('Could not delete.');
            } else {
                Tools::redirectAdmin($this->context->link->getAdminLink('AdminModules', true) . '&conf=1&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name);
            }
        }

        /* Display errors if needed */
        if (count($errors)) {
            $this->_html .= $this->displayError(implode('<br />', $errors));
        } elseif (Tools::isSubmit('submitSlide') && Tools::getValue('id_slide')) {
            Tools::redirectAdmin($this->context->link->getAdminLink('AdminModules', true) . '&conf=4&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name);
        } elseif (Tools::isSubmit('submitSlide')) {
            Tools::redirectAdmin($this->context->link->getAdminLink('AdminModules', true) . '&conf=3&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name);
        }
    }

    public function hookdisplayHeader($params)
    {
        if (!isset($this->context->controller->php_self) || $this->context->controller->php_self != 'index')
            return;
        $this->context->controller->addCSS($this->_path.'css/dorSlider.css');
        $this->context->controller->addJS($this->_path.'js/jssor.slider.min.js');
        $this->context->controller->addJS($this->_path.'js/dorSlider.js');
    }
    public function hookdorHomeSlider($hookName = null, array $configuration = [])
    {
        if (!$this->isCached('slider.tpl', $this->getCacheId())) {
            $this->smarty->assign($this->getWidgetVariables($hookName, $configuration));
        }

        return $this->display(__FILE__, 'slider.tpl', $this->getCacheId());
    }
    public function renderWidget($hookName = null, array $configuration = [])
    {
        if (!$this->isCached('slider.tpl', $this->getCacheId())) {
            $this->smarty->assign($this->getWidgetVariables($hookName, $configuration));
        }

        return $this->display(__FILE__, 'slider.tpl', $this->getCacheId());
    }
    public static function checkhttp($url) {
        if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
            return false;
        }
        return true;
    }
    public function getWidgetVariables($hookName = null, array $configuration = [])
    {
        $slides = $this->getSlides(true);
        if (is_array($slides)) {
            $pathUrl = _PS_BASE_URL_.__PS_BASE_URI__;
            foreach ($slides as &$slide) {
                $slide['sizes'] = @getimagesize((dirname(__FILE__) . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . $slide['image']));
                if (isset($slide['sizes'][3]) && $slide['sizes'][3]) {
                    $slide['size'] = $slide['sizes'][3];
                }
                if(isset($slide['imageproduct']) && $slide['imageproduct'] != ""){
                    $checkUrl = $this->checkhttp($slide['imageproduct']);
                    if(!$checkUrl){
                        $slide['imageproduct'] = $pathUrl.$slide['imageproduct'];
                    }
                }
                /****Check title type (text or image)***/
                $slide['type_title'] = "text";
                if(isset($slide['title']) && $slide['title'] != ""){
                    $type = Tools::strtolower(Tools::substr(strrchr($slide['title'], '.'), 1));
                    if($type != "" && in_array($type, array('jpg', 'gif', 'jpeg', 'png'))){
                        $slide['type_title'] = "image";
                        $checkUrlTitle = $this->checkhttp($slide['title']);
                        if(!$checkUrlTitle){
                            $slide['title'] = $pathUrl.$slide['title'];
                        }
                    }
                }

                if($slide['effect'] == 4 && $slide['title'] != ""){
                    $slide['title_arr'] = explode(" ", $slide['title']);
                }
            }
        }

        $config = $this->getConfigFieldsValues();

        return [
            'dorslider' => [
                'theme' => $config['DOR_HOMESLIDER_THEME'],
                'speed' => $config['DOR_HOMESLIDER_SPEED'],
                'pause' => $config['DOR_HOMESLIDER_PAUSE_ON_HOVER'] ? 'hover' : '',
                'wrap' => $config['DOR_HOMESLIDER_WRAP'] ? 'true' : 'false',
                'autoplay' => $config['DOR_HOMESLIDER_AUTOPLAY'] ? 'true' : 'false',
                'arrow' => $config['DOR_HOMESLIDER_ARROW_BUTTON'] ? 'true' : 'false',
                'nav' => $config['DOR_HOMESLIDER_NAVIGATOR'] ? 'true' : 'false',
                'thumbQuanity' => $config['DOR_HOMESLIDER_THUMB_QUANITY'],
                'thumbWidth' => $config['DOR_HOMESLIDER_THUMB_WIDTH'],
                'thumbHeight' => $config['DOR_HOMESLIDER_THUMB_HEIGHT'],
                'slides' => $slides,
            ],
        ];
    }

    public function clearCache()
    {
        $this->_clearCache('slider.tpl');
    }

    public function hookActionShopDataDuplication($params)
    {
        Db::getInstance()->execute('
            INSERT IGNORE INTO '._DB_PREFIX_.'dorimageslider (id_slides, id_shop)
            SELECT id_slides, '.(int)$params['new_id_shop'].'
            FROM '._DB_PREFIX_.'dorimageslider
            WHERE id_shop = '.(int)$params['old_id_shop']
        );
        $this->clearCache();
    }

    public function headerHTML()
    {
        if (Tools::getValue('controller') != 'AdminModules' && Tools::getValue('configure') != $this->name) {
            return;
        }

        $this->context->controller->addJqueryUI('ui.sortable');
        /* Style & js for fieldset 'slides configuration' */
        $html = '<script type="text/javascript">
            $(function() {
                var $mySlides = $("#slides");
                $mySlides.sortable({
                    opacity: 0.6,
                    cursor: "move",
                    update: function() {
                        var order = $(this).sortable("serialize") + "&action=updateSlidesPosition";
                        $.post("'.$this->context->shop->physical_uri.$this->context->shop->virtual_uri.'modules/'.$this->name.'/ajax_'.$this->name.'.php?secure_key='.$this->secure_key.'", order);
                        }
                    });
                $mySlides.hover(function() {
                    $(this).css("cursor","move");
                    },
                    function() {
                    $(this).css("cursor","auto");
                });
            });
        </script>';

        return $html;
    }

    public function getNextPosition()
    {
        $row = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('
            SELECT MAX(hss.`position`) AS `next_position`
            FROM `'._DB_PREFIX_.'dorimageslider_items` hss, `'._DB_PREFIX_.'dorimageslider` hs
            WHERE hss.`id_slides` = hs.`id_slides` AND hs.`id_shop` = '.(int)$this->context->shop->id
        );

        return (++$row['next_position']);
    }

    public function getSlides($active = null)
    {
        $this->context = Context::getContext();
        $id_shop = $this->context->shop->id;
        $id_lang = $this->context->language->id;

        $slides = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
            SELECT hs.`id_slides` as id_slide, hss.`position`, hss.`active`, hssl.`title`,
            hssl.`url`, hssl.`legend`, hssl.`description`, hssl.`image`, hss.`effect`, hss.`imageproduct`, hssl.`price`, hssl.`txtReadmore1`, hssl.`txtReadmore2`, hssl.`UrlReadmore1`, hssl.`UrlReadmore2`
            FROM '._DB_PREFIX_.'dorimageslider hs
            LEFT JOIN '._DB_PREFIX_.'dorimageslider_items hss ON (hs.id_slides = hss.id_slides)
            LEFT JOIN '._DB_PREFIX_.'dorimageslider_items_lang hssl ON (hss.id_slides = hssl.id_slides)
            WHERE id_shop = '.(int)$id_shop.'
            AND hssl.id_lang = '.(int)$id_lang.
            ($active ? ' AND hss.`active` = 1' : ' ').'
            ORDER BY hss.position'
        );

        foreach ($slides as &$slide) {
            $slide['image_url'] = $this->context->link->getMediaLink(_MODULE_DIR_.'dor_imageslider/images/'.$slide['image']);
        }

        return $slides;
    }

    public function getAllImagesBySlidesId($id_slides, $active = null, $id_shop = null)
    {
        $this->context = Context::getContext();
        $images = array();

        if (!isset($id_shop))
            $id_shop = $this->context->shop->id;

        $results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
            SELECT hssl.`image`, hssl.`id_lang`
            FROM '._DB_PREFIX_.'dorimageslider hs
            LEFT JOIN '._DB_PREFIX_.'dorimageslider_items hss ON (hs.id_slides = hss.id_slides)
            LEFT JOIN '._DB_PREFIX_.'dorimageslider_items_lang hssl ON (hss.id_slides = hssl.id_slides)
            WHERE hs.`id_slides` = '.(int)$id_slides.' AND hs.`id_shop` = '.(int)$id_shop.
            ($active ? ' AND hss.`active` = 1' : ' ')
        );

        foreach ($results as $result)
            $images[$result['id_lang']] = $result['image'];

        return $images;
    }

    public function displayStatus($id_slide, $active)
    {
        $title = ((int)$active == 0 ? $this->getTranslator()->trans('Disabled', array(), 'Admin.Global') : $this->getTranslator()->trans('Enabled', array(), 'Admin.Global'));
        $icon = ((int)$active == 0 ? 'icon-remove' : 'icon-check');
        $class = ((int)$active == 0 ? 'btn-danger' : 'btn-success');
        $html = '<a class="btn '.$class.'" href="'.AdminController::$currentIndex.
            '&configure='.$this->name.
                '&token='.Tools::getAdminTokenLite('AdminModules').
                '&changeStatus&id_slide='.(int)$id_slide.'" title="'.$title.'"><i class="'.$icon.'"></i> '.$title.'</a>';

        return $html;
    }

    public function slideExists($id_slide)
    {
        $req = 'SELECT hs.`id_slides` as id_slide
                FROM `'._DB_PREFIX_.'dorimageslider` hs
                WHERE hs.`id_slides` = '.(int)$id_slide;
        $row = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow($req);

        return ($row);
    }

    public function renderList()
    {
        $slides = $this->getSlides();
        foreach ($slides as $key => $slide) {
            $slides[$key]['status'] = $this->displayStatus($slide['id_slide'], $slide['active']);
            $associated_shop_ids = DorSlide::getAssociatedIdsShop((int)$slide['id_slide']);
            if ($associated_shop_ids && count($associated_shop_ids) > 1) {
                $slides[$key]['is_shared'] = true;
            } else {
                $slides[$key]['is_shared'] = false;
            }
        }

        $this->context->smarty->assign(
            array(
                'link' => $this->context->link,
                'slides' => $slides,
                'image_baseurl' => $this->_path.'images/'
            )
        );

        return $this->display(__FILE__, 'list.tpl');
    }

    public function renderAddForm()
    {
        $effect = array(
            array( 'id'=>'0','mode'=>'Effect 1'),
            array('id'=>'1','mode'=>'Effect 2'),
            array('id'=>'2','mode'=>'Effect 3'),
            array('id'=>'3','mode'=>'Effect 4'),
            array('id'=>'4','mode'=>'Effect 5'),
            array('id'=>'5','mode'=>'Effect 6'),
            array('id'=>'6','mode'=>'Effect 7'),
            array('id'=>'7','mode'=>'Effect 8'),
            array('id'=>'8','mode'=>'Effect 9'),
            array('id'=>'9','mode'=>'Effect 10'),
        );   
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->getTranslator()->trans('Slide information', array(), 'Modules.ImageSlider'),
                    'icon' => 'icon-cogs'
                ),
                'input' => array(
                    array(
                        'type' => 'file_lang',
                        'label' => $this->getTranslator()->trans('Image', array(), 'Admin.Global'),
                        'name' => 'image',
                        'required' => true,
                        'lang' => true,
                        'desc' => sprintf($this->getTranslator()->trans('Maximum image size: %s.', array(), 'Admin.Global'), ini_get('upload_max_filesize'))
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->getTranslator()->trans('Image Slide Product', array(), 'Admin.Global'),
                        'name' => 'imageproduct',
                        'lang' => false,
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->getTranslator()->trans('Title', array(), 'Admin.Global'),
                        'name' => 'title',
                        'lang' => true,
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->getTranslator()->trans('Target URL', array(), 'Modules.ImageSlider'),
                        'name' => 'url',
                        'required' => true,
                        'lang' => true,
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->getTranslator()->trans('Caption', array(), 'Modules.ImageSlider'),
                        'name' => 'legend',
                        'lang' => true,
                    ),
                    array(
                        'type' => 'textarea',
                        'label' => $this->getTranslator()->trans('Description', array(), 'Admin.Global'),
                        'name' => 'description',
                        'autoload_rte' => true,
                        'lang' => true,
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->getTranslator()->trans('Price', array(), 'Admin.Global'),
                        'name' => 'price',
                        'required' => false,
                        'lang' => true,
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->getTranslator()->trans('Effect Slider', array(), 'Admin.Global'),
                        'name' => 'effect',
                        'options' => array(
                            'query' => $effect,
                            'id' => 'id',
                            'name'=>'mode',
                        ),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->getTranslator()->trans('Read more Text 1', array(), 'Modules.ImageSlider'),
                        'name' => 'txtReadmore1',
                        'required' => false,
                        'lang' => true,
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->getTranslator()->trans('Read more URL 1', array(), 'Modules.ImageSlider'),
                        'name' => 'UrlReadmore1',
                        'required' => false,
                        'lang' => true,
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->getTranslator()->trans('Read more Text 2', array(), 'Modules.ImageSlider'),
                        'name' => 'txtReadmore2',
                        'required' => false,
                        'lang' => true,
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->getTranslator()->trans('Read more URL 2', array(), 'Modules.ImageSlider'),
                        'name' => 'UrlReadmore2',
                        'required' => false,
                        'lang' => true,
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->getTranslator()->trans('Enabled', array(), 'Admin.Global'),
                        'name' => 'active_slide',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->getTranslator()->trans('Yes', array(), 'Admin.Global')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->getTranslator()->trans('No', array(), 'Admin.Global')
                            )
                        ),
                    ),
                ),
                'submit' => array(
                    'title' => $this->getTranslator()->trans('Save', array(), 'Admin.Actions'),
                )
            ),
        );

        if (Tools::isSubmit('id_slide') && $this->slideExists((int)Tools::getValue('id_slide'))) {
            $slide = new DorSlide((int)Tools::getValue('id_slide'));
            $fields_form['form']['input'][] = array('type' => 'hidden', 'name' => 'id_slide');
            $fields_form['form']['images'] = $slide->image;

            $has_picture = true;

            foreach (Language::getLanguages(false) as $lang) {
                if (!isset($slide->image[$lang['id_lang']])) {
                    $has_picture &= false;
                }
            }

            if ($has_picture) {
                $fields_form['form']['input'][] = array('type' => 'hidden', 'name' => 'has_picture');
            }
        }

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $this->fields_form = array();
        $helper->module = $this;
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitSlide';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $language = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->tpl_vars = array(
            'base_url' => $this->context->shop->getBaseURL(),
            'language' => array(
                'id_lang' => $language->id,
                'iso_code' => $language->iso_code
            ),
            'fields_value' => $this->getAddFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
            'image_baseurl' => $this->_path.'images/'
        );

        $helper->override_folder = '/';

        $languages = Language::getLanguages(false);

        if (count($languages) > 1) {
            return $this->getMultiLanguageInfoMsg() . $helper->generateForm(array($fields_form));
        } else {
            return $helper->generateForm(array($fields_form));
        }
    }

    public function renderForm()
    {
        $themeData = array(
            array( 'id'=>'1','mode'=>'Theme 1'),
            array('id'=>'2','mode'=>'Theme 2'),
        );
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->getTranslator()->trans('Settings', array(), 'Admin.Global'),
                    'icon' => 'icon-cogs'
                ),
                'input' => array(
                    array(
                        'type' => 'select',
                        'label' => 'Themes: ',
                        'name' => 'DOR_HOMESLIDER_THEME',
                        'options' => array(
                            'query' => $themeData,
                            'id' => 'id',
                            'name'=>'mode',
                        ),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->getTranslator()->trans('Speed', array(), 'Modules.ImageSlider'),
                        'name' => 'DOR_HOMESLIDER_SPEED',
                        'suffix' => 'milliseconds',
                        'class' => 'fixed-width-sm',
                        'desc' => $this->getTranslator()->trans('The duration of the transition between two slides.', array(), 'Modules.ImageSlider')
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->getTranslator()->trans('Thumb Quanity', array(), 'Modules.ImageSlider'),
                        'name' => 'DOR_HOMESLIDER_THUMB_QUANITY',
                        'class' => 'fixed-width-sm',
                        'desc' => $this->getTranslator()->trans('The Quanity Image when thumb.', array(), 'Modules.ImageSlider')
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->getTranslator()->trans('Thumb Width', array(), 'Modules.ImageSlider'),
                        'name' => 'DOR_HOMESLIDER_THUMB_WIDTH',
                        'class' => 'fixed-width-sm',
                        'desc' => $this->getTranslator()->trans('The Width Image when thumb.', array(), 'Modules.ImageSlider')
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->getTranslator()->trans('Thumb Height', array(), 'Modules.ImageSlider'),
                        'name' => 'DOR_HOMESLIDER_THUMB_HEIGHT',
                        'class' => 'fixed-width-sm',
                        'desc' => $this->getTranslator()->trans('The Height Image when thumb.', array(), 'Modules.ImageSlider')
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->getTranslator()->trans('Pause on hover', array(), 'Modules.ImageSlider'),
                        'name' => 'DOR_HOMESLIDER_PAUSE_ON_HOVER',
                        'desc' => $this->getTranslator()->trans('Stop sliding when the mouse cursor is over the slideshow.', array(), 'Modules.ImageSlider'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->getTranslator()->trans('Enabled', array(), 'Admin.Global')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->getTranslator()->trans('Disabled', array(), 'Admin.Global')
                            )
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->getTranslator()->trans('Loop forever', array(), 'Modules.ImageSlider'),
                        'name' => 'DOR_HOMESLIDER_WRAP',
                        'desc' => $this->getTranslator()->trans('Loop or stop after the last slide.', array(), 'Modules.ImageSlider'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->getTranslator()->trans('Enabled', array(), 'Admin.Global')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->getTranslator()->trans('Disabled', array(), 'Admin.Global')
                            )
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->getTranslator()->trans('Auto Play', array(), 'Modules.ImageSlider'),
                        'name' => 'DOR_HOMESLIDER_AUTOPLAY',
                        'desc' => $this->getTranslator()->trans('Auto play image slider.', array(), 'Modules.ImageSlider'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->getTranslator()->trans('Enabled', array(), 'Admin.Global')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->getTranslator()->trans('Disabled', array(), 'Admin.Global')
                            )
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->getTranslator()->trans('Arrow Button', array(), 'Modules.ImageSlider'),
                        'name' => 'DOR_HOMESLIDER_ARROW_BUTTON',
                        'desc' => $this->getTranslator()->trans('Enabled/Disabled Arrow Button Slider.', array(), 'Modules.ImageSlider'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->getTranslator()->trans('Enabled', array(), 'Admin.Global')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->getTranslator()->trans('Disabled', array(), 'Admin.Global')
                            )
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->getTranslator()->trans('Navigator', array(), 'Modules.ImageSlider'),
                        'name' => 'DOR_HOMESLIDER_NAVIGATOR',
                        'desc' => $this->getTranslator()->trans('Enabled/Disabled Navigator Button Slider.', array(), 'Modules.ImageSlider'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->getTranslator()->trans('Enabled', array(), 'Admin.Global')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->getTranslator()->trans('Disabled', array(), 'Admin.Global')
                            )
                        ),
                    ),
                ),
                'submit' => array(
                    'title' => $this->getTranslator()->trans('Save', array(), 'Admin.Actions'),
                )
            ),
        );

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $this->fields_form = array();

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitSlider';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );

        return $helper->generateForm(array($fields_form));
    }

    public function getConfigFieldsValues()
    {
        $id_shop_group = Shop::getContextShopGroupID();
        $id_shop = Shop::getContextShopID();

        return array(
            'DOR_HOMESLIDER_THEME' => Tools::getValue('DOR_HOMESLIDER_THEME', Configuration::get('DOR_HOMESLIDER_THEME', null, $id_shop_group, $id_shop)),
            'DOR_HOMESLIDER_SPEED' => Tools::getValue('DOR_HOMESLIDER_SPEED', Configuration::get('DOR_HOMESLIDER_SPEED', null, $id_shop_group, $id_shop)),
            'DOR_HOMESLIDER_PAUSE_ON_HOVER' => Tools::getValue('DOR_HOMESLIDER_PAUSE_ON_HOVER', Configuration::get('DOR_HOMESLIDER_PAUSE_ON_HOVER', null, $id_shop_group, $id_shop)),
            'DOR_HOMESLIDER_WRAP' => Tools::getValue('DOR_HOMESLIDER_WRAP', Configuration::get('DOR_HOMESLIDER_WRAP', null, $id_shop_group, $id_shop)),
            'DOR_HOMESLIDER_AUTOPLAY' => Tools::getValue('DOR_HOMESLIDER_AUTOPLAY', Configuration::get('DOR_HOMESLIDER_AUTOPLAY', null, $id_shop_group, $id_shop)),
            'DOR_HOMESLIDER_ARROW_BUTTON' => Tools::getValue('DOR_HOMESLIDER_ARROW_BUTTON', Configuration::get('DOR_HOMESLIDER_ARROW_BUTTON', null, $id_shop_group, $id_shop)),
            'DOR_HOMESLIDER_NAVIGATOR' => Tools::getValue('DOR_HOMESLIDER_NAVIGATOR', Configuration::get('DOR_HOMESLIDER_NAVIGATOR', null, $id_shop_group, $id_shop)),
            'DOR_HOMESLIDER_THUMB_QUANITY' => Tools::getValue('DOR_HOMESLIDER_THUMB_QUANITY', Configuration::get('DOR_HOMESLIDER_THUMB_QUANITY', null, $id_shop_group, $id_shop)),
            'DOR_HOMESLIDER_THUMB_WIDTH' => Tools::getValue('DOR_HOMESLIDER_THUMB_WIDTH', Configuration::get('DOR_HOMESLIDER_THUMB_WIDTH', null, $id_shop_group, $id_shop)),
            'DOR_HOMESLIDER_THUMB_HEIGHT' => Tools::getValue('DOR_HOMESLIDER_THUMB_HEIGHT', Configuration::get('DOR_HOMESLIDER_THUMB_HEIGHT', null, $id_shop_group, $id_shop)),
        );
    }

    public function getAddFieldsValues()
    {
        $fields = array();

        if (Tools::isSubmit('id_slide') && $this->slideExists((int)Tools::getValue('id_slide'))) {
            $slide = new DorSlide((int)Tools::getValue('id_slide'));
            $fields['id_slide'] = (int)Tools::getValue('id_slide', $slide->id);
        } else {
            $slide = new DorSlide();
        }

        $fields['active_slide'] = Tools::getValue('active_slide', $slide->active);
        $fields['has_picture'] = true;
        $fields['effect'] = Tools::getValue('effect', $slide->effect);
        $fields['imageproduct'] = Tools::getValue('imageproduct', $slide->imageproduct);
        $languages = Language::getLanguages(false);

        foreach ($languages as $lang) {
            $fields['image'][$lang['id_lang']] = Tools::getValue('image_'.(int)$lang['id_lang']);
            $fields['title'][$lang['id_lang']] = Tools::getValue('title_'.(int)$lang['id_lang'], $slide->title[$lang['id_lang']]);
            $fields['url'][$lang['id_lang']] = Tools::getValue('url_'.(int)$lang['id_lang'], $slide->url[$lang['id_lang']]);
            $fields['legend'][$lang['id_lang']] = Tools::getValue('legend_'.(int)$lang['id_lang'], $slide->legend[$lang['id_lang']]);
            $fields['txtReadmore1'][$lang['id_lang']] = Tools::getValue('txtReadmore1_'.(int)$lang['id_lang'], $slide->txtReadmore1[$lang['id_lang']]);
            $fields['txtReadmore2'][$lang['id_lang']] = Tools::getValue('txtReadmore2_'.(int)$lang['id_lang'], $slide->txtReadmore2[$lang['id_lang']]);
            $fields['UrlReadmore1'][$lang['id_lang']] = Tools::getValue('UrlReadmore1_'.(int)$lang['id_lang'], $slide->UrlReadmore1[$lang['id_lang']]);
            $fields['UrlReadmore2'][$lang['id_lang']] = Tools::getValue('UrlReadmore2_'.(int)$lang['id_lang'], $slide->UrlReadmore2[$lang['id_lang']]);
            $fields['description'][$lang['id_lang']] = Tools::getValue('description_'.(int)$lang['id_lang'], $slide->description[$lang['id_lang']]);
            $fields['price'][$lang['id_lang']] = Tools::getValue('price_'.(int)$lang['id_lang'], $slide->price[$lang['id_lang']]);
        }

        return $fields;
    }

    protected function getMultiLanguageInfoMsg()
    {
        return '<p class="alert alert-warning">'.
                    $this->getTranslator()->trans('Since multiple languages are activated on your shop, please mind to upload your image for each one of them', array(), 'Modules.ImageSlider').
                '</p>';
    }

    protected function getWarningMultishopHtml()
    {
        if (Shop::getContext() == Shop::CONTEXT_GROUP || Shop::getContext() == Shop::CONTEXT_ALL) {
            return '<p class="alert alert-warning">' .
            $this->getTranslator()->trans('You cannot manage slides items from a "All Shops" or a "Group Shop" context, select directly the shop you want to edit', array(), 'Modules.ImageSlider') .
            '</p>';
        } else {
            return '';
        }
    }

    protected function getShopContextError($shop_contextualized_name, $mode)
    {
        if (is_array($shop_contextualized_name)) {
            $shop_contextualized_name = implode('<br/>', $shop_contextualized_name);
        }

        if ($mode == 'edit') {
            return '<p class="alert alert-danger">' .
            sprintf($this->getTranslator()->trans('You can only edit this slide from the shop(s) context: %s', array(), 'Modules.ImageSlider'), $shop_contextualized_name) .
            '</p>';
        } else {
            return '<p class="alert alert-danger">' .
            sprintf($this->getTranslator()->trans('You cannot add slides from a "All Shops" or a "Group Shop" context', array(), 'Modules.ImageSlider')) .
            '</p>';
        }
    }

    protected function getShopAssociationError($id_slide)
    {
        return '<p class="alert alert-danger">'.
                        sprintf($this->getTranslator()->trans('Unable to get slide shop association information (id_slide: %d)', array(), 'Modules.ImageSlider'), (int)$id_slide).
                '</p>';
    }


    protected function getCurrentShopInfoMsg()
    {
        $shop_info = null;

        if (Shop::isFeatureActive()) {
            if (Shop::getContext() == Shop::CONTEXT_SHOP) {
                $shop_info = sprintf($this->getTranslator()->trans('The modifications will be applied to shop: %s', array(),'Modules.ImageSlider'), $this->context->shop->name);
            } else if (Shop::getContext() == Shop::CONTEXT_GROUP) {
                $shop_info = sprintf($this->getTranslator()->trans('The modifications will be applied to this group: %s', array(), 'Modules.ImageSlider'), Shop::getContextShopGroup()->name);
            } else {
                $shop_info = $this->getTranslator()->trans('The modifications will be applied to all shops and shop groups', array(), 'Modules.ImageSlider');
            }

            return '<div class="alert alert-info">'.
                        $shop_info.
                    '</div>';
        } else {
            return '';
        }
    }

    protected function getSharedSlideWarning()
    {
        return '<p class="alert alert-warning">'.
                    $this->getTranslator()->trans('This slide is shared with other shops! All shops associated to this slide will apply modifications made here', array(), 'Modules.ImageSlider').
                '</p>';
    }
}
