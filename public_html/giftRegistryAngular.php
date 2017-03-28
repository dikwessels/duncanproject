<?php ob_start();?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Your Gift Registry | As You Like It Silver Shop, New Orleans, Louisiana</title>
<meta charset="UTF-8"/>

<meta name="description" content="As You Like It Silver Shop in New Orleans Louisiana specializes in silver flatware and holloware in active, inactive and obsolete patterns, gifts for weddings, birthdays, corporate events and special occasions."/>
<meta name="keywords" content="gift registry, silver gift registry, silver gifts, wedding registry, wedding presents, silver wedding presents, wedding gifts, silver wedding gifts, birthday presents, birthday gifts, silver birthday gifts, corporate presents, corporate gifts, silver corporate gifts,  selling your silver, purchasing information, sterling silver, sterling flatware, silver flatware, antique silver" />

<link rel="stylesheet" type="text/css" href="/css/Gumby/css/gumby.css">
<link rel="stylesheet" type="text/css" href="/css/categories.css">	

<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>	
<script type="text/javascript" src="/js/libs/modernizr-2.6.2.min.js"></script>
<script type="text/javascript" src="/js/libs/gumby.min.js"></script>
<script type="text/javascript" src="/js/angular/angular-1.5.2.min.js"></script>
<script type="text/javascript" src="/js//angular/angular-route.js"></script>
<script type="text/javascript" src="/js/apps/giftRegistry/giftRegistry.js"></script>
<script type="text/javascript" src="/js/apps/giftRegistry/controllers.js"></script>

<link rel="stylesheet" type="text/css" href="/css/Gumby/css/gumby.css">
<link rel="stylesheet" type="text/css" href="/css/categories.css">	

<script type="text/javascript">
	if (window.location.protocol != "https:")
    window.location.href = "https:" + window.location.href.substring(window.location.protocol.length)
	
	
	/*$(document).ready(function(){
		$.refreshCart();
	});
	*/
</script>

</head>

<body class="default" ng-app = "giftRegistry">
	
<header class="container sixteen colgrid pageHead">

<div class="row">
		<div class="three columns">
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
		
		<div class="ten columns">
			<h1 class="center">As You Like It Silver Shop</h1>
		</div>
		
		<div class="three columns center desktopOnly" id="cartContainer">
			<span id="itemCount"></span><br>
	     		<a href="https://www.asyoulikeitsilvershop.com/shoppingCart.php">
		 		<img alt="View your shopping cart" class="silverChest" src="/images/silverchest_empty.gif" id="chest">
		 		</a>
		
	</div>
		
</div>
	
<div class="row desktopOnly">
	<h2 class="fifteen columns centered center">Estate sterling silver flatware, hollowware, jewelry, baby silver and silver repair services</h2>
</div>

</header>

<nav class="container sixteen colgrid" id="blackMenu">	
	<div class="row navbar  searchMenu" >
			<a class = "toggle" gumby-trigger = "#nav-main" id = "toggle-nav">
				<i class="icon-menu"></i>
			</a>
		<div class = "nine columns">

		<ul id="nav-main">
			
			<li>
				<a href="">Great Gifts</a>
			</li>
			
			<li class="desktopOnly">
				<i class="icon-star"></i>
			</li>
			
			<li>
				<a href="">Caring For Your Silver</a>
			</li>
			
			<li class="desktopOnly">
				<i class="icon-star"></i>
			</li>
			
			<li>
				<a href="">Silver Repairs</a>
			</li>
			
			<li class="desktopOnly">
				<i class="icon-star"></i>
			</li>
			
			<li>
				<a href="">Other Services</a>
			</li>
					
					<li class="mobileOnly"><a href="silver-flatware/">Flatware</a></li>
					<li class="mobileOnly"><a href="silver-hollowware/">Hollowware</a></li>
					<li class="mobileOnly"><a>Jewelry</a></li>
					<li class="mobileOnly"><a>Baby Silver</a></li>
					<li class="mobileOnly"><a>Collectibles</a></li>
					<li class="mobileOnly"><a>Coin Silver</a></li>
					<li class="mobileOnly"><a>Gift Registry</a></li>
					<li class="mobileOnly"><a>Pattern Guide</a></li>
	
	</ul>
		</div>
		<div class = "six columns right" id = "searchContainer">
			<form name="searchForm" class="nopad" action="//www.asyoulikeitsilvershop.com/silver-search.php" method="post">
				<div class="append field">
					<input class="xwide text input" id="searchInput" name="terms" placeholder="Search by item name">
					<button class="medium default pretty btn" type="submit" id="searchButton">
						<i class="icon-search"></i>
					</button>
				</div>
			</form>
		</div>
	</div>
</nav>

<nav class = "container sixteen colgrid desktopOnly navbar categoryMenu" id = "categoryNav">
	<div class="row fullWidth">
		<div class="sixteen columns">
			<ul>
				<li>
					<h3 title=""><a title="Silver Flatware, Serving Pieces, Place Settings, Complete Sets" class="categoryLink" href="/silver-flatware/">Flatware</a></h3>
				</li>
				<li>
					<h3><a title="Silver Hollowware" class="categoryLink" href="/silver-hollowware/">Hollowware</a></h3>
				</li>
				<li>
					<h3><a title="Silver Jewelry, Earrings, Necklaces, Bracelets" class="categoryLink" href="/silver-jewelry/">Jewelry</a></h3>
				</li>
				<li>
					<h3><a title="Baby Silver" class="categoryLink" href="/baby-silver/">Baby Silver</a></h3>
				</li>
				<li>
					<h3><a title="Silver Collectibles" class="categoryLink" href="/silver-collectibles/">Collectibles</a></h3>
				</li>
				<li>
					<h3><a title="Coin Silver" class="categoryLink" href="/coin-silver/">Coin Silver</a></h3>
				</li>
				<li>
					<h3><a title="Gift Registry" class="categoryLink" href="/giftRegistry.php">Gift Registry</a></h3>
				</li>
				<li>
					<h3><a class="categoryLink" title="Browse Over 1300 Patterns!" href="/silver-patterns.php">Pattern Guide</a></h3>
				</li>
			</ul>
		</div>
	</div>
</nav>

<section class = "container sixteen colgrid mainContent" role = "main">
	
	<div class = "row fullWidth">
		
		<h1>Bridal & Gift Registry: Edit Your Registry</h1>

	</div>
	
	<div class = "row fullWidth">
		
		<div class = "sixteen columns" id="greeting">
			<span>Welcome back, {{userName}}</span>
			
		</div>
	
	</div>
		<section class="tabs" ng-controller = "NavCtrl">

		<nav class ="row navbar giftRegistrySubNav">

	<ul class="">
	
	<li ng-class="{active:viewItems}">
		<a ng-click="changeTab(1)" href="#/viewRegistry" >Registry Items</a>
	</li>
	<li ng-class="{active:addItems}">
		<a ng-click = "changeTab(2)" href="#/inventorySearch">Add Items</a>
	</li>
	<li ng-class="{active:editProfile}">
		<a ng-click = "changeTab(3)" href="#/editProfile">View/Edit Profile</a>
	</li>
	<li ng-class="{active: changePassword}">
		<a ng-click = "changeTab(4)" href="#/editPassword">Change Password</a>
	</li>
	</ul>

	
		</nav>
	<div class="row" ng-view>

</div>
		</section>
</section>

<footer class = "container sixteen colgrid">
	 <div class = "row fullWidth footer">
		 <div class = "sixteen columns center">
		 	<?
		 		$c=include("copyright.php");
		 		echo $c;
 			?>
		 </div>
	 </div>
</footer>	

</body>

</html>
