<!DOCTYPE html>
<html>
<head>
	<title>
	Hollowware Items 
	</title>
	
	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>	
	<link rel="stylesheet" href="/css/Gumby/css/gumby.css">
	<link rel="stylesheet" href="ayliss_style_uni.css">
</head>
<body>

<div class="container sixteen colgrid">
<?php

$searchCategories=array('Baskets, Centerpieces, Vases and Epergnes',
					'Bowls,Compotes & Cake Stands',
					'Bread & Butter Items','Candleware',
					'Gift','Goblets, Mint Julep Cups & Other Cups',
					'Jewelry and Personal Accessories',
					'Napkin Rings',
					'Picture Frames',
					'Pitchers & Urns',
					'Salt & Pepper Items',
					'Tabletop Items',
					'Tea and Coffee',
					'Trays',
					'Vanity Items',
					'Wine & Bar Items',
					'Chafing Dishes'
					);

	include("/home/asyoulik/connect/mysql_pdo_connect.php");
	
	
$query=$db->prepare("SELECT DISTINCT item,searchCategory FROM inventory WHERE item <>'' AND searchCategory>0 AND category='h' ORDER BY item, searchCategory");

$query->execute();
$result=$query->fetchAll();

foreach($result as $row){
 extract($row);
 
  	$teststmt="UPDATE inventory SET searchCategory=$searchCategory WHERE item='$item' and category='h' and searchCategory=0";
  	$update=$db->prepare("UPDATE inventory SET searchCategory=:sc WHERE category='h' AND item=:item and searchCategory=0");
  	$update->bindParam(':sc',$searchCategory,PDO::PARAM_INT);
  	$update->bindParam(':item',$item,PDO::PARAM_STR);	
	$update->execute();
	echo "<div class='row'><div class='eight columns'>Setting $item to $searchCategory...</div><div class='eight columns'>".$update->rowCount()." records updated</div></div>";
}


?>
</div>
</body>
</html>