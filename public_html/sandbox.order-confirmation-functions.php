<?php
/***** 

Written 10-7-2014 by Michael Wagner
Consolidates functions between express-checkout.php and order-confirmation.php
Those scripts could likely be consolidated into one script.


//10/7/15 TO DO: correct bug with gift card generation for gift cards that do not have a recipient email address


*****/
session_name('checkout');
session_start();

include_once("/home/asyoulik/connect/mysql_pdo_connect.php");

include_once("GiftCard.php");

include_once("Invoice.php");

function clearSession(){
	session_unset();
	setcookie("custNum","",time()-30);
	setcookie("shipping","",time()-30);
	setcookie("items","",time()-30);
}

function createPaymentResponseFromSession(){

//this is called for transactions that are paid entirely with a gift card
//-1 card type for gift cards

	$arr=array(
		
	'BILLTONAME'=>$_SESSION['cardfname'].' '.$_SESSION['cardlname'],
	'BILLTOSTREET'=>$_SESSION['cardaddress'],
	'BILLTOCITY'=>$_SESSION['cardcity'],
	'BILLTOSTATE'=>$_SESSION['cardstate'],
	'BILLTOZIP'=>$_SESSION['cardzip'],
	'BILLTOCOUNTRY'=>$_SESSION['cardcountry'],
	'CARDTYPE'=>-1,
	'EMAIL'=>$_SESSION['cardemail'],	
	'FIRSTNAME'=>$_SESSION['fname'],
	'LASTNAME'=>$_SESSION['lname'],
	'SHIPTONAME'=>$_SESSION['fname'].' '.$_SESSION['lname'],
	'SHIPTOSTREET'=>$_SESSION['address1'].' '.$_SESSION['address2'],
	'SHIPTOCITY'=>$_SESSION['city'],
	'SHIPTOSTATE'=>$_SESSION['state'],
	'SHIPTOCOUNTRY'=>$_SESSION['country'],
	'SHIPTOZIP'=>$_SESSION['zip'],
	'PPREF'=>'',
	'RESULT'=>0,
	'NAMETOSHIP'=>$_SESSION['fname'].' '.$_SESSION['lname'],
	'ADDRESSTOSHIP'=>$_SESSION['address1'].' '.$_SESSION['address2'],
	'CITYTOSHIP'=>$_SESSION['city'],
	'STATETOSHIP'=>$_SESSION['state'],
	'ZIPTOSHIP'=>$_SESSION['zip'],
	'COUNTRYTOSHIP'=>$_SESSION['country'],
	'BILLTOEMAIL'=>$_SESSION['cardemail'],
	'AMT'=>$_SESSION['invoicetotal']-$_SESSION['giftcardamount'],	
	'TRANSACTIONID'=>'TRANSIDGIFTCARD'.$_SESSION['giftcardcode']
	
	);
	
	return $arr;
}

function generateGiftCards($giftcardArray,$saleID){
	//split the gift card session data into an array
	//if the gift card was purchased for a registry, the $recipientemail is actually the registry ID	
	foreach($giftcardArray as $giftcardData){
	 $giftcard=new GiftCard();
	 
	 $email=$giftcardData['email'];
	 $amount=$giftcardData['amount'];
	 $index=$giftcardData['cardIndex'];
	 $registryID=$giftcardData['registryID'];
	 $tempCardCode=$giftcardData['cardcode'];
	 
	 if(strpos($id, "@")==0){
		 //it's a registry ID, assign $id to $regID variable
		 $regID=$id;
	 }
	 else{	 
		 //it's a gift card basic purchase, assign $id to $recipientemail
		 //10/16/15 - this will likely be deprecated because AYLISS wants to mail the gift certificates
		 $recipientemail=$id;
	 }
	 
	 $giftcard->recipientEmail=$email;
	 
	 $giftcard->initialBalance=$amount;
	 
	 $giftcard->saleID=$saleID;
	 
	 $giftcard->registryID=$registryID;
	
     //gift card returns a code of the  form [newCode]:[tempCode]
     //this is done to update the item cookie data 
	 $codePair=$giftcard->create($tempCardCode);
	 
	}
	//return the code pair for updating item cookies
	
	return $codePair;
	
}

function updateGiftCardBalance($gc,$ga,$invoice){

	$giftcard=new GiftCard();
	
	$giftcard->cardCode=$gc;

	$giftcard->redeemedAmount=$ga;

	$giftcard->update($invoice);
	
}

function updateGiftCardCodesWithInvoiceNumber($card,$invoiceID){
	global $db;
	foreach($card as $v){
		$stmt="UPDATE tblGiftCards SET saleID=:saleID WHERE cardCode=:code LIMIT 1";
		$query=$db->prepare($stmt);
		$query->bindParam(":saleID",$invoiceID,PDO::PARAM_INT);
		$query->bindParam(":code",$v,PDO::PARAM_STR);
		$query->execute();
	}
}

function updateInventory(){

global $testing;
global $db;
global $PayPalMode;

$tbl="inventory";

if( $PayPalMode != "live" ){
	$tbl="inventorySandbox";
}

//$tbl=($environment=="sandbox" || $testing==1)?"inventorySandbox":"inventory";

//comment following line out when going live
//$tbl="inventory";
$item=explode('&',substr($_COOKIE['items'],1));
	
	foreach($item as $k=>$v){ 
	//only look for items that aren't gift cards
	//gift cards handled by generateGiftCards function
	
	if(strpos($v, "gc") === FALSE){
	
		$i=explode(':',$v);	
		
		$update = "UPDATE ".$tbl. " SET quantity=(IF (quantity>-1,quantity-:qty,quantity)) where id=:id";
		
		$query 	= $db->prepare($update);
		
		$query->bindParam(":qty",$i[1],PDO::PARAM_INT);
		
		$query->bindParam(":id",$i[0],PDO::PARAM_INT);
		
		$query->execute();
		
		
		//additional update for 4-PC cleaning sets
		if( $i[0] == "49634" ){
		
			$stmt = "UPDATE ".$tbl. " SET quantity=(IF (quantity>-1,quantity-:qty,quantity)) WHERE productId = 74553 OR productId = 75587 OR productId = 83067 OR productId = 78974";
			
			$query = $db->prepare($stmt);
			
			$query->bindParam(":qty",$i[1],PDO::PARAM_INT);
			
			$query->execute();
		
		}
				
		 //$i[1]."
		 //$i[0]";	
		
		//echo $update;	
		
		
		//$q=mysql_query($update);
		
		//wedding registry purchases only
		if( $i[2] > 0){
			
			$update = "UPDATE weddingRegistryItems SET qtyPurchased = qtyPurchased + :qty WHERE qtyRequested>qtyPurchased AND regID = :regID and itemID = :itemID"; 
			
			$query=$db->prepare($update);
			
			$query->bindParam(":itemID", $i[0],PDO::PARAM_INT);
			$query->bindParam(":qty", $i[1],PDO::PARAM_INT);
			$query->bindParam(":regID", $i[2],PDO::PARAM_INT);
			
			$query->execute();
			
			//$result=mysql_query($query);
			if($query->rowCount()>0){	
			
			}	
			
			}	
		}
	 }
	
}

?>