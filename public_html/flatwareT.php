<?
require("GzipCompress.php");
?>
<!DOCTYPE html
 PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<title> As You Like It Silver Shop </title>
<meta name="description" content="As You Like It Silver Shop in New Orleans Louisiana specializes in silver flatware and holloware in active, inactive and obsolete patterns." />
<meta name="keywords" content="sterling silver, sterling flatware, silver flatware, antique silver, silver tableware, antique sterling, replacement silver, silver repair, corporate gifts, wedding gifts, silver identification, cleaning silver" />
<script language="javascript" src='js/images.js'></script>
<script language='javascript' src='js/storeT.js'></script>
<script language=javascript>
brands= new Array();
<?

$displayCat=array("f"=>'Place Settings','f,fcs,sp'=>'Flatware','sp'=>"Serving Pieces",'fcs'=>"Complete Sets");

if (!$category) { $category="sp' or substring(a.category,1,1)='f";$cat='f,fcs,sp'; } else { $cat=$category;};
$searchcategory="(a.category='$category')";	
$current='';$brands="";$options='';
include("../connect/mysql_connect.php");
	$query=mysql_query("SELECT distinct(brand) as p from inventory as a where $searchcategory and pattern!='' order by p");
	while ($row=mysql_fetch_assoc($query)) {

		$brand.="'$row[p]',";
		}
	$brands= "brands['any']=new Array('');\nbrands['all']=new Array($brand";
/*	$query=mysql_query("SELECT brand as b from inventory where $searchcategory order by b");
	while ($row=mysql_fetch_assoc($query)) {
		$options.= "<option value=\"$row[b]\">".substr($row[b],0,40);
		$sq=mysql_query("SELECT distinct(pattern) as p from inventory  where $searchcategory and brand='$row[b]' order by p");
	$brands.="\r\nbrands['".$row['b']."']=new Array(";
		while ($sr=mysql_fetch_assoc($sq)) {
		$brands.="'".$sr['p']."','".$sr['p']."',";
		}
	if (mysql_num_rows($sq)!=0) {$brands= substr($brands,0,-1); }
	$brands.=");";
	} */
		$cB='all';
		$sq=mysql_query("SELECT  distinct(a.pattern) as p,a.brand as b FROM inventory AS a, inventory AS b WHERE $searchcategory and a.pattern = b.pattern  and a.pattern!='' order by a.p");

		while ($sr=mysql_fetch_assoc($sq)) {
			if ($sr[p]!=$cB) { 
				$options.= "<option value='".addslashes($sr[p])."'>".substr($sr[p],0,40);
				$cB=$sr[p];
				$brands= substr($brands,0,-1).")\r\n"; 
				$brands.="\r\nbrands[\"".addslashes($sr['p'])."\"]=new Array(";
				}
			$brands.="'".addslashes($sr['b'])."',";
			}
		$brands= substr($brands,0,-1).")";
 echo $brands;


?>	

fieldArray=new Array(brands);

function populate(num,val) {
	rx=/\\/g
	val=val.replace(rx,"")
	currentPId=0
	field=	document.forms[0].brand
	currentPattern=field.options[field.selectedIndex].value
	field.options.length = 0;
	if (fieldArray[num][val].length>1) { 
		field.options[field.options.length]=new Option('All Manufacturers', '', false, false);
		}
	for (j=0; j< fieldArray[num][val].length;j++) {
		if (fieldArray[num][val][j]==currentPattern) { currentPId=field.options.length		}
		field.options[field.options.length]=new Option(fieldArray[num][val][j], fieldArray[num][val][j], false, false);
		}
		field.selectedIndex=currentPId;
}


function updateIt(lev) {
for (i=lev;i>0;i-=1) {
elem=	document.forms[0].elements[1];
populate((i-1),elem.options[elem.selectedIndex].value)
}
}

function newWindow() {
//updatesSoon = window.open ("flatware_moresoon.html", "flatwareinventory", "width=450,height=350")
}

</script>
<link rel="stylesheet" href="ayliss_style_tan.css" type="text/css">
</head>

<body class="sub" onLoad="preLoad();document.forms[0].pattern.selectedIndex=0;document.forms[0].brand.selectedIndex=0;populate(0,'all')"; TOPMARGIN=0 LEFTMARGIN=0 MARGINHEIGHT=0 MARGINWIDTH=0>


