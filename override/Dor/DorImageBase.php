<?php

require_once (_PS_ROOT_DIR_.'/override/Dor/phpthumb/PhpThumb.inc.php');
require_once (_PS_ROOT_DIR_.'/override/Dor/phpthumb/ThumbLib.inc.php');
class DorImageBase
{
    /**
     * @var string $_name is name of group;
     *
     * @access private;
     */
    private $__name = '';

    /**
     *  check the folder is existed, if not make a directory and set permission is 755
     *
     * @param array $path
     * @access public,
     * @return boolean.
     */
	
    public static function makeDir($path)
    {
        $folders = explode('/', ($path));
        $tmppath = _PS_ROOT_DIR_."/img/dorthumbs/";
        if (!file_exists($tmppath)) {
            mkdir($tmppath, 0777, true);
        };
        for ($i = 0; $i < count($folders) - 1; $i++) {
            if (!file_exists($tmppath . $folders [$i]) && !mkdir($tmppath . $folders [$i], 0777)) {
                return false;
            }
            $tmppath = $tmppath . $folders [$i] . '/';
        }
        return true;
    }

    /**
     *  check the folder is existed, if not make a directory and set permission is 755
     *
     * @param array $path
     * @access public,
     * @return boolean.
     */
    public static function renderThumb($path, $width = 100, $height = 100,$type = '', $title = '', $isThumb = true, $image_quanlity = 100, $returnPath = false, $class = "")
    {
        if (!preg_match("/.jpg|.png|.gif/", strtolower($path))) return '&nbsp;';
        if ($isThumb) {
            if (empty($image_quanlity)) {
                $image_quanlity = 100;
            }
            $imagSource = _PS_MODULE_DIR_. $path;
            
            if (file_exists($imagSource)) {
                if($type!=''){
                    $path = $width . "x" . $height . "x" . $type . '/' . $path;
                }else{
					$path = $width . "x" . $height . '/' . $path;
				}
                $thumbImage = 'img/dorthumbs/' . $path;
                $thumbPath = _PS_ROOT_DIR_.'/' . $thumbImage;
                if (!file_exists($thumbPath)) {
                    $thumb = \PhpThumbFactory::create($imagSource);
                    $thumb->setOptions(array('jpegQuality' => $image_quanlity));
                    if (!DorImageBase::makeDir($path)) {
                        return '';
                    }
                    $thumb->adaptiveResize($width, $height);
                    
                    $thumb->save($thumbPath);
                }
                $path = __PS_BASE_URI__.$thumbImage;
            }
        }
        return $path;
       
    }
    public static function renderThumbMasonry($path, $width = 100, $height = 100,$type = '', $title = '', $isThumb = true, $image_quanlity = 100, $returnPath = false, $class = "")
    {
        if (!preg_match("/.jpg|.png|.gif/", strtolower($path))) return '&nbsp;';
        if ($isThumb) {
            if (empty($image_quanlity)) {
                $image_quanlity = 100;
            }
            $imagSource = _PS_MODULE_DIR_. $path;
            
            if (file_exists($imagSource)) {
                if($type!=''){
                    $path = $width . "x" . $height . "x" . $type . '/' . $path;
                }else{
                    $path = $width . "x" . $height . '/' . $path;
                }
                $thumbImage = 'img/dorthumbs/' . $path;
                $thumbPath = _PS_ROOT_DIR_.'/' . $thumbImage;
                if (!file_exists($thumbPath)) {
                    $thumb = \PhpThumbFactory::create($imagSource);

                    $thumb->setOptions(array('jpegQuality' => $image_quanlity));
                    if (!DorImageBase::makeDir($path)) {
                        return '';
                    }
                    $thumb->resize($width, $height);

                    $thumb->save($thumbPath);
                }
                $path = __PS_BASE_URI__.$thumbImage;
            }
        }
        return $path;
       
    }
    public static function renderThumbProduct($path,$rewrite="", $width = 100, $height = 100, $image_quanlity = 100, $type = '', $title = '', $isThumb = true, $returnPath = false, $class = "")
    {
        if (!preg_match("/.jpg|.png|.gif/", strtolower($path))) return '&nbsp;';
        if ($isThumb) {
            if (empty($image_quanlity)) {
                $image_quanlity = 100;
            }
            $imagSource = _PS_ROOT_DIR_."/img". $path;
            if (file_exists($imagSource)) {
                if($type!=''){
                    $path = "product/".$width . "x" . $height . "x" . $type . '/' . $path;
                }else{
                    $path = "product/".$width . "x" . $height . '/' . $rewrite.".jpg";
                }
                $thumbImage = 'img/dorthumbs/' . $path;
                $thumbPath = _PS_ROOT_DIR_.'/' . $thumbImage;
                if (!file_exists($thumbPath)) {
                    $thumb = \PhpThumbFactory::create($imagSource);
                    $thumb->setOptions(array('jpegQuality' => $image_quanlity));
                    if (!DorImageBase::makeDir($path)) {
                        return '';
                    }
                    $thumb->adaptiveResize($width, $height);
                    
                    $thumb->save($thumbPath);
                }
                $path = __PS_BASE_URI__.$thumbImage;
            }
        }
        return $path;
       
    }
    public static function renderThumbDorGallery($path,$rewrite="", $width = 100, $height = 100, $image_quanlity = 100, $type = '', $title = '', $isThumb = true, $returnPath = false, $class = "")
    {
        if (!preg_match("/.jpg|.png|.gif/", strtolower($path))) return '&nbsp;';
        if ($isThumb) {
            if (empty($image_quanlity)) {
                $image_quanlity = 100;
            }
            $imagSource = _PS_MODULE_DIR_. $path;
            if (file_exists($imagSource)) {
                if($type!=''){
                    $path = "gallery/".$width . "x" . $height . "x" . $type . '/' . $path;
                }else{
                    $path = "gallery/".$width . "x" . $height . '/' . $rewrite.".jpg";
                }
                $thumbImage = 'img/dorthumbs/' . $path;
                $thumbPath = _PS_ROOT_DIR_.'/' . $thumbImage;
                if (!file_exists($thumbPath)) {
                    $thumb = \PhpThumbFactory::create($imagSource);
                    $thumb->setOptions(array('jpegQuality' => $image_quanlity));
                    if (!DorImageBase::makeDir($path)) {
                        return '';
                    }
                    $thumb->adaptiveResize($width, $height);
                    $thumb->save($thumbPath);
                }
                $path = __PS_BASE_URI__.$thumbImage;
            }
        }
        return $path;
       
    }
    public static function renderThumbGallery($path,$rewrite="", $width = 100, $height = 100, $image_quanlity = 100, $type = '', $title = '', $isThumb = true, $returnPath = false, $class = "")
    {
        if (!preg_match("/.jpg|.png|.gif/", strtolower($path))) return '&nbsp;';
        if ($isThumb) {
            if (empty($image_quanlity)) {
                $image_quanlity = 100;
            }
            $imagSource = _PS_ROOT_DIR_."/img". $path;
            if (file_exists($imagSource)) {
                if($type!=''){
                    $path = "gallery/".$width . "x" . $height . "x" . $type . '/' . $path;
                }else{
                    $path = "gallery/".$width . "x" . $height . '/' . $rewrite;
                }
                $thumbImage = 'img/dorthumbs/' . $path;
                $thumbPath = _PS_ROOT_DIR_.'/' . $thumbImage;
                if (!file_exists($thumbPath)) {
                    $thumb = \PhpThumbFactory::create($imagSource);
                    $thumb->setOptions(array('jpegQuality' => $image_quanlity));
                    if (!DorImageBase::makeDir($path)) {
                        return '';
                    }
                    $thumb->adaptiveResize($width, $height);
                    
                    $thumb->save($thumbPath);
                }
                $path = __PS_BASE_URI__.$thumbImage;
            }
        }
        return $path;
       
    }
    
/**
     *  check the folder is existed, if not make a directory and set permission is 755
     *
     * @param array $path
     * @access public,
     * @return boolean.
     */
    public function renderThumbCropCenter($path, $width = 100, $height = 100, $type = '', $title = '', $isThumb = true, $image_quanlity = 100, $returnPath = false)
    {
        $picture_path = BASE_URL;
        
        if (!preg_match("/.jpg|.png|.gif/", strtolower($path))) return '&nbsp;';
        if ($isThumb) {
            if (empty($image_quanlity)) {
                $image_quanlity = 100;
            }
            $imagSource = APP_ROOT . '/images/' . $path;
            if (file_exists($imagSource)) {
                if($type!=''){
                    $path = $width . "x" . $height . "x" . $type . '/' . $path;
                }else{
                    $path = $width . "x" . $height . '/' . $path;
                }
            
                
                $thumbPath = APP_ROOT . '/' . 'thumbs/' . $path;
                if (!file_exists($thumbPath)) {
                    //$thumb = \PhpThumbFactory();
                    if(!preg_match("/.gif/", strtolower($path))){
                        $thumb = \PhpThumbFactory::create($imagSource);
                        $thumb->setOptions(array('jpegQuality' => $image_quanlity));
                        if (!DorImageBase::makeDir($path)) {
                            return '';
                        }
                        $thumb->cropFromCenter($width, $height);
                    }else{
                        copy($imagSource,$thumbPath);
                        DorImageBase::makeDir($thumbPath);
                    }
                    $thumb->save($thumbPath);
                }
                $path = $thumbPath;
                
            }
        }
        if ($returnPath) {
            return $path;
        } else {
            return $path;
        }
    }

