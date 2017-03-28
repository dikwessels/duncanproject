<?
include("/home/asyoulik/connect/mysql_connect.php");

/*
if ($_GET['cat']) {
 $cat=stripslashes($_GET['cat']);	
 header("Set-Cookie: cat=$cat;'';path=/");
}
else  if ($_COOKIE['cat']) {
 $cat=$_COOKIE['cat']; 
} 
else { exit; }


 if ($_GET['letter']) {
  //$letter=stripslashes($_GET['letter']);	
  header("Set-Cookie: letter=$letter;'';path=/");
 }

 else  if ($_COOKIE['letter']) {
  $letter=$_COOKIE['letter']; 
 } 

//echo $_GET['letter'];
if ($_GET['order']) {
  $order=stripslashes($_GET['order']);	
  header("Set-Cookie: order=$order;'';path=/");
}
else  if ($_COOKIE['order']) {
  $order=$_COOKIE['order']; 
} 

if ($_GET['retail']) {
  foreach ($id as $k=>$v) {
   mysql_query("UPDATE inventory set retail='$retail[$k]' where id=$v");
  }
}

if ($update) {
  foreach($id as $k=>$v) {
   mysql_query("UPDATE inventory set retail='$retail[$k]' where id=$v");
  }
}

*/

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

top.topframe.cat='<?echo $cat; ?>'
ie=1; dom=0
function setUp() {
link=document.getElementById("anch<?echo $anchor;?>")
	if (ie || dom) {
	loc=link.offsetTop
	parnt=link.offsetParent;
	while (parnt) {
	loc+=parnt.offsetTop
	parnt=parnt.offsetParent
		}
	}
window.scroll(0,loc)

}
	</script>
<link rel="stylesheet" href="managestyle.css" type="text/css">
</HEAD>
<BODY <? if ($anchor) {?>onLoad=setUp()<?}?>>
<form method=post>
<input type=submit value=update>
<table width=600 cellspacing=0 cellpadding=4 border=1 bordercolor="#A27178">
<tr>
<td colspan="4">

<?
//$letter=stripslashes($letter);

if (!$order) {$order= "item,brand,pattern"; }

if ($letter=="") {
 echo "<font class=chosen>All</font> | "; 
} 

else {
 echo "| <a href=displayPS.php?order=$order&letter=>All</a> | "; 
}

for ($i='A';$i<='Z';$i++) { 
	if ($i==$letter) { 
         echo "<font class=chosen>$i</font>"; 
        }
	else {
          echo "<a href=displayPS.php?order=$order&letter=$i".">$i</a>"; 
        }

	echo " | ";
	  if ($i=='Z') { break; }
	
}

if ($letter=="<'A'") { 
    echo "<font class=chosen>Other</font> |"; 
  } 

else{
  echo "<a href=displayPS.php?order=$order&letter=<'A'>Other</a> |"; 
}

?>

 </td>
 </tr>
<tr> 
<th>

<? 
  if ($order=="brand,pattern,item") { echo "** ";}
?>

<a href='displayPS.php?cat=<? echo$cat; ?>&order=brand,pattern,item'>Pattern and Manufacturer</a></th>
<th>
 <? if ($order=="item,brand,pattern") { echo "** ";}?>
 <a href='displayPS.php?cat=<? echo$cat; ?>&order=item,brand,pattern'>Item</a>
</th>
<th>Retail</th>
</tr>

<?


if ($letter) {
 $letter=" and substring(".substr($order,0,strpos($order,',')).",1,1)='$letter'";
 //echo "Letter Variable:$letter<br>";
}

if ($delete){ 
 mysql_query("DELETE from inventory where id=$delete"); 
}

$j=0;
$cat="ps";

if ($cat){ 

  $query="SELECT * from inventory where category='ps' $letter order by $order";
  $result=mysql_query($query);

  //$query=mysql_query("SELECT * from inventory where category like '%$cat%'  $letter order by $order");
  //echo "SELECT * from inventory where category like '%$cat%'  $letter order by $order";

  while($r=mysql_fetch_assoc($result)) {
	echo "<tr><td>";
	if ($r[pattern]!=$cP){
	  echo "<b><a id=anch$r[id]>$r[pattern]</a> by $r[brand]</b>";
	  $cP=$r[pattern];
	}

	echo"</td>
             <td>$r[item]</td>
             <td>
                 <input type=hidden name=id[] value=$r[id]>
                 <input name=retail[] value=$r[retail] size=\"9\">
             </td>
             </tr>";
   $j++;
   //end while
  } 

  if ($j==0){
    echo"<tr><td>There are no place settings for this letter.</tr></td>";
  }
 
// end if($cat)
}

?>

</table>
<input type="hidden" name="update" value="1">
<input type="submit" value="update">
</form>
<br><br><br><br><br><br><br>
</BODY>
</HTML>
