<?php
/*
* 2007-2014 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2014 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/
if (!defined('_PS_VERSION_'))
	exit;
class Dor_blockcustoms extends Module
{
	private $spacer_size = '1';
	public function __construct()
	{
		$this->name = 'dor_blockcustoms';
		$this->tab = 'front_office_features';
		$this->version = 2.0;
		$this->author = 'Dorado Themes';
		$this->need_instance = 0;
		$this->bootstrap =true ;
		parent::__construct();
		$this->displayName = $this->l('Dor Block Custom');
		$this->description = $this->l('Block Custom File HTML');
		$this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
	}
	public function install()
	{
		if (!parent::install() || !$this->registerHook('dorBlockCustom1') || !$this->registerHook('dorBlockCustom2') || !$this->registerHook('dorBlockCustom3') || !$this->registerHook('dorBlockCustom4') || !$this->registerHook('dorBlockCustom5'))
			return false;
		return true;
	}
	public function uninstall(){
		return parent::uninstall();
	}
	public function hookHeader($params){}
	public function hookDorBlockCustom1($params)
	{
		$this->context->smarty->assign(array(
			'average_total' => 1
		));
		return $this->display(__FILE__, '/dorCustomNewsletter.tpl');
	}
	public function hookDorBlockCustom2($params)
	{
		$this->context->smarty->assign(array(
			'average_total' => 1
		));
		return $this->display(__FILE__, '/dorBoxSteps.tpl');
	}
	public function hookDorBlockCustom3($params)
	{
		$this->context->smarty->assign(array(
			'average_total' => 1
		));
		return $this->display(__FILE__, '/dorBoxAbout.tpl');
	}

	public function hookDorBlockCustom4($params)
	{
		$this->context->smarty->assign(array(
			'average_total' => 1
		));
		return $this->display(__FILE__, '/dorBoxService.tpl');
	}
	public function hookDorBlockCustom5($params)
	{
		$this->context->smarty->assign(array(
			'average_total' => 1
		));
		return $this->display(__FILE__, '/dorBlockCustom5.tpl');
	}

	private function _postProcess() {
        $this->_html .= $this->displayConfirmation($this->l('Configuration updated'));
    }
	public function getContent() {
    }
	public  function _displayForm() {
		
    }
    public function getConfigFieldsValues()
    {
        
    }
}

