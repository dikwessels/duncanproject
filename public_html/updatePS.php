<?php

  include('/home/asyoulik/connect/mysql_connect.php');

  $query="SELECT * FROM inventory WHERE category=\"ps\" AND retail >0 ORDER BY pattern,brand,item";

  $result=mysql_query($query);

  while($row=mysql_fetch_assoc($result)){
    extract($row);
    $psf="";
    $type=str_replace("4 Piece","",$item);
    $type=str_replace("Setting","",$type);
    $type=trim($type);
    $type=strtoupper($type);
    $limit=" LIMIT 4";
    
   //$c= "<span style=\"font-size:10pt;font-weight:bold;\">$pattern BY $brand 4 Piece $type Setting:</span>";

    if($type=="PLACE SIZE"){
       $psf=" OR item=\"SALAD FORK (PLACE SIZE)\"";
       $type="PLACE";
       $limit=" LIMIT 5";
    }
    
    $query="SELECT quantity,item FROM inventory WHERE (monogram=0 or monogram IS NULL) and quantity>0 AND pattern=\"$pattern\" AND brand=\"$brand\" AND (item=\"TEASPOON\" OR item=\"SALAD FORK\" OR item=\"$type FORK\" OR instr(item, \"$type KNIFE\") > 0 $psf ) ORDER BY quantity ASC $limit";
    $sResult=mysql_query($query);
    $numrows=mysql_num_rows($sResult);
 
     if($numrows>=4){
       $i=0;
       
       while($r=mysql_fetch_assoc($sResult)){ 
         
          extract($r);

          if($i==0){
            updatePSQuantity($id,$r['quantity'],$pattern,$brand,$type);
          // $c.="<span style=\"font-size:10pt;\"> $quantity in stock</span><br />";
           }
           //$c.= "<span style=\"font-size:9pt;margin-left:15px;\">$item: $quantity</span><br/>";
          //updatePSQuantity($id,$r['quantity'],$pattern,$brand,$type);
         $i++;
       }
     
     }
     
     else{
      updatePSQuantity($id,0,$pattern,$brand,$type);
     }
   // echo "<span style=\"font-size:7pt;font-family:arial;\">$query:$numrows records</span><br />";
  
  //$c.="<br />";
 // echo $c;
}

function updatePSQuantity($id,$qty,$pattern,$brand,$type){

  $query="UPDATE inventory SET quantity = $qty WHERE id=$id LIMIT 1";
  $result=mysql_query($query);
  
  echo "<span style=\"font-size:7pt;font-family:arial;\">$pattern BY $brand 4 Piece $type Setting: $query</span><br />";

}

?>