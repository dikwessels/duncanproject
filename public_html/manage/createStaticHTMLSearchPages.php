<?php
ini_set("display_errors",1);
extract($_GET);

//clearstatcache();

include_once("/home/asyoulik/public_html/categoryArrays.php");
//contains arrays of category specific html and CSS tags

include_once("/home/asyoulik/connect/mysql_pdo_connect.php");
//database connection script

include_once("/home/asyoulik/connect/ftp_connect.php");
//ftp connection

include_once("/home/asyoulik/public_html/staticHTMLFunctions.php");
//contains createFile(),createFileName(),handleImageFile(),rewriteSearchURL(),staticURL() functions

include_once("/home/asyoulik/public_html/searchDisplayFunctions.php");
//makePatternName(), getImageTitle() functions

include_once("/home/asyoulik/public_html/seoFunctions.php");
//updateKeywords function()

$pageTemplate = file_get_contents("/home/asyoulik/public_html/searchPageTemplate.html");

$metaKeywords="";
//generic other links menu
$otherLinkMenu=file_get_contents("/home/asyoulik/public_html/otherlinks.php");
//generic category links menu
$catLinkMenu=include("/home/asyoulik/public_html/categoryLinks.php");
//generic copyright footer
$copyright=include_once("/home/asyoulik/public_html/copyright.php");

include("/home/asyoulik/public_html/generateSearchResults.php"); 	

//contains generateSearchResults() function
//$login_result=ftp_login($conn_id,"asyoulikeitsilvershop", "10Chri14Cam");

$path="/home/asyoulik/public_html/";

$insertNum=1;

function ftp_rmAll($cId,$dir) {
	
	$files = ftp_nlist($cId,$dir);
	
		foreach($files as $st) { 
			if (($st==".")  || ($st==".." )) {continue;} 

				if (substr($st,-3)!='php') {
				ftp_rmAll($cId,"$dir/$st");
				}
				else {
					echo "delete $st<BR>";
					ftp_delete($cId,"$dir/$st");
				}
			}
			echo "remove $dir<BR>";
			ftp_rmdir($cId,$dir);
}	
	
function createOldFileName($path,$v,$pattern,$brand){
				
		if($pattern!=""){
			$fname=strtolower(str_replace(array('/'),array(''),$pattern))."by".strtolower(str_replace(array('/'),array(''),$brand));
		}
		else{
			$fname=strtolower(str_replace(array('/'),array(''),$brand));		
		}
			
		$fname=str_replace(' ', '', $fname);
		$fname=str_replace("'",'',$fname);
		$fname=str_replace('&','and',$fname);
		$fname=str_replace('co.','company',$fname);
		$fname=str_replace('co ','company',$fname);
		$fname=str_replace('bros.','brothers',$fname);
		$fname=str_replace('bro ','brothers',$fname);
		$fname=str_replace('.','',$fname);
		$fname=str_replace(',','',$fname);
		
		$fname=$path.$v."/".$fname.".html";
	
	return $fname;	
}

function createGiftsPage($conn_id){

   $searchPage=staticSearchMain("","","",1,"","Gift Ideas","");
//$content=file_get_contents("http://www.asyoulikeitsilvershop.com/showProducts.php?gift=1&template=gift");
	$replace="<title> As You Like It Silver Shop: Great sterling silver gifts";
//	$content=str_replace('<title> As You Like It Silver Shop',$replace,$content);
     
	$find="<meta name='description' content=\"As You Like It Silver Shop in New Orleans Louisiana specializes in silver flatware and holloware in active, inactive and obsolete patterns.\" />";
	
	$replace="<meta name='description' content='$brand sterling silverware, $brand silver, $brand flatware, $brand hollowware, $brand jewlery, $brand baby silver, $brand antique silver, available at As You Like It Silver Shop, New Orleans, Louisiana' />";
		
	$searchPage=str_replace($find, $replace, $searchPage);

	createFile("/home/asyoulik/public_html/","silver-gift-ideas.html",$conn_id,$searchPage,"","","");

}

