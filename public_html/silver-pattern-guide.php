<?php

 //ini_set("display_errors","1");
 
 header("Location://www.asyoulikeitsilvershop.com/silver-patterns/");
 
 include("/connect/mysql_pdo_connect.php");
 include("/home/asyoulik/public_html/staticHTMLFunctions.php");
 include("/home/asyoulik/public_html/categoryArrays.php");
?> 
 
<!DOCTYPE html>
<html>
<head>
	
	<title>
		As You Like It Silver Shop: Sterling Silver Flatware, Hollowware, Gifts
	</title>
	
	<base href="//www.asyoulikeitsilvershop.com">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	
	<meta charset="UTF-8" />

	<!-- google and bing verification -->
	<meta name="verify-v1" content="+I7SXW9NzrBhIDFSL/HxvjXwpZoydIFiGVozcxN8hxU=" />
	<meta name="msvalidate.01" content="DE85E9FCB2BA8AE4B75B2AF33CB4E5E0" />

	<meta name="description" content="As You Like It Silver Shop in New Orleans Louisiana specializes in sterling silver and silverplate flatware, sterling silver and silverplate holloware, sterling silver and silver plate jewelry, coin silver, sterling silver and silverplate collectibles in new and vintage patterns from silversmiths such as Reed & Barton, Gorham and Tiffany Silver." />

	<meta name="keywords" content="silver, sterling silver,flatware, antique, tableware, replacement , silver repair, corporate gifts, wedding gifts, silver identification, cleaning silver" />
	
	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>	
	<script type="text/javascript" src="/js/libs/modernizr-2.6.2.min.js"></script>
	<script type="text/javascript" src="/js/libs/gumby.min.js"></script>
	<script type="text/javascript" src="/js/libs/ui/gumby.fixed.js"></script>
	<script type="text/javascript" src="/js/libs/ui/gumby.fittext.js"></script>
	<script type="text/javascript" src="/js/libs/ui/gumby.toggleswitch.js"></script>
	<script type="text/javascript" src="/js/cart-min.js"></script>
		
	<link rel="stylesheet" type="text/css" href="/css/Gumby/css/gumby.css">
	<link rel="stylesheet" type="text/css" href="/css/fonts.css">
	<link rel="stylesheet" type="text/css" href="/css/tripadvisor.css">
	<link rel="stylesheet" type="text/css" href="/css/main.css">
	<!--<link rel="stylesheet" type="text/css" href="/css/ayliss_custom.css">-->
		
</head>

<body class="default">
	
<header class="container sixteen colgrid pageHead">
	<div class="row">
		<div class="three  columns">
			<div id="shoppingCartMobile" class="mobileOnly">
				<i class="icon-cart"></i>
			</div>
			<div id="contactInfo" class="desktopOnly">
			<a href="/contact.php" class="contactLink">Contact Us</a>
			1-800-828-2311<br>
			<a href="http://www.bbb.org/new-orleans/business-reviews/antiques-dealers/as-you-like-it-silver-shop-inc-in-new-orleans-la-22301/#bbbonlineclick" title="As You Like It Silver Shop Inc BBB Business Review"><img src="https://seal-neworleans.bbb.org/seals/black-seal-200-42-as-you-like-it-silver-shop-inc-22301.png" style="border: 0;" alt="As You Like It Silver Shop Inc BBB Business Review"></a><br>
	<a rel="nofollow" class="contactLink" href="http://www.facebook.com/pages/As-You-Like-It-Silver-Shop/110024085689230"><i class="icon-facebook-squared"></i></a>
		</div>
		</div>
		
		<div class="ten  columns">
			<h1 class="center">As You Like It Silver Shop</h1>
		</div>
		
		<div class="three  columns center desktopOnly" id="cartContainer">
			<a href="https://www.asyoulikeitsilvershop.com/shoppingCart.php" style="font-size:1.5rem;color:white">
				<span id="itemCount"></span><br>
		 		<i class="icon-basket" ></i>
		 		</a>
		
	</div>
		
</div>
	<div class="row desktopOnly">
	<h2 class="fifteen columns centered center">Estate sterling silver flatware, hollowware, jewelry, baby silver and silver repair services</h2>
</div>

</header>

<? include("navigation.html"); ?>

<!-- begin main content -->

<section class="container sixteen colgrid mainContent" role="main">
  <!-- begin main content head with h1 -->
  <div class="row fullWidth">
        <h1 class="sixteen  columns">
            Silver Pattern Guide
        </h1>
    </div>

  
