<!DOCTYPE html>
<html>
<head>
	<title>
	Hollowware Item Search Categories
	</title>
	
	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>	
	<script src="/js/libs/modernizr-2.6.2.min.js"></script>
	<script src="/js/libs/gumby.min.js"></script>
	<link rel="stylesheet" href="/css/Gumby/css/gumby.css">

</head>
<body>

<div class="container sixteen colgrid">
<div class="row">
<div class="sixteen columns">
<!--<table>
<tbody>-->
<?php
include("/home/asyoulik/connect/mysql_pdo_connect.php");

$searchCategories=array(
					'No Search Category',
					'Baskets, Centerpieces, Vases and Epergnes',
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


	
$stmt="SELECT DISTINCT item,searchCategory FROM inventory WHERE item <>''  and quantity>0 AND category='h' and instr(item,'ZZZ')=0 and searchCategory<1";

$stmt.=" ORDER BY item,searchCategory";

	$query=$db->prepare($stmt);

	$query->execute();
	$result=$query->fetchAll();
	$j=0;
	
	if(count($result)>0){
		foreach($result as $row){
 			extract($row);
 			$itemRow.= "$item<br>";
 		}
 	}

echo $itemRow;
		

?>
<!--</tbody>
</table>
</div>
</body>