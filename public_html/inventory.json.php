
<?php

extract($_GET);

ini_set("display_errors",1);

include("/home/asyoulik/connect/mysql_pdo_connect.php");

$thumbKeys=array('image','image2','image3');

$PDOTypes=array(
					'brand'=>PDO::PARAM_STR,
					'bs'=>PDO::PARAM_STR,
					'category'=>PDO::PARAM_STR,
					'city'=>PDO::PARAM_STR,
					'desc'=>PDO::PARAM_STR,
					'desc2'=>PDO::PARAM_STR,
					'desc3'=>PDO::PARAM_STR,
					'description'=>PDO::PARAM_STR,
					'designPeriod'=>PDO::PARAM_STR,
					'dimension'=>PDO::PARAM_STR,
					'display'=>PDO::PARAM_STR,
					'gift'=>PDO::PARAM_STR,
					'id'=>PDO::PARAM_INT,
					'image'=>PDO::PARAM_STR,
					'image2'=>PDO::PARAM_STR,
					'image3'=>PDO::PARAM_STR,
					'item'=>PDO::PARAM_STR,
					'keywords'=>PDO::PARAM_STR,
					'listOrder'=>PDO::PARAM_INT,
					'monogram'=>PDO::PARAM_STR,
					'monogramText'=>PDO::PARAM_STR,
					'num_pieces'=>PDO::PARAM_INT,
					'pattern'=>PDO::PARAM_STR,
					'origin'=>PDO::PARAM_STR,
					'retail'=>PDO::PARAM_STR,
					'productId'=>PDO::PARAM_INT,
					'sale'=>PDO::PARAM_STR,
					'searchCategory'=>PDO::PARAM_INT,
					'similliarItems'=>PDO::PARAM_STR,
					'stockNumber'=>PDO::PARAM_STR,
					'state'=>PDO::PARAM_STR,
					'suggestedItems'=>PDO::PARAM_STR,
					'time'=>PDO::PARAM_STR,
					'weight'=>PDO::PARAM_STR,
					'limit'=>PDO::PARAM_INT
					);


function makePatternName($p,$b){
	if($p){
		$patternname=ucwords(strtolower($p));
		if($b){
			$patternname.=" by ".ucwords(strtolower($b));
		}
	}
	else{
		if($b){
			$patternname=ucwords(strtolower($b));
		}
	}
	return $patternname;
}

function makeItemName($i,$m){
	$itemName=ucwords(strtolower($i));
	if($m!=0){
		$itemName.=" - monogrammed";
	}
	return $itemName;
}

$stmt="SELECT * FROM inventory WHERE quantity <> 0 AND display >0 ";

//it's annoying, but if
  foreach($_GET as $k=>$v){
	  
	  if($v){

		  if($k!="limit" && $k!="order" && $k!="hasimage"){
		    
		    if(strtolower($v)=="f"){
			   //flatware search which should return, place settings, serving pieces, complete sets
			   $stmt.=" AND (category='f' OR category='sp' OR category='fcs' OR category='ps')";
			}
			   else{
			   $stmt.=" AND `$k`=:$k";
			   }
		  }
		  if($k=="hasimage"){
			  $stmt.=" AND image IS NOT NULL ";
		  }	 
	 
	  }
	  	  
  }
	if(!$order){
		$stmt.=" ORDER BY brand, pattern, listOrder, category, item";
	}
	else{
		switch($order){
			case "rand":
			$stmt.=" ORDER BY rand()";
			
		}
	}
	if($limit){$stmt.=" LIMIT :limit ";}
	 
	//echo $stmt;
	
	$query=$db->prepare($stmt);
	
	foreach($_GET as $k=>$v){
		if($k!="hasimage" && $k!="order"){
		if($v && strtolower($v)<>"f"){
			$query->bindValue(":$k",$v,$PDOTypes[$k]);
		}
		}
	}
	
	$query->execute();

	$result=$query->fetchAll();


	$jsonArray=array();

extract($result);

$lastpattern="";
$pc=0;
$curcategory="";
$lastcategory="";

$categoryArray=array(
				'f'=>'Place Setting Pieces',
				'sp'=>'Serving Pieces',
				'fcs'=>'Complete Sets',
				'bs'=>'Baby Silver',
				'h'=>'Hollowware',
				'j'=>'Jewelry',
				'cs'=>'Coin Silver',
				'n'=>'Novelty',
				'ps'=>'4-Piece Settings',
				'xm'=>'Christmas'
				);
				
