
<?php
	ini_set("display_errors", 1);
	
include("/home/asyoulik/connect/mysql_pdo_connect.php");

$categoryKeywords=array('sp'=>'Flatware Serving Pieces',
                'ps'=>'Flatware',
                'fcs'=>'Flatware Complete Sets',
                'f'=>'Flatware',
                'h'=>'Hollowware',
                'bs'=>'Baby Silver',
                'j'=>"Jewelry",
                'cp'=>'Silver Care',
                'cs'=>'Coin Silver',
                'stp'=>'Silver Storage',
                'xm'=>'Christmas',
                'cl'=>'Collectibles');

$stmt="SELECT * FROM inventory";

if($newonly){ $stmt .= "WHERE keywords IS NULL or keywords=''";}

$query=$db->query( $stmt);

$result=$query->fetchAll();


foreach($result as $row){

	$keywordTerms="";

		extract($row);

	if($city){$city=str_replace(" ", "+", $city);}
	if($state){$state=str_replace(" ","+",$state);}
	
	if(abs($monogram)==1){
		$monogram="monogrammed";
	}

	$catKeyWords=$categoryKeywords[$category];
	
	$terms="$catKeyWords $pattern $brand $item $city $state $monogram";
	
	$terms=trim($terms);
	$terms=strtolower($terms);
	$terms=str_replace("&", "", $terms);
	$terms=str_replace("/"," ",$terms);
	$terms=str_replace("(coin)","coin silver",$terms);
	$terms=str_replace("(", "", $terms);
	$terms=str_replace(")", "", $terms);
	$terms=str_replace("-"," ",$terms);
	
	
	$termArray=explode(" ", $terms);
	for($i=1;$i<count($termArray);$i++){
		$termArray[$i]=trim($termArray[$i]);		
	}
	
	if(natsort($termArray)){
	  echo "Array sorted!   ";
	}
	
	foreach($termArray as $k){
	 if($keywordTerms){
	 	$keywordTerms.=" ".$k;
	 	}
		else{
			$keywordTerms=$k;		
		}
	}
	
	$stmt="UPDATE inventory SET keywords=:keywords WHERE id=:id";
	
	$query=$db->prepare($stmt);
	$query->bindParam(':keywords',$keywordTerms,PDO::PARAM_STR);
	$query->bindParam(':id',$id,PDO::PARAM_INT);
	$query->execute();
	
	echo "UPDATE inventory set keywords=$keywordTerms WHERE id=$id <br>";
	
	//$updateResult=mysql_query($query);
	if($query->rowCount()>0){
		echo "Item $id updated with keywords $keywordTerms <br>";
	}
	
	
}

?>