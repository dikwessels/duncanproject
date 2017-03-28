<html>
<head>
<title> As You Like It Silver Shop </title>

<link rel='stylesheet' href='managestyle.css' type='text/css'>
<script language='javascript'>
function Send(i,elem,form) {
	location="showOrders.php?shipped="+i+"&status=p&tracking="+form.elements[elem].value
	}
</script>
</head>


<BODY class='sub' onLoad="javacript:document.forms[0].target='bottom_bottom'" style="padding-left:20px;"><form>

<?
	ini_set("display_errors",1);
	
extract($_POST);
extract($_GET);

//include_once("/connect/mysql_pdo_connect.php");

function cleanUp() {
	
	//mysql_query("DELETE  from customers where now()-time>1000000 and status=''");
	mysql_query("OPTIMIZE TABLE customers");

}

function format($num) {
	$num*=100;$num="$num";
	return (substr($num, 0,-2).".".substr($num,-2));
	}

function getRegistryMailingAddress($regID){
	
	global $db;
		
	/*$query = $db->prepare("SELECT * from weddingRegistries WHERE id=:id");
	
	$query->bindParam(":id",$regID);
	
	$query->execute();
	
	$result = $query->fetchAll();
	
	$row = $result[0];
	*/
	
	$result = mysql_query("SELECT * from weddingRegistries WHERE id = $regID");
	
	$row = mysql_fetch_assoc($result);
	
	extract($row);
	
		if($raddress) {
			
			$address=$rfname." ".$rlname.", ".$raddress.", ".$rcity.", ".$rstate." ".$rzipcode;	
		}
		else {
			$address="";
		}
		
	return $address;	
}

function email($on) {
	
	$query=mysql_query("SELECT * from customers where customerNum=$on");
	
	$row=mysql_fetch_assoc($query);
		foreach($row as $k => $v) {
			$$k=$v;
			}

$carrier="USPS (3-7 business days).";

if(strchr($tracking,"1Z70583")==$tracking){
		$carrier="UPS.";
	}
	
if(strchr($tracking,"01995")==$tracking){
		$carrier="FEDEX.";
}

$item=split('&',substr($items,1));
$invoiceItems = "";

foreach($item as $v){
	
			$itm=explode(':',$v);
			
			if(($itm[0]=="000000") || substr($itm[0],0,2) == "gc" ){
	                        
	                    $registryData = explode("||", $itm[2]);
	                        
	                    $registryID = $registryData[0];
	                    $giftCardCode = $registryData[1];
	                        
	                    $deliveryTo = getRegistryMailingAddress($registryID);
	                        
                        $invoiceItems .= str_pad("1 $$itm[1] AS YOU LIKE IT SILVER SHOP GIFT CARD",69);
                        $invoiceItems .= str_pad("\$".format($itm[1]),9);
						$invoiceItems .=	str_pad("\$".format($itm[1]),9);
						$invoiceItems .= "\n\r".str_pad("Gift Card will be delivered to: $deliveryTo",87);  	    
                    
             }
			 else{
						
						$q = mysql_query("SELECT item,brand,pattern,retail,sale from inventory where id={$itm[0]}");
						$row = mysql_fetch_assoc($q);
					
						if ($row[sale]) { $price=$row[sale]; } else  { $price=$row[retail];	}
						
							$invoiceItems .= str_pad($itm[1],9);
							$invoiceItems .= str_pad("$row[pattern] by $row[brand]--$row[item]",60);
							$invoiceItems .= str_pad("\$".format($price),9)."$".format(($itm[1]*$price))."";					
					
					}
		}

if($shippingMethod == 5){

	$shippingHeader = "Your order has been completed and is ready for pickup at our store, located at 3033 Magazine St, New Orleans, LA 70115. We are open  11am - 5pm Tuesday through Friday, and 10:30am-5pm on Saturday. Thank you for your business!"; 
	
	$shippingFooter = "";
}
else{
	
	$shippingHead = "Your order has been completed and shipped. 
		Thanks for with us, and we hope you visit our site again soon.";
		
	$shippingFooter = "This shipment was sent to:

$fname $lname
$address1 $address2
$city, $state $zip
			
via $carrier

For your reference, the number you can use to track your package is
$tracking";

}

$email="Greetings from asyoulikeitsilvershop.com

$shippingHeader

The following items were included in this shipment:
--------------------------------------------------------------------------------------------------
Qty      Item                                                        Price    Subtotal
--------------------------------------------------------------------------------------------------

$invoiceItems


--------------------------------------------------------------------------------------------------
               Item Subtotal:  ".str_pad("\$".format($subtotal),7,' ',STR_PAD_LEFT)."
                         Tax:  ".str_pad("\$".format($tax),7,' ',STR_PAD_LEFT)."
         Shipping & Handling:  ".str_pad("\$".format($shipping),7,' ',STR_PAD_LEFT)."


                       Total:  ".str_pad("\$".format(($subtotal+$tax+$shipping)),7,' ',STR_PAD_LEFT)."


--------------------------------------------------------------------------------------------------

$shippingFooter


---------------------------------------------------------------------
Please note: This e-mail was sent from a notification-only address
that cannot accept incoming e-mail. Please do not reply to this message.
Thank you for shopping at asyoulikeitsilvershop.com.

---------------------------------------------------------------------
www.asyoulikeitsilvershop.com
---------------------------------------------------------------------
";



$query=mysql_query("SELECT cardemail from customers where customerNum=$customerNum");
$mailto=mysql_result($query,0);
mail($mailto,"Your 'As You like It Silver Shop' order has been shipped",$email,'From: orders@asyoulikeitsilvershop.com');

}

