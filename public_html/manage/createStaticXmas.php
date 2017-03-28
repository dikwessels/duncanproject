<?php
include("/home/asyoulik/connect/mysql_connect.php");
$url="http://www.asyoulikeitsilvershop.com/manage/createStaticHTML.php?id=";

$query="SELECT id FROM inventory WHERE category=\"xm\"";

$result=mysql_query($query);


 while($row=mysql_fetch_assoc($result)){
  set_time_limit(10);

  extract($row);
  $ch=curl_init($url.$id);
  curl_exec($ch);
  curl_close($ch);     
 }

?>