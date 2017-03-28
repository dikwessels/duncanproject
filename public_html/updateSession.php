<?php
	
	session_name('checkout');
	session_start();
	
	foreach($_GET as $k => $v){

		$_SESSION[$k] = $v;
		
		echo "$k => ".$_SESSION[$k];

	}
	
	?>