    /**
     *  check the folder is existed, if not make a directory and set permission is 755
     *
     * @param array $path
     * @access public,
     * @return boolean.
     */
    public function renderThumbResize($path, $width = 100, $height = 100, $type = '', $title = '', $isThumb = true, $image_quanlity = 100, $returnPath = false, $id = '')
    {
        $picture_path = BASE_URL;
        if (!preg_match("/.jpg|.png|.gif/", strtolower($path))) return '&nbsp;';
        if ($isThumb) {
            if (empty($image_quanlity)) {
                $image_quanlity = 100;
            }
            $imagSource = APP_ROOT . '/images/' . $path;
            if (file_exists($imagSource)) {
                $path = $width . "x" . $height . '/' . $path;
                if($type!=''){
                    $path = $width . "x" . $height . "x" . $type . '/' . $path;
                }else{
                    $path = $width . "x" . $height . '/' . $path;
                }

                $thumbPath = APP_ROOT . '/thumbs/' . $path;

                if (!file_exists($thumbPath)) {
                    $thumb = \PhpThumbFactory::create($imagSource);

                    $thumb->setOptions(array('jpegQuality' => $image_quanlity));
                    if (!$this->makeDir($path)) {
                        return '';
                    }
                    $thumb->resize($width, $height);

                    $thumb->save($thumbPath);
                }
                $path = $thumbPath;
            }
        }
        if ($returnPath) {
            return $path;
        } else {
            return $path;
        }
    }

    

