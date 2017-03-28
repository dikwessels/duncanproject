<!DOCTYPE html>
<html>
<head>
<title>Silver Repairs | As You Like It Silver Shop, New Orleans, Louisiana</title>
<meta charset="UTF-8"/>
<meta name="description" content="As You Like It Silver Shop in New Orleans Louisiana specializes in silver flatware and holloware in active, inactive and obsolete patterns."/>
<meta name="keywords" content="selling your silver, purchasing information, sterling silver, sterling flatware, silver flatware, antique silver, silver tableware, antique sterling, replacement silver, silver repair, corporate gifts, wedding gifts, silver identification, cleaning silver"/>

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

<script type="text/javascript">

  var pluginUrl ='//www.google-analytics.com/plugins/ga/inpage_linkid.js';
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
     <h1 class="h1PageCatTitle" id="defaultH1">Silver Repairs</h1>
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
	  <div class="cell twelveColumns">
		  
      <form id="form1" name="form1" method="post" action="" onSubmit='return check(this)'>
        <div class="row fullWidth">
        	<div class="cell sixteenColumns style1">
        	Please enter your last name AND your zip code.
        	</div>
        </div>
        <div class="row fullWidth">
	        <div class="cell threeColumns">Last name: <span class="super">*</span> </div>
	        	<div class="cell thirteenColumns">
		        	<input class="repairFormInput" name="lastname" type="text" value ="" size="10" maxlength="20">
		        </div>
        </div>



       <div class="row fullWidth">
	          <div class="cell threeColumns">Zip Code:<span class="super">*</span> </div>   
	         <div class="cell thirteenColumns"> 
		         <input class="repairFormInput" name="zip" type="text" value="" size="5" maxlength="5">
	         </div>
       </div>
        

         <div class="row fullWidth">
          <div class="cell sixteenColumns style1">
          Or you may enter only the repair number of the item.
          </div>
         </div>
         <div class="row fullWidth">
           <div class="cell threeColumns"> Repair number:<span class="super">*</span></div>	
           <div class="cell thirteenColumns">
            <input class="repairFormInput" name="repairnumber" type="text" value ="" size="8" maxlength="8">
           </div>
         </div>
         <div class="row fullWidth">
         	<div class="cell sixteenColumns">
              <input class="repairFormButton" type="submit" name="Submit" value="Submit">
         	</div>
         </div>


            <div class="row fullWidth">
             <div class="cell sixteenColumns">
            	*denotes a required field 
             </div>
            </div>
		

		 
<?php


//include_once("/connect/mysql_pdo_connect.php");

$_REQUEST["lastname"]=$lastname;

$_REQUEST["zip"]=$zip;

$_REQUEST["repairnumber"]=$repairnumber;


mysql_select_db("ayliss");



/* if (!$repairnumber && !$zip && !$lastname) {

	echo "<p><b>You must enter the appropriate information.</p></b>"; 

 	} */



if ($lastname && $zip && !$repairnumber) {

	

	$query = "SELECT * FROM repairs WHERE last_name = '$lastname' AND zip = '$zip'";

	$result= mysql_query($query);

	

	if (mysql_num_rows($result)== 0) {

		echo "<p><b>An entry for the lastname/zip code combination you provided does not exist.</p></b>";

		 break;}  

	}



else if ($repairnumber) {

	$query = "SELECT * FROM repairs WHERE invoice_num = '$repairnumber'";

 	$result= mysql_query($query);

	

	if (mysql_num_rows($result)== 0) {

		echo "<p><b>An entry for the repair number you provided does not exist.</p></b>";

		 break;}  

	}

	



	
