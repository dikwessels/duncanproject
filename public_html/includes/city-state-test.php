<?php
include("connect/mysql_connect.php");
ini_set("display_errors",1);
	$query="SELECT DISTINCT city FROM inventory WHERE category='cs'";
	
	$result=mysql_query($query);
	$row=mysql_fetch_assoc($result);
 
 print_r($row);
 
?>