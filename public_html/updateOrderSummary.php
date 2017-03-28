<?php
session_name('checkout');
session_start();

include("checkoutCalcsNew.php");
include("/connect/mysql_connect.php");
include("shippingSurcharges.php");
//include("shipping-array.php");

$shippingDescription=array(
		0=>"Ground, 8-10 days",  	 
		1=>"3-day select",
		2=>"Second day air",
		3=>"Next day air"
		);
		
		
$smethod=$_SESSION['shipmethod'];
//echo $smethod;
$shipdescription=$shippingDescription[$smethod];
		
extract($_POST);
extract($_GET);


$subtotal=$ship=$gcSubtotal=0;
$items="";
calculateCost($_COOKIE["cartNum"],$subtotal,$items,$ship,$inventoryMessage,$gcSubtotal);
//$note="";



/*if($_COOKIE["custNum"]){	

	$query="SELECT * from customers where customerNum=".$_COOKIE["custNum"];
	$result=mysql_query($query);
	$c=mysql_fetch_assoc($result);

	$shipto="$c[fname] $c[lname]<br>$c[address1] $c[address2]<br>$c[city], $c[state] $c[zip] $c[country]<br>$c[phone]";
	
	
	$shipSurcharge=$c['shippingMethod'];
	
}	
else{
	$shipSurcharge=4;
}

*/
//default shipping surcharge is $4
$shipSurcharge=0;

/*if($_COOKIE['shipping']){
	$shippingInfo=split("::",urldecode($_COOKIE['shipping']));
	$totalShipping=$shippingInfo[0]+$shippingInfo[1];
	$shipSurcharge=$shippingInfo[1];
	$ship=$shippingInfo[0];
	if($_COOKIE['shipzip']){
		$zip=$_COOKIE['shipzip'];
	}
}
else{
	$totalShipping=$ship+4+$shipSurcharge;
}

$shipSurcharge=0;
if($_COOKIE['shipping']){
	$shippingInfo=split("::",urldecode($_COOKIE['shipping']));
	$totalShipping=$shippingInfo[0]+$shippingInfo[1];
	$shipSurcharge=$shippingInfo[1];
	$ship=$shippingInfo[0];
}
else{
	$totalShipping=$ship+4+$shipSurcharge;
}
*/

$ship=$_SESSION['shipping'];
$shipSurcharge=$_SESSION['shippingsurcharge'];
$salestax=$_SESSION['salestax'];

//echo $shipSurcharge;


$st=$subtotal-$gcSubtotal;
    //$tx=calculateTax($st);

if($_SESSION['fname']){	

//if($_COOKIE["custNum"]){
//	$query="SELECT * from tblTempInvoiceData where tmpID=".$_COOKIE["custNum"];
//	$result=mysql_query($query);
//	$c=mysql_fetch_assoc($result);
//	extract($c);
//append shipping information, including any gift card address information
 $gcEmails=array();
 $arrItems=explode("&",substr($_COOKIE["items"],1));
 $item_count=count($arrItems);
 $giftCardOnly=true;
 $hasGiftCard=false;
	for($i=0;$i<$item_count;$i++){
	if(substr($arrItems[$i],0,2)=="gc"){
			$hasGiftCard=true;
			$itemData=explode(":", $arrItems[$i]);
			$gcEmails[$j]=$itemData[2];
			$j++;
		}
		else{
			$giftCardOnly=false;
		}
	}

//}
//else{
extract($_SESSION);	
//}

if($fname){	
$shipto="
		<div class='row'></div>
		<div class='row'></div>
		<div class='row border-bottom'>
		<div class='cell fourColumns'>
        	<h3>Ship To:</h3>
        </div>";
	
if(!$giftCardOnly){

$shipto.="
        <div class='cell twelveColumns rightAlign'>
        <span class='caption link' title='Edit your shipping information' id='edit-shipping'>Edit Shipping/Billing Information</span>
        </div>
    </div>
    <div class='row bold'>$fname $lname</div>
    <div class='row bold'>$address1 $address2</div>
    <div class='row bold'>$city, $state $zip $country</div>";
    if($phone){
    	$shipto.="<div class='row bold'>$phone</div>";
    }
    if($cardemail){
	    $shipto.="<div class='row bold'>$cardemail</div>";
    }
    
    if($giftwrap){
	    $shipto.="<div class='row bold'>Optional Gift Wrap: $giftwrap</div>";
    }
    else{
	  $shipto.="<div class='row bold'>Optional Gift Wrap: None</div>";
	}
	
	
 }   

}

			
if(count($gcEmails)>0){	
foreach($gcEmails as $e){
	$emails.="<span class='bold'>$e</span><br>";
}
$shipto.="<div class='row'>
  			<div class='cell sixteenColumns'>
  				Gift card(s) will be sent to the following:<br>$emails
  			</div>
  		</div>";
  }	 
$shipto.="</div>";
 

if(!$_POST['editBilling']){
	
	  $billto="<div class='row'></div>
	  		<div class='row'></div>
	  		<div class='row border-bottom'>
	  		<div class='cell sixteenColumns'>
	  			<h3>Bill To:</h3>
	  		</div>
	  		<!--<div class='cell twelveColumns rightAlign'>
	  			<span class='link caption' title='Edit your billing information' id='edit-billing'>Edit Billing Information</span>
	  		</div>-->
	  		</div>
	  		<div class='row'>$cardfname $cardlname</div>
	  		<div class='row'>$cardaddress</div>
	  		<div class='row'>$cardcity, $cardstate $cardzip
	  		</div>";
	  		
	
	if($cardphone){
	  	$billto.="<div class='row'>
	  			$cardphone
	  			</div>";
	  }
	  		if($cardnumber){	
	  			$billto.="<div class='row'>
	  						xxxx-xxxx-xxxx-".substr($c[cardnumber],-4)."
	  					Exp $c[exp]</div>";
	  		}
}  

}	

