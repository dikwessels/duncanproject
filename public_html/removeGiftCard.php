<?
	session_name('checkout');
	session_start();

	extract($_POST);
	//$newCodes=array();
	$t=0;
	
	foreach($_SESSION['redeemedGiftCards'] as $c=>$v){
		
		if( $c==$code ){
			unset($_SESSION['redeemedGiftCards'][$c]);
		}
	
	}
	
	/*if(array_key_exists($code, $_SESSION['redeemedGiftCards']){
		$amount=$_SESSION['redeemedGiftCards'][$code];

		$_SESSION['invoicetotal']+=$amount;
		unset($_SESSION['redeemedGiftCards'][$code]);
	}
	*/
	
	foreach($_SESSION['redeemedGiftCards'] as $k=>$v){
		 $t+=$v;
	}
	
	$_SESSION['totalRedeemedGiftCards']=$t;
	
?>