    /**
     *  check the folder is existed, if not make a directory and set permission is 755
     *
     * @param array $path
     * @access public,
     * @return boolean.
     */
    public function renderThumbRotate($path, $width = 100, $height = 100, $type = '' , $title = '', $isThumb = true, $image_quanlity = 100, $returnPath = false)
    {
        $picture_path = BASE_URL;
        if (!preg_match("/.jpg|.png|.gif/", strtolower($path))) return '&nbsp;';
        if ($isThumb) {
            if (empty($image_quanlity)) {
                $image_quanlity = 100;
            }
            $imagSource = APP_ROOT . '/images/' . $path;
            if (file_exists($imagSource)) {
                $path = $width . "x" . $height . '/' . $path;
                if($type!=''){
                    $path = $width . "x" . $height . "x" . $type . '/' . $path;
                }else{
                    $path = $width . "x" . $height . '/' . $path;
                }

                $thumbPath = APP_ROOT . '/thumbs/' . $path;

                if (!file_exists($thumbPath)) {
                    $thumb = \PhpThumbFactory::create($imagSource);

                    $thumb->setOptions(array('jpegQuality' => $image_quanlity));
                    if (!$this->makeDir($path)) {
                        return '';
                    }
                    $thumb->rotateImage('CW');

                    $thumb->save($thumbPath);
                }
                $path = $thumbPath;
            }
        }
        if ($returnPath) {
            return $path;
        } else {
            return $path;
        }
    }

