<?php

ob_start();
//require("GzipCompress.php");
//ini_set("display_errors","1");

include_once("/connect/mysql_connect.php");
include_once("/home/asyoulik/public_html/categoryArrays.php");
include_once("/home/asyoulik/public_html/staticHTMLFunctions.php");

extract($_GET);

$c=$cat=$category="bs";

$scriptContent="
<script type=\"text/javascript\">
brands= new Array();";
$scriptContent.=file_get_contents("/home/asyoulik/public_html/includes/$c.inc");
$scriptContent.=include("/home/asyoulik/public_html/categoryPageJavascript.php");

$copyright=include_once("/home/asyoulik/public_html/copyright.php");

$searchcategory="(category='$category')";	

$searchForm=include("/home/asyoulik/public_html/categorySearchForm.php");		
//$featuredItem=getFeaturedItem($cat);


  $pageTitle="Baby Silver, Baby Cups, Infant Feeding Spoons | As You Like It Silver Shop, New Orleans, Louisiana";
  $pageDescription="Item: $litem,Maker: $lbrand,Pattern: $lpattern,Category: sterling silver $lcat,Price: \$$itemPrice";

if($gift==1){$category="g";}

  $pageCategory=$keyCat[$category];
  $pageOnload=$onloadCodes[$category];
  $pageHeadID=$pageHeadIDs[$category];
  $pageHeadImage="/images/ayliss_title_".$pageHeadImages[$category].".jpg";
  $pageHeadImageID=$pageHeadImageIDs[$category];
  $styleSheet=$style[$category];
  $otherLinkMenu=file_get_contents("/home/asyoulik/public_html/otherlinks.php");
    
  $catLinksID=$catLinksIDs[$category];

  $li=$liClass[$category];
  
  $mainContentHeaderImage=$mainContentHeaderImages[$category];
  
  $h1ContainerID=$h1ContainerIDs[$category];
  $h1ID=$h1IDs[$category];

  $sterling=($pattern=="CHRISTOFLE")?"Silverplate ":"Silver ";
  
  if($category=="bs"){$sterling="";}

  $headerText=$sterling.$staticcats[$category];
  if($category=="xm"){$headerText.=" Ornaments";}

  $sideMenuID=$sideMenuIDs[$category];
  
  $sideMenu=getSideMenu($category);
//echo "hello line 123";
 $catLinkMenu=include("/home/asyoulik/public_html/categoryLinks.php");


$brandlinks=getBrandLinks($category);

$tempFindArr=array("<!--category-->",
					"<!--brandScript-->",
					"<!--onLoad-->",
	                 "<!--pageTitle-->",
                     "<!--keywords-->",
                     "<!--cs-->",
                     "<!--pageDescription-->",
                     "<!--pageHeadID-->",
                     "<!--pageHeadImage-->",
                     "<!--pageHeadImageID-->",
                     "<!--otherLinks-->",
                     "<!--catLinksID-->",
                     "<!--catLinks-->",
                     "<!--category-->",
                     "<!--h1ContainerID-->",
                     "<!--h1ID-->",
                     "<!--h1-->",
                     "<!--h2-->",
                     "<!--li class-->",
                     "<!--mainContentHeaderImage-->",
                     "<!--sideMenuID-->",
                     "<!--sideMenu-->",
                     "<!--searchForm-->",
                     "<!--featuredItems-->",
                     "<!--brandLinks-->",
                     "<!--copyright-->");


  $tempRepArr=array($category,
  					$scriptContent,
  					$pageOnload,
                    $pageTitle,
                    $metaKeywords,
                    $styleSheet,
                    $pageDescription,
                    $pageHeadID,
                    $pageHeadImage,
                    $pageHeadImageID,
                    $otherLinkMenu,
                    $catLinksID,
                    $catLinkMenu,
                    $pageCategory,
                    $h1ContainerID,
                    $h1ID,
                    $headerText,
                    $h2Text,
                    $li,
                    $mainContentHeaderImage,
                    $sideMenuID,
                    $sideMenu,
                    $searchForm,
                    $featuredItem,
                    $brandlinks,
                    $copyright);

//echo "script called";
$pageTemplate=file_get_contents("/home/asyoulik/public_html/includes/categoryPageTemplateNew.html");
$searchPage=str_replace($tempFindArr,$tempRepArr,$pageTemplate);
$searchPage=str_replace('<!--searchResults-->',$content,$searchPage);

