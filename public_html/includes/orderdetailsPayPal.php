<?php 
require("GzipCompress.php");
include("/connect/mysql_connect.php");
include("checkoutCalcs.php");

ob_start();
header("Cache-Control: no-store, no-cache, must-revalidate");  // HTTP/1.1
header("Pragma: no-cache");  
setcookie("items",$_COOKIE['items'],0,'/');

/*echo '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>';*/

?>

<!DOCTYPE html>
<html>
<head>
<title> As You Like It Silver Shop </title>
<meta name="description" content="As You Like It Silver Shop in New Orleans Louisiana specializes in silver flatware and holloware in active, inactive and obsolete patterns." />
<meta name="keywords" content="sterling silver, sterling flatware, silver flatware, antique silver, silver tableware, antique sterling, replacement silver, silver repair, corporate gifts, wedding gifts, silver identification, cleaning silver" />
<script language='javascript' src="js/store.js"></script>
<script language="javascript" src="js/images.js"></script>
<link rel="stylesheet" href="ayliss_style.css" type="text/css">
</head>

<body class="sub">


<table width="100%" cellpadding="0" cellspacing="0" border="0" align="left" bgcolor="#A27177" height="85">
<tr>
	<td width="760" background="images/ayliss_title_r.jpg" alt="" border="0" align="right" valign="top"><img src="images/blank.gif" width="760" height="1" alt="As You Like It Silver Shop" border="0"><br><p class="homenav"><!--<a href="contactus.htm" class="top">CONTACT US</a> * <a href="" class="top">VIEW CART</a> *--> 1-800-828-2311</td>
	<td width="*"><img src="images/blank.gif" width="10" height="1" alt="" border="0"></td>
</tr>
</table>
<br clear="all">
<table width="100%" cellpadding="0" cellspacing="0" border="0" align="left">
<tr bgcolor="#000000">
	<td width="760" bgcolor="#000000" align="center"> 
		<p>
		<p><b class="orderstep">1.</b> <b class="orderon">Order Details</b>
		<img src="images/blank.gif" width="15" height="1" alt="" border="0"> 
		<b class="orderstep">2.</b> <b class="orderoff">Shipping Information</b>
		<img src="images/blank.gif" width="15" height="1" alt="" border="0">
		<b class="orderstep">3.</b> <b class="orderoff">Billing Information</b>
		<img src="images/blank.gif" width="15" height="1" alt="" border="0">

		<b class="orderstep">4.</b> <b class="orderoff">Confirm Order</b>
		<img src="images/blank.gif" width="15" height="1" alt="" border="0">
		<b class="orderstep">5.</b> <b class="orderoff">Order Summary</b>
	</td>
	<td width="*"><img src="images/blank.gif" width="10" height="29" alt="" border="0"></td>
</tr>
<tr bgcolor="#7A121B">
	<td width="760" bgcolor="#7A121B" align="center"> 
		<img src="images/blank.gif" width="10" height="20" alt="" border="0">
	</td>
	<td width="*"><img src="images/blank.gif" width="10" height="20" alt="" border="0"></td>
</tr>
<tr>
	<td width="760"><img src="images/t_orderdetails.gif" width="760" height="66" alt="Order Details"></td>
	<td width="*"><img src="images/blank.gif" width="10" height="1" alt="" border="0"></td>
</tr>
</table>
<br clear="all">

<table width="760" border="0" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td width="50"><img src="images/blank.gif" width="50" height="1" alt="" border="0"></td>
	<td align="left">
		<p>

<!--order details-->	
<form name="itemsForm">	
<table width="100%" cellpadding="5" cellspacing="0" border="0">

<tr>
	<td colspan="5"><img src="images/blank.gif" width="10" height="10"></td>
</tr>

<tr>
	<th width="" class="search">Item</th>    
	<th width="" class="search" style=text-align:center>Price</th>
	<th width="" class="search" style=text-align:center>Quantity</th> 
	<th width="" class="search">&nbsp;</th>
</tr>


<? 
$content="";

$sub=$total=$shipping=$insurance=$tax=0;$swap=1;
if (!$_COOKIE[items]) {
	$content.=	 "
	<tr>
		<td colspan=\"4\">Your Cart is empty</td>
	</tr>
	<tr>
		<td colspan=\"4\"><a href=\"http://www.asyoulikeitsilvershop.com\">Continue Shopping</a></td>
	</tr>";
	
	}
