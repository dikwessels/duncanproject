
<?php
/* 
 
 Invoice.php Class
 Written 10-8-14 by Michael Wagner
 
 Performs basic functions to streamline checkout
  	• displays confirmation screen
  	• sends confirmaiton emails
  	
  3/8/16
  	 - updated for in-store pickups
  8/5/16
  	 - changed connection string after server change
  	
*/
include_once("connect/mysql_pdo_connect.php");

class Invoice
{
 public $invoiceID 		= "";
 public $fname 			= "";
 public $lname 			= "";
 public $address1 		= "";
 public $address2 		= "";
 public $city 			= "";
 public $state 			= "";
 public $zip 			= "";
 public $country 		= "";
 public $phone 			= "";		//main contact phone number
 public $cardtype 		= "";		//card type, not used anymore
 public $cardnumber 	= "";			//card number, not used anymore
 public $exp 			= "";				//not actually used anymore
 public $cardname 		= "";			//credit card name
 public $cardaddress 	= "";		//credit card street address
 public $cardcity 		= "";			//credit card city
 public $cardstate 		= "";			//credit card state
 public $cardzip 		= "";			//credit card zip
 public $cardcountry 	= "";		//credit card country
 public $cardphone 		= "";			//credit card phone (aka main contact phone)
 public $cardemail 		= "";			//credit card/main invoice email addresss
 public $time 			= "";
 public $customerNum 	= 0;			//this is also the invoice # 
 public $items 			= "";				//invoice items from cookie
 public $tax 			= 0;					
 public $shipping 		= 0;
 public $subtotal 		= 0;
 public $shipMethod 	= 0;
 public $shippingMethod = 0;
 public $shipTo 		= "";
 public $status 		= "" ;
 public $giftwrap 		= "";
 public $note 			= "";
 public $tracking 		= "";
 public $transactionID 	= "";
 public $giftCardCode 	= "";
 public $giftCardAmt 	= 0;
 public $ordersummary 	= "";
 public $redeemedGiftCards=array();
 
 
 //three versions of invoice receipts
 public $customerReceipt 	= "";
 public $screenReceipt 		= "";
 public $storeReceipt 		= "";
 
 
 public function display_receipt(){
	 echo $this->screenReceipt;
 }
 
 private function getRegistryAddress($regID){
	global $db;
	$query=$db->prepare("SELECT * from weddingRegistries WHERE id=:id");
	$query->bindParam(":id",$regID);
	
	$query->execute();
	$result=$query->fetchAll();
	$row=$result[0];
	extract($row);
	
		if($raddress) {
			
			$address	=	$rfname." ".$rlname.", ".$raddress.", ".$rcity.", ".$rstate." ".$rzipcode;	
		}
		else {
			$address	=	"";
		}
	
	return $address;
	
}

 public function email_receipt($version=""){

 	global $PayPalMode;
 	
	 $headers.= "MIME-Version: 1.0\r\n";
	 $headers.= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	 $headers.="From:orders@asyoulikeitsilvershop.com";

	 $customersubject="As You Like It Silver Shop Order #".$this->invoiceID;
	 $storesubject="You Have Received Order #".$this->invoiceID;
	 
//send shop email
   if($version){
	
		if( $version=="shop" ){
			mail("wagnermichaeljames@gmail.com",$storesubject,$this->storeReceipt,$headers);
			mail("dcox@asyoulikeitsilvershop.com",$storesubject,$this->storeReceipt,$headers);
		}
		
		if( $version=="customer" ){
			mail($this->cardemail,$customersubject,$this->customerReceipt,$headers);
		}
	
	}
	
	else{
	
		 //mail("wagnermichaeljames@gmail.com",$storesubject,$this->storeReceipt,$headers);
		 mail("wagner_michaeljames@yahoo.com",$storesubject,$this->storeReceipt,$headers);
		 
		 if( $PayPalMode == "live" ){
			  mail("wagnermichaeljames@gmail.com","Live Sale ".$storesubject,$this->storeReceipt,$headers);
			  mail("dcox@asyoulikeitsilvershop.com",$storesubject,$this->storeReceipt,$headers);
		 }
		 
		 mail($this->cardemail,$customersubject,$this->customerReceipt,$headers);
	
	}
	
 }
 
