<!DOCTYPE html>
<html>
<head>
<title>Our Guarantee | As You Like It Silver Shop, New Orleans, Louisiana</title>
<meta charset="UTF-8"/>
<meta name="description" content="As You Like It Silver Shop in New Orleans Louisiana specializes in silver flatware and holloware in active, inactive and obsolete patterns. As You Like It Silver Shop in New Orleans, Louisiana Service Guarantee, Merchandise Policy." />
<meta name="keywords" content="As You Like It Silver Shop, New Orleans Lousiana, Service Guarantee, Merchandise Policy"/>

<!--ogTags-->

<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="js/lightbox.js"></script>
<script type="text/javascript" src="/js/ajax.js"></script>
<script type="text/javascript" src="/js/images.js"></script>
<script type="text/javascript" src="/js/store.js"></script>
<script type="text/javascript" src="/js/cookie.js"></script>
<script type="text/javascript" src="/js/giftRegistry.js"></script>
<script type="text/javascript" src="/js/suggestedItems.js"></script>
<script type="text/javascript" src="/js/share.js"></script>

<link rel="stylesheet" href="/css/dropdown/imports.css">

<link rel="stylesheet" href="/ayliss_style.css" type="text/css">
<link rel="stylesheet" href="/ayliss_style_uni.css" type="text/css">

<? include("/home/asyoulik/public_html/js/analytics.html"); ?>

</head>

<body class="sub" onLoad="preLoad('hola');getItemCount();">
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<div id="container">
<!-- begin page head -->
<div class="pageHead" id="defaultPageHead">
<!-- page Header image -->
  <div class="pageHeaderImage row nopad" id="<!--pageHeadImageID-->">
  <div class="row centered" id="mobilePageHeader">
  As You Like It Silver Shop
	  <span id="mobileDescription"> 
		  Antique Silver Flatware, Hollowware, Jewelry, Baby Silver, Repairs
		  </span>
  </div>
    <img class="pageBanner" src="/images/ayliss_title_r.jpg" alt="<!--pageHeadImageAlt-->" title="<!--pageHeadImageTitle-->">
 
 <div id="contactInfo" class="cell eightColumns">
	<a href="contact.php" class="contactLink">Contact Us</a>
	1-800-828-2311	
</div>
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
include("otherlinks.php");
?>
 <!--end other links -->

<!-- begin category links -->
<div class="categoryLinksContainer" id="defaultCatLinks">
  <? 
  include("categoryArrays.php");
  	$c=include("categoryLinks.php");
    echo $c;
   ?> 

</div>
<!-- end category links -->
</div>
<!-- end page head -->



<!-- begin main content -->

<div class="mainContent">
  <!-- begin main content head with h1 -->
  <div class="mainContentHead fullWidth" id="defaultH1Container">
    <div class="titleContainer">
     <h1 class="h1PageCatTitle" id="defaultH1">Our Guarantee</h1>
     <!--breadCrumb-->
    </div>
   <div id="defaultImage" class="pageCatImage"></div>

 </div>
  <!-- end main content head with h1 -->

 <!--<div class="row">
  <div class="cell fifteenColumns bottomBorderDefault">
  		<h2 class="searchResultsH2" id="defaultH2">
	  Common Hallmarks
	  </h2>
  </div>
  </div>
  -->
  
  <div class="row">
  	<div class="cell twoColumns"></div>
	  <div class="cell fourteenColumns">
<p class="fancybody">Merchandise Return Policy</p>
		<p>All merchandise is refundable within 30 days of shipping. Returns on credit
card orders of $500 and over are subject to a 15% restocking fee. Returns on
cash or check orders of $500 or more are subject to a 10% restocking fee.
Shipping fees are non-refundable when any item is returned for any reason
other than an error on the part of an agent of As You like It Silver Shop.</p>

		<p class="fancybody">Shipment & Order Contents</p>

<p>When you receive your order, please check the box thoroughly - smaller
pieces of silver are sometimes
easy to overlook within the packing material and heavier pieces tend to
settle to the bottom of the box.</p>

<p>We also include a sample bottle of Piney Woods silver polish with every
order. Apply the polish with a soft, non-abrasive cloth. It is not
necessary to wet the towel first.</p>

<p><div class="ans">Tip: After polishing an ornate pattern, use a soft toothbrush under warm
water to remove excess polish out of crevices.</div></p>

	  </div>
  </div>
    <!-- end main content -->
</div>

 <footer>
   <?
   	$c=include("copyright.php");
   	echo $c;
   ?>
    </footer>	
</div>
<!-- end container -->
<script type="text/javascript" src="//assets.pinterest.com/js/pinit.js"></script>
</body>
</html>





