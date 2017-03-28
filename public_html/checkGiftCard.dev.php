<?
//include("/connect/mysql_connect.php");
include("/connect/mysql_pdo_connect.php");


	extract($_GET);

	$stmt = "SELECT * FROM tblGiftCards WHERE cardCode= :code"; //'$gc'";

	$query = $db->prepare($stmt);
	
	$query->bindParam(":code",$gc,PDO::PARAM_STR);
	
	$result = $query->execute();
	
	//$result = mysql_query($query);

		if( count($result) > 0){
			
			$row = $result[0];
			
			extract($row);
		
			$cardmsg = "Current available amount: $remainingAmt";
		
			$giftCardAmt = $remainingAmt;
		}
		
		else{
		
			$cardmsg= "Sorry, the code you entered is not valid. Please check your code and try again.";
		
		}

		echo $remainingAmt;

?>