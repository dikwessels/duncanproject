<?php

ini_set("display_errors",1);
include("InventoryItem.php");

extract($_POST);

$userfiles=array(
			'userfile',
			'userfile2',
			'userfile3'
		);

function createImage($image,$w,$h,$ext,$quality) {

	clearstatcache();	
	// name/location of original image.

	$im = "/home/asyoulik/public_html/productImages/_BG/$image"; 
	
	$new_image = "../productImages/$ext/".substr($image,0,-4)."$ext.jpg";
    $imageURL=str_replace("..", "/home/asyoulik/public_html", $new_image);
    
	// name/location of generated thumbnail.
	//echo $new_image;
	if(file_exists($imageURL)){
		$temp=unlink($imageURL); 
	}

	$src_img = ImageCreateFromJPEG($im); // make 'connection' to image


	$src_width = imagesx($src_img); // width original image
	$src_height = imagesy($src_img); // height original image

	$dest_width = $w; //width thumbnail (image will scale down completely to this width)
	$dest_height = $h; //max height of the thumbnail

	$ar = $dest_width / $dest_height; // aspect ratio

	$divX = $src_width / $dest_width; // factor to calculate the scaled down height

	if($src_height / $divX <= $dest_height){ 
		//if the scaled down height is smaller than the thumbnail max height
		$y = 0; // don`t center crop (there is nothing to center)
		$dest_height = $src_height / $divX; // keep the original scaled down height
		$cropheight = $src_height; // set the cropheight to the original image height (there is nothing to be cropped)
	}
	else{
		$cropheight = $src_width / $ar; //maintain the proper thumbnail aspect ratio
		$y = $src_height / $divX; //calculate the scaled down height
		$y = ($y - $dest_height) * $divX / 2; //substract the thumbnail height from the scaled down original height than divide by 2 (for centering the crop to the proper height)
		}

		$dest_img = imagecreatetruecolor($dest_width,$dest_height); 
		imagecopyresampled($dest_img, $src_img, 0, 0, 0 ,$y, $dest_width, $dest_height, $src_width, $cropheight); 
		imagejpeg($dest_img, $imageURL, $quality); 
		imagedestroy($src_img); 
		imagedestroy($dest_img);

return $new_image;

}

//echo 'hello';

if(is_uploaded_file($_FILES['userfile']['tmp_name'])){
	//this doesn't
   //echo $_FILES['userfile']['filename'];
   //fin the productId
   	
   if($productId=="" && $id==""){
	  $filename=$_FILES['userfile']['name'];
	  //this will only work for files named with product id at beginning 
	  $productId=substr($filename, 0,5);
   }
   
      $product=new InventoryItem();
      if($id){
	      $product->setValue('id',$id);
      }
      else{
	      $product->setValue('productId',$productId);
      }
      
      $product->retrieve();
      
      $image=$product->imageName($imagenumber);
      $product->setValue('image'.$imagenumber,$image);
      //echo $image." ";
      
      $origImage="/home/asyoulik/public_html/productImages/_BG/$image";
      
      if (file_exists($image)) { 
        //echo "erasing $origImage...";
		$temp=unlink($origImage);
	  }	
	 
	  copy($_FILES['userfile']['tmp_name'], $origImage);

      $images['mainImage']="../productImages/_BG/$image";
	  $images['smallImage']=createImage($image,250,600,"_SM",100);
	  $images['thumbnail']=createImage($image,100,100,"_TN",75);	
	  
	  $product->saveItem();
	  
	  echo json_encode($images);
}
else{
	echo 'not ok';
}

?>
