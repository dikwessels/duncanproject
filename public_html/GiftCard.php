<?php
include_once("connect/mysql_pdo_connect.php");
ini_set("display_errors",1);

class GiftCard
{
 
 public $cardCode = "";
 public $recipientEmail="";
 public $initialBalance=0;
 public $currentBalance=0;
 public $msg="";
 public $redeemedAmount=0;
 public $saleID="";
 public $registryID=0;
 public $mailTo="";
 
 private function getRegistryContactInfo(){
	 //assigns registry email and shipping address 
	 
	 global $db;
	 
	 $stmt="SELECT * FROM weddingRegistries WHERE id=".$this->registryID. " LIMIT 1";
	 
	 $query=$db->prepare($stmt);
	 $query->execute();
	 
	 $result=$query->fetchAll();
	 
	 $row=$result[0];
	 $this->recipientEmail=$row['remail'];
	 $this->mailTo=$row['rfname']." ".$row['rlname'].", ".$row['raddress'].", ".$row['rcity'].", ".$row['rstate']." ".$row['rzipcode'];
	 
 }
 
 public function create($cardCode=""){
	global $db;
	//store sent code for later reference
	$sentCode=$cardCode;
	//echo "code sent: $sentCode<br>";
	
	$newCode="";
	
	//$checkCode=$referenceCode;
	
	//check existing code against table
	if($cardCode!=""){
		$newCode=$this->generateCode($cardCode);
	}
	
	if($newCode==""){
		//the cardCode was already used and needs to be replaced with a newly generated code
		while($newCode==""){
			$newCode=$this->generateCode();
		}
	}
	
	$this->cardCode=$newCode;
	
	if($this->registryID && $this->recipientEmail==""){
		$this->getRegistryContactInfo();
	}
	
	
	$stmt="INSERT INTO tblGiftCards(cardCode,cardAmt,saleID,customerEmail,remainingAmt,registryID,mailTo) ";
	$stmt.="VALUES(:code,:initialBalance,:saleID,:email,:currentBalance,:registryID,:mailTo)";
		
	$query=$db->prepare($stmt);
	
	$query->bindParam(":code",$this->cardCode,PDO::PARAM_STR);
	
	$query->bindParam(":initialBalance",$this->initialBalance);
	
	$query->bindParam(":saleID",$this->saleID,PDO::PARAM_INT);
	
	$query->bindParam(":email",$this->recipientEmail,PDO::PARAM_STR);
	
	$query->bindParam(":currentBalance",$this->initialBalance);
	
	$query->bindParam(":registryID",$this->registryID,PDO::PARAM_INT);
	
	$query->bindParam(":mailTo",$this->mailTo,PDO::PARAM_STR);
	
	
	$query->execute();
	
	//$result=mysql_query($query);
	
	$response=$query->rowCount();
	
	if($response>0) {
		if($this->recipientEmail!=""){
			//$this->emailGiftCardInformation();
		}
		//send response as newCode:sentCode 
		//sentCode will then be replaced with newCode
		$response=$this->cardCode.":".$sentCode;
	}
	
	else{
	  $msg="An error occured trying to create a gift card. Gift Card \n\r Code:".$this->cardCode."\n\r Initial Balance:".$this->initialBalance."\n\r Invoice: ".$this->saleID."\n\r RecipientEmail: ".$this->recipientEmail."','".$this->initialBalance."\n\r Registry ID: ".$this->registryID."\n\r Error Information: ".$db->errorInfo();
		
		mail("wagnermichaeljames@gmail.com","Gift Card Creation Error",$msg);
	}
	
	return $response;

}

