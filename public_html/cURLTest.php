<!doctype html>

<html>
	<head>

		<title>AYLISS Site Script Dependencies</title>
		
		<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>	
		<script type="text/javascript" src="/js/libs/modernizr-2.6.2.min.js"></script>
		<script type="text/javascript" src="/js/libs/gumby.min.js"></script>
		
		<link rel="stylesheet" type="text/css" href="/css/Gumby/css/gumby.css">
		<link rel="stylesheet" type="text/css" href="/css/fonts.css">
		<link rel="stylesheet" type="text/css" href="/css/tripadvisor.css">
		<link rel="stylesheet" type="text/css" href="/css/main.css">
	</head>

	<body class="default">
		<header class="container sixteen colgrid pageHead">
			
			<div class="row">
				<h1 class="sixteen columns">Script Dependency Diagram</h1>
			</div>
		</header>
		
	<section role="main">
			
	<?php 
		//echo "hello";
		
		ini_set("display_errors",1);
	
     // create curl resource 
        $ch = curl_init(); 

        // set url 
       // $baseURL = "index.html"
        curl_setopt($ch, CURLOPT_URL, "www.asyoulikeitsilvershop.com/shoppingCart.php"); 

        //return the transfer as a string 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

        // $output contains the output string 
        $output = curl_exec($ch); 
        
        curl_close($ch);  
         
        $headerContent = substr($output, strpos($output,"<head>"),strpos($output,"</head>"));
       
        //echo "full header:<pre>".$headerContent."</pre><br>";
       
        $headerContent = substr($headerContent,100);
       
        //echo $headerContent;
        
        $arrScripts = explode("<script ", $headerContent);
        //echo "<pre>";
        
        //print_r($arrScripts);
        
        //echo "</pre>";
        
        $i = 1;
        $tagPos = 0;
        $endPosition = strlen($headerContent);
        
        echo "<br>header length:$endPosition<br>";
        
        
        foreach($arrScripts as $script){
	        
	          if($script && $script!="" && trim($script)!="" && strlen($script)>0 && strpos($script,"nofollow") === false) {
		        //echo "there is script content<br>";
		        if(strpos($script, "src")){
			    	$arrSrc = explode("src=", $script);
			    	
			    	$script = str_replace(array(">","\"","'"),array("","",""),$arrSrc[1]);
		         
					echo " <pre style='color:black;font-family:courier;background-color:#eee'>$i $script</pre><br>";
				
					$i++;	    
				}
			 }   
		
		}
?>
		</section>
	</body>
</html>