while($row = mysql_fetch_row($result)) {

		echo "<h3><p><b>REPAIR INVOICE</b></p></h3>";

		if (!$row[7]) {

		

			if (!$row[5]) {

			

			 echo " <p>&nbsp;</p>

<div align='center'>

  <table width='1011' height='107'>

    <tr>

      <td><div align='center'><b>Repair number: </div></td>

      <td><div align='center'><b>Customer last name: </div></td>

      <td><div align='center'><b>Zip code: </div></td>

      <td><div align='center'><b>Receive date: </div></td>

      <td><div align='center'><b>Final quote: </div></td>

      <td><div align='center'><b>Approved:</div></td>

      <td><div align='center'><b>Approval date: </div></td>

      <td><div align='center'><b>Completed:</div></td>

    </tr>

    <tr>

      <td><div align='center'>$row[0]</div></td>

      <td><div align='center'>$row[1]</div></td>

      <td><div align='center'>$row[2]</div></td>

      <td><div align='center'>$row[3]</div></td>

      <td><div align='center'>Not Available</div></td>

      <td><div align='center'>No</div></td>

      <td><div align='center'>Not Available</div></td>

      <td><div align='center'>No</div></td>

    </tr>

  </table>

</div>

<p align=center'>&nbsp;</p>

			 

<p class ='body'>The item(s) to be repaired were brought into our store on $row[3] and we have not yet recieved an exact quote on the repair from the silversmith. You should expect a call from us soon with a quote. If you brought the repair in over six weeks ago, please contact us during out store hours. <a href='contactus.php'>Our contact information may be found here.</a></p>";

				

				 

				

				}

			else {

		

				

				

				if (!$row[6]) {

				

					//Display MSG 2

					echo " <p>&nbsp;</p>

<div align='center'>

  <table width='1011' height='107'>

    <tr>

      <td><div align='center'><b>Repair number: </div></td>

      <td><div align='center'><b>Customer last name: </div></td>

      <td><div align='center'><b>Zip code: </div></td>

      <td><div align='center'><b>Receive date: </div></td>

      <td><div align='center'><b>Final quote: </div></td>

      <td><div align='center'><b>Approved:</div></td>

      <td><div align='center'><b>Approval date: </div></td>

      <td><div align='center'><b>Completed:</div></td>

    </tr>

    <tr>

      <td><div align='center'>$row[0]</div></td>

      <td><div align='center'>$row[1]</div></td>

      <td><div align='center'>$row[2]</div></td>

      <td><div align='center'>$row[3]</div></td>

      <td><div align='center'>$row[5]</div></td>

      <td><div align='center'>No</div></td>

      <td><div align='center'>Not Available</div></td>

      <td><div align='center'>No</div></td>

    </tr>

  </table>

</div>

<p align=center'>&nbsp;</p>

			 

<p class ='body'>We recieved an exact quote to repair your item(s) of $row[5] . Please either call or email us with your approval to proceed with the repair for this price. <a href='contactus.php'>Our contact information may be found here.</a></p>";

				

				

				

					echo "<p>Or you may click the 'Approve' button below to approve the repair work. Please be sure to enter

							your email address into the space provided to receive a conformation email.</p>";

					echo "</form><form name='input' action='approve.php' method='get'>";

					echo "<p><input type='text' name='customeremail' value =''></p>";

				 	echo "<p><input type='hidden' name='repairnumber' value=$row[0]>

							<input type='submit' value='Approve!'>

							</form>";

					

					

					}

				else {

				

					//Display MSG 3

								echo " <p>&nbsp;</p>

<div align='center'>

  <table width='1011' height='107'>

    <tr>

      <td><div align='center'><b>Repair number: </div></td>

      <td><div align='center'><b>Customer last name: </div></td>

      <td><div align='center'><b>Zip code: </div></td>

      <td><div align='center'><b>Receive date: </div></td>

      <td><div align='center'><b>Final quote: </div></td>

      <td><div align='center'><b>Approved:</div></td>

      <td><div align='center'><b>Approval date: </div></td>

      <td><div align='center'><b>Completed:</div></td>

    </tr>

    <tr>

      <td><div align='center'>$row[0]</div></td>

      <td><div align='center'>$row[1]</div></td>

      <td><div align='center'>$row[2]</div></td>

      <td><div align='center'>$row[3]</div></td>

      <td><div align='center'>$row[5]</div></td>

      <td><div align='center'>Yes</div></td>

      <td><div align='center'>$row[4]</div></td>

      <td><div align='center'>No</div></td>

    </tr>

  </table>

</div>

<p align=center'>&nbsp;</p>

			 

<p class ='body'>We recieved your approval on $row[4] for the repair of your silver item(s), and we are waiting for the item(s) to be returned to our store by the silversmith. If this repair was approved over ten weeks ago, please contact us.<a href='contactus.php'>Our contact information may be found here.</a></p>";

					

					}

				}

			}

		else {

		

			//Display MSG 4

			

			if (!$row[6]) {

			

			echo " <p>&nbsp;</p>

<div align='center'>

  <table width='1011' height='107'>

    <tr>

      <td><div align='center'><b>Repair number: </div></td>

      <td><div align='center'><b>Customer last name: </div></td>

      <td><div align='center'><b>Zip code: </div></td>

      <td><div align='center'><b>Receive date: </div></td>

      <td><div align='center'><b>Final quote: </div></td>

      <td><div align='center'><b>Approved:</div></td>

      <td><div align='center'><b>Approval date: </div></td>

      <td><div align='center'><b>Completed:</div></td>

    </tr>

    <tr>

      <td><div align='center'>$row[0]</div></td>

      <td><div align='center'>$row[1]</div></td>

      <td><div align='center'>$row[2]</div></td>

      <td><div align='center'>$row[3]</div></td>

      <td><div align='center'>$row[5]</div></td>

      <td><div align='center'>Not Available</div></td>

      <td><div align='center'>Not Available</div></td>

      <td><div align='center'>Yes</div></td>

    </tr>

  </table>

</div>

<p align=center'>&nbsp;</p>

			 

<p class ='body'>Your repair is completed and you may either come by to pick it up or call us to arrange shipping.<a href='contactus.php'>Our contact information may be found here.</a></p>";

			

		}

		else {	echo " <p>&nbsp;</p>

<div align='center'>

  <table width='1011' height='107'>

    <tr>

      <td><div align='center'><b>Repair number: </div></td>

      <td><div align='center'><b>Customer last name: </div></td>

      <td><div align='center'><b>Zip code: </div></td>

      <td><div align='center'><b>Receive date: </div></td>

      <td><div align='center'><b>Final quote: </div></td>

      <td><div align='center'><b>Approved:</div></td>

      <td><div align='center'><b>Approval date: </div></td>

      <td><div align='center'><b>Completed:</div></td>

    </tr>

    <tr>

      <td><div align='center'>$row[0]</div></td>

      <td><div align='center'>$row[1]</div></td>

      <td><div align='center'>$row[2]</div></td>

      <td><div align='center'>$row[3]</div></td>

      <td><div align='center'>$row[5]</div></td>

      <td><div align='center'>Yes</div></td>

      <td><div align='center'>$row[4]</div></td>

      <td><div align='center'>Yes</div></td>

    </tr>

  </table>

</div>

<p align=center'>&nbsp;</p>

			 

<p class ='body'>Your repair is completed and you may either come by to pick it up or call us to arrange shipping.<a href='contactus.php'>Our contact information may be found here.</a></p>";

 				}			

			}

		

	} 

		





?> 



		

      </form>


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





