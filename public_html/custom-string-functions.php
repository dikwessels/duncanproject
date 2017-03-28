<?php
/*********** just some custom string functions  ***********/

function to_currency($number){
	//convert a number to currency format
	
	$currency=number_format($number,2,".",",");
	
	return $currency;
}

?>