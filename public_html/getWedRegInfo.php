<?php 


/*
Algorithm

User will get to this page 1 of two ways:

	1) they click a create wedding registry link, page will get a $_GET variable action = 'newReg'
	2) they click an add to wedding registry link from the search pages, page will get a $_GET variable of the item id and the action='addItem'

	If the item id isn't set, then it is a new registration


*/


ob_start();

include("connect/mysql_connect.php");
//ini_set("display_errors","1");

extract($_POST);
extract($_GET);
    
if(!$regID){$regID = $_COOKIE['aylissWeddingReg'];}

main($regID);

function is_DateValid($m,$d,$y){

 $isvalid=1;
 	switch ($m) {
       
 	case "":
 		$isvalid=0;
 		break;
 	
 	case "2":
 		if($d>"29" || $d=="" || $y==""){$isvalid=0;}
  		break;
   
    case "4" || "6" || "9" || "11":
 		if($d>"30" || $d=="" || $y==""){$isvalid=0;}
 		break; 
 
 	}

	return $isvalid;
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

//main sub routine

function main($regID){
	
	$acctcreated=0;
	$wDateMsg="";
	$remailmsg="";
	$rfnamemsg="";
	$rlnamemsg="";
	$raddressmsg="";
	$rcitymsg="";
	$rstatemsg="";
	$rzipcodemsg="";
	$pwordmsg="";
	$requiredMsg="";


  	extract($_GET);
  	extract($_POST);

	if($update){
		
		$valid=1;
	 		if(!is_DateValid($wedmonth,$wedday,$wedyear)){
 	 			 $wDateMsg ="Please enter a valid date for your wedding.";
 	 	 		$valid=0;
 	 		}
 	 		else{$wDateMsg="";}
 	 	 	
 	 	 	if(!$event){
 	 	 	//echo $event;
 	 	 		$eventmsg="Required";
 	 	 		$valid=0;
 	 	 	}
 	 	 	
 	 		if(is_valid_email($remail)==0){
 	  			$remailmsg="Please enter a valid email address.";
 	  			$valid=0;
 	  		}
 	 		else{$remailmsg="";}
 	  
 	  
 	 		if(!$rfname || $rfname==""){
 	 			$rfnamemsg="Required";
 	 			$valid=0;
 	 		}
 	 		else{$rfnamemsg="";}
        
     
     		if(!$rlname || $rlname=="" ){
       			$rlnamemsg="Required";//"Please enter your last name.";
	    		$valid=0;
     		}
        	else{$rlnamemsg="";}
        	
        		 
	      
		if(!$raddress || $raddress==""){
		$raddressmsg="Required";//"Please enter your address.";
		$valid= 0;
		}
     	else{$rAddressmsg="";}
     	
     	if(!$rcity || $rcity==""){
     	$rcitymsg="Required";//"Please enter your city.";
     	$valid=0;
     	}
     	else{$rcitymsg=="";}
     	
     	if(!$rstate || $rstate=="" || $rstate=="NO"){
     	$rstatemsg="Required";//"Please enter a state.";
     	$valid=0;
     	}
  		else{$rstatemsg="";}
  		
  		if(!$rzipcode || $rzipcode==""){
  		$rzipcodemsg="Required";//"Please enter your zipcode.";
  		$valid =0;
  		}
  		else{$rzipcodemsg="";}
		
		
		
     if($valid){
       // update registry entry
 	 		 	 		
 	$success= updateRegistry($regID,$event,$wedmonth,$wedday,$wedyear,$rfname,$rlname,$raddress,$rcity,$rstate,$rzipcode,$rphone,$remail,$crfname,$crlname,$craddress,$crcity,$crstate,$crzipcode,$crphone,$cremail,$bwedship,$awedship);	
 	  		
 	if($success>0){
 	 //$notice="<!--okay--><span id=\"updateStat\" style=\"position:absolute;top:-20px;\" class=\"sectHead\">Your profile has been updated.</span>";
	  showForm($regID,$notice);
	  }
	 else{
	     $notice = "<!--error-->";
	     echo "<script type='text/javascript'>console.log('". mysql_error()."');</script>";
	     
	     //<span id=\"updateStat\" style=\"position:absolute;top:-24px;\" class=\"redtext\">An internal error occurred, please try again.</span>";
	     showForm($regID,$notice);
	 }
  	}
  		
	else{
		$notice="<!--incomplete-->";//<span style=\"position:absolute;top:-24px;\" class=\"redtext\">Please specify all fields marked with an asterik</span>";
 	  	 showForm($regID,$notice,$rfnamemsg,$wDatemsg,$rlnamemsg,$remailmsg,$raddressmsg,$rcitymsg,$rstatemsg,$rzipcodemsg,$pwordmsg,$eventmsg);
 	  	}
 	  	
 	}
 	  	
	else{
		showForm($regID,""	,"","","","","","","","","","");
		//showForm($regID);
	}
	
	

//end main 
}




function newCustomer($email){
		$isnew=1;
		$query="SELECT remail FROM weddingRegistries WHERE remail=\"$email\"";
		$result=mysql_query($query);
		$n=mysql_num_rows($result); 
		if($n>0){$isnew=0;}
   	 
    return $isnew;
  	
  	//end newCustomer
}

function updateRegistry($id,$event,$wMonth,$wDay,$wYear,$rFname,$rLname,$rAddress,$rCity,$rState,$rZipcode,$rPhone,$rEmail,$crFname,$crLname,$crAddress,$crCity,$crState,$crZipcode,$crPhone,$crEmail,$bwedship,$awedship){
    
    $query="UPDATE weddingRegistries SET event=\"$event\",wedmonth=\"$wMonth\",wedday=\"$wDay\",wedyear=\"$wYear\",rfname=\"$rFname\",
			rlname=\"$rLname\",raddress=\"$rAddress\",rcity=\"$rCity\",rstate=\"$rState\",rzipcode=\"$rZipcode\",
 	  		rphone=\"$rPhone\",remail=\"$rEmail\",crfname=\"$crFname\",crlname=\"$crLname\",craddress=\"$crAddress\",
 	  		crcity=\"$crCity\",crstate=\"$crState\",crzipcode=\"$crZipcode\",crphone=\"$crPhone\",cremail=\"$crEmail\",
 	  		bwedship=\"$bwedship\",awedship=\"$awedship\" WHERE id=$id"; 
 	  			  	
    $result=mysql_query($query);
    $success=mysql_affected_rows();

    return $success;

}


 	
function showConfirmation($email,$registryLink,$misc){
    
    $pattern="";
    $brand="";
    $item="";
    
    
    extract($_POST);
    
    	echo "<table><tbody style='text-align:left'>
   	
   	
   		<tr>
   			<td colspan='3'>
   				<span class='sectHead'>Wedding Registry Created</span><br>
   				Your wedding registry has been created and a confirmation email has been sent to $email.<br>
   				To start adding items to your registry, click here:<a href='$registryLink'>$registryLink</a><br>
   				Cookie is $misc
   				
   				
   			</td>
   		</tr>
   		</table>";
   		
   	
 	
 	//end showConfirmation
 	
 	}
 	
 function showForm($regID,$notice,$rfnamemsg,$wDatemsg,$rlnamemsg,$remailmsg,$raddressmsg,$rcitymsg,$rstatemsg,$rzipcodemsg,$pwordmsg,$eventmsg){
    
    
    
    $pageheader="Please enter your contact information.";	
	
	/*
	weddingRegistries table
	id 
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
	
	
   	$query="SELECT * FROM weddingRegistries where id = $regID";
   	$result=mysql_query($query);
   	$row=mysql_fetch_assoc($result);
   	extract($row);
   	
   
   	echo "$notice
   		<form style='margin-left:0px;width:740px;height:425px;font-size:12px;padding-left:20px;'>
   		<div class='row'>
   		<div class='cell fiveColumns'>
   		<span class='sectHead'>Event:</span>
   		<span class='required'>*</span>
   		<select class='small-input' id='ev' name='event'>
	   		<option value=''>Please Select</option>";

	
   		$eventArray=array(
   				"a"=>"Anniversary",
   				"bbs"=>"Baby Shower",
   				"bd"=>"Birthday",
   				"brs"=>"Bridal Shower",
   				"o"=>"Other",
   				"w"=>"Wedding"
   				);
		
		/*$catArray = array("BS"=>"Baby Silver","CP"=>"Cleaning Products",
		"F"=>"Flatware","FCS"=>"Complete Sets","XM"=>"Christmas Ornaments",
		"H"=>"Hollowware","J"=>"Jewelry","SP"=>"Serving Pieces","STP"=>"Storage Products");
		*/
		
		
	foreach($eventArray as $k=>$v){
		$selected=$k==urldecode($event)?"selected='selected'":"";
		echo "<option value='$k' $selected>$v</option>";
	}


   	echo "
   	</select>
   	</div>
   	<div class='cell fourColumns'>			
   		<span class='sectHead'>Date:</span>
   		<span class='required'>*</span>
   		<select class='mini-input' name='wMonth' id='wm'>";
   		
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
			$selected=($i==$wedmonth?"selected='selected'":"");
			echo "<option value='$i' $selected >$monthArray[$i]</option>";
		}
				

echo "</select>
</div>
	<div class='cell threeColumns'>
		<select class='mini-input' name='wDay' id='wd'>";
		
		$max=31;	
		$m=$wedmonth;
		if($m==4||$m==9||$m==6||$m==11){
			$max=30;
		}
		
		if($m==2){
			$max=29;
		}
							
		$d=$wedday;
		for($i=0;$i<=$max;$i++){
		 	$v=($i==0?"Day":$i);
			$selected=($i==$d?"selected='selected'":"");
			echo "<option value='$i' $selected>$v</option>";
		}
				
		echo "</select>
				</div>
				<div class='cell threeColumns'>
					<select class='mini-input' name='wYear' id='wy'>
						<option value='0'>Year</option>";
							
			$year= date("Y");
			$maxy=$year+3;	
			$y=$wedyear;
		
			for($i=$year;$i<=$maxy;$i++){
				$selected=($i==$y?"selected='selected'":"");
				echo "<option value='$i' $selected>$i</option>";
			}					
			
			echo "</select>
				<span class='warning'>$wDateMsg</span>
			</div>
			</div>
			</div>";
   	
   echo "	
   <div class='row'>
   	<div class='cell eightColumns'>
   			<div class='cell sixteenColumns'>
				<span class='sectHead'>Registrant's Information</span>
				</div>
		<div class='row'>
			<div class='cell eightColumns'>First Name:<span class='required'>*</span>
					<span class='redtext'>$rfnamemsg </span><br>
					<input id='rf' name='rfname' value='$rfname' class='small-input'>
			</div>
			<div class='cell eightColumns'>Last Name:<span class='required'>*</span>
					<span class='redtext'>$rlnamemsg </span><br>
					<input size='20' id='rl' name='rlname' value='$rlname' class='small-input'>
			</div>
		</div>
		<div class='row'>
			<div class='cell sixteenColumns'>
				Shipping Address:<span class='required'>*</span>
					<span class='redtext'>$raddressmsg </span><br>
					<input id='ra' value='$raddress' class='medium-input'  name='raddress'>
			</div>	
		</div>
		<div class='row'>
			<div class='cell sixteenColumns'>
			City:<span class='required'>*</span>
					<span class='redtext'>$rcitymsg </span><br>
					<input class='small-input' value='$rcity' id='rc' name='rcity'>
			</div>
			</div>
			<div class='row'>
			<div class='cell eightColumns'>
			State:<span class='required'>*</span>
					<span class='redtext'>$rstatemsg</span><br>
					<select id='rs' name='rstate' class='mini-input'>
					";
					
			global $state;
			$state=$rstate;
			
			include($_SERVER['DOCUMENT_ROOT']."/includes/state_select.php");
				
			echo "</select>
			</div>
			<div class='cell eightColumns'>
			Zipcode:<span class='required'>*</span>
					<span class='redtext'>$rzipcodemsg</span><br>
					<input class='mini-input' value='$rzipcode' size='10' id='rz' type='text' maxlength='10'>
			</div>
			</div>
			
			<div class='row'>
			<div class='cell eightColumns'>
				E-mail:<span class='required'>*</span>
					<span class='redtext'>$remailmsg</span><br>
					<input class='small-input' value='$remail' id='re' name='remail'>
			</div>
			<div class='cell eightColumns'>
				Daytime Phone:<br>
				<input class='small-input' value='$rphone' size='13' id='rp'  name='rphone' maxlength='13'>
			
			</div>
			
			</div>
			
			</div>
			";
			
			/*

			State:<br>		
				<select class='mini-input' id='crs' name='crstate' value='$crstate'>";
									
			include($_SERVER['DOCUMENT_ROOT']."/includes/state_select.php?state=$crstate");
																
			echo "
				</select>
				</div>
			*/			
		
		echo"
		<div class='cell eightColumns'>
		 <div class='cell sixteenColumns'>
		 				<span class='sectHead'>
					Co-Registrant
					</span>
		 </div>
		<div class='row'>
			<div class='cell eightColumns'>
			First Name:<br>
					<input class='small-input' id='crf' name='crfname' value='$crfname'>
					<span class='redtext'>$crFnamemsg</span>
			</div>
			<div class='cell eightColumns'>
			Last Name:<br>
					<input class='small-input' id='crl' name='crlname' value='$crlname'>
					<span class='redtext'>$crLnamemsg</span>

			</div>
		</div>
	
		<div class='row'>
		<div class='cell sixteenColumns'>
			Address: <br><input class='medium-input'  value='$craddress' id='cra' name='craddress' >
		</div>
		</div>

		<div class='row'>
			<div class='cell sixteenColumns'>City:<br>
					<input class='medium-input' size='20' value='$crcity' id='crc' name='crcity'>
			</div>
		</div>
		<div class='row'>
		
			<div class='cell eightColumns'>
			State:<br>		
					<select class='mini-input' id='crs' name='crstate' value='$crstate'>";
	
			$state=$crstate;
			include($_SERVER['DOCUMENT_ROOT']."/includes/state_select.php");
		
			echo "</select>		
			</div>
			
			<div class='cell eightColumns'>
			Zipcode:<br>
					<input class='mini-input' value='$crzipcode' size='10' id='crz' name='crzipcode' maxlength='10'>
			</div>
		
		</div>
		
		<div class='row'>
			<div class='cell eightColumns'>
				E-mail:
				<br>
				<input class='small-input' value='$cremail' id='cre' name='cremail' >
				<span class='redtext'>$crEmailmsg</span>
			</div>
			<div class='cell eightColumns'>
			Daytime Phone:<br>
					<input class='mini-input' value='$crphone' size='13' id='crp'  name='crphone' maxlength='13'>
			</div>
		</div>
	</div>
		</div>	
					
<div class='row'>
<div class='cell sixteenColumns'>
	<input type='button' value='Save Changes' onclick='javascript:changeMainContent(1,$regID)'>
</div>
</div>
</div>

</form>



";
 
 /*
 include this after finishing the testing on the sale form
  		<tr>
			<td colspan='3\">
				<span class=\"sectHead\">Shipping Preferences</span>
			</td>
		</tr>
		<tr>
			<td>
				Before wedding date,<span class=\"required\">*$bwedmsg</span><br>
				mail gifts to:<br>
				<select id=\"bw\" name=\"bwedship\" class=\"reg\">");
				readfile("http://www.asyoulikeitsilvershop.com/includes/ship_select.php?s=$bwedship");
				echo("	
				</select>
			</td>
			<td width=\"25\"></td>
			<td>
				After wedding date, <span class=\"required\">*$awedmsg</span><br>
				mail gifts to:<br>
				<select id=\"aw\" value=\"$awedship\" name=\"awedship\" class=\"reg\">");
				readfile("http://www.asyoulikeitsilvershop.com/includes/ship_select.php?s=$awedship");
					echo("
				</select>
			</td>
		</tr>
 */
 	
 	//end showForm Function
}

