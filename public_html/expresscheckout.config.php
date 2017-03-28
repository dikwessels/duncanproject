<?php

/* 	PayPal ExpressCheckout Settings

	$PayPalMode is set in /home/asyoulik/public_html/checkoutSettings.php

*/

if( !$PayPalMode ){ $PayPalMode  = "testing"; } // sandbox or live

if( $PayPalMode != "live" ){
	
	$PayPalApiUsername  = 'uk_sandbox_api1.asyoulikeitsilvershop.com'; //PayPal API Username
	$PayPalApiPassword  = '1374151299'; //Paypal API password
	$PayPalApiSignature = 'AFcWxV21C7fd0v3bYYYRCpSSRl31AoNA9KUay3k1fBXfaymo-ZfDhHKh'; //Paypal API Signature
}

else{

	$PayPalApiUsername  = 'dcox_api1.asyoulikeitsilvershop.com'; //PayPal API Username
	$PayPalApiPassword  = 'NUR5NUKQHNQ65SYZ'; //Paypal API password
	$PayPalApiSignature = 'AFcWxV21C7fd0v3bYYYRCpSSRl31Av-2ufdU6aw7clLR1IAF.duKY3tA'; //Paypal API Signature

}

	$PayPalCurrencyCode = 'USD'; //Paypal Currency Code

	//FROM NOW ON THESE scripts will check for checkout state based on $PayPalMode variable
	//url to complete order
	$PayPalReturnURL    = 'https://www.asyoulikeitsilvershop.com/order-confirmation.php'; 
	
	//Cancel URL if user clicks cancel
	$PayPalCancelURL    = 'https://www.asyoulikeitsilvershop.com/shoppingCart.php'; 

?>

