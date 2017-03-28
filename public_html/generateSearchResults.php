<?php
ini_set("display_errors","1");

$re=array("&",",",'.','#','by','BROTHERS','INTERNATIONAL');
$rw=array("AND",'','','','','BROS','INTL');
$oldPattern="";
$oldCategory="";

$featureHeaderAdded=false;
//consoleLog($brand);

function displayOptions($n,$pos,$limit,$order,$brand,$pattern,$category,$recent,$searchCategory,$searchItem,$nResults,$monogram,$keywords,$city,$state){
	
	//display the sorting dropdown and if there are greater than 10 results, the display options dropdown
	global $borderClass;
	global $testing;

	
  $so = "<div class='row tableHead $borderClass[$category]' id='searchResultsOptions'>
  
  		<form name='searchValues' id='searchVariables'>
                    <input type='hidden' name='searchCategory' value='$searchCategory'>
                    <input type='hidden' name='category' value='$category'>
                    <input type='hidden' name='searchItem' value='$searchItem' id='item'>
                    <input type='hidden' name='recent' value='$recent'>
                    <input type='hidden' name='brand' value='$brand'>
                    <input type='hidden' name='pattern' value='$pattern'>
                    <input type='hidden' name='pos' value='$pos' id='position'>
                    <!--<input type='hidden' name='order' value='$order' id='orderinput'>-->
                    <input type='hidden' name='gift' value='$gift'>
                    <!--<input type='hidden' name='limit' value='$limit'>-->
                    <input type='hidden' name='nResults' value='$nResults' id='results'>
                    <input type='hidden' name='monogram' value='$monogram'>
                    <input type='hidden' name='keywords' value='$keywords'>
					<input type='hidden' name='testing' value='$testing' id='testing'>
					<input type='hidden' name='city' value='$city'>
					<input type='hidden' name='state' value='$state'>
                
                <div class='cell twoColumns middleAlign'>
                	<strong>$n results</strong>
                </div>";
  
  //add 'Now showing n of N
  $so.="<div class='cell fourColumns middleAlign' id='searchResultsPosition'>
                                Now Showing $pos - ".(($pos+$limit-1>$n)?$n:$pos+$limit-1)."
                            </div>
            <div class='cell twoColumns middleAlign'>Sort by:</div>
	    <div class='cell fourColumns middleAlign'>
             <select name='order' id='resultsOrder' data-field='order' class='searchSort'>
                <option value='category,item,pattern,brand' ".(($order=="item,pattern,brand")?"selected":"").">Item A to Z</option>
                 <option value='category,item DESC,pattern,brand' ".(($order=="item,pattern,brand")?"selected":"").">Item Z to A</option>
                <option value='pattern,brand,category,item' ".(($order=="pattern,brand,item")?"selected":"").">Pattern</option>
                <option value='brand,pattern,category,item' ".(($order=="brand,pattern,item")?"selected":"").">Manufacturer</option>
                <option value='dimension,pattern,brand'".(($order=="dimension,pattern,brand")?"selected":"").">Dimensions</option>
                <option value='retail ASC' ".(($order=="retail ASC pattern,brand,category,item")?"selected":"").">Price: Low to High</option>
                <option value='retail DESC' ".(($order=="retail DESC,pattern,brand,category,item")?"selected":"").">Price: High to Low</option>
             </select>  
            </div>";
          
            
if($n>10){
            
           $so.="<div class='cell threeColumns rightAlign middleAlign'>
                <select class='searchSort' id='displayNResults' name='limit'>
                    <option value='10' ". (($limit==10)?'':'').">Display 10 Items
                    <option value='50' ". (($limit==50)?'':'').">Display 50 Items
                    <option value='100' selected ". (($limit==100)?'':'').">Display 100 Items
                </select>
            </div>";
         }

         else{
         	$so.="<div class='cell threeColumns rightAlign'></div>";
         	}
         

$so.="</form></div>";

return $so;
	
}

function resultPageNavigation($pages,$limit,$order,$brand,$pattern,$category,$recent,$searchCategory,$item,$itemName){

global $borderClass;

//this generates the numerical page navigation

	$pageNav="
         <div class='row tableHead twelvePixels $borderClass[$category]'>
               <div class='cell sixteenColumns' id='searchPageNavs'>";

     for($i=1; $i<= $pages; $i++){
        $nextpos=(($i-1)*$limit)+1;
        $current="";
          if($i==1){
         	//$style="style='text-decoration:none;font-weight:bold;'";
          	$current="current";
          }
          
          $pageNav.="<span data-new='2' href='#' id='pageNum$i' data-nextPos='$nextpos' class='pageLink $current'>$i</span>";

        if($i<$pages){
            $pageNav.=" | ";
          }
     }

    $pageNav.="</div></div>";
    
    
    return $pageNav;
    
    //$sortContent2=  
    
}


