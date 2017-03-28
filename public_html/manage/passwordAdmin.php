<html>

<head>
<title>As You Like It Silver Shop</title>
<link rel="stylesheet" type="text/css" href="managestyle.css">
<style type="text/css">

table{
border: 1px solid aaa;
padding:10px 10px 20px 10px;
}

table td{
border-style: none;
}
td.label{
border:0px none transparent;
text-align: left;
color: #781212;
font-family: 'Century Gothic', 'Franklin Gothic', 'Trade Gothic', 'Capitals', Helvetica, sans-serif; 
font-size: 14px;
padding: 2px 4px 2px 4px;
}

td.tablehead{
border-style: none;
padding: 4px 2px 4px 2px;
font-size: 14px;
color: inherit;
}

body{
padding-top: 20px;
}

input{
border:1px solid #781212;
padding-left: 2px;
}

</style>

</head>
<?

/* extract post data and init variables */
extract($_POST);
$username='';
$accesscode='';
$msg='';
$focusOn='';


/* check to see if data was posted and check form for proper data */
 if($_POST){
  if($_POST['username']){
    $username=$_POST['username'];
   if($_POST['accesscode']=='tasmo40'){
     $accesscode=$_POST['accesscode'];
    if($_POST['newpassword']){
	    
     if($_POST['newpassword']==$_POST['confirmpassword']){
     
       /* connect to the database */
       include("/home/asyoulik/connect/mysql_connect.php");
            
       /*update the password */
       $query="UPDATE users SET password='$newpassword', temp=0 WHERE username='$username'";
       $result=mysql_query($query);
       
       if(mysql_affected_rows()==1){
         $status=4;
       }
     }
     
     else{$status=3;}
    }
    else{$status=2;}
   }
   else{$status=1;$managepassword='';}
  }
  else{$status=5;}
  }
  else{
   $status=0;
  }
  
  switch ($status){
  case 0:
  $msg="To change the management password, please specify the following:";
  $focusOn="username";
  break;
  case 1:
  $msg="Incorrect access code.";
  $focusOn="accesscode";
  break;
  case 2:
  $msg="Please specify a new password.";
  $focusOn="newpassword";
  break;
  case 3:
  $msg="Confirmed password does not match new password.";
  $focusOn="newpassword";
  break;
  case 4:
  $msg="Password for $username successfully updated.";
  $username='';
  $accesscode='';
  $focusOn="username";
  break;
  case 5:
  $msg="Please specify a username.";
  $focusOn="username";
  break;
  } 
  
  echo("<body onLoad=document.forms[0].$focusOn.focus()>");
  
  echo("<form method=post><table width=450 border=1 align=center><tr><td colspan=2>");
  echo("<tr><td class=tablehead colspan=2>$msg</td></tr>");
  echo ("<tr><td class=label>Username:</td><td><input type=text name=username value=$username></td></tr>");
  echo ("<tr><td class=label>Access Code:</td><td><input type=password name=accesscode value=$accesscode></td></tr>");
  echo ("<tr><td class=label>New Password:</td><td><input name=newpassword type=password></td></tr>");		
  echo ("<tr><td class=label>Confirm New Password:</td><td><input name=confirmpassword type=password></td></tr>");
  echo ("<tr><td align=center colspan=2><input type=submit value='Update'></td></tr>");
 /* echo("<tr><td colspan=2><font color=red>NOTE: if a blank screen comes up after clicking 'Update' click the Refresh button.</font></td></tr>");*/
  echo("</table></form>");
?>



</body>

</html>
