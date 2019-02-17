<?php
/**
 * Created by JetBrains PhpStorm.
 * User: LanOfCoder
 * Date: 12/19/13
 * Time: 5:34 PM
 * To change this template use File | Settings | File Templates.
 */
$font = './arial.ttf';

// list possible characters to include on the CAPTCHA
$charset = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

// how many characters include in the CAPTCHA
$code_length = 6;

// antispam image height
$height = 22;

// antispam image width
$width = 120;

############################################################
#END OF SETTINGS
############################################################

// this will start session if not started yet
@session_start();

$code = '';
for($i=0; $i < $code_length; $i++) {
	$code = $code . substr($charset, mt_rand(0, strlen($charset) - 1), 1);
}

$font_size = $height * 0.7;
$image = @imagecreate($width, $height);
$background_color = @imagecolorallocate($image, 255, 255, 255);
$noise_color = @imagecolorallocate($image, 20, 40, 100);

/* add image noise */
for($i=0; $i < ($width * $height) / 8; $i++) {
	@imageellipse($image, mt_rand(0,$width), mt_rand(0,$height), 1, 1, $noise_color);
}

/* render text */
$text_color = @imagecolorallocate($image, 20, 40, 100);
@imagettftext($image, $font_size, 0, 7,17,
$text_color, $font , $code)
or die('Cannot render TTF text.');
/* output image to the browser */
header('Content-Type: image/png');
@imagepng($image) or die('imagepng error!');
@imagedestroy($image);
$_SESSION['dortestimonials_captcha'] = $code;
exit();
