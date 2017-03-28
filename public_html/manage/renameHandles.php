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
/*
$folder="../handles/";  ## include trailing "/";
if ($handle = opendir($folder)) {
   while (false !== ($file = readdir($handle))) { 
		set_time_limit(10); 
        if ($file != "." && $file != ".." && !is_dir($file)) {
			$newname=str_replace(" ",'',strtolower($file));
			echo "$newname<br>";
			rename("../handles/$file","../handles/$newname");
			}
		}
	}
*/
$files=array('alphaqz.php','pattern.php','alphalp.php');

foreach($files as $v) {
	$f=join(file("../$v"),"
");
	$pattern="/handles\/([ \w]+?\.jpg)/e";
	$f=preg_replace($pattern,"'handles/'.str_replace(' ','',strtolower('\\1'))",$f);

	$fh=fopen("$v",'w');
	fwrite($fh,$f);
	fclose($fh);
	 }
?>
</BODY>
</HTML>
