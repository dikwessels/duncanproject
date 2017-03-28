<?php
ini_set("display errors","1");
include_once("/connect/mysql_connect.php");


/*if(isset($_COOKIE['items'])){
//echo "cookie set";
	$items=$_COOKIE['items'];

	$itemlist=explode('&', $items);
	
	foreach($itemlist as $k=>$v){
	echo $v."<br>";
	 $ok="";
	 $gcCode="";
		if(strpos($v, 'gc')===0){
			//gift card, extract information and send email
			$giftCard=explode(":",$v);
			$gcId=$giftCard[0];
			$gcAmount=$giftCard[1];
			$gcEmail=$giftCard[2];
		   
			while(!$gcCode){
				$gcCode=createCode();
			}
			
			echo $gcCode."<br>";
			
			while(!$ok){
				$ok=createGiftCardEntry($gcCode,$gcAmount,$gcEmail);
			}
			
			emailGiftCardInformation($gcCode,$gcAmount,$gcEmail);
			echo "gift card email sent to $gcEmail";
		}	
	}
	
}
*/
function createCode(){
	$code=randomString(8);
	$query="SELECT cardCode FROM tblGiftCards WHERE cardCode='$code'";
	$result=mysql_query($query);
	if(mysql_num_rows($result)>0){
		$code="";
	}
 return $code;
}

function createGiftCardEntry($gcCode,$gcAmount,$gcEmail){
	$query="INSERT INTO tblGiftCards(cardCode,cardAmt,customerID,customerEmail,remainingAmt)";
	$query.=" VALUES('$gcCode','$gcAmount','','$gcEmail','$gcAmount')";	
	$result=mysql_query($query);
	if(mysql_error()){
		echo mysql_error()."<br>";
	}
	return mysql_affected_rows();
}


function emailGiftCardInformation($gcCode,$gcAmount,$gcEmail){

$message="<html><body style=\"font-family:arial, helvetica, verdana;color:#555;font-size:1rem;\">
<h1 style=\"font-weight:normal;color:#999\">You've Received a Gift Card!</h1>
<span style=\"color:#333;\">Hello, you have received a $$gcAmount As You Like It Silver Shop Gift Card from a friend.</span><br>";
$message.="<span style=\"color:#333;\">This gift card can be used for any purchase at our website: <a style=\"color:#79121B;\" href=\"http://www.asyoulikeitsilvershop.com\">www.asyoulikeitsilvershop.com</a> Simply enter the code <strong>$gcCode</strong> during checkout and your gift card will be applied to your purchase. Gift cards do not expire and can be used for multiple purchases at our website. As You Like It Silver shop has a large selection of new and antique silver flatware, hollowware, jewelry, baby silver and coin silver, as well as a full line of silver cleaning products and repair services.</span>
<br>
<br>
To check the available balance of your gift card, go to <a style=\"color:$79121B\" href=\"http://www.asyoulikeitsilvershop.com/gift-cards/\">www.asyoulikeitsilvershop.com/gift-cards/</a>
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
<a href=\"http://www.asyoulikeitsilvershop.com\">www.asyoulikeitsilvershop.com</a><br>

<footer>
<table style=\"border:1px solid #333;border-radius:2px;width:100%\" cellpadding=\"2\">
	<tbody style=\"font-size:.7rem;color:black\">
	 <tr style=\"border-bottom:1px solid #black\">
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

    echo $message;
 
 	$headers.="From:sales@asyoulikeitsilvershop.com\r\n";
 	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
 
	mail($gcEmail, "You've Received A Gift Card From As You Like It Silver Shop", $message,$headers);	

}

function randomString($length = 6) {
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

?>