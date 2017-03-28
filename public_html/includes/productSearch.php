<?php

extract($_GET);
require("GzipCompress.php");

include("staticHTMLFunctions.php");
include("/connect/mysql_connect.php");


$metaKeywords="";
$lastBrand="";
$lastPattern="";
$lastItem="";

/**** BEGIN DECLARATIONS ****/

$borderClass=array("bs"=>"bottomBorderBlue",
                    "cl"=>"bottomBorderTan",
                    "cp"=>"bottomBorderRed",
                    "f"=>"bottomBorderTan",
                    "fcs"=>"bottomBorderTan",
                    "g"=>"bottomBorderRed",
                    "h"=>"bottomBorderRed",
                    "j"=>"bottomBorderBlue",
                    "m"=>"bottomBorderTan",
                    "ps"=>"bottomBorderTan",
                    "sp"=>"bottomBorderTan",
                    "stp"=>"bottomBorderRed",
                    "xm"=>"bottomBorderGreen");

$style=array('f'=>'_tan',
            'fcs'=>'_tan',
            'cl'=>'_tan',
            'h'=>'',
            'bs'=>'_blue',
            'j'=>'_jewelry',
            'cp'=>'',
            'sp'=>'_tan',
            'stp'=>'',
            'c'=>'',
            'xm'=>'_christmas');


$pageHeadImages=array('f'=>'t',
                        'fcs'=>'t',
                        'cl'=>'t',
                        'h'=>'r',
                        'bs'=>'b',
                        'j'=>'b',
                        'cp'=>'r',
                        'sp'=>'t',
                        'stp'=>'r',
                        'xm'=>'g');


$pageHeadIDs=array("bs"=>"babySilverPageHead",
                    "cp"=>"cleaningPageHead",
                    "cl"=>"collectiblesPageHead",
                    "f"=>"flatwarePageHead",
                    "fcs"=>"flatwarePageHead",
                    "h"=>"hollowwarePageHead",
                    "j"=>"jewelryPageHead",
                    "sp"=>"servingPageHead",
                    "stp"=>"cleaningPageHead",
                    "xm"=>"christmasPageHead");

$h1ContainerIDs=array("bs"=>"babyH1Container",
                        "cp"=>"cleaningH1Container",
                        "cl"=>"collectiblesH1Container",
                        "f"=>"flatwareH1Container",
                        "h"=>"hollowwareH1Container",
                        "j"=>"jewelryH1Container",
                        "stp"=>"storageH1Container",
                        "sp"=>"servingH1Container",
                        "xm"=>"christmasH1Container");

$h1IDs=array("bs"=>"babySilverH1",
                "cp"=>"cleaningH1",
                "cl"=>"collectiblesH1",
                "f"=>"flatwareH1",
                "h"=>"hollowwareH1",
                "j"=>"jewelryH1",
                "stp"=>"storageH1",
                "sp"=>"servingH1",
                "xm"=>"christmasH1");

$h2ID=array("bs"=>"babyH2",
                "cp"=>"cleaningH2",
                "cl"=>"collectiblesH2",
                "f"=>"flatwareH2",
                "h"=>"hollowwareH2",
                "j"=>"jewelryH2",
                "stp"=>"storageH2",
                "sp"=>"servingH2",
                "xm"=>"christmasH2");
//line 99
$liClass=array("bs"=>"babyDropdownLI",
                "cl"=>"collectiblesDropdownLI",
                "cp"=>"cleaningDropdownLI",
                "f"=>"flatwareDropdownLI",
                "g"=>"giftsDropdownLI",
                "h"=>"hollowwareDropddownLI",
                "j"=>"jewelryDropdownLI",
                "r"=>"repairsDropdownLI");

$mainContentHeaderImages=array("bs"=>"babySilverImage",
                                "cp"=>"cleaningImage",
                                "cl"=>"collectiblesImage",
                                "f"=>"flatwareImage",
                                "h"=>"hollowwareImage",
                                "j"=>"jewelryImage",
                                "sp"=>"servingImage",
                                "stp"=>"cleaningImage",
                                "xm"=>"christmasImage");

$pageHeadImageIDs=array("bs"=>"babySilverHead",
                        "cp"=>"cleaningHead",
                        "cl"=>"collectiblesHead",
                        "f"=>"flatwareHead",
                        "h"=>"hollowwareHead",
                        "j"=>"jewelryHead",
                        "sp"=>"servingHead",
                        "stp"=>"storageHead",
                        "xm"=>"christmasHead");

