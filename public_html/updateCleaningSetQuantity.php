<?

 include_once("cleaningSetQuantity.php");
 
 $query = $db->prepare("UPDATE inventory SET quantity = :qty WHERE productId = 87571 LIMIT 1");
 
 $query->bindParam(":qty",$SetQuantity);
 
 $query->execute();
 
 if( $query->rowCount() > 0 ){
	 echo "success";
 }
 else {
	 echo $query->errorInfo();
 }
	
?>