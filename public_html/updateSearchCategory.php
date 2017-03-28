<?php

	ini_set("display_errors",1);
	include("/connect/mysql_pdo_connect.php");

	extract($_GET);

	$item=urldecode($item);
	
	$update=$db->prepare("UPDATE inventory SET searchCategory=:sc WHERE category='h' AND item=:item");
	
	$update->bindParam(':sc',$sc,PDO::PARAM_INT);
	
	$update->bindParam(':item',$item,PDO::PARAM_STR);
	
	$update->execute();
	
	//$msgItem=ucwords(strtolower($item));
	
	echo($update->rowCount());
	
?>