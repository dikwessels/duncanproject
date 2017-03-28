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
	$connect=mysql_connect('','ayliss','Ub6473');
	mysql_select_db('ayliss');
$q=mysql_query("SELECT i.image as i, t.id
FROM inventory AS i, testInventory AS t
WHERE i.brand = t.brand AND i.pattern = t.pattern AND i.item = t.item");
while ($r=mysql_fetch_assoc($q)) {
	mysql_query("UPDATE testInventory set image=\"$r[i]\" where id=$r[id]");
	}
?>

</BODY>
</HTML>
