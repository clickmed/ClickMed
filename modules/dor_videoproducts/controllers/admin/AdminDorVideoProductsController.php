<?php

class AdminDorVideoProductsController extends AdminController {
    public $bootstrap = true;
    public $ssl = true;
    protected $id_banner;
    public function __construct() {
        $this->table = 'dor_videoproducts';
        $this->className = 'DorVideoProduct';
        $this->identifier = 'videoId';
        $this->lang = false;
		$this->bootstrap = true;
        $this->deleted = false;
        $this->colorOnBackground = false;
        $this->_defaultOrderBy = 'videoId';
        $this->bulk_actions = array('delete' => array('text' => Context::getContext()->getTranslator()->trans('Delete selected', array(), 'Modules.dor_videoproducts'), 'confirm' => Context::getContext()->getTranslator()->trans('Delete selected items?', array(), 'Modules.dor_videoproducts')));
        $this->context = Context::getContext();
        parent::__construct();
        $this->fields_list = array(
            'videoId' => array(
                'title' => Context::getContext()->getTranslator()->trans('Video ID', array(), 'Modules.dor_videoproducts'),
                'align' => 'center',
                'width' => 25,
                'lang' => false
            ),
            'id_product' => array(
                'title' => Context::getContext()->getTranslator()->trans('Product ID', array(), 'Modules.dor_videoproducts'),
                'width' => 90,
                'lang' => false
            ),
            'url' => array(
                'title' => Context::getContext()->getTranslator()->trans('Youtube', array(), 'Modules.dor_videoproducts'),
                'width' => '100',
                'lang' => false,
                'require' => true
            ),
            'width' => array(
                'title' => Context::getContext()->getTranslator()->trans('Video Width', array(), 'Modules.dor_videoproducts'),
                'width' => 25,
                'lang' => false,
                'require' => true
            ),
            'height' => array(
                'title' => Context::getContext()->getTranslator()->trans('Video height', array(), 'Modules.dor_videoproducts'),
                'width' => 25,
                'lang' => false,
                'require' => true
            )
        );
        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->trans('Delete selected', array(), 'Admin.Notifications.Info'),
                'icon' => 'icon-trash',
                'confirm' => $this->trans('Delete selected items?', array(), 'Admin.Notifications.Info')
            )
        );
    }
    public function renderList()
    {
        $this->addRowAction('edit');
        $this->addRowAction('delete');
        return parent::renderList();
    }
    
  

    public function renderForm() {
        $this->table = 'dor_videoproducts';
        $this->identifier = 'videoId';
        $this->fields_form = array(
            'tinymce' => true,
            'legend' => array(
                'title' => Context::getContext()->getTranslator()->trans('Dor Video Products'),
                'image' => '../img/admin/cog.gif'
            ),
            
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => Context::getContext()->getTranslator()->trans('Youtube URL:'),
                    'name' => 'url',
                    'col' => '4',
                    'lang' => false,
                    'require' => true
                ),
                array(
                    'type' => 'text',
                    'label' => Context::getContext()->getTranslator()->trans('Video Width'),
                    'name' => 'width',
                    'col' => '2',
                    'lang' => false,
                    'require' => true
                ),
                array(
                    'type' => 'text',
                    'label' => Context::getContext()->getTranslator()->trans('Video Height'),
                    'name' => 'height',
                    'col' => '2',
                    'lang' => false,
                    'require' => true
                ),
                array(
                    'type' => 'text',
                    'label' => Context::getContext()->getTranslator()->trans('Product ID'),
                    'name' => 'id_product',
                    'col' => '4',
                    'lang' => false,
                    'require' => true
                )
            ),
            'submit' => array(
                'title' => Context::getContext()->getTranslator()->trans('Save'),
                'class' => 'btn btn-default pull-right'
            )
        );
        /*if (Shop::isFeatureActive()) {
            $this->fields_form['input'][] = array(
                'type' => 'shop',
                'label' => Context::getContext()->getTranslator()->trans('Shop association', array(), 'Admin.Global'),
                'name' => 'checkBoxShopAsso',
            );
        }
*/
        $this->fields_form['submit'] = array(
            'title' => Context::getContext()->getTranslator()->trans('Save', array(), 'Admin.Actions'),
        );

        if (!($obj = $this->loadObject(true))) {
            return;
        }

        return parent::renderForm();
    }
    
}