<?php
class AdminImageTypeController extends AdminController {

    public function __construct() {
        $this->table = 'smart_blog_imagetype';
        $this->className = 'BlogImageType';
        $this->module = 'smartblog';
        $this->lang = false;
        $this->context = Context::getContext();
        $this->bootstrap = true;
        $this->fields_list = array(
                            'id_smart_blog_imagetype' => array(
                                    'title' => Context::getContext()->getTranslator()->trans('Id', array(), 'Modules.smartblog'),
                                    'width' => 100,
                                    'type' => 'text',
                            ),
                            'type_name' => array(
                                    'title' => Context::getContext()->getTranslator()->trans('Type Name', array(), 'Modules.smartblog'),
                                    'width' => 350,
                                    'type' => 'text',
                            ),
                            'width' => array(
                                    'title' => Context::getContext()->getTranslator()->trans('Width', array(), 'Modules.smartblog'),
                                    'width' => 60,
                                    'type' => 'text',
                            ),
                            'height' => array(
                                    'title' => Context::getContext()->getTranslator()->trans('Height', array(), 'Modules.smartblog'),
                                    'width' => 60,
                                    'type' => 'text',
                            ),
                            'type' => array(
                                    'title' => Context::getContext()->getTranslator()->trans('Type', array(), 'Modules.smartblog'),
                                    'width' => 220,
                                    'type' => 'text',
                            ),
                            'active' => array(
                                'title' => Context::getContext()->getTranslator()->trans('Status', array(), 'Modules.smartblog'),
                                'width' => 60,
                                'align' => 'center',
                                'active' => 'status',
                                'type' => 'bool',
                                'orderby' => false
                            )
                    );
        parent::__construct();
    }

    public function renderForm()
            {
        $this->fields_form = array(
          'legend' => array(
          'title' => Context::getContext()->getTranslator()->trans('Blog Category', array(), 'Modules.smartblog'),
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => Context::getContext()->getTranslator()->trans('Image Type Name', array(), 'Modules.smartblog'),
                    'name' => 'type_name',
                    'size' => 60,
                    'required' => true,
                    'desc' => Context::getContext()->getTranslator()->trans('Enter Your Image Type Name Here', array(), 'Modules.smartblog'),
                ),
                array(
                    'type' => 'text',
                    'label' => Context::getContext()->getTranslator()->trans('width', array(), 'Modules.smartblog'),
                    'name' => 'width',
                    'size' => 15,
                    'required' => true,
                    'desc' => Context::getContext()->getTranslator()->trans('Image height in px', array(), 'Modules.smartblog')
                ),
                array(
                    'type' => 'text',
                    'label' => Context::getContext()->getTranslator()->trans('Height', array(), 'Modules.smartblog'),
                    'name' => 'height',
                    'size' => 15,
                    'required' => true,
                    'desc' => Context::getContext()->getTranslator()->trans('Image height in px', array(), 'Modules.smartblog')
                ),
                                    array(
                                    'type' => 'select',
                                    'label' => Context::getContext()->getTranslator()->trans('Type', array(), 'Modules.smartblog'),
                                    'name' => 'type',
                                    'required' => true,
                                    'options' => array(
                                    'query' => array(
                                                array(
                                                'id_option' => 'post',
                                                'name' => 'Post'
                                                ),
                                                array(
                                                'id_option' => 'Category',
                                                'name' => 'category'
                                                ),
                                                array(
                                                'id_option' => 'Author',
                                                'name' => 'author'
                                                )
                                            ),
                                    'id' => 'id_option',
                                    'name' => 'name'
                                    )
                        ),
                array(
                                        'type' => 'radio',
                                        'label' => Context::getContext()->getTranslator()->trans('Status', array(), 'Modules.smartblog'),
                                        'name' => 'active',
                                        'required' => false,
                                        'class' => 't',
                                        'is_bool' => true,
                                        'values' => array(
                                            array(
                                            'id' => 'active',
                                            'value' => 1,
                                            'label' => Context::getContext()->getTranslator()->trans('Enabled', array(), 'Modules.smartblog')
                                            ),
                                            array(
                                            'id' => 'active',
                                            'value' => 0,
                                            'label' => Context::getContext()->getTranslator()->trans('Disabled', array(), 'Modules.smartblog')
                                            )
                                            )
                                     )
            ),
            'submit' => array(
                'title' => Context::getContext()->getTranslator()->trans('Save', array(), 'Modules.smartblog'),
                'class' => 'button'
            )
        );

        if (!($BlogImageType = $this->loadObject(true)))
            return;

        $this->fields_form['submit'] = array(
            'title' => Context::getContext()->getTranslator()->trans('Save', array(), 'Modules.smartblog'),
            'class' => 'button'
        );
        return parent::renderForm();
    }
    public function renderList() {
        $this->addRowAction('edit');
        $this->addRowAction('delete');
        return parent::renderList();;
    }
    public function initToolbar() {
        parent::initToolbar();
    }
}
