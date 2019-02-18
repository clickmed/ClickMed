<?php
class AdminBlogcommentController extends AdminController {
    public $asso_type = 'shop';
    public function __construct() {
        $this->table = 'smart_blog_comment';
        $this->className = 'Blogcomment';
        $this->module = 'smartblog';
        $this->context = Context::getContext();
        $this->bootstrap = true;
        if (Shop::isFeatureActive())
            Shop::addTableAssociation($this->table, array('type' => 'shop'));
		parent::__construct();
                
        $this->fields_list = array(
                            'id_smart_blog_comment' => array(
                                    'title' => Context::getContext()->getTranslator()->trans('Id', array(), 'Modules.smartblog'),
                                    'width' => 100,
                                    'type' => 'text',
                            ),
                            'name' => array(
                                    'title' => Context::getContext()->getTranslator()->trans('Name', array(), 'Modules.smartblog'),
                                    'width' => 150,
                                    'type' => 'text'
                            ),
                            'content' => array(
                                    'title' => Context::getContext()->getTranslator()->trans('Comment', array(), 'Modules.smartblog'),
                                    'width' => 340,
                                    'type' => 'text'
                            ),
                            'created' => array(
                                    'title' => Context::getContext()->getTranslator()->trans('Date', array(), 'Modules.smartblog'),
                                    'width' => 60,
                                    'type' => 'text',
                                    'lang' => true
                            ),
                            'active' => array(
                                'title' => Context::getContext()->getTranslator()->trans('Status', array(), 'Modules.smartblog'),
                                'width' => '70',
                                'align' => 'center',
                                'active' => 'status',
                                'type' => 'bool',
                                'orderby' => false
                            )
                    );
        
            $this->_join = 'LEFT JOIN '._DB_PREFIX_.'smart_blog_comment_shop sbs ON a.id_smart_blog_comment=sbs.id_smart_blog_comment && sbs.id_shop IN('.implode(',',Shop::getContextListShopID()).')';
 
        $this->_select = 'sbs.id_shop';
        $this->_defaultOrderBy = 'a.id_smart_blog_comment';
        $this->_defaultOrderWay = 'DESC';
        
        if (Shop::isFeatureActive() && Shop::getContext() != Shop::CONTEXT_SHOP)
        {
           $this->_group = 'GROUP BY a.id_smart_blog_comment';
        }


        parent::__construct();
    }

    public function renderList() {
        $this->addRowAction('edit');
        $this->addRowAction('delete');
        return parent::renderList();
    }
    public function renderForm()
     {
        $this->fields_form = array(
          'legend' => array(
          'title' => Context::getContext()->getTranslator()->trans('Blog Comment', array(), 'Modules.smartblog'),
            ),
            'input' => array(
                array(
                    'type' => 'textarea',
                    'label' => Context::getContext()->getTranslator()->trans('Comment', array(), 'Modules.smartblog'),
                    'name' => 'content',
                    'rows' => 10,
                    'cols' => 62,
                    'class' => 'rte',
                    'autoload_rte' => false,
                    'required' => false,
                     'desc' => Context::getContext()->getTranslator()->trans('Enter Your Category Description', array(), 'Modules.smartblog')
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

        if (!($Blogcomment = $this->loadObject(true)))
            return;

        $this->fields_form['submit'] = array(
            'title' => Context::getContext()->getTranslator()->trans('Save', array(), 'Modules.smartblog'),
            'class' => 'button'
        );
        return parent::renderForm();
    }
    
    public function initToolbar() {

        parent::initToolbar();
    }
}

