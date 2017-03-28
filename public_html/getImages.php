<?php
	include("/connect/mysql_pdo_connect.php");
ini_set("display_errors",1);
extract($_GET);
$query="SELECT image, image2,image3 FROM inventory WHERE productId=:productId";
$query=$db->prepare($query);
$query->bindValue('productId',$productId);
$query->execute();
$results=$query->fetchAll();

foreach($results as $row){
    foreach($row as $k=>$v){
	    $row[$k]=str_replace('.jpg', '_TN.jpg', $v);
    }
    echo json_encode($row);
}
?>