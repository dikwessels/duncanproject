<?php

include("/home/asyoulik/connect/mysql_pdo_connect.php");

$query=$db->prepare("SELECT * FROM inventory WHERE productId=:pid");
$query->bindValue(':pid',$pid);
$query->execute();

$result=$query->fetchAll();

foreach($result as $row){
	if($row['image']=='' or !$row['image']){
		echo "no_image";
	}
	else {
		echo "has_image";
	}
}


?>