 public function generate_receipt(){

    $total = $this->subtotal + $this->shipping + $this->tax;
    
    $giftcardredemption = "";
    
    $balance = $total;
    
    if( $this->giftCardCode != "" ){
	    
    $this->redeemedGiftCards = json_decode($this->giftCardCode);
    
    //print_r($this->redeemedGiftCards);	
    if( count($this->redeemedGiftCards) > 0 ){
   
    foreach($this->redeemedGiftCards as $k=>$v){
	    
	 	$balance = $balance - $v;
	 
	 	$giftcardredemption .= "
	 		<tr style='font-weight:bold' >
	    		<td colspan='2' align='right'>
	    			Redeemed Gift Card <span style='font-size:75%'><strong>".$k."</strong></span>
				</td>
				<td style='padding-left:2%;'>-$".number_format($v,2)."</td>
			</tr>";
		}
    
    }
    }
    
    $giftcardredemption .= "<tr style='font-weight:bold'>
	    	<td colspan='2' align='right'>
	    		Balance:
			</td>
	    	<td style='padding-left:2%;'>$".number_format($balance,2)."</td>
	    </tr>";
   
   
   
    if( $_SESSION['giftcardonly']!=1 ){
	    
	    if( $_SESSION['shipmethod'] == 5 ){
		    
		     $this->shipMethod = 5;
		     $this->shipTo	= "In-Store Pickup";
		     $shippingnotice = '<p> We will contact you via email once your order is ready to be picked up.</p>';
	    
	    }
	    else{
		    
			 $this->shipTo =	$this->fname." ".$this->lname; 
			
		     $shippingnotice = '<p> Once your order is shipped, you will receive a shipping email with your tracking number.</p>';
	    }
    }
    
    //$shippingnotice = ($_SESSION['giftcardonly']!=1)?'<p> Once your order is shipped, you will receive a shipping email with your tracking number.</p>':'';
    
    
    $headers=array(
	    
    		'customer'=>'<p>Thank you for your online order at As You Like It Silver Shop. Please keep this email as receipt for your order.</p>'.$shippingnotice,
    		
			'screen'=>'
  	 		<h3>Thank you for your order!</h3>
  	 		<p>
  	 		Your payment has been received and your order is being processed.  Your order number is: <strong>'.$this->invoiceID.'</strong></p>
  	 		<p>Please print this page for your records, and for your convenience a confirmation email has been sent to <strong>'.$this->cardemail.'</strong>.</p>'.$shippingnotice,
  	 		'store'=>'<p>You have received an online order. Order details below:</p>'
  	 	);
  	 	
  	 $thankyou=array(
  	 		'customer'=>'',
  	 		'screen'=>'<p>Thank you for shopping at As You Like It Silver shop</p>',
  	 		'store'=>''
  	 		);

/*
If you have any questions regarding your purchase, feel free to contact us at <a target="_blank" value="+18008282311" href="tel:1-800-828-2311">1-800-828-2311</a>. Thank you for your business!<br>
 		Sincerely,<br><br>
 		As You Like It Sales Department<br>
 		<a target="_blank" href="mailto:sales@asyoulikeitsilvershop.com">sales@asyoulikeitsilvershop.<wbr></wbr>com</a><br>
 		<a target="_blank" href="http://www.asyoulikeitsilvershop.com">www.asyoulikeitsilvershop.com</a><br><br>
 		<span>As You Like Offers a full line of repair and monogramming services to meet your needs. For more information please call us.</span>

*/
	$footers=array('customer'=>'If you have any questions regarding your purchase, feel free to contact us at <a target="_blank" value="+18008282311" href="tel:1-800-828-2311">1-800-828-2311</a>. Thank you for your business!<br>
 		Sincerely,<br><br>
 		As You Like It Sales Department<br>
 		<a target="_blank" href="mailto:sales@asyoulikeitsilvershop.com">sales@asyoulikeitsilvershop.<wbr></wbr>com</a><br>
 		<a target="_blank" href="http://www.asyoulikeitsilvershop.com">www.asyoulikeitsilvershop.com</a><br><br>
 		<span>As You Like Offers a full line of repair and monogramming services to meet your needs. For more information please call us.</span>',
 		'screen'=>'',
 		'store'=>''
 		);
 		
	$shippingdescription=array(
						"Ground (8-10 days)",
						"3 Day Select",
						"2nd Day Air",
						"Next Day Air",
						"Gift Card",
						"In-store Pickup");
	
	$invoiceitems = $this->getInvoiceItems();					
	
	$template = file_get_contents("Invoice.html");
	
	$this->customerReceipt 	= $template;
	$this->storeReceipt 	= $template;
	$this->screenReceipt 	= $template;
	
	$versionarray = array('customer','screen','store');
	
	$findarray=array(
				"{{header}}",
				"{{billtoname}}",
				"{{billtoaddress}}",
				"{{billtocity}}",
				"{{billtostate}}",
				"{{billtozip}}",
				"{{billtocountry}}",
				"{{billtoemail}}",
				"{{giftwrap}}",
				"{{invoiceID}}",
				"{{invoiceItems}}",
				"{{note}}",
				"{{redeemedgiftcard}}",
				"{{salestax}}",
				"{{shipping}}",
				"{{shippingDescription}}",
				"{{shiptoname}}",
				"{{shiptoaddress}}",
				"{{shiptocity}}",
				"{{shiptostate}}",
				"{{shiptozip}}",
				"{{shiptocountry}}",
				"{{subtotal}}",
				"{{testing}}",
				"{{thankyou}}",
				"{{total}}",
				"{{transactionID}}",
				"{{footer}}"
				);
				
	//$this->subtotal=number_format($this->subtotal,2);
	//$total=number_format($total,2);

  foreach($versionarray as $version){
  
   $replacearray=array(
			    $headers[$version],
   				$this->cardname,
   				$this->cardaddress,
   				$this->cardcity,
   				$this->cardstate,
   				$this->cardzip,
   				$this->cardcountry,
   				$this->cardemail,
   				$this->giftwrap,
   				$this->invoiceID,
   				$invoiceitems,
   				$this->note,
   				$giftcardredemption,
   				number_format($this->tax,2),
   				number_format($this->shipping,2),
   				$shippingdescription[$this->shipMethod],
   				$this->shipTo,
   				$this->address1,
   				$this->city,
   				$this->state,
   				$this->zip,
   				$this->country,
   				number_format($this->subtotal,2),
   				'',
   				$thankyou[$version],
   				number_format($total,2),
   				$this->transactionID,
   				$footers[$version]
   				);
   	
   	
	if($version	==	'customer'){
		$this->customerReceipt	=	str_replace($findarray,$replacearray,$this->customerReceipt);
	}
	
	if($version	==	'screen'){
		$this->screenReceipt	=	str_replace($findarray,$replacearray,$this->screenReceipt);
	}
		
	if($version	==	'store'){
		$this->storeReceipt		=	str_replace($findarray,$replacearray,$this->storeReceipt);
	}
  
  }
  

 }

