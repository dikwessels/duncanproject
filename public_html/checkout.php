<?php 

session_name('checkout');
session_start();

ob_start();

ini_set("display_errors",1);

include_once("/home/asyoulik/connect/mysql_pdo_connect.php");

include("/home/asyoulik/public_html/staticHTMLFunctions.php");
include("/home/asyoulik/public_html/categoryArrays.php");


//add this when error logging script is set
//include("/home/asyoulik/public_html/log-checkout-errors.php");

include_once("checkoutSettings.php");
require_once("PayflowNVPAPI.php");

//include_once("processorErrorMessages.php");

$errorMessage = "";

if(!$_SESSION['tempCustomerID']){
	
$values="'".$_SESSION['fname']."','".$_SESSION['lname']."','".$_SESSION['address1']."','".$_SESSION['address2']."','".$_SESSION['city']."','".$_SESSION['state']."','".$_SESSION['zip']."','','','".$_SESSION['shippingmethod']."','".$_SESSION['giftwrap']."','".$_SESSION['note']."','".$_SESSION['subtotal']."','','".$_SESSION['shipping']."','".$_COOKIE['items']."'";

$stmt="INSERT into tblTempInvoiceData(fname,lname,address1,address2,city,state,zip,phone,country,shippingMethod,giftwrap,note,subtotal,tax,shipping,items) values($values)";

$query=$db->prepare($stmt);
$query->execute();

	//$result=mysql_query($query);
	if($db->lastInsertId()>0){
		$tempID=$db->lastInsertId();
	}
	
	else{
		 //echo(mysql_error());
	}


	$_SESSION['tempCustomerID']=$tempID;
	
	setcookie('custNum',$tempID,time()+7200,'/'); 
}
else{
	setcookie('custNum',$_SESSION['tempCustomerID'],time()+7200,'/'); 
}


 if(  isset($_POST['RESULT'])  ||  isset($_GET['RESULT'])  ) {
	     echo "<script type='text/javascript'>
	      	 document.write = 'Processing request...<img src = \'/images/ajax-loader.gif\'>';
	     </script>";
	     
		  	//the iframe returned a response, likely an error if it's getting here
		 	$_SESSION['payflowresponse'] = array_merge($_GET, $_POST);

    		//reload the page and the error will be displayed
  			echo '<script type="text/javascript">window.top.location.href = "' . script_url() .  '";</script>';
  			exit(0);
		}

		else{
		
			if( $_SESSION['payflowresponse'] ){
							$errorMessage=$_SESSION['payflowresponse']['RESPMSG'];
				
				$errorMessage = "<div class='row'>
						<div class='cell twoColumns'></div>
						<div class='cell fourteenColumns error'><strong>$errorMessage</strong></div>
					</div>";	
				
				//email error report to webmaster
				$emailBody = "There was an error for the following temp transaction: ";
				$emailBody.= $_SESSION['tempCustomerID']."n\r";
				$emailBody.="Error Details: ".$errorMessage;
				
				mail("wagnermichaeljames@gmail.com","AYLISS Checkout Error",$errorMessage);	
				
				//clear out the error and response information
				unset($_SESSION['payflowresponse']);
 			}

		}



?>
<!DOCTYPE html>
<html>
<head>
<title>Check Out | As You Like It Silver Shop, New Orleans, Louisiana</title>
<meta charset="UTF-8"/>
<meta name="description" content="As You Like It Silver Shop in New Orleans Louisiana specializes in silver flatware and holloware in active, inactive and obsolete patterns."/>
<meta name="keywords" content="customer privacy policy, selling your silver, purchasing information, sterling silver, sterling flatware, silver flatware, antique silver, silver tableware, antique sterling, replacement silver, silver repair, corporate gifts, wedding gifts, silver identification, cleaning silver"/>

<!--ogTags-->

<?php 
	
	if(isset($_SERVER['HTTPS'])){
		if($_SERVER['HTTPS']!=""){
			$http="https:";
		}
		else{
			$http="http:";
		}
	}

	echo "<base href='$http//www.asyoulikeitsilvershop.com/'>";

?>


<script type="text/javascript" src="js/jquery-1.8.2.js"></script>
<script type="text/javascript" src="js/formvalidation.js"></script>
<script type="text/javascript" src="js/sandbox.checkout-functions-min.js"></script>