function createCleaningProducts($conn_id){

$searchPage=staticSearchMain("","","cp","","","Cleaning Products","");

	$replace="<title> As You Like It Silver Shop: Sterling Silver Cleaning Products by Gillis, Cape Cod";
	//$content=str_replace('<title> As You Like It Silver Shop',$replace,$content);
     
	$find="<meta name=\"description\" content=\"As You Like It Silver Shop in New Orleans Louisiana specializes in silver flatware and holloware in active, inactive and obsolete patterns.\" />";
	$replace="<meta name='description' content='As You Like It Silver Shop carries a full line of cleaning products, including our very own silver polish. We carry Rich Glo polishing cloth, Hagerty polishing cloth and Cape Code Metal Polish company products as well.'>";
	//$content=str_replace($find, $replace, $content);

	createFile("/home/asyoulik/public_html/","silver-cleaning-products.html",$conn_id,$searchPage,"","","");

}

function createStorageProducts($conn_id){
	$content=staticSearchMain("","","stp","","","Storage Products","");
	$replace="<title> As You Like It Silver Shop: Sterling Silver Storage Products by Eureka, Hagerty";
	
	//$content=str_replace('<title> As You Like It Silver Shop',$replace,$content);
     
	$find="<meta name=\"description\" content=\"As You Like It Silver Shop in New Orleans Louisiana specializes in silver flatware and holloware in active, inactive and obsolete patterns.\" />";
	
	$replace="<meta name='description' content='As You Like It Silver Shop carries storage products for sterling silver from brands like Hagerty, Rich Glo, and Cape Code Metal Polish Company'/>";
	
	//$content=str_replace($find, $replace, $content);

	createFile("/home/asyoulik/public_html/","silver-storage-products.html",$conn_id,$content,"","","");

}

function createStaticPageFile($conn_id,$content,$brand,$category,$pattern,$item){
	
	global $path;
	global $insertNum;
    global $catSubFolders;
    global $staticcats;
    
    
    $catFolder=$catSubFolders[$category];    
    
    if($catFolder){
    //echo "Category folder is $catFolder"."<br>";
     
		if (!is_dir($path.$catFolder)) {
			ftp_mkdir($conn_id,$path.$catFolder);
			//$chmod_cmd="CHMOD 0777 $path$keyCat[$category]"; 
		}

		$item=str_replace(array("&"," ","'"),array("and","-",""),$item);
		$fname = createFileName($path,$category,$pattern,$brand,$item);	
		echo $path."<br>";
		echo $catFolder."<br>";
		$fname= str_replace($path, "", $fname);
		$fname = str_replace($catFolder."/","",$fname);
		
		echo $fname;
		
		
		//increment insertNum
		$insertNum = $insertNum + createFile("/public_html/$catFolder/", $fname,$conn_id,$content,"",$brand,$item);
		
		echo "<a href='".str_replace("/home/asyoulik/public_html/","http://www.asyoulikeitsilvershop.com/",$fname)."'>".$fname."</a><br>";
		
	}	
}


function createPageTitle($cat,$pattern,$brand,$item,$seoText){
$pt="";

$testPattern=strtolower($pattern);
$testBrand=strtolower($brand);

  $silver="Silver";
  if($cat=="All"){	  
	$cat="";  
  }
  if($cat=="Baby Silver"){
	  $silver="";
  }
	if($pattern && $testPattern!="all"){
		$pt.="$pattern by $brand $silver $cat | ";//$brand $silver $cat | ";//$silver $cat | As You Like It Silver Shop";	
	}	

	if($brand && $testBrand!="all"){
		$pt.="$brand $silver $cat | ";//$silver $cat | As You Like It Silver Shop";
	}

	if($cat){$pt.="$silver $cat | ";}
	
	$pt.="As You Like It Silver Shop";
	if($seoText){
		$pt="$seoText | $pt";
	}
	
	return ucwords(strtolower($pt)); 

}

