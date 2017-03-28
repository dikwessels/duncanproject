<?php

include("/home/asyoulik/connect/mysql_connect.php");

$sql="SELECT * FROM tblGiftCards";

$query=mysql_query($sql);

while($row=mysql_fetch_assoc($query)){

	echo json_encode($row);
}

?>