 private function emailGiftCardInformation(){

$message="<html><body style='font-family:arial, helvetica, verdana;color:#555;font-size:1rem;'>
<h2 style='font-weight:normal;color:#999'>You've Received a Gift Card!</h2>
<span style='color:#333;'>Hello, you have received a $".$this->initialBalance." As You Like It Silver Shop Gift Card from a friend.</span><br>";

$message.="<span style='color:#333;'>This gift card can be used for any purchase at our website: <a style='color:#79121B;' href='http://www.asyoulikeitsilvershop.com'>www.asyoulikeitsilvershop.com</a> Simply enter the code <strong>".$this->cardCode."</strong> during checkout and your gift card will be applied to your purchase. Gift cards do not expire and can be used for multiple purchases at our website (please see Terms and Conditions below).As You Like It Silver shop has a large selection of new and antique silver flatware, hollowware, jewelry, baby silver and coin silver, as well as a full line of silver cleaning products and repair services.</span>
<br>
<br>
To check the available balance of your gift card, go to <a style='color:$79121B' href='http://www.asyoulikeitsilvershop.com/gift-cards/'>www.asyoulikeitsilvershop.com/gift-cards/</a>
<br>
<br>
Thanks for shopping with As You Like It Silver Shop, and have a great day!
<br>
<br>
Sincerely, 
As You Like It Silver Shop Sales Department<br>
sales@asyoulikeitsilvershop.com<br>
As You Like It Silver Shop<br>
1-800-828-2311<br>
<a href='http://www.asyoulikeitsilvershop.com'>www.asyoulikeitsilvershop.com</a><br>
<footer>
<table style='border:1px solid #333;border-radius:2px;width:100%' cellpadding='2'>
	<tbody style='font-size:.7rem;color:black'>
	 <tr style='border-bottom:1px solid #black'>
	 <td>
	 <strong>Terms and Conditions:</strong>
	 </td>
	 </tr>
	 <tr>
	 <td>
	  Gift cards are valid for purchases only at As You Like It Silver Shop. Gift cards cannot be exchanged for cash value, resold or used to purchase other gift cards. Gift cards values cannot be modified or increased, and As You Like It Silver Shop reserves the right to revoke or suspend gift cards without notice.
	 </td>
	 </tr>
	</tbody>
</table>
</footer>
";

$message.="</body></html>";

//echo $message;
 
 	$headers.="From:sales@asyoulikeitsilvershop.com\r\n";
 	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
 
 	mail($this->recipientEmail, "You've Received A Gift Card From As You Like It Silver Shop", $message,$headers);	

}

 private function generateCode($code=""){
	
	global $db;
	//echo "code sent $code<br>";
	if($code==""){
		$code=$this->randomString(8);
	}
	
	$query=$db->prepare("SELECT cardCode FROM tblGiftCards WHERE cardCode=:code");
	$query->bindParam(":code",$code,PDO::PARAM_STR);
	$query->execute();
	$result=$query->fetchAll();	
	
	if(count($result)>0){
		$code="";
	}
	
 return $code;

}

 public function load(){
	
	global $db;
	
	$query=$db->prepare("SELECT * FROM tblGiftCards WHERE cardCode=:code LIMIT 1");
	$query->bindParam(":code",$this->cardCode,PDO::PARAM_STR);
	$query->execute();

	$result=$query->fetchAll();

	if(count($result)>0){
		//echo "result found";
		//print_r($result[0]);
		$row=$result[0];
		$this->recipientEmail=$row['customerEmail'];
		$this->initialBalance=$row['cardAmt'];	
		$this->currentBalance=$row['remainingAmt'];
		$this->saleID=$row['saleID'];
		$this->registryID=$row['registryID'];	
	}
	
}

 public function describe($c){
	
	$this->cardCode=$c;
	
	print_r(get_object_vars($this));
	
}

 private function randomString($length = 6) {
	
 $str = "";
 $characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
 $max = count($characters) - 1;
 for ($i = 0; $i < $length; $i++) {
  $rand = mt_rand(0, $max);
  $str .= $characters[$rand];
 }
 
 //$str=substr($str, 0,4)."-".substr($str, -4);
 
 return $str;
 
 
}

 public function retrieveBalance(){
	global $db;
	
	$query=$db->prepare("SELECT remainingAmt FROM tblGiftCards WHERE cardCode=:code LIMIT 1");
	$query->bindParam(":code",$this->cardCode,PDO::PARAM_STR);
	$query->execute();
	$result=$query->fetchAll();
	
	//$result=mysql_query($query);
	
	if(count($result)>0){
			$row=$result[0];
			extract($row);
			$this->msg= "Current available amount: $remainingAmt";
			$this->currentBalance=$remainingAmt;
		}
		else{
			$this->currentBalance=-1;
			$this->msg= "Sorry, the code you entered is not valid. Please check your code and try again.";
		}
	
}

 public function showData(){
	print_r(get_object_vars($this));

}	

 public function update($saleID){
	global $db;
	
	$query=$db->prepare("UPDATE tblGiftCards SET remainingAmt=remainingAmt-:amt WHERE cardCode=:code LIMIT 1");
	$query->bindParam(":amt",$this->redeemedAmount);
	$query->bindParam(":code",$this->cardCode);
	$query->execute();
	
	$rc=$query->rowCount();
	
	if($rc>0){
		//$this->retrieveBalance();
		//echo $this->msg;
	}
	else{
			
		$msg="Gift Card ".$this->cardCode." was unsuccessfully updated on Invoice# $saleID with the following amount: ".$this->redeemedAmount."\n\r"."PHP SQL Error Info follows: ".$db->errorInfo();
		
		mail("wagnermichaeljames@gmail.com","Gift Card Update Error",$msg);	
	}
}

}

?>