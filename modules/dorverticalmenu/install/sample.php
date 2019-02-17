<?php
/**
 * Manager and display megamenu use bootstrap framework
 *
 * @package   dorverticalmenu
 * @version   1.0.0
 * @author    http://www.doradothemes@gmail.com
 * @copyright Copyright (C) December 2015 doradothemes@gmail.com <@emai:doradothemes@gmail.com>
 *               <info@doradothemes@gmail.com>.All rights reserved.
 * @license   GNU General Public License version 2
 */

(bool)Db::getInstance()->execute('
	INSERT INTO `'._DB_PREFIX_.'dorverticalmenu` VALUES (\'1\', \'1\', \'category\', \'1\', null, null, null, null, null, null);
');

(bool)Db::getInstance()->execute('
	INSERT INTO `'._DB_PREFIX_.'dorverticalmenu_lang` VALUES (\'1\', \'1\', \'Root\', null, null);
');

(bool)Db::getInstance()->execute('
	INSERT INTO `'._DB_PREFIX_.'dorverticalmenu_shop` VALUES (\'1\', \'1\');
');