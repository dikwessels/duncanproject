<?
extract($_GET);
include("/home/asyoulik/connect/mysql_connect.php");

if($action=="s"){
    //$webID=getWebID($v);
    $query="UPDATE weddingRegistryItems SET qtyPurchased=qtyPurchased+$qs WHERE id=$rowID";
    $result=mysql_query($query);
    //echo $query;
	// itemID=$webID AND regID=$regID LIMIT 1";
}

else{


foreach($itemID as $k=>$v){

    $query="SELECT id FROM weddingRegistryItems WHERE storeRowID=$storeRowID[$k]";


    $result=mysql_query($query);
     if($row=mysql_fetch_assoc($result)){
      updateItem($storeRowID[$k],$qtyR[$k]);
     }

     else{
      $webID=getWebID($v);
      $m=insertItem($webID,$regID[$k],$qtyR[$k],$storeRowID[$k]);
      echo "$m rows inserted";
     }
}

/*
foreach($itemID as $k=>$v){

	$webID=getWebID($v);
	
	$query="SELECT id FROM weddingRegistryItems WHERE storeRowID=$storeRowID[$k]";

        // itemID=$webID and regID=$regID[$k]";
	
        //echo "Select query: $query<br>";
	
	
	$result=mysql_query($query);
	if($row=mysql_fetch_assoc($result)){
	  $query="UPDATE weddingRegistryItems SET qtyRequested=$qtyR[$k] WHERE id=$row[id]"; //itemID=$v and regID=$regID[$k]";	
		echo "Update query: id=$row[id], k=$k, v=$v, $query<br>";
		
		$result=mysql_query($query);
	}

	else{
		//insert item
		$m=insertItem($webID,$regID[$k],$qtyR[$k]);

		echo "$m rows inserted";
	}

}
*/


}


function getWebID($storeID){

 	$query="SELECT id FROM inventory WHERE productID=$storeID";
 	$result=mysql_query($query);
 	$row=mysql_fetch_assoc($result);
 
  return $row[id]; 
}

function insertItem($itemID,$regID,$qty,$storeRowID){

	$query="INSERT INTO weddingRegistryItems(regID,storeRowID,itemID,qtyRequested,qtyPurchased) VALUES($regID,$storeRowID,$itemID,$qty,0)";
	echo "Insert query: $query <br>";
	
	$result=mysql_query($query);
	$success=mysql_affected_rows();

  return $success;
}


function updateItem($id,$qty){

    $query="UPDATE weddingRegistryItems SET qtyRequested=$qty WHERE storeRowID=$id";
    $result=mysql_query($query);

}
//6070803

?>

