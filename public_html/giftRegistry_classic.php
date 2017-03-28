<?php 
	ini_set("display_errors",1);
ob_start();


/*
Algorithm

User will get to this page 1 of two ways:

	1) they click a create wedding registry link, page will get a $_GET variable action = 'newReg'
	2) they click an add to wedding registry link from the search pages, page will get a $_GET variable of the item id and the action='addItem'

	If the item id isn't set, then it is a new registration


*/

extract($_POST);
extract($_GET);

include("/home/asyoulik/connect/mysql_pdo_connect.php");


if($_GET || $_POST){

	switch($action){
	
        case 'loginOnly':
                //for people who are trying to add items from front end but aren't yet logged in
                echoLogin($itemID,"","",$ref);
                break;

	case 'find':
	        //for the store program to check on existing registries
	  	echo(getRegistryID($email));
	   	break;
	
        case 'create':
		//for the store program to create new registries
		echo(createRegistry($wMonth,$wDay,$wYear,$rFname,$rLname,$rAddress,$rCity,$rState,$rZipcode,$rPhone,$rEmail,$crFname,$crLname,$crAddress,$crCity,$crState,$crZipcode,$crPhone,$crEmail,$bwedship,$awedship,'password',$sa));   
    	        break;
    
        case '':
     	        cookieCheck($itemID,1);
		break;
		
	case 'newReg':
	
		$pageTitle="Create Your Registry";
			
		newRegForm($action,$itemID);
	
		break;

	case 'showLogin':
		loginForm($itemID,"","",$ref);
		break;

	case 'addItem':
		cookieCheck($itemID,0);
		break;
	
    case 'ep':
    	        echoPageHead($pageTitle);
    	        $p=EmailOnFile($email);
    	        if($p){
    		    emailPassword($email,$p);
    		    forgotPassword(1);
    	        }
    	        else{
    		    $msg="The email address you entered is not on file.";
	    	    forgotPassword("",$msg);
    	        }
  	
   	  	echoPageFooter();	
   	  	break;
    
    case 'fp':
    	echoPageHead();
       	forgotPassword();
     	echoPageFooter();
     	break;
     
	case 'login':
	 	LogUser($username,$password,0,$itemID,$ref);
	 	break;

	case 'logout':
	    setcookie("aylissWeddingReg","",time()-3600);
	    $confmsg="<span class=\"sectHead\" style=\"color:000000\">You have logged out.</span><br>"; 
	    echoPageHead();
	    showSplash();
	    echoPageFooter();
	    //loginForm("","",$confmsg);
	    break;

	case 'showSearch':
	   echoPageHead();
	   echoSearchForm();
	   echoPageFooter();
	   break;
	
	case 'checkInfo':    
	   break; 
	}


}

else{
	cookieCheck($itemID,1);
}

function checkLoginInfo($username,$password){

	global $db;

	$username = urldecode($username);
	$password = urldecode($password);

	$stmt = "SELECT id FROM weddingRegistries where remail=:email and pword=:pword";
	$query = $db->prepare($stmt);
	
	$query->bindParam(':email',$username);
	$query->bindParam(':pword',$password);
	
	$query->execute();
	$result = $query->fetchAll();
	
	//$result = mysql_query($query);

	$r = $result[0];
	//$r= mysql_fetch_assoc($result);
	
	extract($r);

	if($id){
		return $id;
	}

	else{
		return 0;
	}

}

function cookieCheck($itemID,$showSplash){
//check to see if a cookie's set with the user
	 if(isset($_COOKIE['aylissWeddingReg'])){
	 
	 // $regID=$_COOKIE['aylissWeddingReg'];
	   header("Location:http://www.asyoulikeitsilvershop.com/giftRegistryEdit.php?itemID=$itemID");
	 
	 }
	 
	 else{
	 	//have the user login
	 	if($itemID){
	 		 loginForm($itemID);
		 }

		 else{
		   	echoPageHead("Gift Registry");
			showSplash();
			//echoSearchForm();
			echoPageFooter();
		}
	 }
}

