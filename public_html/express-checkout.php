<?php 
//THIS WILL BE DEPRECATED AND CONSOLIDATED TO THE order-confirmation.php script

ini_set("display_errors","1");
session_name('checkout');
session_start();

ob_start();

include_once("/connect/mysql_pdo_connect.php");

//load in $PayPalMode variable
include("checkoutSettings.php");

include_once("/home/asyoulik/public_html/sandbox.order-confirmation-functions.php");
include_once("GiftCard.php");
include_once("Invoice.php");

include("shippingMethodDescriptions.php");

include("expresscheckout.config.php");

//code for making express payment calls to PayPal
include("PayPalExpressPayment.php");

function getRegistryAddress($regID){
	global $db;
	$query=$db->prepare("SELECT * from weddingRegistries WHERE id=:id");
	$query->bindParam(":id",$regID);
	
	$query->execute();
	$result=$query->fetchAll();
	$row=$result[0];
	extract($row);
	
		if($raddress) {
			
			$address=$rfname." ".$rlname.", ".$raddress.", ".$rcity.", ".$rstate." ".$rzipcode;	
		}
		else {
			$address="";
		}
	
	return $address;
	
}

function showItems(&$items,&$inventoryMessage,&$subtotal){
	global $db;
	global $invoiceTotal;
	
	//shipping method long description array
	global $shippingMethodDescription;
	
	//separate items cookie by &
	
	$_SESSION['invoiceitems']=$_COOKIE['items'];
	
	$item=explode('&',substr($_COOKIE['items'],1));

        $items='<div class="row" style="color:#555">
        			<div class="cell threeColumns min-zero"></div>
        			<div class="cell thirteenColumns">
        				<div class="row  border-bottom">
							<div class="cell oneColumn min-zero">Qty</div>
			    			<div class="cell twelveColumns">Description</div>
			    			<div class="cell threeColumns">Price</div>
			    		</div>';
			    		
         $emailmsg='<table style="width:100%;max-width:650px;">
         		<tbody style="font-size:1rem">
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
		 		
	foreach ($item as  $v) { 
		//separate each item into id, quantity and regID data
		$i=explode(':',$v);
		
                if(substr($i[0],0,2)=="gc"){
                  $gcSubtotal+=$i[1];
                  $itemName="AS YOU LIKE IT SILVER SHOP GIFT CARD";
                  $itemQuantity="1";
                  $price=$i[1];
                  $subData=explode("||",$i[2]);
                  if(strpos($subData[0], "@")==0){
	                  $deliverTo=getRegistryAddress($subData[0]);
	                  $itemName.="<br>Delivery to $deliverTo";
                  }
                  //$items.="1 $$i[1] AS YOU LIKE IT SILVER SHOP GIFT CARD<br>";
                }
                else{
                 	$itemQuantity=$i[1];
                 	$itemName="";
                 	
	                $stmt="SELECT * from inventory where id=".$i[0];
	                
	                $query=$db->prepare($stmt);
	                $query->execute();
	                $result=$query->fetchAll();
	                
	                $r=$result[0];
	                
	                //$r=mysql_fetch_assoc($query);
		
	               if ($i[1]>$r['quantity'] && $r['quantity']>-1) {
			       $ne=$i[1];
			       $i[1]=$r['quantity'];
			
			   if ($r['quantity']==0){ 
				$_COOKIE['items']=str_replace("&$v",'',$_COOKIE['items']);
				$inventoryMessage.="<br>Sorry, our inventory has changed.  $r[item] ($r[pattern] BY $r[brand]) is currently out of stock";
				continue;
			      }
			      else {
				$_COOKIE['items']=str_replace("&$v","&$i[0]:$i[1]:$i[2]",$_COOKIE['items']);
				$inventoryMessage.="<br>Sorry, our inventory has changed.  We only have $i[1] $r[item] ($r[pattern] BY $r[brand]) in stock.";
			      }
		        }
	
	                	else{
		                	if ($r['sale']) { 
			                	$price=$r['sale'];
			                } 
			                else {
				                $price=$r['retail'];
				            }

				            if($r['pattern']!=''){$itemName.=" $r[pattern] BY";}
                            if($r['brand']!=''&&$r[brand]!='UNKNOWN'){$itemName.=" $r[brand]";}
                            $itemName.=" $r[item]";
                 
                            //$subtotal+=$price*$i[1];
						}
              	
               // end if($i[0]=="000000")        
               }
              
               $items.="<div class='row' style='color:#555'>
               				<div class='cell oneColumn min-zero'>$itemQuantity</div>
			    			<div class='cell twelveColumns'>$itemName</div>
			    			<div class='cell threeColumns'>$".number_format($price,2)."</div>
			    		</div>";
			   $emailmsg.='<tr>
			   				<td>'.$itemQuantity.'</td>
			   				<td>'.$itemName.'</td>
			   				<td>'.$price.'</td>
			   			  </tr>';
        //end foreach loop
       }
		
	$emailmsg.='<tr style="border-top:1px solid #aaa"><td colspan="3"></td></tr></tbody></table>';
		
	//setcookie("items",$_COOKIE['items'],0,'/'); 
	
    //$ship=calculateShipping($subtotal);
    $subtotal=$_SESSION['subtotal'];
	$shippingInfo=$_COOKIE['shipping'];
	$shippingDetails=explode("::", $shippingInfo);
	$shippingTotal=$shippingDetails[0]+$shippingDetails[1];
	$shippingMethod=$_SESSION['shipmethod'];
	$shippingDescription=$shippingMethodDescription[$shippingMethod];
	
	$salestax=$_SESSION['salestax'];
	$invoiceTotal=$subtotal+$shippingTotal+$salestax;
    
   
	$emailmsg.='<table style="width:100%">
    			<tbody style="font-weight:bold">
    				<tr>
    					<td style="width:75%;text-align:right;">
    						Sub-total:
    					</td>
    					<td style="width:23%;padding-left:2%;text-align:right">'.number_format($subtotal,2).'</td>
    				</tr>
    				<tr>
    					<td style="width:75%;text-align:right;">
    						Sales Tax:
    					</td>
    					<td style="width:23%;padding-left:2%;text-align:right">'.number_format($salestax,2).'</td>
    				</tr>
    				<tr>
    					<td>Shipping ('.$shippingDescription.')</td>
    					<td>'.number_format($shippingTotal,2).'</td>
    				</tr>
    				<tr>
    					<td>Total:</td>
						<td>'.number_format($invoiceTotal,2).'</td>
    				</tr>
    			</tbody>
    			</table>';
 
 $emailmsg.='<table style="width:100%">
 		<tbody>
 			<tr>
 			<td style="width:40%"><strong>Shipping Info</strong></td>
 			<td style="width:20%"></td>
 			<td style="width:40%"><strong>Billing Info</strong></td>
 			</tr>
 			<tr>
 			<td>'.$_SESSION['shipto']."<br>".
			$_SESSION['shipaddress']."<br>".
			$_SESSION['shipcity'].", ".
			$_SESSION['shipstate']." ".
			$_SESSION['shipzip']."<br>".
			$_SESSION['shipcountryname'].
			"<br><span class='caption'>(Address ".
			$_SESSION['addressStatus'].")</span><br>".
			$_SESSION['email'].'
			</td>
			<td></td>
			<td>'.$_SESSION['billaddress'].'<br>'.
			$_SESSION['billcity'].'<br>'.
			$_SESSION['billstate'].'<br>'.
			$_SESSION['billzip'].'<br>'.
			$_SESSION['billcountry'].'<br>'.
 		'</tbody>
 </table>';
 
 
 $_SESSION['emailmessage']=$emailmsg;
 
//echo $shippingDetails[0]." ".$shippingDetails[1]." ".$shippingDetails[2];   
    
$items.="
<div class='row border-top bold' style='color:black'>
	<div class='cell oneColumn min-zero'></div>
	<div class='cell tenColumns rightAlign'>Subtotal</div>
	<div class='cell oneColumn'></div>
	<div class='cell threeColumns'>$".number_format($subtotal,2)."</div>
</div>
<div class='row bold' style='color:black'>
	<div class='cell oneColumn min-zero'></div>
	<div class='cell tenColumns rightAlign'>Sales Tax</div>
	<div class='cell oneColumn'></div>
	<div class='cell threeColumns'>$".number_format($salestax,2)."</div>
</div>
<div class='row bold' style='color:black'>
	<div class='cell oneColumn min-zero'></div>
	<div class='cell tenColumns rightAlign'>Shipping (".$shippingDescription.")</div>
	<div class='cell oneColumn'></div>
	<div class='cell threeColumns'>$".number_format($shippingTotal,2)."</div>
</div>

<div class='row bold' style='color:black'>
	<div class='cell oneColumn min-zero'></div>
	<div class='cell tenColumns rightAlign'>Total</div>
	<div class='cell oneColumn'></div>
	<div class='cell threeColumns'>$".number_format($invoiceTotal,2)."</div>
</div>

</div>

</div>";
 
 

	$_SESSION['invoicetotal']=$invoiceTotal;
	//end function 
}	

