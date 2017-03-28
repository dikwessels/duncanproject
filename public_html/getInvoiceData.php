<?php

    include("/connect/mysql_pdo_connect.php");
    //this outputs a hybrid xml format because visual basic sucks 

    extract($_GET);

    extract($_POST);

    $stmt = "SELECT * FROM customers WHERE customerNum = :id";

    $query = $db->prepare($stmt);

    $query->bindParam(":id",$id);

    $query->execute();

    $result = $query->fetchAll();


    $row = $result[0];


	function getProductDescription($id){
	
		global $db;
	
		//this spits out the product ID the pattern, brand and item name if the product is a place setting etc
		
		$stmt = "SELECT * FROM inventory WHERE id=:id";
		
		$query = $db->prepare($stmt);
		
		$query->bindParam(":id",$id);
		
		$query->execute();
		
		$result = $query->fetchAll();
		
		$row = $result[0];
		
		extract($row);
		
		$productId = $productId?$productId:-1;
		
		return "$productId::$pattern::$brand::$item::$retail";
		
		
	
	}
	
    foreach($row as $k=>$v){
	    
	    if( $k == "items" ){
		    
		    $itemArray = explode("&", $v);
		    
		    foreach($itemArray as $item){
				
				$itemData = explode(":", $item);
		
				if(substr($itemData[0],0,2) == "gc"){
					$id = substr($itemData[0],2);
					//$retail = substr($itemData[2], 0,strpos($itemData, "||") );
					
					$productDescription = getProductDescription($id);
					$orderData.="<field><item>$productDescription::1</item></field>";
				}	
				else{
					if($itemData[0]){
					$productDescription = getProductDescription($itemData[0]);

					$orderData.="<field><item>$productDescription::$itemData[1]</item></field>";
					}
				}
					    
		    
		    }
	    }
	    
	    else{
		    $orderData .= "<field>$k=>$v</field>";
	    }
        
    }

  echo $orderData;

?>