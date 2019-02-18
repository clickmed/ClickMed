<?php
class DorVideoProduct extends ObjectModel
{
    /** @var string Name */
    public $videoId;
    public $id_product;
    public $url;
    public $width;
    public $height;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'dor_videoproducts',
        'primary' => 'videoId',
        'fields' => array(
            'videoId'       =>           array('type' => self::TYPE_INT,'lang' => false),
            'id_product'    =>           array('type' => self::TYPE_INT,'lang' => false,'required' => true),
            'width'    =>           array('type' => self::TYPE_INT,'lang' => false,'required' => false),
            'height'    =>           array('type' => self::TYPE_INT,'lang' => false,'required' => false),
            'url'           =>          array('type' => self::TYPE_STRING, 'lang' => false,'required' => true, 'size' => 500),
        ),
    );
    

}