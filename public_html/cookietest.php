<?php 
$hour = 3600 + time();
//this adds 30 days to the current time
$datestamp=date("F jS - g:i:s a");

if($_POST){
    extract($_POST);
    
    if($cookieData=="clear cookies"){
      //retrieve old cookie data
 	    $oldcookie=$_COOKIE['myCookie'];
    	$oldAbout=$_COOKIE['AboutVisit'];
    	
    	
    	// destroy old cookies
    	setcookie(myCookie,$oldcookie,time()-72000);
        setcookie(AboutVisit,$oldAbout,time()-72000);
    }
    
    
    
    if(isset($_COOKIE['mycookie']) || isset($_COOKIE['AboutVisit'])){
 	    
 	    //retrieve old cookie data
 	    $oldcookie=$_COOKIE['myCookie'];
    	$oldAbout=$_COOKIE['AboutVisit'];
    	
    	
    	// destroy old cookies
    	setcookie(myCookie,$oldcookie,time()-72000);
        setcookie(AboutVisit,$oldAbout,time()-72000);
        
    }
	
	// set new cookie
	setcookie(myCookie,$cookieData,$hour);
	setcookie(AboutVisit,$datestamp,$hour);
    
    $newcookie = $_COOKIE['myCookie'];
    
	echo "Welcome back! <br> You last visited on $oldAbout and entered \"$newcookie\" in the form.<br>$cookieData<br>$oldcookie";
	echoForm();

 }
 
else{
	setcookie(AboutVisit, $datestamp, $hour);
	echo "Welcome to our site!";
	echoForm();
}

/*
if(isset($_COOKIE['AboutVisit']) || isset($_COOKIE['mycookie']))
{
	$last = $_COOKIE['AboutVisit'];
	$cookieD= $_COOKIE['mycookie'];

	
	setcookie(AboutVisit,$last, time()-72000);
	setcookie(mycookie, $cookieD, time()-72000);
	
	
	
	
		
		
}
else
{
echo "Welcome to our site!";
echoForm();
} 

*/


function echoForm(){
	echo "<table width=\"200\"><tr><td><form method=\"post\" action=\"/cookietest.php\">
		Enter Cookie Data Here:<input type=\"text\" name=\"cookieData\" length=\"50\"><br>
		<input type=\"submit\" name=\"Submit\">
		</form></tr></td></table>";

}
?>