function flatwareSpecialCategorys(){

/*  end goal is to create 
	/silver-flatware/serving-pieces.html
	/silver-flatware/place-settings.html
	/silver-flatware/complete-sets.html	
*/

	global $keyCat;
	global $c;
	global $conn_id;
	global $staticcats;
	
	$specialCatArray=array("ps"=>"Place Settings",
							"sp"=>"Serving pieces",
							"fcs"=>"Complete Sets"	
			);

foreach($specialCatArray as $k=>$v){
	$itemSearch=staticSearchMain("","",$k,"","",$v,"");
	createStaticPageFile($conn_id,$itemSearch,"","Flatware","",$v);	
	}
}

function sideMenuPages(){

 //echo "HELLO<br>";
 global $keyCat;
 global $c;
 global $conn_id;
 global $staticcats;
 global $db;
 
 $stmt = "SELECT * FROM sideMenu";
 
 	$query = $db->prepare($stmt);//Query);
 	$query->execute();
  
 	$result = $query->fetchAll();
 	
 	foreach($result as $row){
     
	 extract($row);
	 $brand = "";
	 $pattern = "";
	 $item = "";

	 if($searchCategory){
		 $itemSearch=staticSearchMain("","",$category,"","",$text,$searchCategory);
		 createStaticPageFile($conn_id,$itemSearch,$brand,$staticcats[$category],$pattern,$text);
	 }
	 else{
	 $searchTerms=explode("&",$searchText);
 //echo "okay";
	 foreach($searchTerms as $s){
	  
	   $values=explode("=", $s);
	   if($values[0]=="brand"){$brand=$values[1];}
	   if($values[0]=="item"){$item=$values[1];}
	   if($values[0]=="pattern"){$pattern=$values[1];}  	 
	    
	   } //end foreach searchTerms
	   
	    if($item){
	    	$item=str_replace("&","%26", $item);
	    	//get search content
	   		$itemSearch=staticSearchMain($pattern,$brand,$category,"",$item,$text,"");
	   		//create file with content
	   		echo "$staticcats[$category]<br>";
	   		createStaticPageFile($conn_id,$itemSearch,$brand,$staticcats[$category],$pattern,$text);
	    }
	   }
	 }//end while 
	 
 }		

