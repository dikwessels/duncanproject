<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>

	<head>
		<meta http-equiv="content-type" content="text/html;charset=utf-8" />
		<meta name="generator" content="Adobe GoLive" />
		<title>Inventory with No Description</title>
	<link href="http://www.asyoulikeitsilvershop.com">
	</head>
	



<body>

<?php

    $db = mysql_connect("localhost", "asyoulik_admin",'f[()6COPT!Wo') or die ('I cannot connect to the database because: ' . mysql_error());
    if (!$db) {
      echo("Connection to database failed.  Please, try again later.");
    }

    if (!@mysql_select_db("ayliss")) {
      echo("Database connection failed.  Please, try again later.");
    }
$result=mysql_query("SELECT productId,pattern,item,brand FROM inventory WHERE productId<1 or productId=0 or NOT productId ORDER BY productId");
$number=mysql_numrows($result);
print "<br>Rows Found:$number<br><br>";

print "<table>";
for($i=0;$i<$number;$i++){
print "<tr>";
$record=mysql_fetch_assoc($result);
print "<td>$productId</td><td>$pattern by $brand</td><td>$item</td><td></td></tr>";
}

print "</table>";
mysql_close();
?>

</body>

</html>
