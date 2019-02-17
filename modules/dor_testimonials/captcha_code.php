<?php
/**
 */
  session_start();
  $ranStr = md5(microtime());
  $ranStr = substr($ranStr, 0, 6);
  $_SESSION['cap_code'] = $ranStr;
  $newImage = imagecreatefromjpeg("bg_captcha.jpg");
  $txtColor = imagecolorallocate($newImage, 0, 0, 0);
  imagestring($newImage, 5, 5, 5, $ranStr, $txtColor);
  header("Content-type: image/jpeg");
  imagejpeg($newImage);