function createRegistry($event,$wMonth,$wDay,$wYear,$rFname,$rLname,$rAddress,$rCity,$rState,$rZipcode,$rPhone,$rEmail,$crFname,$crLname,$crAddress,$crCity,$crState,$crZipcode,$crPhone,$crEmail,$bwedship,$awedship,$pword,$sa){
	
	
	global $db;
	
	if($sa){
		
	 $crAddress=$rAddress;
	 $crCity=$rCity;
	 $crState=$rState;
	 $crZipcode=$rZipcode;
	 
	}
	
	/*$fieldArray = array("event" 	=>	":event",
						"wedmonth"	=>	":wedmonth",
						"wedday"	=>	":wedday",
						"wedyear"	=>	":wedyear",
						"rfname"	=>	":rfname",
						"rlname"	=>	":rlname",
						"raddress"	=>	":raddress",
						"rcity"		=>	":rcity",
						"rstate"	=>	":rstate",
						"rzipcode"	=>	":rzipcode",
						"rphone"	=>	":rphone",
						"remail"	=>	":remail",
						"crfname"	=>	":crfname",
						"crlname"	=>	":crlname",
						"craddress"	=>	":craddress",
											crcity,
											crstate,
											crzipcode,
											crphone,
											cremail,
											bwedship,
											awedship,
											pword)";*/
 	
 	$bindArray = array("event" 		=> 	$event,
 					   "wedmonth" 	=> 	$wMonth,
 					   "wedday"		=>	$wDay,
 					   "wedyear"	=> 	$wYear,
 					   "rfname"		=> 	$rFname,
 					   "rlname"		=>	$rLname,
 					   "raddress"	=>  $rAddress,
 					   "rcity"		=>  $rCity,
 					   "rstate"		=>  $rState,
 					   "rzipcode"	=>  $rZipcode,
 					   "rphone"		=>  $rPhone,
 					   "remail"		=>  $rEmail,
 					   "crfname"	=>  $crFname,
 					   "crlname"	=>  $crLname,
 					   "craddress"	=>  $crAddress,
 					   "crcity"		=>  $crCity,
 					   "crstate"	=>  $crState,
 					   "crzipcode"	=>	$crZipcode,
 					   "cremail"	=>	$crEmail,
 					   "bwedship"	=>	$bwedship,
 					   "awedship"	=>	$awedship,
 					   "pword"		=>	$pword
 					   
 					   );
 		
 		foreach($bindArray as $k => $v){

	 		$fields .= "$k,";
	 		$values .= ":$k,";
	 		
 		}			   

 		$fields = substr($fields, 0,-1);
 		$values = substr($values,0,-1);
 		
 		
 		
 		$stmt = "INSERT INTO weddingRegistries($fields) VALUES($values)";
 		
 		$f =fopen("/home/asyoulik/logs/sqlStatements.txt","a");
 		fwrite($f, date('Y-d-m hh:ii:ss').":".$stmt);
 	
 		$query = $db->prepare($stmt);
 	
 		foreach($bindArray as $k => $v){
	 	
	 		$query->bindValue(":$k",$v);
	 	
	 	}
 	
 	$query->execute();
 	
 	/*$stmt.=" VALUES(\"$event\",\"$wMonth\",\"$wDay\",\"$wYear\",\"$rFname\",\"$rLname\",\"$rAddress\",\"$rCity\",\"$rState\",\"$rZipcode\",\"$rPhone\",\"$rEmail\",";
 	  		$query.="\"$crFname\",\"$crLname\",\"$crAddress\",\"$crCity\",\"$crState\",\"$crZipcode\",\"$crPhone\",\"$crEmail\",\"$bwedship\",\"$awedship\",\"$pword\")";
 	  	
 	  	//$result = $query->fetchAll();
 	  	
 	  	
	  	//$result=mysql_query($query);
	  	*/
	  	$success = $query->rowCount();//mysql_affected_rows();
	  
	  	if( $success>0 ){

	  		 $loginInfo = $rEmail.":".$pword;

	  	}
	  	
	return $loginInfo;

	//return "success";

}


function daySelect($m,$d){
	
			
		$max=31;	
		
		if($m==4||$m==9||$m==6||$m==11){
			$max=30;
		}
		
		if($m==2){
			$max=29;
		}
							
		
		for($i=0;$i<=$max;$i++){
		 	$v=($i==0?"Day":$i);
			$selected=($i==$d?"selected='selected'":"");
			echo "<option value='$i' $selected>$v</option>";
		}
	
}

function echoLogin($itemID,$errMsg,$confMsg,$ref){
  

    $formTag=$ref?"<form onsubmit=\"javascript:return false;\" style=\"margin-left:0px;\">":"<form style=\"margin-left:0px;\" method=\"post\" action=\"/giftRegistry_classic.php\">";
    $buttonTag=$ref?"<input type=\"button\" value=\"Login\" onclick=\"LogUser($ref,$itemID)\">":"<input type=\"submit\" value=\"Login\">";
    $cancelTag=$ref?"<input type=\"button\" value=\"Cancel\" onclick=\"hideLogin()\">":"";

$title=$ref?"Please log into your gift registry using the form below:":"Please enter your username and password:";
    $style=$ref?"border:1px solid #aaaaa;":"";   
	$c="
   	
   	<table><tbody style=\"text-align:center;$style\"><tr><td colspan=\"2\">   
	 	  $formTag
			<input type=\"hidden\" name=\"itemID\" value=\"$itemID\">
			<input type=\"hidden\" name=\"action\" value=\"login\">		
	 	  <table><tbody style=\"text-align:left\">
	 	  <tr><td colspan=\"2\">$confMsg<span class=\"sectHead\">$title</span>$errMsg</td></tr>
    	  <tr>
    	  	<td>Username:</td>
    	  	<td><input class=\"reg\" size=\"30\" type=\"text\" name=\"username\" id=\"username$itemID\"></td>
    	  </tr>
   	      <tr>
   	      	<td>Password:</td>
   	      	<td><input class=\"reg\" size=\"30\" type=\"password\" name=\"password\" id=\"password$itemID\"></td>
   	      </tr>
   	      <tr>
   	      	<td></td>
   	      	<td>$buttonTag <span style=\"min-width:10px\">&nbsp;</span>$cancelTag</td>
   	      </tr>
   	      
   	      <tr>
   	      	<td><a href=\"//www.asyoulikeitsilvershop.com/giftRegistry_classic.php?action=newReg&itemID=$itemID\">New User</a></td>
   	      	<td><a href=\"//www.asyoulikeitsilvershop.com/giftRegistry_classic.php?action=fp&itemID=$itemID\">Forgot your password?</a></td>
   	      </tr>
   	      
   	      </tbody>
   	      </table>
   	      
   	      
   	      </form>
   	      
   	      </td></tr>
			
   	      
   	      </tbody>
   	      </table>
   	      
   	      ";
    echo $c;

}

