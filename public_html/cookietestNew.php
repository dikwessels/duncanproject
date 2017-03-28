<?php
if(isset($_COOKIE)){
	echo 'cookie set';
	
	foreach($_COOKIE as $k=>$v){
		echo "$k   $v<br>";
	}
	//echo $_COOKIE['pma_lang'];
	
}
?>