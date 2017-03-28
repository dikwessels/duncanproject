<!DOCTYPE html>
<html>
<head>
<title>
</title>

<link rel="stylesheet" src="ayliss_style_uni.css">

</head>
<body>
<div id="container">
<div class="row">


<?php
ini_set("display_errors","1");

 include("/connect/mysql_connect.php");


function createItemHeader($p,$b,$i){
	$s="$p by $b $i";
	$s=trim($s);
	if(strpos($s,'by')==0){
		$s="$b $i";
	}
	$s=trim($s);

	$s=ucwords($s);
	return $s;
}

$query="SELECT * FROM customers WHERE status='processed' or status='shipped'";

$itemarray=array();


$result=mysql_query($query);

$first=0;
$second=0;
$third=0;

while($row=mysql_fetch_assoc($result)){
	extract($row);
	
	$invoiceitems=explode("&", $items);
	
	foreach($invoiceitems as $invoiceitem){
		$itemdata=explode(":", $invoiceitem);
		$itemarray[$itemdata[0]]+=$itemdata[1];
	}
}
$topthree=array();
foreach($itemarray as $k=>$v){
	if($v>$first){
		$topthree[0]=$k;
		$first=$v;
	}
	else{
		if($v>$second){
		$topthree[1]=$k;
		$second=$v;
		}
		else{
		if($v>$third){
			$topthree[2]=$k;
			$third=$v;
		}
		}
	}
	
}




foreach($topthree as $k=>$v){
	
	$query="SELECT * FROM inventory WHERE id=$v";
	echo $query;
	$result=mysql_query($query);
	
	$row=mysql_fetch_assoc($result);
	
	extract($row);
	
	if($image){
		$imageSource="/productImages/_BG/".$image;
	}
	
	$itemhead=createItemHeader($pattern,$brand,$item);
	
	$itemcontent.="<div class='cell fiveColumns'>
					
					<h4 class='patternHead'>
						$itemhead
					</h4>
					
					<img width='100' src='$imageSource'>
					
					</div>
					";
}

echo $itemcontent;

print_r($topthree);
echo $first."<br>".$second."<br>".$third;
//natsort($itemarray);
//print_r($itemarray);
?>
</div>
</div>
</body>
</html>