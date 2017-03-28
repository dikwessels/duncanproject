<?php 

function format($num) {
  $num*=100;
    if($num>0){
      $num="$num";
      return (substr($num, 0,-2).".".substr($num,-2));
    }
    else{
     $num="0.00";
      return $num;
    }
}

function sendConfirmationEmail($msg,$emailAddress,$orderID,$version){

$headers.= "MIME-Version: 1.0\r\n";
$headers.= "Content-Type: text/html; charset=ISO-8859-1\r\n";
$headers.="From:orders@asyoulikeitsilvershop.com";

$subject=($version=="customer")?"As You Like It Silver Shop Order #".$orderID:"You Have Received Order #".$orderID;

//send shop email
if($version=="shop"){
	mail("dcox@asyoulikeitsilvershop.com",$subject,$msg,$headers);
	mail("cduncancox1962@gmail.com",$subject,$msg,$headers);
	mail("wagner_michaeljames@yahoo.com",$subject,$msg,$headers);
}
else{
	//send customer email
	mail($emailAddress,$subject,$msg,$headers);
}

}


function sendShopConfirmation($msg,$emailAddress,$orderID){

$headers.= "MIME-Version: 1.0\r\n";
$headers.= "Content-Type: text/html; charset=ISO-8859-1\r\n";
//$headers.="Content-Length: ".len($msg)."\r\n";
$headers.="From:orders@asyoulikeitsilvershop.com";
$subject="You Have Received Order #".$orderID;

	mail("dcox@asyoulikeitsilvershop.com",$subject,$msg,$headers);
	mail("cduncancox1962@gmail.com",$subject,$msg,$headers);
	mail("wagnermichaeljames@gmail.com",$subject,$msg,$headers);

}

