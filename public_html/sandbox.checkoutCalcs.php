<?php

include("louisianaZips.inc");

function format($num) {
  $num*=100;
    if($num>0){
      $num="$num";
      return (substr($num, 0,-2).".".substr($num,-2));
    }
    else{
     $num="0.00";
      return $num;
    }
}

function calculateShipping($sub){
	//echo $sub;
	$ship=$sub*.02;
 	if($ship<5 && $sub>0){$ship=5;}
 
	return $ship;
}


function calculateTax($st) { 
	global $la_zips,$jef_zips,$no_zips;$tx=0;
	//$query=mysql_query("SELECT zip from customers where customerNum=".$_COOKIE["custNum"]);
	//$c=mysql_fetch_assoc($query);
		if(strpos($la_zips,$_SESSION['zip'])) { 
			if(strpos($jef_zips,$_SESSION['zip'])){$tx=$st*.08;}
			else if(strpos($no_zips,$_SESSION['zip'])){$tx=$st*.09;  	 }
			else { $tx=$st*.04;}
		} 

  return $tx;
}


?>