<?


function socketmail($toArray, $subject, $message,$from,$fromName) {
 // $toArray format --> array("Name1" => "address1", "Name2" => "address2", ...)
	$message=str_replace("
","\015",stripslashes($message));
 ini_set(sendmail_from, "$from");

 $connect = fsockopen (ini_get("SMTP"), ini_get("smtp_port"), $errno, $errstr, 30) or die("Could not talk to the sendmail server!"); 
   $rcv = fgets($connect, 1024); 

 fputs($connect, "HELO {$_SERVER['SERVER_NAME']}\r\n");
   $rcv = fgets($connect, 1024); 

 while (list($toKey, $toValue) = each($toArray)) {

  fputs($connect, "MAIL FROM:$from\r\n"); 
    $rcv = fgets($connect, 1024); 
   fputs($connect, "RCPT TO:$toValue\r\n"); 
     $rcv = fgets($connect, 1024); 
  fputs($connect, "DATA\r\n"); 
     $rcv = fgets($connect, 1024); 

   fputs($connect, "Subject: $subject\r\n"); 
   fputs($connect, "From: $fromName <$from>\r\n"); 
   fputs($connect, "To: $toKey  <$toValue>\r\n"); 
   fputs($connect, "X-Sender: <$from>\r\n"); 
   fputs($connect, "Return-Path: <$from>\r\n"); 
   fputs($connect, "Errors-To: <$from>\r\n"); 
   fputs($connect, "X-Mailer: PHP\r\n"); 
   fputs($connect, "X-Priority: 3\r\n"); 
   fputs($connect, "Content-Type: text/plain; charset=iso-8859-1\r\n"); 
   fputs($connect, "\r\n"); 
   fputs($connect, $message." \r\n"); 

   fputs($connect, ".\r\n"); 
     $rcv = fgets($connect, 1024); 
   fputs($connect, "RSET\r\n"); 
    $rcv = fgets($connect, 1024); 
 }

 fputs ($connect, "QUIT\r\n"); 
   $rcv = fgets ($connect, 1024); 
fclose($connect);
 ini_restore(sendmail_from);
}


?>