<?php

include("/connect/mysql_pdo_connect.php");

$query=$db->query("SELECT distinct pattern, brand from inventory WHERE pattern!='' and pattern !='UNKNOWN' and brand!='' and brand!='UNKNOWN' ORDER BY pattern, brand");

$result=$query->fetchAll();

$settings=array(
				'4 Piece Lunch/Place Setting',
				'4 Piece Place Size Setting',
				'4 Piece Dinner Setting'
				);

foreach($result as $row){
	extract($row);
	echo "<strong>$pattern BY $brand</strong><br>";
	foreach($settings as $setting){
	  
	$query=$db->query("SELECT count(*) as ct FROM inventory WHERE category='ps' and pattern='$pattern' and brand='$brand' and item='$setting'");
	$result2=$query->fetchAll();
	 extract($result2[0]);
	 if($ct==1){
		 echo "- $setting entry exists<br>";
	 }
	 else{
		 echo "- no $setting entry<br>";
	 }
	}
	echo '<br>';
}

$result=$query->drop();

print_r($result);

?>