$catLinksIDs=array("bs"=>"babyCatLinks",
                    "cp"=>"cleaningCatLinks",
                    "cl"=>"collectiblesCatLinks",
                    "f"=>"flatwareCatLinks",
                    "h"=>"hollowwareCatLinks",
                    "j"=>"jewelryCatLinks",
                    "sp"=>"servingCatLinks",
                    "stp"=>"storageCatLinks",
                    "xm"=>"christmasCatLinks");

$sideMenus=array("bs"=>"bsMenu.inc",
                 "cl"=>"clMenu.inc",
                 "f"=>"fMenu.inc",
                 "h"=>"hMenu.inc",
                "j"=>"jMenu.inc");
             
$template=array("bs"=>"bs",
                "cl"=>"f",
                "f"=>"f",
                "fcs"=>"f",
                "g"=>"g",
                "h"=>"h",
                "j"=>"j",
                "m"=>"f",
                "ps"=>"f",
                "s"=>"h",
                "sp"=>"f",
                "xm"=>"xm");

$bgs=array("bs"=>"bgBaby",
            "cl"=>"bgCollectibles",
            "cp"=>"bgCleaning",
            "f"=>"bgFlatware",
            "fcs"=>"bgFlatware",
            "gift"=>"bgGift",
            "h"=>"bgHollowware",
            "j"=>"bgJewelry",
            "m"=>"bgManufacturer",
            "s"=>"bgSearch",
            "sp"=>"bgServingPieces",
            "stp"=>"bgStorage",
            "xm"=>"bgChristmas");

$staticcats=array("sp"=>'Flatware',
                "fcs"=>'Flatware',
                "f"=>'Flatware',
                "ps"=>'Flatware',
                "h"=>'Hollowware',
                "bs"=>'Baby Silver',
                "j"=>'Jewelry',
                "stp"=>'SilverStorage',
                "cp"=>'SilverCare',
                "xm"=>'Christmas',
                "cl"=>'Collectibles');

$keyCat=array(''=>'All',
                'sp'=>'Serving Pieces',
                'ps'=>'Flatware',
                'fcs'=>'Complete Sets',
                'f'=>'Flatware',
                'h'=>'Hollowware',
                'bs'=>'Baby Silver',
                'j'=>"Jewelry",
                'cp'=>'SilverCare',
                'stp'=>'SilverStorage',
                'xm'=>'Christmas',
                'cl'=>'Collectibles');

/**** END DECLARATIONS ****/

function getImageTitle($p,$b,$i,$m){
  if($p){
    $f=$p;
    if($b){$f.=" by $b";}
  }
  
  else{
    if($b){
     $f=$b;  
    }
  }

  if($f){
   $f.=" $i";
  }
  else
  {
   $f=$i;
  } 
 //line 200
 if($m){$f.=", monogrammed";}
 $f=strtolower($f);

 return $f;

}

function makePatternName($pattern,$brand){

 $patName=ucwords(strtolower($pattern));

                if($patName){
                  $patName.=" by ";
                  $patName.=ucwords(strtolower($brand));
                }
                else{
                    if($brand && $brand!="UNKNOWN"){
                      $patName=ucwords(strtolower($brand));
                    }
                }
 return $patName;

}

