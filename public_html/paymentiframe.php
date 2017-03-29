<?php 
session_name('checkout');
session_start();
//ini_set("display_errors");


include_once("checkoutSettings.php");

//get PayPal Name value pairs
require_once('PayflowNVPAPI.php');

extract($_POST);

function randomString($length = 6) {
	
 $str = "";
 $characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
 $max = count($characters) - 1;
 for ($i = 0; $i < $length; $i++) {
  $rand = mt_rand(0, $max);
  $str .= $characters[$rand];
 }
 
 return $str;
 
}

			
		$_SESSION['cardemail']	=	$cardemail;
		$_SESSION['fname']		=	$fname;
		$_SESSION['lname']		=	$lname;
		$_SESSION['address1']	=	$address1;
		$_SESSION['address2']	=	$address2;
		
		//these are conditional upon whether or not it's a store pickup
		if( $_SESSION['shipmethod'] != 5 ) {
			//set the values to the form values, otherwise ignore
			$_SESSION['city']		=	$city;
			$_SESSION['state']		=	$state;
			$_SESSION['zip']		=	$zip;
			$_SESSION['country']	=	$country;
		}
		
		$_SESSION['phone']		=	$phone;
		
		if($cardaddress){
			$_SESSION['cardaddress']	=	$cardaddress;
		}
		else{
			$_SESSION['cardaddress']	=	$address1." ".$address2;
		}						
		
		
		//for in store pickups this will work out 
		$_SESSION['cardfname']	=	$billtofname?$billtofname:($cardfname?$cardfname:$fname);
		$_SESSION['cardlname']	=	$billtolname?$billtolname:($cardlname?$cardlname:$lname);
		
		$_SESSION['cardcity']	=	$cardcity?$cardcity:$city;
		$_SESSION['cardstate']	=	$cardstate?$cardstate:$state;
		$_SESSION['cardzip']	=	$cardzip?$cardzip:$zip;
		$_SESSION['cardcountry']=	$cardcountry?$cardcountry:$country;
		$_SESSION['cardphone']	=	$cardphone?$cardphone:$phone;
		$_SESSION['note']		=	$note;
		$_SESSION['giftwrap']	=	$giftwrap;
		$_SESSION['sameaddress']=	$sameaddress;


	
		$paymentamount = urldecode($_SESSION['invoicetotal']);


        echo "<script>console.log( 'Debug Objects: " . $_SESSION['redeemedGiftCards'] . "' );</script>";
		//subtract any gift cards from this payment
		foreach($_SESSION['redeemedGiftCards'] as $k => $v){
		 
		  $paymentamount = $paymentamount - $v;
		}

//generate a request token

$token = randomString(30);
//echo $token;
$_SESSION['sentToken']=$token;

$request = array(
  "PARTNER" 			=> 	"PayPal",
  "VENDOR" 				=> 	"AsYouLikeIt925",
  "USER" 				=> 	"AsYouLikeIt925",
  "PWD" 				=> 	"aRgent529", 
  "TRXTYPE" 			=> 	"S",
  "AMT" 				=> 	$paymentamount,
  "CURRENCY" 			=> 	"USD",
  "VERBOSITY" 			=> 	"HIGH",
  "TENDER" 				=> 	"C",
  "CREATESECURETOKEN" 	=> 	"Y",
  "SECURETOKENID" 		=> 	$token, //Should be unique, never used before
  "RETURNURL" 			=>	"http://localhost:8888/$sandboxURL"."order-confirmation.php",
  "CANCELURL" 			=> 	"http://localhost:8888/$sandboxURL"."checkout.php",
  "ERRORURL" 			=> 	"http://localhost:8888/$sandboxURL"."checkout.php",
  "BILLTOEMAIL"			=>	urldecode($_SESSION['cardemail']),
  "BILLTOFIRSTNAME" 	=> 	urldecode($_SESSION['cardfname']),
  "BILLTOLASTNAME" 		=> 	urldecode($_SESSION['cardlname']),
  "BILLTOSTREET" 		=> 	urldecode($_SESSION['cardaddress']),
  "BILLTOCITY" 			=> 	urldecode($_SESSION['cardcity']),
  "BILLTOSTATE" 		=> 	urldecode($_SESSION['cardstate']),
  "BILLTOZIP" 			=>	urldecode($_SESSION['cardzip']),
  "BILLTOCOUNTRY" 		=> 	urldecode($_SESSION['cardcountry']),
  "COMMENT1"			=>	urldecode($_SESSION['note']),
  "COMMENT2"			=>	urldecode($_SESSION['giftwrap']),
  "SHIPTOFIRSTNAME" 	=> 	urldecode($_SESSION['fname']),
  "SHIPTOLASTNAME" 		=> 	urldecode($_SESSION['lname']),
  "SHIPTOSTREET" 		=> 	urldecode($_SESSION['address1']).' '.urldecode($_SESSION['address2']),
  "SHIPTOCITY" 			=> 	urldecode($_SESSION['city']),
  "SHIPTOSTATE" 		=> 	urldecode($_SESSION['state']),
  "SHIPTOZIP" 			=> 	urldecode($_SESSION['zip']),
  "SHIPTOCOUNTRY" 		=> 	urldecode($_SESSION['country']),
  "SHIPTOPHONE"			=>	urldecode($_SESSION['phone']) 
);

//Run request and get the secure token response
$response = run_payflow_call($request);

if ($response['RESULT'] != 0) {
  pre($response, "Payflow call failed");
  exit(0);

} 
else{ 

  $securetoken = $response['SECURETOKEN'];
  $securetokenid = $response['SECURETOKENID'];

}

if( $PayPalMode != "live" ){
	$mode="TEST";
	$src="pilot-payflowlink.paypal.com";
}
else {
	$mode="LIVE";
	$src="payflowlink.paypal.com";
}

$iframe="<input type='hidden' name='totalCheck' value='$paymentamount'>
<iframe src='https://$src?SECURETOKEN=$securetoken&SECURETOKENID=$securetokenid&MODE=$mode' width='490' height='565' border='0' frameborder='0' scrolling='no' allowtransparency='true'>\n</iframe>";

//echo $paymentamount;

echo $iframe;	
	
?>