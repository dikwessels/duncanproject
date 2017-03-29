<?function createImage($im,$image,$w,$h,$ext,$quality) {

$new_image = "../featuredArticles/images/$image";; // name/location of generated thumbnail.
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

if (is_uploaded_file($_FILES['userfile']['tmp_name'])) {
    $image=stripslashes($_FILES['userfile']['name']);
	createImage($_FILES['userfile']['tmp_name'],$image,250,600,"_SM",100);
	list($w,$h)=getimagesize("../featuredArticles/images/$image");
	$c="<img src='/featuredArticles/images/$image' align=$align width=$w height=$h>";
echo"<html><head><script>	t=opener.document.forms[0].text;
	t.value=t.value.replace(/\.img/,\"$c\");
	opener.showDisplay()
	self.close()</script></head></html>";
	
	}
else {
if ($handle = opendir('../featuredArticles/images/')) {
   while (false !== ($file = readdir($handle))) {
       if ($file != "." && $file != "..") {
           $imgs.="<option value='/featuredArticles/images/$file'>$file";
       }
   }
   closedir($handle);
}
if ($handle = opendir('../productImages/_SM/')) {
   while (false !== ($file = readdir($handle))) {
       if ($file != "." && $file != "..") {
           $prodimgs.="<option value='/productImages/_SM/$file'>$file";
       }
   }
   closedir($handle);
}
?>
<html><head> <script type=text/javascript>
function showImg(v) {
	document.getElementById('imgViewer').innerHTML="<img id=displayImg src='"+v+"'>"; 
	}

function addImg(e) {
	t=opener.document.forms[0].text;
	h=document.getElementById('displayImg').offsetHeight
	w=document.getElementById('displayImg').offsetWidth
	v=e.options[e.selectedIndex].value
	c = "<img src='"+v+"' width="+w+" height="+h+" align="+e.form.align.options[e.form.align.selectedIndex].value+">";
	t.value=t.value.replace(/\.img/,c);
	opener.showDisplay()
	self.close()
	}
</script></head><body><h2>
Upload Image</h2>You can either upload an image, select a previously uploaded image, or select an inventory image.<form method=post action=uploadFeaturedArticleImage.php enctype='multipart/form-data' method=post><input type='hidden' name='MAX_FILE_SIZE' value='1000000'><table><tr><td align=right>Align to</td><td><select name=align><option value=right>right<option value=left>left</select> of text.</td></tr><tr><td colspan=2><hr><h2>Upload From Hard Drive</h2></td><tr><tr><td align=right>File</td><td><input type=file name=userfile><input type=submit value='Upload Photo'></td></tr><tr><td colspan=2><hr><h2>Photo Library</h2></td></tr><tr><td align=right>Photo</td><td><select name=libraryImg onChange=showImg(this.value)><option><? echo $imgs;?></select><input type=button onClick=addImg(this.form.libraryImg)  value='Insert Image'></td></tr><tr><td colspan=2><hr><h2>Inventory Photos</h2></td></tr><tr><td>Photo</td><td><select name=inventoryImg onChange=showImg(this.value) ><option><? echo $prodimgs;?></select><input type=button onClick=addImg(this.form.inventoryImg) value='Insert Image'></td></tr><tr><td colspan=2 id=imgViewer></td></tr></table></form>
</body></html>
<?
}
?>