<?php
	
include("/home/asyoulik/connect/mysql_pdo_connect.php");

ini_set("display_errors",1);

ini_set("memory_limit",-1);


	$stmt = "SELECT * FROM inventory WHERE productId > 0";

	if($max){ $stmt .= " AND productId < $max";}
	
	if($min){$stmt .= " AND productId > $min";}
	
	$stmt.= " ORDER BY productId DESC";
	
	$query = $db->query($stmt);
	
	$query->execute();


	$result = $query->fetchAll();

	foreach( $result as $row ){
		
		extract($row);
		
		if( file_exists("/home/asyoulik/public_html/productImages/_BG/$image" ) === false){
		
			
			//echo "$image doesn't exist<br>";
			
			$by = "";	
			
			$monogrammed = "";
			
		
			if($pattern){$by = "by";}
			
			if($monogram == 1||$monogram == -1){$monogrammed = "-monogrammed";}
			
			$fullName = strtoupper(trim("$pattern $by $brand $item $monogrammed"));
			
			echo "$productId<item>$fullName;";
			
		
		}
	
	}

?>