<?php 
	
ob_start();
	
include("/connect/mysql_pdo_connect.php");	

ini_set("display_errors","1");

	extract($_GET);
	extract($_POST);

	//echo "retrieving data...";
	if( isset($_COOKIE['aylissWeddingReg']) ){
		$regID = $_COOKIE['aylissWeddingReg'];
		$strAcctInfo = getAccountInfo($regID);
	}
			
	else{
		$strAcctInfo = "Sorry, there was an error retrieving your information. ";
	}

function brandOptions(){
	global $db;
	global $brand;
	
	$stmt = "SELECT distinct(ucase(brand)) as b from inventory where quantity!=0 AND brand!='' order by b";
	
	$query = $db->prepare($stmt);
		
	$query->execute();
	
	$result = $query->fetchAll();
	
	$brandList .= "<option value=''></option>";
		
	foreach( $result as $r ){
		
	    	$b=$r['b'];
	    	
			$selected = $b == urldecode($brand)?"selected='selected'":"";
		
			$brandList .= "<option value='$b' $selected\">$b</option>";
	}
		
	return $brandList;

}

function echoPageMenu(){
	
	global $regID;
	global $new;

	if( $new ){
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
		<div class='sixteen   columns' id='spanStatus'>
			<div id='spanStatus' style='padding-left:10px;font-size:14px;height:14px;padding-top:5px;color:#554444;'>
			</div>
		</div>
	</div>
	<div class='row'>
		<div class=' sixteen  columns'>
			<div id='mainContentSub'></div>
		</div>
	</div>";

echo $c;

}

function getAccountInfo($regID){

	//connection object
	global $db;
	
	$stmt = "SELECT rfname,remail FROM weddingRegistries WHERE id=:regID";
	$query = $db->prepare($stmt);
	$query->bindParam('regID',$regID);
	 
	$query->execute();
	$result = $query->fetchAll();
	
	$row = $result[0];
	 
	extract($row);
 
	$strInfo="<span>Welcome back, $rfname<br> You are logged in as $remail.
 <a href='http://www.asyoulikeitsilvershop.com/giftRegistry.php?action=logout'>Logout</a></span>";

 	return $strInfo;

}
	
function patternOptionList(){
	
	global $db;
	global $pattern;
		
		$stmt = "SELECT DISTINCT CONCAT(ucase(pattern),\" BY \",IF(brand<>\"\",ucase(brand),\"UNKNOWN\")) as p,ucase(pattern) as pvalue FROM inventory WHERE quantity!=0 AND pattern!=\"\" ORDER BY pattern,brand";
		
		$query = $db->prepare($stmt);
		
		$query->execute();
		
		$result = $query->fetchAll();
		
		$patternOptions =  "<option value=''></option>\n\r
							<option value='NON'>NON-PATTERNED</option>";
							
		foreach( $result as $r ){
			
	    	$p = $r['p'];
	    	
			$pvalue = $r['pvalue'];
			
			$selected = $pvalue == urldecode($pattern)?"selected='selected'":"";
			
			$patternOptions .= "<option value='$pvalue' $selected>$p</option>\n\r";
		
		}
		
		return $patternOptions;
	
}
	
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Your Gift Registry | As You Like It Silver Shop, New Orleans, Louisiana</title>
<meta charset="UTF-8"/>

<meta name="description" content="As You Like It Silver Shop in New Orleans Louisiana specializes in silver flatware and holloware in active, inactive and obsolete patterns, gifts for weddings, birthdays, corporate events and special occasions."/>

<meta name="keywords" content="gift registry, silver gift registry, silver gifts, wedding registry, wedding presents, silver wedding presents, wedding gifts, silver wedding gifts, birthday presents, birthday gifts, silver birthday gifts, corporate presents, corporate gifts, silver corporate gifts,  selling your silver, purchasing information, sterling silver, sterling flatware, silver flatware, antique silver" />


<!--ogTags-->

<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>	
<script type="text/javascript" src="/js/libs/modernizr-2.6.2.min.js"></script>
<script type="text/javascript" src="/js/libs/gumby.min.js"></script>
<script type="text/javascript" src="/js/libs/ui/gumby.skiplink.js"></script>
<script type="text/javascript" src="/js/handlebars-v2.0.0.js"></script>
<script type="text/javascript" src="/js/cart-min.js"></script>
<script type="text/javascript" src="/js/giftRegistryManagementFunctions-min.js"></script>

<link rel="stylesheet" type="text/css" href="/css/Gumby/css/gumby.css">
<link rel="stylesheet" type="text/css" href="/css/categories.css">	


<script type="text/javascript">
	
	var activeSection='#registryItems';
	var regID = '<? 
					echo $regID; 
				?>';
	
	$(document).ready(function(){
		$.refreshCart();
	});
	
</script>


<? include("/home/asyoulik/public_html/js/analytics.html"); ?>

</head>

<body class="default">
	
<div id="modal1" class="modal">
		<div class="content">
			<a gumby-trigger="|#modal1" class="close switch"><i title=".icon-cancel" class="icon-cancel"></i></a>
			<div class="row">
				<div class="ten   columns centered">
					<h2>Add to Cart</h2>
					<p id="addItemConfirm"></p>
				</div>
			</div>
			<div class="row">
				<div class="ten   columns centered">
					<div class="container sixteen colgrid">
						<div class="row">
							<div class="eight   columns btn medium default">
								<a gumby-trigger="|#modal1" class="switch active" href="#">Continue Shopping</a>
							</div>
        
							<div class="eight   columns btn medium default">
								<a href="https://www.asyoulikeitsilvershop.com/shoppingCart.php">Checkout →</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
</div>

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
			<span id="itemCount"></span><br>
	     		<a href="https://www.asyoulikeitsilvershop.com/shoppingCart.php">
		 		<img alt="View your shopping cart" class="silverChest" src="/images/silverchest_empty.gif" id="chest">
		 		</a>
		
	</div>
		
</div>
	
<div class="row desktopOnly">
	<h2 class="fifteen  columns centered center">
		Estate sterling silver flatware, hollowware, jewelry, baby silver and silver repair services
	</h2>
</div>

</header>

<? include("navigation.html"); ?>

<section class="container sixteen colgrid mainContent" role="main" id="mainContent">
	
	<header class="row fullWidth">
		<h1>Bridal & Gift Registry</h1>
	</header>
	
	<div class="row" id="statusBar">
		<div class="sixteen   columns alignRight" id="navBack">
			
		<? 
			echo $strAcctInfo;
		?>
		
		</div>
	</div>

	<nav class='row fullWidth navbar'>
			<ul>
			
				<li id='nav1' class='navitem' style='background-color:#dddddd;'>
					<a class='tab' data-target="#editProfile" href="#">Edit Profile</a>
				</li>
			
				<li id='nav2' class='navitem' style='background-style:transparent;'>
					<a class='tab' data-target="#registryItems" href="#">View/Edit Registry</a>
				</li>	
							
				<li id='nav3' class='navitem' style='background-color:#dddddd;'>
					<a class='tab' data-target="#addRegistryItems" href="#">Add Items</a>
				</li>	
				
				<li id='nav4' class='navitem' style='background-color:#dddddd;'>
					<a class='tab' data-target="#changePassword" href="#">Change Password</a>	
				</li>
			
			</ul>
	</nav>	
	
	<div class="row">
		<div class="sixteen  columns" id ="spanStatus"></div>
	</div>
	
	
	<div class='row'>
		
		<section id="registryItems" class="regSection active">
			<div class='sixteen columns'>
				<div id='mainContentSub'></div>
			</div>
		</section>
		
		<section id="addRegistryItems" class="regSection">
			<section class="container sixteen colgrid">
				<header class="row border-bottom">
					<h3 class="sixteen columns" >Search our inventory:</h3>
				</header>
			
			
				<form id="inventorySearch" class="border-bottom">
					<div class="row">
						<div class="three columns">
							Pattern:
						</div>
						<div class="nine columns field">
							<div class="picker">
								<select name="pattern" id="selectPattern">
									<? 
									
										$patternOptions =  patternOptionList();
										echo $patternOptions;
									
									 ?>
			</select>
							</div>
						</div>
						<div class="four columns" id="patternLink"></div>
						
					</div>
					
					<div class="row">
		
						<div class="three columns ">
								Maker:
						</div>
			
						<div class="eleven columns field">
							<div class="picker">
								<select name="brand" id="selectBrand">
									<? 
										$brandList = brandOptions();
										echo $brandList; 
									?>
								</select>
							</div>
						</div>
						
						</div>			
					
						<div class="row">
							
							<div class="three columns ">
								Category:
							</div>
				
							<div class="eleven columns field">
								<div class="picker">
									<select class="medium-input" name="category" id="selectCategory"><option value=""></option><option value='BS' >Baby Silver</option>
<option value='CP' >Cleaning Products</option>
<option value='F' >Flatware</option>
<option value='CL' >Collectibles</option>
<option value='FCS' >Complete Sets</option>
<option value='XM' >Christmas Ornaments</option>
<option value='H' >Hollowware</option>
<option value='J' >Jewelry</option>
<option value='SP' >Serving Pieces</option>
<option value='STP' >Storage Products</option>
</select>
								</div>
							</div>
						</div>
						
					<div class="row">

			<div class="three columns ">
				Item:
			</div>
			<div class="eleven columns field">
				<input class="input" type="text" name="item" id="selectItem"></div>
			</div>
			
			<div class="row border-bottom">
				<div class="three columns ">
					Retail Price:
				</div>
				
				<div class="three columns field">
					<input class="small-input" type="text" size="10" name="minretail" id="minretail">
				</div>
			
				<div class="three columns field">
					<input class="small-input" type="text" size="10" name="maxretail" id="maxretail">
				</div>
			</div>
			
			<div class="row centered">
				<div class="four columns field">
					<button class="default metro btn" id="searchInventory" name="Search" value="Search Inventory">
				</div>
			</div>
			</form>		
			
			<section id="inventory"></section>
			
		</section>
			
		</section>
		<section id="editProfile" class="regSection"></section>
		<section id="changePassword" class="regSection"></section>
	</div>

	
</section>

<footer>
   <?
   	$c=include("copyright.php");
   	echo $c;
   ?>
</footer>

</body>

</html>

<?php ob_flush(); ?>


