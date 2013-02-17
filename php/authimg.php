<?php
header("Content-type: image/png");

$string = ""; // ...ABCDEFGHIJKLMNOPQRSTUVWXYZ...

for ($i = 1 ; $i < 5 ; $i++)
	$string .= substr("0123456789",rand(0,9),1);

$im = imagecreate(30,20)
      or die("Cannot Initialize new GD image stream");
      
$text_color = imagecolorallocate($im, 255, 128, 0);
$bg_color = imagecolorallocatealpha($im,0,0,0,127); 
imagefill($im,0,0,$bg_color); 

imagestring($im, 2, 3, 2,  $string, $text_color);

imagepng($im);
imagedestroy($im);
?>