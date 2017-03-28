<!DOCTYPE html>
<html>
<head>
	<title>Data list test</title>
	<link rel="stylesheet" href="/css/Gumby/css/gumby.css">
</head>
<body>
<div class="container sixteen colgrid">
<div class="row">
<label class="two columns">Pattern</label>
<div class="field four columns">
<input class="medium input" list="patterns">
<datalist id="patterns">

<?php
$patbrand=array();
ini_set("display_errors",1);
include("/connect/mysql_pdo_connect.php");

$query=$db->prepare("SELECT DISTINCT pattern, brand from inventory WHERE pattern IS NOT NULL and pattern <>'' and brand IS NOT NULL and brand<>'' ORDER BY pattern, brand");
$query->execute();
$result=$query->fetchAll();

foreach($result as $row){	
extract($row);
//echo $i;
//print_r($row);
	$patbrand[0]=ucwords(strtolower($pattern));
	$patbrand[1]=ucwords(strtolower($brand));
	//implode(, )
	$value=implode(' by ', $patbrand);	
	echo "<option value='$value'>";
	$i++;
}
//echo $content;

?>
</datalist>
</div>
</div>

</div>

</body>
</html>