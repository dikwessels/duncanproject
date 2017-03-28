<?php
 extract($_GET);
 
 echo $productID."<br>";
 
 	ini_set("display_errors",1);

	include("/home/asyoulik/connect/mysql_connect.php");
	
	
	if($productID){
		
		$query = "SELECT * FROM inventory WHERE productId = $productID AND image IS NOT NULL AND image! = ''";	  
	
	}	
	
	else{
		
		$query = "SELECT * FROM inventory WHERE (image IS NULL OR image='' OR image=' ') order by id, productId";
	}
  
	$result=mysql_query($query);
	
	if(mysql_num_rows($result)>0){
		
	  if(!$productID){ 
	
		while( $row = mysql_fetch_assoc($result) ){
			
			extract($row);
			//$image=str_replace(" ", "", $image);
				echo "$id:$productId:$image<br>";	
		}
	  
	  }
	  else{
		  
	  	  $row = mysql_fetch_assoc($result);
	  	  extract($row);
	  	  echo $image."<br>";	
		  echo "has_image";
	  }
	}
	else{
		echo "yadda_no_image_fool	";
	}
	

?>