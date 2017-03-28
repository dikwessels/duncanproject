<?

if ($_GET['cat']) {
$cat=stripslashes($_GET['cat']);	header("Set-Cookie: cat=$cat;'';path=/");}
else  if ($_COOKIE['cat']) {$cat=$_COOKIE['cat']; } 
else { exit; }
include("/connect/mysql_connect.php");
if ($update) {
	if (isset($id)) {
	foreach($id as $k=>$v) {
	if (!$text[$k]) {
		mysql_query("DELETE from sideMenu where id=$v limit 1");continue;
		}

		$sT="brand=".rawurlencode($brand[$k])."&pattern=".rawurlencode($pattern[$k])."&item=".rawurlencode($item[$k]);
		$s="UPDATE sideMenu set text='$text[$k]',searchText='$sT' where id=$v";
		mysql_query($s);
		}
	}
	if ($ntext) {
		$sT="brand=".rawurlencode($nbrand)."&pattern=".rawurlencode($npattern)."&item=".rawurlencode($nitem);
		mysql_query("INSERT into sideMenu(text,searchText,category) values ('$ntext','$sT','$cat')");
		}
	}

$header=array('f'=>'Flatware','h'=>'Holloware','bs'=>'Baby Silver','j'=>'Jewelry','cl'=>'Collectibles','cs'=>'Coin Silver');
	
?>
<HTML>
<HEAD>
<TITLE></TITLE>
<META name="description" content="">
<META name="keywords" content="">
<script language='javascript'></script>
<link rel="stylesheet" href="managestyle.css" type="text/css">
</HEAD>
<BODY><form>
<table><tr><th colspan=4><? echo $header[$cat]; ?> Side Menu</th></tr>
<tr><td colspan=4>Use this page to add/edit/delete items from the <? echo $header[$cat]; ?> sideMenu.<br>To add an entry, type in the text you want to appear on the website under 'Display Text'.  Then you may enter information under the 3 other search fields.<br>Text under Manufacturer and Pattern will only bring up exact matches. <BR> Text under Item matches as follows: 'ring' will match 'ring' but not 'earring'; 'key ring' will match 'key' or 'ring'; 'key+ring' will match 'key ring' but not 'key' or 'ring'. <BR>To delete an entry simply leave the 'Display Text' blank.  You are limited to 8 items per category for frontend display purposes.</td></tr><tr><th>Display Text</th><th>Manufacturer</th><th>Pattern</th><th>Item</th></tr>

<?


$q=mysql_query("SELECT * from sideMenu where category='$cat' limit 12");
$num=mysql_num_rows($q);while ($r=mysql_fetch_assoc($q)) {
	extract($r);
$search=split('&',$searchText);

	echo "<tr><td><input type=hidden value=$id name=id[]><input name=text[] value='$text' size=40></td>";
	foreach ($search as $s) {
		list($k,$v)=split('=',$s);
		echo "<td><input name={$k}[] value='".urldecode($v)."' size=25></td>";
		}
	echo "</tr>";
	}
if ($num<12) {
?>
<tr><td><input type=text name=ntext size=40></td><td><input type=text name=nbrand size=25></td><td><input type=text name=npattern size=25></td><td><input type=text name=nitem size=25></td></tr>
<?
}
?>
<tr><td colspan=4 align=center><input type=hidden value=1 name=update><input type=submit class=submit value='Update Side Menu'></td></tr>
</table>
</form>
</BODY>
</HTML>
