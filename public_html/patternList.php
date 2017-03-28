<?php

	include("/connect/mysql_pdo_connect.php");

	$query=$db->prepare("SELECT DISTINCT pattern, brand FROM inventory WHERE category='f'");
	
	$query->execute();
	
	$result=$query->fetchAll();
	
	foreach($result as $row){
		print_r($row);
	}

?>