/*<script type=\"text/javascript\" language=\"javascript\">
function updateProfile(regID){
	if(regID){
	
		//mainContent.innerHTML = \"Updating profile...\";
	 	var wedmonth=document.getElementById('wm').value;
	  	var wedday=document.getElementById('wd').value;
	  	var wedyear=document.getElementById('wy').value;
		var rfname=document.getElementById('rf').value;
		var rlname=document.getElementById('rl').value;
		var raddress=document.getElementById('ra').value;
		var rcity=document.getElementById('rc').value;
		var rstate=document.getElementById('rs').value;
		var rzipcode=document.getElementById('rz').value;
		var rphone=document.getElementById('rp').value;
		var remail=document.getElementById('re').value;
		var bwedship=document.getElementById('bw').value;
		var awedship=document.getElementById('aw').value;
		var cremail=document.getElementById('cre').value;
		var crphone=document.getElementById('crp').value;
		var sameaddress=document.getElementById('sa').value;
		
		if(!sameaddress){
			var crfname=document.getElementById('crf').value;
			var crlname=document.getElementById('crl').value;
			var craddress = document.getElementById('cra').value;
			var crcity=document.getElementById('crc').value;
			var crstate=document.getElementById('crs').value;
			var crzipcode=document.getElementById('crz').value;
		}

		
		
		var strQuery=\"wedmonth=\"+wedmonth+\"&wedday=\"+wedday+\"&wedyear=\"+wedyear+\"&rfname=\"+rfname+\"&rlname=\"+rlname+\"&raddress=\"+raddress+\"&rcity=\"+rcity+\"&rstate=\"+rstate+\"&rzipcode=\"+rzipcode+\"&rphone=\"+rphone+\"&remail=\"+remail;
		
		if(!sameaddress){
			strQuery=strQuery+\"&crfname=\"+crfname+\"&crlname=\"+crlname+\"&craddress=\"+craddress+\"&crcity=\"+crcity+\"&crstate=\"+crstate+\"&crzipcode=\"+crzipcode+\"&cremail=\"+cremail+\"&awedship=\"+awedship+\"&bwedship=\"+bwedship;
		
		}
		
			strURL=\"/getWedRegInfo.php?update=1&\"+strQuery;
		
		}
		
		else{
			strURL=\"/getWedRegInfo.php\";
	 	}
	  alert(strURL);
}
</script>*/
ob_flush();
 	
?>




