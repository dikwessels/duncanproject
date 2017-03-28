<?php 
session_name('checkout');
session_start();
//echo "hello";
ob_start();
global $environment;

include("/connect/mysql_connect.php");
include("/home/asyoulik/public_html/staticHTMLFunctions.php");
include("expresscheckout.config.php");
include("expressCheckOut.php");
include("/home/asyoulik/public_html/emailConfirmation.php");


include("paypalsettings.php");

$environment="live";
//echo($environment);

ini_set("display_errors","1");

function composeExpressEmail($orderID,$invoiceData,&$ordersummary){

$shipping=$_SESSION['shipping'];
$shippingMethod=$_SESSION['shippingsurcharge'];
$shippingDescription=$_SESSION['shippingDescription'];
$shippingTotal=$shipping+$shippingMethod;

$subtotal=$_SESSION['subtotal'];
$invoiceTotal=$_SESSION['invoicetotal'];

$subtotal=urldecode($invoiceData['AMT'])-$shipping-$shippingMethod;
$phone=urldecode($_SESSION['phone']);
$transactionID=urldecode($invoiceData['TRANSACTIONID']); //2XV449181A229492A
$items=urldecode($_COOKIE['items']);
$note=urldecode($_SESSION['note']);
$giftwrap=urldecode($_SESSION['giftwrap']);

$tax=$_SESSION['salestax'];

$confmsg='<p>Thank you for your online order at As You Like It Silver Shop. Please keep this email as receipt for your order.</p><p>As soon as your order is shipped you will receive an email with your tracking number.</p>';
 
 $orderitems='
 <table style="width:100%;max-width:650px;">
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
		
	               /*if($i[1]>$r[quantity] && $r[quantity]>-1) {
			       		$ne=$i[1];
				   		$i[1]=$r[quantity];
				   		if ($r[quantity]==0) { 
				$_COOKIE['items']=str_replace("&$v",'',$_COOKIE['items']);
				$inventoryMessage.="<br>Sorry, our inventory has changed.  $r[item] ($r[pattern] BY $r[brand]) is currently out of stock";
				continue;
			      }
				   		else {
				$_COOKIE['items']=str_replace("&$v","&$i[0]:$i[1]:$i[2]",$_COOKIE['items']);
				$inventoryMessage.="<br>Sorry, our inventory has changed.  We only have $i[1] $r[item] ($r[pattern] BY $r[brand]) in stock.";
			      }
				   	}*/
	
	               
		             if($r[sale]){ 
			             $price=$r[sale];
			         } 
			         else{
				         $price=$r[retail];
				     }

				     if($r[pattern]!=''){$itemName.=" $r[pattern] BY";}
                     if($r[brand]!=''&&$r[brand]!='UNKNOWN'){$itemName.=" $r[brand]";}
                     $itemName.=" $r[item]";
                            
                     //$subtotal+=$price*$i[1];
					
              
               // end if($i[0]=="000000")        
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
    				<tr>
    					<td colspan="2" align="right">
    						Tax:
    					</td>
    					<td style="padding-left:2%;">$'.number_format($tax,2).'</td>
    				</tr>
    				<tr style="font-weight:bold">
    					<td colspan="2" align="right">Shipping ('.$shippingDescription.'):</td>
    					<td style="padding-left:2%;">$'.number_format($shippingTotal,2).'</td>
    				</tr>
    				<tr style="font-weight:bold">
    					<td colspan="2" align="right">Total:</td>
						<td style="padding-left:2%;">$'.urldecode($invoiceData['AMT']).'</td>
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
 		<tbody style="font-size:.9rem;">
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
 			urldecode($invoiceData['SHIPTONAME'])."<br>".
			urldecode($invoiceData['SHIPTOSTREET'])."<br>".
			urldecode($invoiceData['SHIPTOCITY']).", ".
			urldecode($invoiceData['SHIPTOSTATE'])." ".
			urldecode($invoiceData['SHIPTOZIP'])."<br>".
			urldecode($invoiceData['SHIPTOCOUNTRYCODE'])."<br>".
			urldecode($invoiceData['EMAIL']).'
			</td>
			</tr>		
		<tr><td></td></tr>
		<tr>
			<td valign="top"><strong>Bill To:</strong><br>'.
			urldecode($invoiceData['SHIPTONAME']).'<br>'.
			urldecode($invoiceData['SHIPTOSTREET']).'<br>'.
			urldecode($invoiceData['SHIPTOCITY']).', '.
			urldecode($invoiceData['SHIPTOSTATE']).' '.
			urldecode($invoiceData['SHIPTOZIP']).'<br>'.
			urldecode($invoiceData['SHIPTOCOUNTRYCODE']).'<br>
			</td>
 		</tr>
 		<tr>
 			<td>PayPal Transaction ID:'.
 			$transactionID. 			
 			'</td>
 		</tr>
 		</tbody>
 </table>';
 
 $footer='<table style="width:100%;max-width:650px;">
 <tbody style="font-size:.9rem;">
 	<tr>
 		<td>
 		If you have any questions regarding your purchase, feel free to contact us at 1-800-828-2311. Thank you for your business!<br>
 		Sincerely,<br><br>
 		As You Like It Sales Department<br>
 		sales@asyoulikeitsilvershop.com<br>
 		www.asyoulikeitsilvershop.com<br><br><br>
 		<span style="font-size:.75rem;">As You Like Offers a full line of repair and monogramming services to meet your needs. For more information please call us.</span>
 		</td>
 	</tr>
 </tbody>
 </table>';
 
 $emailmsg=$confmsg.$orderitems.$ordertotals.$shippingbilling;
 
 $ordersummary=$orderitems.$ordertotals.$shippingbilling;
 
 if(!$storeversion){$emailmsg.=$footer;}
 
 return $emailmsg;
   
}