<table width="100%" cellpadding="0" cellspacing="0" border="0" align="left" bgcolor="#CC9966" height="85">
<tr>
	<td width="760" background="/images/ayliss_title_t.jpg" alt="" border="0" align="right" valign="top"><img src="/images/blank.gif" width="760" height="1" alt="As You Like It Silver Shop" border="0"><br><table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td width="50%" align="left" valign="top"><p class="homenav"><a href="contactus.php" class="top">CONTACT US</a> * 1-800-828-2311</td><td width="50%" align="right"  valign="top"><p class="homenav">YOUR <a href="orderdetails.php" class="top">SILVER CHEST</a> HAS <img src='images/c__.gif' name=nums3 align=bottom><img src='images/c__.gif' name=nums2 align=bottom><img src='images/c_0.gif' name=nums1 align=bottom> ITEMS.<a href="orderdetails.php" class="top"><img src="images/silverchest_empty.gif" border="0" align="top" hspace="0" vspace="0" border="0" name=chest></a></td></tr></table></td>
	<td width="*"><img src="images/blank.gif" width="10" height="1" alt="" border="0"></td>
</tr>
</table>
<br clear="all">
<table width="100%" cellpadding="0" cellspacing="0" border="0" align="left">
<tr bgcolor="#000000">
	<td width="760" bgcolor="#000000" align="left"> 
		<img src="images/blank.gif" width="10" height="1" alt="" border="0">
		<a href="showProducts.php?gift=1&template=gift" onMouseOver="document.blair.src=greatgiftson.src" onMouseOut="document.blair.src=greatgiftsoff.src"><img name="blair" src="images/nav_greatgifts_1.gif" width="101" height="29" alt="Great Gifts" border="0"></a>
		<img src="images/nav_star.gif" width="15" height="29" alt="" border="0">
		<a href="cleaning.php" onMouseOver="document.chuck.src=cleaningon.src" onMouseOut="document.chuck.src=cleaningoff.src"><img name="chuck" src="images/nav_cleaning_1.gif" width="200" height="29" alt="Cleaning and Storage Products" border="0"></a>
		<img src="images/nav_star.gif" width="15" height="29" alt="" border="0">
		<a href="otherservices.php" onMouseOver="document.daniel.src=otheron.src" onMouseOut="document.daniel.src=otheroff.src"><img name="daniel" src="images/nav_other_1.gif" width="135" height="29" alt="Other Products" border="0"></a>
		<img src="images/nav_star.gif" width="15" height="29" alt="" border="0">
		<a href="search.php" onMouseOver="document.fritz.src=searchon.src" onMouseOut="document.fritz.src=searchoff.src"><img name="fritz" src="images/nav_search_1.gif" width="68" height="29" alt="Search" border="0"></a>
		<img src="images/nav_star.gif" width="15" height="29" alt="" border="0">
		<a href="index.php" onMouseOver="document.edwin.src=returnhomeon.src" onMouseOut="document.edwin.src=returnhomeoff.src"><img name="edwin" src="images/nav_home_1.gif" width="52" height="29" alt="Home" border="0"></a>		
	</td>
	<td width="*"><img src="images/blank.gif" width="10" height="1" alt="" border="0"></td>
</tr>
</table>
<br clear="all">
<table width="100%" cellpadding="0" cellspacing="0" border="0" align="left">
<tr bgcolor="#6A2A00">
	<td width="760" bgcolor="#6A2A00" align="center"> 
		<table width="760" border="0">
			<tr>
				<td align="center"><a href="flatware.php" class="onred">FLATWARE</a></td>
				<td align="center"><a href="hollowware.php" class="red">HOLLOWWARE</a></td>
				<td align="center"><a href="babysilver.php" class="red">BABY SILVER</a></td>
			</tr>
		</table>
	</td>
	<td width="*"><img src="images/blank.gif" width="10" height="1" alt="" border="0"></td>
</tr>
</table>
<br clear="all">
<table width="760" cellpadding="0" cellspacing="0" border="0" align="left">
<tr>
	<td colspan="3"><img src="images/t_flat_main.jpg" width="760" height="66" alt="Flatware"></td>
</tr>
<tr>
	<td width="150" align="left" bgcolor="#CC9966" valign=top>
		<ul>
		<li <?if ($cat=='f,fcs,sp') {echo 'class=on';}?>><a href="flatware.php" class="listme<?
