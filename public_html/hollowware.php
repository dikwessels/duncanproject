<?php
header("Location:http://www.asyoulikeitsilvershop.com/silver-hollowware/");
exit;

ob_start();
//require("GzipCompress.php");

include_once("/connect/mysql_connect.php");
include_once("/home/asyoulik/public_html/categoryArrays.php");
include_once("/home/asyoulik/public_html/staticHTMLFunctions.php");

extract($_GET);

$c=$cat=$category="h";

$scriptContent="
<script type=\"text/javascript\">
brands= new Array();";
$scriptContent.=file_get_contents("includes/$c.inc");
$scriptContent.=include("categoryPageJavascript.php");

$copyright=include_once("copyright.php");

$searchcategory="(category='$category')";	

$searchForm=include("categorySearchForm.php");		
$featuredItem=getFeaturedItem($cat);


  $pageTitle="Silver Hollowware | As You Like It Silver Shop, New Orleans, Louisiana";
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


$tempFindArr=array("<!--brandScript-->",
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
                     "<!--copyright-->");


  $tempRepArr=array($scriptContent,
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
                    $copyright);

//echo "script called";
$pageTemplate=file_get_contents("/home/asyoulik/public_html/includes/categoryPageTemplate.html");
$searchPage=str_replace($tempFindArr,$tempRepArr,$pageTemplate);
$searchPage=str_replace('<!--searchResults-->',$content,$searchPage);

echo $searchPage;