<link rel="stylesheet" href="/css/dropdown/imports.css">
<link rel="stylesheet" href="/ayliss_style.css" type="text/css">
<link rel="stylesheet" href="/ayliss_style_uni.css" type="text/css">


</head>

<body class="sub">
	
<? include_once("analyticstracking.php"); ?>
	
<div id="container">
<!-- begin page head -->
<div class="pageHead" id="defaultPageHead" style="border-bottom:1px solid #333">
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

<div class="mainContent">
  <!-- begin main content head with h1 -->
  
  <div class="mainContentHead" id="defaultH1Container">
    <div class="titleContainer border-bottom">
     <h1 class="h1PageCatTitle" id="defaultH1">Check Out</h1>
    </div>
    <div class="pageCatImage" style="text-align:right;top:2px;background-color:white;" title="Click to Verify - This site chose Thawte SSL for secure e-commerce and confidential communications.">
<script type="text/javascript" src="https://seal.thawte.com/getthawteseal?host_name=www.asyoulikeitsilvershop.com&amp;size=S&amp;lang=en"></script>
	    
    </div>
  </div>
 
  <div class="row">
  	<div class="cell twoColumns"></div>
  	<div class="cell fourteenColumns">
  		<ul class="horizontal ordersteps">
			<li id="step-1" class="orderstep current">1. Shipping & Billing Information</li>
			<li id="step-2" class="orderstep">2. Review Order and Submit Payment</li>
			<li id="step-3" class="orderstep">3. Order Confirmation</li>
		</ul>
  	</div>
 </div>

     <? 
	    
	    if( $errorMessage != ""){
		    echo $errorMessage;
	    }
	    
	 ?>


 
  <div class="row" id="checkout-forms" style="display:none;">	  
  	  <div class="cell twoColumns"></div>  	  
	  	<div class="cell eightColumns" id="checkout-form">
	  	<div id="transition">
		  	<div style="position: relative;width: 100%;top: 25%;">
			  	<img src="images/ajax-loader.gif">
				  	<br>Saving information...
			  	</div>
			 </div>
	<div id="shipping-container" class="no-display">
		
	<form id="shipping-info" name="shippingInfo">
		  	
	<span style="display:none" id="form-status"></span>
	
		<div class="row">
		<div class="cell sixteenColumns formtext border-bottom">
		<h3 class="inline">
			<? 
				$header = "Shipping Information";
				
				if($_SESSION['shipmethod'] == 5){
					$header = "Billing Information";
				}
				
				echo $header;
			?>	 
			<span class="inline caption">Fields marked with <span class="asterik">*</span> are required.</span>
		</h3>
		</div>
		</div>
		<div class="row">
				<div  class="cell fourColumns formtext">
					<span class="asterik">*</span>Email
				</div>
		<div class="cell twelveColumns" class="formtext">
			<input id="email" class="validate medium-input" name="cardemail" validation-rule="email" err-msg="Please enter a valid email address." err-msg-target="self">
			<span id="email-validate"></span>
			
		</div>
		</div>
		<div class="row">
			<div class="cell sixteenColumns formtext">
				<span class="caption">Your email address will only be used to send you your order confirmation</span>
			</div>
		</div>
		<div class="row">
				<div  class="cell fourColumns formtext">
					<span class="asterik">*</span>First Name:
				</div>
				<div class="cell twelveColumns">
					<input class="validate medium-input" id="fname" data-validate="true" name="fname"  err-msg-target="self" validation-rule="required" err-msg="Please enter your first name.">
					<span id="fname-validate"></span>
				</div>
			</div>
		<div class="row">
				<div class="cell fourColumns formtext">
					<span class="asterik">*</span>Last Name:
				</div>
				<div class="cell twelveColumns">
					<input class="validate medium-input" validation-rule="required" err-msg="Please enter your last name." err-msg-target="self" id="lname" name="lname" data-validate="true">
					<span id="lname-validate"></span>
				</div>
			</div>
		<div class="row" id="rowShippingAddress1">
				<div class="cell fourColumns formtext"><span style="color:red">* </span>Address:</div>
					<div class="cell twelveColumns">
				<input class="validate medium-input" id="address1" validation-rule="required" err-msg="Please specify your shipping address." err-msg-target="self" data-validate="true" name="address1">
				 <span id="address1-validate"></span>
				</div>	
		
			</div>
		<div class="row" id="rowShippingAddress2">
				<div class="cell fourColumns formtext">&nbsp;</div>
				<div class="cell twelveColumns">
					<input class="medium-input" id="address2" name="address2" data-validate="false">
				</div>
			</div>
			
		<div class="row" id="rowShippingZip">
				<div class="cell fourColumns formtext">
					<span class="asterik">*</span>Zip Code:
				</div>
				<div class="cell twelveColumns formtext">
					<input class="validate small-input" validation-rule="required" id="zip" name="zip" err-msg="A valid zip code is required." err-msg-target="self" value="<?php echo $_SESSION['zip']; ?>">
					<span id="zip-validate"></span>
				</div>
			</div>
		<div class="row" id="rowShippingCity">
				<div class="cell fourColumns formtext">
					<span class="asterik">*</span>City:
				</div>
				<div class="cell twelveColumns formtext">
					<input class="validate small-input" id="city" name="city" data-validate="true" validation-rule="required" err-msg="Please specify your city." err-msg-target="self"> 
					<span id="city-validate"></span>
				</div>
		</div>
		<div class="row" id="rowShippingState">
			<div class="cell fourColumns"><span class="asterik">*</span>State:</div>
			<div class="cell twelveColumns">


