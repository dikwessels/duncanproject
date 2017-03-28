<?
	
ini_set("display_errors",1);

$f=fopen("log.txt",'a');

file_put_contents("log.txt", date("Y:M:D hh:ii:ss")."	hello \n\r");
echo "I was called by the sales program \n\r";

include("/home/asyoulik/connect/mysql_connect.php");

$lS=array('sp'=>9,'fcs'=>10,'h'=>11,'bs'=>12,'f'=>8,'j'=>13,'cp'=>14,'stp'=>15,'xm'=>16,'cl'=>17,'cs'=>18); 

$fL=array('dinner knife'=>0,'place knife'=>1,'lunch/place knife'=>2,'dinner fork'=>3,'place fork'=>4,'lunch/place fork'=>5,'salad fork'=>6,'salad fork (place size)'=>6,'teaspoon'=>7);

extract($_GET);
extract($_POST);


switch($action) {

 case 'massUpdate': 
 echo "hello";			
 file_put_contents("log.txt", date("Y-M-D H:I:S",time()).": Mass Update Called\n\r");

 //fputs($f, "Mass update called")  

if($_GET){
    foreach ($_GET as $k=>$v) {
	if ($k=='action' || $k=='productId' || $k=='fieldlist') {continue;} 
	$st.="`$k`='\${$k}[\$id]',"; 
    }
}

else{
    foreach ($_POST as $k=>$v) {
	if ($k=='action' || $k=='productId' || $k=='fieldlist') {continue;} 
	$st.="`$k`='\${$k}[\$id]',"; 
    }
}




//$st=addslashes($st);

$st=str_replace('%26','',$st);

//fputs($f," $st");  


$st=substr($st,0,-1);

	foreach($productId as $id=>$v) { 
	
		//echo 1;

		eval("\$update=\"UPDATE inventory set \".str_replace('\"','\\\\\"',\"$st\").\" where `productId`=$v  limit 1\";");
		//echo $update. "<br>";
		$q=mysql_query($update);

		if (mysql_affected_rows()>0) {
			 //fputs($f,"Item $v successfully updated SUCCESS\n\r"); 
              file_put_contents("log.txt", date("Y-M-D H:I:S").":	"."$update executed successfully\n\r");
              
                   // echo "Item $v successfully updated with following statement:<br>$update";
		} 
		else {
		  if(mysql_errno()>0){
                        //echo "An error occurred";
			 file_put_contents("log.txt","An error occurred: " .mysql_errno()."\n\rItem was not updated with following sql query: $update \n\r"); 
			 
			 }
			 else{	
                 //echo "No error but no new information";
				 fputs($f,"No item information was changed.");
			 }
		 } 

		}

	//fputs($f,$update."\n\r");

	//update all keywords
	//include_once($_SERVER['DOCUMENT_ROOT']."/updateKeywordTerms.php");

break;  



case 'update':





	foreach($productId as $k=>$v) {

		if ($quantity[$k]) {

			$update="UPDATE inventory set quantity=(IF (quantity-".$quantity[$k].">0,quantity-".$quantity[$k].",0)) where productId=$v and quantity>-1 limit 1";	

			}

		if ($retail[$k]) {

			$update="UPDATE inventory set retail='$retail[$k]' where productId=$v limit 1";

			}

		$q=mysql_query($update);

		if (mysql_affected_rows()>0) { 
                    echo "$v 1\r\n"; 
                    } else {
                     echo "$v 0\r\n"; 
                    echo mysql_error();
                    } 

		//echo $update;



		}



break;



case 'add':

	$fields=split(',',$fieldlist);

	foreach($productId as $k=>$v) {	

		$listOrder=($fL[strtolower($item[$k])])?$fL[strtolower($item[$k])]:$lS[strtolower($category[$k])];

		foreach($fields as $v) {

			$values.="'". urldecode(${$v}[$k]) ."',";

			if ($v=='category') {	

				}

			}

		mysql_query("INSERT into inventory($fieldlist,listOrder) values(".substr($values,0,-1).",$listOrder)");



		}
//update keywords
//include_once($_SERVER['DOCUMENT_ROOT']."/updateKeywordTerms.php");

break;



case 'updaterepair':

fputs($f," Hi I am in updaterepair");  

	//$fields=split(',',$fieldlist);

	foreach($invoice_num as $k=>$v) {

	//  foreach($fields as $v) {

		//	$values.="'".${$v}[$k]."',";}

	//	mysql_query("INSERT into repairs($fieldlist) values (".substr($values,0,-1).")");

	$fields = "invoice_num,last_name,zip,receive_date,approval_date,quote,approval,completed,repairsmith";

	$values.="'".$invoice_num[$k]."','".$last_name[$k]."','".$zip[$k]."','".$receive_date[$k]."','".$approval_date[$k]."','".$quote[$k]."','".$approval[$k]."','".$completed[$k]."','".$repairsmith[$k]."'";

	

	

fputs($f,"values"); 

fputs($f,$values);

	$query = "INSERT into repairs($fields) values($values)";

fputs($f,"query"); 

fputs($f,$query);

		

	 mysql_query($query);

	 

	//INSERT into repairs(invoice_number, customer_name, begin_date, status) values ('REP1248','Michael Wagner','2007-05-20','c')

	}

break;



//Case for Testing

case 'deleteentry':

fputs($f," Hi I am in delete");



	foreach($invoice_num as $k=>$v) {

	$fields = "invoice_num";

	$values.="'".$invoice_num[$k]."'";

	$query = "DELETE FROM repairs WHERE invoice_num = '$values'";

	mysql_query($query);



}

break;

    

case 'updatePS':

	$fields=split(',',$fieldlist);

	foreach($pattern as $k=>$v) {

		$update="UPDATE inventory set retail='$retail[$k]' where pattern='$v' and  brand='$brand[$k]' and item='4 Piece $size[$k] Setting' limit 1";		

		$q=mysql_query($update);

		}

break;

case 'set_qty':

	foreach($productId as $k=>$v) {

		$update="UPDATE inventory set quantity=$quantity[$k] where productId=$v and quantity>-1 limit 1";

		$q=mysql_query($update);

		}

break; 

}

fclose($f); 



?>