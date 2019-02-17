<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_2_0_1($object)
{
		$smartbloghomelatestnews = new smartbloghomelatestnews();
		$smartbloghomelatestnews->registerHook('actionsbdeletepost');
		$smartbloghomelatestnews->registerHook('actionsbnewpost');
		$smartbloghomelatestnews->registerHook('actionsbupdatepost');
		$smartbloghomelatestnews->registerHook('actionsbtogglepost');
		return true;
}
