<?php

 //echo "hello";
 
ob_start();
//ini_set("display_errors","1");



 extract($_GET);
 extract($_POST);

 //$referrer=$_SERVER['HTTP_REFERER'];
 //echo $referrer;

 $referrer="<a rel=\"nofollow\" href=\"javascript:history.go(-1)\">< Back to Search Results</a>";
 
 clearstatcache();

/*********** MAIN FUNCTION **************/
include_once("/home/asyoulik/public_html/categoryArrays.php");//contains arrays of category specific html and CSS tags
include_once("/home/asyoulik/connect/mysql_pdo_connect.php");//database connection script
include_once("/home/asyoulik/public_html/staticHTMLFunctions.php");
include_once("/home/asyoulik/public_html/searchDisplayFunctions.php");//makePatternName(), getImageTitle() functions
include_once("/home/asyoulik/public_html/seoFunctions.php");//updateKeywords function()

$blankTemplate=file_get_contents("/home/asyoulik/public_html/includes/itemPageTemplate.html");

$catLinkMenu=include_once("/home/asyoulik/public_html/categoryLinks.php");

//load generic page menus
$otherLinkMenu=file_get_contents("/home/asyoulik/public_html/otherlinks.php");
$copyright=include_once("/home/asyoulik/public_html/copyright.php");

function removeAllCaps($s){
   $s=strtolower($s);
}

$filepath="";
$re=array("&",",",'.','#','by','BROTHERS','INTERNATIONAL');
$rw=array("AND",'','','','','BROS','INTL');
$dimfind=array("~","in.","inches");
$dimrep=array('"','"','"');


//if($product){$where ="id=$product";}

$query=$db->prepare("SELECT * from inventory where id=:id limit 1");
$query->bindValue(':id',$product);
$query->execute();
$result=$query->fetchAll();

 // echo mysql_num_rows($q);
