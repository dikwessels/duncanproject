<?php

//check if zip code is in louisiana, if so add appropriate taxation
//return tax value


session_name('checkout');
session_start();
ob_start();

include_once("louisianaZips.inc");

extract($_POST);
extract($_GET);

$salestax=calculateSalesTax($zip);
//echo $zip;	
$tax=number_format(calculateSalesTax($zip),2);

$_SESSION['salestax'] = $tax;

$_SESSION['zip'] = $zip;

echo $tax;

function calculateSalesTax($zip) { 
	
	global $la_zips,$jef_zips,$no_zips;
	$tx = 0;
	
	$st = $_SESSION['subtotal'];
    //echo $st."<br>";
	if( $_SESSION['giftcardsubtotal'] ){
		$st = $st-$_SESSION['giftcardsubtotal'];
	}
	
	if(strpos($la_zips,$zip)) { 
		if(strpos($jef_zips,$zip)>0){$tx = $st*.09;}
		else if(strpos($no_zips,$zip)){$tx = $st*.1;}
		else {$tx = $st*.05;}
	} 

  return $tx;

}


?>