function staticSearchMain($pattern,$brand,$category,$gift,$searchItem,$h2,$searchCategory){
 
 global $onLoad;
 global $keyCat;
 global $pageHeadIDs;
 global $pageHeadImages;
 global $pageHeadImageIDs;
 global $pageTemplate;
 global $catLinksIDs;
 global $liClass;
 global	$mainContentHeaderImages;
 global $h1ContainerIDs;
 global $h1IDs;
 global $h2ID;
 global $staticcats;
 global $otherLinkMenu;
 global $catLinkMenu;
 global $style;
 global $homePage;
 global $metaKeywords;
 global $copyright;
 global $h2Text;

 
 $metaKeywords="";
 $lastBrand="";
 $lastPattern="";
 $lastItem="";
 $h2Text="";

/**** BEGIN DECLARATIONS ****/

/* BEGIN MAIN FUNCTION */
  $pageDescription="Item: $litem,Maker: $lbrand,Pattern: $lpattern,Category: sterling silver $lcat,Price: \$$itemPrice";
  
  $pageCategory=$keyCat[$category];
  
  $pageOnload=$onLoad[$category];
  $pageHeadID=$pageHeadIDs[$category];
  $pageHeadImage="/images/ayliss_title_".$pageHeadImages[$category].".jpg";
  $pageHeadImageID=$pageHeadImageIDs[$category];

  $catLinksID=$catLinksIDs[$category];
  if(!$searchItem){
  //don't get breadcrumb links for search item pages i.e. "Goblets etc" 
  	$breadCrumb=getBreadCrumbLink($category,$pattern,$brand,$searchItem);
  }
  $li=$liClass[$category];
  $mainContentHeaderImage=$mainContentHeaderImages[$category];

  $h1ContainerID=$h1ContainerIDs[$category];
  $h1ID=$h1IDs[$category];

  $sterling=($pattern=="CHRISTOFLE")?"Silverplate ":"Silver ";
  
  $styleSheet=$style[$category];
  
  if($category=="bs"){$sterling="";}
   
  if($category){
	 // if($gift==1){
	//	  $headerText="Silver Gift Ideas";
	 // }
	  
	  //else{
	  $headerText=$sterling.$staticcats[$category];
	  
 	  $headerText=str_replace("SilverCare", "Care Products", $headerText);
 	  $headerText=str_replace("SilverStorage", "Storage Products", $headerText);
 	  $headerText=str_replace("Silver All","Silver",$headerText);
 	  //}
 	  
 	  $h1="<a class='h1Link' href='$homePage[$category]' title='$headerText' alt='$title'>$headerText</a>";
    
    }
    else{
  	  if($gift==1){$headerText="Silver Gift Ideas";}
       else{
	    $patHeader=makePatternName($pattern,$brand);
	    $headerText=$patHeader." ".$sterling;
	   }
	    $h1=$headerText;
    }
    
  if($category=="xm"){$headerText.=" Ornaments";}

    
  $cat=$staticcats[$category];
  
  /*$metaKeywords.="silver shop, silver shop new orleans, silverware shop new orleans,silver ware shop new orleans,
silverware shop,silver ware $cat,silver ware,silverware,silver silverware,$cat silver,";
*/
  $styleSheet=$style[$category];
  
//get actual search content
//$content.=generateSearchResults($brand,$pattern,$category,$searchItem,$gift,$searchCategory);

$seoTitleText=$h2;

if($h2){
	$h2=urldecode($h2);

if($searchItem){
	$h2="Silver ".$h2;
	if($category=="cs"){$h2="Coin ".$h2;}
}

else{
 if($brand || $pattern && ($category!="cp" && $category!="" && $category!="stp" && $category!="cs")){ 
		$h2.=" Silver"." ".$staticcats[$category];
 }
 
}
	$h2Text=$h2;
}

if($category){
if($h2Text){
 $h2Content="<div class='row tableHead'>
 	<div class='cell sixteenColumns $borderClass[$category]' style='margin-left:20px'>
 		<h2 class='searchResultsH2' id='$h2ID[$category]'>$h2Text</h2>
 	</div>
 </div>";
 }
 
 if($category=="cp"){
	 $h2Content=$h2Content="<div class='row tableHead'>
 	<div class='cell sixteenColumns $borderClass[$category]' style='margin-left:20px'>
 		<h2 class='searchResultsH2' id='$h2ID[$category]'>$h2Text</h2>
 		<a href='/silver-storage-products.html' title='Silver storage products' alt='Silver storage products'>Click here for our silver storage products</a>
 	</div>
 </div>";
 }
}
//modify page title with H1 and H2 text

  if($h2Text){$pageTitle.="$h2Text | ";}
  if($patHeader){$pageTitle.="$patHeader | ";}
  if($headerText){$pageTitle.="$headerText | ";}
 
  $pageTitle=createPageTitle($staticcats[$category],$pattern,$brand,$item,$seoTitleText);
 $pageDescription=$pageTitle;
 /* $pageTitle.="As You Like It Silver Shop, New Orleans, Louisiana"
  $pageTitle=str_replace("Hollowware", "Holloware, Hollowware", $pageTitle);
  $pageDescription=$pageTitle; //." We have the following items in stock: $metaKeywords";
  */
  //$content=$h2Content;


//get page footer
 $javascriptVariables="var brand='".rawurlencode($brand)."';\n\r
 var pattern='".rawurlencode($pattern)."';\n\r
 var cat='".rawurlencode($category)."';\n\r
 var item='".rawurlencode($searchItem)."';\n\r
 var keys='".rawurlencode($keywords)."';\n\r
 var searchCat='".rawurlencode($searchCategory)."';\n\r
 var recent='".rawurlencode($recent)."';\n\r
 var monogram='".rawurlencode($monogram)."';\n\r
 var gift='".rawurlencode($gift)."';\n\r";
 
 

$tempFindArr=array(
                     "{pageTitle}",
                     "{meta keywords}",
                     "{css}",
                     "{pageDescription}",
                     "{pageHeadID}",
                     "{pageHeadImage}",
                     "{pageHeadImageID}",
                     "{otherLinks}",
                     "{catLinksID}",
                     "{catLinks}",
                     "{h1ContainerID}",
                     "{h1ID}",
                     "{h1}",
                     "{h2}",
                     "{javascriptVariables}",
                     "{breadCrumb}",
                     "{li class}",
                     "{mainContentHeaderImage}",
                     "{copyright}",
                     "{brand}",
                     "{pattern}",
                     "{category}",
                     "{item}",
                     "{keywords}",
                     "{monogram}",
                     "{recent}",
                     "{searchCategory}",
                     "{gift}"
                     );


  $tempRepArr=array(
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
                    $h1ContainerID,
                    $h1ID,
                    $h1,
                    $h2Content,
                    $javascriptVariables,
                    $breadCrumb,
                    $li,
                    $mainContentHeaderImage,
                    $copyright,
                    $brand,
                    $pattern,
                    $category,
                    $searchItem,
                    $keywords,
                    $monogram,
                    $recent,
                    $searchCategory,
                    $gift
                    );


$tempPage=$pageTemplate;

$searchPage=str_replace($tempFindArr,$tempRepArr,$tempPage);

//$searchPage=str_replace('<!--searchResults-->',$content,$searchPage);

return $searchPage;

//add code here to ftp and save the file

}

