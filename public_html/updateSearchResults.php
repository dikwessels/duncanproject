<?php
require("GzipCompress.php");

include("/home/asyoulik/public_html/categoryArrays.php");
include("/home/asyoulik/connect/mysql_pdo_connect.php");
include("/home/asyoulik/public_html/staticHTMLFunctions.php");
include("/home/asyoulik/public_html/searchDisplayFunctions.php");
include("/home/asyoulik/public_html/seoFunctions.php");

ini_set("display_errors", 1);

//extract($_GET);
extract($_POST);

$tabsOnly=1;

$start;
$displaylimit;
$oldPattern="";
$oldCategory="";
$featureHeaderAdded=false;

$class="search";

$stmt = "SELECT * FROM inventory WHERE (retail>0 and retail IS NOT NULL) and (quantity IS NOT NULL and quantity!=0) and display=1";

$brand = urldecode($brand);
$pattern = urldecode($pattern);

$pattern = strtolower($pattern);
$brand = strtolower($brand);

if($pattern && $pattern!="all"){$stmt.=" AND pattern=\"$pattern\"";}
if($brand && $brand!="all"){$stmt.=" AND brand=\"$brand\"";}

// category 'g' is a dummy category assigned to gifts, which is a sub classication applicable to all categories, so do not filter by it

if($cat && $cat!="g"){
 if($cat=="f"){
	 $stmt.=" AND (category='f' OR category='sp' OR category= 'ps' or category = 'fcs')";
 } 
 else{
	 $stmt.=" AND category=\"$cat\"";
 }
}

if($searchCategory){$stmt.=" AND searchCategory=$searchCategory";}

if($keywords){$stmt.=" AND keywords LIKE '%$keywords%'";}


if($searchItem){
	$searchItem=str_replace("%2B", "+", $searchItem);
	$searchItem=str_replace("%26", "&", $searchItem);
	$searchItem=str_replace("%20", " ", $searchItem);
   $words=split(" ",$searchItem);
   $searchWords='(0';
   
   foreach($words as $v) {
      $searchWords.=" or item regexp '[[:<:]]".str_replace("+",' ',$v)."[s]?[[:>:]]'"; 
    }
   $searchWords.=")";
   
   $stmt.=" and (item regexp '[[:<:]]".$searchItem."[[:>:]]' or soundex('$searchItem')=soundex(item) or $searchWords)";
 } 
 
if($sgift==1){$stmt.= " AND gift='y'";} 

$qOrder=" ORDER BY `time` DESC ";

if(!$order||$order==""){
		$order="brand, pattern, listOrder, category, item";
}

$qOrder.=",".$order;

$stmt.=$qOrder;

//echo $stmt;

if(!$limit){
	$limit=100;
}

$displaylimit=$limit;

if(!$pos){
	
	$pos=1;

}

	$start=$pos-1;

	$query = $db->prepare($stmt);
	
	$query->execute();

	$result = $query->fetchAll();
	
	$n = count($result); 



