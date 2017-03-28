<!DOCTYPE html>
<html>
<head>
<title>View Your Wishlist | As You Like It Silver Shop, New Orleans, Louisiana</title>
<meta charset="UTF-8"/>
<meta name="description" content="Silver Wishlist, get notified when items you're looking for become available at As You Like It Silver Shop in New Orleans, Louisiana." />
<meta name="keywords" content="silver wishlist, wishlist, gift wishlist, silver flatware, silver hollowware, silver jewlery, silver collectibles, baby silver, flatware wishlist, hollowware wishlist, jewlery wishlist, collectibles wishlist, silver flatware wishlist, silver hollowware wishlist, silver jewelry wishlist, silver collectibles wishlist" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
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

<script type="text/javascript">
function submitForm(){
	document.wishlist.submit();
}

function changePattern(){

	var brand= document.getElementById('selectBrand').value;
	var url = '/TEST/PatternSelect.php';
	var params='brand='+brand;
	var responseAction='document.getElementById(\'spanPatternSelect\').innerHTML=request.responseText';
	requestURL(url,params,responseAction,'');
}

</script>

<link rel="stylesheet" href="/css/dropdown/imports.css">
<link rel="stylesheet" href="/ayliss_style.css" type="text/css">
<link rel="stylesheet" href="/ayliss_style_uni.css" type="text/css">
<link rel="stylesheet" href="/mobile.css" type="text/css">
<link href="/css/lightbox.css" rel="stylesheet" />



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

<body class="sub" onLoad="preLoad('hola');getItemCount();">
<div id="container">
<!-- begin page head -->
<div class="pageHead" id="defaultPageHead">
<!-- page Header image -->
  <div class="pageHeaderImage row nopad" id="defaultHead">
  <div class="row centered" id="mobilePageHeader">
  As You Like It Silver Shop
	  <span id="mobileDescription"> 
		  Antique Silver Flatware, Hollowware, Jewelry, Baby Silver, Repairs
		  </span>
  </div>
    <img class="pageBanner" src="/images/ayliss_title_r.jpg" alt="As You Like It Silver Shop Item Wishlist" title="As You Like It Silver Shop Item Wishlist">
 <div id="contactInfo" class="cell eightColumns">
	<a href="contact.php" class="contactLink">Contact Us</a>
	1-800-828-2311	
</div>
   <!-- begin cart container --> 
  <div class="cell sixColumns rightAlign" id="cartContainer">
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
     <h1 class="h1PageCatTitle" id="defaultH1">Silver Wishlist</h1>
     <!--breadCrumb-->
    </div>
   <div id="defaultImage" class="pageCatImage"></div>

 </div>
  <!-- end main content head with h1 -->
  <div class="row">
  <div class="cell oneColumn"></div>
  <div class="cell fourteenColumns">
  	<h2 class="searchResultsH2" id="defaultH2">
	  View Your Wishlist	
	    </h2>
  </div>
  </div>
  <div class="row">
  <div class="cell oneColumn"></div>
	  <div class="cell fourteenColumns">
	  
	  
   <?
   
	include("/connect/mysql_connect.php");
	
	$content="<form name=\"wishlist\" method=\"post\" action=\"wishlist.php\">
			<div class=\"row\">
					<span class=\"caption\">Wishlist items for $email</span>
			</div>	
			<div class=\"row bottomBorderDefault\">
				<div class=\"cell oneColumn\">Qty</div>
				<div class=\"cell sixColumns\">Item</div>
				<div class=\"cell sixColumns\">Pattern/Maker</div>
				<div class=\"cell threeColumns\">Notes</div>
			</div>";
	
	extract($_GET);
	
	$query="SELECT item, pattern, maker, qty,notes FROM wishlist WHERE customerEmail=\"$email\" ORDER BY id";
	//echo($query);
	$result=mysql_query($query);
$n=mysql_num_rows($result);
	if($n==0){
		$content.="<div class=\"row\">
					<div class=\"cell sixteenColumns\">No Wishlist Items found</div>
				  </div>";
	}
	
	else{
		while($r=mysql_fetch_assoc($result)){
		
			$item=urldecode($r['item']);
			$notes=urldecode($r['notes']);
	
			$content.= "<div class=\"row\">
							<div class=\"cell oneColumn\">
								$r[qty]
							</div>
							<div class=\"cell sixColumns\">$item</div>";
    		
    		if($r['pattern']!=""){
   				$pm=$r['pattern'];
    		}
    		else{
    			$pm=$r['maker'];
    		}
    		
    	$content.="<div class=\"cell sixColumns\">$pm</div>
    				<div class=\"cell threeColumns\">$notes</div>
    				
    				</div>";
    	}
	
	$content.="
	<div class=\"row bottomBorderDefault\">
	 <div class=\"cell sixteenColumns\">
		<input type=\"hidden\" name=\"av\" value=\"1\"></td>
		<input type=\"hidden\" name=\"email\" value=\"$email\">
	 </div>
	</div>		
	
	<div class=\"row\">
		<div class=\"cell sixColumns centered\">
			<a href=\"javascript:submitform();\">Add Items to Your Wishlist</a>
		</div>
		<div class=\"cell twoColumns\"></div>
		
		<div class=\"cell eightColumns centered\">
			<a href=\"javascript:history.go(-2);\">Resume Shopping</a>
		</div>
		
	</div>";

		}
		
	echo $content;
	
	mysql_close();
?>

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





