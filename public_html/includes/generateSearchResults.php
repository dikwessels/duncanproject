<?php

$re=array("&",",",'.','#','by','BROTHERS','INTERNATIONAL');
$rw=array("AND",'','','','','BROS','INTL');
$oldPattern="";
$oldCategory="";

$featureHeaderAdded=false;

function displayOptions($n,$pos,$limit,$order,$brand,$pattern,$category,$recent,$searchCategory,$searchItem,$nResults,$monogram){
	//display the sorting dropdown and if there are greater than 10 results, the display options dropdown
	global $borderClass;

  $so="<div class=\"row tableHead $borderClass[$category]\" id=\"searchResultsOptions\">
  		<form name=\"searchValues\" id=\"searchVariables\">
                    <input type=\"hidden\" name=\"searchCategory\" value=\"$searchCategory\">
                    <input type=\"hidden\" name=\"category\" value=\"$category\">
                    <input type=\"hidden\" name=\"template\" value=\"$template\">
                    <input type=\"hidden\" name=\"searchItem\" value=\"$searchItem\" id=\"item\">
                    <input type=\"hidden\" name=\"recent\" value=\"$recent\">
                    <input type=\"hidden\" name=\"brand\" value=\"$brand\">
                    <input type=\"hidden\" name=\"pattern\" value=\"$pattern\">
                    <input type=\"hidden\" name=\"pos\" value=\"$pos\">
                    <input type=\"hidden\" name=\"order\" value=\"$order\">
                    <input type=\"hidden\" name=\"gift\" value=\"$gift\">
                    <input type=\"hidden\" name=\"limit\" value=\"$limit\">
                    <input type=\"hidden\" id=\"results\" name=\"nResults\" value=\"$nResults\">
                    <input type=\"hidden\" name=\"monogram\" value=\"$monogram\">
                    
                <div class=\"cell twoColumns middleAlign\">
                	<strong>$n results</strong>
                </div>";
  
  //add 'Now showing n of N
  $so.="<div class=\"cell fourColumns middleAlign\" id=\"searchResultsPosition\">
                                Now Showing $pos - ".(($pos+$limit-1>$n)?$n:$pos+$limit-1)."
                            </div>
            <div class=\"cell twoColumns middleAlign\">Sort by:</div>
	    <div class=\"cell fourColumns middleAlign\">
             <select id=\"resultsOrder\" class=\"searchSort\" onchange=\"sortResults('$category','$brand','$pattern','$recent','$searchCategory','$item','$itemname','$pos','$limit');\">
                <option value=\"category,item,pattern,brand\" ".(($order=="item,pattern,brand")?"selected":"").">Item A to Z</option>
                 <option value=\"category,item DESC,pattern,brand\" ".(($order=="item,pattern,brand")?"selected":"").">Item Z to A</option>
                <option value=\"pattern,brand,category,item\" ".(($order=="pattern,brand,item")?"selected":"").">Pattern</option>
                <option value=\"brand,pattern,category,item\" ".(($order=="brand,pattern,item")?"selected":"").">Manufacturer</option>
                <option value=\"dimension,pattern,brand\"".(($order=="dimension,pattern,brand")?"selected":"").">Dimensions</option>
                <option value=\"retail ASC\" ".(($order=="retail ASC pattern,brand,category,item")?"selected":"").">Price: Low to High</option>
                <option value=\"retail DESC\" ".(($order=="retail DESC,pattern,brand,category,item")?"selected":"").">Price: High to Low</option>
             </select>  
            </div>";
          
            
if($n>10){
            
           $so.="<div class=\"cell threeColumns rightAlign middleAlign\">
                <select class=\"searchSort\" id=\"displayNResults\" name=\"limit\" onChange=\"changeDisplayLimit('$category','$brand','$pattern','$recent','$searchCategory','$item','$itemname','0');\">
                    <option value=\"10\" ". (($limit==10)?'':'').">Display 10 Items
                    <option value=\"50\" ". (($limit==50)?'':'').">Display 50 Items
                    <option value=\"100\" selected ". (($limit==100)?'':'').">Display 100 Items
                </select>
            </div>";
         }

         else{
         	$so.="<div class=\"cell threeColumns rightAlign\"></div>";
         	}
         

$so.="</form></div>";

return $so;
	
}

