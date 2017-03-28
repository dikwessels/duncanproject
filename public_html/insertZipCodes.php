<!DOCTYPE html>
<html>
<head>
<title>Importing zipcodes...</title>
</head>
<body>
<?php

include("connect/mysql_connect.php");
echo "Opening file...<br>";
$zipcodes=file_get_contents("ZipCodes.txt");

$arrZipCodes=explode("\n", $zipcodes);

foreach($arrZipCodes as $zipdata){

$zipdata=str_replace('"', '', $zipdata);
	
$arrZipData=explode(",", $zipdata);
$query="INSERT INTO tblZipCodes(zipcode,zipcodetype,city,citytype,state,statecode,areacode,latitude,longitude) VALUES($arrZipData[0],'$arrZipData[1]',\"$arrZipData[2]\",'$arrZipData[3]',\"$arrZipData[4]\",'$arrZipData[5]','$arrZipData[6]',$arrZipData[7],$arrZipData[8])";
	
$result=mysql_query($query);

if(!mysql_error()){
	echo $query."<br>";
}
else{
	echo mysql_error()."<br>";
}

}

?>
</body>