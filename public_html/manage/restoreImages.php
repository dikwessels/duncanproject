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
$q=mysql_query("Select i.id,b.$field from inventory_backup as b, inventory as i  where i.brand=b.brand and i.pattern=b.pattern and i.item=b.item and b.$field!=''");
while ($r=mysql_fetch_assoc($q)) {
	mysql_query("UPDATE inventory set $field='$r[$field]' where id=$r[id]");
	}
?>

</BODY>
</HTML>
