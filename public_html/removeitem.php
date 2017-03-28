<?php

extract($_POST);
extract($_GET);
$items=explode("&",$_COOKIE['items']);

$newCookie="";

foreach($items as $v){
 if(trim($v)!=""){
	if(strpos($v, $id)===false){
	  $newCookie.="&".$v;	
	  //echo "No match found in $v<br>";
	}
	else{
		//echo "Match found in $v<br>";
	}
 }
}

//echo $newCookie;
//erase old cookie
setcookie("items",$_COOKIE['items'],time()-60,'/');
//create new one
setcookie("items",$newCookie,0,'/');

//include("orderdetailsAJAX.php");

?>