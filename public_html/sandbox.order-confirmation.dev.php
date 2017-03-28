<?php
	session_name('checkout');
	session_start();
	ob_start();

?>

<!DOCTYPE html>
<html>
<head>
<title>Order Confirmation | As You Like It Silver Shop, New Orleans, Louisiana</title>
<meta charset="UTF-8"/>
<meta name="description" content="As You Like It Silver Shop in New Orleans Louisiana specializes in silver flatware and holloware in active, inactive and obsolete patterns."/>
<meta name="keywords" content="customer privacy policy, selling your silver, purchasing information, sterling silver, sterling flatware, silver flatware, antique silver, silver tableware, antique sterling, replacement silver, silver repair, corporate gifts, wedding gifts, silver identification, cleaning silver"/>

<!--ogTags-->
<base href="//www.asyoulikeitsilvershop.com/">
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="js/jquery.jconfirm-1.0.min.js"></script>
<link rel="stylesheet" href="/css/dropdown/imports.css">
<link rel="stylesheet" href="/ayliss_style.css" type="text/css">
<link rel="stylesheet" href="/ayliss_style_uni.css" type="text/css">

<? include("/home/asyoulik/public_html/js/analytics.html"); ?>

</head>

<body class="sub">
<div id="container">
<!-- begin page head -->
<div class="pageHead" id="defaultPageHead">
<!-- page Header image -->
  <div class="pageHeaderImage row nopad" id="<!--pageHeadImageID-->">
  <div class="row centered" id="mobilePageHeader">
  As You Like It Silver Shop
	  <span id="mobileDescription"> 
		  Antique Silver Flatware, Hollowware, Jewelry, Baby Silver, Repairs
		  </span>
  </div>
  
 <img class="pageBanner" src="/images/ayliss_title_r.jpg" alt="<!--pageHeadImageAlt-->" title="<!--pageHeadImageTitle-->">
 
 <div id="contactInfo" class="cell eightColumns">
	<a href="contact.php" class="contactLink">Contact Us</a>
	1-800-828-2311	
</div>

  </div>  
   <!-- end page header image -->

<!-- begin other links -->
<? 
include("otherlinks.php");
?>
 <!--end other links -->

<!-- begin category links -->
<div class="categoryLinksContainer" id="defaultCatLinks">
  <? 
   include("categoryArrays.php");
  	$c=include("categoryLinks.php");
    echo $c;
   ?> 

</div>
<!-- end category links -->
</div>
<!-- end page head -->

<div class="mainContent">
  <!-- begin main content head with h1 -->
  
  <div class="mainContentHead fullWidth" id="defaultH1Container">
    <div class="titleContainer">
     <h1 class="h1PageCatTitle" id="defaultH1">Your Order Has Been Received</h1>
     
     <!--breadCrumb-->
    </div>
    <div id="defaultImage" class="pageCatImage"></div>
  </div>
 
  <div class="row">
  	<div class="cell twoColumns"></div>
  	<div class="cell fourteenColumns">
  		<ul class="horizontal ordersteps">
			<li id="step-1" class="orderstep">1. Shipping & Billing Information</li>
			<li id="step-2" class="orderstep">2. Review Order and Submit Payment</li>
			<li id="step-3" class="orderstep current">3. Order Confirmation</li>
		</ul>
  	</div>
 </div>
 
 
  <div class="row">
  	<div class="cell threeColumns"></div>
  	<div class="cell thirteenColumns" id="confirmation-content">
  	
 <?php 

include_once('GiftCard.php');

include_once('Invoice.php');

include_once("/home/asyoulik/connect/mysql_pdo_connect.php");

include("/home/asyoulik/public_html/sandbox.order-confirmation-functions.php");

ini_set("display_errors",1);

global $giftCardAmt;
global $giftCardCode;
global $response;
global $environment;
global $testing;

$environment="live";

extract($_GET);
extract($_POST);

//echo "testing state:$testing";

	$giftcardContent="";
	$giftCardAmt=0;
	
//echo "testing: $testing";

if($_SESSION['redeemedGiftCards']!=""){
	foreach($_SESSION['redeemedGiftCards'] as $k=>$v){	
			$giftCardAmt=$giftCardAmt+$v;
		}
	}
	
	if($giftCardAmt==$_SESSION['invoicetotal']){
		$giftcardtransaction=1;
	}

if($giftcardtransaction==1){
  //this section triggers in one of two cases
  // 1) testing purposes
  // 2) transaction that was paid for entirely with a gift card
  
  $response = createPaymentResponseFromSession();
  $customerEmail=urldecode($response['EMAIL']);
  
  //set payflowresponse to 1 for automatic success because there was no payflow call
  $_SESSION['payflowresponse']=1;

}
else{
	//attempt to get paypal info
	include_once("paypalsettings.php");
	require_once('PayflowNVPAPI.php');	 
}

//check for response
if(!empty($_SESSION['payflowresponse'])) {
	
	if($giftcardtransaction==0 && $testing==0){
		$response= $_SESSION['payflowresponse'];
	}
	
	unset($_SESSION['payflowresponse']);
  
	$success=($response['RESULT']==0);

		if($success){
		
			if($_SESSION['redeemedGiftCards']!=""){
				
				foreach($_SESSION['redeemedGiftCards'] as $code=>$value){
					//update giftcard table 
					updateGiftCardBalance($code,$value,$custNum);
				}
			
			}

			//update inventory table
			updateInventory();

			//GENERATE ANY GIFT CARDS
			if(isset($_SESSION['giftcards'])&& $_SESSION['giftcards']){
				//echo "hello";
				//return code and update the cookie with the code
				//generateGiftCards returns a code pair in form of newCode:tempCode
				//update the cookie data to store the new code in the old items information
				
				$gcResponse=generateGiftCards($_SESSION['giftcards'],$custNum);
				//echo $gcResponse;
				$s=explode(":",$gcResponse);
				$newCode=$s[0];
				$tempCode=$s[1];
				$giftCardCodes[]=$newCode;
				$_COOKIE['items']=str_replace($tempCode,$newCode, $_COOKIE['items']);
				
			} 
			
			//create and save invoice
			$invoice=new Invoice();
			
			$invoice->save($response,0);
			
			$invoice->load($testing);
			
			if(count($giftCardCodes)>0){
				updateGiftCardCodesWithInvoiceNumber($giftCardCodes,$invoice->invoiceID);
			}
			
			//show confirmationon screen and send email
			$invoice->generate_receipt();
			
			$invoice->display_receipt();
			
			$invoice->email_receipt();
	
			//CLEAR COOKIES AND SESSION
			if($testing!=1){
    			clearSession();
			}
			
            unset($invoice);
		}
		
		else{
    
		//error handling will actually be done by the checkout page so this is largely useless
		echo 'Your transaction was declined due to an mismatch in the billing address you entered and the billing address on file with your credit card company.<br>To amend your billing information, please click this link: <a href="https://www.asyoulikeitsilvershop.com/checkoutV3New.php">Edit Billing Information</a>';
		}
	
	}

else{
   	echo "<span style='font-family:sans-serif;'>Transaction failed! Please try again with another payment method.</span>";
   	}
  

?>

  	</div>
  </div>
   
    <!-- end main content -->

</div>

 	<footer><?	$c=include("copyright.php");	echo $c;	?>
    </footer>	
    
</div>
<!-- end container -->

</body>
</html>

<? ob_flush(); ?>




