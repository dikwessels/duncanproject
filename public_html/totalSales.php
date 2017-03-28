<?php
/*
include("/connect/mysql_connect.php");

extract($_GET);
//extract($_POST);

$query="SELECT sum(subtotal+tax+shipping) as total from customers WHERE (status='processed' OR status='shipping)";

if($startDate){
  $arrDate=explode("-",$startDate);
  if(strlen($arrDate[0])<'2'){$arrDate[0]='0'+$arrDate[0];}
  //if(strlen($arrDate[1]<2){$arrDate[1]='0'+$arrDate[1];}
  // $arrDate[2]=strlen($arrDate[2])<4?(left($arrDate[2],1)=='9'?'19'+$arrDate[2]:'20'+$arrDate[2]):$arrDate[2];
 
  $query=$query+" AND mid(time,1,4)>='$arrDate[2]' AND mid(time,6,2)>='$arrDate[1]' AND mid(time,9,2)>='$arrDate[0]'";
}

echo strlen($arrDate[0])."Month: $arrDate[0]<br> Day: $arrDate[1]<br> Yeard: $arrDate[2]<br> Start Date: $startDate<br>Query: $query<br>";


if($endDate){
 $arrDate=explode($endDate,"/");
 if(strlen($arrDate[0])<2){$arrDate[0]='0'+$arrDate[0];}
 if(strlen($arrDate[1]<2){$arrDate[1]='0'+$arrDate[1];}
 $query=$query+" AND mid(time,1,4)<='$arrDate[2]' AND mid(time,6,2)<='$arrDate[1]' AND mid(time,9,2)<='$arrDate[0]'";
}

$result=mysql_query($query);

$row=mysql_fetch_assoc($result);

extract($row);

$total=format($total,2);

echo "Total sales for this period: $total";
*/
?>