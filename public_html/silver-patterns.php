<?php

 //ini_set("display_errors","1");
 
 header("Location://www.asyoulikeitsilvershop.com/silver-patterns/");

 include("/home/asyoulik/connect/mysql_connect.php");
 include("/home/asyoulik/public_html/staticHTMLFunctions.php");
 include("/home/asyoulik/public_html/categoryArrays.php");

?> 
 
<!DOCTYPE html>

<html>

<head>
<title>Silver Pattern Guide | As You Like It Silver Shop, New Orleans, Louisiana</title>
<meta charset="UTF-8"/>
<meta name="description" content="Complete sterling silver and silverplate pattern guide. Find your pattern here at As You Like It Silver Show, New Orleans, LA"/>
<meta name="keywords" content="sterling silver, sterling flatware, silver flatware, antique silver, silver tableware, antique sterling, replacement silver, silver repair, corporate gifts, wedding gifts, silver identification, cleaning silver" />
<script language="javascript" src='js/images.js'>
</script>
<script language='javascript' src='js/store.js'>
</script>
<link rel="stylesheet" href="/css/dropdown/imports.css">
<link rel="stylesheet" href="ayliss_style.css" type="text/css">
<link rel="stylesheet" href="ayliss_style_uni.css" type="text/css">


</head>
<body onload="preLoad();getItemCount();">
<? include_once("analyticstracking.php"); ?>
<div id="container">
<!-- begin page head -->
<div class="pageHead" id="<!--pageHeadID-->">
<div id="contactInfo">
Questions?
	<a href="contact.php" class="contactLink">Contact Us</a>
	1-800-828-2311	
</div>
  <!-- page Header image -->
  <div class="pageHeaderImage" id="defaultPageHead">
  <div class="row centered" id="mobilePageHeader">
  As You Like It Silver Shop
	  <span id="mobileDescription"> 
		  Antique Silver Flatware, Hollowware, Jewelry, Baby Silver, Repairs
		  </span>
  </div>
    <img class="pageBanner" src="/images/ayliss_title_r.jpg" alt="Silver Pattern Guide, As You Like it Silver Shop" title="Silver Pattern Guide, As You Like It Silver Shop">
    
 <!-- begin cart container --> 
  <div class="cell sevenColumns" id="cartContainer">
   <a href="/shoppingCart.php" class="top">
    Your Cart Has <img src='/images/c__.gif' name=nums3 align=bottom>
	       <img src='/images/c__.gif' name=nums2 align=bottom>
	       <img src='/images/c_0.gif' name=nums1 align=bottom>
    Items
     <img class="silverChest" src="/images/silverchest_empty.gif" name=chest>
    <div style="display:none">
    <button class="viewCart">
     <span class="viewCartCaption" id="cartStatus">Your Cart Is Empty </span>   
     <div class="cartImageContainer">
     		<div id="itemCount"></div>
     		<img src="/images/shoppingcart.png">
     	</div>
    </button>
    </div>
   </a>
<!-- end cart link -->
 </div>
 <!-- end cart container -->
    
  </div>  
   <!-- end page header image -->

<!-- begin other links -->

<? 
	$otherlinks=file_get_contents("/home/asyoulik/public_html/otherlinks.php");
	echo $otherlinks; 
?>


 <!--end other links -->

<!-- begin category links -->
<div class="categoryLinksContainer" id="defaultCatLinks">
  
  <?
  	$catLinks=include("/home/asyoulik/public_html/categoryLinks.php");
  	echo $catLinks; 
  ?>
  
  <!--catLinks-->
</div>
<!-- end category links -->

</div>
<!-- end page head -->

<!-- begin main content -->

<div class="mainContent">
  <!-- begin main content head with h1 -->
  <div class="mainContentHead">
 
    <div class="titleContainer" id="defaultH1Container">
        <h1 class="h1PageCatTitle" id="defaultH1">
            Silver Pattern Guide
        </h1>
    </div>
  
  <div id="defaultImage" class="pageCatImage"></div>
  
</div>

<!-- end main content head with h1 -->

 <div class="searchResults">
 
 <div class="row">
	 <div class="cell sixteenColumns centered">
 <?
	 $letter=($_GET[letter])?$_GET[letter]:"='A'";
	 $letter=stripslashes($letter);
	 $sortby=($_GET[sortby])?$_GET[sortby]:'pattern';
	 echo "Results sorted by <b>".(($sortby!='pattern')?"<a href='silver-patterns.php?sortby=pattern&letter=".urlencode($letter)."'>Pattern</a>":"Pattern")."</b> | <b>Choose Letter Below</b>  | Sort by <b>".(($sortby!='brand')?"<a rel=\"nofollow\" href='silver-patterns.php?sortby=brand&letter=".urlencode($letter)."'>Manufacturer</a>":'Manufacturer')."</b>";
?>
	 </div>
	 
	 </div>
	 
	 <div class="row">
	 	<div class="cell sixteenColumms centered">
	 	  <?
 
for ($i='A';$i<='Z';$i++) { 
	if ("='$i'"==$letter) { echo "<span class=chosen><b>$i</b></span>"; }
	else { echo "<a href=silver-patterns.php?sortby=$sortby&letter=".urlencode("='$i'").">$i</a>"; }
	echo " | ";
	if ($i=='Z') { break; }
	}

	
if ($letter=="<'A'") { echo "<span class=chosen><b>Other</b></span> |"; } 
	else {
		echo "<a href=silver-patterns.php?sortby=$sortby&letter=%3C%27A%27>Other</a> |"; 
	}

?>
	 	</div>
	 </div>
	 
<div id="patternListSub">	 
	 