if ($cat=='f,fcs,sp') {echo 'bold'; } ?>">Main</a>
<hr width="90%" size="1" noshade color="#CFC1B4" align="left">
		<li <?if ($cat=='f') {echo 'class=on';}?>>  <a href="flatware.php?category=f" class="listme<?if ($cat=='f') {echo 'bold'; }?>">Place Settings	</a>
		<li <?if ($cat=='sp') {echo 'class=on';}?>>  <a href="flatware.php?category=sp" class="listme<?if ($cat=='sp') {echo 'bold';} ?>">Serving Pieces</a>
		<li <?if ($cat=='fcs') {echo 'class=on';}?>>  <a href="flatware.php?category=fcs" class="listme<?if ($cat=='fcs') {echo 'bold'; } ?>">Complete Sets</a>
<hr width="90%" size="1" noshade color="#CFC1B4" align="left">
		<li>  <a href="showProducts.php?category=f%2Cfcs%2Csp&template=f&brand=GORHAM&pattern=CHANTILLY&submit=Display" class="listme">Chantilly by Gorham</a>
		<li>  <a href="showProducts.php?category=f%2Cfcs%2Csp&template=f&brand=REED+%26+BARTON&pattern=FRANCIS+I&submit=Display" class="listme">Francis I by Reed & Barton</a>
		<li>  <a href="showProducts.php?category=f%2Cfcs%2Csp&template=f&brand=DURGIN%2FGORHAM&pattern=FAIRFAX&submit=Display" class="listme">Fairfax by Durgin/Gorham</a>
		<li>  <a href="showProducts.php?category=f%2Cfcs%2Csp&template=f&brand=GORHAM&pattern=STRASBOURG&submit=Display" class="listme">Strasbourg by Gorham</a>
		<li>  <a href="showProducts.php?category=f%2Cfcs%2Csp&template=f&brand=TOWLE&pattern=OLD+MASTER&submit=Display" class="listme">Oldmaster by Towle</a>
		<li>  <a href="http://www.asyoulikeitsilvershop.com/showProducts.php?category=f%2Cfcs%2Csp&template=f&brand=INTERNATIONAL&pattern=JOAN+OF+ARC&submit=Display" class="listme">Joan of Arc by International</a>
		</ul>
		<p>&nbsp;</p>
		<p>&nbsp;</p>
		<p>&nbsp;</p>
		<p>&nbsp;</p>
		<p>&nbsp;</p>
		<p>&nbsp;</p>
		<p>&nbsp;</p>
		<p>&nbsp;</p>
	
	</td>
	<td width="10"></td>
	<td width="600" valign="top" align="right">
	




	
<!-- START: ALL NEW SEARCH OPTIONS -->
	<table width="595" border="0" align="right" cellpadding="0" cellspacing="0">
	<form action='showProducts.php'><input type=hidden value='<? echo $cat; ?>' name=category><input type=hidden value=f name=template>
					<tr>
				<th width="40%" height="30"><? echo $displayCat[$cat]; ?> Patterns</th>
					<td align=center >&nbsp;<b>by</b>&nbsp;</td>
					<th width="40%" height="30"><? echo $displayCat[$cat]; ?> Manufacturers</th>

	
				</tr>
				<tr>
					<td colspan="3"><img src="images/blank.gif" width="1" height="10" alt="" border="0"></td>
				</tr>
				<tr>
					<td align="left"><SELECT name=pattern size=1  onChange='populate(0,this.options[this	.selectedIndex].value)' ><option value='all'>All patterns <?
	echo $options;

?></SELECT></td>
				<td>&nbsp;</td>
					<td align="left"><SELECT  name=brand size=1> <option value='' >All Manufacturers
</SELECT></td></tr>
				<tr>
					<td colspan="3"><img src="images/blank.gif" width="1" height="10" alt="" border="0"></td>
				</tr>
				
		<tr>
					<td colspan="3"><img src="images/blank.gif" width="1" height="10" alt="" border="0"></td>
				</tr><tr>
	<td colspan="3"  align=left><b >To Search <? echo $displayCat[$cat]; ?>, choose a  pattern and manufacturer and click <INPUT TYPE="submit" VALUE="Search <? echo $displayCat[$cat]; ?>"  name=submit></b></td></tr>

			</table>
<!-- END: ALL NEW SEARCH OPTIONS -->