function echoPageFooter(){
 echo("	</tbody>
		</table>

	</td></tr>
</tbody>
</table>

<br clear=\"all\">
		<table width=\"760\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" align=\"left\">
<tr>
	<td width=\"50\"><img src=\"../images/blank.gif\" width=\"50\" height=\"1\" alt=\"\" border=\"0\"></td>
	<td valign=\"top\">
		<hr width=\"710\" noshade size=\"1\" color=\"#A27177\" align=\"left\">
		<p class=\"bottom\">© Copyright 2003-".date('Y').". As You Like It Silver Shop.</p>
		<p> </p>
	</td>
</tr>
</table>
	</body>
</html>");
}

function echoPageHead($pageTitle){

echo("<!DOCTYPE html
 PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"
 \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\" lang=\"en\">
<head>
<title> As You Like It Silver Shop: $pageTitle </title>
<meta name=\"description\" content=\"As You Like It Silver Shop in New Orleans Louisiana specializes in silver flatware and hollowware in active, inactive and obsolete patterns.\" />
<meta name=\"keywords\" content=\"sterling silver, sterling flatware, silver flatware, antique silver, silver tableware, antique sterling, replacement silver, silver repair, corporate gifts, wedding gifts, silver identification, cleaning silver\" />
<script language=\"javascript\" src=\"/js/images.js\"></script>
<script language=\"javascript\" src=\"/js/store.js\"></script>
<script language=\"javascript\" src=\"/js/ajax.js\"></script>
<script language=\"javascript\" src=\"/js/forms.js\"></script>

<script language=\"javascript\" type=\"text/javascript\">

 function additem(itemID){
	var regID = document.getElementById('regID').value;
	var quantity = document.getElementById(itemID).value;
	var params = \"id=\"+itemID +\"&regID=\"+regID+\"&quantity=\"+quantity;
	
	var url=\"addWedRegItem.php\";
        var action=\"document.getElementById('spanConfirmAdd').innerHTML=request.responseText;\";
 	var results = document.getElementById('spanConfirmAdd');
	results.innerHTML=\"One Moment...\";
	requestURL(url,params,action);	
 }


 function searchRegistry(s,fn,ln,sm,sy,regID){
	var status = document.getElementById('spanStatus');
        var itemList=document.getElementById('spanRegistryItems');
        var action=\"\";
        var waitAction=\"\";

    	if(regID){
    		
                status.innerHTML = \"Retrieving items...\";
                action = \"document.getElementById('spanRegistryItems').innerHTML=request.responseText;document.getElementById('spanStatus').innerHTML='';\";
                itemList.innerHTML=\"\";
    	}
	else{
		var fname = document.getElementById('sfname').value;
		var lname = document.getElementById('slname').value;
		var smonth = document.getElementById('sMonth').value;
		var syear = document.getElementById('sYear').value;
                
               action = \"document.getElementById('spanRegistryList').innerHTML=request.responseText;document.getElementById('spanStatus').innerHTML='';\";
                
                status.innerHTML=\"Searching...\";
		itemList.innerHTML=\"\";

		if(fn){fname=fn;}
		if(ln){lname=ln;}
		if(sm){smonth=sm;}
		if(sy){syear=sy;}
	}

	var url=\"searchWedReg.php\";
	var params = \"fname=\"+fname +\"&lname=\"+lname+\"&smonth=\"+smonth+\"&syear=\"+syear+\"&sort=\"+s+\"&regID=\"+regID;
	
	requestURL(url,params,action,waitAction);	
 }



</script>
    <link rel=\"stylesheet\" href=\"ayliss_style.css\" type=\"text/css\">
    <link rel=\"stylesheet\" href=\"ayliss_giftReg_style.css\" type=\"text/css\">


<script type=\"text/javascript\">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-31581272-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>

</head>

<body class=\"sub\" onLoad=preLoad()>

<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" align=\"left\" bgcolor=\"#A27177\" height=\"85\">
<tr>
	<td width=\"760\" background=\"/images/ayliss_title_r.jpg\" alt=\"\" border=\"0\" align=\"right\" valign=\"top\">
		<img src=\"/images/blank.gif\" width=\"760\" height=\"1\" alt=\"As You Like It Silver Shop\" border=\"0\"><br><table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td width=\"50%\" align=\"left\" valign=\"top\">
		<p class=\"homenav\"><a href=\"../contact.php\" class=\"top\">CONTACT US</a> * 1-800-828-2311</td><td width=\"50%\" align=\"right\"  valign=\"top\">
		<p class=\"homenav\">YOUR SILVER CHEST HAS <img src=\"../images/c__.gif\" name=nums3 align=bottom><img src=\"../images/c__.gif\" name=nums2 align=bottom>
		<img src=\"/images/c_0.gif\" name=nums1 align=bottom> ITEMS.<a href=\"/orderdetails.php\" class=\"top\"><img src=\"/images/silverchest_empty.gif\" border=\"0\" align=\"top\" hspace=\"0\" vspace=\"0\" border=\"0\" name=chest></a></td></tr></table></td>
	<td width=\"*\"><img src=\"/images/blank.gif\" width=\"10\" height=\"1\" alt=\"\" border=\"0\"></td>
</tr>
</table>
<br clear=\"all\">
<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" align=\"left\">");

include("topnav.inc");

echo("
<tr bgcolor=\"#7A121B\">
	<td width=\"760\" bgcolor=\"#7A121B\" align=\"center\">");
	 
		 readfile("silver_cats.in.php");

echo("</td>
	<td width=\"*\"><img src=\"../images/blank.gif\" width=\"10\" height=\"1\" alt=\"\" border=\"0\"></td>
</tr>
<tr>
	<td width=\"760\"><img src=\"/images/t_giftregistry.jpg\" width=\"760\" height=\"66\" alt=\"Advanced Search\"></td>
	<td width=\"*\"><img src=\"/images/blank.gif\" width=\"10\" height=\"1\" alt=\"\" border=\"0\"></td>
</tr>
</table>


<br clear=\"all\">
<table cellpadding=\"5\" cellspacing=\"0\" border=\"0\">
	<tbody align=\"left\">
		<tr>
			<td style=\"padding-left:20px\" width=\"740\" align=\"center\">
				<table cellpadding=\"1\" cellspacing=\"1\" border=\"0\" style=\"width:100%\">
					<tbody align=\"left\">
	

");




}

function echoSearchForm(){
					$c="<tr><td><form onsubmit=\"javascript:searchRegistry('','','','','','');return false;\" style=\"height:125px;margin-left:0px;width:760px\">
							<table>
								<tbody style=\"text-align:left\">
									<tr>
										<td colspan=\"3\">
											<span class=\"sectHead\">Search our Gift Registries:</span>
			 									$requiredMsg
										</td>
									</tr>
		
									<tr>
										<td class=\"cust\">
											First Name:<span class=\"required\">*</span>
											<span class=\"redtext\">$rfnamemsg</span><br>
											<input size=\"30\" id=\"sfname\" name=\"sfname\" value=\"$sfname\" class=\"reg\" type=\"text\">
										</td>
				
										<td width=\"25\"></td>	
				
										<td class=\"cust\">
											Last Name:<span class=\"required\">*</span>
											<span class=\"redtext\">$slnamemsg</span><br>
											<input size=\"30\" id=\"slname\" name=\"slname\" value=\"$rlname\" class=\"reg\" type=\"text\">
										</td>
									</tr>
			
									<tr>
										<td colspans=\"3\">Event Date (optional)</td></tr>
			
									<tr>
   		   		<td>
   		   			<select class=\"reg\" name=\"sMonth\" id=\"sMonth\">";
   		   			
   		   	echo $c;		
   		   	
   		   	readfile("http://www.asyoulikeitsilvershop.com/includes/month_select.php?m=$sMonth");
   		   	
   		   	$c="</td>
				
				<td width=\"25\"></td>						
				
				<td align=\"left\">
					<select  class=\"reg\" name=\"sYear\" id=\"sYear\" >
						<option value=\"0\">Year</option>";
			echo $c;
						
			readfile("http://www.asyoulikeitsilvershop.com/includes/year_select.php?y=$sYear");
			
			$c="</select>
					<span class=\"warning\">$wDateMsg</span>
				</td>
			</tr>
					
			<tr>
				<td colspan=\"3\"><input type=\"button\" value=\"Search\" onclick=\"javascript:searchRegistry(1,'','','','','')\"></td>
			</tr>
		
		</tbody>
	</table>
</form>
</td>
</tr>";
		
echo $c;	

echo("<tr>
		<td>
			<div class=\"sectHead\" style=\"height:20px;width:740px;\" id=\"spanStatus\">
			</div>
		</td>
	</tr>
	
	<tr>
		<td>
			<div style=\"left:20px;width:auto;overflow-y:auto;\">
				<span id=\"spanRegistryList\" style=\"margin-left:20px;\"></span>
			</div>
		</td>
	</tr>
	
	<tr>
		<td>
		<div style=\"margin-top:20px;left:20px;width:auto;\">
		<span id=\"spanRegistryItems\"></span>
		</div>
		</td>
	</tr>
	");	
		
}

/*	else{
	//echo "no email found";
		forgotPassword("","We're sorry, but the email address you entered was not on file.");
	}
}
*/

function echoFPForm($errMsg, $itemID){
echo("
   	
   	<table>
   	   	<tbody style=\"text-align:center\">
   	   		<tr>
   	   			<td>   
	 	  			<form style=\"margin-left:0px;height:350px;\" method=\"post\" action=\"/giftRegistry_classic.php?action=ep\">
						<input type=\"hidden\" name=\"itemID\" value=\"$itemID\">
						<input type=\"hidden\" name=\"action\" value=\"login\">		
	 	  					<table>
	 	  						<tbody style=\"text-align:left\">
	 	  							<tr>
	 	  								<td colspan=\"2\">
	 	  									<span class=\"sectHead\">Please enter the email address you used to create your registry.</span>$errMsg
	 	  								</td>
	 	  							</tr>
    	  							<tr>
    	  								<td>E-mail:</td>
    	  								<td><input autocomplete=\"off\" class=\"reg\" size=\"60\" type=\"text\" name=\"email\"></td>
    	  							</tr>
    	     	      				<tr>
   	      								<td></td>
   	      								<td><input autocomplete=\"off\" type=\"submit\" value=\"Request Password\"></td>
   	      							</tr>
   	      						</tbody>
   	      					 </table>
   	      				</form>
   	      			</td>
   	      		</tr>"	
    	  
    	  );
}

function emailConfirmation($email,$pword,$header){
$subject="As You Like It Silver Shop Gift Registry Information";

$headers = 'From: do-not-reply@asyoulikeitsilvershop.com' . "\r\n" .
    'Reply-To: sales@asyoulikeitsilvershop.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
$message=$header . "
To view your account, login at http://www.asyoulikeitsilvershop.com/giftRegistry_classic.php
\r\n
USERNAME: $email \r\n
PASSWORD: $pword 

Thank you for shopping at As You Like It Silver Shop.";

mail($email, $subject, $message, $headers);

//end emailConfirmation
}

function EmailOnFile($email){
	$onFile="";
	$query="SELECT pword FROM weddingRegistries WHERE remail=\"$email\"";
	$result=mysql_query($query);
	   	if(mysql_num_rows($result)>0){
 			$row=mysql_fetch_assoc($result);
 			$onFile=$row[pword];
		}
	return $onFile;
}

function emailPassword($email,$pword){
 	$msg="As you requested, we have emailed you your gift registry login information for asyoulikeitsilvershop.com \r\n";
 	emailConfirmation($email,$pword,$msg);
}

function forgotPassword($emailsent,$errMsg){
	if($emailsent!=""){
	echo("
	<table>
   	   	<tbody style=\"text-align:center\">
   	   		<tr>
   	   			<td> 
   	   			<span class=\"sectHead\">An email has been sent with your password information.</span>
	 	  		</td>
	 	  	</tr>
	 	  	<tr>
	 	  		<td>
	 	  		<a href=\"//www.asyoulikeitsilvershop.com/giftRegistry_classic.php\">Return to Gift Registry Main Page</a>
	 	  	 	</td>
	 	  	</tr>
	 	</tbody>
	</table>
	 ");
	}

	else{
	
	echoFPForm($errMsg,$itemID);
	
	}

}

function getRegistryID($email){

	global $db;
	
	$stmt = "SELECT id FROM weddingRegistries WHERE remail=\"$email\" LIMIT 1";
	
	$query = $db->prepare($stmt);
	
	$query->execute();
	
	$result = $query->fetchAll();

	$row = $result[0];
	
	return $row[id];

//end createRegistryLink
}

function is_DateValid($m,$d,$y){

 $valid=1;
 if($m==0 || $d==0 || $y==0){
  $valid=0;
 }
 else{
 	switch ($m) {
       
 	case 0:
 		$valid=0;
 		break;
 	
 	case 2:
 		if($d>29){$valid=0;}
  		break;
   
        case 4 || 6 || 9 || 11:
 		if($d>30){$valid=0;}
 		break; 
 
 	}
}

	return $valid;
}

function is_valid_email($email){
    	$isvalid=0;
    	
    	$regexp= "^([_a-z0-9-]+)(\.[_a-z0-9-]+)*@([a-z0-9-]+)(\.[a-z0-9-]+)*(\.[a-z]{2,4})$";
		
		if(eregi($regexp, $email)){
         
 		list($username,$domaintld) = split("@",$email);
      	// Validate the domain
      	
      	if (getmxrr($domaintld,$mxrecords)){
      		$isvalid=1;
      	}
          	  
    	}
	 
 	 return $isvalid;	
 }


//main();

function loginForm($itemID,$errMsg,$confMsg,$ref){
    if(!$ref){echoPageHead("Gift Registry Login");}
    echoLogin($itemID,$errMsg,$confMsg,$ref);
    if(!$ref){ echoPageFooter();}
}

function LogUser($username,$password,$new,$itemID,$ref){

	$success= checkLoginInfo($username,$password);
	//echo "Username: $username<br>Password: $password<br>RegID: $success";
	
	if($success){
	  	setcookie("aylissWeddingReg", $success, time()+3600);
               
                if(!$itemID || $itemID=""){$itemID=0;}
		 if($ref){
                   echo "<!--logged in -->"; //include("/addWedRegItem.php?id=$itemID&regID=$success&quantity=1");
                 }
                else{
                         header("Location://www.asyoulikeitsilvershop.com/giftRegistryEdit.php?itemID=$itemID&new=$new");
	        }
        }

	 
	else
	{
		$errMsg="<br><span class=\"redtext\">Incorrect password or username entered.</span>";
	 	if($ref)
                {
                    loginForm($itemID,$errMsg,"",$ref);
                }
                else{loginForm("",$errMsg,"",$ref);}
	}

}

//main sub routine
function main($action){
	
	
	$acctcreated=0;
	$dateMsg="";
	$remailmsg="";
	$rfnamemsg="";
	$rlnamemsg="";
	$raddressmsg="";
	$rcitymsg="";
	$rstatemsg="";
	$rzipcodemsg="";
	$pwordmsg="";
	$requiredMsg="";
	
	if($_POST){
		
	 extract($_POST);
	 
	 $valid=1;
	 if(is_DateValid($wMonth,$wDay,$wYear)==0){
 	 		$datemsg ="Required (you may change the date after creating the registry)";
 	 	 	$valid=0;
 	 	}
 	 	else{$datemsg="";}
 	 	
 	 	if(!$event){
 	 	 $valid=0;
 	 	 $eventmsg="Required";
 	 	}
 	 	
 	 	if(is_valid_email($rEmail)==0){
 	  		$remailmsg="Please enter a valid email address.";
 	  		$valid=0;
 	  	}
 	 	
 	 	else{$remailmsg="";}
 	 	
 	 	if(!$rFname || $rFname==""){
 	 		$rfnamemsg="Required";
 	 		$valid=0;
 	 	}
 	 	else{$rfnamemsg="";}
        
        if(!$rLname || $rLname=="" ){
       		$rlnamemsg="Required";//"Please enter your last name.";
	    	$valid=0;
     	}
        else{$rlnamemsg="";}
 		
 		if(!$pword || $pword==""){
 		 $valid=0;
 		 $pwordmsg="Please enter a password for your registry.";
 		}
 		else{
 		
 		if(!$pwordConfirm || $pwordConfirm=="" || $pwordConfirm!=$pword){
 		 $pwordmsg="Confirmed password does not match password.";
 		 }
 		 else{$pwordmsg="";}
 		}    
 		 
      	if($pwordmsg!=""){$pwordmsg="<br><span class=\"redtext\">$pwordmsg</span>";}
		
		if(!$rAddress || $rAddress==""){
		$raddressmsg="Required";//"Please enter your address.";
		$valid= 0;
		}
     	else{$rAddressmsg="";}
     	if(!$rCity || $rCity==""){
     	$rcitymsg="Required";//"Please enter your city.";
     	$valid=0;
     	}
     	else{$rcitymsg=="";}
     	if(!$rState || $rState=="" || $rState=="NO"){
     	$rstatemsg="Required";//"Please enter a state.";
     	$valid=0;
     	}
  		else{$rstatemsg="";}
  		if(!$rZipcode || $rZipcode==""){
  		$rzipcodemsg="Required";//"Please enter your zipcode.";
  		$valid =0;
  		}
  		else{$rzipcodemsg="";}
 
  		
 	 if($valid){
 	  // create Registry entry
 	   if(newCustomer($rEmail)){
 	  	
 	  	$success = createRegistry($event,$wMonth,$wDay,$wYear,$rFname,$rLname,$rAddress,$rCity,$rState,$rZipcode,$rPhone,$rEmail,$crFname,$crLname,$crAddress,$crCity,$crState,$crZipcode,$crPhone,$crEmail,$bwedship,$awedship,$pword,$sameaddress);	
 	   	
 	   	if($success != ""){
 	   		
 	   		LogUser($rEmail,$pword,1,$itemID);
 	   		emailConfirmation($rEmail,$pword,$msg);
	  		/*	
	  		$rID=getRegistryID($rEmail);
	  		setcookie("aylissWeddingReg", $rID, time()+3600);
			header("Location:http://www.asyoulikeitsilvershop.com/giftRegistryEdit.php?itemID=$itemID&new=1");
	  		$msg = "You're wedding registry account has been created at As You Like It Silver Shop.\r\n";
	  		*/
	  		}
	   	}
 	  
 	   else{
	 	  	showForm($itemID,$requiredMsg,$rfnamemsg,$datemsg,$rlnamemsg,"This email address is already on file",$raddressmsg,$rcitymsg,$rstatemsg,$rzipcodemsg,$pwordmsg,$eventmsg);
 	   }
 	  
    }
 	 /*show required fields with missing data*/	 
 	 else{
 	 
 	 if($av){
 	 	//show the confirmation form version
 	 	showConfirmation($itemmsg,$qtymsg,$ptnmsg,$brandmsg,"Please specify the required fields");
 	 }
 	 else{
 		$requiredMsg=" <br><span class=\"redtext\">Please specify all fields labelled 'Required'</span>";
  //showForm($itemID,$requiredMsg,$rfnamemsg,$datemsg,$rlnamemsg,$remailmsg,$raddressmsg,$rcitymsg,$rstatemsg,$rzipcodemsg,$pwordmsg,$eventmsg)
     showForm($itemID,$requiredMsg,$rfnamemsg,$datemsg,$rlnamemsg,$remailmsg,$raddressmsg,$rcitymsg,$rstatemsg,$rzipcodemsg,$pwordmsg,$eventmsg);
           }
   	 }
 	 
 	 //end if $_POST  	 
 	 }
 	 /*brand new form*/
 	 else{
 	 showForm('','','','','','','','','','','','');
 	 }
//end main 
}

function monthSelect($m){
	
			$monthArray=array(
				
				"Month",
				"January",
				"February",
				"March",
				"April",
				"May",
				"June",
				"July",
				"August",
				"September",
				"October",
				"November",
				"December"
			
			);
		
						
		for($i=0;$i<13;$i++){
			$selected=($i==$m?"selected=\"selected\"":"");
			echo "<option value='$i' $selected >$monthArray[$i]</option>";
		}
	
}

function newCustomer($email){
	
	global $db;
	
		$isnew	= 1;
		$stmt 	= "SELECT remail FROM weddingRegistries WHERE remail=\"$email\"";
		$query 	= $db->prepare($stmt);
		$query->execute();
		$result = $query->fetchAll();
		
		//$result=mysql_query($query);
		
		$n = count($result);// mysql_num_rows($result); 

		if($n>0){$isnew=0;}

   	 return $isnew;
  	
  	//end newCustomer
}
  	
function newRegForm($action,$itemID){
	
 	echoPageHead("Create Your Wedding Registry");
 
 	main($action);
 
 	echoPageFooter();
 
}

function showConfirmation($email,$registryLink,$misc){
    
 
    
    $pattern="";
    $brand="";
    $item="";
    
    
    extract($_POST);
    
    	echo("<table><tbody style=\"text-align:left\">
   	
   	
   		<tr>
   			<td colspan=\"3\">
   				<span class=\"sectHead\">Wedding Registry Created</span><br>
   				Your wedding registry has been created and a confirmation email has been sent to $email.<br>
   				   				
   			</td>
   		</tr>
   		</table>");
   		
   	
 	
 	//end showConfirmation
 	
 	}
 	
function showForm($itemID,$requiredMsg,$rfnamemsg,$datemsg,$rlnamemsg,$remailmsg,$raddressmsg,$rcitymsg,$rstatemsg,$rzipcodemsg,$pwordmsg,$eventmsg){
    
    
    $pageheader="Please enter your contact information.";	
	$sectionheader="Wishlist Item Information.";	
	
	/*
	weddingRegistries table
	id 
	event
	rfname
	rlname
	raddress
	rcity
	rstate
	rzipcode
	remail
	rphone
	crfname
	crlname
	craddress
	crcity
	crstate
	crzipcode
	cremail
	crphone
	bwedship
	awedship
	pword
	wedmonth
	wedday
	wedyear
	*/
	
	/*

 	weddingRegistryItems
	id
	wedRegID
	itemID
	qtyRequested
	qtyPurchased

	*/
	
	if($_POST){extract($_POST);}
	extract($_GET);
   	
 	echo("<form style=\"margin-left:0px;width:760px\" method=\"post\" action=\"/giftRegistry_classic.php\">");
 
   	echo("<table>
   	
   		<tbody style='text-align:left'>
   	
   		<tr>
   			<td colspan='3'>
   				<span class='sectHead'>Event Information:</span><span style='font-size:8pt'>(items marked with an asterik are required)</span>
   			$requiredMsg
   			</td>
   		</tr>
   		<tr>
   			<td colspan='3' padding='0'>	
   			<table style='margin:0px;'>
   			<tr>
   			<td>
   				Type:<span class='required'>*$eventmsg</span><br>
   				<select class='regReq' id='event' name='event'>");
   				
   				
   				 eventList($event);
  
  				 echo("</select>
   			</td>
   			<td width='20'></td>
   			<td >
   				Date:
   				<span class='required'>*$datemsg</span><br>
   				<select class='regReq' name='wMonth' id='wMonth'>");

 	  			monthSelect($wMonth);
   				
   				echo("</select>&nbsp;/&nbsp;
   				
   				<select class='regReq' name='wDay' id='wDay'>");
   				
   				daySelect($wMonth, $wDay);
   				
   			
   				echo("</select>&nbsp;/&nbsp;<select  class='regReq' name='wYear' id='wYear'>
   						<option value='0'>Year</option>");

   				yearSelect($wYear);

   				echo("</select>
   				
   			</td>
   		</tr>
   		</table>
   		</td>
   		</tr>");
   								
							
					echo("						
		<tr>
			<td colspan='3'><hr class='thin'></td>
		</tr>
	
	
		<tr>
			<td colspan='3'>
				<span class='sectHead'>Registrant's Information</span>
			 

			</td>
		</tr>
		
		<tr>
			<td class='cust'>
				First Name:<span class='required'>*</span>
				<span class='redtext'>$rfnamemsg</span><br>
				<input autocomplete='off' size='30' name='rFname' value='$rFname' class='regReq' type='text'>
			</td>
			<td width='25'></td>	
			<td class='cust'>
				Last Name:<span class='required'>*</span>
				<span class='redtext'>$rlnamemsg</span><br>
				<input autocomplete='off' size='30' name='rLname' value='$rLname' class='regReq' type='text'>
				
			</td>
		</tr>");
		
		echo("
		<tr>
			<td colspan='3' class='cust'>
				Address:<span class='required'>*</span>
				<span class='redtext'>$raddressmsg</span><br>
				<input autocomplete='off' size='60' value='$rAddress' class='regReq'  name='rAddress' type='text'>
			
			</td>
		</tr>
		<tr>
			<td class='cust' valign='top'>
				City:<span class='required'>*</span>
				<span class='redtext'>$rcitymsg</span><br>
				<input autocomplete='off' class='regReq' size='30' value='$rCity' name='rCity' type='text'>
				
			</td>
			<td width='25'></td>	
			<td class='cust'>State:<span class='required'>*</span>
				<span class='redtext'>$rstatemsg</span><br>
				<select name='rState' class='regReq'>
				
				");
			
				stateList($rState);				
				
		
		echo("</select>
			</td>
		</tr>
		<tr>
			<td colspan='3' class='cust'>
				Zipcode:<span class='required'>*</span>
				<span class='redtext'>$rzipcodemsg</span><br>
				<input autocomplete='off' class='regReq' value='$rZipcode' size='10' name='rZipcode' type='text' maxlength='10'>
			</td>
		</tr>");
	
	
	echo("<tr>
			<td colspan='3' class='cust'>
				E-mail:<span class='required'>*</span>
				<span class='redtext'>$remailmsg</span><br>
				<input autocomplete='off' class='regReq' size='50' value='$rEmail' name='rEmail' type='text'>
			</td>
		</tr>
		
		<td class='cust'>
				Daytime Phone:<br>
				<input autocomplete='off' class='reg' value='$rPhone' size='13'  name='rPhone' type='text' maxlength='13'>
			</td>
		</tr>
		
		<tr>
			<td colspan='3'><hr class='thin'></td>
		</tr>
		
		<tr>
			<td colspan='3' style='margin-left:-10px'>
			<span class='sectHead'>
				Co-Registrant's Information
			</span>
			</td>
		</tr>
		<tr>
			<td class='cust'>
				First Name:<br>
				<input autocomplete='off' class='reg' size='30' name='crFname' value='$crFname' type='text'>
				<span class='redtext'>$crFnamemsg</span>
			</td>
			<td width='25'></td>
			<td class='cust'>
				Last Name:<br>
				<input autocomplete='off' class='reg' size='30' name='crLname' value='$crLname' type='text'>
				<span class='redtext'>$crLnamemsg</span>
			</td>
		</tr>
		<tr>
			<td colspan='3' class='cust'>
				Address: <input autocomplete='off' type='checkbox' name='sameaddress' value='$sameaddress'>
				<span style='font-size:10px'>(same as registrant's address)</span><br>
				<input class='reg' size='60' value='$crAddress' name='crAddress' type='text'>
			</td>
		</tr>
		<tr>
			<td class='cust' valign='top'>
				City:<br>
				<input autocomplete='off' class='reg' size='30' value='$crCity' name='crCity' type='text'>
			</td>
			<td width='25'></td>
			<td class='cust'>
				State:<br>		
				<select class='reg' name='crState' value='$crState'>");
										
				stateList($crState);
																
				echo("</select>
			</td>
		</tr>
		<tr>
			<td class='cust' colspan='3'>
				Zipcode:<br>
				<input autocomplete='off' class='reg' value='$crZipcode' size='10'  name='crZipcode' type='text' maxlength='10'>
			</td>
		</tr>
		<tr>
			<td class='cust' colspan='3'>
				E-mail:<br>
				<input autocomplete='off' class='reg' size='30' value='$crEmail' name='crEmail' type='text'>
				<span class='redtext'>$crEmailmsg</span>
			</td>
		</tr>
		<tr>
			<td class='cust' colspan='3'>
				Daytime Phone:<br>
				<input autocomplete='off' class='reg' value='$crPhone' size='13'  name='crPhone' type='text' maxlength='13'>
			</td>
		</tr>
		<tr>
			<td colspan='3'><hr class='thin'></td>
		</tr>
	");	


	echo("	
		<tr>
			<td colspan='3'><hr class='thin'></td>
		</tr>
		
		<tr>
			<td colspan='3'>
				<span class='sectHead'>Create Registry Password</span>
				<span style='font-size:10px'>(to be used to update your registry)</span>
				$pwordmsg
			</td>
		</tr>
		<tr>
			<td class='cust'>
				Password: <span class='required'>*</span><br>
				<input autocomplete='off' class='regReq' size='30' name='pword' type='password'>
			</td>
			<td width='3'></td>
			<td class='cust'>
				Confirm Password:<span class='required'>*</span><br>
				<input autocomplete='off' class='regReq' size='30' name='pwordConfirm' type='password'>
			</td>
		</tr>

	
	
	<tr>
		<td colspan='3'><hr class='thin'></td>
	</tr>
	
	<tr>
		<td><input type='submit' value='Create Registry'></td>
		<td>
		<input type='hidden' name='regID' value='$regID'>
		<input type='hidden' name='action' value='$action'>
		<input type='hidden' name='itemID' value='$itemID'>
		</td>
	</tr>
	</tbody>
	</table>
	
	</form>	");
 	
 	//end showForm Function
 }

function eventList($event) {
	
	//$event = $ev;
	
	$events	= array(
					"a"		=>"Anniversary",
					"bbs"	=>"Baby Shower",
					"bd"	=>"Birthday",
					"brs"	=>"Bridal Shower",
					"o"		=>"Other",
					"w"		=>"Wedding"
				);

	echo "<option value=''>Event Type*</option>";
	
	foreach($events as $k=>$v){
		
		$selected = $k == urldecode($event)?"selected='selected'":"";
		
		echo "<option value='$k' $selected>$v</option>";
	
	}
	
}

function showSplash(){
//shows main wedding registry page with 3 options
echo("

<div class=\"maincontent\">
	
	<div id=\"sp1\"  class=\"splashpoint\" style=\"top:20px;width:200px;>\">
				Create Your Registry:
	</div>
	
	<div class=\"splashpoint\" style=\"top:20px;left:210px;\">
		<a class=\"sectHead\" href=\"giftRegistry_classic.php?action=newReg\">Planning a special event?<br>Create a gift registry for your special occasion and find great gifts from As You Like it Silver Shop's inventory.</a>
	</div>
	
	<br clear=\"all\">
	
	<div id=\"sp2\"  class=\"splashpoint\" style=\"top:140px;width:200px;\">
		Login to your Registry:
	</div>
	
	<div class=\"splashpoint\" style=\"top:140px;left:210px;\">
		<a class=\"sectHead\" href=\"giftRegistry_classic.php?action=showLogin\">Already registered with As You Like It Silver Shop?<br>Click here to update your contact information, add items to your existing registry, and see what's been purchased. </a>
	</div>
	
	<br clear=\"all\">
	
	<div id=\"sp3\" class=\"splashpoint\"  style=\"top:260px;width:200px;\">
		Shop your friend's Registry:
	</div>
	
	<div class=\"splashpoint\" style=\"top:260px;left:210px;\">
		<a class=\"sectHead\" href=\"giftRegistry_classic.php?action=showSearch\">Know someone registered with As You Like it Silver Shop?<br>Click here to search our registry.</a>
	</div>

</div>");

}

function stateList($state = ""){
	
	$stateArray = array(
						"NO" => "",
						"AL" => "Alabama",
						"AK" => "Alaska",
						"AZ" => "Arizona",
						"AR" => "Arkansas",
						"CA" => "California",
						"CO" => "Colorado",
						"CT" => "Connecticut",
						"DE" => "Delaware",
						"DC" => "District of Columbia",
						"FL" => "Florida",
						"GA" => "Georgia",
						"HI" => "Hawaii",
						"ID" => "Idaho",
						"IL" => "Illinois",
						"IN" => "Indiana",
						"IA" => "Iowa",
						"KS" => "Kansas",
						"KY" => "Kentucky",
						"LA" => "Louisiana",
						"ME" => "Maine",
						"MD" => "Maryland",
						"MA" => "Massachusetts",
						"MI" => "Michigan",
						"MN" => "Minnesota",
						"MS" => "Mississippi",
						"MO" => "Missouri",
						"MT" => "Montana",
						"NE" => "Nebraska",
						"NV" => "Nevada",
						"NH" => "New Hampshire",
						"NJ" => "New Jersey",
						"NM" => "New Mexico",
						"NY" => "New York",
						"NC" => "North Carolina",
						"ND" => "North Dakota",
						"OH" => "Ohio",
						"OK" => "Oklahoma",
						"OR" => "Oregon",
						"PA" => "Pennsylvania",
						"RI" => "Rhode Island",
						"SC" => "South Carolina",
						"SD" => "South Dakota",
						"TN" => "Tennessee",
						"TX" => "Texas",
						"UT" => "Utah",
						"VT" => "Vermont",
						"VA" => "Virginia",
						"WA" => "Washington",
						"WV" => "West Virginia",
						"WI" => "Wisconsin",
						"WY" => "Wyoming"
					);
	foreach( $stateArray as $k => $v){

	    $selected = "";

		if( $state == $k){
			$selected = "selected";
		}
		
		$options.="<option value='$k' $selected>$v</option>";
		
	}
	
	echo $options;		
}

function storeLogin(){
	if($_POST){
		setcookie("userData",$rEmail.":".$regID,time()+3600);
	}

}

function yearSelect($y){
		
		$year= date("Y");
		$maxy = $year+3;	
		
		for($i=$year;$i<=$maxy;$i++){
			$selected=($i==$y?"selected=\"selected\"":"");
			echo "<option value=\"$i\" $selected>$i</option>";
		}
		
}


ob_flush();
?>
