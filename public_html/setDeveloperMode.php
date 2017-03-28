<?php 
/**** 
	
this sets a cookie that tells the website it is in test/developer mode, so certain functionality will only be available to the developer 
	
****/

extract($_GET);

//set "developer_mode" cookie to 'on' or 'off'
//cookie will expire in one hour

setcookie("developer_mode",$mode,time()+3600);	

?>