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
mysql_connect('','ayliss','Ub6473');
mysql_select_db('ayliss');
$f=fopen('sp.txt','r');
while ($line=trim(fgets($f))) {
	$line=str_replace("\"","",$line);
	$line=str_replace("	","','",$line);
	$line=substr(str_replace("$","",$line),0,-11);
	mysql_query("INSERT into inventory(id,pattern,brand,quantity,item,retail,dimension,monogram,category) values('$line','sp')");
//echo "INSERT into inventory(id,pattern,brand,item,retail,dimension,monogram,category) values('$line','f')";
 	}
?>
</BODY>
</HTML>
