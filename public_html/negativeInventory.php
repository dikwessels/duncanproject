<?php
ini_set("display_errors",1);
	include("/connect/mysql_pdo_connect.php");

	$query=$db->prepare("SELECT * FROM inventory WHERE quantity<0");
    $query->execute();
    
	$result=$query->fetchAll();
	
	if(count($result)>0){
	foreach($result as $row){
		extract($row);
		echo "$productId $pattern $brand $item $quantity $retail<br>";
	}
	}
	else{
		echo "There are no items with a negative quantity";
	}


?>