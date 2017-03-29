<?php 
	
//THIS SCRIPT SHOWS THE ORDER DETAILS FOR shoppingCart.php
/*

11/1/15 - added gift wrap options to expedite express checkout flow

*/

ini_set("display_errors","1");
session_name('checkout');
session_start();
ob_start();

extract($_POST);

require("GzipCompress.php");

include_once("connect/mysql_pdo_connect.php");
include_once("sandbox.checkoutCalcs.php");
include_once("checkoutSettings.php");
include_once("shippingSurcharges.php");
include_once("shippingMethodDescriptions.php");

$defaultSurcharge=array(
		4,
		10,
		27,
		53);
		
$surcharge=array();

//echo $giftcardonly;
if($_SESSION['shippingrates']){
	$surcharge = json_decode($_SESSION['shippingrates']);
}	
else{
	$surcharge = $defaultSurcharge;
}

function getRegistryAddress($regID){
	
	global $db;
	$query=$db->prepare("SELECT * from weddingRegistries WHERE id=:id");
	$query->bindParam(":id",$regID);
	
	$query->execute();
	$result=$query->fetchAll();
	$row=$result[0];
	extract($row);
	
		if($raddress){
			$address=$rfname." ".$rlname.", ".$raddress.", ".$rcity.", ".$rstate." ".$rzipcode;	
		}
		else{
			$address="";
		}
	
	return $address;
}


function tax_amount($sub){
//echo $sub;
	if( isset($_SESSION['zip']) && $giftcardonly!=1 ){
			$tax=calculateTax($sub);
	}
	else{
		$tax=0;
	}
	

$tax=str_replace("$", "", $tax);
	
 return $tax;
}

setcookie("items",$_COOKIE['items'],0,'/');

if( !$shipmethod ){

	$shipmethod = 0;
	
	if( $_SESSION['shipmethod'] ){
		$shipmethod = $_SESSION['shipmethod'];
	}

}

$orderdetails='
<div class="cell twoColumns"></div>
<div class="cell fourteenColumns">

<div class="row border-bottom">
	<div class="cell sevenColumns">Item</div>    
	<div class="cell twoColumns rightAlign">Price</div>
	<div class="cell oneColumn"></div>
	<div class="cell twoColumns centered">Quantity</div> 
	<div class="cell oneColumn"></div>
</div>';

$sub=$total=$shipping=$insurance=$tax=0;
$swap=1;
$giftcardonly=1;
$hasgiftcard=0;
if (!$_COOKIE['items']) {

	$orderdetails.='<div class="row border-bottom">
					<div class="cell sixteenColumns">Your Cart is empty</div>
				</div>
		<div class="row centered">
			<div class="cell oneColumn"></div>
			<div class="cell fourColumns">
				<a href="http://www.asyoulikeitsilvershop.com">Resume Shopping</a>
			</div>
		</div>
	';
	
	}
