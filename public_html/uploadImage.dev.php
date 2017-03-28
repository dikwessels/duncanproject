<?php

ini_set("display_errors",1);
include("InventoryItem.php");

include_once("/connect/mysql_pdo_connect.php");

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

$index = 0;
$images = "";

//echo 'hello';
foreach($_FILES['userfile'] as $userFile){

if(is_uploaded_file($userFile['tmp_name'][$index])){   	
	
   if($productId[$index] == ""){
	   
	//drag and drop
	  $filename = $userFile['name'][$index];
	
	  //this will only work for files named 
	  $productId[$index] = substr($filename, 0,5);
   	  
   }
   
      $product = new InventoryItem();
      $product->setValue('productId',$productId[$index]);
      
      $product->retrieve();
      
      $image = $product->imageName($imagenumber[$index]);
      
      $origImage = "/home/asyoulik/public_html/productImages/_BG/$image";
      
      if (file_exists($image)) { 
		$temp = unlink($origImage);
	  }	
	 
	  if(copy($userFile['tmp_name'][$index], $origImage)){
	
	  $images[] = array(
	  					"mainImage"	 => "../productImages/_BG/$image",
	  					"smallImage" => createImage($image,250,600,"_SM",100),
	  					"thumbnail"  => createImage($image,100,100,"_TN",75)
	  					);
     
	  $imageArray = json_encode($images);
	  
	  $stmt = " UPDATE 
	  				
	  				inventory 
	  				
	  			SET 
	  				image".$imagenumber[$index]."=:image

	  			WHERE 
	  				productId = :productId";
	  
	  //echo $stmt;
	  $query = $db->prepare($stmt);

	  $query->bindParam(":image",$image,PDO::PARAM_STR);
	  
	  //$query->bindParam(":images",$imageArray,PDO::PARAM_STR);
	  
	  $query->bindParam(":productId",$productId[$index],PDO::PARAM_INT);

	  $query->execute();
	  
	  $success = $query->rowCount();
	  
	  //$product->setValue('images',$imageArray);
	  
	  //$product->saveItem();
	  
	  	if( $success > 0 ){
	  		echo json_encode($images);
	  	}
	  
	  }
	  else{
		  $err = error_get_last();
	      echo "Upload failed:".$err['message'];
	  }
}
else{
	echo 'not ok';
}
	
	$index++;
	
}
//update the json array of image names
$stmt = " UPDATE 
	  				inventory 
	  				
	  		SET 
	  				images =:image

	  		WHERE 
	  				productId = :productId";
	  				
$query = $db->prepare($stmt);

$query->bindParam(":image",json_encode($images));

$query->execute();


?>
