<?php
//this is a master inventory query script designed to search the database inventory based on any searchable fields
ini_set("display_errors",1);
include("/connect/mysql_pdo_connect.php");

extract($_GET);

$distinct=array("","DISTINCT");

$stmt="SELECT ".$distinct[$d]." :fields FROM inventory WHERE item<>'' AND retail>0 ";

//these are the filter criteria
	$whereCriteria=array(
				'brand'=>urldecode($brand),
				'category'=>$category,
				'city'=>urldecode($city),
				'desc'=>$desc,
				'display'=>$display,
				'gift'=>$gift,
				'id'=>$id,
				'item'=>urldecode($item),
				'keywords'=>urldecode($keywords),
				'instock'=>$instock,
				'maxretail'=>$maxRetail,
				'minretail'=>$minRetail,
				'monogram'=>$monogram,
				'monogramText'=>urldecode($monogramText),
				'origin'=>urldecode($origin),
				'pattern'=>urldecode($pattern),
				'productId'=>$productId,
				'words'=>urldecode($words),
				'retail'=>$retail,
				'searchCategory'=>$searchCategory,
				'state'=>$state,
				'time'=>$time,
				'weight'=>$weight,
				'hasSearchCategory'=>$sc
				);



	foreach($whereCriteria as $k=>$v){
		if($v){
			switch($k){
				case 'instock':
					$stmt.=" AND quantity > 0";
					break;
					
				case 'maxretail':
				 	$stmt.=" AND retail<=:$k";
				 	break;
				
				case 'minretail':
					$stmt.=" AND retail >=:$k";
					break;
					
				case 'words':
					$stmt.=" AND keywords LIKE(:$k)";	
					break;
				
				case 'item':
					$stmt.=" AND instr($k,':$k')>0";
					break;
					
				case 'hasSearchCategory':
					if($v==1){
						$stmt.=" AND searchCategory=0";
					}
					else{
						$stmt.=" AND searchCategory>0";
					}
					 break;
				default:
					$stmt.=" AND $k=:$k";		
			}			
		}
	}

	$query=$db->prepare($stmt);

	$query->bindParam(":fields",$fields);

	foreach($whereCriteria as $k=>$v){
	  
		if($v){
			if($k!='instock'&& $k!='hasSearchCategory'){
			//echo "binding $k<br>";
				$bindTag=":$k";
			//echo "$bindTag<br>";
				if($k=="searchCategory"){
					$query->bindValue($bindTag,$v,PDO::PARAM_INT);		
				}
				else{
					$query->bindValue($bindTag,$v,PDO::PARAM_STR);	 
				}
			}
		}
		
	}
	
	$query->execute();
	
	$result=$query->fetchAll();
  	//$results['resultCount']=count($result);
	$results['inventoryItem']=$result;
	
	
	echo json_encode($results);

?>