function dump_response($arr){
	foreach($arr as $k=>$v){
		echo $k." ".$v."<br>";
	}
}

function updateCustomerTable($invoiceData){
global $environment;

mysql_query("UNLOCK tables");

//PAYERID NP9A2DFDTBA2U
//PAYERSTATUS verified
//$country=$invoiceData['COUNTRYCODE']; //US

$cardemail=urldecode($invoiceData['EMAIL']); //wagner_michaeljames%40yahoo%2ecom
//assume the shipping and billing are the same because it's a paypal express checkout
$shipto=$cardname=urldecode($invoiceData['SHIPTONAME']);
$address1=$cardaddress=urldecode($invoiceData['SHIPTOSTREET']); //1%20Main%20St
$city=$cardcity=urldecode($invoiceData['SHIPTOCITY']);// San%20Jose
$state=$cardstate=urldecode($invoiceData['SHIPTOSTATE']);// CA
$zip=$cardzip=urldecode($invoiceData['SHIPTOZIP']);// 95131
$country=urldecode($invoiceData['SHIPTOCOUNTRYCODE']);// US

if($invoiceData['PHONENUM']){
	$phone=urldecode($invoiceData['PHONENUM']);
}
if($_COOKIE['shipping']){
	$shippingDetails=split("::", $_COOKIE['shipping']);
	$shipping=$shippingDetails[0];
	$shippingsurcharge=$shippingDetails[1];
}
else{
	$shipping=$_SESSION['shipping'];
	$shippingsurcharge=$_SESSION['shippingsurcharge'];
}

$subtotal=urldecode($invoiceData['AMT'])-$shipping-$shippingsurcharge;

//temporarily make $shipping the total shipping
$shipping=$shipping+$shippingsurcharge;

$cardtype="PayPal";

// make this the shipping surcharge SHIPHANDLEAMOUNT 0%2e00
//TIMESTAMP 2013%2d07%2d29T18%3a01%3a04Z
//CORRELATIONID 51c2d0889c3b6
//ACK Success
//VERSION 62%2e0
//BUILD 6855329
$note=$_SESSION['note'];
$giftwrap=$_SESSION['giftwrap'];

$tax=$_SESSION['salestax'];
$shipmethod=$_SESSION['shippingsurcharge'];

$fname=$invoiceData['FIRSTNAME']; // Michael
$lname=$invoiceData['LASTNAME']; // Wagner
$transactionID=$invoiceData['TRANSACTIONID']; //2XV449181A229492A
$items=urldecode($_COOKIE['items']);
//TRANSACTIONTYPE expresscheckout
//PAYMENTTYPE instant
//ORDERTIME 2013%2d07%2d29T18%3a01%3a03Z
// back calculate order subtotal to insert AMT 71%2e00

$tbl=($environment=="sandbox")?"tblCustomersSandbox":"customers";
	
$query="INSERT INTO ".$tbl."(fname,lname,address1,address2,city,state,country,zip,phone,cardtype,cardnumber,exp,cardname,
cardaddress,cardcity,cardstate,cardcountry,cardzip,cardphone,cardemail,tax,shipping,subtotal,shipMethod,shippingMethod,giftwrap,note,giftCardCode,giftCardAmt,items,transactionID,status)";

$query.=" VALUES('$fname','$lname','$address1','$address2','$city','$state','$country','$zip','$phone','$cardtype',
'$cardnumber','$exp','$cardname','$cardaddress','$cardcity','$cardstate','$cardcountry','$cardzip','$cardphone','$cardemail','$tax','$shipping','$subtotal','$shipmethod','0','$giftwrap','$note','$giftCardCode','$giftCardAmt','$items','$transactionID','processed')";
	
