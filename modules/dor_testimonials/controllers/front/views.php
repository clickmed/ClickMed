<?php
@session_start();
@include_once(_PS_MODULE_DIR_ . 'dor_testimonials/dor_testimonials.php');
@include_once(_PS_MODULE_DIR_ . 'dor_testimonials/classes/DorTestimonial.php');
@include_once(_PS_MODULE_DIR_ . 'dor_testimonials/classes/DorFileUploader.php');
@include_once(_PS_MODULE_DIR_ . 'dor_testimonials/libs/Params.php');
class dortestimonialsViewsModuleFrontController extends ModuleFrontController
{
    public $errors = array();
    public $success;
    public $identifier;
    public function __construct()
    {
        parent::__construct();
        $this->context = Context::getContext();
        $this->name = 'dor_testimonials';
        $this->identifier = 'id_dortestimonial';
        smartyRegisterFunction($this->context->smarty, 'function', 'testimonialpaginationlink', array('dortestimonialsViewsModuleFrontController', 'getTestimonialPaginationLink'));
    }

    public function initContent()
    {
        $this->display_column_left = true;
        $this->display_column_right = true;
        parent::initContent();
        if (Tools::getValue('process') == 'view' || Tools::getValue('process') == 'read_more') {
            $this->listAllTestimoninals();
        } elseif (Tools::getValue('process') == 'form_submit') {
            $this->formTestimoninals();
        }
    }

    public static function getTestimonialPaginationLink($params, &$smarty)
    {
        $id = Tools::getValue('id');
        if (!isset($params['p']))
            $p = 1;
        else
            $p = $params['p'];
        if (!isset($params['n']))
            $n = 10;
        else
            $n = $params['n'];
        return Context::getContext()->link->getModuleLink(
            'dor_testimonials',
            'views',
            array(
                'process' => 'view',
                'id' => $id,
                'p' => $p,
                'n' => $n,
            )
        );
    }

    public function listAllTestimoninals()
    {
        $this->addCSS(_MODULE_DIR_ . $this->name . '/assets/front/css/style.css');
        $this->name = 'dortestimonial';
        $this->_configs = '';
        $this->addJqueryPlugin('fancybox');
        $image_type = explode('|', $this->module->getParams()->get('type_image'));
        $video_type = explode('|', $this->module->getParams()->get('type_video'));
        $p = Tools::getValue('p', 1);
        $n = Tools::getValue('n', $this->module->getParams()->get('test_limit'));
        $id = (int)Tools::getValue('id', 0);
      //  var_dump($id);die;
        if ($id == 0) {
            $alltestimoninals = DorTestimonial::getAllTestimonials();
            $testimoninals = DorTestimonial::getAllTestimonials($p, $n);
         //   echo "<pre>".print_r($alltestimoninals,1);die;
            $max_page = floor(sizeof($alltestimoninals) / ((int)(Tools::getValue('n') > 0) ? (int)(Tools::getValue('n')) : 10));
        } else {
            $testimoninals = DorTestimonial::getAllTestimonials(1, false, $id, false); //view all and curent testimonial

            $alltestimoninals = DorTestimonial::getAllTestimonials(1, false, false, $id); // view all other testimonial
            $page_other_testimonials = DorTestimonial::getAllTestimonials($p, $n, false, $id); // item on page
            $max_page = floor(sizeof($alltestimoninals) / ((int)($n > 0) ? (int)$n : 10));
            $this->context->smarty->assign(array('page_other_testimonials' => $page_other_testimonials));
        }
        $this->context->smarty->assign(array(
            'page' => ((int)$p > 0 ? (int)$p : 1),
            'nbpagination' => ((int)($n > 0) ? (int)$n : $n),
            'nArray' => array(10, 20, 50),
            'max_page' => $max_page,
            'alltestimoninals' => $alltestimoninals,
            'testimoninals' => $testimoninals,
            'id' => $id,
            'image_type' => $image_type,
            'video_type' => $video_type,
            'name' => $this->name,
        ));
        $this->setTemplate('all_testimonials.tpl');
    }

    public function formTestimoninals()
    {
        $this->addCSS(_MODULE_DIR_ . $this->name . '/assets/front/css/style.css');
        $this->addJS(_PS_JS_DIR_ . 'validate.js');
        $this->addJS(_THEME_JS_DIR_ . 'validate_fields.js');
        $tm_captcha = (int)$this->module->getParams()->get('captcha');
        $captcha_code = _MODULE_DIR_ . 'dor_testimonials/captcha.php';
        $loader_image = $video_vimeo = _MODULE_DIR_ . $this->name . 'assets/front/img/loading.gif';
        $this->context->smarty->assign(array(
            'captcha' => $tm_captcha,
            'captcha_code' => $captcha_code,
            'name_post' => html_entity_decode(Tools::getValue('name_post')),
            'company' => html_entity_decode(Tools::getValue('company')),
            'address' => html_entity_decode(Tools::getValue('address')),
            'media_link' => html_entity_decode(Tools::getValue('media_link')),
            'content' => html_entity_decode(Tools::getValue('content')),
            'email' => html_entity_decode(Tools::getValue('email')),
            'errors' => $this->errors,
            'success' => $this->success,
            'loader_image' => $loader_image,
        ));
        $this->setTemplate('form_submit.tpl');
    }

