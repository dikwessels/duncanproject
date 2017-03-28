 <!DOCTYPE html
 PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>As You Like It Silver Shop: Search for Sterling Silver by Pattern</title>
<meta name="description" content="As You Like It Silver Shop in New Orleans Louisiana specializes in silver flatware and holloware in active, inactive and obsolete patterns." />
<meta name="keywords" content="sterling silver, sterling flatware, silver flatware, antique silver, silver tableware, antique sterling, replacement silver, silver repair, corporate gifts, wedding gifts, silver identification, cleaning silver" />
<script language="javascript" src='js/images.js'>
</script>
<script language='javascript' src='js/store.js'>
</script>
<link rel="stylesheet" href="ayliss_style.css" type="text/css">

<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-31581272-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>

</head>
<body class="sub" onLoad="preLoad()">
 

<table width="100%" cellpadding="0" cellspacing="0" border="0" align="left" bgcolor="#A27177" height="85">
<tr>
	<td width="760" background="/images/ayliss_title_r.jpg" alt="" border="0" align="right" valign="top"><img src="/images/blank.gif" width="760" height="1" alt="As You Like It Silver Shop" border="0"><br><table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td width="50%" align="left" valign="top"><p class="homenav"><a href="contactus.php" class="top">CONTACT US</a> * 1-800-828-2311</td><td width="50%" align="right"  valign="top"><p class="homenav">YOUR SILVER CHEST HAS <img src='images/c__.gif' name=nums3 align=bottom><img src='images/c__.gif' name=nums2 align=bottom><img src='images/c_0.gif' name=nums1 align=bottom> ITEMS.<a href="orderdetails.php" class="top"><img src="images/silverchest_empty.gif" border="0" align="top" hspace="0" vspace="0" border="0" name=chest></a></td></tr></table></td>
	<td width="*"><img src="images/blank.gif" width="10" height="1" alt="" border="0"></td>
</tr>
</table>
<br clear="all"><form action=showflatware.php><input type=hidden name=template value='s'>

<table width="100%" cellpadding="0" cellspacing="0" border="0" align="left">
	<? include("topnav.inc"); ?>
</table>

<br clear="all">
<table width="100%" cellpadding="0" cellspacing="0" border="0" align="left">
<tr bgcolor="#7A121B">
	<td width="760" bgcolor="#7A121B" align="center"> 
		<? readfile("silver_cats.in.php");?>	</td>	<td width="*">
<img src="images/blank.gif" width="10" height="1" alt="" border="0">
</td>
</tr>
<tr>	
	<td width="760"><img src="images/t_patternguide.jpg" width="760" height="66" alt="Pattern Guide"></td>	
	<td width="*"><img src="images/blank.gif" width="10" height="1" alt="" border="0">
</td>
</tr> <tr>     <td align="right">

<input type=submit value='PATTERN SEARCH'> <input type="text" size="30" name=homePattern>
</td> </tr>    
</table>
<br clear="all">
<table width="700" border="0">
<tr>	<td width="50">
<img src="images/blank.gif" width="50" height="1" alt="" border="0">
</td>	<td>				   <table border="0" width="90%" cellpadding="5" cellspacing="0" align="center" valign="top">      
<tr> <td align="center">
<p>


<?


$letter=($_GET[letter])?$_GET[letter]:"='A'";
$letter=stripslashes($letter);
$sortby=($_GET[sortby])?$_GET[sortby]:'pattern';
echo "sort by <b>".(($sortby!='pattern')?"<a href='pattern.php?sortby=pattern&letter=".urlencode($letter)."'>Pattern</a>":"Pattern")."</b> | <b>Choose Letter Below</b>  | sort by <b>".(($sortby!='brand')?"<a href='pattern.php?sortby=brand&letter=".urlencode($letter)."'>Manufacturer</a>":'Manufacturer')."</b>";
?>


</td> </tr>    
<tr> <td align="center">
<?
 
for ($i='A';$i<='Z';$i++) { 
	if ("='$i'"==$letter) { echo "<font class=chosen><b>$i</b></font>"; }
	else { echo "<a href=pattern.php?sortby=$sortby&letter=".urlencode("='$i'").">$i</a>"; }
	echo " | ";
	if ($i=='Z') { break; }
	}

	
if ($letter=="<'A'") { echo "<font class=chosen><b>Other</b></font> |"; } 
	else {
		echo "<a href=pattern.php?sortby=$sortby&letter=%3C%27A%27>Other</a> |"; 
	}

?>


</td>    </tr>       

<?

include("/connect/mysql_connect.php");

function createFileName($path,$v,$pattern,$brand){
		
		$brand=str_replace(" ", "-",$brand);
		if($pattern!=""){
			$pattern=str_replace("#","",$pattern);
			$pattern=str_replace(" ","-",$pattern);
			$fname=strtolower(str_replace(array('/'),array(''),$pattern))."-by-".strtolower(str_replace(array('/'),array(''),$brand));
		}
		else{
			
			$fname=strtolower(str_replace(array('/'),array(''),$brand));		
		}
			
		$fname=str_replace(' ', '', $fname);
		$fname=str_replace("'",'',$fname);
		$fname=str_replace('&','and',$fname);
		$fname=str_replace('co.','company',$fname);
		$fname=str_replace('co ','company',$fname);
		$fname=str_replace('bros.','brothers',$fname);
		$fname=str_replace('bro ','brothers',$fname);
		$fname=str_replace('.','',$fname);
		$fname=str_replace(',','',$fname);
		
		$keyword="";
		if($v!="SilverCare" && $v!="SilverStorage"){
			$keyword="-sterling-silver";
		}
		
		$fname=$path.$v."/".$fname."$keyword.html";
	
	return $fname;	
}

