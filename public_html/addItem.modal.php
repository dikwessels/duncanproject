<?php
	
 include("../connect/mysql_pdo_connect.php");
 include("staticHTMLFunctions.php")
 include("categoryArrays.php")
  
 ini_set("display_errors",1);


extract($_POST);
//include("/connect/mysql_connect.php");
//general item cookie is set up as itemID:quantity:registryID
//for generated gift cards it is a dummyID:amount:email
//for preset gift cards it will be itemID:quantity:registryID

function isGiftCard($id){
	global $db;
	$query=$db->prepare("SELECT category FROM inventory WHERE id=:id");
	$query->bindParam(":id",$id);
	$query->execute();
	$result=$query->fetchAll();
	$row=$result[0];
	if($row['category']=="gc"){return 1;}
	return 0;
}


function randomString($length = 6) {
	
 $str = "";
 $characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
 $max = count($characters) - 1;
 for ($i = 0; $i < $length; $i++) {
  $rand = mt_rand(0, $max);
  $str .= $characters[$rand];
 }
 
 return $str;
 
}

//$isGiftCard=isGiftCard($id);
//test this on firefox
if(substr($id,0,2)=="gc"||$isGiftCard){

$tempCardCode=randomString(8);
 //it's a gift card 
 if($isGiftCard==1){
	 //this will be called from wedding registry
	 //upon checkout check the email field to see if it's a registry ID, if so then retrieve the shipping address
	 $items=$_COOKIE['items']."&gc$id:$amount:$regID||$tempCardCode";
 }
 else{
 //this is used for the gift card page
 	$items=$_COOKIE['items']."&$id:$amount:$email||$tempCardCode";
 	
 	//$message.="1 $amount Gift Card has been placed in your silver chest<br>";
 }

 $message.="1 $$amount Gift Card has been placed in your silver chest<br>";

}

else{
	//standard inventory item
    $query=$db->prepare("SELECT item,quantity as q,pattern,brand,category from inventory where id=:id");//$id");
    $query->bindParam(":id",$id);
    $query->execute();
    $result=$query->fetchAll();
    //$r=mysql_fetch_assoc($q);
    $row=$result[0];
    extract($row);
 
    if(!$regID){$regID=0;}
 
	if(0){
	//do place setting check to make sure there are still enough settings in stock
	list($type,$blank)=split(" ",$item);
	
	$sf=($type=='Place')?'Place':'Salad';
	
	$statement="SELECT a.quantity as q FROM inventory AS a WHERE a.pattern=:pattern and a.brand=:brand and(a.item='$type Knife' or a.item='$type Fork' or a.item='$sf FORK' or a.item='TEASPOON') and a.monogram!=-1 order by a.quantity DESC";
		
		$sq=$db->prepare($statement); 
		$sq->bindParam(":pattern",$pattern);
		$sq->bindParam(":brand",$brand);
		
		$sq->execute();//mysql_query($statement);
		$sr=$sq->fetchAll();
		//mysql_fetch_assoc($sq);
		 if(count($sr)<4 || $sr[q]<$quantity) {  
			    $quantity=$sr[q]; 
			    $message=($sr[q])?"<b>Sorry, we only have $sr[q] $item ($pattern by $brand) in stock.</b><br>":"Sorry, we no longer have $item ($pattern by $brand) in stock.";
		}
	
	}
		
	else{		
		$q=abs($q); 
		if ($quantity>$q) { 
			$quantity=$q; 
			$message=($q)?"<b>Sorry, we only have $q $item ($pattern by $brand) in stock.</b><br>":"Sorry, we no longer have $item ($pattern by $brand) in stock."; 
		}
	}
	
	preg_match("/$id:([0-9]+):$regID/",$_COOKIE['items'],$m);
	
	if ($quantity==0) {  	
			$items=	str_replace("$id:$m[1]:$regID","",$_COOKIE['items']); 
	}
	
	else {
	
		$patternname=$pattern?(ucwords(strtolower("$pattern by $brand"))):trim(ucwords(strtolower($brand)));
		
		$has=($quantity>1)?'have':'has';
		$itemname=ucwords(strtolower($item));
		$message.="$quantity $patternname $itemname $has been placed in your silver chest<br>";
		
		if ($m[1]) {
			$items=	str_replace("$id:$m[1]:$regID","$id:$quantity:$regID",$_COOKIE['items']);
		}
		     else {$items=$_COOKIE['items']."&$id:$quantity:$regID";}
	}
}		

setcookie("items",$items,0,'/');

echo $message;

?>