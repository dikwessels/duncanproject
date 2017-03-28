<?php
	ini_set("display_errors",1);

	//include('/connect/mysql_connect.php');
	include('GiftCard.php');
	//echo "hello";

	
	
	$giftcard=new GiftCard();

	extract($_GET);	
	//print_r($_GET);

	switch($action){
	case "create":
		$giftcard->recipientEmail="wagnermichaeljames@gmail.com";
		$giftcard->initialBalance=$amount;
		$giftcard->registryID=$regID;
		$success=$giftcard->create($code);	
		echo $success;
		/*print_r(get_object_vars($giftcard));
		
		if($success!=0){
			$giftcard->cardCode=$success;
			$giftcard->load();
			$giftcard->showData();
		}
		*/
		break;
	
	case "redeem":
		$giftcard->cardCode=$code;
		$giftcard->redeemedAmount=$amount;
		$giftcard->update();
		$giftcard->load();
		$giftcard->showData();
		break;
	
	case "getInfo":
		$giftcard->cardCode=$code;
		$giftcard->load();
		$giftcard->showData();
		break;
		
	default:
	
	if($code){
		$giftcard->cardCode=$code;
		
		//echo $giftcard->cardCode;
		$giftcard->retrieveBalance();
		echo $giftcard->msg;
	}
	break;

	}


	
	

?>