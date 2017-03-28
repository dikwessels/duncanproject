<?
include("/home/asyoulik/connect/mysql_pdo_connect.php");

extract($_GET);

	$stmt = "SELECT * FROM tblGiftCards WHERE cardCode='$gc'";

	$query = $db->prepare($stmt);
	
	$query->execute();
	
	$result = $query->fetchAll();

		if(count($result)){
			
			$row = $result[0];
			
			extract($row);
		
			$cardmsg = "Current available amount: $remainingAmt";
		
			$giftCardAmt = $remainingAmt;
		
		}
		else{
			
			$cardmsg = "Sorry, the code you entered is not valid. Please check your code and try again.";
		}

		echo $remainingAmt;

?>