<?php
include_once(_PS_MODULE_DIR_.'dor_testimonials/classes/DorTestimonial.php');
include_once(_PS_MODULE_DIR_.'dor_testimonials/dor_testimonials.php');
include_once(_PS_MODULE_DIR_.'dor_testimonials/classes/DorFileUploader.php');
include_once(_PS_MODULE_DIR_.'dor_testimonials/libs/Params.php');
class AdminTestimonialsController extends AdminController {
	public $bootstrap = true;
    public $ssl = true;
	protected $position_identifier = 'id_dortestimonial';
	public function __construct(){
		$this->table = 'dortestimonial';
		$this->className = 'DorTestimonial';
		$this->name ='dor_testimonials';
		$this->lang = false;
		$this->deleted = false;
		$this->context = Context::getContext();
		$this->_defaultOrderBy = 'position';
		$this->bulk_actions = array('delete' => array('text' => Context::getContext()->getTranslator()->trans('Delete selected', array(), 'Modules.Dor_managerblockfooter'), 'confirm' => Context::getContext()->getTranslator()->trans('Delete selected items?', array(), 'Modules.Dor_managerblockfooter')));
        Shop::addTableAssociation($this->table, array('type' => 'shop'));
        $this->context = Context::getContext();
		$this->fields_list = array(
            'id_dortestimonial'=> array('title' => Context::getContext()->getTranslator()->trans('ID', array(), 'Modules.dor_testimonials'), 'width' => 20),
			'rating'=> array('title' => Context::getContext()->getTranslator()->trans('Rating', array(), 'Modules.dor_testimonials'), 'width' => 20),
            'name_post' => array('title' => Context::getContext()->getTranslator()->trans('Name', array(), 'Modules.dor_testimonials'), 'width' => 'auto',),
			'title_post' => array('title' => Context::getContext()->getTranslator()->trans('Title', array(), 'Modules.dor_testimonials'), 'width' => 'auto',),
			'email' => array('title' => Context::getContext()->getTranslator()->trans('Email', array(), 'Modules.dor_testimonials'), 'width' => 'auto',),
			'company' => array('title' => Context::getContext()->getTranslator()->trans('Company', array(), 'Modules.dor_testimonials'), 'width' => 'auto',),
			'address' => array('title' => Context::getContext()->getTranslator()->trans('Address', array(), 'Modules.dor_testimonials'), 'width' => 'auto',),
			'media_type'=> array('title' => Context::getContext()->getTranslator()->trans('Media type', array(), 'Modules.dor_testimonials'), 'width' => 'auto',),
			'position'=> array('title' => Context::getContext()->getTranslator()->trans('Position', array(), 'Modules.dor_testimonials'),'filter_key' => 'a!position','align' => 'center','position' => 'position' ),
			'date_add'=> array('title' => Context::getContext()->getTranslator()->trans('Date add', array(), 'Modules.dor_testimonials'), 'width' => 'auto','class'=> 'fixed-width-xs'),
			'active'=> array('title' => Context::getContext()->getTranslator()->trans('Active', array(), 'Modules.dor_testimonials'),'align' => 'center','active' => 'status','type' => 'bool','orderby' => false,),
		);
		parent::__construct();
		$this->_defaultFormLanguage = (int)Configuration::get('PS_LANG_DEFAULT');
	}
    public function renderList()
    {
        $this->addRowAction('view');
        $this->addRowAction('edit');
        $this->addRowAction('delete');
        return parent::renderList();
    }
	public function initToolbar(){
		if (empty($this->display)){
			$this->toolbar_btn['new'] = array(
				'href' => self::$currentIndex.'&add'.$this->table.'&token='.$this->token,
				'desc' => Context::getContext()->getTranslator()->trans('Add New', array(), 'Modules.dor_testimonials')
			);
			$this->toolbar_btn['edit'] = array(
				'href' => 'index.php?controller=AdminModules&token='.Tools::getAdminTokenLite('AdminModules') .'&configure=dortestimonials&tab_module=others&module_name=dor_testimonials',
				'desc' => Context::getContext()->getTranslator()->trans('Configurations and Custom Field', array(), 'Modules.dor_testimonials')
			);
		}
	}
	public function postProcess(){
		$obj = $this->loadObject(true);
		if (Tools::isSubmit('forcedeleteImage')|| Tools::getValue('deleteImage'))
		{
			$this->processForceDeleteImage();
            if (Tools::isSubmit('forcedeleteImage'))
                Tools::redirectAdmin(self::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminTestimonial'));
		}
		if(Tools::getValue('media_link')){
			$link =explode('/',Tools::getValue('media_link'));
			$link_id = new DorTestimonial();
			if($link[2] =='www.youtube.com' || $link[2]=='vimeo.com'){
                $_POST['media_type'] =$link_id->getTypevideo(Tools::getValue('media_link'));
                $_POST['media_link_id'] = $link_id->getIdFromLinkInput(Tools::getValue('media_link'));
            }
            else {
                $this->errors[] ='Media link require link youtube or vimeo';
            }
		}
		if(isset ($_FILES['media']['name']) && ($_FILES['media']['name'])!= null ){
			$upload = new DorFileUploader($this->module, $_FILES['media']);
			$res = $upload->handleUpload();
			if(!empty($upload->errors)){
				if(is_array($upload->errors))
					$this->errors = array_merge($this->errors, $upload->errors);
				else
					$this->errors[] = $upload->errors;
            }
		}
		if(!count($this->errors)){
		if(isset($res)){
			$_POST['media'] = $res['name'];
			$_POST['media_type'] = $res['type'] ;
			}
		}
			$return = parent::postProcess();
		return $return;
	}

	public function processForceDeleteImage()
	{
		$obj = $this->loadObject(true);
		if (Validate::isLoadedObject($obj))
		$update = new DorTestimonial($obj->id);
		$update->media = null;
		$update->media_type = null;
		$obj->deleteImage();
		$update->update();
	}
	/////position////////////////////////
	public function ajaxProcessUpdatePositions()
	{
		$way = (int)(Tools::getValue('way'));
		$id_dortestimonial = (int)(Tools::getValue('id'));
		$positions = Tools::getValue($this->table);
        foreach ($positions as $position => $value){
            $pos = explode('_', $value);
            if (isset($pos[2]) && (int)$pos[2] === $id_dortestimonial){
                if ($dortestimonial = new DorTestimonial((int)$pos[2])){
                    if (isset($position) && $dortestimonial->updatePosition($way, $position))
                        echo 'ok position '.(int)$position.' for carrier '.(int)$pos[1].'\r\n';
                    else
                        echo '{"hasError" : true, "errors" : "Can not update carrier '.(int)$id_dortestimonial.' to position '.(int)$position.' "}';
                }else
                    echo '{"hasError" : true, "errors" : "This carrier ('.(int)$id_dortestimonial.') can t be loaded"}';
                break;
            }
        }
	}

	public function processPosition(){
        if ($this->tabAccess['edit'] !== '1')
            $this->errors[] = Tools::displayError('You do not have permission to edit this.');
        else if (!Validate::isLoadedObject($object = new DorTestimonial((int)Tools::getValue($this->identifier, Tools::getValue('id_dortestimonial', 1)))))
            $this->errors[] = Tools::displayError('An error occurred while updating the status for an object.').' <b>'.
            $this->table.'</b> '.Tools::displayError('(cannot load object)');
        if (!$object->updatePosition((int)Tools::getValue('way'), (int)Tools::getValue('position')))
            $this->errors[] = Tools::displayError('Failed to update the position.');
        else{
            Tools::redirectAdmin(self::$currentIndex.'&'.$this->table.'Orderby=position&'.$this->table.'Orderway=asc&conf=5'.'&token='.Tools::getAdminTokenLite('AdminTestimonial'));
        }
	}
	////////////////////////////
	public function renderForm($isMainTab = true) {
		global $currentIndex;
		$obj = $this->loadObject(true);
		$this->context->controller->addJS(_MODULE_DIR_.$this->name.'/assets/admin/admin_testimonial.js');
        $media_desc = '';
		$this->context->smarty->assign('media_desc',$media_desc);
		$this->fields_form = array(
			'legend' => array(
				'title' => Context::getContext()->getTranslator()->trans('Submit and Manage Testimonial', array(), 'Modules.dor_testimonials'),
				'image' => '../img/admin/quick.gif'
			),
			'input' => array(
				array(
                    'type' => 'text',
                    'label'=> Context::getContext()->getTranslator()->trans('Name:', array(), 'Modules.dor_testimonials'),
                    'name' => 'name_post',
                    'lang' => false,
                    'required' => true,
                    'hint' => Context::getContext()->getTranslator()->trans('Invalid characters:', array(), 'Modules.dor_testimonials').' <>;=#{}',
                    'desc' => Context::getContext()->getTranslator()->trans('This field is one person name. example: Peter, Marry...', array(), 'Modules.dor_testimonials'),
                    'size' => 40
                ),
                array(
                    'type' => 'text',
                    'label'=> Context::getContext()->getTranslator()->trans('Email:', array(), 'Modules.dor_testimonials'),
                    'name' => 'email',
                    'lang' => false,
                    'required' => true,
                    'class'=> '',
                    'hint' => Context::getContext()->getTranslator()->trans('Invalid characters:', array(), 'Modules.dor_testimonials').' <>;=#{}',
                    'desc' => Context::getContext()->getTranslator()->trans('This field is an email. ex: peter123@gmail.com.', array(), 'Modules.dor_testimonials'),
                    'size' => 20
                ),
                array(
                    'type' => 'text',
                    'label'=> Context::getContext()->getTranslator()->trans('Company:', array(), 'Modules.dor_testimonials'),
                    'name' => 'company',
                    'lang' => false,
                    'required' => false,
                    'class'=> '',
                    'hint' => Context::getContext()->getTranslator()->trans('Invalid characters:', array(), 'Modules.dor_testimonials').' <>;=#{}',
                    'desc' => Context::getContext()->getTranslator()->trans('Your company name enter here.', array(), 'Modules.dor_testimonials'),
                    'size' => 40
                ),
                array(
                    'type' => 'text',
                    'label'=> Context::getContext()->getTranslator()->trans('Address:', array(), 'Modules.dor_testimonials'),
                    'name' => 'address',
                    'lang' => false,
                    'required' => true,
                    'class'=> '',
                    'hint' => Context::getContext()->getTranslator()->trans('Invalid characters:', array(), 'Modules.dor_testimonials').' <>;=#{}',
                    'desc' => Context::getContext()->getTranslator()->trans('Maybe your Home address or Company address.', array(), 'Modules.dor_testimonials'),
                    'size' => 40
                ),
                array(
                    'type' => 'text',
                    'label'=> Context::getContext()->getTranslator()->trans('Media link:', array(), 'Modules.dor_testimonials'),
                    'name' => 'media_link',
                    'lang' => false,
                    'required' => false,
                    'class'=> '',
                    'hint' => Context::getContext()->getTranslator()->trans('Invalid characters:', array(), 'Modules.dor_testimonials').' <>;=#{}',
                    'desc' => Context::getContext()->getTranslator()->trans('Media link should be one Youtube link.', array(), 'Modules.dor_testimonials'),
                    'size' => 40
                ),
                array(
                    'type' => 'file',
                    'label'=> Context::getContext()->getTranslator()->trans('Media posted:', array(), 'Modules.dor_testimonials'),
                    'id' => 'media',
                    'name' => 'media',
                    'display_image' => true,
                    'delete_url' => self::$currentIndex.'&'.$this->identifier .'='.$obj->id.'&token='.$this->token.'&deleteImage=1',
                    'image' => $media_desc ? $media_desc: false,
                    'size' => 100
                ),
                array(
                    'type' => 'text',
                    'label'=> Context::getContext()->getTranslator()->trans('Title:', array(), 'Modules.dor_testimonials'),
                    'name' => 'title_post',
                    'required' => true,
                    'hint' => Context::getContext()->getTranslator()->trans('Invalid characters:', array(), 'Modules.dor_testimonials').' <>;=#{}',
                    'desc' => Context::getContext()->getTranslator()->trans('This field is one title post', array(), 'Modules.dor_testimonials'),
                    'size' => 40
                ),
                array(
                    'type' => 'textarea',
                    'label'=> Context::getContext()->getTranslator()->trans("Content:", array(), 'Modules.dor_testimonials'),
                    'name' => 'content',
                    'id' => 'content_area',
                    'required' => true,
                    'hint' => Context::getContext()->getTranslator()->trans('Invalid characters:', array(), 'Modules.dor_testimonials').' <>;=#{}',
                    'rows' => 3,
                    'col' => 8,
                ),
                array(
                    'type' => 'text',
                    'label'=> Context::getContext()->getTranslator()->trans('Rating:', array(), 'Modules.dor_testimonials'),
                    'name' => 'rating',
                    'required' => false,
                    'desc' => Context::getContext()->getTranslator()->trans('This field is one rating post width max rating = 5', array(), 'Modules.dor_testimonials'),
                    'size' => 40
                ),
                array(
                    'type' => 'switch',
                    'label' => Context::getContext()->getTranslator()->trans('Active:', array(), 'Modules.dor_testimonials'),
                    'name' => 'active',
                    'required' => false,
                    'class' => 't',
                    'is_bool' => true,
                    'values' => array(
                    array(
                        'id' => 'active_on',
                        'value' => 1,
                        'label' => Context::getContext()->getTranslator()->trans('Enabled', array(), 'Modules.dor_testimonials')
                    ),
                    array(
                        'id' => 'active_off',
                        'value' => 0,
                        'label' => Context::getContext()->getTranslator()->trans('Disabled', array(), 'Modules.dor_testimonials')
                        )
                    )
                )
            ),
			'submit' => array(
                'title' => Context::getContext()->getTranslator()->trans('Save', array(), 'Admin.Global'),
            )
        );
        
		if (Shop::isFeatureActive()) {
            $this->fields_form['input'][] = array(
                'type' => 'shop',
                'label' => Context::getContext()->getTranslator()->trans('Shop association', array(), 'Admin.Global'),
                'name' => 'checkBoxShopAsso',
            );
        }
        
        if (!($obj = $this->loadObject(true))) {
            return;
        }

        return parent::renderForm();
	}
}
