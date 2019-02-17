<?php
/**
 * Manager and display dorverticalmenu use bootstrap framework
 *
 * @package   dorverticalmenu
 * @version   1.0.0
 * @author    http://www.doradothemes@gmail.com
 * @copyright Copyright (C) December 2015 doradothemes@gmail.com <@emai:doradothemes@gmail.com>
 *               <info@doradothemes@gmail.com>.All rights reserved.
 * @license   GNU General Public License version 2
 */

class DorVerticalmenuMcrypt {

	protected $mcrypt;

	public function __construct()
	{
		//$this->mcrypt = new Rijndael(_DORMEGAMENU_MCRYPT_KEY_, _DORMEGAMENU_MCRYPT_IV_);
		//$this->mcrypt = "";
	}

	public function encode($text)
	{
		//return $this->mcrypt->encrypt($text);
		return $text;
	}

	public function decode($text)
	{
		//return $this->mcrypt->decrypt($text);
		return $text;
	}

}
