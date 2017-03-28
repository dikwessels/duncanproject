<!DOCTYPE html>
<?php 
session_name('checkout');
session_start();
include_once("paypalsettings.php");

global $environment;
global $sandbox;

if($sandbox==1){
	$sandboxURL="sandbox.";
}
else{
	$sandboxURL="";
}
require_once('PayflowNVPAPI.php');
//ini_set("display_errors","1");
/*foreach($_POST as $k=>$v){	
echo $k."=>".$v."<br>";
}
*/
extract($_POST);

		$_SESSION['cardemail']=$cardemail;
		$_SESSION['fname']=$fname;
		$_SESSION['lname']=$lname;
		$_SESSION['address1']=$address1;
		$_SESSION['address2']=$address2;
		$_SESSION['city']=$city;
		$_SESSION['state']=$state;
		$_SESSION['zip']=$zip;
		$_SESSION['country']=$country;
		$_SESSION['phone']=$phone;
		if($cardaddress)$_SESSION['cardaddress']=$cardaddress; else $_SESSION['cardaddress']=$address1;						$_SESSION['cardfname']=$billtofname?$billtofname:$fname;
		$_SESSION['cardlname']=$billtolname?$billtolname:$lname;
		$_SESSION['cardcity']=$cardcity?$cardcity:$city;
		$_SESSION['cardstate']=$cardstate?$cardstate:$state;
		$_SESSION['cardzip']=$cardzip?$cardzip:$zip;
		$_SESSION['cardcountry']=$cardcountry?$cardcountry:$country;
		$_SESSION['cardphone']=$cardphone?$cardphone:$phone;
		$_SESSION['note']=$note;
		$_SESSION['giftwrap']=$giftwrap;
		$_SESSION['sameaddress']=$sameaddress;
	

$paymentamount=urldecode($_SESSION['invoicetotal']);

foreach($_SESSION['redeemedGiftCards'] as $k=>$v){
	  //if($_SESSION['redeem']){
		  $paymentamount=$paymentamount-$v;
	  //}
}
	  
 $request = array(
  "PARTNER" => "PayPal",
  "VENDOR" => "AsYouLikeIt925",
  "USER" => "AsYouLikeIt925",
  "PWD" => "aRgent529", 
  "TRXTYPE" => "S",
  "AMT" => $paymentamount,
  "CURRENCY" => "USD",
  "VERBOSITY" => "HIGH",
  "TENDER" => "C",
  "CREATESECURETOKEN" => "Y",
  "SECURETOKENID" => uniqid('MySecTokenID-'), //Should be unique, never used before
  "RETURNURL" =>"https://www.asyoulikeitsilvershop.com/$sandboxURL"."order-confirmation.php",
  "CANCELURL" => "https://www.asyoulikeitsilvershop.com/$sandboxURL"."checkout.php",
  "ERRORURL" => "https://www.asyoulikeitsilvershop.com/$sandboxURL"."checkout.php",
  // In practice you'd collect billing and shipping information with your own form,
// then request a secure token and display the payment iframe.
// --> See page 7 of https://cms.paypal.com/cms_content/US/en_US/files/developer/Embedded_Checkout_Design_Guide.pdf
// This example uses hardcoded values for simplicity.
  "BILLTOEMAIL"=>urldecode($_SESSION['cardemail']),
  "BILLTOFIRSTNAME" => urldecode($_SESSION['cardfname']),
  "BILLTOLASTNAME" => urldecode($_SESSION['cardlname']),
  "BILLTOSTREET" => urldecode($_SESSION['cardaddress']),
  "BILLTOCITY" => urldecode($_SESSION['cardcity']),
  "BILLTOSTATE" => urldecode($_SESSION['cardstate']),
  "BILLTOZIP" =>urldecode($_SESSION['cardzip']),
  "BILLTOCOUNTRY" => urldecode($_SESSION['cardcountry']),
  "COMMENT1"=>urldecode($_SESSION['note']),
  "COMMENT2"=>urldecode($_SESSION['giftwrap']),
  "SHIPTOFIRSTNAME" => urldecode($_SESSION['fname']),
  "SHIPTOLASTNAME" => urldecode($_SESSION['lname']),
  "SHIPTOSTREET" => urldecode($_SESSION['address1']).' '.urldecode($_SESSION['address2']),
  "SHIPTOCITY" => urldecode($_SESSION['city']),
  "SHIPTOSTATE" => urldecode($_SESSION['state']),
  "SHIPTOZIP" => urldecode($_SESSION['zip']),
  "SHIPTOCOUNTRY" => urldecode($_SESSION['country']),
  "SHIPTOPHONE"=>urldecode($_SESSION['phone']) 
);


//foreach($_SESSION as $k=>$v){
//	echo $k."=>".$v."<br>";
//}
//$environment="sandbox";
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

if($environment == "sandbox" || $environment == "pilot"){
	$mode="TEST";
	$src="pilot-payflowlink.paypal.com";
}
else {
	$mode='LIVE';
	$src="payflowlink.paypal.com";
}

$iframe="<iframe src='https://$src?SECURETOKEN=$securetoken&SECURETOKENID=$securetokenid&MODE=$mode' width='490' height='565' border='0' frameborder='0' scrolling='no' allowtransparency='true'>\n</iframe>";


echo $iframe;	
	
?>





