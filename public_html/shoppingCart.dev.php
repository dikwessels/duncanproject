
<?php
//begin session

session_name('checkout');
session_start();
ini_set("display_errors","1");

ob_start();

	include("/home/asyoulik/public_html/checkoutSettings.php");

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

<script type="text/javascript" rel = "nofollow" src="/js/jquery-1.11.1.min.js"></script>
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
.errMsgContent{
	background-color: white;
	border:1px solid #aaa;
}
</style>



<script type="text/javascript">
	
	var giftCardOnly = '';
	var hasPhoneNumber = '';
	
	$(document).ready(function(){
		
		
		$.savePhoneForExpressPayment = function(){
			
		 //save a phone number when customer opts for express payment instead of ayliss checkout
		 if( $('#contact-phone').val() !== ''){
			
			var params = $('#contact-phone').val();
			var shipMethod=$('#shipping-method>option:selected').data('method');

			$('.nodalFormElement').css('visibility','hidden');
			$('.nodalLoader').css('display','block');
			$('#nodalHeader').html('Thank you, you will now be directed to PayPal to complete your transaction...');

			$.get(
				'updateSession.php',
				"phone="+params,
				
				function(result){
					
					
					//$('#phoneErrorMessage').fadeIn();

					console.log(result);
					
					$('#express-checkout-form').submit();
					
					//$('#page-overlay').fadeOut();
					//$('#popup').fadeOut();
				
				}
			);	
		}
		
		else{
			$('#phone-err-msg').fadeIn();
		}
			
	 	};
		
		$.closeNodal = function(){
			//console.log('I was clik');
			$('#page-overlay').fadeOut();	
			$('#popup').fadeOut();
		};

		$.bindNodalButtons = function(){
		
			$('#savePhone').on('click',$.savePhoneForExpressPayment);
	
			$('.closeNodal').on('click',$.closeNodal);

	 	};

		$.bindNodalButtons();
		
		//var phoneNodalMessage = '';
		/*$.get('phoneErrMsg.html',function(result){
			phoneNodalMessage = result;
		
			$.bindNodalButtons();
		
		});
		*/
		
		$.showOrderSummary();
	
	
	});
	
	 
	 
	 
</script>


<script type="text/javascript" rel="nofollow" id="analytics">

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

<div class="overlay white" id="page-overlay"></div>

<div id="popup" style="display:none;position:absolute;top:25%;width:100%;z-index:1000">
	<div class="row fullWidth centered">
		<div id = "phoneErrorMessage" class="cell sixColumns errMsgContent">
		
			<div class="row">
				<div class="cell sixteenColumns">
						<h4 id = "nodalHeader">Please enter a contact phone number</h4>
				</div>
			</div>
			
			<div class="row centered nodalLoader" style="display:none">
				
				<div class="cell sixteenColumns">
					<img src="/images/ajax-loader-2.gif">
				</div>
			
			</div>
			
			<div class="row centered nodalFormElement">
				
			  <div class="cell eightColumns">
				<input class="medium-input" id="contact-phone">
			  </div>
			
			</div>
			
			<div class="row nodalFormElement">
			<div class="cell fourteenColumns error" style="display:none;" id="phone-err-msg">
				<span class="caption">Please enter a valid phone number</span>
			</div>
			</div>
			
			<div class="row nodalFormElement">
			<div class="cell fourteenColumns left-align">
				<span class="caption">In case we need to contact you quickly for any reason regarding your order, we ask that you enter a contact phone number.
As You Like It Silver Shop will not share or otherwise use this number.</span>
			</div>
			</div>
				
			<div class="row centered nodalFormElement">
					
				<div class="cell fiveColumns">
					<button id="savePhone">Save Phone</button>
				</div>

				<div class="cell fiveColumns">
					<button id="cancel" class="closeNodal">Cancel</button>
				</div>
		
			</div>
		
		</div>
		
		<div id = "payPalErrorMessage"  class="cell sixColumns errMsgContent" style="display:none">
		
			<div class="row">
				<div class="cell sixteenColumns">
						<h4>Form data error</h4>
						There was an error with the invoice data submitted to PayPal.<br>
						To correct this and resubmit your payment,  please click the 'Pay Now with PayPal' button.
				</div>
			</div>
				
			<div class="row centered">
					
				<div class="cell fiveColumns">
					<button id="nodalOk" class="closeNodal">Ok</button>
				</div>
		
			</div>
		
		</div>
	</div>
</div>


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
<? 
	if( $PayPalMode != "live"){
		include("checkoutNotice.php");
	}
	


	include("expresscheckout.config.php");
	include("PayPalExpressPayment.php");

	if($_POST || $_GET){

	extract($_POST);
	extract($_GET);

	switch($action){
	
	case "SetExpressCheckout":
	//this is called when the user clicks the pay with paypal button
	
	//save shipping preferences to session variables
	//$itemlist.
	//'&'.urlencode($_SESSION['paypalitems']). 

		//new way, set invoice and item data to session in case someone tries to change the form data
		 $ItemTotalPrice=$ItemPrice = $_SESSION['invoicetotal'];
		 
		 $items = $_SESSION['paypalitems'];
		 
		 //old way
		 //$ItemTotalPrice=$ItemPrice=$PAYMENTREQUEST_0_AMT;
		 //$items = $itemlist;
		 
		 $padata ='&CURRENCY='.urlencode($PayPalCurrencyCode).
                '&PAYMENTACTION=Sale'.
                '&ALLOWNOTE=1'.
                '&PAYMENTREQUEST_0_CURRENCYCODE='.urlencode($PayPalCurrencyCode).
                '&PAYMENTREQUEST_0_AMT='.urlencode($ItemTotalPrice).
				'&AMT='.urlencode($ItemTotalPrice).
				'&'.$items.
                '&useraction=commit'.
                '&HDRIMG=https://www.asyoulikeitsilvershop.com/images/AYLISSPAYPALBANNER.png'.
                '&HDRBACKCOLOR=A27177'.
                '&PAYFLOWCOLOR=FFFFFF'.
                '&RETURNURL='.urlencode($PayPalReturnURL ).
                '&CANCELURL='.urlencode($PayPalCancelURL);

        //We need to execute the "SetExpressCheckOut" method to obtain paypal token
        $paypal= new PayPalExpressPayment();
        
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

                if($PayPalMode=='testing')
                {
                    $sandboxURL		=   '.sandbox';
                }
                else
                {
                    $sandboxURL		=   '';
                }
                
                //Redirect user to PayPal store with Token received.
                $paypalurl ='https://www'.$sandboxURL.'.paypal.com/cgi-bin/webscr?cmd=_express-checkout&useraction=commit&token='.$httpParsedResponseAr["TOKEN"].'';
                header('Location: '.$paypalurl);

        }
        
        else{
	        
            
            echo "<div class='row'>
            		<div class='sixteen columns'>
            			<span class='error'>
            				An error occurred with the data submitted to PayPal. To fix this error and resubmit your payment, please click the 'Pay Now with PayPal' button. 
            			</span>
            		</div>
            	</div>";
        }

	break;
	
	
}

}
	
?>
	

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