function query($cat,$w,$s) {

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

if ($order) { $o="order by $order"; }

if ($s==1) { 
            $w.=" and time='n'  and quantity!='0'"; 
            $bgcolor=$bgs[$cat];
            $class='searchrecent'; 
           $c.="
            <div class=\"row tableHead $bgcolor\">
                <div class=\"cell sixteenColumns centered\">
                    <strong class=\"recentadesc\">
                    FEATURED ITEMS:
                    </strong>
                </div>
            </div>";
            }
if ($s==2) { 
        $w.=" and time!='n' and quantity!='0'";
        $class='search'; }

if ($s==3) { 
        $w.=" and time!='n' and quantity='0'";
        $class='searchnostock';  
        }

	$query= mysql_query("SELECT * from inventory where  $w  $giftG $o  limit $start,$displaylimit");
	$start+=mysql_num_rows($query);$displaylimit-=mysql_num_rows($query);



	while ($row=mysql_fetch_assoc($query)) {
	$row[dimension]=str_replace("\\",'',$row[dimension]); 
	$row[category]=strtolower($row[category]);
	$instock=abs($row[quantity]);;
	$price=($row[sale])?$row[sale]:$row[retail];
	 
		if (!$row[image] || !file_exists("productImages/_BG/$row[image]")) { 
			$handle="/HANDLES/".strtoupper(substr($row[pattern],0,1))."/".str_replace(" ",'',strtolower($row[pattern])."by".strtolower($row[brand]).".jpg");
			$row[image]=(file_exists($handle))?$handle:'/productImages/_TN/noimage_th.jpg';
		}  
		        
		else {
		        $row[image]='/productImages/_TN/'.substr($row[image],0,-4)."_TN.jpg"; 
		     }
		 

                $imgTitle=getImageTitle($row[pattern],$row[brand],$row[item],$row[monogram]);
		
                $monogram=($row[monogram])?"(monogrammed)":'';
      		$statURL = staticURL($row[pattern],$row[brand],$staticcats[$row[category]],$row[category],$row[item],$row[id]);
 
		$brandfname="nofile.html";
		$patternfname="nofile.html";
		$pattern=$row[pattern];
		$brand=$row[brand];

		$item=str_replace("/","", $row[item]);
                
                $patternName=makePatternName($pattern,$brand);
                $itemName=ucwords(strtolower($item));
                //$itemName=trim($patternName." ".ucwords(strtolower($item)));
                
                $category=$row[category];

                
		
		
		if($brand!=""){    
			$brandfname=createFileName("search/","All","",$row[brand]);
		 
                }
/*line 300*/		
		if($row[pattern]!=""){
			$patternfname=createFileName("search/","All",$row[pattern],$row[brand]);
		}
	
		if(file_exists("/home/asyoulik/public_html/$brandfname")){
			$bfilelink="http://www.asyoulikeitsilvershop.com/$brandfname";
		}
		
		else{
		 	$bfilelink= rewriteSearchURL($pattern,$brand,$searchCategory,$category,$recent,$item); //"href=http://www.asyoulikeitsilvershop.com/showSearch/template/m/brand/$b"
		}
		
		if(file_exists("/home/asyoulik/public_html/$patternfname")){
			 $pfilelink="http://www.asyoulikeitsilvershop.com/$patternfname";
		}
		else{
			$pfilelink=rewriteSearchURL($pattern,$brand,$searchCategory,$category,$recent,$item);
		}


            //begin row

  

	$c.= "<div class=\"row $bgcolor\">
			<div class=\"cell threeColumns centered imageThumbnail\">
                            <a href=\"$statURL\" class=$class>
                                <img src='{$row[image]}' class=\"productThumbnail\" title=\"$imgTitle\" alt=\"$imgTitle\">
                                <br>
                                <span class=\"imgCaption\">(click for details)</span>
                            </a>
                        </div>

			<div class=\"cell sevenColumns $bgcolor\">
                        
                                <h3 class=\"searchResultsH3\">
                                    <a href=\"$statURL\" class=$class>$itemName $monogram<br>
                                    $patternName
                                    </a>
                                </h3>
                            
                            <p class=$class>
                                <strong>$row[dimension]".(($row[weight])?"<br>$row[weight] troy oz":'')."</strong>
                            </p>
                              <p class=\"$class\">
                                <strong class=\"itemPrice\">\$$price</strong>
                            </p>
                        </div>";


                        if($row[similliarItems] && !$sI){
			    $similarItems="<strong>
                                        <a href='http://www.asyoulikeitsilvershop.com/showSearch/template/$template/category/$category/item/$item/sI/1/category/$category/' class=$class>
                                            View Similar Items
                                        </a>
                                       </strong>";

                        }


			$c.="<div class=\"cell fiveColumns $bgcolor\">
                       
                        <div class=\"spacer\">
                            
                                <strong class=\"itemQty\">In Stock: </strong>
                           
                            </div>

                            <div class=\"spacer centered\">
                               <strong class=\"itemQty\"> $instock</strong>
                             </div>
                             
                            <p class=$class>
                            
                                    <input type=\"button\" value=\"Add\" class=\"searchResultAddButton\" onClick=\"javascript:location='http://www.asyoulikeitsilvershop.com/addItem.php?id=$row[id]&quantity='+this.form.quantity$row[id].value+'&temp=$template'\">
                            
                                <input class=\"staticItemAddQty\" type='text' value='1' size=2 name=quantity$row[id]>
                            </p>
                            <p class=$class>
                             $similarItems
                            </p>
                        </div>
                        ";

	
		
		if ($s<3) {
		//	$c.="<td><img src=/images/silverchest_add.gif name='chestimage$row[id]' onClick=\"updateCart($row[id],1);\"></td	</tr>";
			}
		else {$c.="<div class=\"cell oneColumn\"><p class=searchnostock>Out of Stock</p></div>";}

                //end row
                $c.="</div>";

                $metaKeywords.=updateKeywords($category,$patternName,$brand,$itemName,$monogram);

		}  
	return $c;
	} 


