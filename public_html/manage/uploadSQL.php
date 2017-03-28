<HTML>
<HEAD>
<TITLE></TITLE>
<META name="description" content="">
<META name="keywords" content="">
<script language='javascript'></script>
<style type='text/css'></style>
</HEAD>
<BODY>
<?
$filename='ayliss.sql';
include("/home/asyoulik/connect/mysql_connect.php");
$file=file($filename);
for ($i=0;$i<count($file);$i++) {
	if (substr($file[$i],0,1)=='#') { continue; }
	mysql_query(substr($file[$i],0,-3));
	echo substr($file[$i],0,-3);
	}
?>

</BODY>
</HTML>