    /**
     *  check the folder is existed, if not make a directory and set permission is 755
     *
     * @param array $path
     * @access public,
     * @return boolean.
     */
    public function renderThumbPercent($path, $percent = 100, $width = 100, $height = 100, $type = '' , $title = '', $isThumb = true, $image_quanlity = 100, $returnPath = false)
    {
        $picture_path = BASE_URL;
        if (!preg_match("/.jpg|.png|.gif/", strtolower($path))) return '&nbsp;';
        if ($isThumb) {
            if (empty($image_quanlity)) {
                $image_quanlity = 100;
            }
            $imagSource = APP_ROOT . '/images/' . $path;
            if (file_exists($imagSource)) {
                $path = $width . "x" . $height . '/' . $path;
                if($type!=''){
                    $path = $width . "x" . $height . "x" . $type . '/' . $path;
                }else{
                    $path = $width . "x" . $height . '/' . $path;
                }

                $thumbPath = APP_ROOT . '/thumbs/' . $path;

                if (!file_exists($thumbPath)) {
                    $thumb = \PhpThumbFactory::create($imagSource);

                    $thumb->setOptions(array('jpegQuality' => $image_quanlity));
                    if (!$this->makeDir($path)) {
                        return '';
                    }
                    $thumb->resizePercent($percent);

                    $thumb->save($thumbPath);
                }
                $path = $thumbPath;
            }
        }
        if ($returnPath) {
            return $path;
        } else {
            return $path;
        }
    }

    /**
     *  check the folder is existed, if not make a directory and set permission is 755
     *
     * @param array $path
     * @access public,
     * @return boolean.
     */
    public function renderThumbCrop($path, $crop1 = 100, $crop2 = 100, $width = 100,$type = '', $height = 100, $title = '', $isThumb = true, $image_quanlity = 100, $returnPath = false)
    {
        $picture_path = BASE_URL;
        if (!preg_match("/.jpg|.png|.gif/", strtolower($path))) return '&nbsp;';
        if ($isThumb) {
            if (empty($image_quanlity)) {
                $image_quanlity = 100;
            }
            $imagSource = APP_ROOT . '/images/' . $path;
            if (file_exists($imagSource)) {
                $path = $width . "x" . $height . '/' . $path;
                if($type!=''){
                    $path = $width . "x" . $height . "x" . $type . '/' . $path;
                }else{
                    $path = $width . "x" . $height . '/' . $path;
                }
                $thumbPath = APP_ROOT . '/thumbs/' . $path;

                if (!file_exists($thumbPath)) {
                    $thumb = \PhpThumbFactory::create($imagSource);

                    $thumb->setOptions(array('jpegQuality' => $image_quanlity));
                    if (!$this->makeDir($path)) {
                        return '';
                    }
                    $thumb->crop($crop1, $crop2, $width, $height);

                    $thumb->save($thumbPath);
                }
                $path = $thumbPath;
            }
        }
        if ($returnPath) {
            return $path;
        } else {
            return $path;
        }
    }


