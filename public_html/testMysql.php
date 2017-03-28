<?php
	
	include("/home/asyoulik/connect/mysql_pdo_connect.php");
	
$stmt = "SELECT * FROM inventory WHERE quantity>0";

$query = $db->prepare($stmt);

$query->execute();

$result = $query->fetchAll();

print_r($result);

	
?>