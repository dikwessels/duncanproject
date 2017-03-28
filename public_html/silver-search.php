<?php
/*
 find search category in terms √
 -	remove search category to look for other terms
 -	call generic search script with search terms √
 	- works best on simple queries e.g. gorham flatware, gorham knives
*/
ini_set("display_errors","1");

 
include("/home/asyoulik/connect/mysql_connect.php");

$soundexCats=array( 
					"Baby"=>"bs",
					"Baby Silver"=>"bs",
					"Baby Silver Ware"=>"bs",
					"Cleaning Products"=>"cp",
					"koin silver"=>"cs",
					"coin"=>"cs",
					"Coin Silver"=>"cs",
					"coin silver"=>"cs",
					"Collectibles"=>"cl",					
					"Complete Set"=>"fcs",
					"Flatware"=>"f",
					"Hollowware"=>"h",
					"Jewelry"=>"j",
					"Serving Pieces"=>"sp",
					"Silverware"=>"f",
					"Storage Products"=>"stp",
					"Storage"=>"stp",
					"Christmas"=>"xm"
					);
					 
$singularWords=array("knives"=>"knife");

$monogramKeywords=array("monogram",
						"monogrammed",
						"monograms");

$monogramFind=array(" mono ",
					" monogram ",
					" monograms ",
					" monogramm "
					);
//monogrammedgrammedgrammed
$monogramReplace=array("monogrammed",
					"monogrammed",
					"monogrammed",
					"monogrammed"
					);	

					
$findArray=array("/",
				"&",
				"inc.",
				"inc",
				"brothers",
				"bros.",
				"chantily",
				" ware",
				"baby silver",
				"no.",
				"number",
				"serving pieces",
				"ornaments",
				" by ",
				" and ",
				" spoons",
				" sets",
				" strasburg ",
				" luxemburg ",
				" chantily ",
				" butercup ",
				" onieda ",
				" onida ");

$replaceArray=array(" ",
				"",
				"",
				"",
				"bros",
				"bros.",
				"chantilly",
				"ware",
				"babysilver",
				"#",
				"#",
				"servingpieces",
				"ornament",
				" ",
				" ",
				" spoon",
				" set",
				" strasbourg ",
				" luxembourg ",
				" chantilly ",
				" buttercup ",
				" oneida ",
				" oneida ");


$origTerms=$terms;

////consoleLog($terms);


//$fields=array("pattern","brand","item","category");
if(isset($_GET)){extract($_GET);}
if(isset($_POST)){extract($_POST);}

 $terms	= strtolower($terms);
 
 $h2Temp = ucwords($terms);
 
 $ignoreH2 = 1;
 
 $removeBrand = "";
 
 $removePattern = "";
 
 //consoleLog("line 111 $terms");
 $terms=str_replace($monogramFind,$monogramReplace,$terms);
 //consoleLog("line 122 $terms");
 
 $terms=str_replace($findArray, $replaceArray, $terms);
// $logmsg.="Search terms: $terms <br>";
 
 $searchTerms=explode(" ", $terms);
 //consoleLog("line 128 ".json_encode($searchTerms));

//check for category keywords
 foreach($searchTerms as $s){
  
  if( soundex('strasbourg') == soundex($s) ){
  
  	$s = "strasbourg";
  
  }
  
  $cat = $s;
  
	 foreach( $soundexCats as $k => $v ){
		 
		 //if ($k == $cat){
		if( soundex($k) == soundex($cat) && $cat != 'strasbourg' ){
		//consoleLog("line 123 soundex $s");
		  $replaceCat = $cat;
		  $searchCat = $v;	 
		}
		 
		else{
		
		 if( $s != "" ){
			 
			if( strpos($v,$s) === false ){}
		 
			else{
				
				 $replaceCat=$cat;
				 $searchCat=$v;
			 
			 }
		 }
	//		echo("Term $s doesn't match category $v <br>");
		}
	 }

if( strpos($terms,"coin silver") === false ){
	
}
else{
	$replaceCat = "coin silver";
	$searchCat = "cs";
}
//search for monogram keyword	 
foreach($monogramKeywords as $m){
		 if(soundex($s)==soundex($m)){
			 //flag for monogram pieces
			 //consoleLog("line 151 monogram term $s");
			 $searchMono=$s;
			 $monogram=1;	  
	}
   
  }
   
 }
 
 //filter out any found terms to simplify remaining search
 //monogram

 //if($searchMono){$terms=str_replace($searchMono,"",$terms);}

 //category
 $category = $searchCat;
 
 //$terms=str_replace($replaceCat,"",$terms);
 $terms = strtolower($terms);

 $termArray = explode(" ", $terms);
 
 natsort($termArray);
 
 $testing = "false";
 
 foreach( $termArray as $k ){
	 //consoleLog($k);
	 if( $k == "testzzz" ){
	
			$testing = "true";	 
			$k = "";
	 }
	 if( $queryTerms ){
	
		 $queryTerms .= "%".$k;
	
	 }
	 else{
	
		 $queryTerms = $k;
	
	 }
 }


$keywords = $queryTerms;

$h2 = $h2Temp;
 
if( $testing === "true" ){

	include("/home/asyoulik/public_html/productSearch.dev.php");

}
else{
	
	include("/home/asyoulik/public_html/productSearch.php");

}


/*function //consoleLog($s){
 echo "<script type='text/javascript'>
	console.log('You searched for \'$s\', testing is $testing');
</script>";
}
*/
?>