echo $searchPage;
ob_flush();

function getBrandLinks($cat){

global $staticcats;
global $keyCat;
global $borderClass;
global $brandContainerIDs;

$query="SELECT DISTINCT brand FROM inventory WHERE category=\"$cat\" AND (quantity>0 or quantity<0) ORDER BY brand";
	$result=mysql_query($query);

	//$rc=mysql_num_rows($result);

//$rowcount=ceil($rc/8);

$i=0;

while($row=mysql_fetch_assoc($result)){
	extract($row);
	$bfilelink=createFileName("/home/asyoulik/public_html/",$staticcats[$cat],"",$brand,"");
	
	if(file_exists($bfilelink)){
		$bfilelink=str_replace("/home/asyoulik/public_html","",$bfilelink);
	}	
	else{
		$brand=urlencode($brand);
		$bfilelink="productSearch.php?category=$cat&brand=$brand";
	}
	
	
	$brandName=ucwords(strtolower($row[brand]));
	
	$brandContent="
		<li>
			<h2>
			<a class=\"sideMenuLink\" title=\"".$brandName." Silver Hollowware\" href=\"$bfilelink\">".$brandName."</a>
			</h2>
		</li>";
	/*
	if($i<$rowcount){
	  if($i==0){
		  $brandlist.="<div class=\"cell twoColumns\">
		  <ul class=\"sideMenuList\">";
	  }
      $brandlist.=$brandContent;
	 $i++;
	}

	else{
	 $brandlist.=$brandContent."</ul></div>";
	 $i=0;
	}
	*/


$brandlist.=$brandContent;

	
}


$brandlist="
<div class=\"brandContainer\" id=\"$brandContainerIDs[$cat]\">
<ul class=\"sideMenuList\">
<li class=\"bottomBorder\">
<h3>$keyCat[$cat] by brand:</h3>
</li>
$brandlist
</ul>
</div>
";


 /*$brandlist="<div class=\"row\">
 			 <div class=\"sixteenColumns\">
 				View Inventory from the following Manufacturers:
 			  </div>
 			
 			</div>
 			<div class=\"row fullWidth\">
 				$brandlist
 			</div>
 			";
*/

 return $brandlist;
}




function getSideMenu($cat){
//echo "getSideMenu called";
global $staticcats;

/*
	$specialCatArray=array("ps"=>"Place Settings",
							"sp"=>"Serving pieces",
							"fcs"=>"Complete Sets"	
			);
*/

  $query="SELECT * FROM sideMenu WHERE category=\"$cat\"";
  
  //echo $query;
  $result=mysql_query($query);
  $sideMenu="<ul class=\"sideMenuList\">";
  
  $sideMenu.="<li class=\"bottomBorder\">
  				<a class=\"sideMenuLink\" href=\"productSearch.php?category=$cat\">Complete List</a>
  			 </li>";
  
  $path="/home/asyoulik/public_html/";
  
  
  while($row=mysql_fetch_assoc($result)){
    extract($row);
    
     $searchTerms=explode("&",$searchText);

	 foreach($searchTerms as $s){
	  
	   $values=explode("=", $s);
	   if($values[0]=="brand"){$brand=$values[1];}
	   if($values[0]=="item"){$item=$values[1];}
	   if($values[0]=="pattern"){$pattern=$values[1];}  	 
	    
	   } //end foreach searchTerms

	 
	$staticFileName=createFileName("/home/asyoulik/public_html/",$staticcats[$category],$pattern,$brand,$text);
	$sideMenu.="<!--$staticFileName-->";
    $staticFileName=checkForFile($staticFileName);
    
    //$staticFileName=getStaticFileName($text,$category);
    
    if($staticFileName!=""){
	    $fileLink=str_replace("/home/asyoulik/public_html","",$staticFileName);
    }
    else{	
      	$searchText=str_replace("&item=", "&searchItem=", $searchText);
      	$fileLink="productSearch.php?category=$cat&h2=".urlencode($text)."&".$searchText;
    }
    
    $sideMenu.="<li>
    			 <h2>
    			  <a class=\"sideMenuLink\" href=\"$fileLink\" title=\"$text\">$text</a>
    			 </h2>
    			</li>";
    	  
  }
  
  $sideMenu.="</ul>";

  return $sideMenu;

}





?>

