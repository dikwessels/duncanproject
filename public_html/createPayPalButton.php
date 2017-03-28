<?php
ini_set("display_errors",1);
session_name('checkout');
session_start();

extract($_POST);

$shippingDescription = array(
		"Ground (8-10 days)",
		"3-Day Select",
		"2nd-Day Air",
		"Next-Day Air",
		"In-Store Pickup"
);

//take shipping method and current surcharges
$surcharges 	= json_decode($_SESSION['shippingrates']);

$shipindex 		= $_SESSION['PPShipIndex'];
$taxindex 		= $_SESSION['PPTaxIndex'];
$shipsurcharge 	= $_SESSION['shippingsurcharge'];
$shipping 		= $_SESSION['shipping'];
$tax 			= $_SESSION['salestax'];
$PPItemString	= $_SESSION['invoiceItemsPP'];

$PPTaxString 	= "L_PAYMENTREQUEST_0_QTY$taxindex=1&L_PAYMENTREQUEST_0_NAME$taxindex=Sales+Tax&L_PAYMENTREQUEST_0_AMT$taxindex=$tax";


$PPShipString 	= "L_PAYMENTRQUEST_0_QTY$shipindex=1&L_PAYMENTREQUESTL_PAYMENTREQUEST_0_NAME$shipindex=Shipping+and+Insurance:".$shippingDescription[$smethod].
"&L_PAYMENTREQUEST_0_AMT$shipindex=".($shipping+$shipsurcharge);


$_SESSION['paypalitems'] = $PPItemString.$PPTaxString."&".$PPShipString;

echo "{$PPItemString}{$PPTaxString}&{$PPShipString}";


?>