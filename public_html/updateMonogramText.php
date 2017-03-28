<?php

include("/home/asyoulik/connect/mysql_connect.php");

extract($_GET);

$query="SELECT monogramText FROM inventory WHERE productId=$productId";
$result=mysql_query($query);

$row=mysql_fetch_assoc($result);
extract($row);

 if($monogramText!=""){
  $arrMonoText=explode($monogramText,";");
  $count=0;
 
   foreach($arrMonoText as $k=>$v){if($t==$v){$count++;}}
  
   if($count==0){
     appendMonogramText($productId,$t);
   }
   else{
    updateMonogramText();
   }
  }
  else{
   appendMonogramText($productId,$t);
  }

function appendMonogramText($productId,$t){
 $query="UPDATE inventory set monogramText=CONCAT(monogramText,'$t;') WHERE productId=$productId";

 echo $query;

 $result=mysql_query($query);

 $success=mysql_affected_rows();

 if($success>0){
  echo "Item $productId updated with '$t'";
 }
 else{
   echo "Item $productId not updated";
 }

}

?>