<?php

 $arrItems=explode("&",substr($_COOKIE["items"],1));
 $item_count=count($arrItems);
 $hasGiftCard=false;
	for($i=0;$i<$item_count;$i++){
	if(substr($arrItems[$i],0,2)=="gc"){
			$hasGiftCard=true;
		}
	}
	
	echo $hasGiftCard;

?>