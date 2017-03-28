<?php
//basic inventoryItem class
include_once("/home/asyoulik/connect/mysql_pdo_connect.php");

class InventoryItem{
	public $brand;					//maker, manufacturer etc
	public $category;				//category
	public $city;					//origin city
	//public $description=array();	//array containing descriptive paragraphs
	public $desc;
	public $desc2;
	public $desc3;
	public $description;
	public $designPeriod;			//
	public $dimension;
	public $display;		
	public $gift;		
	//public $holdQuantity;
	public $id;						//database id
	public $image;
	public $image2;
	public $image3;
	//public $images;					//json of images
	public $keywords;				//keywords for search form
	public $listOrder;				//
	public $monogram;
	public $monogramText;			//
	public $num_pieces;
	public $pattern;				//pattern
	public $origin;				
	public $retail;					//price
	public $productId;				//AYLISS product ID
	//public $instock;				//quantity in stock
	public $sale;					//on sale price (never used)
	public $searchCategory;			//for holloware sub searching
	//public $shippingWeight;			//converted troy oz weight to oz
	public $similliarItems;			//mispelled!!! correct this
	//public $stockNumber;  			//manufacturer stock number
	public $state;					//origin state
	public $suggestedItems;
	public $time;
	public $weight;					//weight in troy oz.

	

	function retrieve(){
	 global $db;
	 global $displayformat;
	   if($this->id){
			$query=$db->query("SELECT * FROM inventory WHERE id=".$this->id);
		}
		else{
			if($this->productId){
				$query=$db->query("SELECT * FROM inventory WHERE productId=".$this->productId);
			}
		}
		//echo $query;
		$query->execute();
		$result=$query->fetchAll();
		$inventoryArr=$result[0];		
		//$inventoryArr=mysql_fetch_assoc($result);
		//natcasesort($inventoryArr);
		foreach($inventoryArr as $k=>$v){
		if($displayformat==1){
			$this->$k=ucwords(strtolower($v));
		}
		else{
			$this->$k=$v;
		 }	//echo $k.": ".$this->$k."<br>";
		}
		
		return $inventoryArr;
	}
	
	function displayItem(){
	//echo "pdo <br>";
		foreach(get_object_vars($this) as $k=>$v){		   
			echo $k.": ";
			if(substr($v,-3)=="jpg"){
			  $v=str_replace(".jpg", "", $v);
			  echo '<img src="/productImages/_SM/'.str_replace(" ", "-", strtoupper($v)).'_SM.jpg">';
			}
			else{
				echo $v;
			}
			echo"<br>";
		}		
	}
	
	
	function itemName() {
	   
	   $itemName="";
	   
	   if( $this->pattern!="" && $this->brand!="UNKNOWN" && $this->brand!="")	{
		   $by=" by ";
	   }
	   
	   if( $this->monogram!="" ) {
		   $monogram=" - monogrammed";
	   }
	   
	   $itemName=trim($this->pattern.$by.$this->brand.$this->item.$monogram);
	   
	   $itemName=str_replace("UNKNOWN", "", $itemName);
	   
	   $itemName=ucwords(strtolower($itemName));

	   return $itemName;	   
	}
	
	function ouputJSON(){
		$json=array();
	
		foreach(get_object_vars($this) as $k=>$v){
			$json[$k]=$v;
		}
	
		echo json_encode($json);
	}
	
	function saveItem(){
		
		global $db;

					
		$columnTypes=array(
					'brand'=>"'",
					'bs'=>"'",
					'category'=>"'",
					'city'=>"'",
					'desc'=>"'",
					'desc2'=>"'",
					'desc3'=>"'",
					'description'=>"'",
					'designPeriod'=>"'",
					'dimension'=>"'",
					'display'=>"'",
					'gift'=>"'",
					'id'=>"",
					'image'=>"'",
					'image2'=>"'",
					'image3'=>"'",
					'images'=>"'",
					'item'=>"'",
					'keywords'=>"'",
					'listOrder'=>"",
					'monogram'=>"'",
					'monogramText'=>"'",
					'num_pieces'=>"",
					'pattern'=>"'",
					'origin'=>"'",
					'retail'=>"",
					'productId'=>"",
					'sale'=>"",
					'searchCategory'=>"",
					'similliarItems'=>"'",
					'stockNumber'=>"'",
					'state'=>"'",
					'suggestedItems'=>"'",
					'time'=>"'",
					'weight'=>""
					);
		
		
		$fields = array();
		
			foreach(get_object_vars($this) as $k=>$v){
				
				if($k != 'id'&& $k != 'productId'){ 
				  
				  $quotes = $columnTypes[$k];
				  
				   if( $v != ""){
					
					  if(strpos($v, "'") !== false){
					    $v = addslashes($v);	  
				  	  }
				 	
				 	}
				  
				   if(!$v && $quotes != "'"){ 
					
					  $v=0; 
					
					}
				  
				  			
				  $fields[] = "`$k`=$quotes$v$quotes";		
				
				}
			}
			
			$fieldstr = join(",",$fields);
			$stmt = "UPDATE inventory SET $fieldstr WHERE id=".$this->id;
			//echo $stmt;
			$query = $db->query("UPDATE inventory SET $fieldstr WHERE id=".$this->id);
			$query->execute();		
    }
	
	function setValue($k,$v){
	 	$this->$k = $v;
	 }
	 
	public function imageName($imageversion=""){
	
		if($this->pattern){
			$newImage = $this->pattern;
			if($this->brand){$newImage .= " BY ".$this->brand;}
		}
		else{
			if($this->brand){
				$newImage = $this->brand;
			}
		}

		if($newImage){
			$newImage = $newImage." ".$this->item;
		}
		else
		{
			$newImage = $this->item;
		}
     
 
		if( ($this->monogram == 1) || ($this->monogram == -1) ){$newImage .= "-MONOGRAMMED";} 

		$dash = $imageversion?"-":"";
			
		$newImage .= "-".$this->productId.$dash.$imageversion;
		
		
		$newImage = str_replace(" - ","-",$newImage);
		$newImage = str_replace("/","-",$newImage);
		$newImage = str_replace(" ","-",$newImage);
		$newImage = str_replace("&","AND",$newImage);
		$newImage = str_replace("'","",$newImage);
		$newImage = str_replace(".","",$newImage);
		$newImage = str_replace(",","",$newImage);
		
		
		$newImage .= ".jpg";
 
		return $newImage;
		
	}
	
	function getShippingWeight(){
	  if(isset($this->weight)){
		  $shipweight=$this->weight/.911458333;
	  }
	  else{
	   $c=$this->category;
	   
	   if($c=='f' || $c=='sp' || $c=='bs' || $c=='xm' || $c=='cl'){
			 $shipweight=1/.911458333;  
	   }
	   else{
		   $shipweight=$this->retail/23/.911458333;
	   }
		  
	  }
	  
	  return $shipweight;
	 }
}

?>