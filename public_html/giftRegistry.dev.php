<?php
	extract($_POST);
	extract($_GET);
	//ini_set("display_errors","1");
	
	include("/connect/mysql_connect.php");
	ob_start();
?>

<!DOCTYPE html>
<html>
<head>
<title>Bridal & Gift Registry <? if($_GET['action']=="newreg"){echo " Create Your Registry "; } ?> | As You Like It Silver Shop, New Orleans, Louisiana</title>	

<base href="//localhost:8888">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	
<meta charset="UTF-8" /><meta name="description" content="As You Like It Silver Shop in New Orleans Louisiana specializes in silver flatware and holloware in active, inactive and obsolete patterns, gifts for weddings, birthdays, corporate events and special occasions."/>
<meta name="keywords" content="gift registry, silver gift registry, silver gifts, wedding registry, wedding presents, silver wedding presents, wedding gifts, silver wedding gifts, birthday presents, birthday gifts, silver birthday gifts, corporate presents, corporate gifts, silver corporate gifts,  selling your silver, purchasing information, sterling silver, sterling flatware, silver flatware, antique silver" />

<!--ogTags-->

<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>	
<script type="text/javascript" src="/js/libs/modernizr-2.6.2.min.js"></script>
<script type="text/javascript" src="/js/libs/gumby.min.js"></script>
<script type="text/javascript" src="/js/handlebars-v2.0.0.js"></script>
<!--<script type="text/javascript" src="/js/giftRegSEO.js"></script>-->


<link rel="stylesheet" type="text/css" href="/css/Gumby/css/gumby.css">
<link rel="stylesheet" type="text/css" href="/css/categories.css">	

<style type="text/css">
	.browse{
		cursor: pointer;
	}
</style>

<? include("/home/asyoulik/public_html/js/analytics.html"); ?>

</head>

<body class="default">
<div id="modal1" class="modal">
		<div class="content">
			<a gumby-trigger="|#modal1" class="close switch"><i title=".icon-cancel" class="icon-cancel"></i></a>
			<div class="row">
				<div class="ten columns centered">
					<h2>Add to Cart</h2>
					<p id="addItemConfirm"></p>
				</div>
			</div>
			<div class="row">
				<div class="ten columns centered">
					<div class="container sixteen colgrid">
						<div class="row">
							<div class="eight columns btn medium default">
								<a gumby-trigger="|#modal1" class="switch active" href="#">Continue Shopping</a>
							</div>
        
							<div class="eight columns btn medium default">
								<a href="https://www.asyoulikeitsilvershop.com/sandbox.shoppingCart.php">Checkout →</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
</div>
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
			<a class="toggle" gumby-trigger="#nav-main" id="toggle-nav">
				<i class="icon-menu"></i>
			</a>
		<div class="nine columns">

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
		<div class="six columns right" id="searchContainer">
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

<nav class="container sixteen colgrid desktopOnly navbar categoryMenu" id="categoryNav">
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

<section class="container sixteen colgrid mainContent">
	
	<div class="row fullWidth">
		<h1>Bridal & Gift Registry</h1>
	</div>
	
	<div class="row fullWidth">
		<div class="sixteen columns" id="subContent"></div>
	</div>

</section>

<footer class="container sixteen colgrid">
	 <div class="row fullWidth footer">
		 <div class="sixteen columns center">
		 	<?
		 		$c=include("copyright.php");
		 		echo $c;
 			?>
		 </div>
	 </div>
</footer>	


<!-- end container -->

</body>