<select validation-rule="required" err-msg="State required." err-msg-target="self" class="validate mini-input" id="state" name="state">
	<option value=""></option>
	<option value="AL">Alabama</option> 
	<option value="AK">Alaska</option> 
	<option value="AZ">Arizona</option> 
	<option value="AR">Arkansas</option> 
	<option value="CA">California</option> 
	<option value="CO">Colorado</option> 
	<option value="CT">Connecticut</option> 
	<option value="DE">Delaware</option> 
	<option value="DC">District Of Columbia</option> 
	<option value="FL">Florida</option> 
	<option value="GA">Georgia</option> 
	<option value="HI">Hawaii</option> 
	<option value="ID">Idaho</option> 
	<option value="IL">Illinois</option> 
	<option value="IN">Indiana</option> 
	<option value="IA">Iowa</option> 
	<option value="KS">Kansas</option> 
	<option value="KY">Kentucky</option> 
	<option value="LA">Louisiana</option> 
	<option value="ME">Maine</option> 
	<option value="MD">Maryland</option> 
	<option value="MA">Massachusetts</option> 
	<option value="MI">Michigan</option> 
	<option value="MN">Minnesota</option> 
	<option value="MS">Mississippi</option> 
	<option value="MO">Missouri</option> 
	<option value="MT">Montana</option> 
	<option value="NE">Nebraska</option> 
	<option value="NV">Nevada</option> 
	<option value="NH">New Hampshire</option> 
	<option value="NJ">New Jersey</option> 
	<option value="NM">New Mexico</option> 
	<option value="NY">New York</option> 
	<option value="NC">North Carolina</option> 
	<option value="ND">North Dakota</option> 
	<option value="OH">Ohio</option> 
	<option value="OK">Oklahoma</option> 
	<option value="OR">Oregon</option> 
	<option value="PA">Pennsylvania</option> 
	<option value="RI">Rhode Island</option> 
	<option value="SC">South Carolina</option> 
	<option value="SD">South Dakota</option> 
	<option value="TN">Tennessee</option> 
	<option value="TX">Texas</option> 
	<option value="UT">Utah</option> 
	<option value="VT">Vermont</option> 
	<option value="VA">Virginia</option> 
	<option value="WA">Washington</option> 
	<option value="WV">West Virginia</option> 
	<option value="WI">Wisconsin</option> 
	<option value="WY">Wyoming</option>
    <option value="-"><i>Canada</i></option>
	<option value="AB">Alberta</option>
	<option value="BC">British Columbia</option>
	<option value="MB">Manitoba</option>
	<option value="NB">New Brunswick</option>
	<option value="NL">Newfoundland and Labrador</option>
	<option value="NS">Nova Scotia</option>
	<option value="NT">Northwest Territories</option>
	<option value="NU">Nunavut</option>
	<option value="ON">Ontario</option>
	<option value="PE">Prince Edward Island</option>
	<option value="QC">Quebec</option>
	<option value="SK">Saskatchewan</option>
	<option value="YT">Yukon</option>