function getFeaturedItem($cat){
global	 $showAll;
global  $cId;
global $homePage;

if($showAll==1){

$query=mysql_query("SELECT pattern,brand,item,retail,sale,id,image,description from inventory as a where (category=\"$cat\")  and time='n' and display=1 and quantity!=0 and retail>0 order by retail");

if (mysql_num_rows($query)) { 
$f="<div class=\"row tableHead\">
	<div class=\"cell sixteenColumns centered\">
		<b class=\"recentadesc\"><span class=\"featuredItemHeader\">Featured Items:</span></b>
	</div>
</div>";
	while ($row=mysql_fetch_assoc($query)) {
		$folder=strtoupper(substr($row[pattern],0,1));
		if ($row[sale]) {
			$price=$row[sale]; 
			}  
		else {
			$price=$row[retail];
		}
		
		if (!$row[image] || !file_exists("/home/asyoulik/public_html/productImages/_BG/$row[image]")) { 
			$handle="HANDLES/$folder/".str_replace($re,$rw,strtoupper("$row[pattern] $row[brand]")).".jpg";
			$row[image]=(file_exists($handle))? $handle:'productImages/_TN/noimage_th.jpg'; }  
		 else {
		 	$row[image]='productImages/_TN/'.substr($row[image],0,-4)."_TN.jpg"; 
		 }	
		 
		if ($row[pattern] && $row[brand]) {
			$title="$row[pattern] by $row[brand]"; 
		}
		else {
			$title="$row[pattern]$row[brand]&nbsp;"; 
			}

$f.="<div class=\"row\">	

			<div class=\"cell fourColumns topAlign\">
				<a href=\"hollowware.php?cId=$row[id]\" class=\"recentacqs\">
				<img class=\"noborder\" src=\"{$row[image]}\" width=\"100\">
				</a>
			</div>
			
			<div class=\"cell twelveColumns\">
			<!--begin second column -->
			  <div class=\"row \">
			    <div class=\"sixteenColumns\">
					<strong class=\"color\">$title</strong>
			    </div>	    
			  </div>
			  
			   <div class=\"row\">
			   	<!-- begin item name -->
			   	<div class=\"cell eightColumns\">
			   		<p class=searchrecent>
						<strong>
							<a href=\"hollowware.php?cId=$row[id]\">$row[item]</a>
						</strong>
					</p>
			   	</div>
			   	
			   	<div class=\"cell threeColumns leftAlign\">	
						<p class=searchrecent>
						<strong>\${$price}</strong>
						</p>
				</div>	
			   	
			   	<div class=\"cell threeColumns topAlign leftAlign\">
					<input class=\"searchResultAddButton\" type='button' value='Add Item' onClick='updateCart($row[id],1)'>
				</div>
				
				<div class=\"cell twoColumns topAlign leftAlign\">
					<input type=\"hidden\" value=\"1\" name=\"quantity$row[id]\">
					<img src=images/silverchest_add.gif name='chestimage$row[id]' onClick=\"updateCart($row[id],1);return false;\">
				</div>
				
			   	<!--end row-->
			   </div>
			   <!--end seven columns -->
			   </div>
			   <!-- end row -->
			   </div>
			   ";

		}
 }
}

else{

	$statement=($cId)?"SELECT pattern,brand,item,retail,sale,id,image as image1, image2,image3,image$cI as imageMain,`desc$cI` as description,image$nI as nextI,quantity from inventory WHERE id=$cId":"SELECT pattern,brand,item,retail,sale,id,image as image1,image2,image3,`desc` as desc1,desc2,desc3, quantity from inventory where category=\"$cat\" and image IS NOT NULL and `time`='n' and display=1 and quantity!=0 and retail>0 order by rand() limit 1";
	
 //echo $statement."<br>";
  $query=mysql_query($statement);
  if (mysql_num_rows($query)) {
  
  $f="<div class=\"row tableHead\">
	<div class=\"cell sixteenColumns centered\">
		<b class=\"recentadesc\"><span class=\"featuredItemHeader\">FEATURED ITEM:</span></b>
	</div>
</div>";
  
		$row=mysql_fetch_assoc($query);
		$row[category]=strtolower($row[category]);
		$folder=strtoupper(substr($row[pattern],0,1));
		$template=($row[bs]!='')?'bs':(($row[category]=='h')?'h':'f');
		$instock=($template=='f')?(($row[quantity]>-1)?$row[quantity]:'16'):'yes';
		
if (!$row[image1] || !file_exists("/home/asyoulik/public_html/productImages/_BG/$row[image1]")) { 
		$handle="HANDLES/$folder/".str_replace($re,$rw,strtoupper("$row[pattern] $row[brand]")).".jpg";
		$row[image1]=($row[handle])? $handle:'productImages/_TN/noimage_th.jpg'; 
		$imageS=(file_exists($handle))?$handle:"productImages/_SM/noimage.jpg";
		$caption="Sorry, there is no product image available for this item.
					<br>We have included a picture of the pattern to assist you.";
		} 
	else {
		$isize= getImageSize("productImages/_BG/".$row[image1]);
		$imageS="productImages/_SM/".substr($row[image1],0,-4)."_SM.jpg";
		$caption="To view an enlarged image, click on the image above.";
	}

if($row[sale]){
	$price=$row[sale];
}
else{
	$price=$row[retail]; 
}

if($row[pattern] && $row[brand]){ 
	$title="$row[pattern] by $row[brand]"; 
}
else{  
	$title="$row[pattern]$row[brand]"; 
}
$title.=" $row[item]";

$monogram=($row[monogram])?"(monogrammed)":'';

	if($row[image1]!=''){
		$imageB1="productImages/_BG/$row[image1]";
		$imageS1="productImages/_SM/".substr($row[image1],0,-4)."_SM.jpg";
		if($desc1){$LBcaption=$title."<br>".$desc1;}
	
	}
	if($row[image2]!=''){
		$imageB2="productImages/_BG/$row[image2]";
		$imageS2="productImages/_SM/".substr($row[image2],0,-4)."_SM.jpg";
		if($desc2){$LBcaption2=$title."<br>".$desc2;}
		
	}
	
	if($row[image3]!=''){
		$imageB3="productImages/_BG/$row[image3]";
		$imageS3="productImages/_SM/".substr($row[image3],0,-4)."_SM.jpg";
		if($desc3){$LBcaption3=$title."<br>".$desc3;}
	}

//echo $imageS1."<br>".$imageS2."<br>".$imageS3;
$f.="
<div class=\"row\">
 <div class=\"cell sixteenColumns centered\">
 	<h5 class=\"verticalPad5px\">$title</h5>
 </div>
</div>

<div class=\"row\">
	<div class=\"cell eightColumns centered\">
	 <div class=\"row\">
	   <div class=\"cell sixteenColumns\">
		<a id=\"mainFeaturedLink\" href=\"$imageB1\" rel=\"lightbox\" title=\"$LBcaption\">
			<img id=\"mainFeaturedImage\" src=\"$imageS\" title=\"$title\" alt=\"$title\">
		</a>
		<br>
		<span class=\"imgCaption\">$caption</span>";
		
if($imageS2!=''||$imageS3!=''){


	$f.="<div id=\"imageThumbnails\" class=\"row\">
			<div class=\"cell fiveColumns bottomPadFive\">
					<a href=\"javascript:changePicture('$imageS1','$imageB1','$row[desc1]')\">
					<img id=\"imageThumb1\" class=\"currentImage\" src=\"$imageS1\" width=\"75\" style=\"border:1px solid #666;\" >
					</a>
			 </div>";
			 
				if($imageS2){
		$f.="<div class=\"cell sixColumns bottomPadFive\">
					<a href=\"javascript:changePicture('$imageS2','$imageB2','$row[desc2]')\">
					<img id=\"imageThumb2\" src=\"$imageS2\" width=\"75\" style=\"border:1px solid #666;\"></a>
			</div>";
					}
					else{
						$f.="<div class=\"cell sixColumns bottomPadFive\"></div>";
					}
		
	if($imageS3){
			$f.="<div class=\"cell fiveColumns bottomPadFive\">
						<a href=\"javascript:changePicture('$imageS3','$imageB3','$row[desc3]')\">
						 <img id=\"imageThumb3\" src=\"$imageS3\" width=\"75\" style=\"border:1px solid #666;\" >
						</a>
					   </div>";
				}
	else {$f.="<div class=\"cell fiveColumns bottomPadFive\"></div>";}
	
	$f.="</div>";
	
	}
	$f.="
		</div>
	</div/";
	$f.="<!--end image column -->
	</div>
	
	<div class=\"cell topAlign eightColumns\">
	  <div class=\"row\">
	  <div class=\"cell sixteenColumns featuredItemName\">
	  	$row[item] $monogram<br>
		$row[dimension]<br>";

if ($row[weight]) {$f.="$row[weight] troy oz.";}
$f.="
	 </div>
	 </div>
	 <div class=\"row\">
	  <div class=\"cell sixteenColumns featuredItemDescription\" id=\"itemDescription\">
		<p>$row[desc1]</p>";
		if($row[desc2]){$f.="<p>$row[description2]</p>";}
		if($row[desc3]){$f.="<p>$row[description3]</p>";}
		$f.="
	  </div>
	 </div>";

	//if (!$row[nextI] && $cI) {$nI="";}
	
	/*
	if ($row[nextI]!=$row[image1]||$cI) { 
		$f.="<div class=\"row\">
		<div class=\"cell sixteenColumns alignRight\">
			<a href=\"".$homePage[$cat]."?cId=$row[id]&cI=$nI\">".(($nI)?"Read More":"Back")."</a>
		</div>	
		</div>"; 
	}
*/
	$f.="
	<div class=\"row\">
		<div class=\"cell sixColumns\">
			<b class=\"category\">Price:</b>
		</div>
		 
	<div class=\"cell tenColumns\">\$$price</div>
	</div>
	<div class=\"row\">
		<div class=\"cell sixColumns\">
			<b class=\"category\">In Stock:</b>
		</div> 
		<div class=\"cell tenColumns\">$instock</div>
	</div>	
	
	<div class=\"row\">
	
		<div class=\"cell sixColumns bottomAlign\">
			<input class=\"searchResultAddButton\" type=\"button\" value=\"Add\" onClick=\"javascript:location='addItem.php?id=$row[id]&quantity='+this.form.quantity$row[id].value+'&temp=h'\">
		</div>
		<div class=\"cell tenColumns bottomAlign\">
			<input class=\"staticItemAddQty\" type=\"text\" value=\"1\" name=quantity$row[id] size=\"2\">
		</div>
		<!--
		<div class=\"cell elevenColumns bottomAlign\">
			<img src='images/silverchest_add.gif' name='chestimage$row[id]' onClick=\"updateCart($row[id],1);setCartGraphic2();\" align=bottom width=42>
		</div>-->
	</div>



</div>
<div class=\"row\">
	<div class=\"cell sixteenColumns centered\">
		<b class=\"recentadesc\">
			<a rel=\"nofollow\" href=\"".$homePage[$cat]."?showAll=1\" class=recentadesc>View the complete list</a> of As You Like It Silver Shop's featured items in Hollowware.</b>
	</div>
</div>
";
 }
}
  
  return $f;
 
 }

function getSideMenu($cat){
//echo "getSideMenu called";
global $staticcats;
	$specialCatArray=array("ps"=>"Place Settings",
							"sp"=>"Serving pieces",
							"fcs"=>"Complete Sets"	
			);

  $query="SELECT * FROM sideMenu WHERE category=\"$cat\"";
  $query.=" AND searchCategory ORDER BY text";
  
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