    /**
     *  check the folder is existed, if not make a directory and set permission is 755
     *
     * @param array $path
     * @access public,
     * @return boolean.
     */
    public function renderThumbCropFrom($path, $crop1 = 100, $crop2 = 100, $width = 100, $height = 100, $type = '',  $title = '', $isThumb = true, $image_quanlity = 100, $returnPath = false)
    {
        $picture_path = BASE_URL;
        if (!preg_match("/.jpg|.png|.gif/", strtolower($path))) return '&nbsp;';
        if ($isThumb) {
            if (empty($image_quanlity)) {
                $image_quanlity = 100;
            }
            $imagSource = APP_ROOT . '/images/' . $path;
			
            if (file_exists($imagSource)) {
                $path = $width . "x" . $height . '/' . $path;
                if($type!=''){
                    $path = $width . "x" . $height . "x" . $type . '/' . $path;
                }
                $thumbPath = APP_ROOT . '/' . 'images' . '/' . 'thumbs' . '/' . $path;
				
                if (!file_exists($thumbPath)) {
                    $thumb = \PhpThumbFactory::create($imagSource, array(), true);
                    $thumb->setOptions(array('jpegQuality' => $image_quanlity));
                    if (!$this->makeDir($path)) {
                        return '';
                    }

                    $thumb->crop($crop1, $crop2, $width, $height);
					
                    $thumb->save($thumbPath);
                }
                $path = $picture_path . 'images/thumbs/' . $path;
            }
        }
        if ($returnPath) {
            return $path;
        } else {
            return '<img src="' . $path . '" title="' . $title . '" alt="' . $title . '"/>';
        }
    }

    public function renderThumbBase($path, $width = 100, $height = 100, $type = '' ,$isThumb = true)
    {
        if (!preg_match("/.jpg|.png|.gif|.jpeg/", strtolower($path))) return '&nbsp;';
        if ($isThumb) {
            $imagSource = PATH_TO_MEDIA. $path;

            if (file_exists($imagSource)) {
                $paths = $width . "x" . $height . '/' . $path;
                if($type!=''){
                    $paths = $width . "x" . $height . "x" . $type . '/' . $path;
                }

                $thumbPath = PATH_TO_THUMB . $paths;

                list($CurWidth, $CurHeight, $ImageType) = getimagesize($imagSource);
                switch (strtolower($ImageType)) {
                    case 1:
                        $CreatedImage = imagecreatefromgif($imagSource);
                        break;
                    case 2:
                        $CreatedImage = imagecreatefromjpeg($imagSource);
                        break;
                    case 3:
                        $CreatedImage = imagecreatefrompng($imagSource);
                        break;
                    default:
                        die('Unsupported File!'); //output error
                }
                if (!file_exists($thumbPath)) {

                    if (!$this->makeDir($paths)) {
                        return '';
                    }
                    $this->saveCustom($imagSource);
                }
                if(!preg_match("/.gif/", strtolower($path))){
                    $resizeImagePath = $this->resizeImage($CurWidth, $CurHeight, $width, $height, $thumbPath, $CreatedImage);
                }else{
                    copy($imagSource,$thumbPath);
                    $resizeImagePath = $thumbPath;
                }
                if ($resizeImagePath) {
                    $path = PATH_TO_THUMB . $paths;
                } else {
                    die('Resize Error');
                }

            }
        }
            return $path;
    }

    public function saveCustom($fileName, $format = null)
    {
        @chmod($fileName, 0777);
        return true;
    }

    public function resizeImage($CurWidth, $CurHeight, $MaxWidth, $MaxHeight, $DestFolder, $SrcImage)
    {

        $ImageScale = min($MaxWidth / $CurWidth, $MaxHeight / $CurHeight);
        $NewWidth = ceil($ImageScale * $CurWidth);
        $NewHeight = ceil($ImageScale * $CurHeight);
        $NewCanves = imagecreatetruecolor($NewWidth, $NewHeight);
        if (imagecopyresampled($NewCanves, $SrcImage, 0, 0, 0, 0, $NewWidth, $NewHeight, $CurWidth, $CurHeight)) {
            // copy file
            @chmod($DestFolder, 0777);
            if (imagejpeg($NewCanves, $DestFolder, 100)) {
                imagedestroy($NewCanves);
                return true;
            }
        }
        return true;
    }

}
