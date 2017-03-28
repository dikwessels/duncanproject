<?
ob_start();

include("/home/asyoulik/connect/mysql_connect.php");

extract($_GET);
extract($_POST);
extract($_COOKIE);	

if ($_GET['cat']) {  
  $cat=stripslashes($_GET['cat']);	
  header("Set-Cookie: cat=$cat;'';path=/");
  $_COOKIE['cat']=$cat;
}
else{  
  if($_COOKIE['cat']){
   $cat=$_COOKIE['cat']; 
  } 
  else{
   $cat="f";
  }
}

if ($_GET['letter']) {
  $letter=stripslashes($_GET['letter']);
  setcookie("letter",urlencode($letter),"","/");
  //header("Set-Cookie: letter=$letter;'';path=/");
  $_COOKIE['letter']=$letter;
}

else { 
 if ($_COOKIE['letter']){
  $letter=$_COOKIE['letter'];
 }
 else{
  $letter="='A'";
 } 

}


if($_GET['order']){
  $order=stripslashes($_GET['order']);	
  header("Set-Cookie: order=$order;'';path=/");
  $_COOKIE['order']=$order;
}
else{
  if($_COOKIE['order']){
   $order=$_COOKIE['order']; 
  }
  else{
   $order="pattern,brand,item";
  }
} 


if($_GET['stock']) {
  $stock=$_GET['stock'];	
  header("Set-Cookie: stock={$_GET['stock']};'';path=/");
  $_COOKIE['stock']=$stock;
 }
else{  
  if($_COOKIE['stock']){
   $stock=$_COOKIE['stock']; 
  } 
  else{
   $stock="I";
  }
}

$categories=array('f'=>'Flatware','fcs'=>'Complete Sets','h'=>'Holloware','bs'=>'BabySilver','cp'=>'Cleaning Products','sp'=>'Serving Pieces','j'=>'Jewelry','stp'=>'Storage Products','xm'=>'Christmas');



if ($update) {  
	foreach ($ids as $v) {
		mysql_query("Update inventory set display='$display[$v]',time='$time[$v]',gift='$gift[$v]' where id=$v");
		}
	}

?>
<HTML>
<HEAD>
<TITLE></TITLE>
<META name="description" content="">
<META name="keywords" content="">
<style type="text/css">
.chosen {font-size:12px;color:000000;font-weight:bold}
</style>

<script language='javascript'>

function confirmDelete(id,text) {
	
	if (confirm('Delete '+text)) { location='display.php?delete='+id+'&cat='+top.topframe.cat; }

}

top.topframe.cat='<?echo $cat; ?>'
ie=1; dom=0;

	function setUp() 
	{
		var anchID = '';
	for (i=0;i<document.forms[0].length;i++) {
		f=document.forms[0].elements[i]
		regexp=/select/
		if (f.type.match(regexp)) {
			
			for (j=0;j<f.options.length;j++) {
				if (f.options[j].value==f.id) {
					f.options[j].selected=1
					}
				}
			}	
			
		}
		
	<? 
		if($anchor) {
		 echo "anchID = 'anch$anchor';";
	    }
	?>
	
		if(anchID !== ''){
			
			link = document.getElementById(anchID)
			
			if (ie || dom) {
			
				loc   = link.offsetTop
				parnt = link.offsetParent;
			
				while(parnt){
					
					loc		+= parnt.offsetTop
					parnt	=  parnt.offsetParent
				
				}
			
			}
			
		 }
		 
		else{
			
			 loc = 0;
		 
		}
	
		window.scroll(0,loc)
	
	}
	</script>
	
	<link rel="stylesheet" href="managestyle.css" type="text/css">
	
</HEAD>

<? 

$letter=stripslashes($letter);
$ltr=substr($letter,-3); 

$startingWith=$letter?(($letter=="<'A'")?"beginning with 'Other'":"beginning with $ltr"):"";

?>

<BODY onLoad=setUp()>
	
<? 
 echo "<strong>$categories[$cat]</strong> (".(($stock=='G')?'Gift':(($stock=='O')?'out of stock':(($stock=='R')?'Featured Items':'in stock'))).") sorted by ".substr($order,0,strpos($order,',')). " $startingWith";
