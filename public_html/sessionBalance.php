<?php
	
session_name('checkout');
session_start();
	
$total = $_SESSION['invoicetotal'];

foreach( $_SESSION['redeemedGiftCards'] as $k=>$v ){
	$giftCardTotal += $v;
}

$balance = $total-$giftCardTotal;

if( $balance < 0 ){
	$balance = 0;
}	

echo $balance;
?>