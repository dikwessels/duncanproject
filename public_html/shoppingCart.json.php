<?php
include_once("InventoryItem.php");
ini_set("display_errors",1);

$items=substr($_COOKIE['items'], 1);// $_COOKIE['items'];

$itemArray=explode("&", $items);
//item cookie data is split up as follows
//standard items: 	itemID:qty:regID
//gift cards:		gcItemID:amount:regID||cardCode

$re=array("&",",",'.','#','by','BROTHERS','INTERNATIONAL');
$rw=array("AND",'','','','','BROS','INTL');

foreach( $itemArray as $item ) {
	$itemData=explode(":", $item);
	$id=$itemData[0];
	$retail=$itemData[1];
	$registryData=explode("||", $itemData[2]);
	$registryID=$registryData[0];
	$cardCode=$registryData[1];	
	$results['id']=$id;
	
	if( strpos($id, "gc")===false ) {
		//echo $id;
		$inventoryItem=new InventoryItem();
		$inventoryItem->id=$id;
		$inventoryItem->retrieve();
		
		$results['giftCard']="";
		$results['itemName']=$inventoryItem->itemName();

		$results['quantity']=$retail;
		$results['retail']=number_format($inventoryItem->retail,2);
		$results['deliverTo']="";
	
		$testFileName = "/home/asyoulik/public_html/productImages/_BG/".$inventoryItem->image;
		//echo $inventoryItem->image;
		if( file_exists($testFileName) ) {
	
			$image="/productImages/_SM/".substr($inventoryItem->image,0,-4)."_SM.jpg";
		
		}
		
		else { 
			
			$fullPattern = strtoupper($inventoryitem->pattern. " ". $inventoryitem->brand);
			$testFileName = "/HANDLES/".strtoupper(substr($inventoryitem->pattern,0,1));
			$testFileName .= "/".str_replace($re,$rw,$fullPattern).".jpg";
		
		//echo $handle;
			if( file_exists("/home/asyoulik/public_html".$testFileName) ) {
				$image=$testFileName;
				
				
			}
		}
		
		$results['image']=$image;
	
	}
	
	else {
		
	//this is a giftcard
		$results['giftCard']=1;
		$results['itemName']="AS YOU LIKE IT SILVER SHOP GIFT CARD";
		$results['quantity']=1;
		$results['retail']=$retail;	
		$results['image']="";
		
		//retreive delivery address from registry on file if possible
		if( strpos($registryID, "@")===false ) {
			$results['deliverTo']=getRegistryAddress($registryID);
		}	
	
	
	}
  
  $itemList[]=$results;
  
}
	
$response['items']=$itemList;

echo json_encode($response);	


function getRegistryAddress($regID){
	global $db;
	$query=$db->prepare("SELECT * from weddingRegistries WHERE id=:id");
	$query->bindParam(":id",$regID);
	
	$query->execute();
	$result=$query->fetchAll();
	$row=$result[0];
	extract($row);
	
		if($raddress) {
			
			$address=$rfname." ".$rlname.", ".$raddress.", ".$rcity.", ".$rstate." ".$rzipcode;	
		}
		else {
			$address="";
		}
	
	return $address;
	
}

?>