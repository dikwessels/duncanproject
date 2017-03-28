<?php
	//initiate session
	session_name('checkout');
	session_start();

$paypalItemList = $_SESSION['paypalitems'] ;
//$_SESSION['subtotal'] =	$sub;

$ship = $_SESSION['shipping'];
$shipsurcharge = $_SESSION['shippingsurcharge'];

//$_SESSION['shippingDescription'] = $shippingMethodDescription;

$tax = $_SESSION['salestax'];

//$_SESSION['totalshipping'] = $ship + $shipsurcharge;
$total = $_SESSION['invoicetotal'];

//$_SESSION['giftcardonly'] = $giftcardonly;
//$_SESSION['hasgiftcard'] = $hasgiftcard; //flag if invoice has gift card on it
//$_SESSION['giftcards'] = $giftcards;

$paypalFormInputs = '
			<input id="invoice-tax" type="hidden" name="zTAXAMT" value="'.number_format($tax,2).'">
			<input id="invoice-shipping" type="hidden" name="zFREIGHTAMT" value="'.number_format($ship+$shipsurcharge,2).'">
			<input id="invoice-total" type="hidden" name="PAYMENTREQUEST_0_AMT" value="'.number_format($total,2).'">
			<input id="pp-invoice-items" type="hidden" name="itemlist" value="'.$paypalItemList.'">';
			
echo $paypalFormInputs;

?>
