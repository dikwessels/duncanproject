<?php
ob_start();
extract($_GET);

 include("connect/mysql_connect.php");
 include("staticHTMLFunctions.php");
 include("categoryArrays.php");

 include("checkoutSettings.php");

ini_set("display_errors","1");

//include("/connect/mysql_connect.php");
include("connect/mysql_pdo_connect.php");

global $PayPalMode;

if(substr($id,0,2)=="gc"){
 //it's a gift card 
 $items=$_COOKIE['items']."&$id:$amount:$email";
 $message.="1 $amount Gift Card has been placed in your silver chest<br>";
}

else{

	$stmt = "SELECT item,quantity as q,pattern, brand,category from inventory where id=:id";
	
	$query = $db->prepare($stmt);
	$query->bindParam(':id',$id);
	
	$query->execute();
	
	$result = $query->fetchAll();
	
	$row = $result[0];
	extract($row);
	
 
    if(!$regID){$regID=0;}
 
	if (0) {
		
		list($type,$blank)	=	split(" ",$item);
	
		$sf	= ($type == 'Place')?'Place':'Salad';
		
		$statement = "SELECT a.quantity as q 
					 FROM inventory AS a 
					 WHERE a.pattern='$pattern' 
					 AND a.brand='$brand' 
					 AND(a.item='$type Knife' or a.item='$type Fork' or a.item='$sf FORK' or a.item='TEASPOON') 
					 AND a.monogram!=-1 order by a.quantity";
		
		$subQuery = $db->prepare($statement);
		$subQuery->execute();

		$subResult = $subQuery->fetchAll();
		
	
			
		//$sq = mysql_query($statement);
		//$sr = mysql_fetch_assoc($sq);
		
		if ((count($subResult) < 4) || ($subResult['q'] < $quantity) ) { 
		//if ( mysql_num_rows($sq)<4 || $sr[q]<$quantity ) {  
			    $quantity=$subResult['q'];
			    $message=($subResult['q'])?"<b>Sorry, we only have $sr[q] $item ($pattern by $brand) in stock.</b><br>":"Sorry, we no longer have $item ($pattern by $brand) in stock.";
			}
		}
		
	else {		
		$q=abs($q); 
		if ($quantity>$q) { 
			$quantity=$q; 
			$message=($q)?"<b>Sorry, we only have $q $item ($pattern by $brand) in stock.</b><br>":"Sorry, we no longer have $item ($pattern by $brand) in stock."; 
		}
	}
	
	preg_match("/$id:([0-9]+):$regID/",$_COOKIE['items'],$m);
	
	if ($quantity == 0) {  	
			$items=	str_replace("$id:$m[1]:$regID","",$_COOKIE['items']); 
	}
	
	else {
	
	
		$patternname = $pattern?(ucwords(strtolower("$pattern by $brand"))):trim(ucwords(strtolower($brand)));
		
		$has=($quantity>1)?'have':'has';
		$itemname=ucwords(strtolower($item));
		$message.="$quantity $patternname $itemname $has been placed in your silver chest<br>";
		
		if ($m[1]) {
			$items=	str_replace("$id:$m[1]:$regID","$id:$quantity:$regID",$_COOKIE['items']);
		}
		     else {$items=$_COOKIE['items']."&$id:$quantity:$regID";}
	}
}		

setcookie("items",$items,0,'/');

?>

 
<!DOCTYPE html>

<html data-version="2.0">

<head>
<title>Add Item To Cart | As You Like It Silver Shop, New Orleans, Louisiana</title>
<meta charset="UTF-8"/>
<meta name="description" content="As You Like It Silver Shop in New Orleans Louisiana specializes in silver flatware and holloware in active, inactive and obsolete patterns." />
<meta name="keywords" content="sterling silver, sterling flatware, silver flatware, antique silver, silver tableware, antique sterling, replacement silver, silver repair, corporate gifts, wedding gifts, silver identification, cleaning silver" />

<script type="text/javascript" src="/js/jquery-1.7.2.min.js" ></script>

<script type="text/javascript" src='js/images.js'></script>
<script type="text/javascript" src='js/store.js'></script>

<link rel="stylesheet" href="/css/dropdown/imports.css">
<link rel="stylesheet" href="ayliss_style.css" type="text/css">
<link rel="stylesheet" href="ayliss_style_uni.css" type="text/css">

