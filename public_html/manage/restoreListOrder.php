<?
include("/home/asyoulik/connect/mysql_connect.php");




$fL=array('dinner knife'=>0,'place knife'=>1,'lunch/place knife'=>2,'dinner fork'=>3,'place fork'=>4,'lunch/place fork'=>5,'salad fork'=>6,'salad fork (place size)'=>6,'teaspoon'=>7);

foreach($fL as $k=>$v) {
	mysql_query("UPDATE inventory set listOrder=$v where item='$k'");
	}
?>