<?php
class AdminDorTabCategoryController extends ModuleAdminController {
    public function __construct() {
		$url  = 'index.php?controller=AdminModules&configure=dor_ajaxtabproductcategory';
		$url .= '&token='.Tools::getAdminTokenLite('AdminModules');
		Tools::redirectAdmin($url);
        parent::__construct();
    }
}