</html>
<script type="text/javascript" src="/js/giftRegistryFunctions-min.js"></script>
<!--<script type="text/javascript">
	
    var searchForm='';
    var loginForm='';
    var splashForm='';
    var linksBound=false;
    var formBound=false;
    var html='';
	var registryTemplate='';
	var registryItemTemplate='';
	var responseArray=[];
	var s='';
	var fn='';
	var ln='';
	var sm='';
	var sy='';
	var regID='';
	var i;
	var registryList='';
	var giftRegList ='';
	var giftRegItems = '';
	var sortArray=[];

	
	$.additem=function(itemID){
		var regID = $('#regID').val();
		var quantity = $('#itemID').val();
		
		var params = 'id='+itemID +'&regID='+regID+'&quantity='+quantity;
		
		$('#spanConfirmAdd').html('One moment...');
		
		setTimeout(function(){
			$.ajax({
				type:'get',
				url:'addWedRegItem.php',
				data:params,
				
				success:function(result){
					$('#spanConfirmAdd').html('result');	
					}
				});
				}
				, 
				500);
 }
	
 	$.addToCart=function(id){
	 	//window.alert('I was called');
	 	
	 	var itemID=$('#addButton'+id).data('itemid');
	 	var gc=$('#addButton'+id).data('gc');
	 	var regID=$('#addButton'+id).data('regid');
	 	
	 	var addQty=$('#regItem'+itemID).val();
	 	
	 	var params = 'id='+itemID +'&regID='+regID+'&quantity='+addQty;
	 	
	 	var amount=$('#addButton'+id).data('retail');
	 	
	 	
	 	if(gc=='1'){
		 	params=params+'&isGiftCard=1&amount='+amount;
	 	}
	 	
	 	//console.log(params);
	 	$.post('addItem.modal.php',params,function(response){
			$('#addItemConfirm').html(response);
			$('#modal1').toggleClass('active');	
	 	});
	 	
	 	//window.location.href='addItem.dev.php?'+params;
	 	
	 	
	 	
 	};
	
	$.bindAddToCart=function(){
		$('addToCart').bind('click',$.addToCart($(this).attr('id')));	
	};
	
	$.bindSortLinks=function(){
		
		$('.sort').click(function(id){
				//var ind ='';
				var curSort='';
				
				curSort=$(this).data('direction')*-1;
				
				console.log(curSort);
				
				$(this).attr('data-direction',curSort);
			    		
				for(i=0;i<5;i++){
					sortArray[i]=$('#sort'+(i+1)).data('direction');
				}
				
				sortArray[Math.abs(curSort)-1]=curSort;
				console.log(sortArray);
			    console.log('call search script');
			   		
				$.searchRegistry(curSort,'',0);
		});
	
	};
	
	$.bindSplashLinks=function(){
	
		$('.showLogin').click($.showLoginForm);
		$('.browse').click($.showSearchForm);
		 
    };
    
    $.bindFormLinks=function(){
	    //if(!formBound){
		    $('#searchForm').submit(function(event){
			event.preventDefault();
			return false;
		});
		
		$('button').click(function(event){
		 	event.preventDefault();
		});

		$('#search-registry').on('click',function(){
			 var hasData=false;
			 $(".req").each(function(){
				 if(!hasData){
					console.log('checking ' + $(this).attr('id'));
				 	if($(this).val()!='' || $(this).val()>0){
					 	hasData = true;
					 	console.log('hasData: ' + hasData);
				 	}
				 }
				 console.log($(this).attr('id')+ ' data:' + $(this).val());
			 });
				if(hasData){
					console.log('data on form');
					$.searchRegistry();}
					else{
						console.log('no data on form');
						
					}
		});
		
		$('#back').click(function(){
			$('#subContent').fadeOut(400,function(){
				$('#subContent').html(splashForm);
				$.bindSplashLinks();
				$('#subContent').fadeIn();
			});
		});
    };
    
    $.bindViewItemsLinks=function(){
	    $('.viewRegistryItems').on('click', function(){
			//store results in local variable
			var regID=$(this).data('id');
			//registryList=$('#spanRegistryList').html();
			//console.log('view was clicked');
			//$('#spanRegistryList').html('');
			$.showRegistryItems(regID);
		});
    };
    
    $.displayRegistryList=function(content){
	 
	    responseArray=JSON.parse(content);
		template=Handlebars.compile(giftRegList);
		html=template(responseArray);
		
		$('#spanRegistryList').html(html);
				
		for(i=0;i<5;i++){
			$('#sort'+(i+1)).attr('data-direction',sortArray[i]);
		}
		
		$.bindSortLinks();	
		$.bindViewItemsLinks();	
    
    };
    
    $.loadForms= function(showSplash){
	    $.get('giftRegSplash.html',
			function(result){
				splashForm=result;
				showSplash(splashForm);
					
			});	
		
		$.get('giftRegSearchForm.php',
						function(response){
							searchForm=response;
							//console.log('search form loaded');	
						}
		);
		
		$.get('giftRegLoginForm.html',
			function(response){
				loginForm=response;
				//console.log(loginForm);
		});		
    };
    
    $.loggedIn= function(){
	    $.get("checkGiftRegCookie.php",
		    function(result){
			 return result;
		    }
	    );
    }
    
    $.logUser=function(){
	   console.log('i was clicked');
	   
	   var params=$('#loginForm').serialize();
	   	console.log(params);
	 	 
	 	 $.post('giftRegLogin.php',params,function(response){
		 	 console.log('script response:'+response);
				if(response>0){
				  window.location.href="//www.asyoulikeitsilvershop.com/giftRegistryEdit.php"	
				}   
				else{
					$('#message').html('Incorrect username or password entered');
				}
	   	   });
	 };
    
    $.refreshCart=function(){
	
	var msg='Your cart has ';
	var items=' Items';
	var c=unescape(document.cookie);
	var begin=c.indexOf("items=");
	
	if(begin<0){  
		$('#chest').attr('src','/images/silverchest_empty.gif');
		$('#itemCount').html('Your Silver Chest Is Empty');
	}
	else{
	
	var end=c.indexOf(";",begin+6)
	
	if (end<1) {end=c.length;} 
	
	var items=c.substring((	begin+6),end);

	var numItems=items.split("&");
	var n=0;
	var i=1;
	var temp='';
	var msgitems=' Items';
	
		for(i=1;i<(numItems.length);i++) {
			temp=numItems[i].split(":");
			if(temp[0]==0||temp[0].substring(0,2)=='gc'){
			n+=1;
        }
        else{
        	n+=parseInt(temp[1]);
        }
		}
	
	if(n>0){ 
	  if(n==1){msgitems=' Item';}
	  $('#chest').attr('src','/images/silverchest_full.gif');		
	  $('#itemCount').html(msg+n+msgitems);
	 }
	 else{
	  $('#chest').attr('src','/images/silverchest_empty.gif');		
	  $('#itemCount').html('Your silver chest is empty');
	 } 

	
	}
	
};
    
	$.showLoginForm=function(){
		$('#subContent').fadeOut(400,function(){
			if(!loginForm){
				$.get('giftRegLoginForm.html',function(response){
					loginForm=response;
					$('#subContent').html(loginForm);
					$('#subContent').fadeIn();	
					$('#login').click($.logUser);
				});
			}
			else{
				$('#subContent').html(loginForm);
				$('#subContent').fadeIn();	
				$('#login').click($.logUser);
			}
			
			
		});
		
	};
	
	$.showSplash= function(s){
		//console.log('showSplash() called...');
		if(!s){
			//console.log('splash form not loaded locally');
			$.get('giftRegSplash.html',
				function(result){
				splashForm=result;			
			});
		}
		else{
			splashForm=s;
			//console.log('testing persistent binding');
		}		
		$('#subContent').html(splashForm);
		$.bindSplashLinks();
	
	};
	
	$.showSearchForm = function(){
		
		$('#subContent').fadeOut(400,function(){
			if(!searchForm){
				$.get('giftRegSearchForm.php',
					function(result){
						searchForm=result;
				});
			}		
			$('#subContent').html(searchForm);
			$.bindFormLinks();
			$('#subContent').fadeIn();	
		});
	};
	
	$.showRegistryItems=function(regID){
		
		var params="regID="+regID;

		template=Handlebars.compile(giftRegItems);
		
		$.get('searchGiftRegistry.dev.php',params,function(result){
			responseArray=JSON.parse(result);
			
			html=template(responseArray);
			$('#spanRegistryItems').fadeOut(400,function(){
				$('#spanRegistryItems').html(html);
				$('#spanRegistryItems').fadeIn();
				$('.addToCart').on('click',function(){
				
				var itemID=$(this).data('itemid');
					$.addToCart(itemID,regID);
				});
			});
			
		});
	
	};
	
	

	$.searchRegistry=function(s,regID,clearall){
		if(clearall){
			$('#spanStatus').html('');
			$('#spanRegistryItems').html('');
			sy='';
			ln='';
			sm='';
			fn='';
			s='';
		}
        
        var action="";
        var waitAction="";
		var target='';
    	var waitMsg='';
    	var params='';
    	//var template='';
    	
    	if(regID){
    	 	target='#spanRegistryItems';
		 	waitMsg='Retrieving items...';
    	}
    	
		else{
		 	regID='';
			target='#spanStatus';
			waitMsg='Searching...';
		}
	
		$('#spanRegistryItems').html('');
		
		//searching for gift registries
		if(s &&(fn || ln || sm || sy)){
			//sorting link clicked
			params="&rfname="+fn+"&rlname="+ln+"&sMonth="+sm+"&sYear="+sy+"&regID="+regID+"&sort="+s;
		}
		
		else{
		 //form search
		 params = $('#searchForm').serialize();
		 s='';
		 fn=$('#fname').val();
		 ln=$('#lname').val();
		 sm=$('#sMonth').val();
		 sy=$('#sYear').val();
		}
	
	
		$.get('searchGiftRegistry.dev.php',
			params,
			function(result){
				$.displayRegistryList(result);
			}
		);
	};	

	//main code on load
	if($.loggedIn()==1){
			document.location.href= 'http://www.asyoulikeitsilvershop.com/giftRegistryEdit.php';
		}
	else{
			
			$.get('/templates/giftRegSearchTemplate.html',
				function(result){
					giftRegList= result;
					
					$.get('/templates/giftRegistryItems.html',
						function(result){
						giftRegItems=result;
					});

				}
			);
			
						
			//load forms into lcocal variables
			$.loadForms($.showSplash);
			
			$.refreshCart();
		}

</script>-->