function composeEmail($orderID,$invoiceData,&$ordersummary){
global $orderitems;
global $ordertotals;
global $shippingbilling;


$shipText=array(
		0=>"Ground 8-10 days",
		1=>"3-day select",
		2=>"Second day air",
		3=>"Next day air"
		);

$shipto=urldecode($invoiceData['SHIPTONAME']);
$address1=urldecode($invoiceData['SHIPTOSTREET']); //1%20Main%20St
$city=urldecode($invoiceData['SHIPTOCITY']);// San%20Jose
$state=urldecode($invoiceData['SHIPTOSTATE']);// CA
$country=urldecode($invoiceData['SHIPTOCOUNTRY']);// US
$zip=urldecode($invoiceData['SHIPTOZIP']);// 95131

$shipping=$_SESSION['shipping'];
$shippingMethod=$_SESSION['shippingsurcharge'];


$shippingTotal=$shipping+$shippingMethod;
$smethod=$_SESSION['smethod'];
$shippingDescription=$shipText[$smethod];

//$subtotal=$_SESSION['subtotal'];
$invoiceTotal=$_SESSION['invoicetotal'];

$tax=$_SESSION['salestax'];
$subtotal=urldecode($invoiceData['AMT'])-$shipping-$shippingMethod-$tax;

$fname=urldecode($invoiceData['FIRSTNAME']); // Michael
$lname=urldecode($invoiceData['LASTNAME']); // Wagner
$cardname=urldecode($invoiceData['BILLTONAME']);
$cardaddress=urldecode($invoiceData['BILLTOSTREET']);
$cardcity=urldecode($invoiceData['BILLTOCITY']);
$cardstate=urldecode($invoiceData['BILLTOSTATE']);
$cardcountry=urldecode($invoiceData['BILLTOCOUNTRY']);
$cardzip=urldecode($invoiceData['BILLTOZIP']);
$cardemail=urldecode($invoiceData['EMAIL']); //wagner_michaeljames%40yahoo%2ecom
$phone=urldecode($_SESSION['phone']);
$transactionID=urldecode($invoiceData['PPREF']); //2XV449181A229492A
$items=urldecode($_COOKIE['items']);
$note=urldecode($_SESSION['note']);
$giftwrap=urldecode($_SESSION['giftwrap']);

 $confmsg='<p>Thank you for your online order at As You Like It Silver Shop. Please keep this email as receipt for your order.</p><p>As soon as your order is shipped you will receive an email with your tracking number.</p>';
 
 $orderitems='
 <table style="width:100%;max-width:650px;">
         		<tbody>
         		<tr>
         			<td colspan="3">
         				<h2 style="color:#8D6B6B;font-weight:normal">Your Order:</h2>
         			</td>
         		</tr>
         		<tr style="font-weight:bold">
         				<td style="width:6%">Qty</td>
         				<td style="width:60%">Item</td>
         				<td style="padding-left:2%;">Price</td>
         		</tr>
         		<tr>
         			<td colspan="3" style="border-top:1px solid #aaa"></td>
		 		</tr>
		 		';
		 		
		 		
   if($_COOKIE['items']){
   	$items=$_COOKIE['items'];
   	}
   else{
    $items=$_SESSION['invoiceItems'];
   }      				

   $item=explode('&',substr($_COOKIE['items'],1));
   
   foreach($item as $v){ 
		//separate each item into id, quantity and regID data
		$i=explode(':',$v);
		
                if(substr($i[0],0,2)=="gc"){
                  $gcSubtotal+=$i[1];
                  $itemName="AS YOU LIKE IT SILVER SHOP GIFT CARD";
                  $itemQuantity="1";
                  $price=$i[1];
                  //$items.="1 $$i[1] AS YOU LIKE IT SILVER SHOP GIFT CARD<br>";
                }
                else{
                 	$itemQuantity=$i[1];
                 	$itemName="";
	                $query=$query=mysql_query("SELECT * from inventory where id=".$i[0]);
	                $r=mysql_fetch_assoc($query);
	            
		                if($r[sale]){ 
			                $price=$r[sale];
			            } 
			            else{
				            $price=$r[retail];
				        }

				        if($r[pattern]!=''){$itemName.=" $r[pattern] BY";}
                        if($r[brand]!=''&&$r[brand]!='UNKNOWN'){$itemName.=" $r[brand]";}
                        $itemName.=" $r[item]";
                                   
                }
              
               $price=$price;

			   $orderitems.='<tr style="color:#777;">
			   				<td>'.$itemQuantity.'</td>
			   				<td>'.stripslashes(ucwords(strtolower($itemName))).'</td>
			   				<td style="padding-left:2%;">$'.format($price).'</td>
			   			  </tr>';
        //end foreach loop
       }
   
   //add order totals etc
   $ordertotals.='<tr><td colspan="3" style="border-top:1px solid #aaa"></td></tr>';    
   
       $ordertotals.='
    			 <tr style="font-weight:bold">
    					<td colspan="2" align="right">
    						Sub-total:
    					</td>
    					<td style="padding-left:2%;">$'.number_format($subtotal,2).'</td>
    				</tr>
    				<tr style="font-weight:bold">
    					<td colspan="2" align="right">
    						Sales Tax:
    					</td>
    					<td style="padding-left:2%;">$'.number_format($tax,2).'</td>
    				</tr>
    				<tr style="font-weight:bold">
    					<td colspan="2" align="right">Shipping ('.$shippingDescription.'):</td>
    					<td style="padding-left:2%;">$'.number_format($shippingTotal,2).'</td>
    				</tr>
    				<tr style="font-weight:bold">
    					<td colspan="2" align="right">Total:</td>
						<td style="padding-left:2%;">$'.number_format($invoiceData['AMT'],2).'</td>
    				</tr>
    				<tr>
	    				<td colspan="3"></td>
    				</tr>
    				<tr>
    					<td colspan="3"></td>
    				</tr>
    				<tr>
    					<td colspan="3"><strong>Optional Gift Wrap:</strong><br>'.stripslashes($_SESSION['giftwrap']).'</td>
    				</tr>
    				  <tr>
    					<td colspan="3"></td>
    				</tr>
    				<tr>
    					<td colspan="3"><strong>Memo:</strong><br>'.stripslashes($_SESSION['note']).'</td>
    				</tr>
    			</tbody>
    			</table>';
 
 $shippingbilling=' 
 <table style="width:100%;max-width:650px;">
 		<tbody>
 			<tr>
 				<td></td>
 			</tr>
 			<tr>
 				<td></td>
 			</tr>
 			<tr>
 				<td style="width:20%"></td>
 				<td style="padding-left:20px"></td>
 			</tr>
 			<tr>
 			<td valign="top"><strong>Ship To:</strong><br>'.
 			$invoiceData['NAMETOSHIP']."<br>".
			$invoiceData['ADDRESSTOSHIP']."<br>".
			$invoiceData['CITYTOSHIP'].", ".
			$invoiceData['STATETOSHIP']." ".
			$invoiceData['ZIPTOSHIP']."<br>".
			$invoiceData['COUNTRYTOSHIP']."<br>".
			$invoiceData['BILLTOEMAIL'].'
			</td>
			</tr>		
		<tr><td></td></tr>
		<tr>
			<td valign="top"><strong>Bill To:</strong><br>'.
			$invoiceData['BILLTONAME'].'<br>'.
			$invoiceData['BILLTOSTREET'].'<br>'.
			$invoiceData['BILLTOCITY'].', '.
			$invoiceData['BILLTOSTATE'].' '.
			$invoiceData['BILLTOZIP'].'<br>'.
			$invoiceData['BILLTOCOUNTRY'].'<br>
			</td>
 		</tr>
 		</tbody>
 </table>';
 
 $footer='<table style="width:100%;max-width:650px;">
 <tbody >
 	<tr>
 		<td>
 		If you have any questions regarding your purchase, feel free to contact us at 1-800-828-2311. Thank you for your business!<br>
 		Sincerely,<br><br>
 		As You Like It Sales Department<br>
 		sales@asyoulikeitsilvershop.com<br>
 		www.asyoulikeitsilvershop.com<br><br>
 		<span>As You Like Offers a full line of repair and monogramming services to meet your needs. For more information please call us.</span>
 		</td>
 	</tr>
 </tbody>
 </table>';
 
 
 //full email message
 $emailmsg=$confmsg.$orderitems.$ordertotals.$shippingbilling;
 
 //order summary for confirmation page
 $ordersummary=$orderitems.$ordertotals.$shippingbilling;
 
 if(!$storeversion){$emailmsg.=$footer;}
 
  return $emailmsg;
     
}

?>