function generateSearchResults($brand,$pattern,$cat,$searchItem,$sgift,$searchCategory,$h2,$monogram,$keywords,$city,$state){

global $start;
global $displaylimit;
global $pos;
global $order;
global $borderClass;
global $giftG,$sI;
global $metaKeywords;

global $keyCat;
global $staticcats;
global $bgs;
global $h2Text;
global $re;
global $rw;
global $oldPattern;
global $oldCategory;
global $featureHeaderAdded;
global $cats;
global $limit;
global $origTerms;

global $db;


$rowTemplate		= file_get_contents("/home/asyoulik/public_html/includes/itemRowTemplate.html");
$panelTemplate		= file_get_contents("/home/asyoulik/public_html/includes/itemPanelTemplate.html");
$thumbNailTemplate	= file_get_contents("/home/asyoulik/public_html/includes/itemThumbnailTemplate.html");

//global $h2Content;

$class="search";

$bindTag = array(
	':brand'	=>$brand,
	':pattern'	=>$pattern,
	':cat'		=>$cat,
	':monogram'	=>$monogram	
);

$stmt = "SELECT * FROM inventory WHERE (quantity IS NOT NULL and quantity!=0) and retail>0 and display=1";

$psquery = "SELECT * FROM inventory WHERE (quantity IS NOT NULL and quantity!=0 and retail>0 and display=1 and category='ps'";


if( $pattern && $pattern != "all" ){
	
 	$where.=" AND (pattern=\"$pattern\"";
	
		if(strtolower($pattern) == "francis i"){
			
			$where .= " OR pattern=\"$pattern (old hallmarks)\"";
			
		}
	
	$where .= ")";

}

//echo $brand;
if( $brand && $brand!="all" ){
	
	$bwhere = "";	

	if( strpos($brand, ":" )>0){
		
		$brands = explode(":", $brand);
		foreach( $brands as $b ){
			if( $b != "" ){
				
				$bwhere .= " brand=\"$b\" OR";
			
			}
		}
		
	$bwhere = substr($bwhere, 0,strlen($bwhere)-3);
	$bwhere = " AND (".$bwhere.")";
	//echo $bwhere;
	$where .= $bwhere;

	}
	else{
		$where.=" AND brand=\"$brand\" "; 	
	}
 }
//echo "<script>console.log('$query')</script>";
// category 'g' is a dummy category assigned to gifts, which is a sub classication applicable to all categories, so do not filter by it

if($cat && $cat!="g"){

 if( $cat == "f" ){
	 
	 $where .= " AND (category='f' OR category='sp' OR category='ps' or category='fcs')";
	
 }
 
 else{
  	$where.=" AND category=\"$cat\"";
 }

}

if($monogram){$where.= " AND monogram=1";}

$sI = $searchItem;

if( $searchItem ){

	$searchItem = str_replace("%26", "&", $searchItem);
	$searchItem = str_replace("%2B", "+", $searchItem);
	$searchItem = str_replace("%20", " ", $searchItem);

//echo $searchItem."<br>";
   $words = split(" ",$searchItem);
   
   $searchWords ='(0';
   
   foreach($words as $v) { 
	   
      $searchWords.=" or item regexp '[[:<:]]".str_replace("+",' ',$v)."[s]?[[:>:]]'"; 
    
    }
   
   $searchWords .= ")";
   
   $where .= " and (item regexp '[[:<:]]".$searchItem."[[:>:]]' or soundex('$searchItem')=soundex(item) or $searchWords)";

 } 

if( $searchCategory ){
	
	$where .= " AND searchCategory=$searchCategory";

}

if( $state ){
	
	$where .= " AND state='$state'";

}
if( $city ){
	
	$where .= " AND city='$city'";

}

if( $sgift == 1 ){
	
	$where .= " AND gift='y'";
	
} 

if( $keywords ){
 
 $keyWordArr = split("%", $keywords);
 
 	//$where.=" AND keywords like '%$keywords%'";
   foreach( $keyWordArr as $key){
	   $where .= " AND (keywords like(\"$key\") OR instr(keywords, \"$key\")) ";
   }
   
}

$stmt.=$where." ORDER BY `time` DESC ";

$psquery.=$where." ORDER BY listOrder";

if( !$order||$order == "" ){
	
	$order="brand, pattern, listOrder, category, item";

}

$stmt.=",".$order;

//echo $query;

if( !$limit ){
	
	$limit=100;

}

$displaylimit = $limit;

if( !$pos ){ $pos=1; }

$start = $pos-1;
//get total count including featured and non-featured items
$query = $db->prepare($stmt); 

$query->execute(); 

//mysql_query($query);

$result = $query->fetchAll();

$n = count($result);
// mysql_num_rows($totalResult);

if( $n > 0 ){

//if its looking for featured items $recent will be 1
if( $recent == 1 ){


  if( $sgift == 1 ){
	  
   $bgcolor=$bgs["g"];
  
  }
  
  else{

	   $bgcolor=$bgs[$category];

  }
   

  $class="searchrecent";
  
}

//calculate number of pages
$pages = ceil( $n/$limit );
$x = ( $n%10 );

$i = 1; 

$beginResults = "<div class='searchResultsSub' id='searchResultsListing'>
			 <form name='itemsForm'>";           

$endResults = "</form></div>";


if($n>1){
	
	$displayOptions = displayOptions($n,$pos,$limit,$order,$brand,$pattern,$cat,$recent,$searchCategory,$searchItem,$n,$monogram,$keywords,$city,$state);

}
else{
	
	if( $n == 1 ){
		
	 $displayOptions = "<div class='row tableHead $borderClass[$category]' id='searchResultsOptions'>
	 					<div class='cell twoColumns'><strong>$n result</strong></div>
	 					  <div class='cell fourteenColumns' id='searchResultsPosition'>
                                
                          </div>
	 				</div>
	 				";
	}
	
} 
	
if( $pages > 1 ){
	 
	$topPageNav = resultPageNavigation($pages,$limit,$order,$brand,$pattern,$cat,$recent,$searchCategory,$sI,$itemName);
	$bottomPageNav = str_replace($borderClass[$cat],"",$topPageNav);

}
else{
	
	$topPageNav = "<div class='row tableHead twelvePixels $borderClass[$category]'>
               <div class='cell sixteenColumns' id='searchPageNavs'>
               </div>
               </div>";
}

    
//begin specific search results

  if( !$searchItem ){
	  
    if( $pattern || $brand ){
	    
     $h2Text = makePatternName($pattern,$brand);     
     
     $h2Text .= " $staticcats[$cat]";
     $h2Text = str_replace("SilverCare", "Care Products", $h2Text);
     $h2Text = str_replace("SilverStorage", "Storage Products", $h2Text);
     $h2Text = str_replace("Silver All","Silver",$h2Text);
     
     if( $category == "xm" ){ $h2Text .= " Ornaments"; }
    
    } 
  
  }
  else
   {
   if( $keywords ){
	
	   $h2Text = "Search results for $keywords";
   }
	  $h2Text = "";   
   }

    $stmt .= " LIMIT $start,$displaylimit ";

	//echo "<script type='text/javascript'>console.log('".$stmt."');</script>";

//query database and list results
$query	= $db->prepare($stmt); //mysql_query($query);

$query->execute();

$result = $query->fetchAll();

foreach( $result as $row ){

	
	extract($row);
	
	$brandfname 			= "";
	$category 				= strtolower($category);
	$dimension 				= str_replace(array("\\","in."," \"","~"),array('',"\"","\"","\""),$dimension); 
	$handleImage 			= "";
	$handleTest 			= "";
	$instock 				= abs($quantity);
	$imagecontent 			= "";
	$imgTitle 				= "";
	$originField 			= "";
	$patternHandleHeader 	= "";
	$patternHeader 			= "";
	$patternName 			= "";
	$patternfname 			= "";
	$price =	($sale)?$sale:$retail;
	$productImage 			= "";
	$similarItemsC 			= "";
	$weightField 			= "";
	
	//retrieve handle image
	$handleImage			= "/HANDLES/".strtoupper(substr($row[pattern],0,1))."/".str_replace($re,$rw,strtoupper("$row[pattern] $row[brand]")).".jpg";
	//echo $handleImage;
	
	if( !file_exists( "/home/asyoulik/public_html".$handleImage) ){
	 	$handleImage="";
	}
	
    //get image variable
	if( $image && file_exists("/home/asyoulik/public_html/productImages/_BG/$image") ){	
		 $productImage="/productImages/_BG/$image";
	}	
	else{
	    if($handleImage){
		    $productImage=$handleImage;
	    }
	    else{
		$productImage="/productImages/_SM/noimage.jpg";
		}
	}
		
    $brandfname=($brand)?createFileName("/home/asyoulik/public_html/",$keyCat[$category],"",$brand,""):"nofile.html";

    $imgTitle=getImageTitle($pattern,$brand,$item,$monogram);
    $imagecontent="<img title='$imgTitle' style='max-width:250px' src='$productImage'>";
    
    $itemName=ucwords(strtolower($item));
    
    $itemName = str_replace(" Hh", " HH", $itemName);

    $keyword=($pattern=="CHRISTOFLE")?"Silverplate":"Sterling Silver";   
    $monogram=($monogram)?" (monogrammed)":'';

	if($cat=="cs" && ($origin || $city || $state)){
                            	$o=getOrigin($origin,$city,$state);
                            	$originField.="<br>Origin: $o";	
    }
    
    $patternfname = ( $pattern )?createFileName("/home/asyoulik/public_html/",$keyCat[$category],$pattern,$brand,""):"nofile.html";
    
	$patternName = makePatternName($pattern,$brand);
   
    $statURL="/showItem.php?product=$row[id]";
    
    if($similarItems && !$sI){
		$similarItemsC="<strong>
                            <a href='productSearch.php?category=$category&searchItem=$item&sI=1' class='$class'>
                              View Similar Items
                            </a>
                        </strong>";
    }
	
	if($weight){$weightField="<br>$weight troy oz";}	 
                
    //find replace arrays for templates       
	$find=array(
		 			"{{bgcolor}}",
		 			"{{class}}",
		 			"{{dimension}}",
		 			"{{id}}",
		 			"{{image}}",
		 			"{{imagecontent}}",
		 			"{{imgTitle}}",
		 			"{{instock}}",
		 			"{{item}}",
		 			"{{itemName}}",
		 			"{{monogram}}",
		 			"{{origin}}",
		 			"{{pattern}}",
		 			"{{patternName}}",
		 			"{{price}}",
		 			"{{similarItems}}",
		 			"{{statURL}}",
		 			"{{weight}}"
		 			);
		 		
	if(file_exists($brandfname)){
		
		$bfilelink=str_replace("/home/asyoulik/public_html/","",$brandfname);
		
	}
		
	else{
		
		$bfilelink=rewriteSearchURL($pattern,$brand,$searchCategory,$category,$recent,$item);
	
	}
		
	if(file_exists($patternfname)){
		
		$pfilelink=str_replace("/home/asyoulik/public_html/","",$patternfname);
		
	}
	else{
		
		$pfilelink=rewriteSearchURL($pattern,$brand,$searchCategory,$category,$recent,$item);
	
	}


	if($searchItem){
	 $newItem=$itemName;
	 
	 if(strpos($newItem," ")===false && substr($newItem,-1)!="s" && !is_numeric(substr($newItem,-1))){
		 $newItem.="s";
	 }
	 
	   if($h2Text!=""){
	   		//if(strpos($h2Text,$newItem)===false){$h2Text.=", $newItem";}
		}
	
		else{
			//$h2Text.=$newItem;
		}
	}
	
	
	
	     $currentPattern=$patternName;
	   
	     if($cat=="f"||$cat=="sp"||$cat=="fcs"||$cat=="ps"){
	     //this is only for large flatware category
	     //if pattern has changed, create new pattern section
	     if($currentPattern!=$oldPattern){
		    
		//add pattern header field    
		if($oldPattern){
			$prevSectionBuffer="<div class='row'>
                          <div class='cell sixteenColumns'>
                          </div> 
                         </div>
                   ";
		}
		else{
			$prevSectionBuffer="";
		}
		 //reset categories   
		$oldCategory="";    
		$oldPattern=$currentPattern;	    
	    $patternHeader= "<!--this is the handle image code -->
                <div class='row'>
                     <div class='cell sixteenColumns centered'>
                        <a href='$pfilelink' style='text-decoration:none'>
                            <h2 class='h2PatternHeader' id='h2PatternHeaderFlatware'>            
                                $currentPattern
                            </h2>
                        </a>
                     </div>
                </div>";
                
			//insert handle image if available
			if($handleImage){
			$patternHandleHeader.="<div class='row'>
                     <div class='cell sixteenColumns centered'>
                        <img class='handleImageHeader' src='$handleImage' title='$patternName $keyword Flatware' alt='$patternName $keyword Flatware'>
                     </div>
                 </div>";
			}
                                    
	    }
	    
	     //add new category header
         $currentCategory=$category;  
         if($currentCategory!=$oldCategory){ 
                $categoryHeader= "<div class='row'>
                            <div class='cell sixteenColumns centered'>
                                <a href='$pfilelink'>$patternName $keyword $cats[$currentCategory]</a>
                            </div>
                    </div>"; 
            $oldCategory=$currentCategory;
         }
	
		 }
	
		 $bgcolor="";
		 
		 //featured items
		 if($time == "n"){
		 
		 $bgcolor = $bgs[$cat];
		 
		 if( $featureHeaderAdded == false ){
			 
			$c .= "<div class='row tableHead $bgcolor'>
			
                <div class='cell sixteenColumns centered'>
                    <strong class='recentadesc'>
                    FEATURED ITEMS:
                    </strong>
                </div>
            </div>";	
            
           $featureHeaderAdded = true; 	
            
		 }
		 
		 /*end if N bracket*/
		 }
		 else{
		
			if( $sgift == 1 ){
			
				$bgcolor = $bgs["g"];
			
			}	
			
			if( $featureHeaderAdded == true ){
				
				$featureHeaderAdded = false;
			
			}   
		 
		 }


		 $replace=array(
			 
		 			$bgcolor,
		 			$class,
		 			$dimension,
		 			$id,
		 			$productImage,
		 			$imagecontent,
		 			$imgTitle,
		 			$instock,
		 			$item,
		 			$itemName,
		 			$monogram,
		 			$originField,
		 			$pattern,
		 			$patternName,
		 			$price,
		 			$similarItemsC,
		 			$statURL,
		 			$weightField
		 			
		 			);


		 //non-featured items		 
		 if($instock > 0){
			 
		 	if( $category == 'ps' ){
			 	
			 //use the panel template
			 	$currentRow = $panelTemplate;
			 	$currentRow = str_replace($find, $replace, $currentRow);
			 	
			 	if($pc == 0){
			 		$currentRow = "<div class='row $bgcolor' style='border:1px solid #eee;width:99%;text-align:center;'>".$currentRow;
			 	}
			 	
			 	$pc++;
			 	//reset pc counter
			 	if( $pc == 3 ){
				 	
			 		$currentRow .= "</div>";
			 		$pc = 0;
			 	
			 	}
			 	
			}
			
			else{
			    //$currentRow=$thumbNailTemplate;
		  	 	$currentRow = $thumbNailTemplate;
		  	 	
		  	 	$currentRow = str_replace($find, $replace, $currentRow);
		  	 	if($pc > 0){
		  	 		//if this is the case then that means we haven't closed the place setting row
		  	 		$pc = 0;
		  	 		$currentRow = "</div>".$currentRow;
		  	 	}
		  	}

		    $c .= $currentRow; 
	     }
	    
	 $metaKeywords .= updateKeywords( $category,$patternName,$brand,$itemName,$monogram );
	
	}//end while

	$c = $displayOptions.$topPageNav.$beginResults.$c.$endResults;//.$bottomPageNav.$displayOptions;
	
}

else{

if($origTerms){
	$itemsNotfound="\"$origTerms\"";
}
else{
    
	if($pattern){
	  if($brand){
		  $itemsNotfound=ucwords(strtolower($pattern. " by ". $brand));
	  }
	  
	  $wishlistVariables="brand=".urlencode($brand)."&pattern=".rawurlencode($pattern);
	}
	else{
		if($brand){
			$itemsNotfound=ucwords(strtolower($brand));
			$wishlistVariables="brand=".rawurlencode($brand);
		}
	
	}
 
 if($itemName){
   $itemsNotfound.=$itemName;
 }
 else{
 	$itemsNotfound.=" $keyCat[$category]";
 }
 
 $itemsNotfound=trim($itemsNotfound);

}

	$c = $beginResults."<div class='row'>
		<div class='cell sixteenColumns'>
		  Sorry, no results were found for $itemsNotfound.		  
		</div>
	</div>";
	
	if($pattern || $brand){
	$c .= "<div class='row'>
		<div class='cell sixteenColumns'>
			If you would like to be notified when we receive inventory, please 
			<a href='wishlist.php?$wishlistVariables'>Click Here to add items to your wishlist</a>
		</div>
	</div>";
	}


	$c .= $endResults;

}

//echo "\n\rconsole.log('".str_replace("'","\'",$c)."');</script>";
//echo $c;

 return $c;


}

function getOrigin($country,$city,$state){
	
	$origin="";
	$originArray[0]=$city;
	$originArray[1]=$state;
	$originArray[2]=$country;
  
	  for($i=0;$i<3;$i++){
		  if($originArray[$i]){
		  	if($origin && $originArray[$i]){$origin.=", ";}
		  	$origin.=$originArray[$i];
		  	$origin=trim($origin);
		  }
	  }

	  return $origin;
	  
}

//end generate search results
?>