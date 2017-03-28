<?php
	
	session_name('checkout');
	session_start();
	if(isset($_SESSION['giftcardcode'])){$_SESSION['giftcardcode']='';}
	if(isset($_SESSION['giftcardamount'])){$_SESSION['giftcardamount']=0;}	
	if(isset($_SESSION['giftcardsubtotal'])){$_SESSION['giftcardsubtotal']=0;}

?>

<!DOCTYPE html>
<html>
<head>
<title>As You Like It Silver Shop: Sterling silver flatware, hollowware, jewelry, baby silver, repairs, New Orleans, Louisiana</title>

<meta charset="UTF-8" />

<!-- google and bing verification -->
<meta name="verify-v1" content="+I7SXW9NzrBhIDFSL/HxvjXwpZoydIFiGVozcxN8hxU=" />
<meta name="msvalidate.01" content="DE85E9FCB2BA8AE4B75B2AF33CB4E5E0" />

<meta name="description" content="As You Like It Silver Shop in New Orleans Louisiana specializes in sterling silver and silverplate flatware, sterling silver and silverplate holloware, sterling silver and silver plate jewelry, coin silver, sterling silver and silverplate collectibles in new and vintage patterns from silversmiths such as Reed & Barton, Gorham and Tiffany Silver." />

<meta name="keywords" content="silver, sterling silver,flatware, antique, tableware, replacement , silver repair, corporate gifts, wedding gifts, silver identification, cleaning silver" />

<link rel="stylesheet" href="/css/dropdown/imports.css">
<link rel="stylesheet" type="text/css" href="ayliss_index_style.css">
<link rel="stylesheet" type="text/css" href="ayliss_style_uni.css">
<link rel="stylesheet" type="text/css" href="/css/fonts.css">
<link rel="stylesheet" type="text/css" href="/css/tripadvisor.css">

<style  type="text/css">

div#browser-alert{
	background-color: #FFFFFF;
    border: 1px solid #AAAAAA;
    display: none;
    font-size: 1rem;
    height: auto;
    left: 33%;
    line-height: 1.5rem;
    padding: 10px;
    position: fixed;
    top: 25%;
    width: 33%;
    z-index: 100;
}

div#categoryFooterLinks {
   border-top: 1px solid #AAAAAA;
   display: inline-block;
   height: 62%;
   left: 0;
   position: relative;
   background-color: white;
   top: 0px;
}
span.contactInfo{
	font-size:.85rem;
}
#footer{
	border:none;
}
.footer{
	color: white;
	font-size: serif;
}
</style>

<script src="/js/jquery-1.7.2.min.js" type="text/javascript"></script>
<script type="text/javascript"  src='js/store.js'></script>
<script type="text/javascript"  src='js/images.js'></script>
<script type="text/javascript" src='js/MobileDetect.js'></script>



<script>

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

  	$('.homepageBrandLink').each(function(){
		$(this).on('mouseover',function(){
			$('#splash-image').attr('src','/images/h_i_'+$(this).data('brand')+'.jpg');
		});
	});	
	
	$.refreshCart();
	
	$.ajax({type:'GET',
			url:'tripAdvisorBadge.php',
			success:function(response){
				$('#tripAdvisorBadge').html(response);	
			}
	});
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

<body onload="">


<div id="container">
 <div id="browser-alert">
	 <h4>Notice to users of Internet Explorer 9 and below</h4><br>
	 Internet Explorer versions 9 and below cause display and functionality issues with this site.<br><br>Please upgrade to Internet Explorer 10, or use Google Chrome, Mozilla Firefox or Safari. We apologize for any inconvenience and we appreciate your business.<br><br>
	 <p style="width:100%;text-align:center;color:red;text-decoration:underline;cursor:pointer;" id="close-alert">Close this window		</p>
 </div> 

