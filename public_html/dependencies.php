<?php
	
	ini_set("display_errors");
	
	$pageContent = file_get_contents("shoppingCart.php");
	
	//echo "hello";
	//echo strlen($pageContent);
	
	$totalLength = strlen($pageContent);
	
	$output = "";
	
	//exit;
	//first get php dependencies
	$curPosition = 0;
	
	while(($curPosition < $totalLength)){
		 
		//echo $curPosition."<br>";
	
		$start = strpos($pageContent,"include",$curPosition);
	
		if($start >0 ){
		$start = strpos($pageContent,"(",$start)+2;
		
		$end = strpos($pageContent,")",$start);
		
		$length = $end - $start-1;
		
		$script = substr($pageContent,$start,$length);
		
		$curPosition = $end;
		
		$output .= "$script<br>";
		}
		else{
			$curPosition = $totalLength;
		}			
	}
	
	
	echo $output;
	
	?>