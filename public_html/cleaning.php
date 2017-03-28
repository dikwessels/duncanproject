<!DOCTYPE html>
<html>
<head>
<title>Silver Care, Cleaning and Storage | As You Like It Silver Shop, New Orleans, Louisiana</title>
<meta charset="UTF-8"/>
<meta name="description" content="As You Like It Silver Shop in New Orleans Louisiana specializes in silver flatware and holloware in active, inactive and obsolete patterns."/>
<meta name="keywords" content="silver care and cleaning, silver storage, silver cleaning, silver cleaning products, silver storage products, silver tableware, antique sterling, replacement silver, silver repair, corporate gifts, wedding gifts, silver identification, cleaning silver"/>

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


</head>

<body class="sub" onLoad="">
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
      <!-- the following code will be removed once new site is up -->
       	<p class="homenav"><span id="itemCount"></span>
	     	<a href="https://www.asyoulikeitsilvershop.com/shoppingCart.php">
     		<img alt="View your shopping cart" class="silverChest" src="/images/silverchest_empty.gif" id="chest">
     	</a>
     	</p>
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
     <h1 class="h1PageCatTitle" id="defaultH1">Silver Care, Cleaning and Storage</h1>
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
  <div class="cell threeColumns"></div>
  	  <div class="cell thirteenColumns">
	<table width="700" border="0">
<tr>
	<td width="50"><img src="images/blank.gif" width="50" height="1" alt="" border="0"></td>
	<td>
		<p>
<p>

<img src="images/blank.gif" width="50" height="1" alt="" border="0">&raquo; <b><a href = "/silver-cleaning-products.html">See our cleaning products</a></b><br><img src="images/blank.gif" width="50" height="1" alt="" border="0">&raquo; 
<b><a href = "/silver-storage-products.html">See our storage products.</a></b></p>
		
<p>Silver will need to be polished occasionally. This should be done with a soft cotton cloth and a good polish. Use very little polish since wiping off excess polish can make the job difficult.</p>

<p>Chemicals in the air cause silver to tarnish. Therefore, the best way to keep it clean is to keep it in an airtight environment. Storing silver in a chest lined in tarnish preventive fabric or airtight plastic bags are both effective ways to retard the formation of tarnish. Plastic cling wrap should not be used because it may adhere to a piece permanently. Paper strips which are made to absorb tarnish causing chemicals can be used in drawers or in display cabinets where silver is kept. Treated gloves are available and are a good way to keep silver clean that is on display in an open area. These gloves work best on silver that is not badly tarnished.</p>

<p>Salt and sulfur can cause tarnish, corrosion or pitting. Pieces used to serve eggs, mayonnaise, salty foods and salad dressings should be washed as soon as possible. Rubber also contains sulfur, so never bundle your pieces with rubber bands or use rubber gloves when handling them.</p>

<p>Many older pieces of silver had bowls of spoons or tines of forks gold plated. This not only looks pretty but serves a purpose as well. Gold is less susceptible to acid damage than silver.</p>

<p>Dishwashers will often turn silver an odd color and will remove oxidation from the pattern. The heat may also loosen knife blades. However, you should use and enjoy your silver and if that means using the dishwasher, do it! The same applies to silver dips and other "miracle" cleaners. Items with mother-of-pearl, bone or ivory handles should never go in the dishwasher.</p>

<p>There is very little you can do to silver that cannot be remedied by a good silversmith. Silversmiths can buff out scratches, reoxidize, straighten tines, reshape the bowls of spoons, and can even replace knife blades. Guard against silver going into the disposal; this can be fatal.</p>

<p>Rare, old pieces should never be put in the dishwasher or mechanically buffed. A silver expert should be consulted before any restoration is undertaken.</p>

<p><i>The text above was taken from Helen Cox's book "Silver Flatware."</i></p>

<p><img src="images/blank.gif" width="50" height="1" alt="" border="0">&raquo; <b><a href = "/silver-cleaning-products.html">See our cleaning products</a></b></p>

		
		
		
		
		
		
		
		</p>
	</td>
	<td width="50"><img src="images/blank.gif" width="50" height="1" alt="" border="0"></td>
</tr>
</table>  

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

</body>
</html>





