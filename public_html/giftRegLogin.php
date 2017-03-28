<?php 
ini_set("display_errors",1);

include("/home/asyoulik/connect/mysql_pdo_connect.php");

extract($_POST);

//echo $username;

LogUser($username,$password);

function checkLoginInfo($username,$password){
	
	global $db;
	$loginID=0;
	
	$username=urldecode($username);
	$password=urldecode($password);

	$query=$db->prepare("SELECT id FROM weddingRegistries where remail=:username and pword=:password LIMIT 1");
	$query->bindParam(":username",$username,PDO::PARAM_STR);
	$query->bindParam(":password",$password,PDO::PARAM_STR);

	$query->execute();
		
	$result = $query->fetchAll();

	foreach($result as $row){
		if($row['id']){
			$loginID= $row['id'];
		}
	}
	
	return $loginID;

}

function LogUser($username,$password){

	$success= checkLoginInfo($username,$password);
	//echo "Username: $username<br>Password: $password<br>RegID: $success";
	
	if($success){
	  	setcookie("aylissWeddingReg", $success, time()+3600);
    }

	echo $success;

}

?>