foreach($result as $row){
  
  $row['patternName']=makePatternName($row['pattern'],$row['brand']);
  $cat=$row['category'];
  if($row['category']=='ps'){	
  $row['newRow']="";
  $row['endRow']="";
  $row['placeSetting']="1";

  if($pc==0){  
	 $row['newRow']="1";
  }
  $pc++;
 
  if($pc==3){
	  $row['endRow']="1";
	  $pc=0;
  }
  
  }
  
  else{
	  $row['placeSetting']="";
  }
  
 
  $row['imageTitle']=ucwords(strtolower($row['patternName']." ".$row['item']));
  $row['itemName']=makeItemName($row['item'],$row['monogram']);
  $row['fullItemName']=$row['patternName']." ".$row['itemName'];
  
  $arrFind = array(
	  				"&",
  					"-",
  					"(",
  					")",
  					",",
  					"'",
  					"/",
  					" "
  					);
  
  $arrReplace = array(
	  				" ",
  					"_",
  					"",
  					"",
  					"",
  					"",
  					"_",
  					"_"
  					);
  
  $row['articleID']=str_replace($arrFind,$arrReplace, $row['fullItemName']);
   $lastindex=count($jsonArray)-1;
      
	$curpattern=ucwords(strtolower($row['pattern']." by ".$row['brand']." silver"));
  
		if($curpattern!=$lastpattern){
			$row['patternHeader']="1";
			$row['patternSectionID']=str_replace(" ","_",$curpattern);
			
			if($lastpattern){
				//add end section tag
				$jsonArray[$lastindex]['endPattern']="1";
			}
			
			$lastpattern=$curpattern;
		}
	   else{
		   $row['patternheader']="";
	   }

   
  $row['categoryHeader']="";
  $curcategory=strtolower($row['category']);
  
   if($curcategory!=$lastcategory){
		   $row['categoryHeader']=$row['patternName']." Silver ".$categoryArray[$curcategory];
		   $row['categoryHeaderID']=str_replace(" ", "_", $row['categoryHeader']);
		   if($lastcategory){
			   $lastindex=count($jsonArray)-1;
			   $jsonArray[$lastindex]['endCategory']="1";
		   }
		   
		   $lastcategory=$curcategory;
	}

   
   
  foreach($thumbKeys as $key){
	   	if($row[$key] && file_exists("/home/asyoulik/public_html/productImages/_BG/".$row[$key])){
		   	$imageThumbnails[]=$row[$key];
		    //$imageThumbnails['thumbIndex'][]
	   	}
   	}
   
   $imageThumbs=str_replace("[", "", $row['images']);
   $imageThumbs=str_replace("]","",$imageThumbs);
   $imageThumbs=str_replace("\"","",$imageThumbs);
   $imageThumbs=str_replace(".jpg","_SM.jpg",$imageThumbs);
   
   $imageThumbs=explode(",", $imageThumbs);   
   	
   	foreach($imageThumbs as $v){
	   	$j=1;
	   	$row['thumbnails'][]=$v;
	   	$j++;
   	}
   	
   	//create image array for items with multiple images
   	$row['images']=json_encode($imageThumbs);
	
	//test to see if item has a specific image
		if($row['image'] && file_exists("/home/asyoulik/public_html/productImages/_BG/".$row['image'])){
			$row['image']="/productImages/_SM/".substr($row['image'],0,-4)."_SM.jpg";
			$row['thumbnail']=str_replace("_SM","_TN",$row['image']);
		}
		else{
			$row['image']="";
		}
	
		//get folder path and file name base on specific naming conventions
		$handleFolder=strtoupper(substr($row['pattern'],0,1));
		$handleFile=str_replace($re,$rw,strtoupper($row['pattern']. " ". $row['brand']));

		$handle="/HANDLES/".$handleFolder."/".$handleFile.".jpg";
		
		if(file_exists("/home/asyoulik/public_html".$handle)){
				$row['handle']=$handle;
		}
	
		
	if($row['dimension']==0){$row['dimension']="";}
	if($row['weight']==0){$row['weight']="";}
	
	$jsonArray[]=$row;
}

	$results['product']=$jsonArray;
		
	echo json_encode($results);

?>
