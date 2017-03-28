<?php
/************** updateOrderSummary.php *****************/
// this script shows/updates the order summary on checkout.php
/*
Author:			Michael Wagner
Date:			8/1/13 - 3/9/16
Description:	Updates small order summary in checkout window

				3/8/16 - added functionality
				8/7/16 - updated connect file references after
*/

ob_start();

session_name('checkout');
session_start();

ini_set("display_errors", 1);

include_once("/home/asyoulik/connect/mysql_pdo_connect.php");
include_once("/home/asyoulik/public_html/checkoutSettings.php");

include("sandbox.checkoutCalcs.php");
include("shippingSurcharges.php");
//include("shipping-array.php");

$shippingDescription=array(
	
		0	=>	"Ground, 8-10 days",  	 
		1	=>	"3-day select",
		2	=>	"Second day air",
		3	=>	"Next day air",
		4	=>	"No Shipping Charge for Gift Cards",
		5 	=>	"In-store Pickup"
		);
	
extract($_POST);
extract($_GET);
	
	//echo "hello";	

//echo $smethod;
echo "<!--- get variables -->";
foreach($_GET as $k => $v){
	echo "<!-- $k =>$v -->";
}

function getRegistryAddress($regID){
	
	global $db;
	$query = $db->prepare("SELECT * from weddingRegistries WHERE id=:id");
	$query->bindParam(":id",$regID);
	
	$query->execute();
	$result=$query->fetchAll();
	$row=$result[0];
	
	extract($row);
	
		if($raddress){
			$address=$rfname." ".$rlname." ".$raddress.", ".$rcity.", ".$rstate." ".$rzipcode;	
		}
		else{
			$address="";
		}
	
	return $address;
	
}
$subtotal = $ship = $gcSubtotal = 0;
$items="";

calculateCost($_COOKIE["cartNum"],$subtotal,$items,$ship,$inventoryMessage,$gcSubtotal);
//echo $subtotal;

if($_SESSION['shipmethod'] == 5){
	
	//this is an instore pickup
	$ship 			= 0;
	$smethod 		= 5;
	$shipSurcharge 	= 0;
}

else{
	
	//check if it's a gift card transaction etc
	if($_SESSION['giftcardonly']==1){
	
	$ship = 0;
	$smethod = 4;
	$shipSurcharge = 0;	

	}
	
	else{
	
	$ship = calculateShipping($subtotal);

	$smethod = $_SESSION['shipmethod'];
	
	if(!$smethod){
		$smethod=0;
		$shipSurcharge=4;
	}

	if(!$_SESSION['shippingsurcharge']){
		$shipSurcharge=4;
	}	
	else{
		$shipSurcharge=$_SESSION['shippingsurcharge'];
	}
}

}

$shipdescription = $shippingDescription[$smethod];

if($subtotal==0){
	
	$shipSurcharge=0;	

}

$subtotal = $subtotal+$gcSubtotal;

$salestax = $_SESSION['salestax'];

