<?php

ini_set("display_errors",1);
//echo "hello";
	
include("/home/asyoulik/connect/mysql_pdo_connect.php");

extract($_POST);
extract($_GET);
//$giftCards=[];

function makeItemDescription($r){
	
	$by=$r['pattern']<>""?" by ":"";
	$pb=ucwords(strtolower($r['pattern'])).$by.ucwords(strtolower($r['brand']));
	$pb=str_replace("Unknown", "", $pb);
	$mono = $r['monogram']?" - Monogrammed":"";
	
	$item=ucwords(strtolower($r['item']));
	$d = trim($pb." ".$item.$mono);
	return $d;
}

function getQuantity($pattern,$brand,$item,$quantity){
	
if($item=="4 Piece Lunch/Place Setting"||$item=="4 Piece Lunch Setting" || $item=="4 Piece Dinner Setting"){
 
$query = "SELECT min(quantity) as minQuantity FROM inventory WHERE pattern=\"$pattern\" AND brand=\"$brand\" and (monogram=0 or monogram=\"\") and (item='teaspoon' OR item='salad fork'";
  
switch($item){
  case "4 Piece Lunch/Place Setting":
    $query.=" OR item='Lunch/Place Fork' OR item='Lunch/Place Knife')";
    break;
  case "4 Piece Place Size Setting":
   $query.=" OR item='Place Fork' OR item='Place Knife')";
    break;
  case "4 Piece Dinner Setting":
   $query.=" OR item='Dinner Fork' OR item='Dinner Knife')";
   break;
}

$result=mysql_query($query);
$row=mysql_fetch_assoc($result);
//echo $query;
extract($row);
$q=$minQuantity;

}

else{
 $q = $quantity;
}

return $q;

}


$eventArray=array(
					"a"		=>	"Anniversary",
					"bbs"	=>	"Baby Shower",
					"bd"	=>	"Birthday",
					"brs"	=>	"Bridal Shower",
					"o"		=>	"Other",
					"w"		=>	"Wedding"
				);


