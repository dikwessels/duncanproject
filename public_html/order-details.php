<?php 
ini_set("display_errors","1");
session_name('checkout');
session_start();
ob_start();

extract($_POST);

require("GzipCompress.php");
include("/connect/mysql_connect.php");
include("checkoutCalcsNew.php");
include("shippingSurcharges.php");
include("shippingMethodDescriptions.php");

//	echo $_SESSION['salestax'];

$defaultSurcharge=array(
		4,
		10,
		27,
		53);
		
$surcharge=array();

//echo $_SESSION['zip'];
//echo $_SESSION['shippingrates'];


if($_SESSION['shippingrates']){
	$surcharge=json_decode($_SESSION['shippingrates']);
}	
else{
	$surcharge=$defaultSurcharge;
}

setcookie("items",$_COOKIE['items'],0,'/');

if(!$shipsurcharge){$shipsurcharge=0;}

$orderdetails='
<div class="cell twoColumns"></div>
<div class="cell fourteenColumns">
<form name="itemsForm">
<div class="row border-bottom">
	<div class="cell eightColumns">Item</div>    
	<div class="cell twoColumns rightAlign">Price</div>
	<div class="cell oneColumn"></div>
	<div class="cell twoColumns centered">Quantity</div> 
	<div class="cell oneColumn"></div>
</div>';

$sub=$total=$shipping=$insurance=$tax=0;
$swap=1;

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

$itemCount=count($item);

