<?php

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
   if ($sub>800) {
   		$ship=$sub*.03;
   		$ship=$ship+4;
   	}
   	
   else if ($sub>450) { $ship=24; }
   else if ($sub>300) { $ship=21; }
   else if ($sub>200) { $ship=19; }
   else if ($sub>135) { $ship=17; }
   else if ($sub>75) { $ship=14;}
   else if ($sub>35) { $ship=12;}
   else if($sub>0) { $ship=10; }
   else {$ship=0;}

  return $ship;

}


function calculateTax($st) { 
	global $la_zips,$jef_zips,$no_zips;$tx=0;
	$query=mysql_query("SELECT zip from customers where customerNum=".$_COOKIE["custNum"]);
	$c=mysql_fetch_assoc($query);
	if (strpos($la_zips,$c[zip])) { 
		if (strpos($jef_zips,$c[zip])) { $tx = $st*.09;	 }
		else if (strpos($no_zips,$c[zip])) { $tx = $st*.1;  	 }
		else { $tx = $st*.05; 	 }
		} 

  return $tx;
}


?>