    public function postProcess()
    {
        if (Tools::isSubmit('submitNewTestimonial')) {
            $this->postValidation();
            if (($_FILES['media']['name']) != null) {
                $upload = new DorFileUploader($this->module, $_FILES['media']);
                $res = $upload->handleUpload();
                if (!empty($upload->errors)) {
                    if (is_array($upload->errors))
                        $this->errors = array_merge($this->errors, $upload->errors);
                    else
                        $this->errors[] = $upload->errors;
                }
            }
            if (!count($this->errors)) {
                $obj = new DorTestimonial();
                $obj->name_post = Tools::getValue('name_post');
                $obj->email = Tools::getValue('email');
                $obj->company = Tools::getValue('company');
                $obj->address = Tools::getValue('address');
                $obj->media = '';
                $obj->media_type = '';
                $obj->media_link_id = $obj->getIdFromLinkInput(Tools::getValue('media_link'));
                if (isset($res) && $res != null) {
                    $obj->media = $res['name'];
                    $obj->media_type = $res['type'];
                }
                $obj->content = Tools::getValue('content');
                if ((int)$this->module->getParams()->get('auto_post') == 1)
                    $obj->active = 0;
                else
                    $obj->active = 1;

                $save_value = $obj->add();
                if (!$save_value)
                    $this->errors[] = $this->module->l('Your testimonial could not be insert. Please, check all again!');
                else
                    $this->success = $this->module->l('Send successfully.');
            }
        }
    }

    public function postValidation()
    {
        $this->validateRules('dortestimonial');
        if (Tools::isSubmit('submitNewTestimonial')) {
            if (Tools::getValue('media_link')) {
                $link = explode('/', Tools::getValue('media_link'));
                if ($link[2] == 'www.youtube.com' || $link[2] == 'vimeo.com') {
                    return true;
                } else {
                    $this->errors[] = $this->module->l('Media link require link youtube or vimeo');
                    return false;
                }
            }
            $captcha = $_SESSION['dortestimonials_captcha'];
            if ((int)$this->module->getParams()->get('captcha')) {
                if (!strtolower(Tools::getValue('captcha')) || strtolower(Tools::getValue('captcha')) != strtolower($captcha))
                    $this->errors[] = $this->module->l('Captcha is incorrect.');
            }
        }
    }

    public function validateRules($class_name = false)
    {
        if (!$class_name)
            $class_name = $this->className;
        if (!empty($class_name))
            $rules = @call_user_func(array($class_name, 'getValidationRules'), $class_name);
        if (isset($rules) && count($rules) && (count($rules['requiredLang']) || count($rules['sizeLang']) || count($rules['validateLang']))) {
            $default_language = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
            $languages = Language::getLanguages(false);
        }
        /* Checking for required fields */
        if (isset($rules['required']) && is_array($rules['required']))
            foreach ($rules['required'] as $field)
                if (($value = Tools::getValue($field)) == false && (string)$value != '0')
                    if (!Tools::getValue($this->identifier) || ($field != 'passwd' && $field != 'no-picture'))
                        $this->errors[] = sprintf(
                            Tools::displayError('The %s field is required.'),
                            call_user_func(array($class_name, 'displayFieldName'), $field, $class_name)
                        );
        /* Checking for maximum fields sizes */
        if (isset($rules['size']) && is_array($rules['size']))
            foreach ($rules['size'] as $field => $max_length)
                if (Tools::getValue($field) !== false && Tools::strlen(Tools::getValue($field)) > $max_length)
                    $this->errors[] = sprintf(
                        Tools::displayError('The %1$s field is too long (%2$d chars max).'),
                        call_user_func(array($class_name, 'displayFieldName'), $field, $class_name),
                        $max_length
                    );
        /* Checking for maximum multilingual fields size */
        if (isset($rules['sizeLang']) && is_array($rules['sizeLang']))
            foreach ($rules['sizeLang'] as $field_lang => $max_length)
                foreach ($languages as $language) {
                    $field_lang_value = Tools::getValue($field_lang . '_' . $language['id_lang']);
                    if ($field_lang_value !== false && Tools::strlen($field_lang_value) > $max_length)
                        $this->errors[] = sprintf(
                            Tools::displayError('The field %1$s (%2$s) is too long (%3$d chars max, html chars including).'),
                            call_user_func(array($class_name, 'displayFieldName'), $field_lang, $class_name),
                            $language['name'],
                            $max_length
                        );
                }
        /* Checking for fields validity */
        if (isset($rules['validate']) && is_array($rules['validate']))
            foreach ($rules['validate'] as $field => $function)
                if (($value = Tools::getValue($field)) !== false && ($field != 'passwd'))
                    if (!Validate::$function($value) && !empty($value))
                        $this->errors[] = sprintf(
                            Tools::displayError('The %s field is invalid.'),
                            call_user_func(array($class_name, 'displayFieldName'), $field, $class_name)
                        );
        /* Checking for multilingual fields validity */
    }
}
