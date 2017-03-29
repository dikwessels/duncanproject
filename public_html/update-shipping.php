<?php
include("../connect/mysql_connect.php");

extract($_GET);
$i=0;

foreach($shippingMethod as $v){
	$query="UPDATE tblShippingCosts SET shippingSurcharge=".$shippingSurcharge[$i]." WHERE shippingDescription='".$v."'";
echo $query."<br>";
	$result=mysql_query($query);
if(mysql_affected_rows()>1){
	
}
else{
	echo mysql_error();
}
$i++;

}

?>