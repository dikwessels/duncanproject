<?php

session_name('checkout');
session_start();

extract($_POST);

if($newTotal){$_SESSION['invoicetotal']=$newTotal;}

if($giftcardcode && !array_key_exists($giftcardcode, $_SESSION['redeemedGiftCards'])){	
	
	$_SESSION['redeemedGiftCards'][$giftcardcode]	=	$giftcardamount;
	$_SESSION['giftcardcode'][]						=	$giftcardcode;
	$_SESSION['totalRedeemedGiftCards']				=	0;
	
	foreach($_SESSION['redeemedGiftCards'] as $k=>$v){
	 $t+=$v;
	
	}
	
	$_SESSION['totalRedeemedGiftCards']	= $t;
}


if($giftcardamount){$_SESSION['giftcardamount'][] = $giftcardamount;}


?>