</select>
			
	<span id="state-validate"></span>
			</div>
		</div>
		
		
		<div class="row">
				<div class="cell fourColumns">	
				 	Country: 				
				 </div>
				<div class="cell twelveColumns">
					<select class="small-input" name="country">
						<option value="US">US</option>
						<option value="Canada">Canada</option>
					</select>
				</div>
			</div>
		<div class="row border-top">
				<div class="cell fiveColumns formtext">
					<span class="asterik">*</span>
						Contact Phone:
				</div>
				<div class="cell elevenColumns">
					<input class="validate small-input" validation-rule="required" id="phone" name="phone" err-msg="Please enter a contact phone number.">
					<span id="phone-validate"></span>
				</div>
		</div>
		<div class="row">
			<div class="cell sixteenColumns formtext">
				<span class="caption">In case we need to contact you quickly for any reason regarding your order, we ask that you enter a contact phone number.<br> As You Like It Silver Shop will not share or otherwise use this number.</span>
			</div>
		</div>
		
		<?php 
			
			if( $_SESSION['shipmethod']!=5 ){
			
			echo '<div class="row" id="use-shipping-checkbox-container">
			<div class="cell fourColumns"></div>
			<div class="cell twelveColumns">
				<input type="hidden" name="sameaddress" value="no">
				<input type="checkbox" checked="true" name="sameaddress" value="yes" id="use-shipping-address"> 
		
					<span class="bold" style="font-size:12px" id="billing-address-instructions">
						Use shipping address as billing address	
					</span><br>
					<span class="caption">Uncheck this if you need to specify a different billing address.</span>
					</div>
			</div>';
		
			}
		
		?>

		<div class="row nopad card-address-container" id="card-address-fields">
			<div class="row border-bottom">
				<h3 class="inline">Billing Information 
				<span class="inline caption">Fields marked with <span class="asterik">*</span> are required.</span>
				</h3>
		</div>
			<div class="row">
				<div  class="cell fourColumns formtext">
					<span class="asterik">*</span>First Name:
				</div>
				<div class="cell twelveColumns">
					<input class="validate medium-input" id="billtofname" data-validate="true" name="billtofname"  err-msg-target="self" disabled="true" validation-rule="required" err-msg="Please enter your first name.">
					<span id="billtofname-validate"></span>
				</div>
			</div>
			<div class="row">
				<div class="cell fourColumns formtext">
					<span class="asterik">*</span>Last Name:
				</div>
				<div class="cell twelveColumns">
					<input disabled="true" class="validate medium-input" validation-rule="required" err-msg="Please enter your last name." err-msg-target="self" id="billtolname" name="billtolname" data-validate="true">
					<span id="billtolname-validate"></span>
				</div>
			</div>
			<div class="row">
			<div class="cell fourColumns formtext">
			<span class="asterik">*</span>Address:
			</div>
			<div class="cell twelveColumns">
				<input disabled="true" id="card-address" name="cardaddress" class="medium-input" validation-rule="required" err-msg="Please enter your billing address." err-msg-target="self">
			<span id="card-address-validate"></span>
			</div>

		</div>
			<div class="row">
			<div class="cell fourColumns formtext">
			<span class="asterik">*</span>City:
			</div>
			<div class="cell twelveColumns formtext">
				<input disabled="true" id="card-city" class="small-input" name="cardcity" validation-rule="required" err-msg="Please enter your billing city." err-msg-target="self">
				<span id="card-city-validate"></span>
			</div> 

		</div>
			<div class="row">
			<div class="cell fourColumns">
			<span class="asterik">*</span>State:
			</div>
			<div class="cell twelveColumns">
			<select disabled="true" validation-rule="required" err-msg="Billing state required." err-msg-target="self" class="mini-input" id="card-state" name="cardstate">
			
	<option value="">State(required)</option>
	<option value="AL">Alabama</option> 
	<option value="AK">Alaska</option> 
	<option value="AZ">Arizona</option> 
	<option value="AR">Arkansas</option> 
	<option value="CA">California</option> 
	<option value="CO">Colorado</option> 
	<option value="CT">Connecticut</option> 
	<option value="DE">Delaware</option> 
	<option value="DC">District Of Columbia</option> 
	<option value="FL">Florida</option> 
	<option value="GA">Georgia</option> 
	<option value="HI">Hawaii</option> 
	<option value="ID">Idaho</option> 
	<option value="IL">Illinois</option> 
	<option value="IN">Indiana</option> 
	<option value="IA">Iowa</option> 
	<option value="KS">Kansas</option> 
	<option value="KY">Kentucky</option> 
	<option value="LA">Louisiana</option> 
	<option value="ME">Maine</option> 
	<option value="MD">Maryland</option> 
	<option value="MA">Massachusetts</option> 
	<option value="MI">Michigan</option> 
	<option value="MN">Minnesota</option> 
	<option value="MS">Mississippi</option> 
	<option value="MO">Missouri</option> 
	<option value="MT">Montana</option> 
	<option value="NE">Nebraska</option> 
	<option value="NV">Nevada</option> 
	<option value="NH">New Hampshire</option> 
	<option value="NJ">New Jersey</option> 
	<option value="NM">New Mexico</option> 
	<option value="NY">New York</option> 
	<option value="NC">North Carolina</option> 
	<option value="ND">North Dakota</option> 
	<option value="OH">Ohio</option> 
	<option value="OK">Oklahoma</option> 
	<option value="OR">Oregon</option> 
	<option value="PA">Pennsylvania</option> 
	<option value="RI">Rhode Island</option> 
	<option value="SC">South Carolina</option> 
	<option value="SD">South Dakota</option> 
	<option value="TN">Tennessee</option> 
	<option value="TX">Texas</option> 
	<option value="UT">Utah</option> 
	<option value="VT">Vermont</option> 
	<option value="VA">Virginia</option> 
	<option value="WA">Washington</option> 
	<option value="WV">West Virginia</option> 
	<option value="WI">Wisconsin</option> 
	<option value="WY">Wyoming</option>
    <option value="-"><i>Canada</i></option>
	<option value="AB">Alberta</option>
	<option value="BC">British Columbia</option>
	<option value="MB">Manitoba</option>
	<option value="NB">New Brunswick</option>
	<option value="NL">Newfoundland and Labrador</option>
	<option value="NS">Nova Scotia</option>
	<option value="NT">Northwest Territories</option>
	<option value="NU">Nunavut</option>
	<option value="ON">Ontario</option>
	<option value="PE">Prince Edward Island</option>
	<option value="QC">Quebec</option>
	<option value="SK">Saskatchewan</option>
	<option value="YT">Yukon</option>