function createFileNameDEPREC($path,$v,$pattern,$brand){
		
		
		if($pattern!=""){
			$fname=strtolower(str_replace(array('/'),array(''),$pattern))."by".strtolower(str_replace(array('/'),array(''),$brand));
		}
		else{
			$fname=strtolower(str_replace(array('/'),array(''),$brand));		
		}
			
		$fname=str_replace(' ', '', $fname);
		$fname=str_replace("'",'',$fname);
		$fname=str_replace('&','and',$fname);
		$fname=str_replace('co.','company',$fname);
		$fname=str_replace('co ','company',$fname);
		$fname=str_replace('bros.','brothers',$fname);
		$fname=str_replace('bro ','brothers',$fname);
		$fname=str_replace('.','',$fname);
		$fname=str_replace(',','',$fname);
		
		$fname=$path.$v."/".$fname.".html";
	
	return $fname;	
}


$re=array("&",",",'.','#','by','BROTHERS','INTERNATIONAL');
$rw=array("AND",'','','','','BROS','INTL');
$letter="substring($sortby,1,1) $letter";
$query="SELECT * from handles where $letter and image=1 order by $sortby";
//echo($query);
$result=mysql_query($query); //"SELECT * from handles where $letter and image=1 order by $sortby");


while ($r=mysql_fetch_assoc($result)) {
	$folder=strtoupper(substr($r[pattern],0,1));
	$file=str_replace($re,$rw,strtoupper("$r[pattern] $r[brand]")).".jpg";
        $keyword="Sterling Silver";
        
        if(strtolower($r[brand])=="christofle"){
         $keyword="Silverplate";
        }
		if($r[pattern]!=""){
			$patternfname=createFileName("search/","All",$r[pattern],$r[brand]);
	
    }
	
		$qr="SELECT count(*) as c,sum(quantity) as ct FROM inventory WHERE quantity!=0 AND pattern=\"$r[pattern]\" AND brand=\"$r[brand]\"";
		//$qcount="SELECT sum(quantity) as ct FROM inventory WHERE pattern=\"$r[pattern]\" AND brand=\"$r[brand]\"";

                $qresult=mysql_query($qr);
		
		$qrow=mysql_fetch_assoc($qresult);
		
		if($qrow[c]>0){
			
		  if(file_exists("/home/asyoulik/public_html/$patternfname")){
	             $patternlink="http://www.asyoulikeitsilvershop.com/$patternfname";
		  }
	          else{
		     $patternlink="http://www.asyoulikeitsilvershop.com/showSearch/pattern/".rawurlencode(strtolower($r[pattern]))."/brand/".rawurlencode(strtolower($r[brand]))."/category/f,sp,fcs/template/f";
	          }
		}
                
                else{
                    $patternlink="http://www.asyoulikeitsilvershop.com/showSearch/pattern/".rawurlencode(strtolower($r[pattern]))."/brand/".rawurlencode(strtolower($r[brand]))."/category/f,sp,fcs/template/f";
                }

    echo"
	<tr>
	<td class=patbox><a href=\"$patternlink\"><img src=\"resizedHandles/$folder/$file\" alt=\"$r[pattern] by $r[brand] sterling silver\" title=\"$r[pattern] by $r[brand] $keyword\"  border=0></a>
	<br />
	<font color=#8C1111>
	<b>";

		
		

/*
 $fname=$r['pattern']."by".$r['brand'];
 $fname=formatFileName($fname);
 $fname.=".html";
 $fname="/search/All/$fname";

if (file_exists("/home/asyoulik/public_html/$fname")){
 		$patternlink="1";//<a href=\"$fname\">$r['pattern'] by $r['brand'] sterling silver</a>";
	}
	else{
	 $patternlink="<a href='showSearch/pattern/".rawurlencode(strtolower($r[pattern]))."/brand/".rawurlencode(strtolower($r[brand]))."/category/f,sp,fcs/template/f'>$r[pattern] by $r[brand] sterling silverware</a>";
}
*/

/* 
		//$patternlink="<a href='showSearch/pattern/".rawurlencode(strtolower($r['pattern']))."/brand/".rawurlencode(strtolower($r['brand']))."/category/f,sp,fcs/template/f'>$r['pattern'] by $r['brand'] sterling silver</a>";
}
*/
//echo "<a href='showSearch/pattern/".rawurlencode(strtolower($r[pattern]))."/brand/".rawurlencode(strtolower($r[brand]))."/category/f,sp,fcs/template/f'>$r[pattern] by $r[brand]</a>

$qinstock=$qrow[ct]>0?$qrow[ct]:0;

echo "<a href=\"$patternlink\">$r[pattern] by $r[brand] $keyword</a></b></font><span style=\"font-size:12px\">: $qinstock items in stock</span></td></tr>";

}

?>

</table>

</form>

</body>

</html>
