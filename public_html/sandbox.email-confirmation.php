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

 function invoiceRow($label,$value){
	 $row="<tr>
    					<td colspan='2' align='right'>
    						<strong>$label </strong>  					
    					</td>
    					<td style='padding-left:2%;'><strong>$$value</strong></td>
    				</tr>";
    return $row;
 }

function sendConfirmationEmail($msg,$emailAddress,$orderID,$version){

$headers.= "MIME-Version: 1.0\r\n";
$headers.= "Content-Type: text/html; charset=ISO-8859-1\r\n";
$headers.="From:orders@asyoulikeitsilvershop.com";

$subject=($version=="customer")?"As You Like It Silver Shop Order #".$orderID:"You Have Received Order #".$orderID;

//send shop email
if($version=="shop"){
	mail("wagnermichaeljames@gmail.com",$subject,$msg,$headers);
}
else{
	//send customer email
	mail($emailAddress,$subject,$msg,$headers);
}

}
function sendShopConfirmation($msg,$emailAddress,$orderID){

$headers.= "MIME-Version: 1.0\r\n";
$headers.= "Content-Type: text/html; charset=ISO-8859-1\r\n";
$headers.="From:orders@asyoulikeitsilvershop.com";
$subject="You Have Received Order #".$orderID;

	mail("wagnermichaeljames@gmail.com",$subject,$msg,$headers);

}

