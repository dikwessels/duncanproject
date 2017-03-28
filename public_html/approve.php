<?php 
					
					include("/home/asyoulik/connect/mysql_connect.php");
					$_REQUEST["repairnumber"]=$repairnumber;
					$_REQUEST["customeremail"]=$customeremail;
					
					
					$curdate = date("y-m-d");
					
					$query = "UPDATE repairs SET approval='y' WHERE invoice_num=$repairnumber"; 
					$query2 = "UPDATE repairs SET approval_date='$curdate' WHERE invoice_num=$repairnumber";
					$query3 = "SELECT * FROM repairs WHERE invoice_num=$repairnumber";
					
					
					mysql_query($query);
					mysql_query($query2); 
					$result = mysql_query($query3);
					$row = mysql_fetch_row($result);
								
					$message ="		APPROVAL NOTIFICATION:
								
									Customer last name: $row[1]
									Invoice number: $row[0]
					
									This repair work has been approved by the Customer on $curdate.";
									
									
					$message2 ="	APPROVAL NOTIFICATION:	
						
									Customer last name: $row[1]
									Invoice number:	$row[0]
									
									You have approved the repair work on the above items. 
									Please check the repairs section at www.asyoulikeitsilvershop.com/repairs for updates
									on the status of your repair.";
									
											
					mail('duncan@asyoulikeitsilvershop.com','APPROVAL NOTIFICATION',$message);	
					mail($customeremail,'APPROVAL NOTIFICATION',$message2);
					
					
					$handle = fopen("customerapproves.txt","a");
					
					$info = "$row[0]\t$curdate\n";
					
					fwrite($handle,$info);
					
					fclose($handle);
								 
								 
								 
					header( 'Location: http://www.asyoulikeitsilvershop.com/repairs.php' ) ;
				
?>
						
						