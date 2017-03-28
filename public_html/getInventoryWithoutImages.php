<?php
include("/home/asyoulik/connect/mysql_pdo_connect.php");

ini_set("display_errors",1);


$stmt = "SELECT * FROM inventory WHERE productId AND (image='' OR image IS NULL OR image=' ') ";

if($max){$stmt .= " AND productId<$max";}
if($min){$stmt .= " AND productId>$min";}

$stmt .= " ORDER BY productId DESC";


$query = $db->query($stmt);

$query->execute();


$result = $query->fetchAll();

foreach( $result as $row ){
	
	extract($row);
	
	$by = "";	
	$monogrammed = "";
	//if($image!=''||$image2!=''||$image3)
	if($pattern){ $by = "by"; }
	
	if( ($monogram == 1) || ($monogram == -1) ){ 
	
		$monogrammed="-monogrammed";
	
	}
	
	$fullName = strtoupper(trim("$pattern $by $brand $item $monogrammed"));
	
	echo $productId."<item>$fullName;";

}

?>