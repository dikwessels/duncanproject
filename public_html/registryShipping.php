<?php

include("/home/asyoulik/connect/mysql_pdo_connect.php");

	extract($_POST);
	extract($_GET);

	$stmt = "SELECT * FROM weddingRegistries where id=$id";
	
	$query = $db->prepare($stmt);
	
	$query->execute();

	$result = $query->fetchAll();
	

	if( count($result) > 0 ){
		
		 $row = $result[0];
		 
		 extract($row);
		 
		 echo $rfname.";".$rlname.";".$raddress.";".$rcity.";".$rstate.";".$rzipcode.";".$rphone;
	 
	}
	else
	{
	 echo "No shipping data found";
	}

?>