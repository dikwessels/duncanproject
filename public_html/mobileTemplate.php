<!DOCTYPE html>
<html>
<head>
	<title>
	As You Like It Silver Shop: Sterling Silver Flatware, Hollowware, Gifts
	</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>	
	<script src="/js/libs/modernizr-2.6.2.min.js"></script>
	<script src="/js/libs/gumby.min.js"></script>
	<script src="/js/libs/ui/gumby.fixed.js"></script>
	<script src="/js/libs/ui/gumby.fittext.js"></script>
	<script src="/js/libs/ui/gumby.toggleswitch.js"></script>
	<link rel="stylesheet" href="/css/Gumby/css/gumby.css">
	<link rel="stylesheet" href="/css/fonts.css">
	<link rel="stylesheet" type="text/css" href="/css/tripadvisor.css">
	<link rel="stylesheet" type="text/css" href="/css/custom.css">	
	<style>
		.overlay{
			display: none;
			color:black;
			opacity: .85;
			-moz-opacity: .85;
			-webkit-opacity: .85;
			filter: alpha(opacity=85);
			background: white;
			padding-top:5%;
			text-align: center;
			position: absolute;
			height: 100%;
			width:100%;
			z-index: 1000;
			left: 0px;
		}
		
		.overlay.rowoverlay{
			padding-top: 0%;
			max-height:auto;
		}

		

	</style>
	
	<link rel="stylesheet" href="/css/custom.css">
	
	<script type="text/javascript">
		
		
		$(document).ready(function(){

  	$('.homepageBrandLink').each(function(){
		$(this).on('mouseover',function(){
			$('#splash-image').attr('src','/images/h_i_'+$(this).data('brand')+'.jpg');
		});
	});	
	
	//$.refreshCart();
	
	$.ajax({type:'GET',
			url:'tripAdvisorBadge.php',
			success:function(response){
				$('#tripAdvisorBadge').html(response);	
			}
	});
});
		

	</script>
	
</head>

<body>
	
	<div class="container sixteen colgrid header" id="siteTitleContainer">
		<div class="row fullWidth">
			<div class="fifteen columns centered" id="banner">
				<div id="contactInfo" class="desktopOnly">
	<a href="contact.php" class="contactLink">Contact Us</a>
	1-800-828-2311<br>
	<a href="http://www.bbb.org/new-orleans/business-reviews/antiques-dealers/as-you-like-it-silver-shop-inc-in-new-orleans-la-22301/#bbbonlineclick" title="As You Like It Silver Shop Inc BBB Business Review"><img src="https://seal-neworleans.bbb.org/seals/black-seal-200-42-as-you-like-it-silver-shop-inc-22301.png" style="border: 0;" alt="As You Like It Silver Shop Inc BBB Business Review"></a>
	<br>
	<a rel="nofollow" class="contactLink" href="http://www.facebook.com/pages/As-You-Like-It-Silver-Shop/110024085689230">
                            <img width="36" style="border-width:0px" alt="As You Like It Silver Shop on Facebook" title="Visit Us on Facebook" src="images/facebook48.png">
                        </a>


<span id="tripAdvisorBadge"></span>

</div>
				<h1 class="siteTitle">As You Like It Silver Shop</h1>
				<h2 class="siteTagLine desktopOnly">
					Sterling silver flatware, hollowware, jewelry, baby silver and silver repair services
				</h2>
			</div>
			
			<a class="toggle" gumby-trigger="#nav-main" id="toggle-nav">
				<i class="icon-menu"></i>
			</a>
			<a class="toggle" >
<svg style="max-height: 25px;max-width: 25px;height:25px;width:25px" version="1.1" id="Shopping_basket" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px"
	 y="0px" viewBox="0 0 20 20" enable-background="new 0 0 20 20" xml:space="preserve">
