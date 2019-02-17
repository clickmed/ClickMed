<?php
if (!defined('_CAN_LOAD_FILES_') AND _PS_VERSION_ > '1.5')
	exit;
class DorTestimonial extends ObjectModel{
	public $id_dortestimonial;
	public $name_post;
	public $email;
	public $company;
	public $address;
	public $media_link_id;
	public $media;
	public $media_type;
	public $title_post;
	public $content;
	public $date_add;
	public $position;
	public $rating;
	public $active = 1;
	public static $definition = array(
        'table' => 'dortestimonial',
        'primary' => 'id_dortestimonial',
        'multilang' => false,
        'multishop' => true,
        'fields' => array(
            'name_post' => array('type' => self::TYPE_STRING, 'validate'=> 'isGenericName', 'required' => true, 'size' => 100),
            'title_post' => array('type' => self::TYPE_STRING, 'validate'=> 'isGenericName', 'required' => true, 'size' => 100),
            'email' => array('type' => self::TYPE_STRING, 'validate'=> 'isEmail', 'required' => true, 'size' => 100),
            'company' => array('type' => self::TYPE_STRING, 'validate'=> 'isGenericName', 'required' => false, 'size' => 255),
            'address' => array('type' => self::TYPE_STRING, 'validate'=> 'isGenericName', 'required' => true, 'size' => 500),
            'media' => array('type' => self::TYPE_STRING, 'validate'=> 'isGenericName', 'required' => false, 'size' => 255),
            'media_type' => array('type' => self::TYPE_STRING, 'validate'=> 'isGenericName', 'required' => false, 'size' => 255),
            'content' => array('type' => self::TYPE_HTML, 'validate'=> 'isCleanHtml','required' => true),
            'date_add' => array('type' => self::TYPE_DATE, 'validate'=> 'isDate', 'required' => false),
            'position' => array('type' => self::TYPE_INT, 'validate'=> 'isInt', 'required' => false),
            'rating' => array('type' => self::TYPE_INT, 'validate'=> 'isInt', 'required' => false),
            'active' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => false),
        )
	);
	public function __construct($id = NULL, $id_lang = NULL){
		parent::__construct($id, $id_lang);
	}
    
	public function add($autodate = true, $null_values = false){
		$id_shop = Context::getContext()->shop->id;
		$res=true ;
		if ($this->position <= 0)
			$this->position = DorTestimonial::getHigherPosition() + 1;
			$res = parent::add($autodate , $null_values);
			$res &= Db::getInstance()->execute('
				INSERT INTO `'._DB_PREFIX_.'dortestimonial_shop`(`id_dortestimonial`,`id_shop`)
				VALUES('.(int)$this->id.','.(int)$id_shop.')');
		return $res;
	}
    
	public function update($null_values = false){
		$shop_ids = Tools::getValue('checkBoxShopAsso_dortestimonial');
		$res=true ;
		foreach ($shop_ids as $key => $idShop) {
			$id_shop = $this->checkUpdateExist($idShop,$this->id);
			if($id_shop){
				parent::update($null_values);
			}else{
				$res &= Db::getInstance()->execute('
					INSERT INTO `'._DB_PREFIX_.'dortestimonial_shop`(`id_dortestimonial`,`id_shop`)
					VALUES('.(int)$this->id.','.(int)$idShop.')');
			}
		}
		return $res;
	}
    public function checkUpdateExist($id_Shop, $idItem){
    	$sql = 'SELECT `id_shop` FROM `'._DB_PREFIX_.'dortestimonial_shop` WHERE id_shop='.$id_Shop.' AND id_dortestimonial='.$idItem;
		$idShop = DB::getInstance()->getValue($sql);
		return $idShop;
    }
	public function delete(){
		$res=true ;
		$res &= parent::delete();
		$res &= Db::getInstance()->execute('
			DELETE FROM `'._DB_PREFIX_.'dortestimonial_shop`
			WHERE `id_shop` = '.(int)$this->id
		);
		if($res){
			if(file_exists(_PS_IMG_DIR_.$this->media))
			@unlink(_PS_IMG_DIR_.'dor_testimonial/'.$this->media);
			return true;
		}
	}
    
	public function deleteImage($force_delete = false) {
		$res = parent::deleteImage($force_delete);
		if ($res) {
		if(file_exists(_PS_IMG_DIR_.'dor_testimonial/'.$this->media))
			@unlink(_PS_IMG_DIR_.'dor_testimonial/'.$this->media);
			return true;
		}
		return $res;
	}
	
	public static function getAllTestimonials($p = 1, $n = false, $id = false, $excpt_id = false){
		$context = Context::getContext();
		$id_shop = $context->shop->id;
		$sql= 'SELECT * FROM '._DB_PREFIX_.'dortestimonial lt ';
		$sql .=' INNER JOIN '._DB_PREFIX_.'dortestimonial_shop ls ON (lt.id_dortestimonial = ls.id_dortestimonial) ';
        $sql .=' WHERE lt.active = 1 AND ls.id_shop ='.$id_shop .( $id ? ' AND lt.id_dortestimonial ='.(int)$id :'').
        ( $excpt_id ? ' AND lt.id_dortestimonial != '.(int)$excpt_id :'');
        $sql .= ' ORDER BY lt.position ASC '.($n ? ' LIMIT '.($p - 1) *$n .','.(int)$n: '');
		$results = Db::getInstance()->executeS($sql);
		return $results;
	}
    
	public function updatePosition($way, $position){
		if (!$res = Db::getInstance()->executeS('
			SELECT `id_dortestimonial`, `position`
			FROM `'._DB_PREFIX_.'dortestimonial`
			ORDER BY `position` ASC'
		))
		return false;
		foreach ($res as $testimonial)
		if ((int)$testimonial['id_dortestimonial'] == (int)$this->id)
		$moved_testimonial = $testimonial;
		if (!isset($moved_testimonial) || !isset($position))
		return false;
		// < and > statements rather than BETWEEN operator
		// since BETWEEN is treated differently according to databases
		return (Db::getInstance()->execute('
			UPDATE `'._DB_PREFIX_.'dortestimonial`
			SET `position`= `position` '.($way ? '- 1' : '+ 1').'
			WHERE `position`
			'.($way
		? '> '.(int)$moved_testimonial['position'].' AND `position` <= '.(int)$position
		: '< '.(int)$moved_testimonial['position'].' AND `position` >= '.(int)$position.'
		'))
		&& Db::getInstance()->execute('
			UPDATE `'._DB_PREFIX_.'dortestimonial`
			SET `position` = '.(int)$position.'
			WHERE `id_dortestimonial` = '.(int)$moved_testimonial['id_dortestimonial']));
	}
	/**
	 * Reorders testimonialspositions.
	 * Called after deleting a carrier.
	 *
	 * @since 1.5.0
	 * @return bool $return
	 */
	public static function cleanPositions()
	{
		$return = true;
		$sql = '
			SELECT `id_dortestimonial`
			FROM `'._DB_PREFIX_.'dortestimonial`
			ORDER BY `position` ASC';
		$result = Db::getInstance()->executeS($sql);
		$i = 0;
		foreach ($result as $value)
		$return = Db::getInstance()->execute('
			UPDATE `'._DB_PREFIX_.'dortestimonial`
			SET `position` = '.(int)$i++.'
			WHERE `id_dortestimonial` = '.(int)$value['id_dortestimonial']);
		return $return;
	}
	
	/**
	 * Gets the highest testimonials position
	 *
	 * @since 1.5.0
	 * @return int $position
	 */
	public static function getHigherPosition()
	{
		$sql = 'SELECT MAX(`position`)FROM `'._DB_PREFIX_.'dortestimonial`';
		$position = DB::getInstance()->getValue($sql);
		return (is_numeric($position)) ? $position : -1;
	}
	
	public static function getTypevideo($link =null){
		$defaultLinkCheck = array(
			'youtube' => 'www.youtube.com',
			'vimeo' => 'vimeo.com'
		);
		$exLink = explode('/',$link);
		$typevideoLink = $exLink[2] ;
		if ($defaultLinkCheck['youtube'] == $typevideoLink){
			$typevideo = 'youtube' ;
		}else {
			$typevideo = 'vimeo';
		}
		return $typevideo ;
	}
    
	public static function getIdFromLinkInput($link = null){
		if(empty($link))
			return '';
        $defaultLinkCheck = array(
            'youtube' => 'www.youtube.com',
            'vimeo' => 'vimeo.com'
        );
        $exLink = explode('/',$link);
        $cmpYoutubeLink = @strcmp($exLink[2],$defaultLinkCheck['youtube']);
        $cmpVimeoLink = @strcmp($exLink[2],$defaultLinkCheck['vimeo']);
        if($cmpYoutubeLink == 0 AND !empty($exLink[3])){
            $youtube_id = explode('=',$exLink[3]);
            return end($youtube_id);
        } elseif($cmpVimeoLink == 0 AND !empty($exLink[3])){
            return end($exLink);
        }
	}
}