<div class="pageHead" id="defaultPageHead">
	
 	<div class="pageHeaderImage row nopad centered fullWidth" id="defaultHead"> 
 <div class="row centered" id="mobilePageHeader">
	<h1>As You Like It Silver Shop</h1>
	<span id="mobileDescription">
		Antique Silver Flatware, Hollowware, Jewelry, Baby Silver, Repairs
	</span>
 </div>
    <div id="siteTitleContainer">
    <h1 class="siteTitle"> As You Like It Silver Shop</h1>
    <h2 class="siteTagLine">
    Estate sterling silver flatware, hollowware, jewelry, baby silver and silver repair services
    </h2>
    <div id="contactInfo" class="cell sevenColumns">
	<a href="contact.php" class="contactLink">Contact Us</a>
	1-800-828-2311<br>
	<a href="http://www.bbb.org/new-orleans/business-reviews/antiques-dealers/as-you-like-it-silver-shop-inc-in-new-orleans-la-22301/#bbbonlineclick" title="As You Like It Silver Shop Inc BBB Business Review"><img src="https://seal-neworleans.bbb.org/seals/black-seal-200-42-as-you-like-it-silver-shop-inc-22301.png" style="border: 0;" alt="As You Like It Silver Shop Inc BBB Business Review"></a>
	<br>
	<a rel="nofollow" class="contactLink" href="http://www.facebook.com/pages/As-You-Like-It-Silver-Shop/110024085689230">
                            <img width="36" style="border-width:0px" alt="As You Like It Silver Shop on Facebook" title="Visit Us on Facebook" src="images/facebook48.png">
                        </a>


<span id="tripAdvisorBadge"></span>

</div>
  <!-- begin cart container --> 
     <div class="cell sevenColumns" id="cartContainer">
      <!-- the following code will be removed once new site is up -->
       	<p class="homenav"><span id="itemCount"></span>
	     	<a href="https://www.asyoulikeitsilvershop.com/shoppingCart.php">
     		<img alt="View your shopping cart" class="silverChest" src="/images/silverchest_empty.gif" id="chest">
     	</a>
 
    </p>
   <!-- end cart container -->
     </div>
 
    </div>
</div>
 	
 	<div id="otherLinksContainer" class="homepageOtherLinks">
<ul id="otherLinksSub" class="row centered nopad fullWidth">
 <li class="otherLink" id="giftsLinkHome">
    <a href="/silver-gift-ideas.html">Great Gifts</a>
   </li>

 <li class="starContainer">*</li>


 <li class="otherLink" id="cleaningLinkHome">
      <a href="/cleaning.php">Caring for Your Silver</a>
   </li>

<li class="starContainer">*</li>


 <li class="otherLink" id="repairsLinkHome">
      <a href="/repairs.php">Silver Repairs</a>
   </li>

   <li class="starContainer">*</li>

   <li class="otherLink" id="servicesLinkHome">
	 	<a href="/otherservices.php">
              Other Services
          </a>
   </li>
   
   <li class="starContainer">*</li>
   
   <li class="otherLink" id="contactLinkHome">
	   	<a href="/contact.php">
	   		Contact Us: 1 800 828 2311
	   	</a>
   </li>
     <li class="otherLink" id="searchLink">
   <form name="searchForm" action="//www.asyoulikeitsilvershop.com/silver-search.php" method="post">
    <span id="searchFieldSpan">
     <input id="searchInput" class="searchField" name="terms" placeholder="Search by Pattern, Maker, Category or Item">
     <button type="submit" class="searchButton"><img alt="Click to search our inventory" src="/images/icon_search.png"></button>
    </span>
    </form>  
  </li>

</ul>

</div>

	<div class="categoryLinksContainerMainPage">

 <div class="categoryLinksContainerSub clearfix">
 <ul class="dropdown">
 	<li class="transparentBG">
 		<a title="Silver Flatware, Serving Pieces, Place Settings, Complete Sets" class="homepageLink" href="/silver-flatware/">Flatware</a></li>
 	<li class="transparentBG">
 		<a title ="Silver Hollowware" class="homepageLink" href="/silver-hollowware/">Hollowware</a></li>
 	<li class="transparentBG">
	 	<a title="Silver Jewelry, Earrings, Necklaces, Bracelets" class="homepageLink" href="/silver-jewelry/">Jewelry</a>
 	</li>
 	<li class="transparentBG">
	 	<a title="Baby Silver" class="homepageLink" href="/baby-silver/">Baby Silver</a>
 	</li>
 	<li class="transparentBG">
	 	<a title="Silver Collectibles" class="homepageLink" href="/silver-collectibles/">Collectibles</a>
 	</li>
 	<li class="transparentBG">
		<a title="Coin Silver" class="homepageLink" href="/coin-silver/">Coin Silver</a>	 	
 	</li>
 	<li class="transparentBG">
 		<a title="Gift Registry" class="homepageLink" href="/giftRegistry.php">Gift Registry</a>
 	</li>
 	<li class="transparentBG">
 		<a class="homepageLink" title="Browse Over 1300 Patterns!" href="/silver-patterns.php">Pattern Guide</a>
 	</li>
 </ul>
 </div>
