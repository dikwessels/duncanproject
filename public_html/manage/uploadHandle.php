<?
extract($_POST);
function createImage($im,$image,$w,$h,$ext,$quality,$folder) {

$new_image = "$folder/$image";; // name/location of generated thumbnail.
$src_img = @ImageCreateFromJPEG($im);

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
echo "$new_image created<BR>";
	}



include("/connect/mysql_connect.php");

if (is_uploaded_file($_FILES['image']['tmp_name'])) {
	$re=array("&",",",'.','#','by','BROTHERS','INTERNATIONAL');$rw=array("AND",'','','','','BROS','INTL');
	$image=str_replace($re,$rw,strtoupper("$pattern $brand")).".jpg";
	createImage($_FILES['image']['tmp_name'],$image,600,1000,"",100,"../HANDLES/".substr($image,0,1));
	createImage($_FILES['image']['tmp_name'],$image,350,1000,"",100,"../resizedHandles/".substr($image,0,1));
	mysql_query("UPDATE handles set image=1 where pattern='$pattern' and brand='$brand' limit 1");
	}
?>
<HTML>
<HEAD>
<TITLE></TITLE>
<META name="description" content="">
<META name="keywords" content="">
<META name="generator" content="CuteHTML">
</HEAD>
<BODY BGCOLOR="#FFFFFF" TEXT="#000000" LINK="#0000FF" VLINK="#800080"><form method=post  enctype='multipart/form-data'><table><tr><td align=right>Pattern:</td><td><select name=pattern>
<?
$q=mysql_query("SELECT distinct(pattern) as p from inventory where category='f'  or category='sp' order by p");
while ($r=mysql_fetch_assoc($q)) { 
	echo "<option value=\"$r[p]\">$r[p]";
}
?>
</select></td></tr><tr><td align=right>Manufacturer:</td><td><select name=brand>
<?
$q=mysql_query("SELECT distinct(brand) as p from inventory where category='f' or category='sp' order by p");
while ($r=mysql_fetch_assoc($q)) { 
	echo "<option value=\"$r[p]\">$r[p]";
}	
?>
</select></td></tr><tr><td align=right>Handle Image:</td><td><input type=file name=image></td></tr></table><input type=submit></form>

</BODY>
</HTML>