if( $_SESSION['fname'] ){	

//append shipping information, including any gift card address information
 $gcEmails 		= array();
 $arrItems 		= explode("&",substr($_COOKIE["items"],1));
 $item_count 	= count($arrItems);
 $giftCardOnly	= true;
 $hasGiftCard	= false;
	
	for($i=0;$i<$item_count;$i++){
	
	if( substr($arrItems[$i],0,2) == "gc" ){
			$hasGiftCard 	= true;
			$itemData 		= explode(":", $arrItems[$i]);
			$gcEmails[$j] 	= $itemData[2];
			$j++;
		}
		else{
			$giftCardOnly 	= false;
		}
	}


extract($_SESSION);	

if( $fname ){	

$shipto = "
		<div class='row'></div>
		<div class='row'></div>
		<div class='row border-bottom'>
		<div class='cell fourColumns'>
        	<h3>Ship To:</h3>
        </div>";
	
if( !$giftCardOnly ){

if( $smethod == 5 ){
	
	$shipto .= '<div class="cell twelveColumns">
					In store Pickup
				</div>
				</div>';
}
else{

	$shipto .= "
        <div class='cell twelveColumns rightAlign'>
        	<span class='caption link' title='Edit your shipping information' id='edit-shipping'>
        		Edit Shipping/Billing Information
        	</span>
        </div>
    </div>
    <div class='row bold'>$fname $lname</div>
    <div class='row bold'>$address1 $address2</div>
    <div class='row bold'>$city, $state $zip $country</div>";
    
    if($phone){
    	$shipto .= "<div class='row bold'>$phone</div>";
    }
    
    if($cardemail){
	    $shipto .= "<div class='row bold'>$cardemail</div>";
    }
    
    if($giftwrap){
	    $shipto .= "<div class='row bold'>Optional Gift Wrap: $giftwrap</div>";
    }
    else{
	  	$shipto .= "<div class='row bold'>Optional Gift Wrap: None</div>";
	}

}
	
 }   


}
	
if(count($gcEmails)>0){	

foreach($gcEmails as $e){

	if(strpos("@", $e)==0){
		//retrieve address from 	
		$e=getRegistryAddress($e);
	}
	
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
	
	  $billto = "<div class='row'></div>
	  		<div class='row'></div>
	  		<div class='row border-bottom'>
	  		<div class='cell fourColumns'>
	  			<h3>Bill To:</h3>
	  		</div>
	  		";
	  
	  if($_SESSION['shipmethod'] == 5){
		  
		  $billto .= "
		  
		  <div class='cell twelveColumns rightAlign'>
	  			<span class='link caption' title='Edit your billing information' id='edit-shipping'>
	  				Edit Billing Information
	  			</span>
	  		</div>";
	  			
	  }
	  
	  //$billto .="</div>";
	  		
	  		$billto .= "</div>
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

	
$shipto.='<div class="row">
			<div class="cell sixteenColumns">Memo:</div>
		</div>
		<div class="row">
			<div class="cell twoColumns"></div>
			<div class="cell fourteenColumns">'.$note.'</div>
	</div>';

	
	$giftcardContent="";
	$giftCardAmt=0;
	
	if($_SESSION['redeemedGiftCards']){
	
		//print_r($_SESSION['redeemedGiftCards']);
		foreach($_SESSION['redeemedGiftCards'] as $k=>$v){
		
		$giftCardAmt=$giftCardAmt+$v;
		
		$giftcardContent.="<div class='row giftCard'>
			<div class='cell tenColumns'>Redeemed Gift Card <strong>$k</strong>:</div>
			<div class='cell threeColumns'><span class='link removeGiftCard' id='$k'>Remove</span></div>
			<div class='cell threeColumns' id='giftCard-$k' data-value='$v'>$".number_format($v,2)."
			</div>
		</div>
		";
		
		}
		
		if($giftcardContent){
			$giftcardContent.="<div class='row'></div>";
		}	
	}
	
	
	$total=$subtotal+$salestax+$totalShipping;

	//set session variable
	$_SESSION['invoicetotal']=$total;
	
	
	$balance=($total-$giftCardAmt)>0?($total-$giftCardAmt):0;
	
	
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
		

$cost.="<div class='row border-top' id='order-total'>
			<div class='cell elevenColumns bold'>Order Total:</div>
			<div id='temp-total' data-value='$total' class='cell fiveColumns bold'>$".number_format($total,2)."</div>
		</div>
	
	";

$cost.=$giftcardContent;
	 
$cost.="<div class='row border-top' id='order-balance'>
 		<div class='cell elevenColumns bold'>Balance Due:</div>
 		<div id='temp-balance' data-value='$balance' class='cell fiveColumns bold'>$".number_format($balance,2)."
 		</div>
 		</div>";



//put content together
$content="
<div id='order-summary-overlay' class='overlay'>
	Updating tax and shipping...<br>
	<img src='/images/ajax-loader-2.gif' alt='refreshing invoice' style=''></div> 	
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
	global $db;
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
	                $query=$db->prepare("SELECT * from inventory where id=:id");
	                $query->bindParam(":id",$i[0],PDO::PARAM_INT);
					$query->execute();
	                $result=$query->fetchAll();
	                $r=$result[0];//mysql_fetch_assoc($query);
		
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
	

    

//end function 
}	

ob_flush();

?>