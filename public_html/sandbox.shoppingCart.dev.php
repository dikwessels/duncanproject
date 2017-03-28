
<?php
//begin session
session_name('checkout');
session_start();
ini_set("display_errors","1");

ob_start();

include("/home/asyoulik/public_html/staticHTMLFunctions.php");
include("/home/asyoulik/public_html/categoryArrays.php");

//set this to 0 for live
$sandbox=1;

include("paypalsettings.php");
include("expresscheckout.config.php");
include("expressCheckOut.php");

if($_POST || $_GET){

	extract($_POST);
	extract($_GET);

	switch($action){
	case "SetExpressCheckout":
	//save shipping preferences to session variables
	//$itemlist.
	//'&'.urlencode($_SESSION['paypalitems']). 

		 $ItemTotalPrice=$ItemPrice=$PAYMENTREQUEST_0_AMT;
		 $padata ='&CURRENCY='.urlencode($PayPalCurrencyCode).
                '&PAYMENTACTION=Sale'.
                '&ALLOWNOTE=1'.
                '&PAYMENTREQUEST_0_CURRENCYCODE='.urlencode($PayPalCurrencyCode).
                '&PAYMENTREQUEST_0_AMT='.urlencode($ItemTotalPrice).
				'&AMT='.urlencode($ItemTotalPrice).
				'&'.$itemlist.
                '&useraction=commit'.
                '&HDRIMG=https://www.asyoulikeitsilvershop.com/images/AYLISSPAYPALBANNER.png'.
                '&HDRBACKCOLOR=A27177'.
                '&PAYFLOWCOLOR=FFFFFF'.
                '&RETURNURL='.urlencode($PayPalReturnURL ).
                '&CANCELURL='.urlencode($PayPalCancelURL);

        //We need to execute the "SetExpressCheckOut" method to obtain paypal token
        $paypal= new MyPayPal();
        $httpParsedResponseAr = $paypal->PPHttpPost('SetExpressCheckout', $padata, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);

        //Respond according to message we receive from Paypal
        if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"]))
        {

                // If successful set some session variable we need later when user is redirected back to page from paypal.
                $_SESSION['itemprice'] =  $ItemPrice;
                $_SESSION['totalamount'] = $ItemTotalPrice;
                $_SESSION['itemName'] =  $ItemName;
                $_SESSION['itemNo'] =  $ItemNumber;
                $_SESSION['itemQTY'] =  $ItemQty;

                if($PayPalMode=='sandbox')
                {
                    $paypalmode     =   '.sandbox';
                }
                else
                {
                    $paypalmode     =   '';
                }
                //Redirect user to PayPal store with Token received.
                $paypalurl ='https://www'.$paypalmode.'.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token='.$httpParsedResponseAr["TOKEN"].'';
                header('Location: '.$paypalurl);

        }else{
            //Show error message
            echo '<div style="color:red"><b>Error : </b>'.urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]).'</div>';
            echo '<pre>';
            print_r($httpParsedResponseAr);
            echo '</pre>';
        }

	break;
	
	
}

}

?>

<!DOCTYPE html>
<html>

<head>
<title>Your Shopping Cart | As You Like It Silver Shop, New Orleans, Louisiana</title>
<meta charset="UTF-8"/>
<meta name="description" content="As You Like It Silver Shop in New Orleans Louisiana specializes in silver flatware and holloware in active, inactive and obsolete patterns." />
<meta name="keywords" content="sterling silver, sterling flatware, silver flatware, antique silver, silver tableware, antique sterling, replacement silver, silver repair, corporate gifts, wedding gifts, silver identification, cleaning silver" />
<?php 
	
	if(isset($_SERVER['HTTPS'])){
		if($_SERVER['HTTPS']!=""){
			$http="https:";
		}
		else{
			$http="http:";
		}
	}

	echo "<base href='$http//www.asyoulikeitsilvershop.com/'>";

?>

<script type="text/javascript" src="/js/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="/js/sandbox.shopping-cart-min.js"></script>

