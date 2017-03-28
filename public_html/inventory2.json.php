<?php
	ini_set("display_errors",1);
	
	include("/connect/mysql_pdo_connect.php");
	
	//echo 'hello';

	$stmt="SELECT * FROM inventory WHERE display <>0 ";

	foreach($_GET as $k=>$v){
		
		if($v){
				if($k=="instock"){
					$stmt.=" AND quantity <> 0";
				}
				else{
					$stmt.=" AND `$k`=:$k";
				}	
		}
	
	}
	
	//$stmt.=" ORDER BY pattern,brand,item ASC";
	
	echo $stmt;
	
	/*if($pattern){$stmt.=" AND pattern=:pattern";}
	
	if($brand){$stmt.=" and brand=:brand";}
	*/
	
	$query=$db->prepare($stmt);
	
	foreach($_GET as $k=>$v){
		
		if($v){
			echo "<br>$k : $v<br>";
			if($k<>"instock"){
				$query->bindParam(":$k",$v);
			}
		}
	}
	
	
	/*
	
	if($pattern){$query->bindParam(':pattern',$pattern,PDO::PARAM_STR);}
	
	if($brand){$query->bindParam(':brand',$brand,PDO::PARAM_STR);}
	
	*/
	
	
	$query->execute();
	
	$result=$query->fetchAll();
	
	print_r($result);
	

?>