else{
	//$shipSurcharge=4;
}

$totalShipping=$ship+$shipSurcharge;

	/*if($giftwrap){
		$shipto.="<div class=\"row\">
					<div class=\"cell thirteenColumns\">Gift wrap: ".ucwords($giftwrap)."</div>
					<div class=\"cell threeColumns\">\$0.00</div>
				</div>"; 
	}
	*/
	
$shipto.='<div class="row">
			<div class="cell sixteenColumns">Memo:</div>
		</div>
		<div class="row">
			<div class="cell twoColumns"></div>
			<div class="cell fourteenColumns">'.$note.'</div>
	</div>';

/*$shipto.="<div class=\"row\">
			<div class=\"cell thirteenColumns bold\">Total</div>
			<div id=\"temp-total\" data-value=\"$total\" class=\"cell threeColumns bold\">
				<span id=total>\$".format($total)."</span>
			</div>
		</div>
	";

*/

	//$c=mysql_fetch_assoc($query);
	
	//$ship+=$c['shippingMethod'];
	
	if($c['giftCardAmt']>0){
		$giftCardAmt=$c['giftCardAmt'];
	}
	
	
	if($_SESSION['giftcardcode']){
	
		$giftcardCode=$_SESSION['giftcardcode'];
		
		if($_SESSION['giftcardamount']){
			$giftCardAmt=$_SESSION['giftcardamount']?$_SESSION['giftcardamount']:0;
		}
	}
	

	$total=$subtotal+$salestax+$totalShipping;

	//set session variable
	$_SESSION['invoicetotal']=$total;
	
	$balance=$total;
	if($giftCardAmt>0){
		$balance=($total-$giftCardAmt)>0?($total-$giftCardAmt):0;
	}
	
	//$giftCardAmt=$c['giftCardAmt'];
	
	if($total<0){$total=0;}

	$cost="
	
	<div class='row'>
			<div class='cell thirteenColumns'>Sub-Total:</div>
			<div class='cell threeColumns' id='temp-subtotal' data-value='$subtotal'>$".number_format($subtotal,2)."</div>
		</div>
	<div class='row'>
		<div class='cell thirteenColumns'>Tax:</div>
		<div class='cell threeColumns' id='temp-tax' data-value='$salestax'>$".number_format($salestax,2)."</div>
	</div>
	<div class='row'>
		<div class='cell sixteenColumns bold'>Shipping:</div>
	</div>
	<div class='row'>
		<div class='cell twoColumns'></div>
		<div class='cell elevenColumns'>Handling & Insurance:</div>
		<div class='cell threeColumns' id='temp-shipping-subtotal' data-value='$ship'>$".number_format($ship,2)."</div>
	</div>
	
	<div class='row'>
		<div class='cell twoColumns'></div>
		<div class='cell elevenColumns' id='shipping-text'>
		$shipdescription:
		</div>
		<div class='cell threeColumns' id='temp-shipping-surcharge' data-value='$shipSurcharge'>$"
			.number_format($shipSurcharge,2)."</div>
	</div>
	
	<div class='row'>
	<div class='cell twoColumns'></div>
	<div class='cell elevenColumns bold'>Shipping Total:</div>
	<div class='cell threeColumns' id='temp-shipping' data-value='$totalShipping'>$".number_format($totalShipping,2)."</div>
			
	</div>";
		

