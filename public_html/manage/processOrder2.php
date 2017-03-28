<?php 

include("louisianaZips.inc");
include_once('socketMail.php');
$mail="Please note the order number in the subject line of this email. We are
pleased to inform you that your order has been verified and is currently
being processed.  Below is a copy of your invoice.  Please note; a tracking
number will be sent to you via e-mail after your order has shipped
successfully.

Thank you for shopping at www.asyoulikeitsilvershop.com and have a great
day!

Best regards,

As You Like It Silver Shop Web Team
www.asyoulikeitsilvershop.com

Try our monogramming service: 1-800-828-2311
";

function format($num) {
	$num*=100;$num="$num";
	return (substr($num, 0,-2).".".substr($num,-2));
	}
  

include("/home/asyoulik/connect/mysql_connect.php");

$x_Test_Request='True';
$x_Type="AUTH_CAPTURE";  
$x_Cust_ID=$_COOKIE["custNum"];
$x_Login="ayls246";    
$x_Password="no12kia12";     
$x_Delim_Data="TRUE";    
$x_Delim_Char=",";      
$x_Encap_Char="";   
$query=mysql_query("SELECT transkey FROM  `transactionKey` ");
$x_Tran_Key=mysql_result($query,0);
if (!$_COOKIE["custNum"]) {
	require('sessionexpired.php');
	exit;
	}

$query=mysql_query("SELECT cardname,cardnumber,exp,cardaddress,cardcity,cardstate,cardzip,subtotal,tax,shipping from customers where customerNum=".$_COOKIE["custNum"]);	
$row=mysql_fetch_assoc($query);
	foreach($row as $k => $v) {
		$$k=$v;
		}
$x_Method="CC";
$amount=$subtotal+$shipping+$tax;
preg_match("/([\w ]+?) (\w+)$/",$cardname,$a);$fname=$a[1];$lname=$a[2];

$fields="x_Tran_Key=$x_Tran_Key&x_Version=3.1&x_Login=$x_Login&x_Delim_Date=$x_Delim_Data&x_Delim_Char=$x_Delim_Char&x_Encap_Char=$x_Encap_Char";
$fields.="&x_Type=$x_Type&x_Test_Request=$x_Test_Request&x_Method=$x_Method&x_Amount=$amount&x_First_Name=$fname&x_Address=$cardaddress&x_City=$cardcity&x_State=$cardstate&x_Zip=$cardzip";
$fields.="&x_Last_Name=$lname&x_Card_Num=$cardnumber&x_Exp_Date=$exp&x_Cust_ID=$x_Cust_ID&x_Invoice_Num=". $_COOKIE['custNum'];
$fields=str_replace(' ','+',$fields);
if($x_Password!=''){  $fields.="&x_Password=$x_Password";}

mysql_query("LOCK table inventory write");
$invoiceItems='';
$item=explode('&',substr($_COOKIE['items'],1));
foreach ($item as  $v) { 
	$i=explode(':',$v);	
	$query=mysql_query("SELECT retail,sale,item,pattern,brand from inventory where id=$i[0] and (quantity>=$i[1] or quantity<0)");
	if (!mysql_num_rows($query)) { 	
		header("LOCATION:http://lynx.phpwebhosting.com/~ayliss/processInfo.php?action=showOrder");
		exit; }
	$r=mysql_fetch_assoc($query);
	$price=($r[sale])?$r[sale]:$r[retail];
	$invoiceItems.= str_pad($i[1],9).str_pad("$r[item] -$r[pattern] by $r[brand]",60).'$'.str_pad(format($price),8)."$".format(($i[1]*$price))."
";
	}


$ch=curl_init();
#curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,0);
curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0);
curl_setopt($ch, CURLOPT_URL,"https://secure.authorize.net/gateway/transact.dll"); 
curl_setopt ($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
curl_setopt($ch, CURLOPT_VERBOSE,1);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);  
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);    
$buffer = curl_exec($ch);                     
curl_close($ch);                             


$details=explode($x_Delim_Char,$buffer);      
include('ordersummary.htm');