if(!$regID && ($fname || $lname || $sYear || $sMonth)){


	$w="WHERE rfname !=''";

	if($fname && $fname!=""){
		$w.=" AND (rfname LIKE :fname OR instr(rfname,:fname2)>0 OR crfname LIKE :fname3 OR instr(crfname,:fname4)>0)";
	}

	if($lname && $lname!=""){
		$w.=" AND (rlname LIKE :lname OR instr(rlname,:lname2)>0 OR crlname LIKE :lname3 OR instr(crlname,:lname4)>0)"; 
	}

	if($sMonth){
		$w.=" AND wedmonth=:smonth";
	}

	if($sYear){
		$w.=" AND wedyear=:syear";
	}


$rs1=1;
$rs2=2;
$rs3=3;
$rs4=4;
$rs5=5;

$rsort=$sort*-1;

switch($sort){
case "":
  	$s = "wedyear DESC,wedmonth DESC,wedday DESC,rlname ,rfname ,crlname,crfname";
  	break;

case -5:
	$s = "event DESC,rlname,rfname";
	break;
  
case -4:
	$s="rstate DESC, rlname,rfname";
	$rs4=$rsort;
	break;

case -3:
	$s= "wedyear DESC,wedmonth DESC,wedday DESC,rlname,rfname";
	$rs3=$rsort;
	break;

case -2:
	$s= "crlname DESC,crfname DESC,wedyear,wedmonth,wedday";
	$rs2=$rsort;
	break;
case -1:
	$s = "rlname DESC,rfname DESC,crlname,crfname";
	$rs1=$rsort;
	break;

case 1:
   	$s = "rlname ,rfname ,crlname,crfname";
	$rs1=$rsort;
	break;

case 2:
	$s= "crlname,crfname,wedyear,wedmonth,wedday";
	//$rs2=$rsort;
	break;

case 3:
	$s= "wedyear,wedmonth,wedday,rlname,rfname";
	//$rs3=$rsort;
	break;
case 4:
	$s="rstate, rlname,rfname";
	//$rs4=$rsort;
	break;
case 5:
	$s="event,rlname,rfname";
	//$rs5=$rsort;
	break;

}


$stmt = "SELECT * FROM weddingRegistries $w ORDER BY $s";

//echo "<script type='text/javascript'>console.log('$stmt');</script>";

$query = $db->prepare($stmt);

		if( $fname != ""){
			
			$query->bindParam(':fname',$fname,PDO::PARAM_STR);
			$query->bindParam(':fname2',$fname,PDO::PARAM_STR);
			$query->bindParam(':fname3',$fname,PDO::PARAM_STR);
			$query->bindParam(':fname4',$fname,PDO::PARAM_STR);
		
		}
		
		if( $lname != "" ){
			
		
			$query->bindParam(':lname',$lname,PDO::PARAM_STR);
			$query->bindParam(':lname2',$lname,PDO::PARAM_STR);
			$query->bindParam(':lname3',$lname,PDO::PARAM_STR);
			$query->bindParam(':lname4',$lname,PDO::PARAM_STR);
		}
		
		
		if($sMonth!="" && $sMonth>0){
		
			$query->bindParam(':smonth',$sMonth,PDO::PARAM_INT);
		
		}
		if($sYear!="" && $sYear > 0){
		
			$query->bindParam(':syear',$sYear,PDO::PARAM_INT);
		
		}




$query->execute();

$result = $query->fetchAll();

   if(count($result)>0){

		foreach($result as $row){

			$row['eventDate']=$row['wedmonth']."/".$row['wedday']."/".$row['wedyear'];
			$row['eventDescription']=$eventArray[$row['event']];
			
			$jsonArray[]=$row;
		}		 
	
	}
	
	else{
	 	$jsonArray[]=array("noresults"=>"1"); //$c.="<tr><td>No Results found</td></tr>";
	}
	
	$results['registryListings']=$jsonArray;
	
	echo json_encode($results);
}
else{
//display individual registry items

if($regID){
    
	$query = $db->prepare("SELECT id,category,pattern,brand,1 as quantity,monogram,image,dimension,item,retail,sale,0 as regItemID,0 as qtyRequested,0 as qtyPurchased,0 as primarySort,item as itemDescription,'' as showlink,'' as complete,:regID as regID,1 as instock, 1 as gc FROM inventory WHERE category='gc' ORDER BY listOrder ASC");
 
 $query->bindParam(":regID",$regID);
 
 $query->execute();
 
 $giftCardList = $query->fetchAll();
 
 //echo json_encode($result1);

$stmt = "

SELECT inventory.id,
 inventory.category,
 inventory.pattern,
 inventory.brand,
 inventory.quantity,
 inventory.monogram,
 inventory.image,
 inventory.dimension,
 inventory.item,
 inventory.retail,
 inventory.sale,
 weddingRegistryItems.id as regItemID, 
 weddingRegistryItems.qtyRequested,
 weddingRegistryItems.qtyPurchased,
 if(abs(quantity)>0,if(weddingRegistryItems.qtyPurchased<weddingRegistryItems.qtyRequested,1,2),
 if(qtyPurchased<qtyRequested,3,1)) as primarySort,
 1 as showLink,
 '' as complete, 
 $regID as regID,
 inventory.quantity as instock,0 as gc 
 
 FROM inventory, weddingRegistryItems 
 
 WHERE weddingRegistryItems.itemID=inventory.id and weddingRegistryItems.regID=:regID 
 
 ORDER BY primarySort ASC, inventory.quantity DESC, inventory.pattern,inventory.brand,inventory.item";

 $query=$db->prepare($stmt);
 		
 $query->bindParam(':regID',$regID);
 $query->execute();
 
 $wedRegtItems=$query->fetchAll();
 
 foreach($wedRegtItems as $row){
	 
		$row['itemDescription']=makeItemDescription($row);	
		$row['retail']="$".number_format($row['retail']);
		if(!$row['image'] || !file_exists("productImages/_BG/$row[image]")) { 
			$handle="/HANDLES/".strtoupper(substr($row[pattern],0,1))."/".strtoupper($row[pattern])." ". str_replace("&", "AND",strtoupper($row[brand])).".jpg";
			$testhandle="/home/asyoulik/public_html".$handle;
			$row['image']=(file_exists($testhandle))? $handle:'/productImages/_TN/noimage_th.jpg'; 
			
		}  
		
		else{
			$row['image']='/productImages/_TN/'.substr($row[image],0,-4)."_TN.jpg"; 
		} 
					
		if($row['quantity']!=0){
			 $row['instock']=1;
		     if($row['qtyPurchased']<$row['qtyRequested']){
			     $row['complete']="";    
              }   
          }
		  else{
            $row['instock']="";
		 }

		$registryItems[]=$row;
 }
 
 if( count($registryItems)>0 ) { 
	$results=array_merge($giftCardList,$registryItems);
 
 }
 else{
	 $results=$giftCardList;
 }
 
 $response['registryItems']=$results;
 
 $query=$db->prepare("SELECT * FROM weddingRegistries WHERE id=:id");
 
 $query->bindParam(':id',$regID);
 
 $query->execute();
	
	$result = $query->fetchAll();
	
	foreach($result as $row){
		if(strpos($row['rlname'], "s")==strlen($row['rlname'])){
			$apostrophe="'";
		}
		else{
			$apostrophe="'s";
		}
		$response['registryData']['name']=$row['rfname'] ." ". $row['rlname'].$apostrophe;
   	}

    echo json_encode($response);
    
   }
	
	else{
		echo "";
	}
}







?>