function composeEmail($orderID,$invoiceData,&$ordersummary,$express){
	
	global $orderitems;
	global $ordertotals;
	global $shippingbilling;

	$shipText=array(
		"Ground 8-10 days",
		"3-day select",
		"Second day air",
		"Next day air"
	);
	
	
	$shipto=urldecode($invoiceData['SHIPTOFIRSTNAME']." ".$invoiceData['SHIPTOLASTNAME']);
	$address1=urldecode($invoiceData['SHIPTOSTREET']); //1%20Main%20St
	$city=urldecode($invoiceData['SHIPTOCITY']);// San%20Jose
	$state=urldecode($invoiceData['SHIPTOSTATE']);// CA
	$country=urldecode($invoiceData['SHIPTOCOUNTRY']);// US
	$zip=urldecode($invoiceData['SHIPTOZIP']);// 95131

	if($express==1){
	//use shipping for billing
		$cardname=$shipto;
		$cardaddress=$address1;
		$cardcity=$city;
		$cardstate=$state;
		$cardcountry=$country;
		$cardzip=$zip;
	}
	else{
		$cardname=urldecode($invoiceData['BILLTOFIRSTNAME']." ".$invoiceData['BILLTOLASTNAME']);
		$cardaddress=urldecode($invoiceData['BILLTOSTREET']);
		$cardcity=urldecode($invoiceData['BILLTOCITY']);
		$cardstate=urldecode($invoiceData['BILLTOSTATE']);
		$cardcountry=urldecode($invoiceData['BILLTOCOUNTRY']);
		$cardzip=urldecode($invoiceData['BILLTOZIP']);
	}
	

  if($_SESSION['giftcardonly']==1){
	$shipping=0;
	$shippingMethod=0;
	$shippingTotal=$shipping+$shippingMethod;
	$smethod=-1;
	$shippingDescription="Electronic Delivery";
  }
  else{
	$shipping=$_SESSION['shipping'];
	$shippingMethod=$_SESSION['shippingsurcharge'];
	$shippingTotal=$shipping+$shippingMethod;
	
	$smethod=$_SESSION['shipmethod'];
	$shippingDescription=$_SESSION['shippingDescription'];
  }
	
	
	$invoiceTotal=$_SESSION['invoicetotal'];
	$tax=$_SESSION['salestax'];
	$subtotal=$_SESSION['subtotal'];
	
	//$subtotal=$invoiceTotal-$shipping-$shippingMethod-$tax;

	//$fname=urldecode($invoiceData['FIRSTNAME']); // Michael
	//$lname=urldecode($invoiceData['LASTNAME']); // Wagner

	
	$cardemail=urldecode($invoiceData['BILLTOEMAIL']); //wagner_michaeljames%40yahoo%2ecom
	
	if($express && $invoiceData['PHONENUM']){
		$phone=urldecode($invoiceData['PHONENUM']);	
	}
	else{
		$phone=urldecode($_SESSION['phone']);
	}
	
	//$phone=urldecode($_SESSION['phone']);
	
	$transactionID=urldecode($invoiceData['PPREF']); //2XV449181A229492A
	
	$items=urldecode($_COOKIE['items']);
	$note=urldecode($_SESSION['note']);
	
	$giftwrap=urldecode($_SESSION['giftwrap']);

	$giftcardcode=urldecode($_SESSION['giftcardcode']);
	$giftcardamount=urldecode($_SESSION['giftcardamount']);

 $confmsg='<h1>Sandbox testing</h1><p>Thank you for your online order at As You Like It Silver Shop. Please keep this email as receipt for your order.</p><p>As soon as your order is shipped you will receive an email with your tracking number.</p>';
 
 $orderitems='
 <table style="width:100%;max-width:650px;">
         		<tbody style="text-align:left;">
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
   
   //add any gift cards
   if(isset($_SESSION['giftcards'])){
	   foreach($_SESSION['giftcards'] as $recipientemail =>$cardamount){
	    //$gcSubtotal+=$amount;
		    $invoiceitems.='<tr>
			   				<td  style="vertical-align:top;">1</td>
			   				<td  style="vertical-align:top;">As You Like It Silver Shop Gift Card<br>
			   				<span class="caption">Delivery to '.$recipientemail.'</span>
			   				</td>
			   				<td style="vertical-align:top;padding-left:2%;">$'.number_format($cardamount,2).'</td>
			   			  </tr>';
	   }
   }
   
   //add non gift cards
   foreach($item as $v){ 
		//separate each item into id, quantity and regID data
		$i=explode(':',$v);
		
                if(substr($i[0],0,2)!="gc"){
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

					$invoiceitems.='<tr style="color:#777;">
			   				<td>'.$itemQuantity.'</td>
			   				<td>'.stripslashes(ucwords(strtolower($itemName))).'</td>
			   				<td style="padding-left:2%;">$'.number_format($price,2).'</td>
			   			  </tr>';

                }
              			  
     //end foreach loop
     }
   
   
     
   //add order totals etc
   $ordertotals.='<tr><td colspan="3" style="border-top:1px solid #aaa"></td></tr>';    
   
   $shipkey="Shipping Method ($shipText[$smethod])";
   	
   $ordertotalsarray=array("Sub-total"=>number_format($subtotal,2),
   								"Sales Tax:"=>number_format($tax,2),
   								$shipkey=>number_format($shippingTotal,2),
   								"Total"=>number_format($invoiceTotal,2));
   
     foreach($ordertotalsarray as $k=>$v){
	     $ordertotals.=invoiceRow($k,$v);
     }

    if($giftcardcode!=""){
	    	$ordertotals.="
	    		<tr style='font-weight:bold' >
	    			<td colspan='2' align='right'>
	    				Gift Card <span style='font-size:75%'><strong>$giftcardcode</strong></span>
					</td>
	    			<td style='padding-left:2%;'>$".number_format($giftcardamount,2)."</td>
	    		</tr>
	    		<tr style='font-weight:bold'>
	    			<td colspan='2' align='right'>
	    				Balance:
					</td>
	    			<td style='padding-left:2%;'>$".number_format($invoiceTotal-$giftcardamount,2)."</td>
	    		</tr>
	    		";
    	}			
    				
		$ordertotals.='
    				<tr>
	    				<td colspan="3"></td>
    				</tr>
    				<tr>
    					<td colspan="3"></td>
    				</tr>
    				<tr>
    					<td colspan="3" style="text-align:left"><strong>Optional Gift Wrap:</strong><br>'.stripslashes($_SESSION['giftwrap']).'</td>
    				</tr>
    				  <tr>
    					<td colspan="3"></td>
    				</tr>
    				<tr>
    					<td colspan="3" style="text-align:left"><strong>Memo:</strong><br>'.stripslashes($_SESSION['note']).'</td>
    				</tr>
    			</tbody>
    			</table>';
 
 $shippingbilling=' 
 <table style="width:100%;max-width:650px;">
 		<tbody style="text-align:left">
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
 			<td valign="top"><strong>Ship To:</strong><br>';
 			
 			if($_SESSION['giftcardonly']==1){
	 			$shippingbilling.="Electronic Delivery";
 			}
 			else{
	 			
 			$shippingbilling.=
 			$invoiceData['NAMETOSHIP']."<br>".
			$invoiceData['ADDRESSTOSHIP']."<br>".
			$invoiceData['CITYTOSHIP'].", ".
			$invoiceData['STATETOSHIP']." ".
			$invoiceData['ZIPTOSHIP']."<br>".
			$invoiceData['COUNTRYTOSHIP']."<br>".
			$invoiceData['BILLTOEMAIL'];
			
			}
			
			$shippingbilling.='
			</td>		
			<td></td>	
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