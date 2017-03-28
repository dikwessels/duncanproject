<?php
/***** 

Written 10-7-2014 by Michael Wagner
Consolidates functions between express-checkout.php and order-confirmation.php
Those scripts could likely be consolidated into one script.


//10/7/15 TO DO: correct bug with gift card generation for gift cards that do not have a recipient email address


*****/

function clearSession(){
	session_unset();
	setcookie("custNum","",time()-30);
	setcookie("shipping","",time()-30);
	setcookie("items","",time()-30);
}

function createPaymentResponseFromSession(){

$arr=array(
	'BILLTONAME'=>$_SESSION['cardfname'].' '.$_SESSION['cardlname'],
	'BILLTOSTREET'=>$_SESSION['cardaddress'],
	'BILLTOCITY'=>$_SESSION['cardcity'],
	'BILLTOSTATE'=>$_SESSION['cardstate'],
	'BILLTOZIP'=>$_SESSION['cardzip'],
	'BILLTOCOUNTRY'=>$_SESSION['cardcountry'],
	'CARDTYPE'=>1,
	'EMAIL'=>$_SESSION['cardemail'],	
	'FIRSTNAME'=>$_SESSION['fname'],
	'LASTNAME'=>$_SESSION['lname'],
	'SHIPTONAME'=>$_SESSION['fname'].' '.$_SESSION['lname'],
	'SHIPTOSTREET'=>$_SESSION['address1'].' '.$_SESSION['address2'],
	'SHIPTOCITY'=>$_SESSION['city'],
	'SHIPTOSTATE'=>$_SESSION['state'],
	'SHIPTOCOUNTRY'=>$_SESSION['country'],
	'SHIPTOZIP'=>$_SESSION['zip'],
	'PPREF'=>'2XV449181A229492A',
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

function generateGiftCards($giftcardArray,$saleID,$regID){
	
	foreach($giftcardArray as $recipientemail=>$amount){
	 $giftcard=new GiftCard();
	 $giftcard->recipientEmail=$recipientemail;
	 $giftcard->initialBalance=$amount;
	 $giftcard->saleID=$custNum;
	 $giftcard->registryID=$regID;
	 $giftcard->create();	
	}
	
}

function updateGiftCardBalance($gc,$ga,$invoice){
	$query="UPDATE tblGiftCards Set remainingAmt=remainingAmt-".$ga." WHERE cardCode='$gc' LIMIT 1";
	//echo $query;
	
	$result=mysql_query($query);
	if(mysql_affected_rows()==0){

	//send out email to webmaster regarding error
		$headers.= "MIME-Version: 1.0\r\n";
		$headers.= "Content-Type: text/html; charset=ISO-8859-1\r\n";
		$headers.="From:error_checker@asyoulikeitsilvershop.com";
		$subject="An error occurred updating a gift card transaction on Invoice $invoice";
		$msg="
			<h3>There was an error processed the gift card information for an order, here's the pertinent info:</h3>
			<p>Card Code: $gc</p>
			<p>Amount redeemed:</p>
			<p>Invoice: $invoice</p>";
			
		mail("wagner_michaeljames@yahoo.com",$subject,$msg,$headers);

	}
}

function updateInventory(){
//need to update this to PDO calls
global $testing;
global $PayPalMode;

	$tbl = "inventory";

	if( $PayPalMode != "live" ){
		$tbl = "inventorySandbox";
	}

	$item=explode('&',substr($_COOKIE['items'],1));
	
	foreach($item as $k=>$v){ 
	//only look for items that aren't gift cards
	//gift cards handled by generateGiftCards function
	
		if(strpos($v, "gc")===FALSE){
			$i=explode(':',$v);	
			$update="UPDATE ".$tbl. " set quantity=(IF (quantity>-1,quantity-".$i[1].",quantity)) where id=$i[0]";	
		
			//echo $update;	
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