</div>

</div>

<div class="row fullWidth mainContentIndex">
	
	<div class="row fullWidth" id="specialAnnouncement" style="display:none;">
  <div class="cell sixteen columns centered"><strong>Notice:</strong> <? include_once('vacationMessage.inc'); ?></div>
  </div>
	
	<div class="row fullWidth">
	<div class="cell sixteenColumns centered">
    <a href="//www.asyoulikeitsilvershop.com/silver-cleaning-products.html" alt="Silver polish and other great cleaning products" title="Silver polish and other great cleaning products">
        Click here for our Silver polish and other great cleaning products</a>
    </div>
	
</div>
	
	<div class="row fullWidth"></div>
	
	<div class="row centered nopad fullWidth" id="splashImage">
		<!--[if lt IE 10]>
			<script type="text/javascript">
				$('#browser-alert').fadeIn();
				$('#close-alert').click(function(){
					$('#browser-alert').fadeOut();
				});
			</script>
		<![endif]-->
		<!-- [if !IE]>
		<![endif]-->
<table style="width:100%;">
<tr style="font-size:0px;">
	<td style="text-align:center">
		<div class="splash-div">
		<a href="/silver-flatware/">
	<img class="noborder" src="images/entersite_l.jpg" style="width:250px;" alt="Enter As You Like it Silver Shop"></a>
	
</div>
		<div class="splash-div">
	<a href="/silver-flatware/" class="" data-brand="wallace">
		<img class="noborder" id="splash-image" src="images/h_i_wallace.jpg" width="250" height="187" alt="Fine silver from Wallace, Gorham, Tiffany and More" >
		</a>

</div>
		<div class="splash-div leftAlign">
	
	

		<h2 class="brandLink">
			<a title="Wallace Silversmiths" href="/silver/wallace-sterling-silver.html" class="homepageBrandLink" data-brand="wallace">

		Wallace Silversmiths
		</a></h2>
		
		<h2 class="brandLink">
		<a title="Gorham Silversmiths" class="homepageBrandLink" href="/silver/gorham-sterling-silver.html" data-brand="gorham">
		Gorham Silversmiths
		</a></h2>
<h2 class="brandLink">
		<a title="Tiffany & Company Silver" class="homepageBrandLink" href="/silver/tiffany-sterling-silver.html" data-brand="tiffany">
		Tiffany & Co. 
		</a>
		
</h2>
<h2 class="brandLink">
		<a title="S. Kirk & Son Silversmiths" class="homepageBrandLink" href="/silver/kirk-sterling-silver.html" data-brand="skirk">
		S. Kirk & Son Silversmiths

		</a>
		</h2>
		
<h2 class="brandLink">
		<a title="Towle Silversmiths" class="homepageBrandLink" href="/silver/towle-sterling-silver.html" data-brand="towle">
		Towle Silversmiths
		</a>
		
		</h2>
		
<h2 class="brandLink">
		<a title="Reed & Barton Silver" class="homepageBrandLink" href="/silver/reed-and-barton-sterling-silver.html" data-brand="reed">

		Reed & Barton
		</a>
		</h2>
		
<h2 class="brandLink">
		<a title="International Silver Company" class="homepageBrandLink" href="/silver/international-sterling-silver.html"  data-brand="international">
		International Silver Co.
		</a>
</h2>
	
	
</div>
	</td>
</tr>

