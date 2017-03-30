<?php ob_start();?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Your Gift Registry | As You Like It Silver Shop, New Orleans, Louisiana</title>
<meta charset="UTF-8"/>

<meta name="description" content="As You Like It Silver Shop in New Orleans Louisiana specializes in silver flatware and holloware in active, inactive and obsolete patterns, gifts for weddings, birthdays, corporate events and special occasions."/>
<meta name="keywords" content="gift registry, silver gift registry, silver gifts, wedding registry, wedding presents, silver wedding presents, wedding gifts, silver wedding gifts, birthday presents, birthday gifts, silver birthday gifts, corporate presents, corporate gifts, silver corporate gifts,  selling your silver, purchasing information, sterling silver, sterling flatware, silver flatware, antique silver" />


<!--ogTags-->

<link rel="stylesheet" href="/css/dropdown/imports.css">
<link rel="stylesheet" href="/ayliss_style.css" type="text/css">
<link rel="stylesheet" href="/ayliss_style_uni.css" type="text/css">
<link rel="stylesheet" href="/ayliss_giftReg_style.css" type="text/css">

<script type="text/javascript" src="/js/ajax.js"></script>
<script type="text/javascript" src="/js/images.js"></script>
<script type="text/javascript" src="/js/store.js"></script>
<script type="text/javascript" src="/js/cookie.js"></script>
<script type="text/javascript" src="/js/giftRegSEO.js"></script>

<?php ("/home/asyoulik/public_html/js/analytics.html"); ?>
	
	<script type="text/javascript">
		var regID = <? echo $_COOKIE['aylissWeddingReg']; ?>;
	</script>

</head>

<body class="sub" onLoad="preLoad('hola');getItemCount();changeMainContent(2,regID,0)">
<div id="fb-root"></div>
<!--<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
-->
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
<?php include("otherlinks.php"); ?>
 <!--end other links -->

<!-- begin category links -->
<div class="categoryLinksContainer" id="defaultCatLinks">
  <?php
  include("staticHTMLFunctions.php");
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
     <h1 class="h1PageCatTitle" id="defaultH1">Bridal & Gift Registry</h1>
     <div id="defaultImage" class="pageCatImage"></div>
    </div>
  </div>
  
  <?php
 //error_reporting(E_ALL);
ini_set("display_errors","1");
include("connect/mysql_connect.php");

	extract($_GET);
	extract($_POST);

//echo "retrieving data...";

if(isset($_COOKIE['aylissWeddingReg'])){
	
	$regID = $_COOKIE['aylissWeddingReg'];
	$strAcctInfo = getAccountInfo($regID);
	
}

else{
	
	echo	"Sorry, there was an error retrieving your information. ";

}


function getAccountInfo($regID){

	 $query = "SELECT rfname,remail FROM weddingRegistries WHERE id= $regID";
	 $result = mysql_query($query);
	 $row = mysql_fetch_assoc($result);
	
	 extract($row);
	 
	 $strInfo = "<span>Welcome back, $rfname<br> You are logged in as $remail.
	 <a href='http://www.asyoulikeitsilvershop.com/giftRegistry.php?action=logout'>Logout</a></span>";
	
	 return $strInfo;
	
}

?>

<div class="row nopad" id="navBack">
	
  		 <div class="cell oneColumn"></div>
    	 <div class="cell fifteenColumns rightAlign">
    	 	<?php global $strAcctInfo; 
    	 	echo $strAcctInfo 
    	 	?>
    	 </div>
    	</div>
  
  <div class="row nopad">
  		  <div class="cell sixteenColumns">
  		  
<?

echoPageMenu();

function echoPageMenu(){
	
	global $regID;
	global $new;

	if($new){
		$greeting="
			<span class='sectHead'> 
				Welcome to your gift registry!<br>
				Use the above tabs to update your profile/contact information, update the items on your registry, and change your password.<br>
				
			</span>";

}

$c="<div class='row fullWidth'>
			<div class='navbar'>
				<div id='nav1' class='navitem' style='left:425px;background-color:#dddddd;'>
					<a class='tab' href=\"javascript:changeMainContent(1,'',0)\">Edit Profile</a>
				</div>
				<div id='nav2' class='navitem' style='left:25px;background-style:transparent;'>
					<a class='tab' href=\"javascript:changeMainContent(2,$regID,0)\">View/Edit Registry</a>
				</div>				
				<div id='nav3' class='navitem' style='left:225px;background-color:#dddddd;'>
					<a class='tab' href=\"javascript:changeMainContent(3,$regID,0)\">Add Items</a>
				</div>	
				<div id='nav4' class='navitem' style='left:625px;background-color:#dddddd;'>
					<a class='tab' href=\"javascript:changeMainContent(4,$regID,0)\">Change Password</a>	
				</div>
			</div>
	</div>
	
	<div class='row'>
		<div class='cell sixteenColumns' id='spanStatus'>
			<div id='spanStatus' style='padding-left:10px;font-size:14px;height:14px;padding-top:5px;color:#554444;'>
			</div>
		</div>
	</div>
	<div class='row'>
		<div class='cell sixteenColumns'>
			<div id='mainContentSub'></div>
		</div>
	</div>";

echo $c;

}

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
<?php ob_flush(); ?>


