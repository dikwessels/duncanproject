<?php 
session_name('checkout');
session_start();
ob_start();

require("GzipCompress.php");
include("/connect/mysql_connect.php");
include("checkoutCalcsNew.php");
include("shippingSurcharges.php");
//header("Cache-Control: no-store, no-cache, must-revalidate");  // HTTP/1.1
//header("Pragma: no-cache");  
setcookie("items",$_COOKIE['items'],0,'/');

extract($_POST);

if(!$shipsurcharge){$shipsurcharge=4;}

/*$shipText[4]="Ground, 8-10 days";  	 
$shipText[10]="3-day select";
$shipText[27]="Second day air";
$shipText[53]="Next day air";
*/
ini_set("display_errors","1");

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
$ship=0;}
else{
	$ship=calculateShipping($sub);
}

//add shipping to list

$paypalItemList.='L_PAYMENTREQUEST_0_NAME'.$j.'='.urlencode('Shipping and Insurance: '.$shipText[$shipsurcharge]).
				 '&L_PAYMENTREQUEST_0_AMT'.$j.'='.urlencode(format($ship+$shipsurcharge));

//use only the not gift card amounts to determine shipping

//add gift card and non gift card subtotals
$sub+=$gcSubtotal;

//$tax=calculateTax($sub);

$total=$sub+$ship+$tax+$shipsurcharge;

$orderdetails.='
<div class="row border-top">
	<div class="cell eightColumns bold subtotal rightAlign middleAlign">Product Subtotal</div>
	<div class="cell twoColumns bold subprice rightAlign middleAlign" id="temp-subtotal" data-value="'.format($sub).'">$'.format($sub).'</div>
	<div class="cell oneColumn"></div>
	<div class="cell fourColumns middleAlign">
		<button type="button" id="update-quantities" data-value="'.substr($idList,0,-1).'">Update Quantities</button>
	</div>	
</div>
<div class="row">
	<div class="cell eightColumns bold subtotal rightAlign middleAlign">Handling & Insurance</div>
	<div class="cell twoColumns bold subprice rightAlign middleAlign" id="shipping-subtotal" data-value="'.format($ship).'">$'.format($ship).'</div>	
	<div class="cell sixColumns"></div>
</div>

<div class="row">
	<div class="cell eightColumns subtotal rightAlign middleAlign">Shipping Method</div>
	<div class="cell fourColumns subprice rightAlign middleAlign">
					<select id="shipping-method" class="medium-input" name="shippingMethod">
						<option data-method="0" value="4"';
						if($shipsurcharge==4){$orderdetails.=' selected="selected"';}
						$orderdetails.='>Ground 8-10 days (add $4.00)</option>
						<option data-method="1" value="10"';
						if($shipsurcharge==10){$orderdetails.=' selected="selected"';}
						$orderdetails.='>3-day select (add $10.00)</option>
						<option data-method="2" value="27"';
						if($shipsurcharge==27){$orderdetails.=' selected="selected"';}
						$orderdetails.='>Second day air (add $27.00)</option>
						<option data-method="3" value="53"';
						if($shipsurcharge==53){$orderdetails.=' selected="selected"';}
						$orderdetails.='>Next day air (add $53.00)</option>
					</select>
	</div>
</div>

<div class="row">
	<div class="cell eightColumns subtotal rightAlign middleAlign">Shipping Total</div>
	<div id="shipping-total" data-value="'.format($ship+$shipsurcharge).'" class="cell twoColumns subprice rightAlign middleAlign">$'.format($ship+$shipsurcharge).'
		
	</div>
	
</div>

<div class="row border-bottom">
	<div class="cell eightColumns bold grandtotal rightAlign middleAlign">ORDER TOTAL</div>
	<div id="temp-total" data-value="'.format($total).'"class="cell twoColumns bold totalprice rightAlign middleAlign">$'.format($total).'</div>
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
			<input id="invoice-total" type="hidden" name="PAYMENTREQUEST_0_AMT" value="'.format($total).'">
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
$_SESSION['shipmethod']=$smethod;
$_SESSION['shippingzip']=$shipzip;

setcookie("shipping",$ship."::".$shipsurcharge."::".$shipText[$shipsurcharge],time()+3600);

setcookie("items",$_COOKIE['items'],time()-60,'/');
setcookie("items",$_COOKIE['items'],0,'/');

echo $orderdetails;
 
if (strstr($HTTP_SERVER_VARS['HTTP_ACCEPT_ENCODING'], 'gzip')){flush();}

ob_flush();

?>
   