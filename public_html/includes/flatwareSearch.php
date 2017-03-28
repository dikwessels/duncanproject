<?
extract($_GET);
extract($_POST);
require("GzipCompress.php");
include("staticHTMLFunctions.php");


/**** BEGIN DECLARATIONS ****/

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


$cats=array('sp'=>'Serving Pieces',
            'fcs'=>'Complete Sets',
            'f'=>'Flatware',
            'ps'=>'Place Settings',
            'h'=>'Hollowware',
            'bs'=>'Baby Silver',
            'j'=>'Jewelry');


$catLinksIDs=array("bs"=>"babyCatLinks",
                    "cp"=>"cleaningCatLinks",
                    "cl"=>"collectiblesCatLinks",
                    "f"=>"flatwareCatLinks",
                    "h"=>"hollowwareCatLinks",
                    "j"=>"jewelryCatLinks",
                    "sp"=>"servingCatLinks",
                    "stp"=>"storageCatLinks",
                    "xm"=>"christmasCatLinks");


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
 
 if($m){$f.=", monogrammed";}
 $f=strtolower($f);

 return $f;

}

function placeSetting($type,$p,$b,$sf) {

  global $bgs,$template;

  $statement="SELECT a.quantity as q , b.retail as r,b.id  FROM inventory AS a, inventory AS b WHERE a.pattern='$p' and a.brand='$b' and(a.item='$type Knife' or a.item='$type Fork' or a.item='$sf FORK' or a.item='TEASPOON') and a.monogram!=-1 AND b.pattern = '$p'  AND b.brand =  '$b'  AND b.item =  '$type Setting' and b.retail>0 order by a.quantity";

		$q=mysql_query($statement);
		if (mysql_num_rows($q)<4) { return ''; }
		$r=mysql_fetch_assoc($q);
		if ($r[q]) {
		    $c.="<div class=\"row\">
                    <div class=\"cell eightColumns $bgs[$template]\">
                        <p class=searchrecent>
                            <strong>$type Setting</strong>
                        </p>
                    </div>
                    
                    <div class=\"cell twoColumns $bgs[$template]\">
                        <p class=searchrecent>
                            <strong>\$$r[r]</strong>
                        </p>
                    </div>
                    
                    <div class=\"cell twoColumns centered $bgs[$template]\">$r[q]</div>
                    
                    <div class=\"cell twoColumns rightAlign\">
                        <p class=$class>
                            <strong>
                                <input type='button' value='Add' onClick=\"javascript:location='/addItem.php?id=$r[id]&quantity='+this.form.quantity$r[id].value+'&temp=$template'\">
                            </strong>
                    </p>
                    </div>
                    
                    <div class=\"cell twoColumns\">
                       <p class=$class> <input type='text' value='1' size=2 name=quantity$r[id]></p>
                    </div>

                    </div>";
		}  
    return $c;

}