/*$cost.="<div class=\"row\">
			<div class=\"cell thirteenColumns\" id=\"temp-gift-wrap\">Gift wrap:";

			$gw=$c[giftwrap]?$c[giftwrap]:"No Gift Wrap";

			$cost.="$gw</div>
			<div class=\"cell threeColumns\">\$0.00</div>
		</div>"; 
*/

$cost.="<div class='row border-top' id='order-total'>
			<div class='cell elevenColumns bold'>Order Total:</div>
			<div id='temp-total' data-value='$total' class='cell fiveColumns bold'>$".number_format($total,2)."</div>
		</div>
	
	";

	
	if($giftCardAmt){
		$cost.="<div class='row'>
			<div class='cell thirteenColumns'>Redeemed Gift Card $giftcardcode:</div>
			<div class='cell threeColumns' data-code='$giftcardcode' id='temp-gift-card' data-value='$giftCardAmt'>$".number_format($giftCardAmt,2)."
			</div>
		</div>
		<div class='row'>
		</div>
		";
	  
	}
	
 
 $cost.="<div class='row border-top' id='order-balance'>
 		<div class='cell elevenColumns bold'>Balance Due:</div>
 		<div id='temp-balance' data-value='$balance' class='cell fiveColumns bold'>$".number_format($balance,2)."
 		</div>
 		</div>";



//put content together
$content="
<div id='order-summary-overlay' class='overlay'>Updating tax and shipping...<br><img src='/images/ajax-loader-2.gif' alt='refreshing invoice' style=''></div> 	
<div class='row bold border-bottom'>
			<div class='cell eightColumns'>
		  		<h3>Your Order:</h3>
		  	</div>
		  	<div class='cell eightColumns rightAlign'>
		  		<a class='caption link' href='/shoppingCart.php'>Edit Shopping Cart</a>
		  	</div>
		  	</div>
		  	<div class='row'>
		  		<div class='cell thirteenColumns'>
		  		Item Description
		  		</div>
		  		<div class='cell threeColumns'>
		  		Price
		  		</div>
		  	</div>
		  	$items
		  	
		  	<div class='row border-top'></div>
		  	
		  	$cost		  	
		  	$shipto
		  	
		  	$billto
		  ";	


echo $content;

//foreach($_SESSION as $k=>$v){
//	echo $k."=>".$v."<br>";
//}

function calculateCost($cn,&$subtotal,&$items,&$ship,&$inventoryMessage,&$gcSubtotal) {
	
	//separate items cookie by &
	$item=explode('&',substr($_COOKIE['items'],1));
       
           
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
			
			      if ($r[quantity]==0) { 
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
			                	$price=$r[sale];
			                } 
			                else {
				                $price=$r[retail];
				            }

				            if($r[pattern]!=''){$itemName.=" $r[pattern] BY";}
                            if($r[brand]!=''&&$r[brand]!='UNKNOWN'){$itemName.=" $r[brand]";}
                            $itemName.=" $r[item]";

				            ;

                            
                            $subtotal+=$price*$i[1];
		      }
              
               // end if($i[0]=="000000")        
               }
              
               $price="$".format($price);
               $items.="<div class='row' style='color:#555'>
               				<div class='cell oneColumn min-zero'>$itemQuantity</div>
			    			<div class='cell twelveColumns'>$itemName</div>
			    			<div class='cell threeColumns'>$price</div>
			    		</div>";
        //end foreach loop
       }
		
	setcookie("items",$_COOKIE['items'],0,'/'); 
	
    $ship=calculateShipping($subtotal);

   $subtotal=$subtotal+$gcSubtotal;

//end function 
}	

?>