</select>
		<span id="card-state-validate"></span>
			</div>
		</div>
			<div class="row">
			<div class="cell fourColumns formtext"><span class="asterik">*</span>Zip Code:</div>
			<div class="cell twelveColumns formtext">
				<input disabled="true" id="card-zip" class="small-input" name="cardzip" validation-rule="required" err-msg="Billing zipcode required" err-msg-target="self">
				<span id="card-zip-validate"></span>
			</div>
		</div>
			<div class="row">
		<div class="cell fourColumns">
				Country:
		</div>
		<div class="cell twelveColumns">
			 <select id="card-country" class="small-input" name="cardcountry">
				<option value="US">US</option>
				<option value="Canada">Canada</option>
			</select>
		</div>
		</div>
			<div class="row">
			<div class="cell fourColumns">Phone:</div>
			<div class="cell twelveColumns formtext">
				<input err-msg="Please enter your telephone number." id="card-phone" class="small-input" name="cardphone" err-msg-target="self">
				<span id="card-phone-validate"></span>
			</div>
		</div>
		</div>
		
		<div class="row">
		<div class="cell sixteenColumns formtext border-bottom">
			<h3 class="inline">Order Options</h3>
		</div>
		</div>
		<div class="row">
		<div class="cell fourColumns formtext">Gift wrap,<br> free of charge.</div>
		<div class="cell twelveColumns"> 
			<select id="gift-wrap" class="small-input" name="giftwrap">
				<option value="">Choose Below</option>
				<option value="">None</option>
				<option value="standard">Standard</option>
				<option value="Christening">Christening</option>
				<option value="Christmas">Christmas</option>
				<option value="Wedding">Wedding</option>
			</select>
		</div>
	</div>
		<div class="row">
				<div class="cell fourColumns formtext">Include a card<br>with this message: </div>
				<div class="cell twelveColumns">
					<textarea name="note" rows=4 class="medium-input" id="card-note"></textarea>
				</div>
			</div>
		<div class="row">
		<div class="cell sixteenColumns formtext border-bottom">
		</div>
		</div>
		<div class="row">
				<div class="cell sixteenColumns centered">
					<button type="button" id="save-shipping">Next Step &rarr; Payment Information</button>
				</div>
		</div>