else {

//split cookie data into array $item using & delimiter
//cookie data stored as &id1:quantity1&id2:quantity2 etc etc

$item=explode('&',substr($_COOKIE['items'],1));

$itemCount=count($item);

$inventoryMessage='';
foreach ($item as  $v) { 
	$ne='';
	$swap=$swap^1;if ($swap) {$td='bgcolor=#ffeedd'; } else {$td=''; }
	
	//split each $item array value $v (id:quantity into array $i using ':' delimiter
	$i=explode(':',$v);
	
	
	if(substr($i[0],0,2)=="gc"){
         $email=$i[2];
         $amount=$i[1];
         $gcSubtotal+=$i[1];
         
         $content.= "
			<tr>
				<td $td>AS YOU LIKE IT SILVER SHOP GIFT CARD</td>
				<td $td align=center><b>
					<input class=\"invoiceQty\" type='text' size='4' value='$amount' id=\"quantity$i[0]\" name=quantity{$i[0]}></b><input name=\"email$i[0]\" type=\"hidden\" value=\"$email\">
				</td>
                <td $td align=center><b>1</b></td>
				<td $td align='center'><!-- <a class=\"removeButton\" href=\"javascript:removeItem('$i[0]')\">X</a>--></td>	
			</tr>";
	}	
	
	else{
		$query=mysql_query("SELECT * from inventory where id=$i[0]");
		$r=mysql_fetch_assoc($query);
		$template=strtolower($r[category]);
		$template=($template=='fcs' || $template=='sp')?'f':$template;
	
		//double check to make sure purchase quantity is valid considering current stock
		//if not, adjust cookie data accordingly
		if ($i[1]>abs($r[quantity])) {
			$ne=$i[1];
			$i[1]=abs($r[quantity]);
		
		
			if ($r[quantity]==0) { 
				//delete value from cookie
				$_COOKIE['items']=str_replace("&$v",'',$_COOKIE['items']);
				$inventoryMessage.="<b>Sorry, $r[item] ($r[pattern] by $r[brand]) is currently out of stock.</b><br>";
				continue;
			}
		
			else {
				$_COOKIE['items']=str_replace("&$v","&$i[0]:$i[1]:$i[2]",$_COOKIE['items']);
				$inventoryMessage.="<b>Sorry, we only have $i[1] $r[item] ($r[pattern] by $r[brand]) in stock.</b><br>";
			}
		}
		
		if($r[sale]){ 
			$price=$r[sale]; 
		} 
		else{ 
			$price=$r[retail];
		}
		
		$by=($r[pattern] && $r[brand])?"BY ":'';
		$payPalDescription.="$r[pattern] $by $r[brand] $r[item] - ".format($price)."\n\r";
		$content.= "
			<tr>
				<td $td><b><a href='/showItem.php?product=$r[id]' class='search'>$r[pattern] $by $r[brand] $r[item]</a></td>
				<td $td align=center><b>\$".format($price)."</b></td>
				<td $td align=center><b><input class=\"invoiceQty\" type='text' size='2' value='$i[1]' id=\"quantity$i[0]\" name=quantity{$i[0]}></b></td>
				<td $td align='center'><!-- <a class=\"removeButton\" href=\"javascript:removeItem($i[0])\">X</a> --></td>	
			</tr>";
          $sub+=$price*$i[1];
	}
	$idList.="$i[0],";
        
	
	}
	
	if ($inventoryMessage) { $content.= "<tr><td colspan=5>$inventoryMessage</td></tr>"; }


$ship=calculateShipping($sub);

//use only the not gift card amounts to determine shipping

//add gift card and non gift card subtotals
$sub+=$gcSubtotal;

$total=$sub+$ship;

$content.="
<tr>
	<th colspan='4' align='center'><img src='images/blank.gif' width='1' height='1'></th>	
</tr>
<tr>
	<td><b class='subtotal'>Product Subtotal</b></td>
	<td align=right><b class='subprice' >$". format($sub)."</b></td>
	<td align='right' rowspan=3 colspan=\"2\" valign=top>
	<input type='button' value='Update Quantities' onClick=\"updateItems('".substr($idList,0,-1)."')\"></td>	
</tr>";



$content.="
<tr>
	<td><b class='subtotal'>Shipping & Insurance</b></td>
	<td  align=right><b class='subprice'>$".format($ship)."</b></td>	
</tr>

<tr>
	<td><b class='grandtotal'>ORDER TOTAL</b></td>
	<td  align=right><b class='totalprice'>$". format($total)."</b></td>
</tr>
<tr>
	<th colspan='4' align='center'><img src='images/blank.gif' width='1' height='1'></th>	
</tr>
</form>
<tr>
<td colspan='4' align='center'>
<form method=\"POST\" action=\"https://pilot-payflowpro.paypal.com\">
<input type=\"hidden\" name=\"USER\" value=\"AsYouLikeIt925\">
<input type=\"hidden\" name=\"VENDOR\" value=\"AsYouLikeIt925\">
<input type=\"hidden\" name=\"LOGIN\" value=\"AsYouLikeIt925\">
<input type=\"hidden\" name=\"PARTNER\" value=\"PayPal\">

<input type=\"hidden\" name=\"DESCRIPTION\" value=\"".$payPalDescription."\">

<input type=\"hidden\" name=\"AMOUNT\" value=\"".format($total)."\">

<input type=\"hidden\" name=\"TYPE\" value=\"S\">

<input type='button' value='Continue Shopping' onClick=\"location='/'\">
<input type=\"submit\" value=\"Check Out\">

</form>

<!--<form action='https://www.asyoulikeitsilvershop.com/processInfo.php'>
<input type=hidden value=getInfo name=action>
<tr>
	<td colspan='4' align='center'><input type='button' value='Continue Shopping' onClick=\"location='/'\">&nbsp;
<input type=hidden name=items value='".  $_COOKIE[items]."'><input type='submit' value='Check Out'></td>
</tr>-->
";
}

echo $content; 

?>


</table>


</td></tr></table>
<!--end order details-->

<br clear="all">
<table width="760" cellpadding="0" cellspacing="0" border="0" align="left">
<tr>
	<td width="150"><img src="images/blank.gif" width="50" height="1" alt="" border="0"></td><tr>
	<td valign="top">
		<hr width="710" noshade size="1" color="#A27177" align="right"></tr><tr>
		<td align ="right"> <script src="https://siteseal.thawte.com/cgi/server/thawte_seal_generator.exe">
</script></td></tr><tr><td><p class="bottom">&copy; Copyright 2003. As You Like It Silver Shop.</p></td></tr>
		<p>&nbsp</p>
	</td>
</tr> 
</table>
<? 

if (strstr($HTTP_SERVER_VARS['HTTP_ACCEPT_ENCODING'], 'gzip'))
{	flush(); }
?>
</body> 
</html>    
<? ob_flush(); ?>