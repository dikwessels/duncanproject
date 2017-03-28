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
include("/home/asyoulik/connect/mysql_connect.php");
$f=file('../../WebInventory.txt',r);
	$fields=str_replace('"','',substr($f[0],0,-1));
	for ($i=1;$i<count($f);$i++) {
		set_time_limit(10);
		$f[$i]=str_replace("''",'',$f[$i]);
	#	$f[$i]=str_replace(',,',",'',",$f[$i]);
		$values=substr($f[$i],0,-1);
		$q=mysql_query("REPLACE into inventory($fields) values($values)");
		if (mysql_affected_rows()) {
			 echo"REPLACE into inventory($fields) values($values)\n<br>"; 
			}
			else { echo "BAD STATEMENT"; }
	}
?>
</BODY>
</HTML>