function resultPageNavigation($pages,$limit,$order,$brand,$pattern,$category,$recent,$searchCategory,$item,$itemName){

global $borderClass;

//this generates the numerical page navigation

	$pageNav="
         <div class=\"row tableHead twelvePixels $borderClass[$category]\">
               <div class=\"cell sixteenColumns\" id=\"searchPageNavs\">";

     for($i=1; $i<= $pages; $i++){
        $nextpos=(($i-1)*$limit)+1;
          if($i==1){$style="style=\"text-decoration:none;font-weight:bold;\"";}
          
          $pageNav.="<a id=\"pageNum$i\" $style class=\"$class\" href=\"javascript:changeResultsPage('$nextpos','pageNum$i');\">
                        $i
                </a>";

        if($i<$pages){
            $pageNav.=" | ";
          }
     }

    $pageNav.="</div></div>";
    
    
    return $pageNav;
    
    //$sortContent2=  
    
}


function generateSearchResults($brand,$pattern,$cat,$searchItem,$sgift,$searchCategory,$h2,$monogram,$where){

global $start;
global $displaylimit;
global $pos;
global $order;
global $borderClass;
global $giftG,$sI;
global $metaKeywords;
global $template;
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
//global $h2Content;

$class="search";

$query="SELECT * FROM inventory WHERE (quantity IS NOT NULL and quantity!=0) and display=1";


if($pattern && $pattern!="all"){
 $query.=" AND pattern=\"$pattern\"";
}
if($brand && $brand!="all"){
 $query.=" AND brand=\"$brand\""; 
}

// category 'g' is a dummy category assigned to gifts, which is a sub classication applicable to all categories, so do not filter by it

if($cat && $cat!="g"){

 if($cat=="f"){
	 $query.=" AND (category=\"f\" OR category=\"sp\" OR category=\"ps\" or category=\"fcs\")";
 }
 
 else{
	 $query.=" AND category=\"$cat\"";
 }

}

$sI=$searchItem;

if($searchItem){

	$searchItem=str_replace("%26", "&", $searchItem);
	$searchItem=str_replace("%2B", "+", $searchItem);
	$searchItem=str_replace("%20", " ", $searchItem);

//echo $searchItem."<br>";
   $words=split(" ",$searchItem);
   $searchWords='(0';
   
   foreach($words as $v) { 
      $searchWords.=" or item regexp '[[:<:]]".str_replace("+",' ',$v)."[s]?[[:>:]]'"; 
    }
   $searchWords.=")";
   
   $query.=" and (item regexp '[[:<:]]".$searchItem."[[:>:]]' or soundex('$searchItem')=soundex(item) or $searchWords)";
 } 

if($searchCategory){$query.=" AND searchCategory=$searchCategory";}
if($monogram){$query.= " AND monogram=1";}

if($sgift==1){$query.= " AND gift='y'";} 

if($where){$query.=$where;}

$query.=" ORDER BY `time` DESC ";


if(!$order||$order==""){
		$order="brand, pattern, listOrder, category, item";
}

$query.=",".$order;

if(!$limit){
	$limit=100;

}
$displaylimit=$limit;

if(!$pos){$pos=1;}

$start=$pos-1;
//get total count including featured and non-featured items
$totalResult=mysql_query($query);

$n=mysql_num_rows($totalResult);

if($n>0){

//if its looking for featured items $recent will be 1
if($featured==1){


  if($sgift==1){
   $bgcolor=$bgs["g"];
   }
   else{
	   $bgcolor=$bgs[$category];
   }
   
//  $bgcolor=$bgs[$category];
  $class="searchrecent";
}

//calculate number of pages
$pages=ceil($n/$limit);
$x=($n%10);

$i=1; 

$beginResults="<div class=\"searchResultsSub\" id=\"searchResultsListing\">
				<div class=\"searchResultsSub\" id=\"searchResultsOverlay\">
				 <div class=\"row\">
				   <div class=\"cell sixteenColumns centered\">
				    <img src=\"/images/resultsLoader.gif\"><br>
				    Loading...
				   </div>
				 </div>
				</div>
				<form name=\"itemsForm\">";           

$endResults="</form></div>";


if($n>1){
	$displayOptions=displayOptions($n,$pos,$limit,$order,$brand,$pattern,$cat,$recent,$searchCategory,$searchItem,$n);

}
else{
	if($n==1){
	 $displayOptions="<div class=\"row tableHead $borderClass[$category]\" id=\"searchResultsOptions\">
	 					<div class=\"cell twoColumns\"><strong>$n result</strong></div>
	 					  <div class=\"cell thirteenColumns\" id=\"searchResultsPosition\">
                                
                          </div>
	 				</div>
	 				";
	}
	
} 
	
if($pages>1){ 
	$topPageNav=resultPageNavigation($pages,$limit,$order,$brand,$pattern,$cat,$recent,$searchCategory,$sI,$itemName);
	$bottomPageNav=str_replace($borderClass[$cat],"",$topPageNav);
}
else{
	
	$topPageNav= "<div class=\"row tableHead twelvePixels $borderClass[$category]\">
               <div class=\"cell sixteenColumns\" id=\"searchPageNavs\">
               </div>
               </div>";
}

    
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

$query.=" LIMIT $start,$displaylimit";
//echo $query."<br>";

//query database and list results
$result=mysql_query($query);

while($row=mysql_fetch_assoc($result)){
	extract($row);
 
	$dimension=str_replace("\\",'',$dimension); 
	$category=strtolower($category);
	$instock=abs($quantity);
	$price=($sale)?$sale:$retail;
	$folder=strtoupper(substr($pattern,0,1));

	if (!$image || !file_exists("/home/asyoulik/public_html/productImages/_BG/$image")){
			$handle="/HANDLES/".strtoupper(substr($pattern,0,1));
			$handle.="/";
			$handle.=strtolower($pattern)."by".strtolower($brand).".jpg";
			
			$image=(file_exists($handle))?$handle:'/productImages/_TN/noimage_th.jpg';
		}
	else {
		        $image='/productImages/_TN/'.substr($image,0,-4)."_TN.jpg"; 
		     }
		 
	$monogram=($monogram)?" (monogrammed)":'';
    $imgTitle=getImageTitle($pattern,$brand,$item,$monogram);

    $statURL = staticURL($pattern,$brand,$staticcats[$category],$category,$item,$id);
    $keyword=($pattern=="CHRISTOFLE")?"Silverplate":"Sterling Silver";
	//$item=str_replace("/","", $item);        
    $patternName=makePatternName($pattern,$brand);
    $itemName=ucwords(strtolower($item));
                     
    $brandfname=($brand)?createFileName("/home/asyoulik/public_html/",$keyCat[$category],"",$brand,""):"nofile.html";
    $patternfname=($pattern)?createFileName("/home/asyoulik/public_html/",$keyCat[$category],$pattern,$brand,""):"nofile.html";
	
	if(file_exists("$brandfname")){
		$bfilelink="http://www.asyoulikeitsilvershop.com/".str_replace("/home/asyoulik/public_html/","",$brandfname);
		}
		
	else{
		$bfilelink=rewriteSearchURL($pattern,$brand,$searchCategory,$category,$recent,$item);
		}
		
	if(file_exists("$patternfname")){
		$pfilelink="http://www.asyoulikeitsilvershop.com/".str_replace("/home/asyoulik/public_html/","",$patternfname);
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
			    $c.="<div class=\"row\">
                          <div class=\"cell sixteenColumns\">
                          </div>
                         </div>
                        ";
		    }
		 //reset categories   
		$oldCategory="";    
		$oldPattern=$currentPattern;
	    
	    $handle="/HANDLES/$folder/".str_replace($re,$rw,strtoupper("$pattern $brand")).".jpg"; 
	    
	    $c.= "<!--this is the handle image code -->
                <div class=\"row\">
                     <div class=\"cell sixteenColumns centered\">
                        <a href=\"$pfilelink\" style=\"text-decoration:none\">
                            <h2 class=\"h2PatternHeader\" id=\"h2PatternHeaderFlatware\">            
                                $currentPattern
                            </h2>
                        </a>
                     </div>
                </div>";
		
		$c.=((file_exists("/home/asyoulik/public_html".$handle))? "<div class=\"row\">
                                        <div class=\"cell sixteenColumns centered\">
                                            <img class=\"handleImageHeader\" src=\"$handle\" title=\"$patternName $keyword Flatware\" alt=\"$patternName $keyword Flatware\">
                                        </div>
                                    </div>":'');
                                    
	    }
	    
	    //add new category header
          $currentCategory=$category;  
        	if ($currentCategory!=$oldCategory){ 
                $c.= "<div class=\"row\">
                            <div class=\"cell sixteenColumns centered\">
                                <a href=\"$pfilelink\">$patternName $keyword $cats[$currentCategory]</a>
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
			$c.="<div class=\"row tableHead $bgcolor\">
                <div class=\"cell sixteenColumns centered\">
                    <strong class=\"recentadesc\">
                    FEATURED ITEMS:
                    </strong>
                </div>
            </div>";	
            
           $featureHeaderAdded=true; 	 
		 }	 
		 
		 $c.= "<div class=\"row $bgcolor\">
			
			<div class=\"cell threeColumns centered imageThumbnail\">
               <a href=\"$statURL\" class=$class>
                 <img src='{$image}' class=\"productThumbnail\" title=\"$imgTitle\" alt=\"$imgTitle\">
                 <br>
                 <span class=\"imgCaption\">(click for details)</span>
               </a>
             <!-- end item image div -->
            </div>

			<div class=\"cell sevenColumns $bgcolor\">   
               <h3 class=\"searchResultsH3\">
                 <a href=\"$statURL\" class=$class>
                 	$itemName $monogram<br>
                 	$patternName
                 </a>
               </h3>
                            
               <p class=$class>
                  <strong>$dimension";

                             
                             //if($weight){$c.="<br>$weight troy oz";}
                               
                               $c.="</strong>
                            </p>
                              <p class=\"$class\">
                                <strong class=\"itemPrice\">\$$price</strong>
                            </p>
              <!-- end item information div -->
              </div>";

			$c.="<div class=\"cell fiveColumns $bgcolor\">
                   <div class=\"spacer\">
                    <strong class=\"itemQty\">In Stock: </strong>
                   </div>

                   <div class=\"spacer centered\">
                    <strong class=\"itemQty\">$instock</strong>
                   </div>
                             
                   <p class=$class>
                    <input type=\"button\" value=\"Add\" class=\"searchResultAddButton\" onClick=\"javascript:location='http://www.asyoulikeitsilvershop.com/addItem.php?id=$id&quantity='+this.form.quantity$id.value+'&temp=h'\">
 
                    <input class=\"staticItemAddQty\" type=\"text\" value=\"1\" size=\"2\" name=\"quantity$id\">
                  </p>";
                  
    if($similarItems && !$sI){
			    $similarItemsC="<strong>
                                <a href=\"http://www.asyoulikeitsilvershop.com/showProductsSEO.php?category=$category&item=$item&sI=1\" class=$class>
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
		 if($featureHeaderAdded==true){$featureHeaderAdded=false;}
		 	 $c.="<div class=\"row $bgcolor\">

		<div class=\"cell eightColumns\">
                     <h3 class=\"searchResultsH3\">
                     <strong>
                      <a href=\"http://www.asyoulikeitsilvershop.com$statURL\" class=$class title=\"$imgTitle\">$item</a>$monogram
                     </strong>
                    </h3>
                     <p class=$class>
                     <strong>$dimension".(($weight)?", $weight troy oz":'')."</strong>
                    </p>
                </div>
 
		<div class=\"cell twoColumns\">
                    <p class=$class><strong class=\"itemPrice\">\$$price</strong></p>
                </div>
                
                <div class=\"cell alignRight fiveColumns\">
                                   <div class=\"spacer\">
                    <strong class=\"itemQty\">In Stock: </strong>
                   </div>

                   <div class=\"spacer centered\">
                    <strong class=\"itemQty\">$instock</strong>
                   </div>
                    <p class=$class>
                     <strong>
                      <input class=\"searchResultAddButton\" type='button' value='Add' onClick=\"javascript:location='/addItem.php?id=$id&quantity='+this.form.quantity$id.value+'&temp=h'\">&nbsp;
                     </strong>
                     <input class=\"staticItemAddQty\" type='text' value='1' size=2 name=quantity$id>
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
		$c.="<div class=\"row tableHead $bgcolor\">
                <div class=\"cell sixteenColumns centered\">
                    <strong class=\"recentadesc\">
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
    
	$c.= "<div class=\"row $bgcolor\">
			
			<div class=\"cell threeColumns centered imageThumbnail\">
               <a href=\"$statURL\" class=$class>
                 <img src='{$image}' class=\"productThumbnail\" title=\"$imgTitle\" alt=\"$imgTitle\">
                 <br>
                 <span class=\"imgCaption\">(click for details)</span>
               </a>
             <!-- end item image div -->
            </div>

			<div class=\"cell sevenColumns $bgcolor\">   
               <h3 class=\"searchResultsH3\">
                 <a href=\"$statURL\" class=$class title=\"$imgTitle\">
                 	$itemName $monogram<br>
                 	$patternName
                 </a>
               </h3>
                            
               <p class=$class>
                  <strong>$dimension";

                             
                             //if($weight){$c.="<br>$weight troy oz";}
                               
                               $c.="</strong>
                            </p>
                              <p class=\"$class\">
                                <strong class=\"itemPrice\">\$$price</strong>
                            </p>
              <!-- end item information div -->
              </div>";

			$c.="<div class=\"cell fiveColumns $bgcolor\">
                   <div class=\"spacer\">
                    <strong class=\"itemQty\">In Stock: </strong>
                   </div>

                   <div class=\"spacer centered\">
                    <strong class=\"itemQty\">$instock</strong>
                   </div>
                             
                   <p class=$class>
                    <input type=\"button\" value=\"Add\" class=\"searchResultAddButton\" onClick=\"javascript:location='http://www.asyoulikeitsilvershop.com/addItem.php?id=$id&quantity='+this.form.quantity$id.value+'&temp=h'\">
 
                    <input class=\"staticItemAddQty\" type=\"text\" value=\"1\" size=\"2\" name=\"quantity$id\">
                  </p>";
                  
    if($similarItems && !$sI){
			    $similarItemsC="<strong>
                                <a href=\"http://www.asyoulikeitsilvershop.com/showProductsSEO.php?category=$category&item=$item&sI=1\" class=$class>
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
     $metaKeywords.=updateKeywords($category,$patternName,$brand,$itemName,$monogram);
	}//end while

$c=$displayOptions.$topPageNav.$beginResults.$c.$endResults;//.$bottomPageNav.$displayOptions;

/*if($featured==1){
	$c=$displayOptions.$topPageNav.$beginResults.$c;
}
else{
 if($featuredN>0){
	$c=$c.$endResults.$bottomPageNav.$displayOptions;
 }
}
*/
}

else{
	$c=$beginResults."<div class=\"row\">
		<div class=\"cell sixteenColumns\">
		  Sorry, no results were found for $pattern $brand $staticcats[$cat] $searchItem
		</div>
	</div>".$endResults;


}


 return $c;


}//end generate search results



?>