<path fill="white" d="M18.399,7h-5.007l-1.812,1.812c-0.453,0.453-1.056,0.702-1.696,0.702c-0.642,0-1.244-0.25-1.698-0.703
	C7.734,8.359,7.484,7.757,7.483,7.116c0-0.039,0.01-0.077,0.011-0.116H1.6C1.268,7,1,7.268,1,7.6V10h18V7.6
	C19,7.268,18.73,7,18.399,7z M10.768,7.999l5.055-5.055c0.235-0.234,0.237-0.613,0.002-0.849l-0.92-0.92
	c-0.234-0.234-0.614-0.233-0.85,0L9,6.231c-0.488,0.488-0.488,1.28,0,1.768C9.488,8.486,10.279,8.487,10.768,7.999z M3.823,17.271
	C3.92,17.672,4.338,18,4.75,18h10.5c0.412,0,0.83-0.328,0.927-0.729L17.7,11H2.3L3.823,17.271z"/>
</svg></a>

		</div>
		<div class="row navbar starContainer blackMenu" id="blackNav">
			<div class="ten columns" id="menuItems">
				<ul id="nav-main">
					<li><a>Great Gifts</a></li>
					<li class="desktopOnly">*</li>
					<li><a>Caring for Your Silver</a></li>
					<li class="desktopOnly">*</li>
					<li><a>Silver Repairs</a></li>
					<li class="desktopOnly">*</li>
					<li><a>Other Services</a></li>
					<li class="mobileOnly"><a>Flatware</a></li>
					<li class="mobileOnly"><a>Hollowware</a></li>
					<li class="mobileOnly"><a>Jewelry</a></li>
					<li class="mobileOnly"><a>Baby Silver</a></li>
					<li class="mobileOnly"><a>Collectibles</a></li>
					<li class="mobileOnly"><a>Coin Silver</a></li>
					<li class="mobileOnly"><a>Gift Registry</a></li>
					<li class="mobileOnly"><a>Pattern Guide</a></li>
				</ul>
				</div>
				<div class="seven columns" id="searchFieldContainer">
					<div class="custom append field">
						<input class="xwide text input" id="search" placeholder="Search by item name">
						<div class="medium default pretty btn" id="searchButton">
							<i class="icon-search"></i>
						</div>
					</div>
				</div>
			
			
				
		</div>
	</div>

	<div class="container sixteen colgrid categoryMenuMain">
		<div class="row navbar">
				<ul id="nav-categories"  class="sixteen columns">
					<li><a>Flatware</a></li>
					<li><a>Hollowware</a></li>
					<li><a>Jewelry</a></li>
					<li><a>Baby Silver</a></li>
					<li><a>Collectibles</a></li>
					<li><a>Coin Silver</a></li>
					<li><a>Gift Registry</a></li>
					<li><a>Pattern Guide</a></li>
				</ul>
		</div>
	</div>

	<div class="container sixteen colgrid tileContainer fullWidth mobileOnly">
<div class="row">
<ul class="two_up tiles categoryTiles">
  <li>
    <a href="/silver-flatware/">
		<img src="http://www.asyoulikeitsilvershop.com/productImages/_BG/CHANTILLY-BY-GORHAM-4-Piece-Place-Size-Setting-.jpg">
		<h2>Silver Flatware</h2>
    </a>
  </li>
  <li>
  	<a href="/silver-hollowware/">
  		<img src="/images/categoryTiles/teaSet.png">
  		  		<h2>Silver Hollowware</h2>
  	</a>
  </li>
</ul>
<ul class="two_up tiles categoryTiles">
<li>
	<a href="/silver-gift-ideas.html">
	<img src="/images/categoryTiles/cigaretteCase.png">
	<h2>Gift Ideas</h2>
	</a>
</li>
  <li>
  <a href="/silver-patterns.php">
    	<!--<img src="http://www.asyoulikeitsilvershop.com/resizedHandles/C/CAMBRIDGE%20GORHAM.jpg">-->
		<img src="http://www.asyoulikeitsilvershop.com/resizedHandles/C/CHANTILLY%20GORHAM.jpg">
		<h2>Pattern Guide</h2>
  </a>
  </li>
  </ul>
</div>
</div>

	<div class="container sixteen colgrid desktopOnly" id="splash">
