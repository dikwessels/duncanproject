<?php
  // Enter your MySQL access data  
  $host= 'localhost';        
  $user= 'asyoulik_admin';              
  $pass= 'f[()6COPT!Wo';
  $db=  'asyoulik_ayliss';

  $backupdir = 'backup';  

  // Compute day, month, year, hour and min.
  $today = getdate();
  $day = $today[mday];
  if ($day < 10) {
     $day = "0$day";
  }
  $month = $today[mon];
  if ($month < 10) {
     $month = "0$month";
  }
  $year = $today[year];
  $hour = $today[hours];
  $min = $today[minutes];
  $sec = "00";

  // Execute mysqldump command.
  // It will produce a file named $db-$year$month$day-$hour$min.gz 
  // under $DOCUMENT_ROOT/$backupdir
  system(sprintf( 
   'mysqldump --opt -h %s -u %s -p%s %s | gzip > %s/%s/%s-%s%s%s-%s%s.gz',                                                  
   $host,
   $user,
   $pass,
   $db,
   getenv('DOCUMENT_ROOT'),
   $backupdir,
   $db,
   $year,
   $month,
   $day,
   $hour,
   $min
  ));  
  echo '+DONE';  
?> 
