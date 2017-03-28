<?php 

include_once('/home/asyoulik/connect/mysql_pdo_connect.php');

extract($_GET);

if($productId){
    $stmt = "SELECT id FROM inventory WHERE productId=$productId";
    $query = $db->prepare($stmt);
    
    $query->execute();
    $result = $query->fetchAll();
    
    //$result=mysql_query($query);
    $num=count($result);

    if($num>0){
        
        $record=$result[0];
        extract($record);
        echo $id;
    }
    else{echo "No record";}
}


?>