/* END QUERY FUNCTION */

function updateKeywords($cat,$pattern,$brand,$item,$monogram){
 global $lastBrand;
 global $lastPattern;
 global $lastItem;
 global $staticcats;

 $sterling="sterling";
 $silver="silver";
 $fullCat=$staticcats[$cat];


     if($pattern && $pattern!=$lastPattern){
        if(strtolower($pattern)=="christofle"){$sterling="";$silver="silverplate";}
        
        $metaKeywords.="$pattern $silver,$pattern $sterling $silver,$pattern silverware,
$pattern $sterling $silver silverware,$pattern $sterling $silver silver ware,
$pattern $silver silverware,$pattern $silver silver ware,$pattern $fullCat,
$pattern $silver $fullCat,$pattern $sterling $silver $fullCat,$silver $fullCat $patternName,
$sterling $silver $fullCat $patternName,";

        

        $lastPattern=$pattern;
     } 
              
     if($brand && $brand!=$lastBrand){

       $metaKeywords.=" $brand $silver,$brand $silver silverware,$brand $silver silver ware,$brand $sterling $silver,
$brand $sterling $silver silverware,$brand $sterling $silver silver ware,$brand silverware,
$brand silver ware,$brand $fullCat,$brand $silver $fullCat,$brand $sterling $silver $fullCat,
$brand $silver silverware $fullCat,$brand $silver silver ware $fullCat,$brand $sterling $silver silverware $fullCat,
$brand $sterling $silver silver ware $fullCat,$silver $brand $fullCat,$sterling $brand $fullCat,
$sterling $silver $brand $fullCat,$fullCat $brand,$silver $fullCat $brand,$sterling $silver $fullCat $brand";

       $lastBrand=$brand;
     }  

     if($item!=$lastItem){

        $metaKeywords.="$pattern $item $monogram,$silver $pattern $item $monogram,
$sterling $silver $pattern $item $monogram,$pattern $silver $item $monogram,$pattern $sterling $silver $item $monogram,
$sterling $silver $monogram $item $pattern,$brand $item $monogram,$silver $brand $item $monogram,
$sterling $silver $brand $item $monogram";

    
        $metaKeywords.="$sterling $silver $item,
                        $sterling $silver $monogram $item";

        if(substr($item,-1)!="s"){
          $items=$item."s";
          $metaKeywords.="$sterling $silver $items,";
        }
        
        $lastItem=$item;
     }

 return $metaKeywords;

}


/* BEGIN MAIN FUNCTION */
  $pageTitle=$keywords;
  $pageDescription="Item: $litem,Maker: $lbrand,Pattern: $lpattern,Category: sterling silver $lcat,Price: \$$itemPrice";
  $pageCategory=$keyCat[$category];

//line 400 ish 

  $pageOnload=$onLoad[$category];
  $pageHeadID=$pageHeadIDs[$category];
  $pageHeadImage="/images/ayliss_title_".$pageHeadImages[$category].".jpg";
  $pageHeadImageID=$pageHeadImageIDs[$category];

  $catLinksID=$catLinksIDs[$category];

  $li=$liClass[$category];
  $mainContentHeaderImage=$mainContentHeaderImages[$category];
  
  $h1ContainerID=$h1ContainerIDs[$category];
  $h1ID=$h1IDs[$category];

  $sterling=($pattern=="CHRISTOFLE")?"Silverplate ":"Sterling Silver ";
  
  if($category=="bs"){$sterling="Sterling ";}

  $headerText=$sterling.$staticcats[$category];

  if($item){
   $h2Text=ucwords(strtolower($item));
   if(substr($h2Text,-1)!="s"){$h2Text.="s";}
  }
   else{
   if($itemname){
     $h2Text=ucwords(strtolower($itemname));
     if(substr($h2Text,-1)!="s"){$h2Text.="s";}
   }
   else{
     $h2Text=makePatternName($pattern,$brand);
     $h2Text.=" $staticcats[$category]";
    }
  } 

  $cat=$staticcats[$category];

  $metaKeywords.="silver shop, silver shop new orleans,silverware shop new orleans,silver ware shop new orleans,
