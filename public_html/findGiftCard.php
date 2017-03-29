<?php


include("connect/mysql_pdo_connect.php");

extract($_POST);
extract($_GET);

if($giftCardCode){
	
		//echo "Gift card code submitted";
		$sql = "SELECT * FROM tblGiftCards WHERE cardCode=\"".$giftCardCode."\"";
		
		$query = $db->prepare($sql);
		
		$query->execute();
		
		$result = $query->fetchAll(); 

		if( count($result) > 0 ){

			
			$row = $result[0];
			
			extract($row);
			
			$cardmsg = "Current available amount: $remainingAmt";
			$giftCardAmt = $remainingAmt.":".$giftCardCode;
		}
		else{
			
			$giftCardAmt= "invalid";		
		
		}	
	
		
echo $giftCardAmt;

}

?>