<!-- end main content head with h1 -->

	<div class="searchResults">
 
 <div class="row">
	 <div class="sixteen columns centered">
 <?
	 $letter=($_GET[letter])?$_GET[letter]:"='A'";
	 $letter=stripslashes($letter);
	 $sortby=($_GET[sortby])?$_GET[sortby]:'pattern';
	 echo "Results sorted by <b>".(($sortby!='pattern')?"<a href='silver-patterns.php?sortby=pattern&letter=".urlencode($letter)."'>Pattern</a>":"Pattern")."</b> | <b>Choose Letter Below</b>  | Sort by <b>".(($sortby!='brand')?"<a rel=\"nofollow\" href='silver-patterns.php?sortby=brand&letter=".urlencode($letter)."'>Manufacturer</a>":'Manufacturer')."</b>";
?>
	 </div>
	 
	 </div>
	 
	 <div class="row">
	 	<div class="sixteenColumms centered">
	 	  <?
 
for ($i='A';$i<='Z';$i++) { 
	if ("='$i'"==$letter) { echo "<span class=chosen><b>$i</b></span>"; }
	else { echo "<a href=silver-pattern-guide.php?sortby=$sortby&letter=".urlencode("='$i'").">$i</a>"; }
	echo " | ";
	if ($i=='Z') { break; }
	}

	
if ($letter=="<'A'") { echo "<span class=chosen><b>Other</b></span> |"; } 
	else {
		echo "<a href=silver-pattern-guide.php?sortby=$sortby&letter=%3C%27A%27>Other</a> |"; 
	}

?>
	 	</div>
	 </div>
	 
<div id="patternListSub" class="alignCenter">	 
	 
<?
	
	function makePatternName($p,$b){
	if($p){
		$patternname=ucwords(strtolower($p));
		if($b){
			$patternname.=" by ".ucwords(strtolower($b));
		}
	}
	else{
		if($b){
			$patternname=ucwords(strtolower($b));
		}
	}
	return $patternname;
}


	
	$re = array("&",",",'.','#','by','BROTHERS','INTERNATIONAL');
	$rw = array("AND",'','','','','BROS','INTL');
	$letter = "substring($sortby,1,1) $letter";
	
  $arrFind = array(
	  				"#",
	  				"&",
  					"-",
  					"(",
  					")",
  					",",
  					"'",
  					"/",
  					" "
  					);
  
  $arrReplace = array(
	  				"",
	  				" ",
  					"_",
  					"",
  					"",
  					"",
  					"",
  					"_",
  					"_"
  					);
	
	$stmt = "SELECT * from handles where $letter and image=1 order by $sortby";

	$query = $db->prepare($stmt);
	$query->execute();
	
	$result=$query->fetchAll();

	foreach( $result as $r ){
	
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

			$fullPatternName = makePatternName($r['pattern'],$r['brand']);
			
			//$patternArticleID = makePatternName($r['pattern'],$r['brand']);
			
			$patternArticleID = str_replace($arrFind, $arrReplace, $fullPatternName);
			
			$patternArticleID .= "_Silver";

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
	
    //echo "<script>console.log('patternLink=$patternLink');</script>";
   $h2PatternHeader = $patternArticleID."_Header";
    echo "
    <article id='$patternArticleID'>
	<div class='row alignCenter'>
	<div class='sixteen columns border-bottom'>
		<h2 class='h2PatternHeader' id='$h2PatternHeader'>
		<a $nofollow href='$patternLink' alt='$fullPatternName Silver' title='$fullPatternName Silver'>$r[pattern] by $r[brand]</a></h2>
	 </div>
	 </div>
	 <div class='row'>	
	 	
	 	<div class='sixteen columns'>
		<a data-test='this is testing' $nofollow title='$r[pattern] by $r[brand] silver' href='$patternLink'>
			<img class='handleImage noborder' src='resizedHandles/$folder/$file' alt='$r[pattern] by $r[brand] sterling silver' title='$r[pattern] by $r[brand] $keyword'>
			</a>
	</div>
	
	<div class='row'>
	<div class='sixteen columns'>
	";

		$qinstock=$qrow[ct]>0?$qrow[ct]:0;
		$items=$qinstock==1?"item":"items";
		$viewInventory=$qinstock>0?": <a href=\"$patternLink\">View Inventory</a>":"";

echo "<span>$qinstock $items in stock$viewInventory</span>
	</div>
	</div>
	</div>
	</article>";
		


}
?>
 
 
<!--searchResultsSub--> 
</div>
      <!--searchResults-->
    </div>    

</section>

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

	
	<script type="text/javascript">
		
		$(document).ready(function(){			
			$.refreshCart();
		});
		
	</script>
</html>

  
<?

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