function query($cat,$w,$s) {

global $start,	$displaylimit,$pos, $order, $template,$item,$brand,$pattern,$limit,$bgs,$recent;
global $cats;
global $staticcats;
global $keyCat;

if ($order) { $o="order by $order"; }

if ($s==1) { 

	$w.=" and i.time='n'  and i.quantity!='0'".(($template=='s')?'':" and category!='h'");

	$bgcolor="bgcolor=".$bgs[$template];$class='searchrecent'; }

if ($s==2) { $w.=" and i.time!='n' and i.quantity!='0'";$class='search'; }

	$query= mysql_query("SELECT i.* ,h.image as handle  from inventory as i,handles as h where  $w and i.pattern=h.pattern and i.brand=h.brand $o limit $start,$displaylimit");

	$start+=mysql_num_rows($query);$displaylimit-=mysql_num_rows($query);$cB='';

	$re=array("&",",",'.','#','by','BROTHERS','INTERNATIONAL');$rw=array("AND",'','','','','BROS','INTL');

	$row[category]=strtolower($row[category]);
	
	
	
	
	switch($s) {

	case(1):

	if (!mysql_num_rows($query)) { break; }

	$c="<div class=\"row\">
                    <div class=\"cell sixteenColumns centered\">
                        <strong class=recentadesc style='font-size:24px'>
                            FEATURED ITEMS:
                        </strong>
                    </div>
            </div>
            <div class=\"row\">
              <div class=\"cell sixColumns\">
                <a href=/showflatware.php?recent=$recent&category=$cat&brand=".urlencode($brand)."&item=".urlencode($item)."&template=$template&pattern=".urlencode($pattern)."&order=item,brand,pattern&gift=$gift&limit=$limit>Item</a>
              </div>
              <div class=\"cell fiveColumns\">
                <a href=/showflatware.php?searchCategory=$searchCategory&recent=$recent&category=$category&brand=".urlencode($brand)."&item=".urlencode($item)."&template=$template&pattern=".urlencode($pattern)."&order=item,dimension&gift=$gift&limit=$limit>Dimensions</a>
              </div>
              <div class=\"cell oneColumn\">
                <a href=/showflatware.php?recent=$recent&category=$cat&brand=".urlencode($brand)."&item=".urlencode($item)."&template=$template&pattern=".urlencode($pattern)."&order=pattern,brand,item&gift=$gift&limit=$limit>Pattern</a>
              </div>
              <div class=\"cell oneColumn\">
                <a href=/showflatware.php?recent=$recent&category=$cat&brand=".urlencode($brand)."&item=".urlencode($item)."&template=$template&pattern=".urlencode($pattern)."&order=brand,pattern,item&gift=$gift&limit=$limit>Manufacturer</a>
              </div>
              <div class=\"cell oneColumn\">
                <a href=/showflatware.php?recent=$recent&category=$cat&brand=".urlencode($brand)."&item=".urlencode($item)."&template=$template&pattern=".urlencode($pattern)."&order=retail,brand,pattern,item&gift=$gift&limit=$limit>Price</a></td><td align=center width=50><a href=/showflatware.php?recent=$recent&category=$cat&brand=".urlencode($brand)."&item=".urlencode($item)."&template=$template&pattern=".urlencode($pattern)."&order=quantity,brand,pattern,item&gift=$gift&limit=$limit>Stock</a>
              </div>
            </div>";

	while ($row=mysql_fetch_assoc($query)) {   

        $row[dimension]=str_replace("\\",'',$row[dimension]); $row[category]=strtolower($row[category]);

	$instock=abs($row[quantity]); 

		$folder=strtoupper(substr($row[pattern],0,1));

		$price=($row[sale])?$row[sale]:$row[retail];

		if (!$row[image] || !file_exists("/home/asyoulik/public_html/productImages/_BG/$row[image]")) { 

			$handle="http://www.asyoulikeitsilvershop.com/HANDLES/$folder/".str_replace($re,$rw,strtoupper("$row[pattern] $row[brand]")).".jpg";

			$row[image]=($row[handle])? $handle:'productImages/_TN/noimage_th.jpg'; }  

		 else {$row[image]='http://www.asyoulikeitsilvershop.com/productImages/_TN/'.substr($row[image],0,-4)."_TN.jpg"; }

		$monogram=($row[monogram])?"(monogrammed)":'';
   
	   $statURL = staticURL($row[pattern],$row[brand],$staticcats[$row[category]],$row[category],$row[item],$row[id]);
        
          $imgTitle=getImageTitle($row[pattern],$row[brand],$row[item],$row[monogram]);

		$brandfname="nofile.html";
		$patternfname="nofile.html";
		$spattern=$row[pattern];
		$sbrand=$row[brand];
		$scategory=$row[category];
		$sitem=str_replace("/","",$row[item]);

                $itemName=ucwords($sitem);

		$kCat=$keyCat[$category];

		if($row[brand]!=""){
			$brandfname=createFileName("search/",$kCat,"",$row[brand]);
			$b=urlencode($row[brand]);
		}
		
		if($row[pattern]!=""){
			$patternfname=createFileName("search/",$kCat,$row[pattern],$row[brand]);
		}
	
		if(file_exists("/home/asyoulik/public_html/$brandfname")){
			$bfilelink="http://www.asyoulikeitsilvershop.com/$brandfname";
		}
		else{
			$bfilelink= rewriteSearchURL($spattern,$sbrand,$searchCategory,$scategory,$recent,"","m",$order,$limit,$gift,$pos); 
		}
		
		if(file_exists("/home/asyoulik/public_html/$patternfname")){
			 $pfilelink="http://www.asyoulikeitsilvershop.com/$patternfname";
		}
		else{
					 
			 $pfilelink= rewriteSearchURL($spattern,$sbrand,$searchCategory,$scategory,$recent,"","s",$order,$limit,$gift,$pos); 
		}



		$c.= "<div class=\"row\">

		<div class=\"cell imageThumbnail\">
                        <a href=\"$statURL\" class=$class>
                         <img class=\"productImage\" title=\"$imgTitle\" alt=\"$imgTitle\" src='{$row[image]}'>
                         <br>
                         <span class=\"imageCaption\">(click for details)</span>
                        </a>
                </div>

		<div class=\"cell $bgcolor\">
		    <p class=$class>
			<b>
			<a href=\"$statURL\" class=$class>$itemName</a>
                        $monogram
                        </b>
                    </p>
                    <p class=$class>
                        <b>$row[dimension]".(($row[weight])?"<br>$row[weight] troy oz":'')."
                        </b>
                    </p>
                </div>

                <div class=\"cell $bgcolor\">
                    
                </div>";

	$c.="
	<div class=\"cell $bgcolor\">
            <p>
                <strong><a href=\"$pfilelink\">$row[pattern]</a></strong>
            </p>
        </div>  
	<div class=\"cell $bgcolor\">
            <p>
                <strong><a href=\"$bfilelink\">$row[brand]</a></strong>
            </p>
        </div>
	<div class=\"cell $bgcolor\">
            <p class=\"$class\">
                <strong>\$$price</strong>
            </p>
        </div>
	<div class=\"cell $bgcolor centered\">$instock</div>
	<div class=\"cell $bgcolor centered\">
            <p class=$class>
            <strong>
                <input type='button' value='Add' onClick=\"javascript:location='/addItem.php?id=$row[id]&quantity='+this.form.quantity$row[id].value+'&temp=$template'\">
            </strong>
            <input type='text' value='1' size=2 name=quantity$row[id]>
            </p>
         </div>
        
        </div>";
	
	
	}

	break;

	case(2):



	while ($row=mysql_fetch_assoc($query)) { 
	
	$row[dimension]=str_replace("\\",'',$row[dimension]); $row[category]=strtolower($row[category]);

	$keyword="Sterling Silver";
	
	if(strtolower($row[brand])=='christofle'){
		$keyword="Silverplate";
	}
	

	$folder=strtoupper(substr($row[pattern],0,1));

	$nB=($row[pattern] && $row[brand])?"$row[pattern] by $row[brand]":(($row[pattern] || $row[brand])?"$row[pattern]$row[brand]":"Unknown");

	if ($cB!=$nB) {

	$cC='';

		if ($cB) { 

			$c.="<div class=\"row\">
                          <div class=\"cell sixteenColumns\">

                          </div>
                         </div>
                        ";

			}	

		$cB=$nB;

		$handle="/HANDLES/$folder/".str_replace($re,$rw,strtoupper("$row[pattern] $row[brand]")).".jpg"; 

		$ptn=$row[pattern];
		$brnd=$row[brand];
		
		$patternfname="nofile.html";
		
		
		$patternfname=createFileName("search/","All",$row[pattern],$row[brand]);
	
		$testfile="/home/asyoulik/public_html/$patternfname";
		
		if(file_exists($testfile)){
			 $pfilelink="http://www.asyoulikeitsilvershop.com/$patternfname";
		}
		else{
			$pfilelink=rewriteSearchURL($pattern,$brand,$searchCategory,$category,$recent,$item,$template,$order,$limit,$gift,$pos);
			//$pfilelink="http://www.asyoulikeitsilvershop.com/showflatware.php?recent=$recent&category=$cat&brand=".urlencode($row[brand])."&template=$template&pattern=".urlencode($row[pattern]);
		}



		$c.= "<!--this is the handle image code -->
                <div class=\"row\">
                     <div class=\"cell sixteenColumns centered\">
                        <a href=\"$pfilelink\" style=\"text-decoration:none\">
                            <h2 class=\"h2PatternHeader\" id=\"h2PatternHeaderFlatware\">            
                                $cB
                            </h2>
                        </a>
                     </div>
                </div>";
		
		$c.=(($row[handle])? "<div class=\"row\">
                                        <div class=\"cell sixteenColumns centered\">
                                            <img class=\"handleImageHeader\" src=\"$handle\" title=\"$ptn by $brnd $keyword Flatware\" alt=\"$ptn by $brnd $keyword Flatware\">
                                        </div>
                                    </div>":'');
  

			//"brand,pattern,retail"
			
			$sortlink="http://www.asyoulikeitsilvershop.com/showflatware.php?recent=$recent&category=$cat&brand=".urlencode($brand)."&item=".urlencode($item)."&template=$template&pattern=".urlencode($pattern)."&order=brand,pattern,item&gift=$gift&limit=$limit";
			
			$c.="<div class=\"row\">
                                <div class=\"cell sixColumns\">
                                <a href=\"$sortlink\">Item</a>
                                <span class=\"caption\">
                                    (click item name for details)
                                </span>
                            </div>";
						
			$sortlink="http://www.asyoulikeitsilvershop.com/showflatware.php?searchCategory=$searchCategory&recent=$recent&category=$category&brand=".urlencode($brand)."&item=".urlencode($item)."&template=$template&pattern=".urlencode($pattern)."&order=item,dimension&gift=$gift&limit=$limit";
			$c.="<div class=\"cell fiveColumns\">
                                <a href=\"$sortlink\">Dimensions</a>
                                </div>"; 
			
						
			$sortlink="http://www.asyoulikeitsilvershop.com/showflatware.php?recent=$recent&category=$cat&brand=".urlencode($brand)."&item=".urlencode($item)."&template=$template&pattern=".urlencode($pattern)."&order=brand,pattern,retail&gift=$gift&limit=$limit";
			
                        $c.="<div class=\"cell oneColumn\">
                                <a href=\"$sortlink\">Price</a>
                            </div>";

			$c.="<div class=\"cell oneColumn centered\">
                                <a href='#'>Stock</a>
                            </div>
                        </div>";

		$cC='';



		}

		$patternfname=createFileName("search/",$keyCat[$row[category]],$row[pattern],$row[brand]);
	
		$testfile="/home/asyoulik/public_html/$patternfname";
		
		if(file_exists($testfile)){
			 $pfilelink="http://www.asyoulikeitsilvershop.com/$patternfname";
		}
		else{
			$pfilelink=rewriteSearchURL($row[pattern],$row[brand],$searchCategory,$row[category],$recent,"", $template,$order,$limit,$gift,$pos);
			//$pfilelink="http://www.asyoulikeitsilvershop.com/showflatware.php?recent=$recent&category=$cat&brand=".urlencode($row[brand])."&template=$template&pattern=".urlencode($row[pattern]);
		}
	
	$linktitle="";
	if($row[pattern]!=""){
	 $linktitle=strtolower($row[pattern]);
         $linktitle=ucfirst($linktitle);
	 $linktitle.= " by ";
        }
	 
        $linktitle.=ucfirst(strtolower($row[brand]));
	
	if ($cC!=strtolower($row[category])) { 
                $cC=strtolower($row[category]);

                $c.= "<div class=\"row\">
                            <div class=\"cell sixteenColumns centered\">
                                <a href=\"$pfilelink\">$linktitle $keyword $cats[$cC]</a>
                            </div>
                    </div>"; 

        }

	$instock=abs($row[quantity]);;

	$price=($row[sale])?$row[sale]:$row[retail];

	$monogram=($row[monogram])?"(monogrammed)":'';

	$bgcolor=($row[bs])?'bgcolor=ccccff':'';

 	$statURL = staticURL($row[pattern],$row[brand],$staticcats[$row[category]],$row[category],$row[item],$row[id]);
   
		$c.="<div class=\"row $bgcolor\">

		<div class=\"cell sixColumns\">
                    <p class=$class>
                     <strong>
                      <a href=\"http://www.asyoulikeitsilvershop.com$statURL\" class=$class>$row[item] </a>$monogram
                     </strong>
                    </p>
                     <p class=$class>
                     <strong>$row[dimension]".(($row[weight])?"<br>$row[weight] troy oz":'')."</strong>
                    </p>
                </div>
 
		<div class=\"cell twoColumns\">
                    <p class=$class><strong class=\"itemPrice\">\$$price</strong></p>
                </div>
                <div class=\"cell centered oneColumn\">$instock</div>
                <div class=\"cell alignRight fourColumns\">
                    <p class=$class>
                     <strong>
                      <input class=\"searchResultAddButton\" type='button' value='Add' onClick=\"javascript:location='/addItem.php?id=$row[id]&quantity='+this.form.quantity$row[id].value+'&temp=$template'\">&nbsp;
                     </strong>
                     <input class=\"searchResultAddQty\" type='text' value='1' size=2 name=quantity$row[id]>
                    </p>
                </div>";


	if (0 && $row[image] && file_exists("/home/asyoulik/public_html/productImages/_BG/$row[image]")) { 

	$row[image]='http://www.asyoulikeitsilvershop.com/productImages/_TN/'.substr($row[image],0,-4)."_TN.jpg";	

       $imgTitle=getImageTitle($row[pattern],$row[brand],$row[item],$row[monogram]);

			$c.= "<a href=\"http://www.asyoulikeitsilvershop.com$statURL\" class=$class>
                                  <img class=\"productImage\" src='{$row[image]}' title=\"\">
                                  <span class=\"imageCaption\">(click for details)</span>
                                </a>";

			}
	$c.="</div>";
	}
	
	
	break;

	}

	

	return $c;

	}


/******* END QUERY FUNCTION ********/

/******* BEGIN MAIN FUNCTION *******/

  $pageTitle=$keywords;
  $pageDescription="Item: $litem,Maker: $lbrand,Pattern: $lpattern,Category: sterling silver $lcat,Price: \$$itemPrice";
  $pageCategory=$keyCat[$category];
  $pageOnload=$onLoad[$category];

  $pageHeadID=$pageHeadIDs[$category];
  $pageHeadImage="/images/ayliss_title_".$pageHeadImages[$category].".jpg";
  $pageHeadImageID=$pageHeadImageIDs[$category];
  $catLinksID=$catLinksIDs[$category];
  $li=$liClass[$category];
  $mainContentHeaderImage=$mainContentHeaderImages[$category];
  
  $h1ContainerID=$h1ContainerIDs[$category];
  $h1ID=$h1IDs[$category];
  $headerText="Sterling Silver ".$cats[$category];
  $styleSheet=$style[$category];
  $otherLinkMenu=file_get_contents("/home/asyoulik/public_html/otherlinks.php");


if($homePattern){ echo($homePattern); 	
                    list($pattern,$brand)=split(" by ",strtolower($homePattern));	 
                }

include("/connect/mysql_connect.php");

$content="<form name=itemsForm>";

$where="1  and i.display!='0' and (i.retail!=0 or i.sale!=0)";

if($category=="f"){
 $category="f,sp,fcs,ps";
}

/*if (!$category || $category=='f,sp,fcs') {

	$category='f,sp,fcs,h,ps';

	}
*/

$cats=explode(',',$category);

$where.=" and (0";

foreach ($cats as $v) { $where.=" or i.category='$v'";}

$where.=")";

$order=($_GET['order'])?$_GET['order']:'brand,pattern,listOrder,category,item';

if ($brand && $brand!='all' && $brand!='blank') { 
$brand=urldecode($brand);
$where.=" and i.brand='$brand'"; 
}


if($pattern && $pattern!='all' && $pattern!='blank') {


	$pattern=urldecode($pattern);
	if(strtolower($pattern)=='francis i'){
		$where.=" and instr(i.pattern,'$pattern')>0 ";
	}
	
	else{
		$where.=" and  i.pattern='$pattern'";
	}

}

if ($recent) { $where.=" and i.time='n'"; }

if ($origin) { $where.=" and i.origin='$origin'"; }

if ($item) {

$words=split(" ",$item);$searchWords='(0';

foreach($words as $v) { $searchWords.=" or item like '%".str_replace("+",' ',$v)."%'"; }

$searchWords.=")";

$where.=" and (item regexp '[[:<:]]".$item."[[:>:]]' or soundex('$item')=soundex(item) or $searchWords)";

 }   

if ($gift) {$where.=" and i.gift='y'"; }

$where.=" and i.display=1";

	$query= mysql_query("SELECT count(*) from inventory as i where  $where  and time='n' and quantity!='0'");

	$type[1]=mysql_result($query,0); 

	$query= mysql_query("SELECT count(*) from inventory as i where  $where   and time!='n' and quantity!='0'");

	$type[2]=mysql_result($query,0);

	$query= mysql_query("SELECT count(*) from inventory as i where  $where   and time!='n' and quantity='0'");

	$type[3]=mysql_result($query,0);



if (!$limit) {$limit=50; }

$displaylimit=$limit;

if (!$pos) { $pos=1;}

if ($pos>$type[1]) { 

	if ($pos<=$type[1]+$type[2]) { $catNum=1; $start=$pos-$type[1]-1; }

	else  { $catNum=2;$start=$pos-$type[1]-$type[2]-1; }

	}

else {$start=$pos-1; }

 

if ($catNum<1) { 

                $content.=query($category,$where,1)."<div class=\"row\">
                                                        <div class=\"cell sixteenColumns centered bottomBorderTan\">
                                                        </div>
                                                    </div>";

if ($limit!=0) {$catNum=1;$start=0; } }

if ($catNum<2 && $limit>0) { $content.=query($category,$where,2)."<tr><td height=20 colspan=6></td></tr>";if ($limit!=0) {$catNum=2;$start=0; } }

if($wbrand!=""){
	$wbrand=urldecode($wbrand);
	$wbrand=str_replace("+"," ",$wbrand);
}
else{$wbrand=$brand;}
	if($wpattern!=""){
		$wpattern=urldecode($wpattern);
		$wpattern=str_replace("+"," ",$wpattern);
	}

else{$wpattern=$pattern;}

$content.= "<tr><td colspan=6 align=center><a href=\"/wishlist.php?pattern=$wpattern&brand=$wbrand\"><b>Add ".(($wpattern)?" $wpattern by":'')." $wbrand to your wishlist.</b></a></td></tr> <tr><td height=20 colspan=6></td></tr>";
$content.="<tr><td colspan=6 align=center><hr width=200 noshade size=1 color=#CC9966 align=center></td></tr><tr><td height=20 colspan=6></td></tr></table><br clear=all>";

$query= mysql_query("SELECT count(*) from inventory as i where $where and quantity!=0   ");

$n=mysql_result($query,0);

$x=($n%10);$i=1; 




$searchCnt="<form action='http://www.asyoulikeitsilvershop.com/showflatware.php'>
<input type=hidden name=category value='$category'>
<input type=hidden name=template value='$template'>
<input type=hidden name=item value='$item'>
<input type=hidden name=brand value='$brand'>
<input type=hidden name=pattern value='$pattern'>
<input type=hidden name=pos value='$pos'>
<input type=hidden name=order value='$order'>
<input name=recent type=hidden value='$recent'>
<input type=hidden name=gift value='$gift'>

<div class=\"row\">
<div class=\"cell twoColumns\">
    <strong>$n results</strong>
</div>";


if ($n) {

	if ($pos>1) {
		$searchCnt.="<div class=\"cell twoColumns searcharrownav\">  
                        <a href=http://www.asyoulikeitsilvershop.com/showflatware.php?recent=$recent&category=$category&template=$template&item=".urlencode($item)."&brand=".urlencode($brand)."&pattern=".urlencode($pattern)."&pos=0&order=$order&gift=$gift&limit=$limit class=searcharrowlink>
                            <img src='http://www.asyoulikeitsilvershop.com/images/arrow_start.gif' border='0'>
                        </a>
                        </div>";
		}
	else { 	
		$searchCnt.="<div class=\"cell twoColumns searcharrownav\">
                                <img src='http://www.asyoulikeitsilvershop.com/images/arrow_start_gray.gif' border='0'>
                            </div>"; 
		}

	if ($pos>=$limit) { 
		$searchCnt.="<div class=\"cell twoColumns nav searcharrownav\">
                                <a href=http://www.asyoulikeitsilvershop.com/showflatware.php?recent=$recent&category=$category&template=$template&item=".urlencode($item)."&brand=".urlencode($brand)."&pattern=".urlencode($pattern)."&pos=".($pos-$limit)."&order=$order&gift=$gift&limit=$limit class=searcharrowlink>
                                    <img src='http://www.asyoulikeitsilvershop.com/images/arrow_back.gif' border='0'>
                                </a>
                            </div>";
		}
	
	else { 	
		$searchCnt.="<div class=\"cell twoColumns searcharrownav\"> 
                                <img src='http://www.asyoulikeitsilvershop.com/images/arrow_back_gray.gif' border='0'>
                            </div>";
	}

	$searchCnt.="<div class=\"cell threeColumns centered searcharrownav\">
                        <strong>Now Showing $pos-".(($pos+$limit-1>$n)?$n:$pos+$limit-1)."</strong>
                    </div>";

	if ($pos<=$n-$limit) {
		$searchCnt.="<div class=\"cell twoColumns searcharrownav\">
                                <a href=http://www.asyoulikeitsilvershop.com/showflatware.php?recent=$recent&category=$category&template=$template&item=".urlencode($item)."&brand=".urlencode($brand)."&pattern=".urlencode($pattern)."&pos=".($pos+$limit)."&order=$order&gift=$gift&limit=$limit>
                                    <img src='http://www.asyoulikeitsilvershop.com/images/arrow_next.gif' border='0'>
                                </a>
                            </div>";

		$searchCnt.="<div class=\"cell twoColumns searcharrownav\">
                                <a href=http://www.asyoulikeitsilvershop.com//showflatware.php?recent=$recent&category=$category&template=$template&item=".urlencode($item)."&brand=".urlencode($brand)."&pattern=".urlencode($pattern)."&pos=".($n-$limit+1)."&order=$order&gift=$gift&limit=$limit>
                                    <img src='http://www.asyoulikeitsilvershop.com/images/arrow_end.gif' border='0'>
                                </a> 
                            </div>";
	}

	else { 	
	    if($n>$limit &&$pos=1){
 		$searchCnt.="<div  class=\"cell twoColumns searcharrownav\">
                                <a href=http://www.asyoulikeitsilvershop.com/showflatware.php?recent=$recent&category=$category&template=$template&item=".urlencode($item)."&brand=".urlencode($brand)."&pattern=".urlencode($pattern)."&pos=".($pos+$limit)."&order=$order&gift=$gift&limit=$limit>
                                    <img src='http://www.asyoulikeitsilvershop.com/images/arrow_next.gif' border='0'>
                                </a>
                            </div>";
		$searchCnt.="<div  class=\"cell twoColumns searcharrownav\">  
                                <a href=http://www.asyoulikeitsilvershop.com/showflatware.php?recent=$recent&category=$category&template=$template&item=".urlencode($item)."&brand=".urlencode($brand)."&pattern=".urlencode($pattern)."&pos=".($n-$limit+1)."&order=$order&gift=$gift&limit=$limit>
                                    <img src='http://www.asyoulikeitsilvershop.com/images/arrow_end.gif' border='0'>
                                </a> 
                            </div>";
   	    }
	    else{
		$searchCnt.="<div class=\"cell twoColumns searcharrownav\">
                                <img src='http://www.asyoulikeitsilvershop.com/images/arrow_next_gray.gif' border='0'>
                                <img src='images/arrow_end_gray.gif' border='0'>
                            </div>";
	    }
	}

   
}

$searchCnt.="<div class=\"cell fiveColumns rightAlign\">
                <select name=limit onChange='javascript:this.form.submit()'>
                    <option value=10". (($limit==10)?' selected':'').">Display 10 Items at a time
                    <option value=50". (($limit==50)?' selected':'').">Display 50 Items at a time
                    <option value=100". (($limit==100)?' selected':'')."> Display 100 Items at a time
                </select>
            </div>
            </div>
            </form>";

$content.="</form>";




$catLinkMenu=include("/home/asyoulik/public_html/categoryLinks.php");
$copyright=file_get_contents("copyright.php");

$c=($n<4)?$searchCnt.$content:$searchCnt.$content.$searchCnt;

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
                                     "<!--li class-->",
                                     "<!--mainContentHeaderImage-->",
                                     "<!--copyright-->");
                
                
                  $tempRepArr=array($pageOnload,
                                    $pageTitle,
                                    $keywords,
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
                                    $li,
                                    $mainContentHeaderImage,
                                    $copyright);
        

$pageTemplate=file_get_contents("/home/asyoulik/public_html/includes/searchTemplate.html");
    
$searchPage=str_replace($tempFindArr,$tempRepArr,$pageTemplate);
$searchPage=str_replace('<!--searchResults-->',$c,$searchPage);
    
return $searchPage;


?>

