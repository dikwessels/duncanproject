<?php 


include('/connect/mysql_connect.php');

extract($_GET);
if($productId>0){
$result=mysql_query("SELECT id FROM inventory WHERE productId=$productId");
}
else{

$pattern=urldecode($pattern);
$brand=urldecode($brand);
$item=urldecode($item);

$result=mysql_query("SELECT id FROM inventory WHERE item=\"$item\" AND pattern=\"$pattern\" AND brand=\"$brand\"");

}

$num=mysql_num_rows($result);
if($num>0){
    $record=mysql_fetch_assoc($result);
    extract($record);
    echo $id;
}
mysql_close();

?>