$inventoryMessage='';
$j=0;
foreach ($item as  $v) { 
	$ne='';
	$swap=$swap^1;if ($swap) {$td="gray-bg"; } else {$td=""; }
	//split each $item array value $v (id:quantity into array $i using ':' delimiter
	$i=explode(':',$v);

	if(substr($i[0],0,2)=="gc"){

         $email=$i[2];
         $amount=$i[1];
         $gcSubtotal+=$i[1];
         $orderdetails.='
			<div class="row '.$td.'">
				<div class="cell eightColumns middleAlign">As You Like It Silver Shop Gift Card</div>
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
	}	
	
	else{
		$query=mysql_query("SELECT * from inventory where id=$i[0]");
		$r=mysql_fetch_assoc($query);
		
		//double check to make sure purchase quantity is valid considering current stock
		//if not, adjust cookie data accordingly
		if($i[1]>abs($r[quantity])) {
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
		$itemName=ucwords(strtolower($r[pattern].' '.$by.$r[brand].' '.$r[item]));
		if(strlen($itemName)>36){
			$itemName=substr($itemName, 0,36);
		}
		$orderdetails.='<div class="row">
						<div class="cell eightColumns bold middleAlign">
						<a href="showItem.php?product='.$r[id].'" class="search">'.ucwords(strtolower($r[pattern].' '.$by.$r[brand].' '.$r[item])).'</a>
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
		$orderdetails.= '<div class="row">
				<div class="cell sixteenColumns">'.$inventoryMessage."</div>
		</div>"; }

if($gconly=="true"){
	$ship=0;
}
else{
	$ship=calculateShipping($sub);
}

//store invoice items in paypal button format in session variable




//add gift card and non gift card subtotals
$sub+=$gcSubtotal;
$tax=0;
if($_SESSION['salestax']){
	$tax=$_SESSION['salestax'];
}
else{
if(isset($_SESSION['zip'])){
	$tax=calculateTax($sub);
}
}
$tax=str_replace("$", "", $tax);
//add applicable tax to paypal button 
$paypalTax='L_PAYMENTREQUEST_0_NAME'.$j.'='.urlencode('Sales Tax').'&L_PAYMENTREQUEST_0_AMT'.$j.'='.urlencode($tax);
$_SESSION['PPTaxIndex']=$j;
$j++;

//add shipping to list
$tempshipmethod=0;
if($_SESSION['shipmethod']){
	$tempshipmethod=$_SESSION['shipmethod'];
}

$shipsurcharge=$surcharge[$tempshipmethod];

$paypalShipping.='&L_PAYMENTREQUEST_0_NAME'.$j.'='.urlencode('Shipping and Insurance: '.$shippingMethodDescription[$tempshipmethod]).
				 '&L_PAYMENTREQUEST_0_AMT'.$j.'='.urlencode(number_format($ship+$shipsurcharge,2));

$_SESSION['PPShipIndex']=$j;

$_SESSION['salestaxPP']=$paypalTax;
$_SESSION['shippingPP']=$paypalShipping;
$_SESSION['invoiceItemsPP']=$paypalItemList;
//use only the not gift card amounts to determine shipping


$paypalItemList.="{$paypalTax}{$paypalShipping}";
if(!$shipmethod){$shipmethod=0;}
$shipsurcharge=$surcharge[$shipmethod];
if($shipsurcharge==0){$shipsurcharge=4;}
$total=$sub+$ship+$tax+$shipsurcharge;

$orderdetails.='
<div class="row border-top">
	<div class="cell eightColumns bold subtotal rightAlign middleAlign">Subtotal</div>
	<div class="cell twoColumns bold subprice rightAlign middleAlign" id="temp-subtotal" data-value="'.number_format($sub,2).'">$'.number_format($sub,2).'</div>
	<div class="cell oneColumn"></div>
	<div class="cell fourColumns middleAlign">
		<button type="button" id="update-quantities" data-value="'.substr($idList,0,-1).'">Update Quantities</button>
	</div>	
</div>
<!--<div class="row">
	<div class="cell eightColumns bold subtotal rightAlign middleAlign">Handling & Insurance</div>
	<div class="cell twoColumns bold subprice rightAlign middleAlign" id="shipping-subtotal" data-value="'.number_format($ship,2).'">$'.number_format($ship,2).'</div>	
	<div class="cell sixColumns"></div>
</div>-->
<div class="row">
    <div class="cell eightColumns rightAlign">
    	Shipping Zip:<span class="asterik">*</span>
    </div>
 
    <div class="cell sevenColumns leftAlign" style="padding-left:20px;">
    	<input style="display:inline;" id="shipping-zip" class="mini-input" value="'.$_SESSION['zip'].'">
    	<span class="caption bold">In order to determine sales tax and shipping estimates, please enter you zip code.</span>
    </div>

    		
</div>
<div class="row">
	<div class="cell eightColumns subtotal rightAlign middleAlign">Shipping:<br>
		<span class="caption" id="shipping-subtotal" data-value="'.number_format($ship,2).
		'">(includes $'.number_format($ship,2).' handling & insurance charge)</span>
	</div>
	<div class="cell sevenColumns subprice leftAlign middleAlign" style="padding-left:20px;">
	<div class="align-left overlay" id="shipping-method-overlay">Requesting FedEx rate estimates...</div>
					<select id="shipping-method" class="medium-input bold" name="shippingMethod">
						<option data-method="0" value="'.number_format($surcharge[0],2).'"';
						if($shipmethod==0){$orderdetails.=' selected="selected"';}
						$orderdetails.='>Ground (8-10 days): $'.number_format($surcharge[0]+$ship,2).'</option>
						<option data-method="1" value="'.number_format($surcharge[1],2).'"';
						if($shipmethod==1){$orderdetails.=' selected="selected"';}
						$orderdetails.='>3-day select: $'.number_format($surcharge[1]+$ship,2).'</option>
						<option data-method="2" value="'.number_format($surcharge[2],2).'"';
						if($shipmethod==2){$orderdetails.=' selected="selected"';}
						$orderdetails.='>Second-day air: $'.number_format($surcharge[2]+$ship,2).'</option>
						<option data-method="3" value="'.number_format($surcharge[3],2).'"';
						if($shipmethod==3){$orderdetails.=' selected="selected"';}
						$orderdetails.='>Next-day air: $'.number_format($surcharge[3]+$ship,2).'</option>
					</select>
					<span id="shipping-notice" class="caption leftAlign"></span>
		<span id="estimated-delivery" class="caption leftAlign">
		
		</span>
	</div>
</div>
<div class="row">
	<div class="cell twoColumns"></div>
	<div class="cell fourteenColumns caption">A note to customers shipping to Texas, Arkansas, Mississippi or Louisiana:<br>Ground Shipping will usually arrive within 2 business days to these states, so we recommend this option over the express options.</div>
</div>
<div class="row">
	<div class="cell eightColumns rightAlign">
	Sales Tax:<br>
	<span class="caption">Applies only to sales in Louisiana.</span> 
	</div>
	<div class="cell twoColumns rightAlign middleAlign bold"  id="salestax" data-value="'.$_SESSION['salestax'].'">$';
	
	if($_SESSION['salestax']){
		$salestax=number_format($_SESSION['salestax'],2);
	}
	else{
		$salestax="0.00";
	}
	

	$orderdetails.=$salestax.'
	</div>
</div>


<div class="row" style="display:none;">
	<div class="cell eightColumns subtotal rightAlign middleAlign">Shipping Total</div>
	<div id="shipping-total" data-value="'.number_format($ship+$shipsurcharge,2).'" class="cell twoColumns subprice rightAlign middleAlign">$'.number_format($ship+$shipsurcharge,2).'
		
	</div>
	
</div>

<div class="row border-bottom">
	<div class="cell eightColumns bold grandtotal rightAlign middleAlign">ORDER TOTAL</div>
	<div id="temp-total" data-value="'.number_format($total,2).'"class="cell twoColumns bold totalprice rightAlign middleAlign">$'.number_format($total,2).'</div>
	<div class="cell sixColumns"></div>
</div>
</form>

<div class="row centered">
		<div class="cell fourColumns">
				<button id="continue-shopping">Continue Shopping</button>
		</div>
		<div class="cell fourColumns" id="check-out-button">
				<button id="check-out">Check Out &rarr;</button>
		</div>
		
		<div class="cell fourColumns">

		<form id="express-checkout-form" method="post" action="shoppingCart.php">
			<input id="invoice-tax" type="hidden" name="zTAXAMT" value="'.number_format($tax,2).'">
			<input id="invoice-shipping" type="hidden" name="zFREIGHTAMT" value="'.number_format($ship+$shipsurcharge,2).'">
			<input id="invoice-total" type="hidden" name="PAYMENTREQUEST_0_AMT" value="'.number_format($total,2).'">
			<input id="pp-invoice-items" type="hidden" name="itemlist" value="'.$paypalItemList.'">
			<input type="hidden" name="action" value="SetExpressCheckout">
			<img id="express-submit" src="https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif" align="left" style="margin-right:7px;position:relative;left:25%;"/>
		</form>
	
		</div>
	
	</div>
	';
}

$_SESSION['paypalitems']=$paypalItemList;
$_SESSION['subtotal']=$sub;
$_SESSION['invoicetotal']=$total;
$_SESSION['shipping']=$ship;
$_SESSION['shippingsurcharge']=$shipsurcharge;
$_SESSION['shippingDescription']=$shipText[$shipsurcharge];
$_SESSION['shipmethod']=$shipmethod;

//$_SESSION['zip']=$shipzip;


setcookie("shipping",$ship."::".$shipsurcharge."::".$shipText[$shipsurcharge],time()+3600);

setcookie("items",$_COOKIE['items'],time()-60,'/');
setcookie("items",$_COOKIE['items'],0,'/');

echo $orderdetails;
 
if (strstr($HTTP_SERVER_VARS['HTTP_ACCEPT_ENCODING'], 'gzip')){flush();}

ob_flush();

?>
   