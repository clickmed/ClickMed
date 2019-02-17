<?php

class AdminDorManagerBlocksController extends AdminController {
    public $bootstrap = true;
    public $ssl = true;
    protected $id_banner;
    public function __construct() {
        $this->table = 'dor_managerblock';
        $this->className = 'Managerblock';
        $this->identifier = 'id_dor_managerblock';
        $this->lang = true;
		$this->bootstrap = true;
        $this->deleted = false;
        $this->colorOnBackground = false;
        $this->_defaultOrderBy = 'position';
        $this->bulk_actions = array('delete' => array('text' => Context::getContext()->getTranslator()->trans('Delete selected', array(), 'Modules.Dor_managerblockfooter'), 'confirm' => Context::getContext()->getTranslator()->trans('Delete selected items?', array(), 'Modules.Dor_managerblockfooter')));
        Shop::addTableAssociation($this->table, array('type' => 'shop'));
        $this->context = Context::getContext();
        parent::__construct();
        $this->fields_list = array(
            'id_dor_managerblock' => array(
                'title' => Context::getContext()->getTranslator()->trans('ID', array(), 'Modules.Dor_managerblockfooter'),
                'align' => 'center',
                'width' => 25,
                'lang' => false
            ),
            'title' => array(
                'title' => Context::getContext()->getTranslator()->trans('Title', array(), 'Modules.Dor_managerblockfooter'),
                'width' => 90,
                'lang' => false
            ),
            'identify' => array(
                'title' => Context::getContext()->getTranslator()->trans('Identify', array(), 'Modules.Dor_managerblockfooter'),
                'width' => '100',
                'lang' => false
            ),
            'hook_position' => array(
                'title' => Context::getContext()->getTranslator()->trans('Hook Position', array(), 'Modules.Dor_managerblockfooter'),
                'width' => '300',
                'lang' => false
            ),
            'position' => array(
                'title' => Context::getContext()->getTranslator()->trans('Order', array(), 'Modules.Dor_managerblockfooter'),
                'width' => '30',
                'lang' => false
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
        $this->table = 'dor_managerblock';
        $this->identifier = 'id_dor_managerblock';
        $mod = new dor_managerblocks();
        $listModules = $mod->getListModuleInstalled();
        $listHookPosition = array(
            array('hook_position'=> 'top'),
            array('hook_position'=> 'displayNav1'),
            array('hook_position'=> 'displayNav2'),
            array('hook_position'=> 'displayTop'),
            array('hook_position'=> 'displayTopColumn'),
            array('hook_position'=>'rightColumn'),
            array('hook_position'=> 'leftColumn'),
            array('hook_position'=>'displayHeader'),
            array('hook_position'=>'home'),
            array('hook_position'=>'topbarDorado1'),
            array('hook_position'=>'topbarDorado2'),
            array('hook_position'=>'topbarDorado3'),
            array('hook_position'=>'topbarDorado4'),
            array('hook_position'=>'topbarDorado5'),
            array('hook_position'=>'topbarDorado6'),
            array('hook_position'=>'topbarDorado7'),
            array('hook_position'=>'topbarDorado8'),
            array('hook_position'=>'headerDorado1'),
            array('hook_position'=>'headerDorado2'),
            array('hook_position'=>'headerDorado3'),
            array('hook_position'=>'headerDorado4'),
            array('hook_position'=>'headerDorado5'),
            array('hook_position'=>'headerDorado6'),
            array('hook_position'=>'headerDorado7'),
            array('hook_position'=>'headerDorado8'),
            array('hook_position'=>'blockDorado1'),
            array('hook_position'=>'blockDorado2'),
            array('hook_position'=>'blockDorado3'),
            array('hook_position'=>'blockDorado4'),
            array('hook_position'=>'blockDorado5'),
            array('hook_position'=>'blockDorado6'),
            array('hook_position'=>'blockDorado7'),
            array('hook_position'=>'blockDorado8'),
            array('hook_position'=>'blockDorado9'),
            array('hook_position'=>'blockDorado10'),
            array('hook_position'=>'displayNav'),
            array('hook_position'=>'displaySearch'),
            array('hook_position'=>'displayFooter'),
            array('hook_position'=>'displayFooterBefore'),
            array('hook_position'=>'displayDorRightColumn'),
            array('hook_position'=>'displayDorLeftColumn'),
            array('hook_position'=>'displaySmartBlogLeft'),
            array('hook_position'=>'displaySmartBlogRight'),
            array('hook_position'=>'dorHomepageBar'),
            array('hook_position'=>'bannerSlide'),
	        array('hook_position'=>'displayBackOfficeHeader'),


        );
        
        $this->fields_form = array(
            'tinymce' => true,
            'legend' => array(
                'title' => Context::getContext()->getTranslator()->trans('Slideshow'),
                'image' => '../img/admin/cog.gif'
            ),
            
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => Context::getContext()->getTranslator()->trans('Title:'),
                    'name' => 'title',
                    'col' => '4',
                    'lang' => true
                ),
                array(
                    'type' => 'text',
                    'label' => Context::getContext()->getTranslator()->trans('Identify:'),
                    'name' => 'identify',
                    'col' => '4',
                    'require' => false
                ),
                array(
                  'type'      => 'radio',                              
                  'label'     => Context::getContext()->getTranslator()->trans('Show/hide title'),       
                  'desc'      => Context::getContext()->getTranslator()->trans('Show/hide title?'),  
                  'name'      => 'active',                             
                  'required'  => true,                                 
                  'class'     => 't',                                  
                  'is_bool'   => true,                                 
                  'values'    => array(                                
                        array(
                          'id'    => 'active_on',                          
                          'value' => 1,                                    
                          'label' => Context::getContext()->getTranslator()->trans('Enabled')                   
                        ),
                        array(
                          'id'    => 'active_off',
                          'value' => 0,
                          'label' => Context::getContext()->getTranslator()->trans('Disabled')
                        )
                  ),
                ),
               array(
                'type' => 'select',
                'label' => Context::getContext()->getTranslator()->trans('Hook Position:'),
                'name' => 'hook_position',
                'required' => true,
                'options' => array(
                    'query' => $listHookPosition,
                    'id' => 'hook_position',
                    'name' => 'hook_position'
                ),
             
                'desc' => Context::getContext()->getTranslator()->trans('Choose the type of the Hooks')
            ),
            
            array(
                              'type'      => 'radio',                              
                              'label'     => Context::getContext()->getTranslator()->trans('Show/hide Hook'),       
                              'desc'      => Context::getContext()->getTranslator()->trans('Show/hide Hook?'),  
                              'name'      => 'showhook',                             
                              'required'  => true,                                 
                              'class'     => 't',                                  
                              'is_bool'   => true,                                 
                              'values'    => array(                                
                                    array(
                                      'id'    => 'active_on',                          
                                      'value' => 1,                                    
                                      'label' => Context::getContext()->getTranslator()->trans('Enabled')                   
                                    ),
                                    array(
                                      'id'    => 'active_off',
                                      'value' => 0,
                                      'label' => Context::getContext()->getTranslator()->trans('Disabled')
                                    )
                              ),
                            ),
			    array(
                    'type' => 'textarea',
                    'label' => Context::getContext()->getTranslator()->trans('Description'),
                    'name' => 'description',
                    'autoload_rte' => TRUE,
                    'lang' => true,
                    'required' => TRUE,
                    'rows' => 5,
                    'cols' => 30,
                    'hint' => Context::getContext()->getTranslator()->trans('Invalid characters:') . ' <>;=#{}'
                ),
                
                array(
                    'type' => 'text',
                    'label' => Context::getContext()->getTranslator()->trans('Order:'),
                    'name' => 'position',
                    'col' => '4',
                    'require' => false
                ),
            ),
            'submit' => array(
                'title' => Context::getContext()->getTranslator()->trans('Save'),
                'class' => 'btn btn-default pull-right'
            )
        );
        if (Shop::isFeatureActive()) {
            $this->fields_form['input'][] = array(
                'type' => 'shop',
                'label' => Context::getContext()->getTranslator()->trans('Shop association', array(), 'Admin.Global'),
                'name' => 'checkBoxShopAsso',
            );
        }

        $this->fields_form['submit'] = array(
            'title' => Context::getContext()->getTranslator()->trans('Save', array(), 'Admin.Actions'),
        );

        if (!($obj = $this->loadObject(true))) {
            return;
        }

        return parent::renderForm();
    }
    
}