<!--<tr style="font-size:0px;">


   
	<td style="width:auto"></td>

	<td style="width:250px;vertical-align:middle;text-align:center">
		<a href="/silver-flatware/">
	<img class="noborder" src="images/entersite_l.jpg" style="width:250px;" alt="Enter As You Like it Silver Shop"></a></td>	
	<td style="width:250px;vertical-align:bottom;text-align:center;">

		<a href="/silver-flatware/" class="" data-brand="wallace">
		<img class="noborder" id="splash-image" src="images/h_i_wallace.jpg" width="250" height="187" alt="Fine silver from Wallace, Gorham, Tiffany and More" >
		</a>
	</td>

	<td style="width:250px;vertical-align:center;text-align:left">

		<h2 class="brandLink">
			<a title="Wallace Silversmiths" href="/silver/wallace-sterling-silver.html" class="homepageBrandLink" data-brand="wallace">

		Wallace Silversmiths
		</a></h2>
		
		<h2 class="brandLink">
		<a title="Gorham Silversmiths" class="homepageBrandLink" href="/silver/gorham-sterling-silver.html" data-brand="gorham">
		Gorham Silversmiths
		</a></h2>
<h2 class="brandLink">
		<a title="Tiffany & Company Silver" class="homepageBrandLink" href="/silver/tiffany-sterling-silver.html" data-brand="tiffany">
		Tiffany & Co. 
		</a>
		
</h2>
<h2 class="brandLink">
		<a title="S. Kirk & Son Silversmiths" class="homepageBrandLink" href="/silver/kirk-sterling-silver.html" data-brand="skirk">
		S. Kirk & Son Silversmiths

		</a>
		</h2>
		
<h2 class="brandLink">
		<a title="Towle Silversmiths" class="homepageBrandLink" href="/silver/towle-sterling-silver.html" data-brand="towle">
		Towle Silversmiths
		</a>
		
		</h2>
		
<h2 class="brandLink">
		<a title="Reed & Barton Silver" class="homepageBrandLink" href="/silver/reed-and-barton-sterling-silver.html" data-brand="reed">

		Reed & Barton
		</a>
		</h2>
		
<h2 class="brandLink">
		<a title="International Silver Company" class="homepageBrandLink" href="/silver/international-sterling-silver.html"  data-brand="international">
		International Silver Co.
		</a>
</h2>
	</td>

	<td style="width:auto"></td>
	
</tr>-->

<tr>
	<td></td>
</tr>

</table>

	<div id="SEODescription" class="">
<span class="bold">As You Like it Silver Shop</span> buys, sells and restores sterling silver and silver-plated tableware, flatware, and holloware in active, inactive and obsolete sterling patterns.
In addition, we sell silver polishing products, tarnish prevention products, and other silver cleaning products in such brand names as 
Hagerty,&nbsp;
Cape Cod,&nbsp; 
and Rich Glo.
However, our customers’ favorite product is our own world renown silver polish, the best available anywhere. 
Some additional services include hand engraving, re-plating, silver-plating, and all types of silverware restoration, 
such as soldering, dent removal, handle repair, and garbage disposal damage repair. 
<br><br>
Our customers can find inventory and patterns from
Gorham,&nbsp;
Reed & Barton,&nbsp;
Wallace,&nbsp;
International Silver Company,&nbsp;
Whiting,&nbsp;
Frank Smith,&nbsp;
Frank M. Whiting,&nbsp;
Tuttle,&nbsp;
Georg Jensen,&nbsp;
Redlich & Co.,&nbsp;
Alvin,&nbsp;
Durgin Division of Gorham,&nbsp;
Graff, Washbourne & Dunn,&nbsp;
Lunt,&nbsp;
Schofield,&nbsp;
Towle,&nbsp;
Tiffany & Co.,&nbsp;
S. Kirk & Son,&nbsp;
Watson Silver Company,&nbsp;
Dominick & Haff,&nbsp;
Shreve,&nbsp;
Bailey, Banks & Biddle,&nbsp;
Empire Silver Company,&nbsp;
A. G. Schultz,&nbsp;
925, Inc.,&nbsp;
Paul Revere, etc.
<br><br>
You will find well-known sterling patterns, such as Francis I,&nbsp;
Chantilly,&nbsp;
Strasbourg,&nbsp;
Buttercup,&nbsp;
Joan of Arc,&nbsp;
Grande Baroque,&nbsp;
Prelude,&nbsp;
Wild Rose,&nbsp;
Frontenac,&nbsp;
Melrose,&nbsp;
Etruscan,&nbsp;
Iris,&nbsp; 
Old Orange Blossom,&nbsp;
Fiddle Thread,&nbsp; 
Acorn,&nbsp;
Audubon,&nbsp; 
Burgundy,&nbsp; 
Love Disarmed,&nbsp; 
Royal Danish,&nbsp; 
Repousse,&nbsp; 
Rose Point,&nbsp; 
Sir Christopher,&nbsp; 
Lily,&nbsp; 
Fairfax,&nbsp; 
King Edward,&nbsp; 
Louis XV,&nbsp; 
Versailles,&nbsp; 
Meadow Rose,&nbsp; 
Queen Elizabeth,&nbsp; 
Old Master,&nbsp; 
King Richard,&nbsp; 
Georgian,&nbsp; 
Old Colonial,&nbsp; 
Old Maryland Engraved,&nbsp; 
Chrysanthemum,&nbsp; 
Medallion,&nbsp;
Saxon Stag,&nbsp; 
Ivory.
<br><br>
Besides a full inventory of silver tableware, we stock silver novelty items such as 
candlesticks,&nbsp;
candelabras,&nbsp;
picture frames,&nbsp;
pill boxes,&nbsp;
business card holders,&nbsp;
goblets,&nbsp; 
coffee service,&nbsp;
tea service,&nbsp;
soup tureens,&nbsp;
centerpieces,&nbsp;
trays,&nbsp;
barware,&nbsp;
sippers,&nbsp;
jiggers,&nbsp; 
wedding cake knives, 
candle snuffers,&nbsp;
Christmas ornaments, 
chafing dishes, 
mint julep cups,&nbsp; 
coffee pots,&nbsp;
tea pots,&nbsp;
wine coolers, 
champagne buckets, 
water pitchers,&nbsp; 
bowls,&nbsp; 
canns,&nbsp; 
meat domes, 
epergnes,&nbsp;
revolving domes, &nbsp;
vases,&nbsp;
 antique sterling, and coin silver.
