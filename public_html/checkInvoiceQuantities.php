<?php
include_once("/connect/mysql_connect.php");
$ok=1;
 $arrItems=explode("&",substr($_COOKIE["items"],1));
 $item_count=count($arrItems);
 $hasGiftCard=false;
	for($i=0;$i<$item_count;$i++){
	if(substr($arrItems[$i],0,2)!="gc"){
			$id=explode(":",$arrItems[$i]);
			$query="SELECT quantity FROM inventory WHERE id=".$id[0];
			echo $query."<br>";
			$result=mysql_query($query);
			$row=mysql_fetch_assoc($result);
			if($row[quantity]<=0){
				$ok=0;
			}			
		}
	}
	
	echo $ok;

?>