$result=mysql_query($query);
 if(mysql_affected_rows()>0){
   $custNum=mysql_insert_id();
 }
 else{
	die(mysql_error());
 }
 return $custNum;	
}

function updateInventory(){

global $environment;

$tbl=($environment=="sandbox")?"inventorySandbox":"inventory";

	$item=explode('&',substr($_SESSION['invoiceitems'],1));
	
	foreach($item as $k=>$v){ 
	if(strpos($v, "gc")===FALSE){
		$i=explode(':',$v);	
		$update="UPDATE ".$tbl." set quantity=(IF (quantity>-1,quantity-".$i[1].",quantity)) where id=$i[0]";		
		$q=mysql_query($update);
		
		//wedding registry purchases only
		if($i[2]>0){
			$query="UPDATE weddingRegistryItems SET qtyPurchased=qtyPurchased+$i[1] WHERE qtyRequested>qtyPurchased AND regID=$i[2] and itemID=$i[0]";
			$result=mysql_query($query);
			if(mysql_affected_rows()>0){	
			}	
		}	
	 }
	
	 }
	
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
<base href="//www.asyoulikeitsilvershop.com/">
<!--
<script type="text/javascript" src="js/validate.js"></script>
-->

<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>


<!--<script type="text/javascript" src="/js/ajax.js"></script>-->
<script type="text/javascript" src="/js/images.js"></script>
<script type="text/javascript" src="/js/store.js"></script>
<script type="text/javascript" src="/js/cookie.js"></script>
<!--<script type="text/javascript" src="/js/giftRegistry.js"></script>-->
<script type="text/javascript" src="/js/formvalidation.js"></script>

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
  
  
  <?
if($_POST){
  $PayPalMode=$environment;
  
 //they're finalizing payment
 $_SESSION['note']=$_POST['note'];
 $_SESSION['giftwrap']=$_POST['giftwrap'];
 if($_POST['METHOD']){
  //DO express payment!
  $paypal= new MyPayPal();
  //echo "posted method";

  
   $padata="&TOKEN=".urlencode($_SESSION['PaypalToken']).
  	"&PAYERID=".urlencode($_SESSION['PayerID']).
  	"&PAYMENTREQUEST_0_AMT=".urlencode($_SESSION['invoicetotal']);
  
  //echo $padata;	
 
  $response = $paypal->PPHttpPost('DoExpressCheckoutPayment', $padata, $PayPalApiUsername,  $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);
  	 
  	 if(strtoupper($response['ACK'])=="SUCCESS"){
  	  
  	  //dump_response($httpParsedResponseAr);
  	 
  	   // print_r($response);
  	   //exit(0);
  	 
  	   updateInventory($_COOKIE['items']); 
  	   
  	   //get transaction details to update customer database
	   $transactionID=urlencode($response['PAYMENTINFO_0_TRANSACTIONID']);
	   $paypal=new MyPayPal();
	   $nvpstr="&TRANSACTIONID=".$transactionID;
	   
 $response = $paypal->PPHttpPost('GetTransactionDetails', $nvpstr, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);
  

 	$ordersummary="";
 	$custNum=updateCustomerTable($response);
  	$emailMsg=composeExpressEmail($custNum,$response,$ordersummary);
  	
    $customerEmail=urldecode($response['EMAIL']);
    
   
  	$confmsg='
  	<script type="text/javascript">
  		$(document).ready(function(){
  		$(\'#defaultH1\').html(\'Your Order\');
  		});
  	</script>
  	<div class="row">
  	<div class="cell twoColumns"></div>
  	<div class="cell fourteenColumns">
  		<ul class="horizontal ordersteps">
			<li id="step-1" class="orderstep">1. Shipping & Billing Information</li>
			<li id="step-2" class="orderstep">2. Review Order and Submit Payment</li>
			<li id="step-3" class="orderstep current">3. Order Confirmation</li>
		</ul>
  	</div>
 </div>
  	<div class="row">
  	 				<div class="cell twoColumns"></div>
  	 				<div class="cell fourteenColumns" style="line-height:1.5rem;">
  	 				  	 		<h2>Thank you for your order!</h2>
  	 		Your payment has been received and your order is being processed.  Your order number is: <strong>'.$custNum.'</strong><br>
  	 				Please print this page for your records. For your convenience, a confirmation email has been sent to '.$customerEmail.'.<br>';
  	 
  	 	if(!$giftCardOnly){	 
  	 	 $confmsg.='<br> Once your order is shipped, you will receive a shipping email with your UPS tracking number.';
  	 	 }
  	 
  	  $confmsg.='If you have any questions for comments,  please call us at 1-800-828-2311.
 <br><br>Thank you for shopping with As You Like It Silver Shop. Order Details below: <br><br>
 
 <a href="http://www.asyoulikeitsilvershop.com/" title="As You Like It Silver Shop">Return to As You Like It Silver Shop Home Page</a>
 '.
 	$ordersummary;
 
 	echo $confmsg;
 		
 		
 	//email confirmation
 	sendConfirmationEmail($emailMsg,$customerEmail,$custNum,"customer");
 	sendConfirmationEmail($emailMsg,$customerEmail,$custNum,"shop");

 	
 	//clear out cookies and session data!
	session_unset();
    setcookie("custNum","",time()-30);
    setcookie("shipping","",time()-30);
    setcookie("items","",time()-30);
  	 
  	 }
  
     else{
 	    // add error handling here:
     	dump_response($response); 
	 }

 }		
}
else{
//display form for final review and add memo or gift wrap options
echo '<div class="row">
  	<div class="cell twoColumns"></div>
  	<div class="cell fourteenColumns">
  		<ul class="horizontal ordersteps">
			<li id="step-1" class="orderstep">1. Shipping & Billing Information</li>
			<li id="step-2" class="orderstep current">2. Review Order and Submit Payment</li>
			<li id="step-3" class="orderstep">3. Order Confirmation</li>
		</ul>
  	</div>
 </div>';
 
extract($_GET);

 $PayPalMode=$environment;
 $paypal= new MyPayPal();
 $padata="&TOKEN=".urlencode($token);
 //$PayPalMode="live";
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

function showItems(&$items,&$inventoryMessage,&$subtotal) {
	global $invoiceTotal;
	//separate items cookie by &
	
	$_SESSION['invoiceitems']=$_COOKIE['items'];
	$item=explode('&',substr($_COOKIE['items'],1));

       
        $items="<div class='row' style='color:#555'>
        			<div class='cell threeColumns min-zero'></div>
        			<div class='cell thirteenColumns'>
        				<div class='row  border-bottom'>
							<div class='cell oneColumn min-zero'>Qty</div>
			    			<div class='cell twelveColumns'>Description</div>
			    			<div class='cell threeColumns'>Price</div>
			    		</div>";
			    		
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
                  //$items.="1 $$i[1] AS YOU LIKE IT SILVER SHOP GIFT CARD<br>";
                }
                else{
                 	$itemQuantity=$i[1];
                 	$itemName="";
	                $query=$query=mysql_query("SELECT * from inventory where id=".$i[0]);
	                $r=mysql_fetch_assoc($query);
		
	               if ($i[1]>$r[quantity] && $r[quantity]>-1) {
			       $ne=$i[1];
			       $i[1]=$r[quantity];
			
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
		                	if ($r[sale]) { 
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
    					<td>Shipping ('.$shippingDetails[2].')</td>
    					<td>'.format($shippingTotal).'</td>
    				</tr>
    				<tr>
    					<td>Total:</td>
						<td>'.format($invoiceTotal).'</td>
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
			"<br><span class=\"caption\">(Address ".
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
	<div class='cell tenColumns rightAlign'>Shipping (".$shippingDetails[2].")</div>
	<div class='cell oneColumn'></div>
	<div class='cell threeColumns'>$".number_format($shippingTotal,2)."</div>
</div>

<div class='row bold ' style='color:black'>
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
	
	if($_SESSION['giftwrap']!=-1&&$_SESSION['giftwrap']!=""){
	echo'
       <script type="text/javascript">
    	var giftwrap='.$_SESSION['giftwrap'].';
    	$(document).ready(function(){ 
    		$(\'#gift-wrap\').val(giftwrap);   
        });
       </script>
	';
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

echo "
<div class=\"row\">
<div class=\"cell threeColumns\"></div>
<div class=\"cell sixColumns\">
	<div class=\"row\">
		<div class=\"cell twoColumns\"><h3>Ship To:</h3></div>
		<div class=\"cell elevenColumns\">".
			$_SESSION['shipto']."<br>".
			$_SESSION['shipaddress']."<br>".
			$_SESSION['shipcity'].", ".
			$_SESSION['shipstate']." ".
			$_SESSION['shipzip']."<br>".
			$_SESSION['shipcountryname'].
			"<br><span class=\"caption\">(Address ".
			$_SESSION['addressStatus'].")</span><br>".
			$_SESSION['email']."
		</div>
	</div>
</div>
<div class=\"cell oneColumn min-zero\"></div>
<div class=\"cell sixColumns\">
	<div class=\"row\">
		<div class=\"cell twoColumns\"><h3>Bill To:</h3></div>
		<div class=\"cell elevenColumns\">";
		
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
	echo "
	</div>
</div>
</div>
</div>";
 
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




