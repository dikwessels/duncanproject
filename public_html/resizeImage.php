<?

$image = "brand_images/ayliss_a_01.jpg"; // name/location of original image.
$new_image = "brand_images/ayliss_a_01thumbnail.jpg"; // name/location of generated thumbnail.

$src_img = ImageCreateFromJPEG($image); // make 'connection' to image

$quality = 40; //quality of the .jpg

$src_width = imagesx($src_img); // width original image
$src_height = imagesy($src_img); // height original image

$dest_width = 150; //width thumbnail (image will scale down completely to this width)
$dest_height = 100; //max height of the thumbnail

$ar = $dest_width / $dest_height; // aspect ratio

$divX = $src_width / $dest_width; // factor to calculate the scaled down height

if($src_height / $divX <= $dest_height){ //if the scaled down height is smaller than the thumbnail max height
$y = 0; // don`t center crop (there is nothing to center)
$dest_height = $src_height / $divX; // keep the original scaled down height
$cropheight = $src_height; // set the cropheight to the original image height (there is nothing to be cropped)
}else{
$cropheight = $src_width / $ar; //maintain the proper thumbnail aspect ratio
$y = $src_height / $divX; //calculate the scaled down height
$y = ($y - $dest_height) * $divX / 2; //substract the thumbnail height from the scaled down original height than divide by 2 (for centering the crop to the proper height)
}

$dest_img = imagecreatetruecolor($dest_width,$dest_height); 
imagecopyresampled($dest_img, $src_img, 0, 0, 0 ,$y, $dest_width, $dest_height, $src_width, $cropheight); 
imagejpeg($dest_img, $new_image, $quality); 
imagedestroy($src_img); 
imagedestroy($dest_img);
?>	