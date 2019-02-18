<?php
class AdminDorVerticalMegamenuController extends ModuleAdminController {
    public function __construct() {
		$url  = 'index.php?controller=AdminModules&configure=dorverticalmenu';
		$url .= '&token='.Tools::getAdminTokenLite('AdminModules');
		Tools::redirectAdmin($url);
        parent::__construct();
    }
}