<link rel="stylesheet" href="/css/dropdown/imports.css">
<link rel="stylesheet" href="ayliss_style.css" type="text/css">
<link rel="stylesheet" href="ayliss_style_uni.css" type="text/css">
<style>
#button-overlay{
	position: absolute;
	height: 100%;
	width: 100%;
	background: rgba(255,255,255,.5);
	display:none;
	z-index: 10;
}
</style>

<script type="text/javascript">
	 $.showOrderSummary();
</script>


<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-31581272-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>

</head>

<body class="sub">
<div id="container" class="row fullWidth nopad">
<!-- begin page head -->
<div class="pageHead" id="<!--pageHeadID-->">

<div id="contactInfo">
Questions?
	<a href="contact.php" class="contactLink">Contact Us</a>
	1-800-828-2311	
</div>

  <!-- page Header image -->
  <div class="pageHeaderImage" id="defaultPageHead">
  
   <div class="row centered" id="mobilePageHeader">
  As You Like It Silver Shop
	  <span id="mobileDescription"> 
		  Antique Silver Flatware, Hollowware, Jewelry, Baby Silver, Repairs
		  </span>
  </div>

    <img class="pageBanner" src="/images/ayliss_title_r.jpg" alt="Silver Pattern Guide, As You Like it Silver Shop" title="Silver Pattern Guide, As You Like It Silver Shop">
  </div>  
   <!-- end page header image -->

<!-- begin other links -->

<? 
	$otherlinks=file_get_contents("/home/asyoulik/public_html/otherlinks.php");
	echo $otherlinks; 
?>


 <!--end other links -->

<!-- begin category links -->
<div class="categoryLinksContainer" id="defaultCatLinks">
  <?
  	$catLinks=include("/home/asyoulik/public_html/categoryLinks.php");
  	echo $catLinks; 
  ?>
  <!--catLinks-->
</div>
<!-- end category links -->

</div>
<!-- end page head -->

<!-- begin main content -->

<div class="mainContent">
  <!-- begin main content head with h1 -->
  <div class="mainContentHead">
 
    <div class="titleContainer" id="defaultH1Container">
        <h1 class="h1PageCatTitle" id="defaultH1">Shopping Cart</h1>
    </div>
  
  <div id="thawteseal" class="pageCatImage" style="text-align:right;top:2px;background-color:white;" title="Click to Verify - This site chose Thawte SSL for secure e-commerce and confidential communications.">
<script type="text/javascript" src="https://seal.thawte.com/getthawteseal?host_name=www.asyoulikeitsilvershop.com&amp;size=S&amp;lang=en"></script>
</div>

  
 </div>

<!-- end main content head with h1 -->

 <div class="searchResults">
 <div class="row nopad">
 <?
 
if($action=="A"){
	echo "<div class='row'>
			<div class='cell twoColumns'></div>
			<div id='addition-confirmation'  class='cell fourteenColumns'>
				$message
			</div>
		</div>";
}
?>
<div id="invoice-items">
	
	<div class="ajax-overlay" style="display:inline;">
 		<div class="cell sixteenColumns centered">
				    <img src="/images/resultsLoader.gif"><br>
				    Loading invoice...
	    </div>
</div>

</div>
</div>

 </div>
      <!--searchResults-->
     

<footer>
 <div id="thawteseal" style="text-align:center;" title="Click to Verify - This site chose Thawte SSL for secure e-commerce and confidential communications.">
<div><script type="text/javascript" src="https://seal.thawte.com/getthawteseal?host_name=www.asyoulikeitsilvershop.com&amp;size=S&amp;lang=en"></script></div>
<div><a href="http://www.thawte.com/ssl-certificates/" target="_blank" style="color:#000000; text-decoration:none; font:bold 10px arial,sans-serif; margin:0px; padding:0px;">ABOUT SSL CERTIFICATES</a></div>
</div>
 
   <?
   	$c=include("copyright.php");
   	echo $c;
   ?>
    </footer>	</div>    
</body>
</html>



<? ob_flush(); ?>
