<?php
ini_set("display_errors",1);

	include_once("/connect/mysql_pdo_connect.php");
	
	extract($_GET);
	
	$query=$db->prepare("SELECT * FROM inventory WHERE id=:id");
	$query->bindValue(":id",$id);
	
	$query->execute();
	$result=$query->fetchAll();
	foreach($result as $row){
		//extract($row);
	    $imageArray[0]=array("image"=>$row['image'],"desc"=>$row['desc']);
	    $imageArray[1]=array("image"=>$row['image2'],"desc"=>$row['desc2']);
	    $imageArray[2]=array("image"=>$row['image3'],"desc"=>$row['desc3']);
	}
	
	echo json_encode($imageArray);

?>