?>

<!DOCTYPE html>
<html>
<head>
<title>Check Out | As You Like It Silver Shop, New Orleans, Louisiana</title>
<meta charset="UTF-8"/>
<meta name="description" content="As You Like It Silver Shop in New Orleans Louisiana specializes in silver flatware and holloware in active, inactive and obsolete patterns."/>
<meta name="keywords" content="customer privacy policy, selling your silver, purchasing information, sterling silver, sterling flatware, silver flatware, antique silver, silver tableware, antique sterling, replacement silver, silver repair, corporate gifts, wedding gifts, silver identification, cleaning silver"/>

<!--ogTags-->
<base href="//localhost:8888">
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="/js/images.js"></script>
<script type="text/javascript" src="/js/store.js"></script>
<script type="text/javascript" src="/js/cookie.js"></script>

<link rel="stylesheet" href="/css/dropdown/imports.css">
<link rel="stylesheet" href="/ayliss_style.css" type="text/css">
<link rel="stylesheet" href="/ayliss_style_uni.css" type="text/css">

<? include("/home/asyoulik/public_html/js/analytics.html"); ?>

<script type="text/javascript">
	//put stuff here to bind note and 
	$(document).ready(function(){
	
	try{
	$('#gift-wrap-selection').bind('change',function(){
		$('#gift-wrap-value').val($(this).val());
	});
	
	$('#card-note').bind('blur',function(){
		$('#note-value').val($(this).val());
	});
	
	$('#cancel-order').bind('click',function(){
		var cancel=confirm('Are you sure you want to cancel this order?');
		if(cancel==true){
			//cancel order
			$.ajax({type:'post',
			url:'cancel-order.php',
				success:function(result){
					alert(result);
					window.top.location="http://www.asyoulikeitsilvershop.com";
				}
			});
		}		
	});
	
	$('#submit-payment').bind('click',function(){
		$('#transition').fadeIn();
	});
	
	}
	catch(err){
		
	}
	});
