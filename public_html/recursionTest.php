<?php


$testArray = array(
			'shoppingCart.php',
			
			'checkout.php'=>array(
							'/connect/mysql_pdo_connect.php',
							
							'sandbox.checkout-functions-min.js'=>array(
																'checkforsession.php',
																
																'checkGiftCard.php',
																
																'get-invoice-total.php')
							),
							
			'checkoutSettings.php'

	);
 $inputArray = array('hello.php', 
 				'world.php', 
 				'helloworld.js' => array('a', 
 							'b',
 							'c'=>array('michael',
 										'wagner',
 										'is',
 										'going',
 										'to',
 										'kill himself')
 							)
 				);
 
 	
 	
  $outputArray = array();
  $currentName = "";
  
  crawl($testArray,"");
  
  echo "Input Array:";
  
  print_r($testArray);
  
  echo "<br><br>
  		Output Array:
  		";
  		
 // ini_set("display_errors", 1);
 
  print_r($outputArray);
  
  
  function crawl($object,$parentName){
	  
	  //object will either be an array or non-array
	  //parent name is the outputarray key storing object (if applicable)
	  global $outputArray;
	  global $currentName;
	  
	  if( is_array($object) ){
		//scan array	
		foreach($object as $k=>$v){
					
			if(is_array($k)){
				crawl($v,$k);
			}	
			else{
				consoleLog($v,$k,"tag");
				
				if(array_key_exists($k, $object)){
					
					$outputArray[$k] = $v;
				
				}
				else{
				
					$outputArray[] = $v;
					
				}
			}
		}  
	  
	  }
	  
	  else{
		  //it's an non key-value pair value
		   consoleLog($object,$parentName);
		  	$outputArray[] = $object;
	  }
	  
	  	  
  }
  
function  consoleLog($var,$to,$tag){
	  echo "Adding $var to $to...$tag<br>";
  }
  
  
	
?>