<?php 
//10/7/15 TO DO: correct bug with gift card generation for gift cards that do not have a recipient email address

ob_start();

require("GzipCompress.php");
include_once("/connect/mysql_pdo_connect.php");
include_once("sandbox.checkoutCalcs.php");

//header("Cache-Control: no-store, no-cache, must-revalidate");  // HTTP/1.1
//header("Pragma: no-cache");  
setcookie("items",$_COOKIE['items'],0,'/');

//ini_set("display_errors","1");

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
	';
	
	}
else {
//split cookie data into array $item using & delimiter
//cookie data stored as &id1:quantity1&id2:quantity2 etc etc

$item=explode('&',substr($_COOKIE['items'],1));

$itemCount=count($item);

$inventoryMessage='';

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
			
          $sub+=$price*$i[1];
	}
	
	$idList.=$i[0].',';
        
	}
	

if ($inventoryMessage) { 
		$orderdetails.= '<div class="row">
				<div class="cell sixteenColumns">'.$inventoryMessage."</div>
		</div>"; }

$orderdetails.='<div class="row border-bottom"></div>';

if($gconly=="true"){
	$ship=0;
}
else{
	$shipsub=$sub-$gcSubtotal;
	//echo $shipsub;
	$ship=calculateShipping($shipsub);
}

//use only the not gift card amounts to determine shipping

//add gift card and non gift card subtotals
$sub+=$gcSubtotal;

$total=$sub+$ship;

$orderdetails.='
<div class="row">
	<div class="cell eightColumns bold subtotal rightAlign middleAlign">Product Subtotal</div>
	<div class="cell twoColumns bold subprice rightAlign middleAlign">$'.format($sub).'</div>
	<div class="cell oneColumn"></div>
	<div class="cell fourColumns middleAlign">
		<button type="button" id="update-quantities" data-value="'.substr($idList,0,-1).'">Update Quantities</button>
	</div>	
</div>
<div class="row">
	<div class="cell eightColumns bold subtotal rightAlign middleAlign">Shipping & Insurance</div>
	<div class="cell twoColumns bold subprice rightAlign middleAlign">$'.format($ship).'</div>	
	<div class="cell sixColumns"></div>
</div>

<div class="row border-bottom">
	<div class="cell eightColumns bold grandtotal rightAlign middleAlign">ORDER TOTAL</div>
	<div class="cell twoColumns bold totalprice rightAlign middleAlign">$'.format($total).'</div>
	<div class="cell sixColumns></div>
</div>
</form>
</div>';
}
setcookie("items",$_COOKIE['items'],time()-60,'/');

setcookie("items",$_COOKIE['items'],0,'/');

echo $orderdetails;
 
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')){flush();}

ob_flush();

?>
   