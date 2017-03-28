<?php
include("/connect/mysql_connect.php");

extract($_GET);

if($action=="delete"){
    $query="DELETE FROM weddingRegistryItems WHERE id = $wrID LIMIT 1";
}
else{
    if($qtyR<1){
    $query="DELETE FROM weddingRegistryItems WHERE id = $wrID LIMIT 1";
    }
    else{
        
        $subQuery="SELECT qtyRequested FROM weddingRegistryItems WHERE id=$wrID LIMIT 1";
        $result=mysql_query($subQuery);
        $row=mysql_fetch_assoc($result);
        extract($row);
        
        if($qtyR!=$qtyRequested){
            $query="UPDATE weddingRegistryItems SET qtyRequested=$qtyR WHERE id=$wrID LIMIT 1";	
        }
    
    }
}

if($query!=""){
    $result=mysql_query($query);
    $affrows=mysql_affected_rows();
    $msg=($affrows>0)?"Your registry has been updated.":"An unexpected database error occurred. Please try again.";
}

echo $msg;


?>