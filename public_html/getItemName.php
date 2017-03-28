<?php
  
  include("/home/asyoulik/connect/mysql_pdo_connect.php");
  
  $query=$db->prepare("SELECT * FROM inventory WHERE productId=:productId");
  
  $query->bindValue(":productId",$productId,PDO::PARAM_STR);
  
  $query->execute();
  $result=$query->fetchAll();
  
  foreach($result as $row){
	  extract($row);
	  if($pattern){$by="by";}
	if($monogram){$monogrammed="-monogrammed";}
	
	$updateStatus=ucwords(strtolower(trim("Updating Item# $productId: $pattern $by $brand $item $monogrammed")));
  }
  echo $updateStatus;
?>