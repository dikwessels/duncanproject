<?
include("/connect/mysql_connect.php");
	extract($_GET);
	
	$query="SELECT wishlist.id, customerInfo.emailID as e,customerInfo.title as t,customerInfo.firstname as f,	
	customerInfo.lastname as l,customerInfo.street as s,customerInfo.city as c,customerInfo.state as st,
	customerInfo.zip as z,customerInfo.homephone as h,customerInfo.nomail as n,customerInfo.advertising as a,
	wishlist.item as i,wishlist.pattern as p,wishlist.maker as m, wishlist.qty as q,wishlist.notes as nt 
	FROM customerInfo, wishlist WHERE customerInfo.emailID=wishlist.customerEmail AND wishlist.id>$id ORDER BY wishlist.id";
		
	//echo($query);
	$result=mysql_query($query);


	$n=mysql_num_rows($result);
	if($n==0){
	echo("zNoData123456");
	}
	
	else{
	
	while($r=mysql_fetch_assoc($result)){
	$email=urldecode($r['e']);
	$title=urldecode($r['t']);
	$fname=urldecode($r['f']);
	$lname=urldecode($r['l']);
	$street=urldecode($r['s']);
	$city=urldecode($r['c']);
	$state=urldecode($r['st']);
	$zip=urldecode($r['z']);
	$phone=urldecode($r['h']);
	$nomail=urldecode($r['n']);
	$ad=urldecode($r['a']);
	$item=urldecode($r['i']);
	$pattern=urldecode($r['p']);
	$maker=urldecode($r['m']);
	$qty=urldecode($r['q']);
	$notes=urldecode($r['nt']);
    $rowID=$r['id'];
    
    $dl="<col>";
    
	echo $email.$dl.$title.$dl.$fname.$dl.$lname.$dl.$street.$dl.$city.$dl.$state.$dl.$zip.$dl.$phone.$dl.$nomail.$dl.$ad.$dl.$item.$dl.$pattern.$dl.$maker.$dl.$qty.$dl.$notes.$dl.$rowID."<endrow><br>";
	   	
	}
	echo "<endlist>";
	}

 mysql_close();
?>