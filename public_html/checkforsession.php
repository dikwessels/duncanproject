<?php
session_name('checkout');
session_start();

if(isset($_SESSION)){
//foreach($_SESSION as $k=>$v){
	//echo $k."=>".$v."<br>";
	echo json_encode($_SESSION);
//}
	//echo "set";	
}
else{
	echo "not set";
}

?>