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
$file=file("placesettings.txt");
include("/home/asyoulik/connect/mysql_connect.php");

foreach($file as $v) {
	$f=split(";",$v);
            mysql_query("INSERT into inventory(category,pattern,brand,item,retail,listOrder,quantity)  values('ps','$f[0]','$f[1]','4 Piece Lunch Setting','$f[2]',-2,0)");
            echo "Inserted $f[0] by $f[1] 4 Piece Lunch Setting<br>";            
            mysql_query("INSERT into inventory(category,pattern,brand,item,retail,listOrder,quantity) values('ps','$f[0]','$f[1]','4 Piece Dinner Setting','$f[3]',-1,0)");
            echo "Inserted $f[0] by $f[1] 4 Piece Dinner Setting<br>";  	    
            mysql_query("INSERT into inventory(category,pattern,brand,item,retail,listOrder,quantity) values('ps','$f[0]','$f[1]','4 Piece Place Size Setting','$f[4]',-3,0)");
            echo "Inserted $f[0] by $f[1] 4 Piece Place Size Setting<br><br><br>";  
	}
?>
</BODY>
</HTML>
