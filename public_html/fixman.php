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
$f=join(file('alphaqz.php'),"
");
$f=preg_replace("/<b>([a-z 0-9]+) by ([a-z &;]+)<\/b>/i","<b><a href='showflatware.php?pattern=\\1&brand=\\2&category=f,sp,fcs&template=f'>\\1 by \\2</a></b>",$f);
echo $f;
$fi=fopen('alphaqz2.php','w');
fwrite($fi,$f);
?>
</BODY>
</HTML>