if( $n>0 ){

//if its looking for featured items $recent will be 1
if( $featured == 1 ){

  if( $sgift == 1 ){
	  
   	$bgcolor = $bgs["g"];
   
   }
   else{
	
	   $bgcolor = $bgs[$category];
   
   }
   
//  $bgcolor=$bgs[$category];
  $class="searchrecent";
}

//this will ensure the h2 header only appears once
$featuredQuery = $stmt." AND time='n'";

//calculate number of pages
//$pages=ceil($n/$limit);
//$x=($n%10);


$i=1; 


/* $searchCnt is the form showing two things
	- sorting dropdown list
	- if there are more than ten results, and option list to show different amounts of results
*/


$beginResults="<div class='searchResultsSub' id='searchResultsOverlay'>
				 <div class='row'>
				    <div class='cell sixteenColumns centered'>
				     <img src='/images/resultsLoader.gif'><br>
				     Loading...
				    </div>
				 </div>
				</div>
				<form name='itemsForm'>";            

$endResults="</form>";


 
//begin specific search results

  if(!$searchItem){
    if($pattern || $brand){
     $h2Text=makePatternName($pattern,$brand); 
     $h2Text.=" $staticcats[$cat]";
     if($category=="xm"){$h2Text.= " Ornaments";}
    } 
  
  }
  else
   {
	$h2Text="";   
   }

//echo "I was called with query $stmt <br>";


$stmt.=" LIMIT $start,$displaylimit";
//echo $query;

//query database and list results
$query = $db->prepare($stmt);
$query->execute();

$result = $query->fetchAll();

//$result=mysql_query($query);

foreach($result as $row){
	
	//while($row=mysql_fetch_assoc($result)){

	extract($row);
 
	$dimension=str_replace(array("\\","in."," \"","~"),array('',"\"","\"","\""),$dimension); 
	
	$category=strtolower($category);
	
	$instock=abs($quantity);
	
	$price=($sale)?$sale:$retail;
	
	$folder=strtoupper(substr($pattern,0,1));
    
	if (!$image || !file_exists("productImages/_BG/$image")){
			$handle="/HANDLES/".strtoupper(substr($pattern,0,1));
			$handle.="/";
			$handle.=strtolower($pattern)."by".strtolower($brand).".jpg";
			
			$image=(file_exists($handle))?$handle:'/productImages/_TN/noimage_th.jpg';
		}
	else {
			$image = "productImages/_BG/$image";
		        //$image='/productImages/_TN/'.substr($image,0,-4)."_TN.jpg"; 
		     }
		 
	$monogram=($monogram)?" (monogrammed)":'';
    $imgTitle=getImageTitle($pattern,$brand,$item,$monogram);

   // $statURL = staticURL($pattern,$brand,$staticcats[$category],$category,$item,$id);
    $statURL="showItem.php?product=$id";
    
    $keyword=($pattern=="CHRISTOFLE")?"Silverplate":"Sterling Silver";
    
    
	$item=str_replace("/","", $item);        
    $patternName=makePatternName($pattern,$brand);
    $itemName=ucwords(strtolower($item));
                      
    //$brandfname=($brand)?createFileName("search/",$keyCat[$category],"",$brand):"nofile.html";
    
	//$patternfname=($pattern)?createFileName("search/",$keyCat[$category],$pattern,$brand):"nofile.html";
		
		
	if(file_exists("/home/asyoulik/public_html/$brandfname")){
		$bfilelink="$brandfname";
		}
		
	else{
		$bfilelink=rewriteSearchURL($pattern,$brand,$searchCategory,$category,$recent,$item);
		}
		
	if(file_exists("/home/asyoulik/public_html/$patternfname")){
		$pfilelink="$patternfname";
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
	   		if(strpos($h2Text,$newItem)===false){$h2Text.=", $newItem";}
		}
	
		else{$h2Text.=$newItem;}
	}
	
    //begin row
    
    if($cat=="f"||$cat=="sp"||$cat=="fcs"||$cat=="ps"){
    //echo "Flatware template being used for category $cat <br>";
    
    //USE FLATWARE TEMPLATE
	    $currentPattern=$patternName;
	    if($currentPattern!=$oldPattern){
		    
		//add pattern header field    
		    
		    if($oldPattern){
			    $c.="<div class='row'>
                          <div class='cell sixteenColumns'>
                          </div>
                         </div>
                        ";
		    }
		 //reset categories   
		$oldCategory="";    
		$oldPattern=$currentPattern;
	    
	    $handle="/HANDLES/$folder/".str_replace($re,$rw,strtoupper("$pattern $brand")).".jpg"; 
	    
	    $c.= "<!--this is the handle image code -->
                <div class='row'>
                     <div class='cell sixteenColumns centered'>
                        <a href='$pfilelink' style='text-decoration:none'>
                            <h2 class='h2PatternHeader' id='h2PatternHeaderFlatware'>            
                                $currentPattern
                            </h2>
                        </a>
                     </div>
                </div>";
		
		$c.=((file_exists("/home/asyoulik/public_html".$handle))? "<div class='row'>
                                        <div class='cell sixteenColumns centered'>
                                            <img class='handleImageHeader' src='$handle' title='$patternName $keyword Flatware' alt='$patternName $keyword Flatware'>
                                        </div>
                                    </div>":'');
                                    
                                    
                                 


	    }
	    
	    //add new category header
          $currentCategory=$category;  
        	if ($currentCategory!=$oldCategory){ 
                $c.= "<div class='row'>
                            <div class='cell sixteenColumns centered'>
                                <a href='$pfilelink'>$patternName $keyword $cats[$currentCategory]</a>
                            </div>
                    </div>"; 
                    $oldCategory=$currentCategory;
             }
	    
	 //set background color for featured items
	 if($sgift==1){$bgcolor=$bgs["g"];}{
		 
	 }
	 $bgcolor=($time=="n")?$bgs[$cat]:"";
	 
	 if($time=="n"){
		 if($featureHeaderAdded==false){
			$c.="<div class='row tableHead $bgcolor'>
                <div class='cell sixteenColumns centered'>
                    <strong class='recentadesc'>
                    FEATURED ITEMS:
                    </strong>
                </div>
            </div>";	
            
           $featureHeaderAdded=true; 	 
		 }	 
		 
		 $c.= "<div class='row $bgcolor'>
			
			<div class='cell threeColumns centered imageThumbnail'>
               <a href='$statURL' class=$class>
                 <img src='{$image}' class='productThumbnail' title='$imgTitle' alt='$imgTitle'>
                 <br>
                 <span class='imgCaption'>(click for details)</span>
               </a>
             <!-- end item image div -->
            </div>

			<div class='cell sevenColumns $bgcolor'>   
               <h3 class='searchResultsH3'>
                 <a href='$statURL' class=$class>
                 	$itemName $monogram<br>
                 	$patternName
                 </a>
               </h3>
                            
               <p class=$class>
                  <strong>$dimension";

                             
                             //if($weight){$c.="<br>$weight troy oz";}
                               
                               $c.="</strong>
                            </p>
                              <p class='$class'>
                                <strong class='itemPrice'>\$$price</strong>
                            </p>
              <!-- end item information div -->
              </div>";

			$c.="<div class='cell sixColumns $bgcolor'>
                   <div class='spacer'>
                    <strong class='itemQty'>In Stock: </strong>
                   </div>

                   <div class='spacer centered'>
                    <strong class='itemQty'>$instock</strong>
                   </div>
                             
                   <p class=$class>
                    <input type='button' value='Add to Cart' class='searchResultAddButton' onClick=\"javascript:location='addItem.php?id=$id&quantity='+this.form.quantity$id.value+'&temp=h'\">
 
                    <input class='staticItemAddQty' type='text' value='1' size='2' name='quantity$id'>
                  </p>";
                  
    if($similarItems && !$sI){
			    $similarItemsC="<strong>
                                <a href='productSearch.php?category=$category&searchItem=$item&sI=1' class=$class>
                                 View Similar Items
                                </a>
                               </strong>";

                        }
                 $c.="       
                  <p class=$class>
                    $similarItemsC
                  </p>
                  
                  <!-- end item purchase button div -->
                </div>";
                //end row
                
                
                $c.="<!-- end row -->
                </div>";

		 
	 }
	 else{
	 //display as list item without individual image
	 //".(($weight)?", $weight troy oz":'')."
		 if($featureHeaderAdded==true){$featureHeaderAdded=false;}
		 	 $c.="<div class='row $bgcolor'>

		<div class='cell eightColumns'>
                    <p class=$class>
                     <strong>
                      <a href='$statURL' class=$class>$item</a>$monogram
                     </strong>
                    </p>
                     <p class=$class>
                     <strong>$dimension</strong>
                    </p>
                </div>
 
		<div class='cell twoColumns'>
                    <p class=$class><strong class='itemPrice'>\$$price</strong></p>
                </div>
                
                <div class='cell alignRight sixColumns'>
                                   <div class='spacer'>
                    <strong class='itemQty'>In Stock: </strong>
                   </div>

                   <div class='spacer centered'>
                    <strong class='itemQty'>$instock</strong>
                   </div>
                    <p class=$class>
                     <strong>
                      <input class='searchResultAddButton' type='button' value='Add to Cart' onClick=\"javascript:location='/addItem.php?id=$id&quantity='+this.form.quantity$id.value+'&temp=h'\">&nbsp;
                     </strong>
                     <input class='staticItemAddQty' type='text' value='1' size=2 name=quantity$id>
                    </p>
                </div>
                
                </div>
                ";   
	    
	    }
	
	 }
    
    else{
    //USE TEMPLATE FOR ALL OTHER CATEGORIES
    $bgcolor=($time=="n")?$bgs[$cat]:"";
    
    if($time=="n"){
   //echo "Hey this is the valud of the feature header variable!!: $featureHeaderAdded <br>";
	    if($featureHeaderAdded==false){
		$c.="<div class='row tableHead $bgcolor'>
                <div class='cell sixteenColumns centered'>
                    <strong class='recentadesc'>
                    FEATURED ITEMS:
                    </strong>
                </div>
            </div>";	
           $featureHeaderAdded=true; 	 
		 }	 
    }
    
    else{
	    if($featureHeaderAdded==true){$featureHeaderAdded=false;}   
    }
    
	$c.= "<div class='row $bgcolor'>
			
			<div class='cell threeColumns centered imageThumbnail'>
               <a href='$statURL' class='$class'>
                 <img src='{$image}' class='productThumbnail' title='$imgTitle' alt='$imgTitle'>
                 <br>
                 <span class='imgCaption'>(click for details)</span>
               </a>
             <!-- end item image div -->
            </div>

			<div class='cell sevenColumns $bgcolor'>   
               <h3 class='searchResultsH3'>
                 <a href='$statURL' class=$class>
                 	$itemName $monogram<br>
                 	$patternName
                 </a>
               </h3>
                            
               <p class=$class>
                  <strong>$dimension";

                             
                             //if($weight){$c.="<br>$weight troy oz";}
                               
                               $c.="</strong>
                            </p>
                              <p class='$class'>
                                <strong class='itemPrice'>\$$price</strong>
                            </p>
              <!-- end item information div -->
              </div>";

			$c.="<div class='cell sixColumns $bgcolor'>
                   <div class='spacer'>
                    <strong class='itemQty'>In Stock: </strong>
                   </div>

                   <div class='spacer centered'>
                    <strong class='itemQty'>$instock</strong>
                   </div>
                             
                   <p class=$class>
                    <input type='button' value='Add' class='searchResultAddButton' onClick=\"javascript:location='addItem.php?id=$id&quantity='+this.form.quantity$id.value+'&temp=h'\">
 
                    <input class='staticItemAddQty' type='text' value='1' size='2' name='quantity$id'>
                  </p>";
                  
    if($similarItems && !$sI){
			    $similarItemsC="<strong>
                                <a href='productSearch.php?category=$category&searchItem=$item&sI=1' class=$class>
                                 View Similar Items
                                </a>
                               </strong>";

                        }
                 $c.="       
                  <p class=$class>
                    $similarItemsC
                  </p>
                  
                  <!-- end item purchase button div -->
                </div>";
                //end row
                
                
                $c.="<!-- end row -->
                </div>";
                
                //$metaKeywords.=updateKeywords($category,$patternName,$brand,$itemName,$monogram);
                }
		
	}//end while

$c=$displayOptions.$topPageNav.$beginResults.$c.$endResults.$bottomPageNav.$displayOptions;

}

else{
if($featured==1){
	$c=$beginResults."<div class='row'>
		<div class='cell sixteenColumns'>
		  Sorry, no results were found for $pattern $brand $staticcats[$category] $item
		</div>
	</div>".$endResults;
}

}


 echo $c;


//end generate search
?>