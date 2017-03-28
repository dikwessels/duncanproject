<?php
	
include_once("/home/asyoulik/connect/mysql_pdo_connect.php");

$query=$db->prepare("SELECT productId FROM inventory WHERE instr(image,'-2.jpg') > 0 ");

$query->execute();

$result = $query->fetchAll();

echo json_encode($result);

?>