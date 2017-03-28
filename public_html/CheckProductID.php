<?php
    $db = mysql_connect("localhost", "asyoulik_admin",'f[()6COPT!Wo') or die ('I cannot connect to the database because: ' . mysql_error());
    if (!$db) {
      echo("Connection to database failed.  Please, try again later.");
    }

    if (!@mysql_select_db("ayliss")) {
      echo("Database connection failed.  Please, try again later.");
    }
    
 if($PID){
  $result=mysql_query("SELECT productId,item,quantity,category FROM inventory WHERE productId=".$PID);
  $record=mysql_fetch_array($result);
  $number=mysql_numrows($result);
  if($number>0){
   $productId= $record['productId'];
   $item=$record['item'];
   $quantity=$record['quantity'];
   $category=$record['category'];
 
   if($showQuantity!=""){
    print $productId.":".$quantity.":".$category;   
   }
   else
   {
   print $productId;
   }
   }
    else {
   print "Item Not Found"; 
   }
 }   
 mysql_close();
?>
