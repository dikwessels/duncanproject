<?php 
session_name('checkout');
session_start();
ob_start();

require("GzipCompress.php");
include("/connect/mysql_connect.php");
include("checkoutCalcsNew.php");

//include_once("/scripts/fedex/ShippingCalculator.php");
include_once("/scripts/fedex/zipcodes.php");

$surcharge=array();

//header("Cache-Control: no-store, no-cache, must-revalidate");  // HTTP/1.1
//header("Pragma: no-cache");  
setcookie("items",$_COOKIE['items'],0,'/');
extract($_POST);
extract($_GET);

$shipMethod=array(
		"Ground, 8-10 days",  	 
		"3-day select",
		"Second day air",
		"Next day air"
		);
		
if($zip && $zip!=''){
//echo $zip;
 if(!$weight){$weight=orderWeight();}
	//insert code to get state from zip
	//getShippingEstimate();
	//echo "requesting rate quote<br>";
    include_once("RateWebServiceClient.php");
   
   if($state="LA"){ 
    $surcharge[0]=4;	
   }
}

else{
	$surcharge[0]=4;
	$surcharge[1]=10;
	$surcharge[2]=27;
	$surcharge[3]=53;
}

if(!$shipsurcharge){$shipsurcharge=0;}


function orderWeight(){

	$item=explode('&',substr($_COOKIE['items'],1));
	foreach($item as $v){
	 $i=explode(':',$v);

	 if(substr($i[0],0,2)!="gc"){
		$query=mysql_query("SELECT * from inventory where id=$i[0]");
		$r=mysql_fetch_assoc($query);	 
		$orderweight=$orderweight+itemWeight($r['weight'],$r['category'],$i[1],$r['retail']);

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
 $weight=$weight*1.09714286;
}

 $weight=$weight*$quantity;
	
	return $weight;
}

/*
	
	if zipcode isn't entered, retrieve default values
	
*/
$orderdetails.='
					<select id="shipping-method" class="medium-input" name="shippingMethod">
						<option data-method="0" value="'.($surcharge[0]).'"';
						if($smethod==0){
							$orderdetails.=' selected="selected"';
						}						
						$orderdetails.='>$'.($surcharge[0]).'.00 - Ground (8-10 days)</option>
						<option data-method="1" value="'.($surcharge[1]).'"';
						if($smethod==1){
							$orderdetails.=' selected="selected"';
							$relativecost='';
						}
						$orderdetails.='>$'.($surcharge[1]).'.00 - 3-Day Select</option>
						<option data-method="2" value="'.($surcharge[2]).'"';
						if($smethod==2){
							$orderdetails.=' selected="selected"';
							$relativecost='';
						}
						$orderdetails.='>$'.($surcharge[2]).'.00 - 2nd-Day Air </option>
						<option data-method="3" value="'.($surcharge[3]).'"';
						
						if($smethod==3){
							$orderdetails.=' selected="selected"';
							$relativecost='';
						}					
						$orderdetails.='>$'.($surcharge[3]).'.00 - Next-Day Air</option>
					</select>
';




//$_SESSION['paypalitems']=$paypalItemList;
//$_SESSION['subtotal']=$sub;
//$_SESSION['invoicetotal']=$total;
//$_SESSION['shipping']=$handling;
//$_SESSION['shippingsurcharge']=$shipsurcharge;
//$_SESSION['shippingDescription']=$shipMethod[$shipsurcharge];
//$_SESSION['shippingzip']=$zip;
//$_SESSION['orderweight']=$orderweight;
//$_SESSION['shipmethod']=$smethod;

$handling=$_SESSION['shipping'];
$shipsurcharge=$surcharge[$smethod];

$_SESSION['shippingrates']=json_encode($surcharge);

setcookie("shipping",$handling."::".$shipsurcharge."::".$shipMethod[$smethod],time()+3600);

setcookie("items",$_COOKIE['items'],time()-60,'/');
setcookie("items",$_COOKIE['items'],0,'/');

//return new select box
echo $orderdetails;

if (strstr($HTTP_SERVER_VARS['HTTP_ACCEPT_ENCODING'], 'gzip')){flush();}

ob_flush();

?>
   