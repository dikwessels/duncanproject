<?php 
/*********
 shippingRatesBeta.php 
Author: 			Michael Wagner
Date:				10/1/14 - Present
Description:		Requests Fedex Shipping rates based on order weight and destination zip
Updates:			3/8/16 - adding functionality to zero all shipping and handling and display special notice
					in case of in-store pickups
	
	
	**********/	
session_name('checkout');
session_start();
ob_start();
ini_set("display_errors","1");

require("GzipCompress.php");

include($_SERVER["DOCUMENT_ROOT"]."/connect/mysql_pdo_connect.php");

include("checkoutCalcsNew.php");

setcookie("items",$_COOKIE['items'],0,'/');
extract($_POST);
extract($_GET);


$surcharge		 = array();
$deliveryMessage = array();

$storePickup 	 = 0;

//total item count, will be used for package count
$itemCount = 0;

//flag to see if order has a large item like a tray etc
$hasLargeItem 		= 0;
$largeItemSurcharge = 0;

//header("Cache-Control: no-store, no-cache, must-revalidate");  // HTTP/1.1
//header("Pragma: no-cache");  

$ship = $_SESSION['shipping'];

$shipMethod = array(
		"Ground, 8-10 days",  	 
		"3-day select",
		"Second day air",
		"Next day air",
		"In Stork Pickup"
		);


//echo "method is ".$_SESSION['shipmethod'];

if( !$smethod ){
	
	if( $_SESSION['shipmethod'] ){
		
	 // echo "setting method to session variable";
		$smethod = $_SESSION['shipmethod'];	
	
	}
	else{
	//echo "setting method to 0";
		$smethod = 0;
	}
}


		
//check to see if it's a store pickup
if( $smethod == 5){
	
	//zero out all shipping charges and handling charges
	$shipsurcharge 	 = 0;
	$ship 			 = 0;
	$weight 		 = 0;
	$handling 		 = 0;
	
	$_SESSION['shipping'] 			= 0;
	$_SESSION['shippingsurcharge'] 	= 0;
	$_SESSION['totalshipping']		= 0;
	
	$shippingOptions = '<option selected = "selected" data-method = "5" value="0">In store Pickup: $0.00</option>';
	
}	

else{
	
	//if a zipcode is assigned, get surcharges
	if( $zip && $zip != '' ){
		//echo $zip;
		//get estimate of order weight
		if( !$weight ){ $weight = orderWeight(); }
			
			$_SESSION['orderweight'] = $weight;
			include_once("FedexRateQuote.php");
  
			if( $state == "LA" ){ 
				
				$surcharge[0] = 4;	
			
			}
	}

	else{
		
		$surcharge[0] = 4;
		$surcharge[1] = 10;
		$surcharge[2] = 27;
		$surcharge[3] = 53;
		
	}

	if( !$shipsurcharge ){
		
		$shipsurcharge = 0;
	
	}
		
		//check if large item is on invoice, apply surcharge
		if( $hasLargeItem == 1 ){
			
			for($i=0;$i<4;$i++){
				$surcharge[$i] = $surcharge[$i] * (1 + $largeItemSurcharge);
			}
		
		}

	//generate option list
		$shippingOptions.='
		
						<option data-method="0" value="'.($surcharge[0]).'"';
						if($smethod==0){
							$shippingOptions.=' selected="selected"';
						}						
						$shippingOptions.='>Ground (8-10 days): $'.number_format($surcharge[0]+$ship,2).'</option>
						<option data-method="1" value="'.($surcharge[1]).'"';
						if($smethod==1){
							$shippingOptions.=' selected="selected"';
							$relativecost='';
						}
						$shippingOptions.='>3-Day Select: $'.number_format($surcharge[1]+$ship,2).'</option>
						<option data-method="2" value="'.($surcharge[2]).'"';
						
						if($smethod==2){
							$shippingOptions.=' selected="selected"';
							$relativecost='';
						}
						
						$shippingOptions.='>2nd Day Air: $'.number_format($surcharge[2]+$ship,"2").'</option>
						<option data-method="3" value="'.($surcharge[3]).'"';
						
						if($smethod==3){
							$shippingOptions.=' selected="selected"';
							$relativecost='';
						}					
						$shippingOptions.='>Next-Day Air: $'.number_format($surcharge[3]+$ship,"2").'</option>
						';



	$handling 		= $_SESSION['shipping'];
	$shipsurcharge 	= $surcharge[$smethod];

}

function orderWeight(){
	
global $itemCount;
global $db;
global $hasLargeItem;

	$item=explode('&',substr($_COOKIE['items'],1));
	foreach($item as $v){
	 $i=explode(':',$v);

	 if(substr($i[0],0,2)!="gc"){
		$query=$db->prepare("SELECT * from inventory where id=:id");//
		$query->bindParam(":id",$i[0],PDO::PARAM_INT);
		$query->execute();
		$result=$query->fetchAll();
		$r=$result[0];
		//$r=mysql_fetch_assoc($query);	 
		$orderweight=$orderweight+itemWeight($r['weight'],$r['category'],$i[1],$r['retail']);
		
		//this is not related to the larger function but it's the most efficient way to implement things
		if(strpos($r['item']," TRAY ")>0) { 
				$hasLargeItem=1;
		}
		
		$itemCount++;
		
	 }

	}

	return $orderweight;
}

function itemWeight($weight,$category,$quantity,$price){

//this function will return standard oz weight
	$category=strtolower($category);
	
	if($weight!=0){
		$weight=$weight;		
	}
	else{
		if($category!='h'){
			$weight=1;
		}
		else{
		//estimate troy oz based on price and spot price of silver
			$spotprice=16;
			$weight=$price/$spotprice;
		}
	}
	
//convert to standard ounce
if($category!='cp' && $category!='stp' && $category!=''){
 $weight = $weight*1.09714286;
}

 $weight = $weight*$quantity;
	
	return $weight;
}

/*
	
	if zipcode isn't entered, retrieve default values
	
*/





//set session and cookie data

$_SESSION['shippingrates'] 		= json_encode($surcharge);
$_SESSION['estDeliveryDates'] 	= json_encode($deliveryMessage);

if($smethod == 5){
	
		$_SESSION['shippingsurcharge'] 	= 0;
		$_SESSION['totalshipping']		= 0;
	
	}
	else{
		$_SESSION['shippingsurcharge'] 	= $shipsurcharge;
		$_SESSION['totalshipping']		= $shipsurcharge + $handling;
	}

setcookie("shipping",$handling."::".$shipsurcharge."::".$shipMethod[$smethod],time()+3600);
setcookie("items",$_COOKIE['items'],time()-60,'/');
setcookie("items",$_COOKIE['items'],0,'/');

//return shipping options
echo $shippingOptions;

if (strstr($HTTP_SERVER_VARS['HTTP_ACCEPT_ENCODING'], 'gzip')){flush();}

ob_flush();

?>
   