 public function getInvoiceItems(){

 //this function generates a nice viewable list of the invoice items for confirmaiton emails and the confirmation page
 global $db;
 
  $invoiceitem=explode('&',$this->items);
  
   $j=0;
   //add non gift cards
   foreach($invoiceitem as $v){ 
		//separate each item into id, quantity and regID data
		$i=explode(':',$v);
			if(strlen($i[0])>0){
             if(substr($i[0],0,2)!="gc"){
                   	$itemQuantity=$i[1];
                 	$itemName="";
                 	//echo "id is $i[0]";
                 	$stmt="SELECT * FROM inventory WHERE id=:id";
                 	$query=$db->prepare($stmt);
                 	$query->bindParam(":id",$i[0]);
                 	$query->execute();
                 	
                 	$result=$query->fetchAll();
                 	
                 	$r=$result[0];
                 	extract($r);
                 	
		            if($sale){ 
			           $price=$sale;
			        } 
			        else{
				       $price=$retail;
				    }

				    if($pattern!=''){$itemName.=" $pattern BY ";}
                    if($brand!=''&& $brand!='UNKNOWN'){$itemName.=" $brand";}
                    $itemName.=" $item";     

					$invoiceitems.='<tr style="color:#777;" id="'.$j.'">
			   				<td>'.$itemQuantity.'</td>
			   				<td>'.stripslashes(ucwords(strtolower($itemName))).'</td>
			   				<td style="padding-left:2%;">$'.number_format($price,2).'</td>
			   			  </tr>';

                }
                else{
	                //gift cards go here
	                $cardData=explode("||", $i[2]);
	                $cardCode=$cardData[1];
	                $cardID=$cardData[0];
	                //$recipientemail=$i[2];
	                if(strpos($cardID, "@")==0){
		                //check to see if there is an email, otherwise retrieve address from 
		                $deliverTo= $this->getRegistryAddress($cardID);
	                }
	                
	                $cardamount=$i[1];
	                
	                $invoiceitems.="<tr style='color:#777; id='$j'>
			   				<td style='vertical-align:top;'>1</td>
			   				<td style='vertical-align:top;'>
			   					As You Like It Silver Shop Gift Card: Code $cardCode<br>
			   					<span class='caption'>Delivery to $deliverTo</span>
			   				</td>
			   				<td style='vertical-align:top;padding-left:2%;'>$".number_format($cardamount,2)."</td>
			   			  </tr>";
	                
			   		}
                }
         $j++;     			  
     //end foreach loop
     }

	return $invoiceitems;
}

