<?php 
	
	include("/home/asyoulik/connect/mysql_pdo_connect.php");
	
	
	
	$query = $db->prepare("SELECT * FROM `inventory` WHERE productId = 74553 OR productId = 78974 or productId = 83067 or productId = 75587 OR productId = 87571");
	
	$query->execute();
	
	$result = $query->fetchAll();
	
	foreach($result as $row){
		
		extract($row);
		
		echo "$item: $quantity<br>";
	}
	
?>