flatwareSpecialCategorys();

sideMenuPages();

$silverplateBrands=array('Christofle');

if(!$spo){
	createGiftsPage($conn_id);
	createStorageProducts($conn_id);
	createCleaningProducts($conn_id);
}

//first loop for categories
$insertNum=0;

foreach($keyCat as $k=>$v){

$stmt = "SELECT DISTINCT brand from inventory WHERE brand <>'' and category <> '' and quantity IS NOT NULL and quantity<>0 and display=1";

if($fbrand){$stmt .= " AND brand=\"$fbrand\"";}
if($k){$stmt .= " and category=\"$k\"";}

//echo $query;
$it = 0;
$query = $db->prepare($stmt);
$query->execute();


//$q = mysql_query($query);
$result = $query->fetchAll();

$rc = count($result);

echo ($v.": ".$rc." pages created<BR>");

	// second loop for brand names
	
		foreach($result as $row){
     	set_time_limit(120);
     	extract($row);
     	echo "hello!";
     	if($searchLimit&& $it>$searchLimit){exit;}

     	$searchResult=staticSearchMain("",$brand,$k,"","","","");

     	createStaticPageFile($conn_id,$searchResult,$brand,$v,$pattern,"");
   
	    $qbrand=$row['brand'];
	    
		$stmt = "SELECT distinct pattern FROM inventory WHERE pattern <> '' AND brand=\"$qbrand\" AND quantity IS NOT NULL AND quantity<>0 and display=1";
	    
	    if($fpattern){$stmt.=" AND pattern=\"$fpattern\"";}
	    
	    if($k){$stmt.=" AND category=\"$k\"";}
	       
	       $pQuery = $db->prepare($stmt);
	       $pQuery->execute();
	       $pResult = $pQuery->fetchAll();
	       
		   //$result=mysql_query($patternQuery);
	    
	    $rc = count($pResult);
	    	    
	    echo("$brand $v: $rc pattern pages created.<BR>");
		
	    	//3rd loop for pattern searches
			foreach($pResult as $pRow){
				
				$searchResult = staticSearchMain($pRow["pattern"],$brand,$k,"","","","");
				
	    		createStaticPageFile($conn_id,$searchResult,$brand,$v,$pRow["pattern"],"");
			}
		 $it++;       
		}
	 

	}

echo("$insertNum static pages created"); 

?>


