<?php
 include("/home/asyoulik/connect/mysql_pdo_connect.php");
 
 extract($_GET);
 
 $query=$db->prepare("UPDATE inventory set image=:image, image2=:image2, image3=:image3 WHERE productId=:productId LIMIT 1");

 $image=str_replace("_TN", "", $image);
 $image2=str_replace("_TN","",$image2);
 $image3=str_replace("_TN","",$image3);
 
 $query->bindValue(':productId',$productId,PDO::PARAM_INT);
 $query->bindValue(':image',$image,PDO::PARAM_STR);
 $query->bindValue(':image2',$image2,PDO::PARAM_STR);
 $query->bindValue(':image3',$image3,PDO::PARAM_STR);
 
 $query->execute();
 
 echo $query->rowCount();
 
?>