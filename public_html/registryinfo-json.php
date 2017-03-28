<?php
include_once("/connect/mysql_connect.php");
extract($_GET);

$query="SELECT * FROM weddingRegistries WHERE id=$regID";

$result=mysql_query($query);

$row=mysql_fetch_assoc($result);

echo json_encode($row);

?>