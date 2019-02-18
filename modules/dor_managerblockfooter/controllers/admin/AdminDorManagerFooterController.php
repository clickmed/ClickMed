<?php

class AdminDorManagerFooterController extends AdminController {
    public $bootstrap = true;
    public $ssl = true;
    protected $id_banner;
    public function __construct() {
        $this->table = 'dor_blockfooter';
        $this->className = 'Managerblockfooter';
        $this->identifier = 'id_dor_blockfooter';
	    $this->bootstrap = true;
        $this->lang = true;
        $this->deleted = false;
        $this->colorOnBackground = false;
        $this->bulk_actions = array('delete' => array('text' => Context::getContext()->getTranslator()->trans('Delete selected', array(), 'Modules.Dor_managerblockfooter'), 'confirm' => Context::getContext()->getTranslator()->trans('Delete selected items?', array(), 'Modules.Dor_managerblockfooter')));
        Shop::addTableAssociation($this->table, array('type' => 'shop'));
        $this->context = Context::getContext();

        parent::__construct();
        $this->fields_list = array(
            'id_dor_blockfooter' => array(
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
            'order' => array(
                'title' => Context::getContext()->getTranslator()->trans('Order Position', array(), 'Modules.Dor_managerblockfooter'),
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
        $this->addRowAction('view');
        $this->addRowAction('edit');
        $this->addRowAction('delete');
        return parent::renderList();
    }
    
  

    public function renderForm() {
        $this->table = 'dor_blockfooter';
        $this->identifier = 'id_dor_blockfooter';
        $mod = new Dor_managerblockfooter();
        $listModules = $mod->getListModuleInstalled();
        $listHookFooterModules = array(
            array('hook_position'=>'displayFooter'),
        );
        $listHookModules = array(
            array(
                'id' => 'displayFooter',
                'name' => Context::getContext()->getTranslator()->trans('Display Footer', array(), 'Admin.Global')
            ),
            array(
                'id' => 'blockDoradoFooter',
                'name' => Context::getContext()->getTranslator()->trans('Dorado Footer', array(), 'Admin.Global')
            ),
            array(
                'id' => 'doradoFooterTop',
                'name' => Context::getContext()->getTranslator()->trans('Dorado Footer Top', array(), 'Admin.Global')
            ),
            array(
                'id' => 'doradoFooterAdv',
                'name' => Context::getContext()->getTranslator()->trans('Dorado Footer Adv', array(), 'Admin.Global')
            ),
            array(
                'id' => 'doradoFooter1',
                'name' => Context::getContext()->getTranslator()->trans('Dorado Footer 1', array(), 'Admin.Global')
            ),
            array(
                'id' => 'doradoFooter2',
                'name' => Context::getContext()->getTranslator()->trans('Dorado Footer 2', array(), 'Admin.Global')
            ),
            array(
                'id' => 'doradoFooter3',
                'name' => Context::getContext()->getTranslator()->trans('Dorado Footer 3', array(), 'Admin.Global')
            ),
            array(
                'id' => 'doradoFooter4',
                'name' => Context::getContext()->getTranslator()->trans('Dorado Footer 4', array(), 'Admin.Global')
            ),
            array(
                'id' => 'doradoFooter5',
                'name' => Context::getContext()->getTranslator()->trans('Dorado Footer 5', array(), 'Admin.Global')
            ),
            array(
                'id' => 'doradoFooter6',
                'name' => Context::getContext()->getTranslator()->trans('Dorado Footer 6', array(), 'Admin.Global')
            ),
            array(
                'id' => 'doradoFooter7',
                'name' => Context::getContext()->getTranslator()->trans('Dorado Footer 7', array(), 'Admin.Global')
            ),
            array(
                'id' => 'doradoFooter8',
                'name' => Context::getContext()->getTranslator()->trans('Dorado Footer 8', array(), 'Admin.Global')
            ),
            array(
                'id' => 'doradoFooter9',
                'name' => Context::getContext()->getTranslator()->trans('Dorado Footer 9', array(), 'Admin.Global')
            ),
            array(
                'id' => 'doradoFooter10',
                'name' => Context::getContext()->getTranslator()->trans('Dorado Footer 10', array(), 'Admin.Global')
            ),
        );
        $this->fields_form = array(
            'legend' => array(
                'title' => Context::getContext()->getTranslator()->trans('Attributes', array(), 'Admin.Catalog.Feature'),
                'icon' => 'icon-info-sign'
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => Context::getContext()->getTranslator()->trans('Title', array(), 'Admin.Global'),
                    'name' => 'title',
                    'lang' => true,
                    'required' => true,
                    'col' => '4',
                    'hint' => Context::getContext()->getTranslator()->trans('Your internal name for this attribute.', array(), 'Admin.Catalog.Help').'&nbsp;'.Context::getContext()->getTranslator()->trans('Invalid characters:', array(), 'Admin.Notifications.Info').' <>;=#{}'
                ),
                array(
                    'type' => 'text',
                    'label' => Context::getContext()->getTranslator()->trans('Identify', array(), 'Admin.Global'),
                    'name' => 'identify',
                    'col' => '4',
                    'hint' => Context::getContext()->getTranslator()->trans('Your internal name for this attribute.', array(), 'Admin.Catalog.Help')
                ),
                array(
                    'type' => 'select',
                    'label' => Context::getContext()->getTranslator()->trans('Hook Position', array(), 'Admin.Catalog.Feature'),
                    'name' => 'hook_position',
                    'required' => true,
                    'options' => array(
                        'query' => $listHookModules,
                        'id' => 'id',
                        'name' => 'name'
                    ),
                    'col' => '2',
                    'hint' => Context::getContext()->getTranslator()->trans('The way the attribute\'s values will be presented to the customers in the product\'s page.', array(), 'Admin.Catalog.Help')
                ),
                array(
                    'type' => 'radio',
                    'label' => Context::getContext()->getTranslator()->trans('Show/hide title', array(), 'Admin.Global'),
                    'name' => 'active',
                    'required' => true,
                    'values' => array(
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->trans('Disabled', array(), 'Admin.Global')
                        ),
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->trans('Enabled', array(), 'Admin.Global')
                        )
                    )
                ),
                array(
                    'type' => 'radio',
                    'label' => Context::getContext()->getTranslator()->trans('Show/hide Hook', array(), 'Admin.Global'),
                    'name' => 'showhook',
                    'required' => true,
                    'values' => array(
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->trans('Disabled', array(), 'Admin.Global')
                        ),
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->trans('Enabled', array(), 'Admin.Global')
                        )
                    )
                ),
                array(
                    'type' => 'textarea',
                    'label' => Context::getContext()->getTranslator()->trans('Description', array(), 'Modules.Dor_managerblockfooter'),
                    'name' => 'description',
                    'autoload_rte' => TRUE,
                    'lang' => true,
                    'required' => TRUE,
                    'rows' => 5,
                    'cols' => 40,
                    'hint' => Context::getContext()->getTranslator()->trans('Invalid characters:', array(), 'Modules.Dor_managerblockfooter') . ' <>;=#{}'
                ),
                array(
                    'type' => 'text',
                    'label' => Context::getContext()->getTranslator()->trans('Order Position', array(), 'Admin.Global'),
                    'name' => 'order',
                    'col' => '4'
                ),
                array(
                    'type' => 'radio',
                    'label' => Context::getContext()->getTranslator()->trans('Insert Module?', array(), 'Admin.Global'),
                    'name' => 'insert_module',
                    'required' => true,
                    'values' => array(
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->trans('Disabled', array(), 'Admin.Global')
                        ),
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->trans('Enabled', array(), 'Admin.Global')
                        )
                    )
                ),
                array(
                    'type' => 'select',
                    'label' => Context::getContext()->getTranslator()->trans('Select Module', array(), 'Admin.Global'),
                    'name' => 'name_module',
                    'required' => true,
                    'options' => array(
                        'query' => $listModules,
                        'id' => 'name',
                        'name' => 'name'
                    ),
                    'desc' => $this->l('Choose the type of the Module')
                ),
                array(
                    'type' => 'select',
                    'label' => Context::getContext()->getTranslator()->trans('Hook-Modules', array(), 'Admin.Global'),
                    'name' => 'hook_module',
                    'required' => true,
                    'options' => array(
                        'query' => $listHookFooterModules,
                        'id' => 'hook_position',
                        'name' => 'hook_position'
                    ),
                    'desc' => $this->l('Choose the type of the Hooks')
                )
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
