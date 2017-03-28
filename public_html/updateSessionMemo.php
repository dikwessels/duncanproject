<?php

	session_name('checkout');
	session_start();

	foreach($_GET as $k => $v){
		
		if($v != ""){
			
			$_SESSION[$k] = $v;
		
		}
	}

?>