<?php 

//if (!$conn_id = @ftp_connect('69.36.171.242')) {
 if(!$conn_id = @ftp_connect('69.36.170.34')){
  die("FTP connection failed."); 
 }
//} 
//6Ufus2004w? or 6Ufus2004g
//password changed 3/18/14 to doriE063005w
//password changed 2/24/15 to KAd65DO6
//entire ftp changed 8/5/16 due to WestHost migration error
//new user is admin@asyoulikeitsilvershop.com  ayl1ss3033Mag

// login with username and password
	if (!$login_result = @ftp_login($conn_id,'admin@asyoulikeitsilvershop.com','ayl1ss3033Mag')) { die("FTP LOG IN FAILED"); }
	
	ftp_pasv($conn_id,TRUE);

?>