<!DOCTYPE html>
<html>
<head>
<title>Item Wishlist | As You Like It Silver Shop, New Orleans, Louisiana</title>
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
	document.viewWishList.submit();
}

function changePattern(){

	var brand= document.getElementById('selectBrand').value;
	var url = '/TEST/PatternSelect.php';
	var params='brand='+brand;
	var responseAction='document.getElementById(\'spanPatternSelect\').innerHTML=request.responseText';
	
	requestURL(url,params,responseAction,'');
	
	//ajaxRequest.open("GET", "/TEST/PatternSelect.php?brand=" + brand, true);
	//ajaxRequest.send(null); 
}

</script>

<link rel="stylesheet" href="/css/dropdown/imports.css">
<link rel="stylesheet" href="/ayliss_style.css" type="text/css">
<link rel="stylesheet" href="/ayliss_style_uni.css" type="text/css">
<!--<link rel="stylesheet" href="/mobile.css" type="text/css">-->
<link href="/css/lightbox.css" rel="stylesheet" />


<? include("/home/asyoulik/public_html/js/analytics.html"); ?>

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
	  Create Your Wishlist and Add Items
	  </h2>
  </div>
  </div>
  <div class="row">
  <div class="cell oneColumn"></div>
	  <div class="cell fourteenColumns">
         <?
	include("/connect/mysql_connect.php");
	
	 function is_valid_email($email){
    	$isvalid=0;
    	
    	$regexp= "^([_a-z0-9-]+)(\.[_a-z0-9-]+)*@([a-z0-9-]+)(\.[a-z0-9-]+)*(\.[a-z]{2,4})$";
		
		if(eregi($regexp, $email)){
         
 		list($username,$domaintld) = split("@",$email);
      	// Validate the domain
      	
      	if (getmxrr($domaintld,$mxrecords)){
      		$isvalid=1;
      	}
          	  
    	}
	 
 	 return $isvalid;	
	 }

	extract($_GET);
	 if($_POST){
	 
	 $valid=1;
	 
	/*echo $av;
	echo $email;
	echo $pattern;
	echo $brand;
	echo $item;
	  */ 
	 if(!$av || $av==0 || $av==""){
 	 
 	 	if(is_valid_email($email)==0){
 	  		$emailmsg="Please enter a valid email address.";
 	  		$valid=0;
 	  	}
 	 	else{$emailmsg="";}
 	 
 	 	if(!$firstname || $firstname==""){
 	 		$fnamemsg="Please enter your first name.";
 	 		$valid=0;
 	 	}
 	 	else{$fnamemsg="";}
     
     	if(!$lastname || $lastname=="" ){
       		$lnamemsg="Please enter your last name.";
	    	$valid=0;
     	}
        else{$lnamemsg="";}
     
     	if(!$advertising || $advertising==""){
     		$admsg="Please tell us how you found out about As You Like It Silver Shop.";
     		$valid=0;
     	}
 	 	else{$admsg="";}
 	 
 	 }
 	 
 	 
 	 if($av){
 	 //this will check only on the second round because the first round defaults the pattern and maker values
 	 if(!$pattern || $pattern==""){
 	 if(!$brand || $brand==""){
 	  	 $ptnmsg="Please specify a pattern or a maker.";
 	  	 $valid=0;
 	 	}
 	 	else{$ptnmsg="";}
 	 }
 	 
 	 else{
 	  $ptnmsg="";
 	  }
 	 
 	 }

 	  	if(!$item || $item==""){
 	 	$itemmsg="Please specify an item, or choose 'Anything'";
 	 	$valid=0;
 	 	}
 	 
 	 	else{$itemmsg="";}
 	 
 	 	if(!$quantity || $quantity==0 || $quantity ==""){
 	  		$qtymsg = "Please enter a quantity greater than 0.";
 	  		$valid=0;
 	 	}
 	 
 	 
 	 
 	 if($valid){
 	  // create customer 
 	  if(newCustomer($email)){
 	   $query="INSERT INTO customerInfo(emailID,Title,FirstName, LastName, Street, City, State, Zip, nomail, HomePhone,advertising) 
	  VALUES(\"$email\",\"$title\",\"$firstname\",\"$lastname\",\"$street\",\"$city\",\"$state\",\"$zip\",\"$nomail\",\"$homephone\",\"$advertising\")";
	  $result=mysql_query($query);
	  //echo $query;
 	  }
 	  	 	  
	  // add wishlist item 
	  if($pattern!="" && !strpos($pattern," BY ")){
		  $pattern=$pattern. " BY ".$brand;
	  }
	  
	  $query="INSERT INTO wishlist(customerEmail,pattern,maker,item, qty,notes) VALUES(\"$email\",\"$pattern\",\"$brand\",\"$item\",\"$quantity\",\"$notes\")";
	  $result=mysql_query($query);

	  //echo $query;
      showConfirmation("","","","","");
      }
 	 
 	 /*show required fields with missing data*/	 
 	 else{
 	 
 	 if($av){
 	 //show the confirmation form version
 	 showConfirmation($itemmsg,$qtymsg,$ptnmsg,$brandmsg,"Please specify the required fields");
 	 }
 	 
 	 else{showForm($fnamemsg,$lnamemsg,$emailmsg,$qtymsg,$itemmsg,$quantity);}
   	 
   	 }
 	 
 	 //end if $_POST  	 
 	 }
 	 
 	 /*brand new form*/
 	 else{
 	 showForm("","","","","",1);
 	 }
 	
 	

	function newCustomer($email){
		$isnew=1;
		$query="SELECT emailID  FROM customerInfo WHERE emailID=\"$email\"";
		$result=mysql_query($query);
		$n=mysql_num_rows($result); 
		if($n>0){$isnew=0;}
   	 return $isnew;
  	}
 	
 	
 	function showConfirmation($itemmsg,$qtymsg,$ptnmsg,$brandmsg,$confmsg){
    
    $pattern="";
    $brand="";
    $item="";
    
    
    extract($_POST);
    
    
    
   $query=mysql_query("SELECT DISTINCT CONCAT(ucase(pattern),\" BY \",IF(brand<>\"\",ucase(brand),\"UNKNOWN\")) as p FROM inventory WHERE pattern!=\"\" ORDER BY pattern,brand");

	while ($r=mysql_fetch_assoc($query)) {
	    $p=$r['p'];
		$patterns.="<option value=\"$p\">$p</option>";
	}

	$query=mysql_query("SELECT distinct(brand) as p from inventory where brand!='' order by p");
		while ($r=mysql_fetch_assoc($query)) {
		$brands.="<option value=\"$r[p]\">$r[p]</option>";
	}
    
    if($pattern && $brand){
    $ptn=$pattern." by ".$brand;
    }
    else{$ptn=$pattern.$brand;}
    
    if($item!="Anything"){
    	$itm=$quantity." ".urldecode($item);
    }
    
    else{$itm=$item;}
    
    if(!$confmsg){
    $confmsg=$itm." in ".$ptn."  has been added to your wishlist.";}
    

    
    	echo("
    	<table>
    	<tbody>
    	<tr>
    	<td style=\"bottom-border:1px solid aaa\" colspan=\"2\">
    	<span style=\"font-size:12pt;color:#79282e\">$confmsg</span>
    	<br>As You Like It Silver Shop will notify you when we receive inventory matching your wishlist items.
    	<br clear=\"all\">
    	</td>
    	</tr>
    
    	<tr>
       	<form name=\"viewWishList\" action=\"viewWishlist.php\" method=\"post\">
    		<input name=\"email\" type=\"hidden\" value=\"$email\">
    		<td colspan=\"2\">
    			You may add more items to your wishlist using the form below, 
    			<a href=\"javascript:submitForm();\">View Your Wishlist</a> or 
    			<a href=\"javascript:history.go(-1)\">Resume Shopping</a>
    		</td>
    	</form>
    	</tr>
    	
    	<tr>
    		<td colspan=\"2\"><br clear=\"all\"></td>
    	</tr>
    	<form  style=\"margin-left:20px;width:760px\" method=\"post\" action=\"/wishlist.php\">
    		<tr>
    			<td>
    				<input type=\"hidden\" name=\"av\" value=\"1\">
    			</td>
    			<td>
    				<input type=\"hidden\" name=\"email\" value=\"$email\">
    			</td>
    		</tr>
    		<tr>
    			<td>Maker:</td>
    			<td><select id=\"selectBrand\" name=\"brand\">
    				<option value=''>--- SELECT BELOW ---$brands</select>*$ptnmsg
    			</td>
    		</tr>
    		<tr>
    			<td>Pattern:</td>
    			<td>
    				<span id=\"spanPatternSelect\">
    				<select name=\"pattern\">
    					<option value=''>--- SELECT BELOW ---$patterns
    				</select>
    				</span>*
    			</td>
    		</tr>
   	
    		<tr>	
				<td valign=\"top\">Category:</td>
				<td>
					<select name=\"category\">
					<option value=''>--- SELECT BELOW ---</option>
																<option value=\"H\">Hollowware</option>
																<option value=\"F\">Flatware</option>
																<option value=\"FCS\">Flatware Complete Sets</option>
																<option value=\"BS\">Baby Silver</option>
																<option value=\"J\">Jewelry</option>
																<option value=\"SP\">Serving Pieces</option>
																<option value=\"CP\">Cleaning Products</option>
																<option value=\"STP\">Storage Products</option>
																<option value=\"XM\">Christmas Ornamanets</option>
															</select>
				</td>
			</tr>
													
			<tr>
				<td valign=\"top\">Item:</td>
					<td>
						<select name=\"item\">
						<option value=''>--- SELECT BELOW ---</option>
						<option value=\"Anything\">Anything</option>");
						
						
$q=mysql_query("SELECT distinct(ucase(item)) as i from inventory where item <> '' and item IS NOT NULL order by item");
while ($r=mysql_fetch_assoc($q)) {
	echo "<option value=".urlencode($r[i]).">$r[i]</option>";
	}

echo "</select>*$itemmsg

<tr>
	<td valign=\"top\">Quantity:</td>
	<td><input name=\"quantity\" value=\"$quantity\" size=\"2\" type=\"text\">*$qtymsg</td>
</tr>
<tr>
	<td valign=\"top\">Notes:</td>
	<td><textarea rows=\"5\" cols=\"50\" name=\"notes\"></textarea></td>
</tr>
													
<tr>
	<td align=\"center\" colspan=\"2\">
	<input type=\"submit\" value=\"Add Item to Wishlist\"></td>
</tr>

</form>
</tbody>
</table>
";
   	
 	
 	
 	/*end function*/
 	}
 	
 	
 	function showForm($fnamemsg,$lnamemsg,$emailmsg,$qtymsg,$itemmsg,$quantity){
    $pageheader="Please enter your contact information.";	
	$sectionheader="Wishlist Item Information.";	
	
	if($_POST){extract($_POST);}
	extract($_GET);
   	   	
   	echo("
   	<form style=\"margin-left:20px;width:760px\" method=\"post\" action=\"/wishlist.php\">");
   	

   	echo("
   	<table>
   	<tbody>
   	<tr>
   			<td style=\"bottom-border:1px solid aaa\" colspan=\"2\">
   				<span style=\"font-size:12pt;color:#79282e\">$pageheader</span> * denotes required fields
   				<br clear=\"all\">
   		</td>
   		</tr>
    	
		<tr>
			<td nowrap=\"true\" class=\"cust\">Title:</td>
					<td>
						<select name=\"title\" value=\"$title\">
							<option value=\"\"></option>
							<option value=\"Dr.\">Dr.</option>
							<option value=\"Mr.\">Mr.</option>
							<option value=\"Mrs.\">Mrs.</option>
							<option value=\"Ms.\">Ms.</option>
						</select>
					</td>
													</tr>
													<tr>
														<td class=\"cust\">First Name:</td>
														<td><input size=\"30\" name=\"firstname\" value=\"$firstname\" type=\"text\"><span class=\"redtext\">*$fnamemsg</span></td>
													</tr>
													<tr>
														<td class=\"cust\">Last Name:</td>
														<td><input size=\"30\" name=\"lastname\" value=\"$lastname\" type=\"text\"><span class=\"redtext\">*$lnamemsg</span></td>
													</tr>
													<tr>
														<td class=\"cust\">Street:</td>
														<td><input size=\"30\" value=\"$street\"  name=\"street\" type=\"text\"></td>
													</tr>
													<tr>
														<td class=\"cust\" valign=\"top\">City:</td>
														<td><input size=\"30\" value=\"$city\" name=\"city\" type=\"text\"></td>
													</tr>
													<tr>
														<td class=\"cust\">State:</td>
														<td><select name=\"state\" value=\"$state\">
																<option value=\"NO\"></option>
																<option value=\"AL\">Alabama</option>
																<option value=\"AK\">Alaska</option>
																<option value=\"AZ\">Arizona</option>
																<option value=\"AR\">Arkansas</option>
																<option value=\"CA\">California</option>
																<option value=\"CO\">Colorado</option>
																<option value=\"CT\">Connecticut</option>
																<option value=\"DE\">Delaware</option>
																<option value=\"DC\">District of Columbia</option>
																<option value=\"FL\">Florida</option>
																<option value=\"GA\">Georgia</option>
																<option value=\"HI\">Hawaii</option>
																<option value=\"ID\">Idaho</option>
																<option value=\"IL\">Illinois</option>
																<option value=\"IN\">Indiana</option>
																<option value=\"IA\">Iowa</option>
																<option value=\"KS\">Kansas</option>
																<option value=\"KY\">Kentucky</option>
																<option value=\"LA\">Louisiana</option>
																<option value=\"ME\">Maine</option>
																<option value=\"MD\">Maryland</option>
																<option value=\"MA\">Massachusetts</option>
																<option value=\"MI\">Michigan</option>
																<option value=\"MN\">Minnesota</option>
																<option value=\"MS\">Mississippi</option>
																<option value=\"MO\">Missouri</option>
																<option value=\"MT\">Montana</option>
																<option value=\"NE\">Nebraska</option>
																<option value=\"NV\">Nevada</option>
																<option value=\"NH\">New Hampshire</option>
																<option value=\"NJ\">New Jersey</option>
																<option value=\"NM\">New Mexico</option>
																<option value=\"NY\">New York</option>
																<option value=\"NC\">North Carolina</option>
																<option value=\"ND\">North Dakota</option>
																<option value=\"OH\">Ohio</option>
																<option value=\"OK\">Oklahoma</option>
																<option value=\"OR\">Oregon</option>
																<option value=\"PA\">Pennsylvania</option>
																<option value=\"RI\">Rhode Island</option>
																<option value=\"SC\">South Carolina</option>
																<option value=\"SD\">South Dakota</option>
																<option value=\"TN\">Tennessee</option>
																<option value=\"TX\">Texas</option>
																<option value=\"UT\">Utah</option>
																<option value=\"VT\">Vermont</option>
																<option value=\"VA\">Virginia</option>
																<option value=\"WA\">Washington</option>
																<option value=\"WV\">West Virginia</option>
																<option value=\"WI\">Wisconsin</option>
																<option value=\"WY\">Wyoming</option>
															</select></td>
													</tr>
													<tr>
														<td class=\"cust\">Zip:</td>
														<td><input value=\"$zip\" size=\"10\"  name=\"zip\" type=\"text\" maxlength=\"10\"></td>
													</tr>
													<tr>
														<td class=\"cust\">E-mail:</td>
														<td><input size=\"30\" value=\"$email\" name=\"email\" type=\"text\"><span class=\"redtext\">*$emailmsg</span></td>
													</tr>
													<tr>
														<td class=\"cust\">Home Phone:</td>
														<td><input value=\"$homephone\" size=\"13\"  name=\"homephone\" type=\"text\" maxlength=\"13\"></td>
													</tr>
																										<tr>
														<td nowrap=\"false\" style=\"font-size:9pt\;width:200px;padding-right:10px;\">Would you like to added to <i>As You Like it Silver Shop's</i> mailing list?</td>
														<td><select name=\"nomail\" >
															<option value=\"0\">Yes</option>
															<option value=\"1\">No</option>
															</select>
														</td>
													</tr>
													<tr>
														<td nowrap=\"false\" style=\"font-size:9pt\;width:200px;padding-right:10px;\">How did you find out about <i>As You Like it Silver Shop</i>?:</td>
														<td>
														<select name=\"advertising\">
														<option value=\"\">--- SELECT BELOW ---</option>");
														
$q=mysql_query("SELECT instoreID as i,advertiserName as a from advertising order by advertiserName");
 while ($r=mysql_fetch_assoc($q)) {
	 echo "<option value=".urlencode($r[i]).">$r[a]</option>";
}
echo "</select>
							<span class=\"redtext\">*</span></td>
							</tr><tr><td colspan=\"2\"></td></tr>";


echo"
<tr><td colspan=\"2\"><br clear=\"all\"></td></tr>

			<tr>
				<td style=\"bottom-border:1px solid aaa\" colspan=\"2\"><span style=\"font-size:12pt;color:#79282e\">$sectionheader</span></td>
			</tr>
			<tr>
			<td colspan=\"2\"><span style=\"font-size:12pt;font-style:italic;color:#79282e\">Choose items in $pattern by $brand below</span></td>
			</tr>";										

/*<tr>

	<td>Pattern:</td>
	<td align=left><span id=\"spanPattern\"><input name=\"pattern\" type=\"text\" value=\"$pattern\"></span></td>
</tr>

<tr>
	<td>Manufacturer:</td>
	<td align=left>	<span id=\"spanBrand\"><input name=\"brand\"  type=\"text\" value=\"$brand\"></span></td>
</tr>
*/
echo "											
												<tr>	
													<td valign=\"top\">Category:</td>
														<td><select name=\"category\">
																<option value=''>--- SELECT BELOW ---</option>
																<option value=\"H\">Hollowware</option>
																<option value=\"F\">Flatware</option>
																<option value=\"FCS\">Flatware Complete Sets</option>
																<option value=\"BS\">Baby Silver</option>
																<option value=\"J\">Jewelry</option>
																<option value=\"SP\">Serving Pieces</option>
																<option value=\"CP\">Cleaning Products</option>
																<option value=\"STP\">Storage Products</option>
																<option value=\"XM\">Christmas Ornamanets</option>
															</select>
														</td>
													</tr>
													
													<tr>
												 	<td valign=\"top\">Item:</td>
													<td>
													<select name=\"item\">
														<option value=''>
															--- SELECT BELOW ---														</option>
														<option value=\"Anything\">Anything</option>";

/*<tr>
														<td class=\"cust\">Work Phone:</td>
														<td><input value=\"$workphone\" size=\"13\"  name=\"workphone\" type=\"text\" maxlength=\"13\"></td>
													</tr>
													<tr>
														<td class=\"cust\">Cell Phone:</td>
														<td><input value=\"$cellphone\" size=\"13\" name=\"cellphone\" type=\"text\" maxlength=\"13\"></td>
													</tr>*/

$q=mysql_query("SELECT distinct(ucase(item)) as i from inventory where item <> '' and item IS NOT NULL order by item");
while ($r=mysql_fetch_assoc($q)) {
	echo "<option value=".urlencode($r[i]).">$r[i]</option>";
	}

echo "</select>*$itemmsg
</td>
		</tr>
		<tr>
			<td valign=\"top\">Quantity:</td>
			<td><input name=\"quantity\" value=\"$quantity\" size=\"2\" type=\"text\">*$qtymsg</td>
		</tr>
		<tr>
			<td valign=\"top\">Notes:</td>
			<td><textarea rows=\"5\" cols=\"50\" name=\"notes\" value=\"$notes\"></textarea></td>
		</tr>
													
		<tr>
			<td align=\"center\" colspan=\"2\"><input class=\"genericButton\" type=\"submit\" value=\"Add Item to Wishlist\"></td>
		</tr>
	</tbody>
	</table>
</form>"; 

   
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





