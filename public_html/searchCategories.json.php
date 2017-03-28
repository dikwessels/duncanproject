<?php
include("/connect/mysql_pdo_connect.php");

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


	
$stmt="SELECT DISTINCT item,searchCategory FROM inventory WHERE item <>''  AND category='h' and instr(item,'ZZZ')=0 ";

if($_GET['sc']==1){
	$stmt.=" AND searchCategory<1";
}

if($_GET['word']){
	$word=$_GET['word'];
	$stmt.=" AND instr(item,'$word')>0";
}
$stmt.=" ORDER BY item,searchCategory";

$query=$db->prepare($stmt);

$query->execute();
$result=$query->fetchAll();
if(count($result)>0){
	foreach($result as $row){
		$jsonArray[]=$row;
	}
}
else{
	$jsonArray[]=array("noresults"=>"1","word"=>"$word");
}


$products['itemName']=$jsonArray;

echo json_encode($products);

/*
$j=0;
if(count($result)>0){
foreach($result as $row){
 extract($row);
	$itemRow.= "<div class='row' id='row$j'>
		<div class='eight columns'>$item</div>
		<div class='eight columns field'>
		<div class='picker'>
		<select class='item' data-span='span$j' data-value='$searchCategory' data-item='$item'>";

		$i=0;
		foreach($searchCategories as $v){
			$itemRow.="<option value='$i' ";
			if($i==$searchCategory){$itemRow.= "selected='selected'";}
			$itemRow.=">$v</option>";
			$i++;
		}
			
		$itemRow.="</select>
		</div>
		</div>

		
		</div>";

$j++;

}
}
else{
	$itemRow="<div class='row'><div class='sixteen columns'>Sorry, no results were found for '$word'.</div></div>";
}
echo $itemRow;
	*/	

?>