$path="/home/asyoulik/public_html/";
if(count($result)>0){
   $description = "";
   
   	$row = $result[0];
     //set_time_limit(120);
     extract($row);
     $category = strtolower($category);

     // treat serving pieces, complete sets, and place settings as flatware
     // convert their category to f for the sake of using the appropriate template and styles
     
    if($category=="sp"||$category=="fcs"||$category=="ps"){$category="f";}

    $styleSheet=$style[$category];

     if (!$keyCat[$category])  {continue; }
 
     $lbrand=strtolower($brand);
     $litem=strtolower($item);
     $lpattern=strtolower($pattern);
     $lcat=strtolower($keyCat[$category]);


	 if($row[pattern]){
  $title="$row[pattern] by $row[brand]";
 }
	 else{
  if($row[brand]){$title=$row[brand];}
}

     
  $ltitle=strtolower($title);
  $itemPrice=$row[retail];
  if($row[sale]>0){$itemPrice=$row[sale];} 
   
  $catTitle=str_replace("<!pattern>", $title, $titles[$category]);
 
  $keyword=($row[pattern]=="CHRISTOFLE")?"Silverplate ":"Silver ";  
  if($category!='cp' && $category !='stp'){$keyword="silver ";}

 //10-24-10 escape quotes that were causing problems with brands with apostrophes 
  $ltitle=addslashes($ltitle);
  $litem=addslashes($litem);
  $lbrand=addslashes($lbrand);
  $lpattern=addslashes($lpattern);
  $catTitle=str_replace("'","\'",$catTitle);

  if($row[pattern]||($row[brand] && $row[brand]!="UNKNOWN")){
  	$breadCrumb=getBreadCrumbLink($category,$row[pattern],$row[brand],TRUE);
  }
  //7/19/12 
  
  $pageHeadID=$pageHeadIDs[$category];
  $pageHeadImage="/images/ayliss_title_".$pageHeadImages[$category].".jpg";
  $pageHeadImageID=$pageHeadImageIDs[$category];
  $catLinksID=$catLinksIDs[$category];

  $mainContentHeaderImage=$mainContentHeaderImages[$category];
 //added 7/16/12 
  
  $h1ContainerID = $h1ContainerIDs[$category];
  
  $h1ID = $h1IDs[$category];

  $imgTitle = getImageTitle($row[pattern],$row[brand],$row[item],$row[monogram]);
 // $pageTitle=ucwords("$ltitle $keyword $litem | \$$itemPrice | As You Like It Silver Shop, New Orleans, Louisiana");
  
  
   $pageTitle = ucwords("$ltitle $keyword $litem | \$$itemPrice | As You Like It Silver Shop, New Orleans, Louisiana"); 
  
 if($lpattern!=""){
 	$pageTitle.=ucwords(" | $lpattern $keyword $litem");
 	$catPatTag=ucwords(" | $lpattern $keyCat[$category]");
 }

 if($lbrand != ""){
 	if($lpattern != ""){$pageTitle.=ucwords(" | $lbrand $keyword $litem");}
 	$catBrandTag = ucwords(" | $lbrand $keyCat[$category]");
 }
  
 $pageTitle .= $catPatTag.$catBrandTag.ucwords(" | $ltitle silver");
 
 $pageTitle = str_replace("Hollowware", "Hollowware, Holloware", $pageTitle);
 
 // $keywords="$ltitle $keyword $litem, $ltitle silver $litem, $ltitle $keyword $keyCat[$category],$ltitle silver $keyCat[$category], silver shop new orleans, louisiana,";
 
$ogTags="<meta property=\"og:title\" content=\"".ucwords("$ltitle $keyword $litem, \$$itemPrice")."\"/>
<meta property=\"og:type\" content=\"product\" />
<meta property=\"og:url\" content=\"http://www.asyoulikeitsilvershop.com/showItem.php?id=".$row[id]."\" />
<meta property=\"og:image\" content=\"http://www.asyoulikeitsilvershop.com/productImages/_BG/".$row[image]."\" />
<meta property=\"og:site_name\" content=\"As You Like It Silver Shop\" />
<meta property=\"fb:admins\" content=\"1609494194\" />";

 
  if($lpattern){
  	$metaKeywords.="$lpattern $lbrand $keyword $litem, $lpattern $lbrand silver $keyCat[$category], $lpattern $lbrand $keyword $keyCat[$category], $lpattern $lbrand silverware, $lpattern $lbrand silver ware,";
  }
   if($lbrand){$metaKeywords.="$lbrand $keyword $litem,$lbrand silver $keyCat[$category], $lbrand $keyword $keyCat[$category], $lbrand silverware, $lbrand silver ware,";
  }
  $metaKeywords.="$keyword $litem, silver $litem, silver $keyCat[$category], $keyword $keyCat[$category],";

  $metaKeywords.="silver shop new orleans, louisiana, antique patterns, antique silver";
  
 // $pageDescription="Item: $litem,Maker: $lbrand,Pattern: $lpattern,Category: sterling silver $lcat,Price: \$$itemPrice";
  $pageDescription="$ltitle $keyword $litem: \$$itemPrice";
  
  if($lpattern!=""){
  	$pageDescription.=", $lpattern $keyword $litem, $lpattern $item, $lpattern $keyCat[$category]";
  }
  if($lbrand!="" && $lpattern !=""){
	$pageDescription.=", $lbrand $keyword $litem, $lbrand $litem, $lbrand $keyCat[$category]";
  }
  
  $pageCategory=$keyCat[$category];
  $pageOnload=$onLoad[$category];
  $li=$liClass[$category];
  
$folder=strtoupper(substr($row[pattern],0,1));

if(!$row['image'] || !file_exists("/home/asyoulik/public_html/productImages/_BG/".$row[image])) { 
	$handle = "HANDLES/$folder/".str_replace($re,$rw,strtoupper("$row[pattern] $row[brand]")).".jpg";
	
	$row['image'] = ($row[handle])? $handle:'/productImages/_TN/noimage_th.jpg'; 
	
	$imageSource = "/home/asyoulik/public_html/$handle";
	
	$imageS=(file_exists($imageSource))? "src='/$handle' title='$ltitle $litem, $title $lcat, sterling silverware' alt='$ltitle $litem, $title $lcat, sterling silverware' style='max-width:250px'><br><br><span style=\"font-size:12px\">Sorry, there is no product image available for this item.<br>We have included a picture of the pattern to assist you.</span>" :"src='/productImages/_SM/noimage.jpg' style='max-width:250px'>"; 
   $lbTag="";
   
   } 

 else{
  	$smallImageSource="/home/asyoulik/public_html/productImages/_SM/".substr($row['image'],0,-4)."_SM.jpg";
  	//$imageSource = $smallImageSource;
 	$imageSource="/home/asyoulik/public_html/productImages/_BG/".$row['image'];
 	
	$isize= getImageSize("/home/asyoulik/public_html/productImages/_BG/".$row[image]);
	$imageS="src='/productImages/_BG/".$row[image]."' style='max-width:$isize[0]"."px;' width='$isize[0]' title='$imgTitle'  alt='$ltitle $litem . Click to enlarge image.'>
	<br><span class='imgCaption'>To view an enlarged image, click on the image above.</span>";
	$lbTag="<a id='lightboxLink' rel='lightbox' href='/productImages/_BG/".$row[image]."'>";
	
	 /*onClick=\"enlarge('$row[image]',{$isize[0]},{$isize[1]},'$row[item]')\">
              ";
       */       
    $pinitDescription=str_replace("|","",$pageTitle);

    $pinterestButton="<a href=\"http://pinterest.com/pin/create/button/?url=http%3A%2F%2Fwww.asyoulikeitsilvershop.com%2FshowItem.php%3Fid%3D".$row[id]."&media=http%3A%2F%2Fwww.asyoulikeitsilvershop.com%2FproductImages%2F_BG%2F".$row[image]."&description=".$pinitDescription."\" class=\"pin-it-button\" count-layout=\"horizontal\">
    <img border=\"0\" src=\"//assets.pinterest.com/images/PinExt.png\" title=\"Pin It\" /></a>";
              
    }
    
   
  $cartButtons=getCartButtons($row[id],$imgTitle,$template[$category]);
  $copyRight="&copy; Copyright 2003-".date('Y')." As You Like It Silver Shop.";
  $dimension=str_replace("\\","",$row['dimension']);
  $dimension=str_replace($dimfind, $dimrep, $dimension);
  
 if( $row['description']!='' ){
 	/*
 	if($category == "fcs"){
	 	
 		$pieces=split(PHP_EOL, $row['description']);
 	
 	}
 	else{
	
	 	$pieces = $row['description'];
 	
 	}
 	*/
 	//$pieces = split(PHP_EOL, $row['description']);
 	//$pieces = nl2br($row['description']);
 	//$pieces = str_replace("\t","<br>",$row['description']);
 	
 	//$pieces = str_replace("\r\n", "<br>", $row['description']);
 	//$pieces=preg_split("/[\r\n]/", $row['description']);
 	$pieces=preg_split('/\r\n|\n|\r|\t/', $row['description']);
 	//$itemdescription.="<p id='item-pieces'>$pieces</p>"; //"<!-- there's a new line -->";
 
 	$itemdescription.="<p id='item-pieces'>".implode("<br>", $pieces)."</p>"; //"<!-- there's a new line -->";
 }
  
  for($l=0;$l++;$l<3){
	  $imageArray[$l]='';
  }
  
  $imageArray[0]=$row['image'];
  $imageArray[1]=$row['image2'];
  $imageArray[2]=$row['image3'];
  
  $ind=1;
  $imageGallery="";
  
  foreach($imageArray as $k=>$v){
	  
	if( ($v != '') && (substr_count($v, "noimage") == 0) ){
		
		//$imageThumb="/productImages/_SM/".substr($v,0,-4)."_SM.jpg";
		
		$imageThumb="/productImages/_BG/".$v;
		
		$lightboxImage="/productImages/_BG/".$v;
		
		$imageGallery.="<div class='cell fiveColumns bottomPadFive'>
					<a data-index='$ind' class='changepicture' data-file='$imageThumb' data-lightbox='$lightboxImage'>
						<img id='imageThumb$ind' src='$imageThumb' style='border:1px solid #666;width:80px'>
					</a>
					</div>";

		$ind++;
	}	  
  }
  
  if($imageGallery){
	  $imageGallery="<div class='row'>$imageGallery</div>";
  }

  if($row['desc']!='' && $row['desc']!=$row['description']){
  	//$description.=str_replace('"',"'",$row['desc']);
  	 $itemdescription.="<p>".stripslashes($row['desc'])."</p>";
  }
  if($row['desc2']!=''&& $row['desc2']!=$row['desc']){
	  $itemdescription.="<p>".stripslashes($row['desc2'])."</p>";

  }
  if($row['desc3']!='' && $row['desc3']!=$row['desc2'] && $row['desc3']!=$row['desc']){
	  $itemdescription.="<p>".stripslashes($row['desc3'])."</p>";
  }
  //$description=str_replace("\\","",$description);
  $monogram=($row[monogram])?"(monogrammed)":"";  
  $itemName=ucwords(strtolower($row[item]." ".$monogram));
  $itemName = str_replace(" Hh", " HH", $itemName);
  
  $itemImage="<img id='mainItemImage' class='itemImageMain' $imageS";
  
  if($lbTag){$itemImage=$lbTag.$itemImage."</a>";}
  
//  $instock = ($row[category] == 'f')?(($row[quantity]>-1)?$row[quantity]:'16'):'yes';
  $instock = $row[quantity]>-1?$row[quantity]:'16';
  $moreItems=getMoreItems($pfilelink,$bcfilelink,$bfilelink,$category,$row[pattern],$row[brand],$keyCat[$category]);

 if($pattern && $brand){
	 $patternHead=$pattern." by ".$brand;
 }
 else{
 	$patternHead=$pattern.$brand;
 }

$patternHead=ucwords(strtolower($patternHead));
$patternHead=str_replace(" By "," by ",$patternHead);
$patternHead.=" $itemName";

$headerText="<a class='h1Link' href='http://www.asyoulikeitsilvershop.com".$homePage[$category]."'>";

if($keyCat[$category]!="Baby Silver" && $keyCat[$category]!="Coin Silver"){$headerText.=ucwords(strtolower($keyword));}

  $headerText.=" ".ucwords(strtolower($keyCat[$category]))."</a>";

  $headerText=str_replace(" By "," by ",$headerText);
  $headerText=str_replace("SilverCare", "Care Products", $headerText);
  $headerText=str_replace("SilverStorage", "Storage Products", $headerText);
  $headerText=str_replace("Silver Care", "Cleaning Products", $headerText);
  $headerText=str_replace("Silver Storage","Storage Products",$headerText);
  $headerText=str_replace("Silver All","Silver",$headerText);

$shareLinks="<div class='fb-like' data-href='http://www.asyoulikeitsilvershop.com/showItem.php?id=$row[id]' data-send='false' data-width='300' data-show-faces='false'></div>";


//getShareLinks($row[id]);


 if($row[category]!='cp' && $row[category]!='stp'){
   $sugItems=suggestedItems($row[id],$category);
  }

  $brandfname="nofile.html";
  $patternfname="nofile.html";
  $brandcatfname="nofile.html";
  $brand=$row[brand];
  $pattern=$row[pattern];

 if($row[brand]!=""){
 
 //echo $row[category];
      $brand=$row[brand];	
	  if(strtolower($row[category])!="h"){$hlink=createCategoryLink("h",$brand);}
	  if(strtolower($row[category]!="f")){$flink=createCategoryLink("f",$brand);}
	  if(strtolower($row[category]!="bs")){$blink=createCategoryLink("bs",$brand);}
	  if(strtolower($row[category]!="j")){$jlink=createCategoryLink("j",$brand);}
  
  }
  }
  else{
  	$pageTitle="Item Not Found";
  	$styleSheet="";
  	$h1ContainerID="default";
  	$headerText="Item Not Found";
  }
  
  $tempFindArr=array("<!--onLoad-->",
                     "<!--pageTitle-->",
                     "<!--keywords-->",
                     "<!--ogTags-->",
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
                     "<!--backNav-->",
                     "<!--li class-->",
                     "<!--mainContentHeaderImage-->",
                     "<!--itemImage-->",
                     "<!--imageThumbnails-->",
                     "<!--shareLinks-->",
                     "<!--pinItButton-->",
                     "<!--patternHead-->",
                     "<!--item-->",
                     "<!--dimensions-->",
                     "<!--description-->",
                     "<!--price-->",
                     "<!--qty-->",
                     "<!--addItem-->",
                     "<!--moreItems-->",
                     "<!--suggestedItems-->",
                     "<!--copyright-->", 
                     "<!--hlink-->",
                     "<!--flink-->",
                     "<!--blink-->",
                     "<!--jlink-->");


  $tempRepArr=array($pageOnload,
                    $pageTitle,
                    $metaKeywords,
                    $ogTags,
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
                    $breadCrumb,
                    $referrer,
                    $li,
                    $mainContentHeaderImage,
                    $itemImage,
                    $imageGallery,
                    $shareLinks,
                    $pinterestButton,
                    $patternHead,
                    $itemName,
                    $dimension,
                    $itemdescription,
                    $itemPrice,
                    $instock,
                    $cartButtons,
                    $moreItems,
                    $sugItems,
                    $copyright,
                    $hlink,
                    $flink,
                    $blink,
                    $jlink);
 


//load fresh page template
$pageTemplate=$blankTemplate;

//populate template with item data
$pageTemplate=str_replace($tempFindArr,$tempRepArr,$pageTemplate);

//modify patternHead with proper color based on category
$patternheadcss=array("bs"=>"baby",
						"h"=>"hollowware",
						"f"=>"flatware",
						"j"=>"jewelry",
						"cl"=>"collectibles",
						"cs"=>"coin",
						"ps"=>"flatware",
						"fcs"=>"flatware");
					
$pageTemplate=str_replace('<div class="patternHead">', '<div class="patternHead '.$patternheadcss[$category].'">', $pageTemplate);						

echo $pageTemplate;
ob_flush();




?>


