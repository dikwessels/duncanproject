<!DOCTYPE html>
<html>
<head>
<title>Silver Inventory Search | As You Like It Silver Shop, New Orleans, Louisiana</title>
<meta charset="UTF-8"/>
<meta name="description" content="As You Like It Silver Shop in New Orleans Louisiana specializes in silver flatware and holloware in active, inactive and obsolete patterns."/>
<meta name="keywords" content="customer privacy policy, selling your silver, purchasing information, sterling silver, sterling flatware, silver flatware, antique silver, silver tableware, antique sterling, replacement silver, silver repair, corporate gifts, wedding gifts, silver identification, cleaning silver"/>

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

<style>
 
 div#suggestedItems{
	 display: inline-block;	 
	 margin-left: 0px;
	 max-width: 817px;
	 width: 100%;
	 
 }
 
</style>

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
     <h1 class="h1PageCatTitle" id="defaultH1">Silver Inventory Search</h1>
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
	  
<table cellpadding="1" cellspacing="0" border="0" align="center">
<tbody>
<form>
<?
include("/connect/mysql_connect.php");

$query=mysql_query("SELECT distinct(pattern) as p  from inventory where pattern!='' order by p");
while ($r=mysql_fetch_assoc($query)) {
	$patterns.="<option value=\"$r[p]\">$r[p]";
	}

$query=mysql_query("SELECT distinct(designPeriod) as p  from inventory where designPeriod!='' order by p");
 while ($r=mysql_fetch_assoc($query)) {
	$periods.="<option value=\"$r[p]\">$r[p]";
	}

$query=mysql_query("SELECT distinct(brand) as p  from inventory where brand!='' order by p");
while ($r=mysql_fetch_assoc($query)) {
	$brands.="<option value=\"$r[p]\">$r[p]";
	}
?>
<tr>
	<th>Manufacturer</th>
	<td align="left">&nbsp;
		<select name="brand">
			<option value=''>----------      SELECT BELOW      ---------<? echo $brands; ?>
		</select> 
	</td>
</tr>
<tr>
	<td colspan="2">
		<img src="images/blank.gif" width="1" height="10" alt="" border="0"></td>
</tr>				
<tr>
	<th width="75">Pattern</th>

	<td align="left">&nbsp;
		<select name="pattern">
			<option value=''>----------      SELECT BELOW      ---------<? echo $patterns; ?>
		</select>
	</td>
</tr>

<tr>
	<td colspan="2">
		<img src="images/blank.gif" width="1" height="10" alt="" border="0">
	</td>
</tr>				

<tr>
	<th width="75">Design Period</th>
	<td align="left">&nbsp;
		<select name="designPeriod">
			<option value=''>----------      SELECT BELOW      ---------<? echo $periods; ?>
		</select>
	</td>
</tr>

<tr>
	<td colspan="2">
		<img src="images/blank.gif" width="1" height="10" alt="" border="0">
	</td>
</tr>	

<tr>

<th width="75">Category</th>
	<td align="left">&nbsp;
		<SELECT id="select3" name="category" value="H">
		
		<OPTION value=''>----------      SELECT BELOW      ---------</option>	
		<OPTION value='FCS'>Complete Sets</option>	
		<OPTION value='BS'>Baby Silver</option>
		<OPTION value='H'>Hollowware</option>
		<OPTION value='F'>Flatware</option>
		<OPTION value='SP'>Serving Pieces</option>

		</select>
	</td>
</tr>

<tr>
	<td colspan="2">
		<img src="images/blank.gif" width="1" height="10" alt="" border="0">
	</td>
</tr>		

<tr>
	<th>Item Name</th>
	<td align="left">&nbsp;
	<SELECT name="itemname">
		<option value="">----------      SELECT BELOW      ---------
<?
$q=mysql_query("SELECT distinct(ucase(item)) as i from inventory where item <> '' and item IS NOT NULL and (quantity>0 or quantity=-1) order by item");
while ($r=mysql_fetch_assoc($q)) {
	echo "<option value=".urlencode($r[i]).">$r[i]";
	}
?>
		</select>
	</td>
</tr>

<tr>
	<td colspan="2">
		<img src="images/blank.gif" width="1" height="10" alt="" border="0">
	</td>
</tr>
		
<tr>
	<th>Keyword</th>
	<td align="left">&nbsp;
		<input type="text" size="30" name="item">
		<span class="example">(For Example: 'Candelabra', 'Tea Set', 'Gravy Ladle')</span>
	</td>
</tr>
<tr>
	<td colspan="2">
		<img src="images/blank.gif" width="1" height="10" alt="" border="0">
	</td>
</tr>
<tr>
	<th>Country Of Origin</th>
	<td align="left">&nbsp;
		<select name="origin">
			<option value="">----------      SELECT BELOW      ---------
<?
$q=mysql_query("SELECT distinct(origin) as o from inventory order by origin");
while ($r=mysql_fetch_assoc($q)) {
	echo "<option value=$r[o]>$r[o]";
	}
?>
		</select>
	</td>
</tr>

<tr>
	<td colspan="2">
		<img src="images/blank.gif" width="1" height="10" alt="" border="0">
	</td>
</tr>				

<tr>
	<td colspan="2">
		<img src="images/blank.gif" width="1" height="10" alt="" border="0">
	</td>
</tr>				
<tr>
	<td colspan="2" align="center">
			<input TYPE="submit" value="SEARCH" id="submit2" name="submit2" class="big">
	</td>
</tr>
</form>
</tbody>
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





