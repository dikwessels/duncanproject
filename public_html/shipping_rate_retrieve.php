<?php 

include("/connect/mysql_connect.php");
ini_set("display_errors","1");
$query="SELECT * FROM customers ORDER BY customerNum DESC limit 100";

$result=mysql_query($query);

$methods=array(
			4=>'Ground Ship',
			10=>'3-day select',
			27=>'Second day air',
			53=>'Next day air'
			);
			
$newShipMethods=array(
			0=>'Ground Ship',
			1=>'3-day select',
			2=>'Second day air',
			3=>'Next day air'
			);

	$testtime=mktime(0,0,0,11,29,2013);
   $testtime=date('Y-m-d H:i:s',$testtime);	
echo $testtime."<br>";
while($row=mysql_fetch_assoc($result)){
	
	extract($row);

	if($time<$testtime){
		echo "$customerNum: $time : old shipping method: ".$methods[$shippingMethod]."<br>";
	}
	else{
		echo "$customerNum: $time : new shipping method: ".$newShipMethods[$shipMethod]."<br>";

	}
}

?>