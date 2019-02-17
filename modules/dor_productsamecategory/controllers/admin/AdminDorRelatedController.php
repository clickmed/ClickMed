<?php
class AdminDorRelatedController extends ModuleAdminController {
    public function __construct() {
		$url  = 'index.php?controller=AdminModules&configure=dor_productsamecategory';
		$url .= '&token='.Tools::getAdminTokenLite('AdminModules');
		Tools::redirectAdmin($url);
        parent::__construct();
    }
}