else {
//split cookie data into array $item using & delimiter
//cookie data stored as &id1:quantity1&id2:quantity2 etc etc

$item=explode('&',substr($_COOKIE['items'],1));

$giftcards=array();

$itemCount=count($item);

$inventoryMessage='';
$j=0;
$gcIndex=0;
foreach ($item as  $v) { 
	$ne='';
	$swap=$swap^1;if($swap){$td="gray-bg"; } else {$td=""; }
	//split each $item array value $v (id:quantity into array $i using ':' delimiter
	$i=explode(':',$v);

	if(substr($i[0],0,2)=="gc"){
		 $email="";
		 $registryID=0;
		 $hasgiftcard=1;
         $cardID=$i[2];
         $amount=$i[1];
         $subData=explode("||",$cardID);
         $cardCode=$subData[1];
         
         $registryID=0;
         if(strpos($subData[0],"@")==0){
	       // $subData=split("||", $cardID);  
	        $registryID=$subData[0];
			$address=getRegistryAddress($cardID);
         }
         else{
	         $email=$subData[0];
         }
         
         $giftcards[$gcIndex] 				= array();
         $giftcards[$gcIndex]['cardIndex'] 	= $gcIndex;
         $giftcards[$gcIndex]['email'] 		= $email;
         $giftcards[$gcIndex]['amount'] 	= $amount;
         $giftcards[$gcIndex]['cardcode'] 	= $cardCode;
		 $giftcards[$gcIndex]['registryID'] = $registryID;
		 
         //$giftcards[$email]=$amount;
         //$giftcards['cardcode']="";
         $gcSubtotal+=$i[1];

         
         $orderdetails.='
			<div class="row '.$td.'">
				<div class="cell sevenColumns middleAlign">As You Like It Silver Shop Gift Card<br><span class="caption">';
				if($address){$orderdetails.='Delivery to '.$address;}
				$orderdetails.='</span></div>
				<div class="cell twoColumns bold rightAlign middleAlign">
					<input class="mini-input centered" type="text" size="4" value="'.$amount.'" id="quantity'.$i[0].'" name="quantity'.$i[0].'">
					<input name="email'.$i[0].'" type="hidden" value="'.$email.'">
				</div>
				<div class="cell oneColumn"></div>
                <div class="cell twoColumns centered bold middleAlign">1</div>
				<div class="cell oneColumn centered middleAlign">
					<span title="Remove Item" class="remove-button" data-remove="'.$i[0].'">X</a>
				</div>
			</div>';


		$paypalItemList.='L_PAYMENTREQUEST_0_QTY'.$j.'=1'.
                '&L_PAYMENTREQUEST_0_AMT'.$j.'='.urlencode(format($amount)).
                '&L_PAYMENTREQUEST_0_NAME'.$j.'='.urlencode('As You Like It Silver Shop Gift Card').'&';
	
	}	
	
	else{
		
	 	$giftcardonly=0;
		$query=$db->prepare("SELECT * from inventory where id=:id");//$i[0]");
		$query->bindParam(":id",$i[0]);
		$query->execute();
		$result=$query->fetchAll();
		
		$r=$result[0];//mysql_fetch_assoc($query);
		
		//double check to make sure purchase quantity is valid considering current stock
		//if not, adjust cookie data accordingly
		if($i[1]>abs($r['quantity'])) {
			$ne=$i[1];
			$i[1]=abs($r['quantity']);
		
			if ($r['quantity']==0) { 
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
		
		
		if($r['sale']){ 
			$price=$r['sale']; 
		} 
		else{ 
			$price=$r['retail'];
		}
		
		$by=($r['pattern'] && $r['brand'])?"BY ":'';
		$itemName=ucwords(strtolower($r['pattern'].' '.$by.$r['brand'].' '.$r['item']));
		if(strlen($itemName)>36){
			$itemName=substr($itemName, 0,36);
		}
		$orderdetails.='<div class="row">
						<div class="cell sevenColumns bold middleAlign">
						<a href="showItem.php?product='.$r['id'].'" class="search">'.ucwords(strtolower($r['pattern'].' '.$by.$r['brand'].' '.$r['item'])).'</a>
				</div>
				<div class="cell twoColumns bold rightAlign middleAlign">
					$'.format($price).'
				</div>
				<div class="cell oneColumn"></div>
				<div class="cell twoColumns centered bold middleAlign">
					<input class="mini-input centered" type="text" size="2" value="'.$i[1].'" id="quantity'.$i[0].'" name="quantity'.$i[0].'"></div>
				<div class="cell oneColumn centered middleAlign">
					<span title="Remove Item" class="remove-button" data-remove="'.$i[0].'">X</span>
				</div>	
				</div>';
			
			//$paypalItemlist.='&L_PAYMENTREQUEST_0_NAME0='.urlencode("$i[1] $ItemName ".format($price).'\n\r');
			
			$paypalItemList.='L_PAYMENTREQUEST_0_QTY'.$j.'='.$i[1].
                '&L_PAYMENTREQUEST_0_AMT'.$j.'='.urlencode(format($price)).
                '&L_PAYMENTREQUEST_0_NAME'.$j.'='.urlencode($itemName).'&';
                	
          $sub+=$price*$i[1];
	}
	
	$j++;
	
	$idList.=$i[0].',';
        
	}
	

if ($inventoryMessage) { 
		$orderdetails .= '<div class="row">
							<div class="cell sixteenColumns">'.$inventoryMessage."</div>
						</div>"; 
	}




//add gift card and non gift card subtotals
$sub += $gcSubtotal;

$_SESSION['giftcardsubtotal']	= $gcSubtotal;

$subtotal=$sub;
//echo ($sub-$gcSubtotal);


//echo $giftcardonly;
if($giftcardonly == 1){
	//zero out shipping and tax
		$shipsurcharge	= 0;
		$ship			= 0;
		$tax			= 0;
		$shippingMethodDescription = "US Mail";
		$taxmsg = "<br>No sales tax applies for gift card purchases";
		
		$paypalShipping .= '&L_PAYMENTREQUEST_0_NAME'.$j.'='.urlencode('US Mail').
							'&L_PAYMENTREQUEST_0_AMT'.$j.'='.urlencode(number_format(0,2));
	}

else{
	if($_SESSION['shipmethod'] == 5){
		
		$tax 			= tax_amount($subtotal-$gcSubtotal);
		
		$shipsub 		= 0;
		$ship 			= 0;
		$shipsurcharge 	= 0;
	
		$taxmsg 		= "Local sales tax applies";
	
		$shippingMethodDescription = "In-store Pickup";
	
		$paypalShipping = 	'&L_PAYMENTREQUEST_0_NAME'.$j.'='.urlencode('In-Store Pickup').
				 		'&L_PAYMENTREQUEST_0_AMT'.$j.'=0';
		
	}
	else{
	
		//echo "Gift card is false";
		$tax 			= tax_amount($subtotal-$gcSubtotal);
		$shipsub 		= $sub - $gcSubtotal;
	
		$ship 			= calculateShipping($shipsub);
		$shipsurcharge 	= $surcharge[$shipmethod];
	
		$taxmsg 		= "";
	
		$shippingMethodDescription = $shipText[$shipsurcharge];
	
		$paypalShipping = 	'&L_PAYMENTREQUEST_0_NAME'.$j.'='.urlencode('Shipping and Insurance: '.
	
		$shippingMethodDescription[$tempshipmethod]).
				 		'&L_PAYMENTREQUEST_0_AMT'.$j.'='.urlencode(number_format($ship+$shipsurcharge,2));
	
	}
}


//add applicable tax to paypal button 
$paypalTax = 'L_PAYMENTREQUEST_0_NAME'.$j.'='.urlencode('Sales Tax').'&L_PAYMENTREQUEST_0_AMT'.$j.'='.urlencode($tax);

$_SESSION['PPTaxIndex'] = $j;
$j++;

//add shipping to list
$tempshipmethod = 0;

if( $_SESSION['shipmethod'] ){
	$tempshipmethod = $_SESSION['shipmethod'];
}

$total = $sub + $ship + $tax + $shipsurcharge;

$_SESSION['PPShipIndex'] 	=	$j;
$_SESSION['salestaxPP']		=	$paypalTax;
$_SESSION['shippingPP']		=	$paypalShipping;
$_SESSION['invoiceItemsPP']	=	$paypalItemList;
//use only the not gift card amounts to determine shipping


$paypalItemList	.= "{$paypalTax}{$paypalShipping}";


$orderdetails .='
<div class="row border-top">
	<div class="cell sevenColumns bold subtotal rightAlign middleAlign">Subtotal</div>
	<div class="cell twoColumns bold subprice rightAlign middleAlign" id="temp-subtotal" data-value="'.number_format($sub,2).'">$'.number_format($sub,2).'</div>
	<div class="cell oneColumn"></div>
	<div class="cell fourColumns middleAlign">
		<button type="button" id="update-quantities" data-value="'.substr($idList,0,-1).'">Update Quantities</button>
	</div>	
</div>';

$storePickupOption = '<div class="row" id="rowStorePickup">
    					<div style="padding-left: 1em;" class="cell sevenColumns"></div>
						<div class="cell sevenColumns">
							<input type="checkbox" id="chkStorePickup" {{checked}} class="mini-input" style="margin-right: 0.5rem;">
							Check here for in-store pickup (New Orleans sales tax applies).
						</div>
					</div>';

$storePickupChecked = "";


if( $_SESSION['shipmethod'] == 5){
	$storePickupChecked = "checked = 'checked'";
	$shippingZip = "70115";
	$ship = 0;
	$shipsurcharge = 0;
}
else{
	if($ship == 0){
		$ship = $_SESSION['shipping'];
	}
	//$shipsurcharge = $_SESSION['shippingsurcharge'];
	$shippingZip = $_SESSION['zip'];
}

$storePickupOption = str_replace("{{checked}}", $storePickupChecked, $storePickupOption);

if( $giftcardonly != 1 ){
	
$orderdetails .= '

	<!--<div class="row">
		<div class="cell sevenColumns bold subtotal rightAlign middleAlign">Handling & Insurance</div>
		<div class="cell twoColumns bold subprice rightAlign middleAlign" id="shipping-subtotal" data-value="'.number_format($ship,2).'">$'.number_format($ship,2).'</div>	
		<div class="cell sixColumns"></div>
		</div>-->

<div class="row" id="shippingZipRow">
    <div class="cell sevenColumns rightAlign">
    	<strong>Shipping Zip:</strong>
    	<br>
    	<span class="caption">
    		(required for tax and shipping estimates)
    	</span>
    </div>
 
    <div class="cell threeColumns leftAlign" style="padding-left:10px;">
    	<input style="display:inline;" id="shipping-zip" class="mini-input" value="'.$shippingZip.'">
    </div>
	<div class="cell fiveColumns leftAlign">
		<span class="caption bold">
			
		</span>
	</div>
    		
</div>

<!--rowStorePickup-->

<div class="row">
	<div class="cell sevenColumns subtotal rightAlign middleAlign">
	<strong>Shipping:</strong><br>
		<span class="caption" id="shipping-subtotal" data-value="'.number_format($ship,2).
		'">(includes $'.number_format($ship,2).' for handling & insurance)</span>
	</div>
	<div class="cell fourColumns subprice leftAlign middleAlign" style="padding-left:10px;">
	<div class="align-left overlay" id="shipping-method-overlay">Updating shipping rates...</div>
	
	<select id="shipping-method" class="medium-input bold" name="shippingMethod">';
					
	if( $_SESSION['shipmethod'] == 5 ){
		$shippingOptionList = '<option data-method="5" value="0">In-Store Pickup: $0:00</option>';
	}
	else{
		
		$shippingOptionList = '	<option data-method="0" value="'.number_format($surcharge[0],2).'"';
						if($shipmethod == 0){$shippingOptionList.=' selected="selected"';}
						
						$shippingOptionList .= '>Ground (8-10 days): $'.number_format($surcharge[0]+$ship,2).'</option>
						<option data-method="1" value="'.number_format($surcharge[1],2).'"';
						
						if($shipmethod == 1){$shippingOptionList.=' selected="selected"';}
						
						$shippingOptionList .= '>3-day select: $'.number_format($surcharge[1]+$ship,2).'</option>
						<option data-method="2" value="'.number_format($surcharge[2],2).'"';
						
						if($shipmethod == 2){$shippingOptionList.=' selected="selected"';}
						$shippingOptionList .= '>Second-day air: $'.number_format($surcharge[2]+$ship,2).'</option>
						
						<option data-method="3" value="'.number_format($surcharge[3],2).'"';
						
						if($shipmethod == 3){$shippingOptionList.=' selected="selected"';}
						$shippingOptionList.='>Next-day air: $'.number_format($surcharge[3]+$ship,2).'</option>';
	
	}
								
	$orderdetails .= $shippingOptionList. '</select>';
	
	$orderdetails .='
					
		<span id="shipping-notice" class="caption leftAlign"></span>
		<span id="estimated-delivery" class="caption leftAlign">
		
		</span>
	</div>
	<div class="cell fiveColumns">
		<span class="caption">
			<strong>Shipping to Texas, Arkansas, Mississippi or Louisiana?</strong>
			<br>We recommend ground shipping as it usually arrives within 2 business days.
		</span>
	</div>
</div>
';
}

$orderdetails .= "<div class='row'>
	<div class='cell sevenColumns rightAlign'>
		<strong>Sales Tax:</strong>
		<br>
		<span class='caption'>(applies only to items shipped within Louisiana)".$taxmsg."</span>
	</div>
	
	<div class='cell twoColumns rightAlign middleAlign bold'  id='salestax' data-value='$tax'>$";

	if( $tax ){
		$salestax=number_format($tax,2);
	}
	else{
		
		$salestax="0.00";
	}

$orderdetails = str_replace("<!--rowStorePickup-->", $storePickupOption, $orderdetails);
	
$orderdetails .= $salestax.'
	</div>
		
		<div class="cell fiveColumns rightAlign">
			
		</div>

</div>';

if($giftcardonly!=1){
$orderdetails.='
<div class="row" style="display:none;">
	<div class="cell sevenColumns subtotal rightAlign middleAlign">Shipping Total</div>
	<div id="shipping-total" data-value="'.number_format($ship+$shipsurcharge,2).'" class="cell twoColumns subprice rightAlign middleAlign">$'.number_format($ship+$shipsurcharge,2).'
		
	</div>
	
</div>';}


//ADD gift wrap options here

$giftwrapFields='<div class="row">
		<div class="cell sevenColumns subtotal rightAlign middleAlign"><strong>Free gift wrap:</strong></div>
		<div class="cell fourColumns" style="padding-left:10px"> 
			<select id="gift-wrap" class="small-input" name="giftwrap">
				<option value="">None</option>
				<option value="standard">Standard</option>
				<option value="Christening">Christening</option>
				<option value="Christmas">Christmas</option>
				<option value="Wedding">Wedding</option>
			</select>
		</div>
		
		<div class="cell fiveColumns">
			<textarea id="note" placeholder="Optional gift card message or special instructions" style="height:30px;width:100%"></textarea>
		</div>';

$orderdetails.=$giftwrapFields;

$orderdetails.='

<div class="row border-bottom border-top" style="margin-top:10px">
	<input type="hidden" id="gift-card-only" value="'.$giftcardonly.'">
	<div class="cell sevenColumns bold grandtotal rightAlign middleAlign">ORDER TOTAL</div>
	<div id="temp-total" data-value="'.number_format($total,2).'"class="cell twoColumns bold totalprice rightAlign middleAlign">$'.number_format($total,2).'</div>
	<div class="cell sixColumns"></div>
</div>
</form>

<div class="row centered">
<div id="button-overlay"></div>
		<div id="divContinueShopping" class="cell fourColumns">
				<button id="continue-shopping">Continue Shopping</button>
		</div>';


//add checkout buttons
if( $PayPalMode == "live" ){ 
	
	$display ='';

}
else{
	
	if( $_COOKIE['developer_mode'] !=  'on'){
		$display ='style="display:none;"';
	}
	
}
	
$orderdetails.='
<!-- paypalmode = "'.$PayPalMode.'" -->
		<div  class="cell fourColumns testingThis" id="check-out-button">
				<button ' .$display. ' id="check-out">Check Out &rarr;</button>
		</div>
		
		<div '.$display.' class="cell fourColumns">

		<form id="express-checkout-form" method="post" action="shoppingCart.php">';
		
		/*$orderdetails.= '
			<input id="invoice-tax" type="hidden" name="zTAXAMT" value="'.number_format($tax,2).'">
			<input id="invoice-shipping" type="hidden" name="zFREIGHTAMT" value="'.number_format($ship+$shipsurcharge,2).'">';
		*/
	//<input id="pp-invoice-items" type="hidden" name="itemlist" value="'.$paypalItemList.'">
	//<input id="invoice-total" type="hidden" name="PAYMENTREQUEST_0_AMT" value="'.number_format($total,2).'">

	$orderdetails.=
	'<input type="hidden" name="action" value="SetExpressCheckout">
		</form>
		
		<div class="paypal-payment-button" id="express-submit">
			Pay Now with  
			<svg width="68" height="18" viewBox="0 0 68 18" xmlns="http://www.w3.org/2000/svg" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns"><title>PayPal</title><g sketch:type="MSShapeGroup" fill="none"><path d="M61.224 5h.882v-3.168h1.074v-.744h-3.036v.744h1.08v3.168zm2.562 0h.816v-1.434c0-.384-.072-.954-.114-1.332h.024l.312.906.6 1.518h.354l.606-1.518.318-.906h.024c-.042.378-.114.948-.114 1.332v1.434h.828v-3.912h-.936l-.636 1.758-.234.684h-.024l-.234-.684-.654-1.758h-.936v3.912zm-8.638-3.717l-1.82 12.231c-.035.237.138.451.365.451h1.831c.303 0 .561-.233.608-.549l1.795-12.011c.035-.237-.138-.451-.365-.451h-2.048c-.182 0-.337.14-.365.33m-15.245 4.057c-.242 1.681-1.458 1.681-2.634 1.681h-.669l.469-3.139c.028-.19.183-.329.365-.329h.307c.8 0 1.556 0 1.946.482.233.288.304.715.216 1.306zm-.512-4.387h-4.435c-.303 0-.561.233-.608.549l-1.793 12.011c-.035.237.138.451.365.451h2.275c.212 0 .393-.163.426-.385l.509-3.405c.047-.316.305-.549.608-.549h1.403c2.921 0 4.607-1.493 5.047-4.453.198-1.294.008-2.311-.566-3.023-.631-.783-1.748-1.197-3.232-1.197zm10.295 8.699c-.205 1.282-1.168 2.142-2.397 2.142-.616 0-1.109-.209-1.426-.605-.314-.393-.432-.952-.333-1.576.191-1.27 1.17-2.158 2.38-2.158.603 0 1.093.211 1.416.611.325.403.453.966.36 1.586zm2.959-4.366h-2.123c-.182 0-.337.14-.365.33l-.093.627-.148-.227c-.46-.705-1.485-.941-2.508-.941-2.346 0-4.35 1.878-4.74 4.512-.203 1.314.085 2.569.79 3.446.648.805 1.573 1.14 2.674 1.14 1.891 0 2.94-1.283 2.94-1.283l-.095.623c-.036.237.138.452.365.452h1.912c.303 0 .561-.233.609-.549l1.148-7.678c.035-.237-.138-.451-.365-.451z" fill="#2790C3"/><path d="M32.326 5.286h-2.134c-.204 0-.395.107-.51.285l-2.944 4.58-1.248-4.401c-.078-.275-.318-.464-.59-.464h-2.098c-.253 0-.432.263-.35.516l2.35 7.287-2.21 3.295c-.174.259.002.616.302.616h2.132c.202 0 .391-.105.506-.28l7.098-10.821c.17-.259-.006-.613-.304-.613m-24.052.054c-.242 1.681-1.458 1.681-2.634 1.681h-.669l.469-3.139c.029-.19.183-.329.365-.329h.307c.8 0 1.557 0 1.946.482.233.288.304.715.216 1.306zm-.512-4.387h-4.435c-.303 0-.561.233-.608.549l-1.793 12.011c-.035.237.138.451.365.451h2.117c.303 0 .561-.233.609-.549l.484-3.24c.047-.316.305-.549.608-.549h1.403c2.921 0 4.607-1.493 5.047-4.453.198-1.294.008-2.311-.566-3.023-.631-.783-1.748-1.197-3.232-1.197zm10.295 8.699c-.205 1.282-1.168 2.142-2.397 2.142-.616 0-1.109-.209-1.426-.605-.314-.393-.432-.952-.333-1.576.191-1.27 1.17-2.158 2.38-2.158.603 0 1.093.211 1.416.611.325.403.453.966.36 1.586zm2.959-4.366h-2.123c-.182 0-.337.14-.365.33l-.093.627-.148-.227c-.46-.705-1.485-.941-2.508-.941-2.346 0-4.35 1.878-4.74 4.512-.203 1.314.085 2.569.79 3.446.648.805 1.573 1.14 2.674 1.14 1.891 0 2.94-1.283 2.94-1.283l-.095.623c-.035.237.138.452.365.452h1.912c.303 0 .561-.233.608-.549l1.148-7.678c.035-.237-.138-.451-.365-.451z" fill="#27346A"/></g></svg>
			</div>
			<!-- <img id="express-submit" src="https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif" align="left" style="margin-right:7px;position:relative;left:25%;height:30px"/> -->
		</div>
	
	</div>';

	
}

$_SESSION['zip'] 					= $shippingZip;
$_SESSION['paypalitems'] 			= $paypalItemList;
$_SESSION['subtotal'] 				= $sub;
$_SESSION['shipping'] 				= $ship;
$_SESSION['shippingsurcharge']  	= $shipsurcharge;
$_SESSION['shippingDescription'] 	= $shippingMethodDescription;
$_SESSION['salestax'] 				= $tax;
$_SESSION['totalshipping'] 			= $ship + $shipsurcharge;
$_SESSION['invoicetotal'] 			= $total;
$_SESSION['giftcardonly'] 			= $giftcardonly;
$_SESSION['hasgiftcard'] 			= $hasgiftcard; //flag if invoice has gift card on it
$_SESSION['giftcards'] 				= $giftcards;

//$_SESSION['zip']=$shipzip;


setcookie("shipping",$ship."::".$shipsurcharge."::".$shipText[$shipsurcharge],time()+3600);
setcookie("items",$_COOKIE['items'],time()-60,'/');
setcookie("items",$_COOKIE['items'],0,'/');

echo $orderdetails;
 
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')){flush();}

ob_flush();

?>
   