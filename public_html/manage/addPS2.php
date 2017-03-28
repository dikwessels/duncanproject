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
	$q=mysql_query("SELECT pattern as p,brand as b from inventory  where category='f' or category='sp' and pattern!='' group by pattern,brand order by pattern");
	while ($r=mysql_fetch_assoc($q)) { extract($r);
		$sq=mysql_query("SELECT id from inventory where category='ps' and brand='$b' and pattern='$p'");
		if (!mysql_num_rows($sq)) {
			mysql_query("INSERT into inventory(category,pattern,brand,item,retail,listOrder,quantity)  values('ps','$p','$b','4 Piece Lunch Setting',0,-2,0)");
			mysql_query("INSERT into inventory(category,pattern,brand,item,retail,listOrder,quantity) values('ps','$p','$b',''4 Piece Dinner Setting',0,-1,0)");
			mysql_query("INSERT into inventory(category,pattern,brand,item,retail,listOrder,quantity) values('ps','$p','$b','4 Piece Place Setting',0,-3,0)");
			}
		}
?>
</BODY>
</HTML>
