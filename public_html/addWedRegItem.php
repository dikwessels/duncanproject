<?php
	
include("/home/asyoulik/connect/mysql_connect.php");

extract($_GET);
extract($_POST);
	
//echo "hello";

$regID = $_COOKIE['aylissWeddingReg'];

//echo "$regID<br>";

switch($action){
	
case '':

//echo "add from website\n\r";

 addFromWebsite($id,$quantity,$regID,$ref,$pattern);
 break;

case 'update':
 addFromStore();
 break;
}

function addFromStore(){
	
	extract($_GET);

	foreach($itemID as $k=>$v){
		
		$query="SELECT id FROM weddingRegistryItems WHERE itemID=$v and regID=$regID[$k]";
		
		$result=mysql_query($query);
		
		if(mysql_num_rows($result)>0){
			
	 		$query="UPDATE weddingRegistryItems SET qtyRequested=$qtyR[$k] WHERE itemID=$v and regID=$regID[$k]";	
			$result=mysql_query($result);
		}

		else{
			//insert item
			insertItem($v,$regID[$k],$qty[$k]);
		}
	//end foreach loop
	}
	
//end function
}

function addFromWebsite($itemID,$qt,$regID,$ref,$pat){

	//echo "addFromWebsite\n\r$itemID";
	//echo "<script type='text/javascript'>console.log('$itemID');</script>";
	if( $itemID ){
		
    	    $pb = getPatternBrandLabel($itemID);
    	    
    	    addOneItem($itemID,$regID,$qt,$pb,$ref,TRUE);
    	    
	}
	else{
		
    	    if( $pat ){
	    	    
        		addPattern($pat,$regID,$ref);
        		
            }
	
	}
	
}

function addOneItem($itemID,$regID,$qty,$pb,$ref,$showMsg){
	
    $alreadyInTable = checkForItem($itemID,$regID);
    
    if( $alreadyInTable ){
	    
        $responseMsg = updateItem($alreadyInTable,$qty);
   
    }
    else{
         $responseMsg = insertItem($itemID,$regID,$qty,$pb,$ref);
    }
    
    if($showMsg){echo $responseMsg;}
    
}

function addPattern($pat,$regID,$ref){
	
    $p=explode(" BY ",$pat);
    
    $query="SELECT id as itemID,quantity as qt,item,pattern,brand from inventory WHERE quantity <> 0 AND pattern=\"$p[0]\" AND brand=\"$p[1]\" ORDER BY pattern, brand, item";
   
     $result=mysql_query($query);
           
     //echo $query." results: ".mysql_num_rows($result);

    while($row=mysql_fetch_assoc($result)){
	    
        extract($row);
        addOneItem($itemID,$regID,$qt,"",$ref,FALSE);
        if(!mysql_error()){
          //echo "$pat $item has been added to your registry<br/>";
        }
       else{
         //echo "Database error:".mysql_error();
        }
    }

	if(!mysql_error()){
		echo "$pat has been added to your registry.";
	}
	else{
		echo "Database error: ". mysql_error();
	}

}

function checkForItem($itemID,$regID){

  $rowID=0;
    $result=mysql_query("SELECT id as rowID FROM weddingRegistryItems WHERE itemID=$itemID and regID=$regID LIMIT 1");

    if(mysql_num_rows()>0){
     $row=mysql_fetch_assoc($result);
     $rowID=$row['rowID'];
    }

 return $rowID;

}


function getPatternBrandLabel($itemID){
    
    $result=mysql_query("SELECT item,pattern, brand from inventory where id=$itemID");
    $row=mysql_fetch_assoc($result);
    extract($row);
    $pb = $pattern?"$pattern by $brand":$brand;
    
    return $pb;
    
}

function insertItem($itemID,$regID,$qty,$pb,$ref){
	$query="INSERT INTO weddingRegistryItems(regID,itemID,qtyRequested,qtyPurchased) VALUES(\"$regID\",\"$itemID\",\"$qty\",\"0\")";
	$result=mysql_query($query);
	$success=mysql_affected_rows();
        if($success>0){
            $has=($qty>1)?'have':'has';
			$msg=($ref)?"This Item has been added to your registry.":"$qty $pb $item $has been added to your registry.";
        }
        else{
        	$msg=mysql_error();
        }

 return $msg;
}

function updateItem($rowID,$qt){

    $query="UPDATE weddingRegistryItems SET qtyRequested=qtyRequested+$qt WHERE id=$rowID";
    $r=mysql_query($query);

    if(mysql_affected_rows()>0){
        $message="This item has been updated";
    }
    else{
        $message=mysql_error();
    }   

}


//6070803
/*while($r=mysql_fetch_assoc($q)){

   extract($r);
    if($pat){$pb=$pat;}
    else{$pb = $pattern?"$pattern by $brand":$brand;}

   //check to see if item's already in registry
   $result=mysql_query("SELECT id as rowID FROM weddingRegistryItems WHERE itemID=$itemID and regID=$regID LIMIT 1");

   if(mysql_num_rows($result)>0){
        //item is already in registry, simply add to existing row
        $row=mysql_fetch_assoc($result);
        extract($row);
	
        $message=updateItem($rowID,$qt);   
   }

   else{
        //create new entry for registry item
        $success=insertItem($itemID,$regID,$qt);

	    if($success>0){
		$has=($qt>1)?'have':'has';
		$message=($ref)?"This Item has been added to your registry.":"$qt $pb $item $has been added to your registry.";
	    }
	    else{
	         $message= mysql_error();
	    }
    }

    echo $message;
}
*/
?>