</form>

</div>
	
  <div id="billing-container" class="no-display">	
	  <div class="divOverlay" id="giftCardPayOverlay"><img src="/images/ajax-loader.gif"><br>Processing your order...</div>
	  <div class="row">
	  	<div class="cell sixteenColumns border-bottom"><h3>Review and Submit Payment</h3></div>
	  </div>
	  <div class="row">
		  <div class="cell sixteenColumns">
			  Please review your order at the right.<br>If everything looks good, please fill out the form below to submit payment and process your order.
		</div>
	</div>
	
<div style="padding: 15px;" class="row giftcard-container">

<div id="gift-card-section" style="position: relative; width: 99.5%;">

<div class="cell sixteenColumns" style="border-style: solid; background-color: white; border-color: rgb(204, 204, 204); padding-right: 0px; border-width: 1px;">
<div id="giftcardcontent" style="border-color: rgb(237, 242, 247); border-style: solid; border-width: 1px; height: 100%;">
<div style="border-bottom: 1px solid rgb(238, 238, 238); color: rgb(102, 102, 102); margin: 0px 10px; padding-bottom: 5px; font-family: Arial,Helvetica,sans-serif; padding-top: 8px; font-size: 80%;">
<span style="float: left;margin: 0 5px 5px 0;"><strong>›</strong></span>
<span style="padding: 1px 0 5px 5px;"><strong>Pay with As You Like It Silver Shop Gift Card</strong></span>

</div>
<div style="padding: 10px 0px 8px 10px; font-size: 80%; color: rgb(51, 51, 51);">Gift card code:
<input class="small-input" id="gift-card-code"><span id="gift-card-validate"></span>
</div>
</div>
 </div>
</div>
</div>



<div id="paypal-fields"></div>
<div class="row giftcard-container no-display" id="pay-with-giftcard">
	<div class="cell sixteenColumns center-align">
		<form action="order-confirmation.php" method="post">
			<button type="submit" id="process-giftcard" class="">Process Order</button>
		</form>
	</div>
</div>	  

</div>	


	  
</div>


<div class="cell fiveColumns" id="order-summary"></div>

<div class="row centered">
<div class="cell sixColumns">
<!--<a href="http://www.asyoulikeitsilvershop.com">--><button id="resume-shopping">Resume Shopping</button><!--</a>-->
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

<script type="text/javascript">
<?
		$gcEmails=array();
		$j=0;	
			$hasGiftCard=0;
			$giftCardOnly=1;
			
			$arrItems=explode("&",substr($_COOKIE["items"],1));
			$item_count=count($arrItems);
	
			for($i=0;$i<$item_count;$i++){
				if(substr($arrItems[$i],0,2)=="gc"){
					$hasGiftCard=1;
					$itemData=explode(":", $arrItems[$i]);
					//echo "//checking item $arrItems[$i]";
					
					$gcEmails[$j]=$itemData[2];
					$j++;
				}
				else{
					$giftCardOnly=0;
				}
			}
    if(count($gcEmails)){
	   echo "var giftCardEmails=[];\n\r";
	    for($i=0;$i<count($gcEmails);$i++){
		   echo "giftCardEmails[$i]='$gcEmails[$i]';\n\r";
	    }
    }
    
    echo "var hasGiftCard=$hasGiftCard;\n\r";
	echo "var giftCardOnly=$giftCardOnly;\n\r";
	
?>

	var beenclicked			= false;
	var creditFieldsBound	= false;
	var giftCardBound		= false;
	var haveBeenUnbound		= false;
	var useShipping			= false;
	var globalSessionData	= [];
 	
 	$(document).ready(function(){

	 	 $.main();
	
	 	 console.log(globalSessionData);
	});
 
</script>

<style type="text/css">
	/*#iframeContainer::first-child{
		display: none;
	}*/
	#iframeContainer > .shadowBox {
    	display:none;
	}
	
	#iframeContainer > .shadowBox ~ .shadowBox {
    	display:inline-block;
	}
	
</style>

</html>
<? ob_flush(); ?>