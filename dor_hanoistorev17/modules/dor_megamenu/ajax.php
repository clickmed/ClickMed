<?php

include_once('../../config/config.inc.php');
include_once('../../init.php');
include_once('dor_megamenu.php');

$obj_menu = new Dor_MegaMenu();

if (!Tools::isSubmit('secure_key') || Tools::getValue('secure_key') != $obj_menu->secure_key || !Tools::getValue('action'))
	die(1);

if (Tools::getValue('action') == 'addMenu')
{
	$values = Tools::getValue('values');
	$values = explode(',', $values);
	$result = false;
	$type = Tools::getValue('type');
	if ($type == 'product') {
		foreach ($values as $id_content) {
			if ( !empty($id_content) && Validate::isInt($id_content) ) {
				$obj = new ObjectDorMegamenu();

				$obj->id_parent = 1;
				$obj->value = (int)$id_content;
				$obj->type = (string)$type;
				$obj->active = 1;
				$obj->name = DorMegamenuHelper::getLangValueByType($id_content, $type);

				if ($obj->add()) {
					$result = true;
				}
			}
		}
	} elseif ($type == 'custom-link') {
		$obj = new ObjectDorMegamenu();

		$obj->id_parent = 1;
		$obj->value = '';
		$obj->type = (string)$type;
		$obj->active = 1;
		if ($type == 'custom-link') {
			$urls = array();
			$names = array();
			$langs = Language::getLanguages(false);
			foreach ($langs as $lang) {
				$urls[$lang['id_lang']] = (string)Tools::getValue('custom_link');
				$names[$lang['id_lang']] = (string)Tools::getValue('custom_name');
			}
			$obj->url = $urls;
			$obj->name = $names;
		}
		if ($obj->add()) {
			$result = true;
		}
	} else {
		foreach ($values as $id_content) {
			if ( !empty($id_content) ) {
				$obj = new ObjectDorMegamenu();
				$obj->id_parent = 1;
				$obj->value = (int)$id_content;
				$obj->type = (string)$type;
				$obj->active = 1;
				$obj->name = DorMegamenuHelper::getLangValueByType($id_content, $type);

				if ($type == 'custom-link') {
					$urls = array();
					$langs = Language::getLanguages(false);
					foreach ($langs as $lang) {
						$urls[$lang['id_lang']] = (string)Tools::getValue('custom_link');
					}
					$obj->url = $urls;
				}

				if ($obj->add()) {
					$result = true;
				}
			}
		}
	}

	$response = array();
	if ($result) {
		$response['status'] = 'ok';
		$response['html'] = $obj_menu->listMenu();
		$response['msg'] = $obj_menu->l('Add menu success', 'ajax');
	} else {
		$response['status'] = 'error';
		$response['msg'] = $obj_menu->l('Add menu error, please try again', 'ajax');
	}

	echo Tools::jsonEncode($response);

} elseif (Tools::getValue('action') == 'updatePosition') {
	$list = Tools::getValue('list');
	$root = 1;
	$child = array();
	foreach ($list as $id => $parent_id)
	{
		if ($parent_id <= 0)
			$parent_id = $root;
		$child[$parent_id][] = $id;
	}
	$res = true;
	foreach ($child as $id_parent => $menus)
	{
		$i = 0;
		foreach ($menus as $id_dormegamenu)
		{
			$res &= Db::getInstance()->execute('
                UPDATE `'._DB_PREFIX_.'dormegamenu` SET `position` = '.(int)$i.', id_parent = '.(int)$id_parent.' 
                WHERE `id_dormegamenu` = '.(int)$id_dormegamenu
			);
			$i++;
		}
	}

	//$obj_menu->clearCache();
	$response['status'] = 'success';
	$response['msg'] = $obj_menu->l('Update Postion Successfull', 'ajax');
	if (!$res) {
		$response['status'] = 'error';
		$response['msg'] = $obj_menu->l('Update Postion Error', 'ajax');
	}

	echo Tools::jsonEncode($response);

} elseif (Tools::getValue('action') == 'editSubMenu' && Tools::getValue('id_menu')) {
	echo $obj_menu->getSubmenuSettingForm( (int)Tools::getValue('id_menu') );

} elseif (Tools::getValue('action') == 'updateMenu' && Tools::getValue('id_dormegamenu') ) {
	$obj = new ObjectDorMegamenu((int)Tools::getValue('id_dormegamenu'));
	$result = false;
	if (Validate::isLoadedObject($obj)) {
		$obj->copyFromPost();
		if ($obj->update()) {
			$result = true;
		}
	}

	$response = array();
	if ($result) {
		$response['status'] = 'ok';
		$response['html'] = $obj_menu->listMenu();
		$response['msg'] = $obj_menu->l('Update menu success', 'ajax');
	} else {
		$response['status'] = 'error';
		$response['msg'] = $obj_menu->l('Update menu error, please try again', 'ajax');
	}

	echo Tools::jsonEncode($response);
} elseif (Tools::getValue('action') == 'deleteMenu' && Tools::getValue('id_menu')) {
	$obj = new ObjectDorMegamenu((int)Tools::getValue('id_menu'));
	$result = false;
	if (Validate::isLoadedObject($obj)) {
		if ($obj->delete()) {
			$result = true;
		}
	}

	$response = array();
	if ($result) {
		$response['status'] = 'ok';
		$response['html'] = $obj_menu->listMenu();
		$response['msg'] = $obj_menu->l('Delete menu success', 'ajax');
	} else {
		$response['status'] = 'error';
		$response['msg'] = $obj_menu->l('Delete menu error, please try again', 'ajax');
	}
	echo Tools::jsonEncode($response);
} elseif (Tools::getValue('action') == 'listWidget') {
	echo $obj_menu->getListWidgets();
} elseif (Tools::getValue('action') == 'addWidget') {
	if (Tools::getValue('id_dormegamenu_widget')) {
		$obj = new DorMegamenuWidget((int)Tools::getValue('id_dormegamenu_widget'));
	} else {
		$obj = new DorMegamenuWidget();
	}
	$obj->copyFromPost();
	$obj->params = Tools::getAllValues();

	if (Tools::getValue('id_dormegamenu_widget')) {
		$result = $obj->update();
	} else {
		$result = $obj->add();
	}

	$response = array();
	if ($result) {
		$response['status'] = 'ok';
		$response['html'] = DorMegamenuWidget::renderAdminListWidgets();
		$response['msg'] = $obj_menu->l('Save widget success', 'ajax');
	} else {
		$response['status'] = 'error';
		$response['msg'] = $obj_menu->l('Save widget error, please try again', 'ajax');
	}

	echo Tools::jsonEncode($response);
} elseif (Tools::getValue('action') == 'deleteWidget') {
	$obj = new DorMegamenuWidget((int)Tools::getValue('id'));
	$result = false;
	if (Validate::isLoadedObject($obj)) {
		if ($obj->delete()) {
			$result = true;
		}
	}

	$response = array();
	if ($result) {
		$response['status'] = 'ok'; 
		$response['html'] = DorMegamenuWidget::renderAdminListWidgets();
		$response['msg'] = $obj_menu->l('Delete widget success', 'ajax');
	} else {
		$response['status'] = 'error'; 
		$response['msg'] = $obj_menu->l('Delete widget error, please try again', 'ajax');
	}
	echo Tools::jsonEncode($response);
} elseif (Tools::getValue('action') == 'updateSubMenu') {
	$id = (int)Tools::getValue('id_dormegamenu');
	$obj = new ObjectDorMegamenu($id);
	$result = true;
	if (Validate::isLoadedObject($obj)) {
		$mscript = new DorMegamenuMcrypt();
		$data = $mscript->encode(serialize(Tools::getAllValues()));
		$obj->params = $data;
		$result = $obj->update();
	}
	

	if ($result) {
		$response['status'] = 'ok';
		$response['msg'] = $obj_menu->l('Save submenu configuration success', 'ajax');
	} else {
		$response['status'] = 'error';
		$response['msg'] = $obj_menu->l('Save submenu configuration error, please try again', 'ajax');
	}

	echo Tools::jsonEncode($response);
}