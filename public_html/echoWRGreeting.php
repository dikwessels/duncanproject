<?php

extract($_GET);
include("/home/asyoulik/connect/mysql_pdo_connect.php");

$stmt = "SELECT * FROM weddingRegistries WHERE id=$regID";

$query = $db->prepare($stmt);

$query->execute();

$result = $query->fetchAll();

$row = $result[0];

$email = $row[remail];

	echo"<span style=\"font-size:20px;color:#444444;\" class=\"sectHead\">
			Welcome to your registry!
		</span>
		<br><br>
		<span class=\"sectHead\">Thank you for registering at As You Like It Silver Shop!<br><br>
		A confirmation email with your username and password has been sent to</span>
		<span style=\"font-size:14px;color:#444444;\"> $email</span><br><br>
		<span class=\"sectHead\">With this form you can:</span><br> 
		<span style=\"padding-left:20px\">-add items to your registry<br></span>
		<span style=\"padding-left:20px\">-see what items on your registry have been purchased<br></span>
		<span style=\"padding-left:20px\">-update your contact information<br></span>
		<span style=\"padding-left:20px\">-update your registry password<br></span>
				
		<br>
		<span class=\"sectHead\">To get started adding items to your registry, click the \"Add Items\" tab above.</span><br><br>
		
		";


?>