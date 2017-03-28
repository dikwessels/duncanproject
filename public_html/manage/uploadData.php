<html>
<head>
<title> As You Like It Silver Shop </title>
<meta name='description' content='As You Like It Silver Shop in New Orleans Louisiana specializes in silver flatware and holloware in active, inactive and obsolete patterns.' />
<meta name='keywords' content='sterling silver, sterling flatware, silver flatware, antique silver, silver tableware, antique sterling, replacement silver, silver repair, corporate gifts, wedding gifts, silver identification, cleaning silver' />

<link rel='stylesheet' href='managestyle.css' type='text/css'>
</head>
<body class='sub'>
<table width='90%' height=400 cellpadding='0' cellspacing='0' border='0' align='center' >
<tr>
</td>

To Upload data to the inventory database, it must be stored as a tab delimited text file.  Make sure that the first line of the file contains the column names(ie... brand).<br>
<?
include("/home/asyoulik/connect/mysql_connect.php");
if (is_uploaded_file($HTTP_POST_FILES['userfile']['tmp_name'])) {
##	copy($HTTP_POST_FILES['userfile']['tmp_name'], "/home/ayliss/www/manage/temp/inventory.txt");
	$file=file("temp/inventory.txt");
	echo $file[0]."<BR><BR>";
	$fields=str_replace('"','',substr($file[0],0,-2));
	
	for ($i=1;$i<10;$i++) {
		set_time_limit(10);
		$file[$i]=str_replace('"','',$file[$i]);
		$values="\"".str_replace(",","\",\"",substr($file[$i],0,-2))."\"";
	mysql_query("REPLACE into new_inventory($fields) values($values)");
	}
	echo $HTTP_POST_FILES['userfile']['name']. " has been uploaded.  The inventory has been updated.<br>";
}
?>

<form   enctype='multipart/form-data' method=post><input type='hidden' name='MAX_FILE_SIZE' value='100000000'><input type=file name=userfile value=browse><input type=submit></form></td>
</tr>
<tr><td align=center><a href=manage.htm target=_top>Return to Main Menu</a></td></tr>
</table>
</BODY>
</HTML>