?>

<table width=700 cellspacing=0 cellpadding=4 border=1 bordercolor="#A27178"><tr><td colspan=6>

<? 
//$letter=stripslashes($letter);
if (!$order ||$order=="") {$order= "pattern,brand,item"; }

if ($letter=="All"){ 
 echo "<font class=chosen>All</font> | "; 
}

else{
 echo "| <a href=display.php?order=$order&letter=All>All</a> | "; 
}

for ($i='A';$i<='Z';$i++) { 
	if ("='$i'"==$letter) { 
         echo "<font class=chosen>$i</font>"; 
        }
	else {
         echo "<a href=display.php?cat=$cat&order=$order&letter=".urlencode("='$i'").">$i</a>"; 
        }
	echo " | ";
	
        if ($i=='Z') { break; }
}

if ($letter=="<'A'") {
 echo "<font class=chosen>Other</font> |"; 
} 
else {
 echo "<a href=display.php?order=$order&letter=<'A'>Other</a> |"; 
}

echo"</td><TD COLSpAN=2><form ><select name=stock id='$stock' onChange=this.form.submit()><option value=I>In Stock<option value=O>Out of Stock<option value=R>Featured Items<option value=G>Gift</select></form></td></tr><tr> <th>";
if ($order=="pattern,brand,item") { 
 echo "** ";
}
?>

<a alt="Sort by pattern" href='display.php?cat=<? echo "$cat&letter=".urlencode($letter); ?>&order=pattern,brand,item'>Pattern</a>/
<a alt="Sort by manufacturer" href='display.php?cat=<? echo "$cat&letter=".urlencode($letter); ?>&order=brand,pattern,item'>Manufacturer</a>

</th><th><? if ($order=="item,brand,pattern") { echo "** ";}?><a href='display.php?cat=<? echo "$cat&letter=".urlencode($letter); ?>&order=item,brand,pattern'>Item</a></th><th>Image</th><th>Recent</th><th>Gift</th><th>Display</th></tr><form method=post >

<?


if ($letter) {
	if($letter!="All"){
         $letter=" and substring(".substr($order,0,strpos($order,',')).",1,1) $letter";
	}
    else{
     $letter="";
  }
}

if ($delete) { mysql_query("DELETE from inventory where id=$delete"); }

if($cat){ 
 $where= ($cat!='0')?"category like '%$cat%'":"bs!=''";
}
else{
 $where="category='f'";
}

$where.=($stock=='R')?" and time='n'":(($stock=='G')?" and gift='y'":(" and quantity".(($stock=='O')?"=0":'!=0'))); 

$query=mysql_query("SELECT brand,pattern,item,id,image,display,gift,time,monogram from inventory where $where  $letter order by $order");
while ($r=mysql_fetch_assoc($query)) {
$mon=($r[monogram] && $r[monogram]!=0)?"<span style=\"font-size:8pt\">(monogrammed)</span>":"";

$im=($r[image])?'Yes':'';
$by=($r[pattern])?'by':'';
	echo "<input type=hidden name=ids[] value=$r[id]><tr><td><a id=anch$r[id]>$r[pattern]</a> $by $r[brand]</td><td>$r[item]&nbsp;$mon</td><td>$im</td>
<td><input type=checkbox name=time[$r[id]] value='n' ".(($r[time])?" checked":'')."></td><td><input type=checkbox name=gift[$r[id]] value='y' ".(($r[gift])?" checked":'')."></td><TD><input type=checkbox name=display[$r[id]] value='1' ".(($r[display])?" checked":'')."></td><td><a href='edit.php?id=$r[id]' target=_top>Edit</a></td><Td><a href=# onClick=\"confirmDelete($r[id],'".addslashes("$r[item]: $r[pattern] by $r[brand]")."')\">Delete</a></td></tr>";
	} 
ob_flush();
?>
</table>
<input type=hidden name=update value=1><input type=submit value=Update>
</form>
<br><br><br><br><br><br><br>
</BODY>
</HTML>