include("/home/asyoulik/connect/mysql_connect.php");

$methods=array(
			4=>'Ground Ship',
			10=>'3-day select',
			27=>'Second day air',
			53=>'Next day air'
			);
			
$newShipMethods=array(
			0=>'Ground Ship',
			1=>'3-day select',
			2=>'Second day air',
			3=>'Next day air',
			4=>'Gift Card (USPS)',
			5=>'In-store Pickup'
			);

$cardtypes=array(
		''=>'PayPal Payment',
		'0'=>'Visa',
		'1'=>'MasterCard',
		'2' => 'Discover',
		'3' => 'American Express', 
		'4' => "Diner's Club",
		'5' => 'JCB',
		'visa' => 'Visa',
		'mastercard' => 'MasterCard',
		'amex' => 'American Express',
		'american express' => 'American Express',
		'discover' => 'Discover',
		'paypal' => 'PayPal',
		'-1'=>'As You Like It Gift Card'
		);
		

if (isset($status)) {
	if ($status=='') {exit; }
	if (isset($processed)) {
		mysql_query("UPDATE customers set status='processed' where customerNum=$processed");
		//emailprocessed();
		}

	if (isset($cancel)) {	
		mysql_query("UPDATE customers set status='cancelled' where customerNum=$cancel");
		//emailfailed();
		}
	if (isset($shipped)) {
		mysql_query("UPDATE customers set status='shipped', tracking='$tracking' where customerNum=$shipped");
	email($shipped);
	}
	$i=0;
	$query=mysql_query("SELECT *,date_format(time,'%m/%d/%Y') as t from customers where status='$status'  order by time DESC");
	while ($row=mysql_fetch_assoc($query)) {
		foreach($row as $k => $v) {
			$$k=$v;
			}
		$item=split('&',substr($items,1));
	#	$y=substr($time,0,4);$m=substr($time,4,2);$d=substr($time,6,2);
	
	$testtime=mktime(0,0,0,11,29,2013);
	$testtime=date('Y-m-d H:i:s',$testtime);
	
	   if($time<$testtime){	
	   		$shippingDescription=$methods[$shippingMethod];
	   		}
	   		else{
		   		$shippingDescription=$newShipMethods[$shipMethod];
		 }
	  
		echo "$t<table width=90% border='1'>
                <tr>
                    <td><h3>Order Number: $customerNum</h3></td>
                </tr>
                <tr>
                    <td width=75%>
                    <table width=100% border=1>
                        <tr>
                            <td align=center width=70%>SHIPPING ADDRESS</td>
                            <td rowspan=5 width=30% valign=top><br>Shipping Method: $shippingDescription<br>";
		if ($giftwrap) { echo "Gift Wrap: $giftwrap<br>"; }
		if ($note) { echo "<table ><tr><td align=center style=text-decoration:underline;>Note</td></tr><tr><td style=border-width:1px;border-style:solid;padding:4px>$note</div></td></tr></table>"; }
		echo "<br>";
		if ($status=='pending') { echo "<input type=button value='Credit Approved' onClick=\"self.location='showOrders.php?status=pending&processed=$customerNum';\"><br><input type=button value='Credit Declined' onClick=\"location='showOrders.php?status=pending&failed=$customerNum';\">"; 
		}
		else if ($status=='cancelled') { echo "<input type=button value='Credit Approved' onClick=\"self.location='showOrders.php?status=processed&processed=$customerNum';\">";
		}
		else if ($status=='processed') {echo "Tracking#:<br><input name=i$i><input type=button value=Ship onClick=\"javascript:Send($customerNum,'i$i',this.form)\"><br><input type=button value='Cancel' onClick=\"javascript:self.location='showOrders.php?status=cancelled&cancel=$customerNum'\">";
			}
		else { echo "Tracking #: $tracking"; }
		echo "</td></tr><tr><td width=70%>$fname $lname</td></tr><tr><td>$address1 $address2</td></tr><tr><td>$city,$state $zip</td></tr><tr><td><table cellspacing=6><tr><td colspan=2>ITEMS</td></tr>";
		
                foreach($item as $v) {
                        $itm=explode(':',$v);
			
						  if(($itm[0]=="000000") || substr($itm[0],0,2) == "gc" ){
	                        echo "<!-- this is a gift card $itm[2]--->";
	                        
	                        $registryData = explode("||", $itm[2]);
	                        
	                        echo "<!-- gift card data $registryData[0] $registryData[1] -->";
	                        $registryID = $registryData[0];
	                        $giftCardCode = $registryData[1];
	                        
	                        $deliveryTo = getRegistryMailingAddress($registryID);
	                        
                          echo "
                          	<tr>
                          	<td colspan='2'>1 $$itm[1] AS YOU LIKE IT SILVER SHOP GIFT CARD<br>
                          		Card Code: $giftCardCode <br> 
						  		Delivery To: $deliveryTo
						  	</td>
                          	</tr>";
                        }

                       /* if($itm[0]=="000000"){
                          echo "<tr><td colspan=\"2\">1 $$itm[1] AS YOU LIKE IT SILVER SHOP GIFT CARD</td></tr>";
                        }*/
                        
                        else{
                          $q=mysql_query("SELECT productId,item,brand,pattern,retail,sale from inventory where id={$itm[0]}");
			  $info=mysql_fetch_assoc($q);
			  $price=($info[sale])?$info[sale]:$info[retail];
			  echo "<tr><td><a href='edit.php?id=$itm[0]' target=display>$info[productId]: $info[pattern] by $info[brand] -- $info[item]</a> (\$$price)</td><td>$itm[1]</td></tr>";
			}
                }

$cardtype=strtolower($cardtype);                
		echo "</table></td></tr></table></td>
			<td valign=top width=25%>
				<table style=font-size:12px valign=top width=100%>
					<tr>
						<td colspan=2 align=center >BILLING INFO</td>
					</tr>
					<tr>
						<td STYLE=FONT-SIZE:14PX>Card Type:$cardtypes[$cardtype]<br>$cardnumber<br> exp:$exp<BR>\$".($subtotal+$shipping+$tax)."</td></tr><tr><td>$cardname</td></tr><tr><td>$cardaddress</td></tr><tr><td>$cardcity, $cardstate $cardzip</td></tr><tr><td>";
						
			if($cardphone){
				$contactphone=$cardphone;
				}
				else{
					$contactphone=$phone;
			}
			
		echo "$contactphone</td></tr><tr><td>$cardemail</td></tr></table></td></tr></table>";
	$i++;
		}
	if ($status=='shipped') {
		echo "<a href='http://asyoulikeitsilvershop.com/manage/download.php'>Download transactions</a>"; }
	}
else {
cleanUp();
	?>
<table width=100%><tr><td><FORM target="bottom_bottom">	SELECT THE ORDERS YOU WOULD LIKE TO VIEW<br><SELECT name=status onChange=this.form.submit()><option value=''>Choose Below<option value=processed>Unshipped Orders<option value=shipped>Shipped Orders<option value=downloaded>Downloaded Orders<option value=cancelled>Cancelled Orders</select></form></td>
</tr></table>
<?
}
?>

</form>	
</BODY>
</HTML>
