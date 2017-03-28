<?php
//create list on website, cross reference with list from store
//echo "hello";
ini_set("display_errors",1);

include("/connect/mysql_pdo_connect.php");

extract($_GET);
$stmt="SELECT * FROM inventory WHERE quantity >0 AND display AND category=:category";

if($pid){$stmt.=" AND productId=:productId";} 

$stmt.=" ORDER BY productId";

//echo $stmt;

$query=$db->prepare($stmt);

$query->bindValue(':category',$category,PDO::PARAM_STR);

if($pid){$query->bindValue(':productId',$pid);}

$query->execute();

$result=$query->fetchAll();

if(count($result)>0){
   
	foreach($result as $row){
		$itemJSON=array();
		extract($row);
		$itemJSON[]=$pattern;
		$itemJSON[]=$brand;
		$itemJSON[]=$item;
		$itemJSON[]=$retail;
		$itemJSON[]=$monogram;
		echo "$productId::$quantity::$pattern::$brand::$item::$retail::$monogram<br>";// $monogram::$id<br>";
	}
}

else{
	echo "No record";
}
?>