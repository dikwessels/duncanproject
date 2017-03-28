<!DOCTYPE html>
<html>
<head>
<title>Gift Certificates | As You Like It Silver Shop, New Orleans, Louisiana</title>
<meta charset="UTF-8"/>
<meta name="description" content="Online gift certificates for sterling silver flatware, hollowware, silver polish, available at As You Like It Silver Shop, New Orleans, Louisiana"/>
<meta name="keywords" content="customer privacy policy, selling your silver, purchasing information, sterling silver, sterling flatware, silver flatware, antique silver, silver tableware, antique sterling, replacement silver, silver repair, corporate gifts, wedding gifts, silver identification, cleaning silver"/>

<!--ogTags-->

<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="/js/ajax.js"></script>
<script type="text/javascript" src="/js/images.js"></script>
<script type="text/javascript" src="/js/store.js"></script>
<script type="text/javascript" src="/js/cookie.js"></script>
<!--<script type="text/javascript" src="/js/giftRegistry.js"></script>-->
<script type="text/javascript" src="/js/suggestedItems.js"></script>
<script type="text/javascript" src="/js/share.js"></script>
<script type="text/javascript" src="/js/formvalidation.js"></script>

<script type="text/javascript">
 $(document).ready(function(){
	$.bindValidation('gift-card-form'); 
 });
</script>
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
   <a href="/orderdetails.php" class="top">
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
     <h1 class="h1PageCatTitle" id="defaultH1">Online Gift Cards</h1>
     <!--breadCrumb-->
    </div>
   <div id="defaultImage" class="pageCatImage"></div>

 </div>
 
  <div class="row">
  	<div class="cell twoColumns"></div>
	  
	  <div class="cell fourteenColumns">
		  <div class="row">
		    <div class="cell sixteenColumns">
			  You can now purchase online gift cards for As You Like It Silver Shop, redeemable on our website <span style="font-size:75%">Please see terms and conditions below</span>. Fill out the form below to get started. 
		    </div>
		  </div>
		  <div class="row">
			  <form id="gift-card-form" method="post" action="addItem.php">
			  <input type="hidden" name="id" value="gc<? echo date("His"); ?>">
			  	<div class="row">
			  	<div class="cell fourColumns">
			  		Recipient's email address
			  	<span class="caption">This is the address to which we will send the online gift card.</span>
			  	</div>
			  	<div class="cell sevenColumns">
			  		<input validation-rule="email" err-msg="Please enter a valid email address." class="validate medium-input" type="text" id="email" err-msg-target="" placeholder="Please enter the email address of the recipient.">
			  		
			  		
			  	</div>
			  	<div class="cell fiveColumns">
			  		<span class="" id="email-validate"></span>
			  	</div>
			  	</div>
			  	<div class="row">
			  		<div class="cell fourColumns">Confirm recipient's email</div>
			  		<div class="cell sevenColumns">
			  			<input validation-rule="email match-#email" err-msg="Email addresses must match." err-msg-target="" name="email" class="validate medium-input" type="text" id="confirm-email">
			  			
			  		</div>
			  		<div class="cell fiveColumns">
			  		<span class="" id="confirm-email-validate"></span>
			  		</div>
			  	</div>
			  	<div class="row">
				  	<div class="cell fourColumns">Gift Card Amount
				  	
					  	<span class="caption">Minimum Gift Card is $25, whole dollar amounts only.</span>
				  	</div>
				  	<div class="cell fourColumns">
				  	<!--<input type="radio" name="amount" value="25">$25
				  	<input type="radio" name="amount" value="50">$50
				  	<input type="radio" name="amount" value="100">$100
				  	<input type="radio" name="amount" value="200">$200
				  	<input type="radio" name="amount" value="500">$500
				  	-->
					 <input validation-rule="decimal-only min-25 max-500" err-msg="Gift card values must be between $25.00 and $500.00" name="amount" id="gift-amount" class="validate small-input" type="text" placeholder="25.00" value="25">
				  	</div>
				  	<div class="cell eightColumns validation-text">	
				  		<span id="gift-amount-validate"></span>
				  	</div>
			  	</div>
			  	<div class="row">
			  	<div class="cell fourColumns"></div>
				  	<div class="cell twelveColumns">
				  		<button disabled class="valid-submit" id="add-to-cart">
				  			Add Gift Card to Cart
				  		</button>
				  	</div>
			  	</div>
			  </form>
		  </div>
		  <div class="row">
			  <span class="caption">
			  	<h5 style="text-decoration:underline;background-color:transparent;color:black;font-weight:normal;font-size:1rem;padding:0px;margin:0px;">Terms and Conditions:</h5>
			  	Online gift cards are not redeemable for cash, and have no cash value. Gift cards cannot be used to purchase another gift card. Gift cards do not expire. You can check your gift card balance at any time by going to <a href="/giftcards">www.asyoulikeitsilvershop.com/giftcards</a></span>
		  </div>
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