<table>
<tbody>
	<tr>
	<td>
		<div class="splash-div">
		<a href="/silver-flatware/">
	<img class="noborder" src="images/entersite_l.jpg" style="width:250px;" alt="Enter As You Like it Silver Shop"></a>
	
</div>
		<div class="splash-div">
	<a href="/silver-flatware/" class="" data-brand="wallace">
		<img class="noborder" id="splash-image" src="/images/h_i_wallace.jpg" width="250" height="187" alt="Fine silver from Wallace, Gorham, Tiffany and More">
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
		<a title="Tiffany &amp; Company Silver" class="homepageBrandLink" href="/silver/tiffany-sterling-silver.html" data-brand="tiffany">
		Tiffany &amp; Co. 
		</a>
		
</h2>
<h2 class="brandLink">
		<a title="S. Kirk &amp; Son Silversmiths" class="homepageBrandLink" href="/silver/kirk-sterling-silver.html" data-brand="skirk">
		S. Kirk &amp; Son Silversmiths

		</a>
		</h2>
		
<h2 class="brandLink">
		<a title="Towle Silversmiths" class="homepageBrandLink" href="/silver/towle-sterling-silver.html" data-brand="towle">
		Towle Silversmiths
		</a>
		
		</h2>
		
<h2 class="brandLink">
		<a title="Reed &amp; Barton Silver" class="homepageBrandLink" href="/silver/reed-and-barton-sterling-silver.html" data-brand="reed">

		Reed &amp; Barton
		</a>
		</h2>
		
<h2 class="brandLink">
		<a title="International Silver Company" class="homepageBrandLink" href="/silver/international-sterling-silver.html" data-brand="international">
		International Silver Co.
		</a>
</h2>
	
	
</div>
	</td>
</tr>

<tr>
	<td></td>
</tr>

</tbody></table>
	
</div>

	<div class="container sixteen colgrid" id="SEODescription">
	<div class="row fullWidth">
<strong>As You Like it Silver Shop</strong> buys, sells and restores sterling silver and silver-plated tableware, flatware, and holloware in active, inactive and obsolete sterling patterns.
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
Reed &amp; Barton,&nbsp;
Wallace,&nbsp;
International Silver Company,&nbsp;
Whiting,&nbsp;
Frank Smith,&nbsp;
Frank M. Whiting,&nbsp;
Tuttle,&nbsp;
Georg Jensen,&nbsp;
Redlich &amp; Co.,&nbsp;
Alvin,&nbsp;
Durgin Division of Gorham,&nbsp;
Graff, Washbourne &amp; Dunn,&nbsp;
Lunt,&nbsp;
Schofield,&nbsp;
Towle,&nbsp;
Tiffany &amp; Co.,&nbsp;
S. Kirk &amp; Son,&nbsp;
Watson Silver Company,&nbsp;
Dominick &amp; Haff,&nbsp;
Shreve,&nbsp;
Bailey, Banks &amp; Biddle,&nbsp;
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

	<div class="container sixteen colgrid desktopOnly">
	<div class="row fullWidth">
		<?php include("categoryLinksFooter.php"); ?>

	</div>
</div>

	<div class="container sixteen colgrid" id="footer">
<div class="row">
<? include("copyright.php"); ?>
<div id="thawteSealContainer">       
      <div id="thawteseal" style="text-align:center;" title="Click to Verify - This site chose Thawte SSL for secure e-commerce and confidential communications.">
        <div>
            <script type="text/javascript" src="https://seal.thawte.com/getthawteseal?host_name=www.asyoulikeitsilvershop.com&size=M&lang=en"></script>
        </div>
      </div>
</div>	
<div >
   <a class="infoLink" href="http://www.asyoulikeitsilvershop.com/sitemap.php" rel="nofollow" id="siteMap">Site Map</a><br>
   <a class="infoLink"  id="usefulLinks" rel="nofollow" href="links.php">Useful Links</a><br>
   <a class="infoLink" id="privacyPolicy" rel="nofollow" href="privacy.php">Privacy Policy</a>
</div>

	
</div>
</div>

</body>

</html>