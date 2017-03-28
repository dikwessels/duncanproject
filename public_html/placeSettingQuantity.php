<?php
//echo "hello";
include_once("/connect/mysql_pdo_connect.php");
ini_set("display_errors",1);
//echo "hello";

getPlaceSettingQuantity("4 Piece Lunch/Place Setting", "BUTTERCUP","GORHAM");

function getPlaceSettingQuantity($placesetting, $pattern,$brand){

global $db;
	
	switch ($placesetting) {
		case "4 Piece Lunch/Place Setting":
		$stmt="SELECT quantity,item FROM inventory WHERE pattern=\"$pattern\" AND brand=\"$brand\" and (item='TEASPOON' OR item='LUNCH/PLACE FORK' OR item='LUNCH/PLACE KNIFE' OR item='SALAD FORK') and monogram=0";
		 break;
		case "4 Piece Dinner Setting":
		$stmt="SELECT quantity,item FROM inventory WHERE pattern=\"$pattern\" AND brand=\"$brand\" and (item='TEASPOON' OR item='DINNER FORK' OR item='DINNER KNIFE' OR item='SALAD FORK') and monogram=0";
		 break;
		case "4 Piece Place Size Setting":
		$stmt="SELECT quantity,item FROM inventory WHERE pattern=\"$pattern\" AND brand=\"$brand\" and (item='TEASPOON' OR item='PLACE FORK' OR item='PLACE KNIFE' OR item='SALAD FORK' OR item='SALAD FORK (PLACE SIZE)') and monogram=0";
		 break;
		 
	}
	
	$query=$db->query($stmt);
	$result=$query->fetchAll();
	
	if(count($result)<4){
		echo "There are 0 $placesetting in stock";
	}
	else{
		$maxqty='';
		$curqty=0;
		$saladforkQty=0;
		$saladforkPSQty=0;
		foreach($result as $row){

			extract($row);
			if($item=="SALAD FORK (PLACE SIZE)" || $item=="SALAD FORK"){
			 if($item=="SALAD FORK"){
				$saladforkQty=$quantity;	
				 
			 }
			 else{
				 $saladforkPSQty=$quantity;
			 }	
			 $curqty=$quantity;
			}
			else{
			$curqty=$quantity;
			
			 if(!$maxqty){
				 $maxqty=$curqty;
			 }
			 else{
				 
			 if($curqty<$maxqty){
				$maxqty=$curqty;
			 }
			 }
		    }
			echo "<strong>$item</strong><br>Current quantity: $curqty<br>Current min quantity: $maxqty<br>";
			
		  
		}
	
	 $limitqty=($saladforkQty>$saladforkPSQty)?$saladforkQty:$saladforkPSQty;
	 echo $limitqty;			
	 if($limitqty<$maxqty){$maxqty=$limitqty;}
		
	 echo "<br><br><strong>There are $maxqty $placesetting in stock.</strong>";
	}
	
  //return 1;
}

?>