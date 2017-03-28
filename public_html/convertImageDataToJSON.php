<?php
	
ini_set("display_errors",1);
	
include('/connect/mysql_pdo_connect.php');

$imagesArray=array();
	
$stmt="SELECT * FROM inventory WHERE image <>'' ORDER BY id ASC";

$query=$db->query($stmt);
$query->execute();

$result=$query->fetchAll();

foreach($result as $row){

	unset($imagesArray);

	$imagesArray=array();

	extract($row);

	if($image){
		$imagesArray[]=$image;
	}
	
	if($image2){
		$imagesArray[]=$image2;		
	}
	
	if($image3){
		$imagesArray[]=$image3;
	}

	$jsonImages=json_encode($imagesArray);
    
    echo($jsonImages."<br>");
    
	$update=$db->query("UPDATE inventory SET images=:jsonData WHERE id=:id LIMIT 1");

	$update->bindParam(':id',$id,PDO::PARAM_INT);
	$update->bindParam(':jsonData',$jsonImages,PDO::PARAM_STR);

	$update->execute();
	if($update->rowCount()>0){
		echo "Update successful";
	}

}


?>