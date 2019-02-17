<?php

class dor_smartuser extends Module {
	
	public function __construct() {
		$this->name 		= 'dor_smartuser';
		$this->tab 			= 'front_office_features';
		$this->version 		= '2.0.0';
		$this->author 		= 'Dorado Themes';
		$this->displayName 	= $this->l('Dor Smart User');
		$this->description 	= $this->l('Dor Smart User Popup Login/Register');
        
		parent :: __construct();
       
	}
	
	public function install() {
		return parent :: install()
            && $this->registerHook('dorSmartuser')
            && $this->registerHook('displayNav')
            && $this->registerHook('header')
            && $this->registerHook('footer')
            ;
	}

  
	public function psversion() {
		$version=_PS_VERSION_;
		$exp=$explode=explode(".",$version);
		return $exp[1];
	}
    
    
    public function hookHeader($params){
        if ($this->psversion()==5){
            $this->context->controller->addJS(($this->_path).'smartuser.js','all');
            $this->context->controller->addJS(($this->_path).'jquery.bpopup.min.js','all');
        } else {
            $this->context->controller->addCSS(($this->_path).'css/smartuser.css');
            $this->context->controller->addJS(($this->_path).'js/jquery.bpopup.min.js');
            $this->context->controller->addJS(($this->_path).'js/smartuser.js');
        }
    }
    
    
    // Hook dor Smart User
	public function hookdorSmartuser($params) {
		return $this->hookFooter($params);
	}   
	// Hook Footer
	public function hookFooter($params) {
		if (!$this->active)
			return;

		$this->smarty->assign(array(
			'cart' => $this->context->cart,
			'cart_qties' => $this->context->cart->nbProducts(),
			'logged' => $this->context->customer->isLogged(),
			'customerName' => ($this->context->customer->logged ? $this->context->customer->firstname.' '.$this->context->customer->lastname : false),
			'firstName' => ($this->context->customer->logged ? $this->context->customer->firstname : false),
			'lastName' => ($this->context->customer->logged ? $this->context->customer->lastname : false),
			'order_process' => Configuration::get('PS_ORDER_PROCESS_TYPE') ? 'order-opc' : 'order'
		));
		return $this->display(__FILE__, 'dor_smartuser.tpl');
	}  
	public function hookDisplayNav($params)
	{
		return $this->display(__FILE__, 'dor_smartuser_button.tpl');
	}    
	
}