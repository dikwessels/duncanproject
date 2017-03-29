<?php
	session_name('checkout');
	session_start();
	ob_start();

//includes 


include_once('GiftCard.php');

include_once('Invoice.php');

include_once("connect/mysql_pdo_connect.php");

include_once("checkoutSettings.php");

include("sandbox.order-confirmation-functions.php");

//variable declarations 
global $giftCardAmt;
global $giftCardCode;
global $response;

//get request
extract($_GET);
extract($_POST);

$confirmationMessage = "";
$giftcardContent="";
$giftCardAmt=0;
	
	
if($_SESSION['redeemedGiftCards']!=""){
	foreach($_SESSION['redeemedGiftCards'] as $k=>$v){	
			$giftCardAmt=$giftCardAmt+$v;
		}
	}
	
if($giftCardAmt==$_SESSION['invoicetotal']){
		$giftcardtransaction=1;
}

if( $giftcardtransaction == 1 ){
  //this section triggers in one of two cases
  // 1) testing purposes
  // 2) transaction that was paid for entirely with a gift card
  
   $_SESSION['payflowresponse'] = createPaymentResponseFromSession();
   
   $customerEmail = urldecode($_SESSION['payflowresponse']['EMAIL']);

}
else{
	//if the token is set, then it's an express checkout 
	if( isset($_GET['token']) ){
	
		include_once("/home/asyoulik/public_html/expresscheckout.config.php");
		include_once("PayPalExpressPayment.php");
		
		// then this was an express checkout return
		$token=$_GET['token'];
		$payerID=$_GET['PayerID'];
		
		$paypal = new PayPalExpressPayment();
		
		//DO PAYMENT
		$padata="&TOKEN=".urlencode($token).
	  			"&PAYERID=".urlencode($payerID).
	  			"&PAYMENTREQUEST_0_AMT=".urlencode($_SESSION['invoicetotal']);
  
	  	$response = $paypal->PPHttpPost('DoExpressCheckoutPayment', $padata, $PayPalApiUsername,  $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);
		
		//if Payment is successful, retreive details
		if( strtoupper($response['ACK']) == "SUCCESS" ){
			
			$transactionID = urlencode($response['PAYMENTINFO_0_TRANSACTIONID']);
	
			//$paypal=new PayPalExpressPayment();
		
			$nvpstr = "&TRANSACTIONID=".$transactionID;
	   
			$_SESSION['payflowresponse'] = $paypal->PPHttpPost('GetTransactionDetails', $nvpstr, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);

	   }

	}
	
	//it's a payflow response
	else{
	
	echo '<div id="interstitial" style="text-align:left;font-size:.8rem;padding:5px;font-family:arial;color:#666;position:absolute;top:0px;left:0px;background-color: white;z-index:100;">
	<img src="/images/ajax-loader.gif"><br>
	Please wait while we finish processing your order.<br>
	Please do not hit the refresh or back button on your browser at this time.
</div>';

	
	require_once('PayflowNVPAPI.php');	 
	
		//Check if we just returned inside the iframe.  
		//If so, store payflow response and redirect parent window with javascript.
		if (isset($_POST['RESULT']) || isset($_GET['RESULT']) ) {
			$_SESSION['payflowresponse'] = array_merge($_GET, $_POST);
			echo '<script type="text/javascript">window.top.location.href = "' . script_url() .  '";</script>';
			exit(0);
		}
	
	}

}

//process any response

if(!empty($_SESSION['payflowresponse'])) {


	//assign payflow response to local array
	$response=$_SESSION['payflowresponse'];

	unset($_SESSION['payflowresponse']);
  
	$success=( $response['RESULT']==0 );

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
				
				$gcResponse = generateGiftCards($_SESSION['giftcards'],$custNum);
			
				$s = explode(":",$gcResponse);
				$newCode = $s[0];
				$tempCode = $s[1];
				$giftCardCodes[] = $newCode;
				$_COOKIE['items'] = str_replace($tempCode,$newCode, $_COOKIE['items']);
				
			} 
			
			//create and save invoice
			$invoice=new Invoice();
			
			$invoice->save($response,0);
			
			$invoice->load($PayPalMode);
			
			if(count($giftCardCodes)>0){
				updateGiftCardCodesWithInvoiceNumber($giftCardCodes,$invoice->invoiceID);
			}
			
			//show confirmationon screen and send email
			$invoice->generate_receipt();
			
			//confirmation message will be shown below
			$confirmationMessage = $invoice->screenReceipt;
			
			//$invoice->display_receipt();
			
			$invoice->email_receipt();
	
			//CLEAR COOKIES AND SESSION
			if( $PayPalMode == "live" ){
    			clearSession();
			}
			
            unset($invoice);
		}
		
		else{
    
		//error handling will actually be done by the checkout page so this is largely useless
		$confirmationMessage =  'Your transaction was declined due to an mismatch in the billing address you entered and the billing address on file with your credit card company.<br>To amend your billing information, please click this link: <a href="https://www.asyoulikeitsilvershop.com/checkout.php">Edit Billing Information</a>';
		}
	
	}

//there was no response returned, the transaction completely failed

else{
   	$confirmationMessage = "<span style='font-family:sans-serif;'>Transaction failed! Please try again with another payment method.</span>";
   	}
  

?>

<!DOCTYPE html>
<html>
<head>
<title>Order Confirmation | As You Like It Silver Shop, New Orleans, Louisiana</title>
<meta charset="UTF-8"/>
<meta name="description" content="As You Like It Silver Shop in New Orleans Louisiana specializes in silver flatware and holloware in active, inactive and obsolete patterns."/>
<meta name="keywords" content="customer privacy policy, selling your silver, purchasing information, sterling silver, sterling flatware, silver flatware, antique silver, silver tableware, antique sterling, replacement silver, silver repair, corporate gifts, wedding gifts, silver identification, cleaning silver"/>

<!--ogTags-->
<base href="//localhost:8888">
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="js/jquery.jconfirm-1.0.min.js"></script>
<link rel="stylesheet" href="/css/dropdown/imports.css">
<link rel="stylesheet" href="/ayliss_style.css" type="text/css">
<link rel="stylesheet" href="/ayliss_style_uni.css" type="text/css">

<? include("js/analytics.html"); ?>


</head>

<body class="sub">

<? include_once("analyticstracking.php"); ?>

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

  		<? 
	  		echo $confirmationMessage; 
	  		
	  		echo "<script type='text/javascript'>
			  		$('#interstitial').fadeOut();
			  	</script>";
	  		
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




