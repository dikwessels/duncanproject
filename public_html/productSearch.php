<?php
ini_set("display_errors","1");
extract($_GET);
require("GzipCompress.php");

include("/home/asyoulik/public_html/categoryArrays.php");
include_once("/home/asyoulik/connect/mysql_pdo_connect.php");
include("/home/asyoulik/public_html/staticHTMLFunctions.php");
include("/home/asyoulik/public_html/searchDisplayFunctions.php");
include("/home/asyoulik/public_html/seoFunctions.php");
include("/home/asyoulik/public_html/generateSearchResults.php");

$metaKeywords = "";
$lastBrand = "";
$lastPattern = "";
$lastItem = "";
$h2Text	= "";
$pageDescription = "";

//consoleLog($brand);
//	consoleLog($pattern);
/**** BEGIN DECLARATIONS ****/

/* BEGIN MAIN FUNCTION */
  //$pageTitle=$keywords;



if( $gift == 1 ){$category="g";}

  $pageCategory = $keyCat[$category];
  $pageOnload = $onLoad[$category];
  $pageHeadID = $pageHeadIDs[$category];
  $pageHeadImage = "/images/ayliss_title_".$pageHeadImages[$category].".jpg";
  $pageHeadImageID = $pageHeadImageIDs[$category];

  $catLinksID=$catLinksIDs[$category];

  $li=$liClass[$category];
  $mainContentHeaderImage = $mainContentHeaderImages[$category];
  
  $h1ContainerID=$h1ContainerIDs[$category];
  
  $h1ID=$h1IDs[$category];

  $sterling=( $pattern == "CHRISTOFLE" )?"Silverplate ":"Silver ";
  
  if($category=="bs"){$sterling="";}

  $headerText=$sterling.$staticcats[$category];
  
  $headerText = str_replace("SilverCare", "Care Products", $headerText);
  $headerText = str_replace("SilverStorage", "Storage Products", $headerText);
  $headerText = str_replace("Silver All","Silver",$headerText);
  $headerText = str_replace("Silver Coin Silver","Coin Silver",$headerText);
  
  $home = $homePage[$category];
  if($home == ""){
	  $home = "#";
  }
  if($brand){
  	$breadCrumbLink = getBreadCrumbLink($category,$pattern,$brand,FALSE);  
  }
  else{
	  $breadCrumbLink = "";
  }
  if( $category == "xm" ){$headerText.=" Ornaments";}
  
  $h1 = "<a class='h1Link' href='$home' title='$title' alt='$title'>$headerText</a>";
 
  $cat = $staticcats[$category];
  
  $metaKeywords .= "silver shop, silver shop new orleans,silverware shop new orleans,silver ware shop new orleans,
silverware shop,silver ware $cat,silver ware,silverware,silver silverware,$cat silver,";

  $styleSheet = $style[$category];
  
  $otherLinkMenu = file_get_contents("/home/asyoulik/public_html/otherlinks.php");

if($origin){
	$arr=explode(",", $origin);
	$city=$arr[0];
	$state=$arr[1];
}


$content .=	generateSearchResults($brand,$pattern,$category,$searchItem,$gift,$searchCategory,$h2,$monogram,$keywords,$city,$state);

echo "<script type='text\javascript'>console.log('Content is:".$content."');</script>";

if($h2 &&!$ignoreH2){

$h2=urldecode($h2);
$h2=str_replace("SilverCare","Care Products",$h2);
$h2=str_replace("SilverStorage", "Storage Products",$h2);
$h2=str_replace("Silver All", "Silver", $h2);


if($searchItem){$h2="Silver ".$h2;}
else{
 if($brand ||$pattern &&($category!="cp"&&$category!=""&$category!="stp")){ 
		$h2.=" Silver"." ".$staticcats[$category];
 }
}

$h2Content="<div class='row tableHead'>
 	<div class='cell sixteenColumns $borderClass[$category]'>
 		<h2 class='searchResultsH2' id='$h2ID[$category]'>$h2</h2>
 	</div>
 </div>";
}
 
else{
 if($h2Text){
	 $h2Text = str_replace("Unknown,","", $h2Text);
	 
 $h2Content = "<div class='row tableHead'>
 	<div class='cell sixteenColumns $borderClass[$category]'>
 		<h2 class='searchResultsH2' id='$h2ID[$category]'>$h2Text</h2>
 	</div>
 </div>";
 
 }
}
//$h2=str_replace(" All", "Silver", $h2Content);
$content = $h2Content.$content;

//this has to come at the end because it redefines a pre-existing variable.
$catLinkMenu = include("/home/asyoulik/public_html/categoryLinks.php");

$copyright = include_once("copyright.php");

$pageTitle = $headerText." | As You Like it Silver Shop, New Orleans, Louisiana";

if( $h2 ){
	$pageTitle = $h2.", ".$pageTitle;
}
else{
	
 if( $h2Text ){
	$pageTitle = $h2Text.", ".$pageTitle;
 }
 
}

$pageDescription = $headerText.",".$h2Text;

$tempFindArr = array("<!--onLoad-->",
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
                     "<!--breadCrumb-->",
                     "<!--h2-->",
                     "<!--li class-->",
                     "<!--mainContentHeaderImage-->",
                     "<!--sideMenu-->",
                     "<!--copyright-->");


  $tempRepArr = array($pageOnload,
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
                    $h1,
                    $breadCrumbLink,
                    $h2Text,
                    $li,
                    $mainContentHeaderImage,
                    $sideMenu,
                    $copyright);

$pageTemplate = file_get_contents($_SERVER["DOCUMENT ROOT"]."includes/searchTemplate.html");

$searchPage = str_replace($tempFindArr,$tempRepArr,$pageTemplate);

$searchPage = str_replace('<!--searchResults-->',$content,$searchPage);

echo $searchPage;


ob_flush();
?>
