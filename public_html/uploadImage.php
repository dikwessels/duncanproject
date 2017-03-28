<?php

//ini_set("display_errors",1);
include("InventoryItem.php");

include_once("/home/asyoulik/connect/mysql_pdo_connect.php");

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
	
    $imageURL = str_replace("..", "/home/asyoulik/public_html", $new_image);
    
	// name/location of generated thumbnail.
	//echo $new_image;
	if(file_exists($imageURL)){
		$temp = unlink($imageURL); 
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
   	
   if($productId == ""){
	//drag and drop
	  $filename = $_FILES['userfile']['name'];
	  //print_r($_FILES['userfile']);
	  //this will only work for files named 
	  $productId = substr($filename, 0,5);
   	  //$productId= substr($filename,0,strpos($filename,"."));
   }
   
      $product = new InventoryItem();
      $product->setValue('productId',$productId);
      $product->retrieve();
      
      $image = $product->imageName($imagenumber);

      $origImage = "/home/asyoulik/public_html/productImages/_BG/$image";
      
      if (file_exists($image)) { 
        //echo "erasing $origImage...";
		$temp = unlink($origImage);
	  }	
	 
	  if(copy($_FILES['userfile']['tmp_name'], $origImage)){
	  
	  createImage($image,250,600,"_SM",100);
	  createImage($image,100,100,"_TN",75);
	  
	  $stmt = "UPDATE inventory SET image".$imagenumber."=:image WHERE productId = :productId";//$productId";
	
	  $query = $db->prepare($stmt);

	  $query->bindParam(":image",$image,PDO::PARAM_STR);
	  
	  $query->bindParam(":productId",$productId,PDO::PARAM_INT);

	  $query->execute();
	  
	  $success = $query->rowCount();
	  
	  if( $success > 0 ){
		  
	  		echo json_encode($images);
	  		$stmt = "SELECT image, image2, image3 FROM inventory WHERE productId = $productId";
	  		$query = $db->prepare($stmt);
	  		$query->execute();
	  		$result = $query->fetchAll();
	  		
	  		$row = $result[0];

				foreach($row as $k=>$v){
				
					if($v){
					
						$images[$k] = array(
		  							"mainImage"	=>$v,
		  							"smallImage"=>substr($v,0,-4)."_SM.jpg",
		  							"thumbNail"	=>substr($v,0,-4)."_TN.jpg"
		  						  );
					}
				}	  		
		  		
		  		$jsonImages['images'] = $images;
		  		
		  		$stmt = "UPDATE inventory SET images = :images WHERE productId = $productId";
		  		
		  		$query = $db->prepare($stmt);
		  		
		  		$query->bindParam(":images",json_encode($jsonImages),PDO::PARAM_STR);	
		  		
		  		$query->execute();		
	  		
	  		
	  	}
	  
	  }
	  else{
		  $err = error_get_last();
	      echo "Upload failed:".$err['message'];
	  }
}
else{
	
	$err = error_get_last();
	echo "Upload failed:".$err['message'];
}

?>