</script>

</head>

<body class="sub">
<div id="container">
<!-- begin page head -->
<div class="pageHead" id="defaultPageHead" style="border-bottom:1px solid #333">
<!-- page Header image -->
  <div class="pageHeaderImage row nopad" id="<!--pageHeadImageID-->">
  <div class="row centered" id="mobilePageHeader">
  As You Like It Silver Shop
	  <span id="mobileDescription"> 
		  Antique Silver Flatware, Hollowware, Jewelry, Baby Silver, Repairs
		  </span>
  </div>
    <img class="pageBanner" src="/images/ayliss_title_r.jpg" alt="<!--pageHeadImageAlt-->" title="<!--pageHeadImageTitle-->">
 
 <div id="contactInfo" class="cell eightColumns">
	<a href="contact.php" class="contactLink">Contact Us</a>
	1-800-828-2311	
</div>
   <!-- begin cart container --> 
  <div class="cell sevenColumns" id="cartContainer">
   <!-- end cart link -->
 </div>
 <!-- end cart container -->
 
  </div>  
   <!-- end page header image -->

</div>

<div class="mainContent" id="express-checkout">
  <!-- begin main content head with h1 -->
 	<div id="transition">
		  	<div style="position: relative;width: 100%;top: 25%;height:100%;">
			  	<img src="images/ajax-loader.gif">
				  	<br>Processing your order...please do not hit the back button on your browser. 
			  	</div>
	</div>	
	
  <div class="mainContentHead" id="defaultH1Container">
    <div class="titleContainer border-bottom">
     <h1 class="h1PageCatTitle" id="defaultH1">Check Out</h1>
    </div>
    <div class="pageCatImage" style="text-align:right;top:2px;background-color:white;" title="Click to Verify - This site chose Thawte SSL for secure e-commerce and confidential communications.">
<script type="text/javascript" src="https://seal.thawte.com/getthawteseal?host_name=www.asyoulikeitsilvershop.com&amp;size=S&amp;lang=en"></script>
</div>
  </div>
  
  <div class="row">
  	<div class="cell twoColumns"></div>
  	<div class="cell fourteenColumns">
  		<ul class="horizontal ordersteps">
			<li id="step-1" class="orderstep">1. Shipping & Billing Information</li>
			<li id="step-2" class="orderstep current">2. Review Order and Submit Payment</li>
			<li id="step-3" class="orderstep">3. Order Confirmation</li>
		</ul>
  	</div>
 </div>
  
 

