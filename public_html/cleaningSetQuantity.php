<?
/********* cleaningSetQuantity.php

Author: 		Michael Wagner
Date: 			2/25/16
Description:	Compiles maximum number of available cleaning sets based on minimum number of constituent pieces

*************/	
	
ini_set("display_errors",1);
include_once("/connect/mysql_pdo_connect.php");
	
$stmt = "SELECT MIN( Inventory.quantity ) AS SetQuantity
			FROM (

				SELECT productId, quantity, item
				FROM inventory
				WHERE productId =74553
				OR productId =75587
				OR productId =78974
				OR productId =83067
				LIMIT 4
				
			) AS Inventory";

$query = $db->prepare($stmt);

$query->execute();

$result = $query->fetchAll();
$row = $result[0];

extract($row);

//echo $SetQuantity;
	
?>