 public function load($mode = "live"){
	 
	 global $db;
	 
	 $tbl="customers";
	 
	 if( $mode != "live" ) {
		 $tbl = "tblCustomersSandbox";
	 }
	
	 $stmt="SELECT * FROM ".$tbl." WHERE customerNum=:invoiceID";
	 
	 $query=$db->prepare($stmt);
	 $query->bindParam(":invoiceID",$this->invoiceID);	
	 $query->execute();
	 
	 //echo $query;
	 
	 $result=$query->fetchAll();
	 //$result=mysql_query($query);
	
	 $invoiceArr=$result[0];
	 
	 foreach($invoiceArr as $k=>$v){
		 $this->$k=$v;	
	 }
	 
 }
 
 public function save($invoiceData,$express){

	global $environment;
	global $testing;
	global $db;
	global $PayPalMode;

//mysql_query("UNLOCK tables");

	//SHIPPING INFORMATION
	
	if($_SESSION['shipmethod'] == 5){
		
		$shipto		= "In-store Pickup";
		$address1  	= "3033 Magazine St";
		$city		= "New Orleans";
		$state		= "LA";
		$zip		= "70115";
		$country 	= "US";
		
	}
	else{
		
		$shipto		=	urldecode($invoiceData['SHIPTONAME']);
		$address1	=	urldecode($invoiceData['SHIPTOSTREET']); //1%20Main%20St
		$city		=	urldecode($invoiceData['SHIPTOCITY']);// San%20Jose
		$state		=	urldecode($invoiceData['SHIPTOSTATE']);// CA
		$zip		=	urldecode($invoiceData['SHIPTOZIP']);// 95131
		$country	=	urldecode($invoiceData['SHIPTOCOUNTRY']);// US
	}
	//will need to modify this to store a integer value for shipping method, then total
	
	//$shipping=$shipping+$shippingMethod;
	
	$subtotal		=	$_SESSION['subtotal'];

	$shipping 		= 	$_SESSION['totalshipping'];
	
	//this doesn't really apply to gift card only transactions
	$shippingMethod	=	$_SESSION['shippingsurcharge'];
	
	$tax = 0;

//BILLING INFORMATION

	
	$fname			=	urldecode($_SESSION['fname']); 
	$lname			=	urldecode($_SESSION['lname']); 
	
	//DOUBLE CHECK 
	//Due to a mystery problem with express checkouts, some are being processed under a user name that returns no 
	//first or last name
	
	if($fname == ""){
	
		$fname = urldecode($invoiceData['FIRSTNAME']);
	
	}
	
	if($lname == ""){
		
		$lname = urldecode($invoiceData['LASTNAME']);
	
	}
	
	if($shipto == "" && $_SESSION['shipmethod']!=5){
	
		$shipto = "$fname $lname";
	
	}
	
	$cardname		=	urldecode($invoiceData['BILLTONAME']);
	
	if( $cardname == "" ){
	
		$cardname = "$fname $lname";
	
	}
	
	
	$cardaddress	=	urldecode($invoiceData['BILLTOSTREET']);
	$cardcity		=	urldecode($invoiceData['BILLTOCITY']);
	$cardstate		=	urldecode($invoiceData['BILLTOSTATE']);
	$cardcountry	=	urldecode($invoiceData['BILLTOCOUNTRY']);
	$cardzip		=	urldecode($invoiceData['BILLTOZIP']);
	$cardemail		=	urldecode($invoiceData['EMAIL']); 

//PAYMENT TYPE AND TRANSACTION ID
	$cardtype		=	urldecode($invoiceData['CARDTYPE']);
	
	
	if( $invoiceData['TRANSACTIONID'] != "" ){
	
		$transactionID = $invoiceData['TRANSACTIONID'];	

	}
	else{
		
		$transactionID = $invoiceData['PPREF'];
	
	}
	
	if( ($express == 1) || ($cardtype == "") ){
		
		$cardtype = "PayPal";
	
	}
	
	else{
		
		$cardtype = urldecode($invoiceData['CARDTYPE']);
			
	}

	//CONTACT PHONE NUMBER
	if($express && $invoiceData['PHONENUM']){
		
		$phone	=	urldecode($invoiceData['PHONENUM']);	
	
	}
	else{
		
		$phone	=	urldecode($_SESSION['phone']);
	
	}

	$items = urldecode($_COOKIE['items']);
	
	$note = urldecode($_SESSION['note']);
	
	//if they also added notes at the paypal checkout then add those to the note
	if( $invoiceData['NOTE'] != "" ){
		
		$note.="\n\rSpecial Instructions: ".urldecode($invoiceData['NOTE']);
	
	}
	
	$giftwrap = urldecode($_SESSION['giftwrap']);
	
	$giftCardCode = "";
	
	if($_SESSION['redeemedGiftCards']){
		
		$giftCardCode= json_encode($_SESSION['redeemedGiftCards']);
	
	}
	
	$tax=$_SESSION['salestax'];

	$shipMethod = 0;

	if($_SESSION['shipmethod']){
		$shipMethod = $_SESSION['shipmethod'];
	}

	$tbl="customers";
	
	if( $PayPalMode != "live" ) {
		//update the testing table only
		$tbl="tblCustomersSandbox";
	}	
	
	if( ($_SESSION['giftcardonly']) && ($_SESSION['giftcardonly'] != "0") ){
		//$shipping = 0;
		$shippingMethod = 0;
		$shipMethod = 0;
	}
	
$stmt="INSERT INTO ".$tbl."(fname,lname,address1,address2,city,state,country,zip,phone,cardtype,cardnumber,exp,cardname,
cardaddress,cardcity,cardstate,cardcountry,cardzip,cardphone,cardemail,tax,shipping,subtotal,shipMethod,shippingMethod,giftwrap,note,giftCardCode,giftCardAmt,items,transactionID,status)";

$stmt.=" VALUES('$fname','$lname','$address1','$address2','$city','$state','$country','$zip','$phone','$cardtype',
'$cardnumber','$exp','$cardname','$cardaddress','$cardcity','$cardstate','$cardcountry','$cardzip','$cardphone','$cardemail','$tax','$shipping','$subtotal','$shipMethod',$shippingMethod,'$giftwrap','$note','$giftCardCode','$giftCardAmt','$items','$transactionID','processed')";

$query = $db->prepare($stmt);
$query->execute();

//$result=mysql_query($query);

 if($query->rowCount()>0){
	 
   $this->invoiceID = $db->lastInsertId(); //mysql_insert_id();
   //=$custNum;
 }
 else{
	 
	die($db->errorInfo());
 }
 
	
}

 public function print_out(){
	foreach(get_object_vars($this) as $k=>$v){
		echo "$k: $v<br>";
	}
 }
 
}

?>