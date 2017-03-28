<?php
ini_set("display_errors","1");

session_name('checkout');

session_start();

ob_start();

extract($_GET);

extract($_POST);

//include_once("getsalestax.php");

//this script solely updates the invoice based on a shipping method change
//use session variables for security
//echo "hello";

$shipMethod=array(
		"Ground, 1-5 business days",  	 
		"3-day select",
		"Second day air",
		"Next day air",
		"In-store Pickup"
		);

$sub		=	$_SESSION['subtotal'];
$handling	=	$_SESSION['shipping'];	

//echo $_SESSION['salestax'];
//calculate

if($smethod == 5){
	
	$shipsurcharge = 0;
}
else{

	//make sure shipping surcharge is minimum value of 4
	if( $shipsurcharge < 4 ){
		$shipsurcharge = 4;	
	}

}

$newShipTotal 	=	$handling + $shipsurcharge;
$newTotal		=	$sub + $newShipTotal + $_SESSION['salestax'];
$PPTax			=	$_SESSION['salestaxPP'];
$PPShipping		=	$_SESSION['shippingPP'];
$PPItems		=	$_SESSION['invoiceItemsPP'];	

$paypalItemList=$_SESSION['paypalitems'];

$paypalItemArray=explode('&', $paypalItemList);
$i=0;
//change the shipping amount


$PPShippingArray=explode("&", $PPShipping);

foreach($PPShippingArray as $pi){
   	if(strpos($pi, "Shipping%2C+Handling+and+Insurance")>0){
		//replace the shipping information with new information
		$subpi	=	explode('=', $pi);	
		$newpi	=	$subpi[0]."=Shipping%2C+Handling+and+Insurance%3A+".urlencode($shipMethod[$smethod]);
		$paypalItemArray[$i] = $newpi;
		
		//update next element with amount
		$subpi	=	explode('=',$PPShippingArray[$i+1]);
		$newpi	=	$subpi[0]."=".number_format($newShipTotal,2,".");
		
		$PPShippingArray[$i+1]=$newpi;
		
	}
	$i++;

}

$PPShipping		=	implode("&", $PPShippingArray);

$newpaypalList	=	$PPItems.$PPTax.$PPShipping;//List.implode("&", $paypalItemArray);

$_SESSION['subtotal']			=	$sub;
$_SESSION['invoicetotal']		=	$newTotal;
$_SESSION['paypalitems']		=	$newpaypalList;
$_SESSION['shippingDescription']=	$shipMethod[$smethod];
$_SESSION['shipmethod']			=	$smethod;
$_SESSION['shippingsurcharge']	=	$shipsurcharge;
$_SESSION['shipping']			=	$handling;
$_SESSION['orderweight']		=	$orderweight;
$_SESSION['totalshipping']		=	$shipsurcharge + $handling;

setcookie("shipping",$handling."::".$shipsurcharge."::".$shipMethod[$smethod],time()+3600);

//$newvalues['salestax']=$_SESSION['salestax'];
$newvalues['shipping-total']	=	$newShipTotal;
$newvalues['invoice-shipping']	=	$newShipTotal;
$newvalues['temp-total']		=	$newTotal;



//$newvalues['pp-invoice-items']=$newpaypalList;
//$newvalues['invoice-total']		=	$newTotal;

//$result['orderTotals']=json_encode($newvalues);
echo json_encode($newvalues);

//if the shipping method is changed, simply change the following
//total

/*L_PAYMENTREQUEST_0_QTY0=1&L_PAYMENTREQUEST_0_AMT0=225.00&L_PAYMENTREQUEST_0_NAME0=+Tiffany+Baby+Spoon+-+Curved+Handle&L_PAYMENTREQUEST_0_NAME1=Shipping%2C+Handling+and+Insurance%3A+3-day+select&L_PAYMENTREQUEST_0_AMT1=23.00
*/

//print_r($_SESSION);
?>