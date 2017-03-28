<HTML>
<HEAD>
<TITLE></TITLE>
<META name="description" content="">
<META name="keywords" content="">
<script language='javascript'></script>
<style type='text/css'></style>
</HEAD>
<BODY>
<?

function createImage($image,$w,$h,$ext,$quality,$dir,$folder) {
$im="$dir/$image";
$new_image = "../resizedHandles/$folder/$image";; // name/location of generated thumbnail.
if (file_exists($new_image)) { return; }
if (!$src_img = @ImageCreateFromJPEG($im)) { echo "$image<br>";return; }

$src_width = imagesx($src_img); // width original image
$src_height = imagesy($src_img); // height original image
if ($src_width==350) { copy($im,$new_image);return; }

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



function resizeImages($dir,$folder) { 
	$h=opendir("$dir");
    while (false !== ($f = readdir($h))) { 	
		set_time_limit(10); 
      	if ($f != "." && $f != ".." && !is_dir("$dir/$f") && eregi("jpg",$f)) { 
			createImage($f,350,1000,"",100,$dir,$folder);
		 	echo"$dir/$f<br>"; 	
			}		
		}
	closedir($h);	
	}


/*$folder="../HANDLES/";  
if ($handle = opendir($folder)) {
   while (false !== ($file = readdir($handle))) { 
		set_time_limit(10); 
      	if ($file != "." && $file != ".." && is_dir("$folder/$file")) { 
			@mkdir("../resizedHandles/$file");
			resizeImages("$folder$file",$file);				
			}
		}
	closedir($handle);
	} */

	$h=opendir("../HANDLES2");
    while (false !== ($f = readdir($h))) { 	
		set_time_limit(10); 
      	if (substr($f,0,1)==	'v') { echo $f."<BR>"; 
			createImage($f,350,1000,"",100,$h,"V");
		 	echo"$dir/$f<br>"; 	
			}		
		}
	closedir($h);	
?>

</BODY>
</HTML>