<?php 
extract($_GET);
include("/connect/mysql_connect.php");

if($action=='f'){
	$regID=getRegistryID($email);
}
else
{
	$regID=createRegistry($wMonth,$wDay,$wYear,$rFname,$rLname,$rAddress,$rCity,$rState,$rZipcode,$rPhone,$rEmail,$crFname,$crLname,$crAddress,$crCity,$crState,$crZipcode,$crPhone,$crEmail,$bwedship,$awedship,$pword,$sa);
}

echo("RegistryID:$regID:endData");
	

	
function createRegistry($wMonth,$wDay,$wYear,$rFname,$rLname,$rAddress,$rCity,$rState,$rZipcode,$rPhone,$rEmail,$crFname,$crLname,$crAddress,$crCity,$crState,$crZipcode,$crPhone,$crEmail,$bwedship,$awedship,$pword,$sa){
	
	if($sa){
	 $crAddress=$rAddress;
	 $crCity=$rCity;
	 $crState=$rState;
	 $crZipcode=$rZipcode;
	}
	//only for store created registries
	$pword="password";
	
	$query="INSERT INTO weddingRegistries(wedmonth,wedday,wedyear,rfname,rlname,raddress,rcity,rstate,rzipcode,
 	  		rphone,remail,crfname,crlname,craddress,crcity,crstate,crzipcode,crphone,cremail,bwedship,awedship,pword)";
 	  		$query.=" VALUES(\"$wMonth\",\"$wDay\",\"$wYear\",\"$rFname\",\"$rLname\",\"$rAddress\",\"$rCity\",\"$rState\",\"$rZipcode\",\"$rPhone\",\"$rEmail\",";
 	  		$query.="\"$crFname\",\"$crLname\",\"$crAddress\",\"$crCity\",\"$crState\",\"$crZipcode\",\"$crPhone\",\"$crEmail\",\"$bwedship\",\"$awedship\",\"$pword\")";
 	  	
	  	$result=mysql_query($query);
	  	$success=mysql_affected_rows();
	  
	  	if($success>0){
	  		 $loginInfo=getRegistryID($rEmail);
	  	}

	  	return $loginInfo;

}

function getRegistryID($email){
	$query="SELECT id FROM weddingRegistries WHERE remail=\"$email\" LIMIT 1";
		$result=mysql_query($query);
		$row=mysql_fetch_assoc($result);
	return $row[id];
}


?>