</form>
		<br clear="all">	<br clear="all"><form name=itemsForm>
	<table border="0" cellpadding="0" cellspacing="0" align="center" width="600">
<?
	$query=mysql_query("SELECT pattern,brand,item,retail,sale,id,image from inventory as a where $searchcategory and time='n' and display=1 and retail>0 limit 5");
if (mysql_num_rows($query)) { ?>

		<tr><td colspan=5 align=center><img src="images/recentacquisitions.gif" width="300" height="25" alt="RECENT ACQUISITIONS" hspace="0" vspace="3" align="absmiddle"> <b class="recentatitle">in <? echo $displayCat[$cat]; ?></td></tr>
		<tr><td colspan="5"  align=center><b class="recentadesc">&nbsp;&nbsp; 
Here's a glimpse of the latest additions to our <? echo $displayCat[$cat]; ?> inventory.
<br>&nbsp;</td></tr>
					
<? 

	while ($row=mysql_fetch_assoc($query)) {
		if ($row[sale]) {$price=$row[sale]; }  else {$price=$row[retail]; }
		if (!$row[image] || !file_exists("productImages/_BG/$row[image]")) { 
			$handle="handles/".strtolower($row[pattern])."by".strtolower($row[brand]).".jpg";
			$row[image]=(file_exists($handle))? $handle:'productImages/_TN/noimage_th.jpg'; }  
		 else {$row[image]='productImages/_TN/'.substr($row[image],0,-4)."_TN.jpg"; }	
		if ($row[pattern] && $row[brand]) { $title="$row[pattern] by $row[brand]"; }
		else {  $title="$row[pattern]$row[brand]&nbsp;"; }
	echo "	<tr>
			<td width='100' valign='top'><a href='showItem.php?id=$row[id]&category=f&template=f' class='recentacqs'><img src='{$row[image]}' width='100' border='0'></a></td>
			<td width='10'><img src='images/blank.gif' width='10' height='1' alt='' border='0'></td>
			<td width='440' valign='top' align=left>
				<table width='100%' class='recentacqs' border='0' cellpadding='1' cellspacing='0' align=left><tr>
	<th colspan=5><b>$title</b></td></tr>
				<tr>
					<td class=color width=200><p class=searchrecent><b><a href='showItem.php?id=$row[id]&category=f&template=f' >$row[item]</a> </b></p></td>
	<td class=color align=right width=100>	<p class=searchrecent><b>\${$price}&nbsp;&nbsp;</b></p></td>";	
		echo "	<td class=color align=right><input type='button' value='Add Item' onClick='updateCart($row[id],1)'></td>
			</tr>
				</table>
			</td>
			<td width='10'><img src='images/blank.gif' width='10' height='1' alt='' border='0'></td>
			<td width='40' valign='middle'><input type='hidden' value='1' name=quantity$row[id]><img src=images/silverchest_add.gif name='chestimage$row[id]' onClick=\"updateCart($row[id],1);return false;\"></td>	
	
		</tr>
		<tr>
			<td colspan='5'><img src='images/blank.gif' width='10' height='10' alt='' border='0'></td>
		</tr>";
			}	
?>

<tr>
	<td colspan="5"  align=center><b class="recentadesc">&nbsp;&nbsp; <a href="showProducts.php?template=f&category=f%2Cfcs%2Csp&recent=1" class="recentadesc">View the complete list</A> of As You Like It Silver Shop's recent acquisitions in <? echo $displayCat[$cat]; ?>.</td>
</tr>
<?
}
else { ?>
<!--<tr><td colspan="5"  align=center><b class="recentadesc">There are no additions to our <? echo $displayCat[$cat]; ?> inventory today.<br> We do update As You Like It Silver Shop's inventory daily. <br>Check here often to see a listing of our most recent acqusitions.<br>&nbsp;</td></tr>-->
<? }

?>
</table>	</form>
	</td>
</tr>
<tr>
	<td width="150" align="left" bgcolor="#CC9966"><img src="images/blank.gif" width="1" height="1" alt="" border="0"></td>
	<td width="10"></td>
	<td valign="top">
		<hr width="100%" noshade size="1" color="#CC9966" align="left">
		<p class="bottom">&copy; Copyright 2003. As You Like It Silver Shop.</p>
		<p>&nbsp</p>
	</td>
</tr>
</table>
</body>
</html>
<? 
ob_flush();
?>