<?
//final order review and payment response displays here


	//IF POST THEN PAYMENT WAS SUBMITTED
	
	if($_POST){  		
  		//they're finalizing payment
  		$_SESSION['note']=$_POST['note'];
  		$_SESSION['giftwrap']=$_POST['giftwrap'];
  		
  		if($_POST['METHOD']){
  
	  	//DO express payment!
	  	$paypal= new PayPalExpressPayment();
  
	  	$padata="&TOKEN=".urlencode($_SESSION['PaypalToken']).
	  			"&PAYERID=".urlencode($_SESSION['PayerID']).
	  			"&PAYMENTREQUEST_0_AMT=".urlencode($_SESSION['invoicetotal']);
  

	  	$response = $paypal->PPHttpPost('DoExpressCheckoutPayment', $padata, $PayPalApiUsername,  $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);
  	 
  	 
	  		if( strtoupper($response['ACK'])=="SUCCESS" ){
  	  
	  		//SUCCESSFUL TRANSACTION
	  		updateInventory($_COOKIE['items']); 

	  		echo "<script type='text/javascript'>
	  			$(document).ready(function(){
	  			$('#step-2').removeClass('current');
	  			$('#step-3').addClass('current');
	  			});
  	   		</script>";

  	   //get transaction details to update customer database
  	  
	   $transactionID=urlencode($response['PAYMENTINFO_0_TRANSACTIONID']);
	   $paypal=new PayPalExpressPayment();
	   $nvpstr="&TRANSACTIONID=".$transactionID;
	   
	   $response = $paypal->PPHttpPost('GetTransactionDetails', $nvpstr, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);
  
	   //$custNum=updateCustomerTable($response);
 
	   //check to see if any gift cards have been purchased and generate codes
	   //update items cookie with generated codes
	    if(isset($_SESSION['giftcards'])&& $_SESSION['giftcards']){
			//return code and update the cookie with the code
			//generateGiftCards returns a code pair in form of newCode:tempCode
			//update the cookie data to store the new code in the old items information
				
				$gcResponse=generateGiftCards($_SESSION['giftcards'],$custNum);
		
				$s=explode(":",$gcResponse);
				$newCode=$s[0];
				$tempCode=$s[1];
				$giftCardCodes[]=$newCode;
				$_COOKIE['items']=str_replace($tempCode,$newCode, $_COOKIE['items']);
				
		}   
	   
 
		//create and save invoice
		$invoice=new Invoice();
			
		$invoice->save($response,1);
			
		$invoice->load($PayPalMode);
			
		if(count($giftCardCodes)>0){
			updateGiftCardCodesWithInvoiceNumber($giftCardCodes,$invoice->invoiceID);
		}
		
		//show confirmationon screen and send email
		$invoice->generate_receipt();
		
		echo ' <div class="row">
  					<div class="cell threeColumns"></div>
  					<div class="cell thirteenColumns" id="confirmation-content">';

  					$invoice->display_receipt();
	
		echo  '</div>';
					
		$invoice->email_receipt();
     
	   //CLEAR COOKIES AND SESSION
		if( ($PayPalMode != "testing") ){
    		clearSession();
		}
			
        unset($invoice);
	  	 
  	}
  
	  		else{
	  			// add error handling here:
     			dump_response($response); 
	 		}

	 	}		
  
	}

	//IF NO POST, SHOW FINAL REVIEW PAGE
	else{
 
		extract($_GET);

		$paypal= new PayPalExpressPayment();
		
		$padata="&TOKEN=".urlencode($token);
 
		file_put_contents("paypalerror.txt", $PayPalApiUsername." ".$PayPalApiPassword." ".$PayPalApiSignature);
   
		//get shipping details etc for final confirmation
 		$httpParsedResponseAr = $paypal->PPHttpPost('GetExpressCheckoutDetails', $padata, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);

 		if(strtoupper($httpParsedResponseAr['ACK'])=="SUCCESS"){

	 	$_SESSION['shipto']=urldecode($httpParsedResponseAr['PAYMENTREQUEST_0_SHIPTONAME']);
	 	$_SESSION['shipaddress']= urldecode($httpParsedResponseAr['PAYMENTREQUEST_0_SHIPTOSTREET']);
	 	$_SESSION['shipcity']=urldecode($httpParsedResponseAr['PAYMENTREQUEST_0_SHIPTOCITY']);
	 	$_SESSION['shipstate']=urldecode($httpParsedResponseAr['PAYMENTREQUEST_0_SHIPTOSTATE']);
	 	$_SESSION['shipcountry']=urldecode($httpParsedResponseAr['PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE']);
	 	$_SESSION['shipcountryname']=urldecode($httpParsedResponseAr['PAYMENTREQUEST_0_SHIPTOCOUNTRYNAME']);
	 	$_SESSION['shipzip']=urldecode($httpParsedResponseAr['PAYMENTREQUEST_0_SHIPTOZIP']);
	 	$_SESSION['addressID']=urldecode($httpParsedResponseAr['PAYMENTREQUEST_0_ADDRESSID']);
	 	$_SESSION['addressStatus']=urldecode($httpParsedResponseAr['PAYMENTREQUEST_0_ADDRESSSTATUS']);
	 	$_SESSION['buyeremail']=urldecode($httpParsedResponseAr['EMAIL']);
	 	$_SESSION['payerID']=$PayerID;

	 	}

	 	else{
		 	echo '<div style="color:red"><b>Error : </b>'.urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]).'</div>';
		
		}




