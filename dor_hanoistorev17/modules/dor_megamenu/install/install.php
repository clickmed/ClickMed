<?php
/**
 * Manager and display megamenu use bootstrap framework
 *
 * @package   dormegamenu
 * @version   1.0.0
 * @author    http://www.doradothemes@gmail.com
 * @copyright Copyright (C) December 2015 doradothemes@gmail.com <@emai:doradothemes@gmail.com>
 *               <info@doradothemes@gmail.com>.All rights reserved.
 * @license   GNU General Public License version 2
 */

if (!defined('_PS_VERSION_'))
	exit;

$path = dirname(_PS_ADMIN_DIR_);
include_once($path.'/config/config.inc.php');
include_once($path.'/init.php');
$res = (bool)Db::getInstance()->execute('
	CREATE TABLE `'._DB_PREFIX_.'dormegamenu` (
	  `id_dormegamenu` int(11) NOT NULL AUTO_INCREMENT,
	  `active` tinyint(1) DEFAULT NULL,
	  `type` varchar(25) DEFAULT NULL,
	  `value` varchar(25) DEFAULT NULL,
	  `id_parent` int(11) DEFAULT NULL,
	  `position` int(11) DEFAULT NULL,
	  `level_depth` int(11) DEFAULT NULL,
	  `date_add` datetime DEFAULT NULL,
	  `date_upd` datetime DEFAULT NULL,
	  `params` text,
	  PRIMARY KEY (`id_dormegamenu`)
	) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;
');
$res &= (bool)Db::getInstance()->execute('
	CREATE TABLE `'._DB_PREFIX_.'dormegamenu_lang` (
	  `id_dormegamenu` int(11) NOT NULL DEFAULT \'0\',
	  `id_lang` int(11) NOT NULL DEFAULT \'0\',
	  `name` varchar(500) DEFAULT NULL,
	  `description` varchar(500) DEFAULT NULL,
	  `url` varchar(500) DEFAULT NULL,
	  PRIMARY KEY (`id_dormegamenu`,`id_lang`)
	) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;
');

$res &= (bool)Db::getInstance()->execute('
	CREATE TABLE `'._DB_PREFIX_.'dormegamenu_shop` (
	  `id_dormegamenu` int(11) NOT NULL DEFAULT \'0\',
	  `id_shop` int(11) NOT NULL DEFAULT \'0\',
	  PRIMARY KEY (`id_dormegamenu`,`id_shop`)
	) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;
');

$res &= (bool)Db::getInstance()->execute('
	CREATE TABLE `'._DB_PREFIX_.'dormegamenu_widget` (
	  `id_dormegamenu_widget` int(11) NOT NULL AUTO_INCREMENT,
	  `name` varchar(255) DEFAULT NULL,
	  `type` varchar(255) DEFAULT NULL,
	  `params` text,
	  `id_shop` int(11) DEFAULT NULL,
	  `wkey` varchar(255) DEFAULT NULL,
	  PRIMARY KEY (`id_dormegamenu_widget`)
	) ENGINE='._MYSQL_ENGINE_.' AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
');

/* install sample data */
$rows = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('SELECT id_dormegamenu FROM `'._DB_PREFIX_.'dormegamenu`');
if ( count($rows) == 0 ) {
	$res &=  (bool)Db::getInstance()->execute('
		INSERT INTO `'._DB_PREFIX_.'dormegamenu` VALUES (\'1\', \'1\', \'category\', \'1\', null, null, null, null, null, null);
	');

	$res &= (bool)Db::getInstance()->execute('
		INSERT INTO `'._DB_PREFIX_.'dormegamenu_lang` VALUES (\'1\', \'1\', \'Root\', null, null);
	');

	$res &= (bool)Db::getInstance()->execute('
		INSERT INTO `'._DB_PREFIX_.'dormegamenu_shop` VALUES (\'1\', \'1\');
	');
}