<?
 $re=array("&",",",'.','#','by','BROTHERS','INTERNATIONAL');
$rw=array("AND",'','','','','BROS','INTL');
$letter="substring($sortby,1,1) $letter";
$query="SELECT * from handles where $letter and image=1 order by $sortby";
//echo($query);
$result=mysql_query($query); //"SELECT * from handles where $letter and image=1 order by $sortby");


while ($r=mysql_fetch_assoc($result)) {
	$rawPatternLink="productSearch.php?pattern=".rawurlencode(strtolower($r[pattern]))."&brand=".rawurlencode(strtolower($r[brand]));
	
	$folder=strtoupper(substr($r[pattern],0,1));
	$file=str_replace($re,$rw,strtoupper("$r[pattern] $r[brand]")).".jpg";
        $keyword="Sterling Silver";
        
        if(strtolower($r[brand])=="christofle"){
         $keyword="Silverplate";
        }
		if($r[pattern]!=""){
			$patternfname=createFileName("/home/asyoulik/public_html/","Silver",$r[pattern],$r[brand],"");
			echo "<!--$patternfname-->";
    }
	
		$qr="SELECT count(*) as c,sum(quantity) as ct FROM inventory WHERE quantity!=0 AND pattern=\"$r[pattern]\" AND brand=\"$r[brand]\"";
		//$qcount="SELECT sum(quantity) as ct FROM inventory WHERE pattern=\"$r[pattern]\" AND brand=\"$r[brand]\"";

         $qresult=mysql_query($qr);
		
		$qrow=mysql_fetch_assoc($qresult);
		
		if($qrow[c]>0){
			
		  if(file_exists($patternfname)){
	             $patternLink=str_replace("/home/asyoulik/public_html/","",$patternfname);
	             $nofollow="";
		  }
	          else{
	          
		     $patternLink="productSearch.php?pattern=".rawurlencode(strtolower($r[pattern]))."&brand=".rawurlencode(strtolower($r[brand]));
		     $nofollow="rel=\"nofollow\"";
	          }
		}
                
                else{
                		     $nofollow="rel=\"nofollow\"";
                    $patternLink="productSearch.php?pattern=".rawurlencode(strtolower($r[pattern]))."&brand=".rawurlencode(strtolower($r[brand]));

                }

	//$patternLink=$rawPatternLink;
	
    echo "<script>console.log('patternLink=$patternLink');</script>";
   
    echo "
	<div class='row centered'>
	<div class='cell sixteenColumns'>
		<h2 class='h2PatternHeader' id='h2PatternHeaderDefault'>
		<a $nofollow href='$patternLink'>$r[pattern] by $r[brand]</a></h2>
	 </div>
	 <br>
	
	<div class='cell sixteenColumns'>
		<a data-test='this is testing' $nofollow title='$r[pattern] by $r[brand] silver' href='$patternLink'>
			<img class='handleImage noborder' src='resizedHandles/$folder/$file' alt='$r[pattern] by $r[brand] sterling silver' title='$r[pattern] by $r[brand] $keyword'>
			</a>
	</div>
	
	<br>
	<div class='cell sixteenColumns'>
	";

		$qinstock=$qrow[ct]>0?$qrow[ct]:0;
		$items=$qinstock==1?"item":"items";
		$viewInventory=$qinstock>0?": <a href=\"$patternLink\">View Inventory</a>":"";

echo "<span>$qinstock $items in stock$viewInventory</span>
	</div>
	</div>";
		

/*
 $fname=$r['pattern']."by".$r['brand'];
 $fname=formatFileName($fname);
 $fname.=".html";
 $fname="/search/All/$fname";

if (file_exists("/home/asyoulik/public_html/$fname")){
 		$patternLink="1";//<a href=\"$fname\">$r['pattern'] by $r['brand'] sterling silver</a>";
	}
	else{
	 $patternLink="<a href='showSearch/pattern/".rawurlencode(strtolower($r[pattern]))."/brand/".rawurlencode(strtolower($r[brand]))."/category/f,sp,fcs/template/f'>$r[pattern] by $r[brand] sterling silverware</a>";
}
*/

/* 
		//$patternLink="<a href='showSearch/pattern/".rawurlencode(strtolower($r['pattern']))."/brand/".rawurlencode(strtolower($r['brand']))."/category/f,sp,fcs/template/f'>$r['pattern'] by $r['brand'] sterling silver</a>";
}
*/
//echo "<a href='showSearch/pattern/".rawurlencode(strtolower($r[pattern]))."/brand/".rawurlencode(strtolower($r[brand]))."/category/f,sp,fcs/template/f'>$r[pattern] by $r[brand]</a>



}

?>
 
 
<!--searchResultsSub--> 
</div>
      <!--searchResults-->
    </div>    
 </div>
  <!-- end main content -->

 <footer>
   <? $copyright=include("copyright.php");
   echo $copyright;
   ?>
   <!--copyright-->
 </footer>

</div>
<!-- end container -->

</body>
</html>

  
<?


/*
function createFileName($path,$v,$pattern,$brand){
		
		$brand=str_replace(" ", "-",$brand);
		if($pattern!=""){
			$pattern=str_replace("#","",$pattern);
			$pattern=str_replace(" ","-",$pattern);
			$fname=strtolower(str_replace(array('/'),array(''),$pattern))."-by-".strtolower(str_replace(array('/'),array(''),$brand));
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
		
		$keyword="";
		if($v!="SilverCare" && $v!="SilverStorage"){
			$keyword="-sterling-silver";
		}
		
		$fname=$path.$v."/".$fname."$keyword.html";
	
	return $fname;	
}
*/
function createFileNameDEPREC($path,$v,$pattern,$brand){
		
		
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

?>