showItems($items,$msg,$subtotal);

echo $items;

	echo '
	<div class="row">
	<div class="cell threeColumns"></div>
	<div class="cell thirteenColumns">
	<div class="row">
	
		<div class="cell fourColumns formtext rightAlign">Free Optional Gift wrap:</div>
		<div class="cell fourColumns">
		<select class="small-input" id="gift-wrap-selection">
				<option value="-1">(Choose Below)</option>
				<option value="">None</option>
				<option value="standard">Standard</option>
				<option value="Christening">Christening</option>
				<option value="Christmas">Christmas</option>
				<option value="Wedding">Wedding</option>
			</select>
		</div>';
	
	if($_SESSION['giftwrap']!="" && $_SESSION['giftwrap']!=-1 ) {
	echo "
       <script type='text/javascript'>
    	var giftwrap='".$_SESSION['giftwrap']."';
    	$(document).ready(function(){ 
    		$('#gift-wrap-selection').val(giftwrap);   
        });
       </script>
	";
	}
	echo'
	<div class="cell fourColumns formtext rightAlign">Optional note to the recipient:</div>
	<div class="cell fourColumns rightAlign">
		<textarea style="height:50px" rows="4" class="medium-input" id="card-note">'.$_SESSION['note'].'</textarea>
	</div>
	
	</div>
	</div>
</div>
';

echo '
<div class="row">
<div class="cell threeColumns"></div>
<div class="cell sixColumns">
	<div class="row">
		<div class="cell twoColumns"><h3>Ship To:</h3></div>
		<div class="cell elevenColumns">'.
			$_SESSION['shipto']."<br>".
			$_SESSION['shipaddress']."<br>".
			$_SESSION['shipcity'].", ".
			$_SESSION['shipstate']." ".
			$_SESSION['shipzip']."<br>".
			$_SESSION['shipcountryname'].
			'<br><span class="caption">(Address '.
			$_SESSION['addressStatus'].")</span><br>".
			$_SESSION['email'].'
		</div>
	</div>
</div>
<div class="cell oneColumn min-zero"></div>
<div class="cell sixColumns">
	<div class="row">
		<div class="cell twoColumns"><h3>Bill To:</h3></div>
		<div class="cell elevenColumns">';
		
		if($_SESSION['billto']){
		  echo
			$_SESSION['billto']."<br>".
			$_SESSION['billaddress']."<br>".
			$_SESSION['billcity'].", ".
			$_SESSION['billstate']." ".
			$_SESSION['billzip']."<br>".
			$_SESSION['billcountryname'].
			$_SESSION['email'];
		 }
		 else{
			 echo "Billing information on file with PayPal";
		 }
	echo '
	</div>
</div>
</div>
</div>';
 
$paymentform='<div class="row">
<div class="cell threeColumns"></div>

<div class="cell thirteenColumns centered">
<div class="row border-top">
<div class="cell fourColumns">
	<button id="cancel-order">Cancel Order</button>

</div>
<div class="cell fourColumns">
<form method="POST" action="express-checkout.php">
	<input type="hidden" value="" name="note" id="note-value">
	<input type="hidden" value="" name="giftwrap" id="gift-wrap-value">
	<input type="hidden" name="METHOD" value="DoExpressCheckoutPayment">
	<button id="submit-payment">Pay and Process Order</button><br>
	<span class="caption">Your order will not be processed until you click this button</span>
</form>
</div>

</div>

</div>';

    $_SESSION['PaypalToken']=$token;
    $_SESSION['PayerID']=$PayerID;
    
    echo $paymentform;

}


?>
<!--- main content -->

</div>
 <footer>

   <?
   	$c=include("copyright.php");
   	echo $c;
   ?>
    </footer>	
</div>
</body>


</html>

<? ob_flush(); ?>




