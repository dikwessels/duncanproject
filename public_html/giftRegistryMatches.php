<?php
extract($_GET);
include("/connect/mysql_connect.php");

$br=$nobreak?"":"<br>";


if($regID){

$columns="id;Product Id;Pattern/Manufacturer;;;Item;Monogram;Requested;Purchased;In Stock;$br";
//echo $regID;

$query="SELECT inventory.productId,inventory.category,inventory.pattern,inventory.brand,inventory.quantity,inventory.monogram,inventory.image,
		inventory.dimension,inventory.item,inventory.retail,inventory.sale,
		weddingRegistryItems.id as wrID, weddingRegistryItems.qtyRequested,weddingRegistryItems.qtyPurchased  
		FROM inventory, weddingRegistryItems 
		WHERE weddingRegistryItems.itemID=inventory.id and weddingRegistryItems.regID=$regID 
		ORDER BY inventory.pattern,inventory.brand";	

 //$query="SELECT weddingRegistryItems.id, weddingRegistryItems.itemID,inventory.productID, weddingRegistryItems.qtyRequested,weddingRegistryItems.qtyPurchased,inventory.item,inventory.pattern,inventory.brand,inventory.monogram,inventory.retail,inventory.quantity from inventory,weddingRegistryItems WHERE inventory.id=weddingRegistryItems.itemID and weddingRegistryItems.regID=$regID";
 
// echo $query;
 $result=mysql_query($query) or die(mysql_error());
 
 while($row=mysql_fetch_assoc($result)){
	 extract($row);
  	$m=$monogram?"Yes":"No";
  	$pb=$pattern?"$pattern BY $brand":$brand;
  	$pb = str_replace("," ,"",$pb);
  	$c.= "$wrID;$productId;$pb;$pattern BY $brand;$brand;$item;$m;$qtyRequested;$qtyPurchased;$quantity;$br";
 }
 
}

else{
$eventArray=Array("a"=>"Anniversary","bbs"=>"Baby Shower","bd"=>"Birthday","brs"=>"Bridal Shower","o"=>"Other","w"=>"Wedding");

$columns="id;Registrant;Co-Registrant;Event;Date;Street;City;State;Zip;<br>";
$query="SELECT id,rfname,rlname,crfname,crlname,event,wedday,wedmonth,wedyear,raddress,rcity,rstate,rzipcode FROM weddingRegistries";

$w=" WHERE rfname !=''";

	if($fname && $fname!=""){
	//echo $fname;
	$w.=" AND (rfname LIKE '$fname' OR instr(rfname,\"$fname\")>0 OR crfname LIKE '$fname' OR instr(crfname,\"$fname\")>0)";
		//$w.=" AND (rfname=\"$fname\" OR crfname=\"$fname\" OR instr(rfname,\"$fname\")>0 or instr(crfname,\"$fname\")>0) ";
	}

	if($lname && $lname!=""){
		$w.=" AND (rlname=\"$lname\" OR crlname=\"$lname\" OR instr(rlname,\"$lname\")>0 or instr(crlname,\"$lname\")>0)";
	}

	if($smonth){
		$w.=" AND wedmonth=\"$smonth\"";
	}

	if($syear){
		$w.=" AND wedyear=\"$syear\"";
	}
	$query.=$w;
$result=mysql_query($query) or die(mysql_error());



while($row=mysql_fetch_assoc($result)){
	extract($row);
        $raddress=str_replace(",","",$raddress);

	$c.="$id;$rfname $rlname;$crfname $crlname;$eventArray[$event];$wedmonth/$wedday/$wedyear;$raddress;$rcity;$rstate;$rzipcode;$br";

}
	
	
}
//echo $query;
	echo $columns; 
	echo $c;
?>

