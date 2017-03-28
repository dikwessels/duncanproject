<?php 

ob_start();

include("expresscheckout.config.php");
include("expressCheckOut.php");
ini_set("display_errors","1");

extract($_GET);

 $paypal= new MyPayPal();
 $padata="&TOKEN=".urlencode($token);
 $PayPalMode="sandbox";
 $httpParsedResponseAr = $paypal->PPHttpPost('GetExpressCheckoutDetails', $padata, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);

if(strtoupper($httpParsedResponseAr['ACK'])=="SUCCESS"){
	$_SESSION['shipto']=$httpParsedResponseAr['PAYMENTREQUEST_0_SHIPTONAME'];
	$_SESSION['shipaddress']= $httpParsedResponseAr['PAYMENTREQUEST_0_SHIPTOSTREET'];
	$_SESSION['shipcity']=$httpParsedResponseAr['PAYMENTREQUEST_0_SHIPTOCITY'];
	$_SESSION['shipstate']=$httpParsedResponseAr['PAYMENTREQUEST_0_SHIPTOSTATE'];
	$_SESSION['shipcountry']=$httpParsedResponseAr['PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE'];
	$_SESSION['shipcountryname']=$httpParsedResponseAr['PAYMENTREQUEST_0_SHIPTOCOUNTRYNAME'];
	$_SESSION['shipzip']=$httpParsedResponseAr['PAYMENTREQUEST_0_SHIPTOZIP'];
	$_SESSION['addressID']=$httpParsedResponseAr['PAYMENTREQUEST_0_ADDRESSID'];
	$_SESSION['addressStatus']=$httpParsedResponseAr['PAYMENTREQUEST_0_ADDRESSSTATUS'];
}

foreach($_SESSION as $k=>$v){
	echo $k.": ".$v."<br>";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Check Out | As You Like It Silver Shop, New Orleans, Louisiana</title>
<meta charset="UTF-8"/>
<meta name="description" content="As You Like It Silver Shop in New Orleans Louisiana specializes in silver flatware and holloware in active, inactive and obsolete patterns."/>
<meta name="keywords" content="customer privacy policy, selling your silver, purchasing information, sterling silver, sterling flatware, silver flatware, antique silver, silver tableware, antique sterling, replacement silver, silver repair, corporate gifts, wedding gifts, silver identification, cleaning silver"/>

<!--ogTags-->
<base href="//www.asyoulikeitsilvershop.com/">
<!--
<script type="text/javascript" src="js/validate.js"></script>
-->

<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>


<!--<script type="text/javascript" src="/js/ajax.js"></script>-->
<script type="text/javascript" src="/js/images.js"></script>
<script type="text/javascript" src="/js/store.js"></script>
<script type="text/javascript" src="/js/cookie.js"></script>
<!--<script type="text/javascript" src="/js/giftRegistry.js"></script>-->
<script type="text/javascript" src="/js/suggestedItems.js"></script>
<script type="text/javascript" src="/js/share.js"></script>
<script type="text/javascript" src="/js/formvalidation.js"></script>

<link rel="stylesheet" href="/css/dropdown/imports.css">
<link rel="stylesheet" href="/ayliss_style.css" type="text/css">
<link rel="stylesheet" href="/ayliss_style_uni.css" type="text/css">

<? include("/home/asyoulik/public_html/js/analytics.html"); ?>

</head>

<body class="sub" onLoad="getItemCount();">
<!--<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>-->

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
   <!-- begin cart container --> 
  <div class="cell sevenColumns" id="cartContainer">
   <!-- end cart link -->
 </div>
 <!-- end cart container -->
 
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
     <h1 class="h1PageCatTitle" id="defaultH1">Review Order and Pay</h1>
    </div>
    <div id="defaultImage" class="pageCatImage"></div>
  </div>
<div class="cell eightColumns">

<? echo "
    <div class=\"row bold\">$_SESSION['shipto']</div>
    <div class=\"row bold\">$_SESSION['shipaddress']</div>
    <div class=\"row bold\">$_SESSION['shipcity'], $$_SESSION['shipstate'] $_SESSION['shipzip']	$_SESSION['shipcountryname']</div>";
?>
</div>  

 
 <div class="row"  id="order-summary-main" style="display:none;">
	<div id="invoice-items"></div>
	<div class="row centered">
		<div class="cell twoColumns"></div>
		<div class="cell threeColumns">
			<a href="#"><button>Resume Shopping</button></a>
		</div>
		<div class="cell fourColumns" id="check-out-button">
			<button id="check-out">Pay Now &rarr;</button><br>
			<span class="caption">Your order and payment will not be processed until you click this button</span>
		</div>
	</div>
 </div>
 
  <div class="row" id="checkout-forms" style="display:none;">	  
  	  <div class="cell twoColumns"></div>  	  
	  	<div class="cell eightColumns" id="checkout-form">
	  	<div id="transition">
		  	<div style="position: relative; width: 100%; top: 25%;">
			  	<img src="images/ajax-loader.gif">
				  	<br>Saving information...
			  	</div>
			 </div>
	  		<div id="shipping-container" class="no-display">
	  			<form id="shipping-info" name="shippingInfo">

</form>
	  		</div>
	
		  

	<div id="payment-information"></div>
	


		
		<div class="row">
			<div class="cell fourColumns"></div>
			<!--<div class="cell sixColumns">
				<button type="button" id="edit-shipping">&larr;Edit Shipping</button>
			</div>-->
			<div class="cell elevenColumns">
				<button type="button" id="review-order">Next Step &rarr; Review Your Order</button>
			</div>
		</div>


		  	</div>	  	
	  
		</div>
	  	<div class="cell fiveColumns" id="order-summary">
		  	<? include('updateOrderSummary.php'); ?>
	  	</div>
  </div>
  
    <!-- end main content -->

</div>

 <footer>
   <?
   	$c=include("copyright.php");
   	echo $c;
   ?>
    </footer>	
</div>
<!-- end container -->

</body>
</html>

<? ob_flush(); ?>