</div>





</div>
	
</div>

<div id="categoryFooterLinks" class="row fullWidth">
	<?php include("categoryLinkFooter.php"); ?>
</div>

<div class="row fullWidth footer">
	
<? include("copyright.php"); ?>

<div class="cell twoColumns"></div>
<div class="cell fourColumns">
  <h5>As You Like It Silver Shop New Orleans</h5>
  <span class="contactInfo">
  3033 Magazine St<br>
  New Orleans, Louisiana, 70115<br>
  Toll-free: (800) 828-2311<br>
  Local #: (504) 897-6915<br>
  Fax: (504) 897-6335<br>
	</span>
</div>
<div class="cell twoColumns"></div>
<div class="cell fourColumns">
  <h5>As You Like It Silver Shop Natchez</h5>
  <span class="contactInfo">
 410 North Commerce Street <br>
  		Stanton Hall Carriage House<br>
  		Natchez, MS 39120-3219<br>
  		Toll Free #: (800) 848-2311
  </span>
</div>
<div class="cell twoColumns">
	<a class="infoLink" href="http://www.asyoulikeitsilvershop.com/sitemap.php" rel="nofollow" id="siteMap">Site Map</a><br>
   <a class="infoLink"  id="usefulLinks" rel="nofollow" href="links.php">Useful Links</a><br>
   <a class="infoLink" id="privacyPolicy" rel="nofollow" href="privacy.php">Privacy Policy</a>
</div>

<!--<div style="position:absolute;left:0px;top:0px;text-align:left">
   <a class="infoLink" href="http://www.asyoulikeitsilvershop.com/sitemap.php" rel="nofollow" id="siteMap">Site Map</a><br>
   <a class="infoLink"  id="usefulLinks" rel="nofollow" href="links.php">Useful Links</a><br>
   <a class="infoLink" id="privacyPolicy" rel="nofollow" href="privacy.php">Privacy Policy</a>
</div>-->

	
</div>
<div id="thawteSealContainer" class="row fullWidth">       
      <div id="thawteseal" style="text-align:center;" title="Click to Verify - This site chose Thawte SSL for secure e-commerce and confidential communications.">
        <div>
            <script type="text/javascript" src="https://seal.thawte.com/getthawteseal?host_name=www.asyoulikeitsilvershop.com&size=M&lang=en"></script>
        </div>
      </div>
</div>	
</div>

</body>

</html>  