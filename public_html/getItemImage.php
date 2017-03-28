<?php
ini_set("display_errors",1);

include_once("/connect/mysql_pdo_connect.php");

extract($_GET);

$query=$db->prepare("SELECT * FROM inventory WHERE id=:id");
$query->bindParam(':id',$id,PDO::PARAM_STR);
$query->execute();
$t=time();
$result=$query->fetchAll();

	foreach($result as $row){
	   extract($row);
	   if($image){
		   $imagecontent="<p>Current Image:</p><img width='100' src='/productImages/_BG/$image?$t'>";
	   }
	   else{
		  $imagecontent="<p>No image uploaded</p>"; 
	   }
	   
	}

echo $imagecontent;
?>