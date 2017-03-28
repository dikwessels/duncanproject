<?php
 include("/connect/mysql_connect.php");
 extract($_POST);
 extract($_GET);
 
 if(!$page){
	$page=$_SERVER['SCRIPT_NAME'];
 }
 if(!$referrer){
	$referrer=$_SERVER['HTTP_REFERER'];
  }

 $query="INSERT INTO  `tblWebsiteHits` (`id`,`hitTime`,`hitPage`,`refererPage`) VALUES ('', NOW( ) ,'$page','$referrer')";

 $result=mysql_query($query);
// echo "$currentpage referred to by $ref";
?>