<script type="text/javascript">
$.refreshCart=function(){
	
	var msg='Your cart has ';
	var items=' Items';
	var c=unescape(document.cookie);
	var begin=c.indexOf("items=");
	
	if(begin<0){  
		$('#chest').attr('src','/images/silverchest_empty.gif');
		$('#itemCount').html('Your Silver Chest Is Empty');
	}
	else{
	
	var end=c.indexOf(";",begin+6)
	
	if (end<1) {end=c.length;} 
	
	var items=c.substring((	begin+6),end);

	var numItems=items.split("&");
	var n=0;
	var i=1;
	var temp='';
	var msgitems=' Items';
	
		for(i=1;i<(numItems.length);i++) {
			temp=numItems[i].split(":");
			if(temp[0]==0||temp[0].substring(0,2)=='gc'){
			n+=1;
        }
        else{
        	n+=parseInt(temp[1]);
        }
		}
	
	if(n>0){ 
	  if(n==1){msgitems=' Item';}
	  $('#chest').attr('src','/images/silverchest_full.gif');		
	  $('#itemCount').html(msg+n+msgitems);
	 }
	 else{
	  $('#chest').attr('src','/images/silverchest_empty.gif');		
	  $('#itemCount').html('Your silver chest is empty');
	 } 

	
	}
	
};

$(document).ready(function(){
	$.refreshCart();
	
});


</script>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-31581272-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>

</head>

<body class="sub" onload="setCartGraphic();">
<div id="container">
<!-- begin page head -->
<div class="pageHead" id="<!--pageHeadID-->">



<div id="contactInfo">
Questions?
	<a href="contact.php" class="contactLink">Contact Us</a>
	1-800-828-2311	
</div>
  <!-- begin cart container --> 
     <div class="cell sevenColumns" id="cartContainer">
      <!-- the following code will be removed once new site is up -->
       	<p class="homenav"><span id="itemCount"></span>
	     	<a href="https://www.asyoulikeitsilvershop.com/shoppingCart.php">
     		<img alt="View your shopping cart" class="silverChest" src="/images/silverchest_empty.gif" id="chest">
     	</a>
     	</p>
	</div>  
    
   <!-- end cart container -->

  <!-- page Header image -->
  <div class="pageHeaderImage" id="defaultPageHead">
  
   <div class="row centered" id="mobilePageHeader">
  As You Like It Silver Shop
	  <span id="mobileDescription"> 
		  Antique Silver Flatware, Hollowware, Jewelry, Baby Silver, Repairs
		  </span>
  </div>

    <img class="pageBanner" src="/images/ayliss_title_r.jpg" alt="Silver Pattern Guide, As You Like it Silver Shop" title="Silver Pattern Guide, As You Like It Silver Shop">
  </div>  
  
   <!-- end page header image -->

<!-- begin other links -->

<? 
	$otherlinks=file_get_contents("otherlinks.php");
	echo $otherlinks; 
?>


 <!--end other links -->

<!-- begin category links -->
<div class="categoryLinksContainer" id="defaultCatLinks">
  <?
  	$catLinks=include("categoryLinks.php");
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
            Shopping Cart
        </h1>
    </div>
  
  <div id="defaultImage" class="pageCatImage"></div>
  
 </div>

<!-- end main content head with h1 -->

 <div class="searchResults">
 <? 
	global $PayPalMode;
	if( $PayPalMode == "testing" ) { 
		
		include("checkoutNotice.php"); 
	
	}
 
 ?>
 <div class="row nopad">
 <?

$message='<form action="http://localhost:8888/shoppingCart.php">
<div class="row">

			<div class="cell twoColumns"></div>
			<div class="cell fourteenColumns">
				'.$message.'
			</div>
		</div>
		<div class="row border-bottom">
			<div class="cell twoColumns"></div>
			<div class="cell fourColumns">
				<button type="button" onClick="javascript:history.back()">Continue Shopping</button>
			</div>';
			
if( ($PayPalMode == "live") || ($PayPalMode != "live" && $_COOKIE['developer_mode'] == "on") ){
	
	$message.='<div class="cell fourColumns">
				<button type="submit">Check Out &rarr;</button>
			</div>
		</div>

		<div class="row">
			<div class="cell twoColumns"></div>
			<div class="cell fourteenColumns bold">
				For your convenience, you can view and edit the contents of your silver chest from any page on the site by clicking on the silver chest 
				<a href="https://www.asyoulikeitsilvershop.com/shoppingCart.php">
				<img src="images/silverchest_full.gif" border="0" align="middle" width="44">
				</a> at the top of the page.
			</div>
		</div>';
}

$message.='</form>';
		
$f=$message;
echo $f;


 ?>
 </div>
      <!--searchResults-->
    </div>    
   
  <!-- end main content -->
</div>

 <div id="footer" class="row centered nopad">

   <? $copyright=include("copyright.php");
   echo $copyright;
   ?>
   <!--copyright-->
 </div>

</div>
<!-- end container -->

</body>
</html>
<? ob_flush();?>