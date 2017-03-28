<HTML><BODY><?

function createImage($image,$w,$h,$ext,$quality) {
$im = "../productImages/_BG/$image"; // name/location of original image.
$new_image = "../productImages/$ext/".substr($image,0,-4)."{$ext}.jpg";; // name/location of generated thumbnail.
if (file_exists($new_image)) { $temp=unlink($new_image); }
$src_img = ImageCreateFromJPEG($im); // make 'connection' to image


$src_width = imagesx($src_img); // width original image
$src_height = imagesy($src_img); // height original image

$dest_width = $w; //width thumbnail (image will scale down completely to this width)
$dest_height = $h; //max height of the thumbnail

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
	}


include("/home/asyoulik/connect/mysql_connect.php");




$folder="../temp/";  ## include trailing "/";
if ($handle = opendir($folder)) {
   while (false !== ($file = readdir($handle))) { 
		set_time_limit(10); 
        if ($file != "." && $file != ".." && !is_dir("../temp/$file")) { 
			if (file_exists("../productImages/_BG/$file")) { $temp=unlink("../productImages/_BG/$file"); }	
			copy("/home/asyoulik/public_html/temp/$file", "/home/asyoulik/public_html/productImages/_BG/$file");
			createImage($file,100,100,"_TN",75);
			createImage($file,250,600,"_SM",100);
			unlink("../temp/$file");			
			}
		}
	}



?>
</BODY>
</HTML>