if ($details[0]=='1') {
	$item['4 Piece Dinner']=array('Dinner Knife','Dinner Fork','Salad Fork','Teaspoon');
	$item['4 Piece Lunch/Place']=array('Lunch/Place Knife','Lunch/Place Fork','Salad Fork','Teaspoon');
	$item['4 Piece Place']=array('Place Knife','Place Fork','Salad Fork (Place Size)','Salad Fork','Teaspoon');
	setcookie('custNum','',time() - 3600,'/');
		setcookie('items','',time() - 3600,'/');
	foreach ($item as  $v) { 
		$i=explode(':',$v);	
		$q=mysql_query("SELECT item,quantity as q,pattern, brand,category from inventory where id=$id");
		$r=mysql_fetch_assoc($q);extract($r);
		if ($category='ps') {
			list($type,$blank)=split(" ",$item);
			foreach($item[$type] as $v) {
			if (mysql_affected_rows() && $v=='Salad Fork (Place Size)') { continue; }		
			$statement="UPdate  inventory set quantity=(IF (quantity>-1,quantity-".$i[1].",quantity)) WHERE pattern='$pattern' and 	brand='$brand' and item='$v' and monogram==0 limit 1";
			mysql_query($statement);	
			}
		else {
			$update="UPDATE inventory set quantity=(IF (quantity>-1,quantity-".$i[1].",quantity)) where id=$i[0]";	
			mysql_query($update);
			}
		}
mysql_query("UNLOCK tables");
	mysql_query("UPDATE customers set items='".$_COOKIE['items']."',status='processed',transactionID='$x_Tran_Key',time=now(),cardnumber=concat(left(cardnumber,4),right(cardnumber,4)) where customerNum=".$_COOKIE["custNum"]);

	$query=mysql_query("SELECT * from customers where customerNum=".$_COOKIE["custNum"]);
	$row=mysql_fetch_assoc($query);
		foreach($row as $k => $v) {
			$$k=$v;
		}

echo "	<h1>Thank You</h1>						<p>Your order has been received. <br>			Your order confirmation number is ".$_COOKIE["custNum"].".<br>			An email confirmation has been sent to $cardemail.<br>";
					
$mail="Dear $cardname,
	
".$mail;

$invoice="

INVOICE___________________________________________________________________________________________

BILLING ADDRESS:

$cardname
$cardaddress
$cardcity, $cardstate $cardzip
$cardphone
$cardemail
xxxx-xxxx-xxxx-".substr($cardnumber,-4). "

SHIPPING ADDRESS:

$fname $lname
$address1 $address2
$city, $state $zip
$phone

ITEMS ORDERED:

--------------------------------------------------------------------------------------------------
Qty      Item                                                        Price    Subtotal
--------------------------------------------------------------------------------------------------
";


$invoice.=$invoiceItems.="
--------------------------------------------------------------------------------------------------
               Item Subtotal:  ".str_pad("\$".format($subtotal),7,' ',STR_PAD_LEFT)."
                         Tax:  ".str_pad("\$".format($tax),7,' ',STR_PAD_LEFT)."
         Shipping & Handling:  ".str_pad("\$".format($shipping),7,' ',STR_PAD_LEFT)."

                       Total:  ".str_pad("\$".format($amount),7,' ',STR_PAD_LEFT)."


-------------------------------------------------------------------------------------------------

";

#$email=array("$cardname"=>"$cardemail");
#socketmail($email,"'As You Like It Silver Shop' Order #{$_COOKIE['custNum']}",($mail.$invoice),"orders@ayliss.com","As You Like It Silver Shop");
mail("$cardemail","'As You Like It Silver Shop' Order #{$_COOKIE['custNum']}",$invoice,"From:orders@asyoulikeitsilvershop.com");
mail("dcox@asyoulikeitsilvershop.com","'You just received order#{$_COOKIE['custNum']}",$invoice,"From:orders@asyoulikeitsilvershop.com");
mail("kirsten@stoffaproductions.com","'You just received order#{$_COOKIE['custNum']}",$invoice,"From:orders@asyoulikeitsilvershop.com");
	}	
else {
	echo "<h1>Sorry</h1><p>Your transaction was declined for the following reason:<br><B> $details[3]</b><br>";
	}
	
?>
		</td></tr></table>
<!--END CONFIRM ORDER-->
<p align="center"><input type="button" onClick="javascript:location='http://asyoulikeitsilvershop.com/'" value="Return to the Home Page"></a>		

	</td>
</tr>
</table>
<br clear="all">
<table width="760" cellpadding="0" cellspacing="0" border="0" align="left">
<tr>
	<td width="50"><img src="images/blank.gif" width="50" height="1" alt="" border="0"></td>
	<td valign="top">
		<hr width="710" noshade size="1" color="#A27177" align="left">
		<p class="bottom">&copy; Copyright 2003. As You Like It Silver Shop.</p>
		<p>&nbsp</p>
	</td>
</tr>
</table>
</body>
</html>