<?
include("/home/asyoulik/connect/mysql_connect.php");

$ac=$HTTP_COOKIE_VARS['accessLevel'];

if ($ac<2) {
	echo "You are not authorized to view this page";
	exit;
 	}
if ($setLevel) { mysql_query("UPDATE users set groupNumber=$setLevel where username='$name'"); }
if ($HTTP_GET_VARS['delete']) {mysql_query("DELETE from users where username='$delete'");}
if ($HTTP_GET_VARS['username']) {

	srand((double) microtime() * 1000000);
	for ($i=0;$i<7;$i++) {
		$num=-1;
		while ($num<0 || ($num>57 && $num<64) || ($num>90 && $num<97)) { 
			$num=rand(48,122);
			}
		$password.=chr($num);
		}	

mysql_query("INSERT into users(username,email,password,temp,groupNumber) values ('$username','$email','$password','1',$groupNumber)");
	if ($HTTP_GET_VARS['email']) {
	echo $email;
		mail($email,'Password',"Your username is $username.\r\nYour temporary password is $password.\r\nYou will have to change this password when you first log in to the control panel.\r\nClick the follwoing link to access the control panel- http://neworleansmarriott.com/management/control_panel.php\r\n",'From: New Orleans Marriott Administration');
		mail('stoffak@bellsouth.net','Password',"The following password has been generated for $email.\r\nUsername: $username.\r\nPassword: $password.",'From: New Orleans Marriott Administration');
		}
	}
?>
<HTML>
<HEAD>
<TITLE>Password Manager</TITLE>
<link rel="stylesheet" type="text/css" href="http://www.neworleansmarriott.com/tent/styles/red.css">
<script language=javascript>
function setAccess(s,name) {
s=s.options[s.selectedIndex].value
location="passwordManager.php?name="+name+"&setLevel="+s
}
</script>
</HEAD>
<BODY BGCOLOR="#FFFFFF" TEXT="#000000" LINK="#0000FF" VLINK="#800080" ><FORM>
<table cellspacing=4px cellpadding=2px width=500 ><tr><td colspan=3>
<? if ($ac>2) {?>

To create a new user simply fill in a username.  If you fill in the email address, an email with the username and a temporary password will be sent. </td></tr>


<tr><td>User Name</td><td>Email</td></tr>
<tr><td><input name=username><select name=groupNumber><option value=1>1<option value=2>2</select></td><td><input name=email></td><td rowspan=2><input type=submit value=Submit class=submit><? }  else  { echo "You do not have permission to add users.";}?></td></tr></table><table cellspacing=4px cellpadding=2px border=1 width=500 ><tr><td colspan=2 align=center>Current Users</td></tr></form><form>
<?

$query=mysql_query("SELECT username,email,groupNumber from users order by username");
while ($row=mysql_fetch_assoc($query))  {
	echo "<tr><td>$row[username]</td><td><a href='mailto:$row[email]'>$row[email]<a></td>";
if ($accessLevel==3) {
	echo"<td><select onChange=setAccess(this,'$row[username]')>";
	for ($i=1;$i<=$accessLevel;$i++) {
	echo "<option value=$i";
	if ($i==$row[groupNumber]) {echo " selected"; }
	echo ">$i";
	}
	echo "</select></td>";

	echo "<td><a href='passwordManager.php?delete=$row[username]'>delete</a></td></tr>";
	}
	}
?>
</td></tr></form></table>
</BODY>
</HTML>