silverware shop,silver ware $cat,silver ware,silverware,silver silverware,$cat silver,";


  $styleSheet=$style[$category];
  $otherLinkMenu=file_get_contents("/home/asyoulik/public_html/otherlinks.php");


if($homePattern){ 
    list($pattern,$brand)=split(" by ",strtolower($homePattern));	 
}


$where="1  and display!='0' and (retail!=0 or sale!=0) and quantity!=0";

if ($category && $category!='') {
	$cats=explode(',',$category);
	$where.=" and (0";
	foreach ($cats as $v) { $where.=" or category='$v'";}
	$where.=")";
	} 

$order=($_GET['order'])?$_GET['order']:'brand,pattern,listOrder,item';

if ($searchCategory) { $where.=" and searchCategory='$searchCategory'";}
if ($designPeriod){$where.=" and designPeriod='$designPeriod'";}
if ($brand && $brand!='all' && $brand!='') {$where.=" and brand='$brand'";}
if ($origin) { $where.=" and origin='$origin'"; }

if ($minretail>0){
$where.=" and retail >= $minretail";
//echo $where;
}
if ($maxretail>0){$where.=" and retail <= $maxretail";}

if ($pattern && $pattern!='all' && $pattern!='') { $where.=" and  pattern = '$pattern'"; }
if ($recent) { $where.=" and time!=''"; }
	//if ($item) {$where.=" and (item regexp '[[:<:]]".$item."[[:>:]]' or soundex('$item')=soundex(item) or item like '%$item%')"; }

$sel="count(*)";


//if $itemname is specified, query will look for exact match
//if $item is specified, query will match all terms in item variable

if($itemname){
 $itemname=urldecode($itemname);
 $where.=" and item=\"$itemname\"";
}

elseif ($item) {

   $words=split(" ",$item);
   $searchWords='(0';

   foreach($words as $v) { 
      $searchWords.=" or item regexp '[[:<:]]".str_replace("+",' ',$v)."[s]?[[:>:]]'"; 
    }
   $searchWords.=")";
   $where.=" and (item regexp '[[:<:]]".$item."[[:>:]]' or soundex('$item')=soundex(item) or $searchWords)";
 }  
 
if ($gift) {$where.=" and gift='y'";//$giftG=' group by item';$sel="count(distinct(item))"; 
}
$where.=" and display=1";
	$query= mysql_query("SELECT $sel from inventory where  $where and quantity!='0' and time='n' ");
	$type[1]=mysql_result($query,0); 
	$query= mysql_query("SELECT $sel from inventory where  $where   and time!='n' and quantity!='0' ");
	$type[2]=mysql_result($query,0);
	$query= mysql_query("SELECT $sel from inventory where  $where   and time!='n' and quantity='0' ");
	$type[3]=mysql_result($query,0);
if (!$limit) {$limit=100; }
$displaylimit=$limit;
if (!$pos) { $pos=1;}

if ($pos>$type[1]) { 
	if ($pos<=$type[1]+$type[2]) { 
            $catNum=1; 
            $start=$pos-$type[1]-1; 
        }
	else  { 
            $catNum=2;
            $start=$pos-$type[1]-$type[2]-1;
         }
	}
else {$start=$pos-1; } 


$content.="<div class=\"searchResultsSub\">
            <form name=\"itemsForm\">";

//line 500ish

//perform inventory search
if ($catNum<1) {
            $content.=query($category,$where,1);
            if ($limit!=0) {
                $catNum=1;
                $start=0;
} 
}

if ($catNum<2 && $limit>0) {
            $content.=query($category,$where,2);
            if ($limit!=0) {$catNum=2;$start=0;} 
}

$content.="</form></div>";

//get number of search results for display
$query= mysql_query("SELECT $sel from inventory where $where and quantity!=0 ");

//get row count
$n=mysql_result($query,0);
//calculate number of pages
$pages=ceil($n/$limit);

$x=($n%10);

$i=1; 

$bottomBorder=($pages>1)?"":$borderClass[$category];

$searchCnt="
                <form action='http://www.asyoulikeitsilvershop.com/showProductsSEO.php'>
                    <input type=hidden name=searchCategory value='$searchCategory'>
                    <input type=hidden name=category value='$category'>
                    <input type=hidden name=template value='$template'>
                    <input type=hidden name=item value='$item'>
                    <input name=recent type=hidden value='$recent'>
                    <input type=hidden name=brand value='$brand'>
                    <input type=hidden name=pattern value='$pattern'>
                    <input type=hidden name=pos value='$pos'>
                    <input type=hidden name=order value='$order'>
                    <input type=hidden name=gift value='$gift'>
                <div class=\"row tableHead $bottomBorder\">

                <div class=\"cell twoColumns\"><strong>$n results</strong></div>";

                //add 'Now showing n of N
	        $searchCnt.="<div class=\"cell fourColumns\">
                                Showing $pos-".(($pos+$limit-1>$n)?$n:$pos+$limit-1)."
                            </div>

            <div class=\"cell twoColumns\">Sort by:</div>

	    <div class=\"cell fourColumns\">
             <select id=\"resultsOrder\" class=\"searchSort\" onchange=\"sortResults('$category','$brand','$pattern','$recent','$searchCategory','$item','$itemname','$pos','$limit');\">
                <option value=\"item,pattern,brand\" ".(($order=="item,pattern,brand")?"selected":"").">Item</option>
                <option value=\"pattern,brand,item\" ".(($order=="pattern,brand,item")?"selected":"").">Pattern</option>
                <option value=\"brand,pattern,item\" ".(($order=="brand,pattern,item")?"selected":"").">Manufacturer</option>
                <option value=\"dimension,pattern,brand\" ".(($order=="dimension,pattern,brand")?"selected":"").">Dimensions</option>
                <option value=\"retail ASC\" ".(($order=="retail ASC")?"selected":"").">Price: Low to High</option>
                <option value=\"retail DESC\" ".(($order=="retail DESC")?"selected":"").">Price: High to Low</option>
             </select>  
            </div>
            
            <div class=\"cell threeColumns rightAlign\">
                <select class=\"searchSort\" id=\"displayNResults\" name=\"limit\" onChange=\"sortResults('$category','$brand','$pattern','$recent','$searchCategory','$item','$itemname','0','$limit');\">
                    <option value=10". (($limit==10)?' selected':'').">Display 10 Items
                    <option value=50". (($limit==50)?' selected':'').">Display 50 Items
                    <option value=100". (($limit==100)?' selected':'').">Display 100 Items
                </select>
            </div>
         </div>
</form>";




   if($pages>1){ 

    $sortContent1.="
         <div class=\"row tableHead twelvePixels $borderClass[$category]\">
               <div class=\"cell sixteenColumns\">";

     for($i=1; $i<= $pages; $i++){
        $nextpos=(($i-1)*$limit)+1;
          $sortContent1.="<a class=\"$class current\" href=\"javascript:sortResults('$category','$brand','$pattern','$recent','$searchCategory','$item','$itemname','$nextpos','$limit');\">
                        $i
                </a>";

        if($i<$pages){
            $sortContent1.=" | ";
          }
     }

    $sortContent1.="</div></div>";
    $sortContent2=str_replace($borderClass[$category],"",$sortContent1);
}



if($h2Text){
$h2Content="<div class=\"row tableHead\">
  <div class=\"cell sixteenColumns $borderClass[$category]\">
   <h2 class=\"searchResultsH2\" id=\"$h2ID[$category]\">$h2Text</h2>
  </div>
 </div>";
}

//$sideMenu=file_get_contents("/home/asyoulik/public_html/includes/$sideMenus[$category]");

//this has to come at the end because it redefines 
$catLinkMenu=include("/home/asyoulik/public_html/categoryLinks.php");
$copyright=file_get_contents("copyright.php");

$tempFindArr=array("<!--onLoad-->",
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
                     "<!--sideMenu-->",
                     "<!--copyright-->");


  $tempRepArr=array($pageOnload,
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
                    $sideMenu,
                    $copyright);


$c=($n<4)?$searchCnt.$sortContent1.$content:$searchCnt.$sortContent1.$content.$searchCnt.$sortContent2;


$c=$h2Content.$c;

$pageTemplate=file_get_contents("/home/asyoulik/public_html/includes/searchTemplate.html");

$searchPage=str_replace($tempFindArr,$tempRepArr,$pageTemplate);
$searchPage=str_replace('<!--searchResults-->',$c,$searchPage);

return $searchPage;

?>
