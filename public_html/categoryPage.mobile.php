

<!DOCTYPE html>
<?php  
	ini_set("display_errors",1);
	
	include("/connect/mysql_pdo_connect.php");
	include_once("/home/asyoulik/public_html/staticHTMLFunctions.php");
	include_once("/home/asyoulik/public_html/categoryArrays.php");
	global $category;
	global $db;
	//load document with featured item already displayed, but include code for ajax calls to refresh
	
	function getBrandLinks($cat){

		global $staticcats;
		global $keyCat;
		global $borderClass;
		global $brandContainerIDs;
		global $db;
		
$stmt="SELECT DISTINCT brand FROM inventory WHERE (category='f' or category='sp' or category='fcs' or category='ps') AND (quantity>0 or quantity<0) AND brand <> '' ORDER BY brand";

	$query=$db->prepare($stmt);
	
	$query->execute();
	
	$result=$query->fetchAll();

$i=0;

foreach($result as $row){
	extract($row);
	$bfilelink=createFileName("/home/asyoulik/public_html/",$staticcats[$cat],"",$brand,"");
	
	if(file_exists($bfilelink)){
		$bfilelink=str_replace("/home/asyoulik/public_html","",$bfilelink);
	}	
	else{
		$brand=urlencode($brand);
		$bfilelink="productSearch.php?category=$cat&brand=$brand";
	}
	
	
	$brandName=ucwords(strtolower($row[brand]));
	
	$brandContent="
		<li>
			<h2>
			<a class='sideMenuLink' title='".$brandName." Silver Flatware' href='$bfilelink'>".$brandName."</a>
			</h2>
		</li>";



$brandlist.=$brandContent;

	
}


$brandlist="

<div class='brandContainer' id='$brandContainerIDs[$cat]'>
	<ul class='sideMenuList'>
	<li class='bottomBorder'>
		<h3> $keyCat[$cat] by brand:</h3>
	</li>
$brandlist
</ul>
</div>
";


 return $brandlist;
}

	function getPopularLinks($cat){
//echo "getSideMenu called";
	global $staticcats;
	global $db;
	
	$specialCatArray=array("ps"=>"Place Settings",
							"sp"=>"Serving pieces",
							"fcs"=>"Complete Sets"	
			);

  $stmt="SELECT * FROM sideMenu WHERE category='$cat'";
  //echo $query;
  $query=$db->prepare($stmt);
  $query->execute();
  
  $result=$query->fetchAll();
  
  $sideMenu="<ul class='sideMenuList'>";
  
  $sideMenu.="<li class='bottomBorder'>
  				<a class='sideMenuLink' href='productSearch.php?category=$cat'>Complete List</a>
  			 </li>";
  
  $path="/home/asyoulik/public_html/";
  
  //add place settings, serving pieces and complete sets link
  foreach($specialCatArray as $k=>$v){
	  $staticFileName=createFileName("/home/asyoulik/public_html/","Flatware","","",$v);
	  //echo "$staticFileName<br>";
	  //$sideMenu.="<!--$staticFileName-->";
	  
	  $staticFileName=checkForFile($staticFileName);
	  if($staticFileName!=""){
	   $fileLink=str_replace("/home/asyoulik/public_html","",$staticFileName);
	   
	   }
	   else{
		   $fileLink="https://www.asyoulikeitsilvershop.com/productSearch.php?category=$k&h2=$v";
	   }
	   
	 $sideMenu.="<li>
    			 <h2>
    			  <a class='sideMenuLink' href='	$fileLink' title='$v'>$v</a>
    			 </h2>
    			</li>";
  }
  
  $sideMenu.="<li class='bottomBorder'></li>";
  
  foreach($result as $row){
    extract($row);
    
     $searchTerms=explode("&",$searchText);

	 foreach($searchTerms as $s){
	  
	   $values=explode("=", $s);
	   if($values[0]=="brand"){$brand=$values[1];}
	   if($values[0]=="item"){$item=$values[1];}
	   if($values[0]=="pattern"){$pattern=$values[1];}  	 
	    
	   } //end foreach searchTerms

	 //echo $staticcats[$category];
	$staticFileName=createFileName("/home/asyoulik/public_html/",$staticcats[$category],$pattern,$brand,$text);
	$sideMenu.="<!--$staticFileName-->";
    $staticFileName=checkForFile($staticFileName);
    
    //$staticFileName=getStaticFileName($text,$category);
    
    if($staticFileName!=""){
	    $fileLink=str_replace("/home/asyoulik/public_html","https://www.asyoulikeitsilvershop.com",$staticFileName);
    }
    else{	
      	$searchText=str_replace("&item=", "&searchItem=", $searchText);
      	$fileLink="http://www.asyoulikeitsilvershop.com/productSearch.php?category=$cat&h2=".urlencode($text)."&".$searchText;
    }
    
    $sideMenu.="<li>
    			 <h2>
    			  <a class='sideMenuLink $sideMenuLink[$category]' href='$fileLink' title='$text'>$text</a>
    			 </h2>
    			</li>";
    	  
  }
  
  $sideMenu.="</ul>";

  return $sideMenu;

}

	
?>

<html>
<head>
	
	<title>
		As You Like It Silver Shop: Sterling Silver Flatware, Hollowware, Gifts
	</title>
	
	<base href="//localhost:8888">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	
	<meta charset="UTF-8" />

	<!-- google and bing verification -->
	<meta name="verify-v1" content="+I7SXW9NzrBhIDFSL/HxvjXwpZoydIFiGVozcxN8hxU=" />
	<meta name="msvalidate.01" content="DE85E9FCB2BA8AE4B75B2AF33CB4E5E0" />

	<meta name="description" content="{{flatwareMeta}}" />

	<meta name="keywords" content="silver, sterling silver,flatware, antique, tableware, replacement , silver repair, corporate gifts, wedding gifts, silver identification, cleaning silver" />
	
	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>	
	<script src="/js/libs/modernizr-2.6.2.min.js"></script>
	<script src="/js/libs/gumby.min.js"></script>
	<script src="/js/libs/ui/gumby.fixed.js"></script>
	<script src="/js/libs/ui/gumby.fittext.js"></script>
	<script src="/js/libs/ui/gumby.toggleswitch.js"></script>
	<script src="/js/libs/ui/gumby.shuffle.js"></script>
	<script src="/js/libs/ui/gumby.skiplink.js"></script>
	<script src="/js/handlebars-v2.0.0.js"></script>
	
	<script type="text/javascript" src="/js/lightbox.js"></script>
	
	<link rel="stylesheet" type="text/css" href="/css/Gumby/css/gumby.css">
	<link rel="stylesheet" type="text/css" href="/css/fonts.css">
	<link rel="stylesheet" type="text/css" href="/css/tripadvisor.css">
	<link rel="stylesheet" href="/css/lightbox.css" type="text/css">
	
	<!--<link rel="stylesheet" type="text/css" href="/css/ayliss_custom.css">-->
	<link rel="stylesheet" type="text/css" href="/css/categories.css">	
	
	<script type="text/javascript">
		var source='';
		
		var inventoryArr=[];
		
		var html='';
		
		var template='';
		
		var itemView='';
		
		var listView='';
		var imageData=[];
		//var event;
		//var e = Event;
		
		$.bindChangePictures=function(){
	//alert('binding change');
		$('.changepicture').on('click',function(){
		   var imagesrc=$(this).children(0).attr('src');
		   console.log(imagesrc);
		   $('#mainFeaturedImage').attr('src',imagesrc);
	      });

		};
		
		$.toggleMenu=function(id,event){
					//alert($(this).data('target'));
					$($('#'+id).data('target')).toggleClass('active');
					//console.log($(this).data('target'));
					$('#'+id).children(1).toggleClass('active');
					event.preventDefault();
					
					if($($('#'+id).data('target')).hasClass('active')){
						$('html,body').animate({
							scrollTop: $($('#'+id).data('target')).offset().top
    					}, 500);			
					}
			
			
				/*event.preventDefault();
				console.log($(this).data('target'));
				$(id).toggleClass('active');	*/	
		};
		
		$.showFeaturedItems=function(showAll){
			var params='category=f&time=n';
			
			//template=listView;
			
			if(showAll==0){
				$.get("/templates/featuredItem.html",
			 		function(response){
			 			source=response;
			 			template=Handlebars.compile(source);
			 		}
			 	);
				
				params=params+'&order=rand&limit=1';
			}
			else{
				 $.get("/templates/featuredItemListView.html",
				 	function(response){
				 		source=response;
				 		template=Handlebars.compile(source);
				 	}
				 );
			}
		
			console.log(params);
			
				$.ajax({
						type:'GET',
						url:'inventory.json.php',
						data:params,
						success:function(result){
							inventoryArr=JSON.parse(result);
							html=template(inventoryArr);
							$('#featuredItem').html(html);
							$.bindChangePictures();
						}		
				});
		};
	
		
		$(document).ready(function(){

			$.showFeaturedItems(0);
			 

			//$('.showAllLink').on('click',$.showFeaturedItems(1));
			
			$('.toggleList').click(function(){
				var id=$(this).attr('id');
				
				$($('#'+id).data('target')).toggleClass('active');
					//console.log($(this).data('target'));
					$('#'+id).children(1).toggleClass('active');
					event.preventDefault();
					
					if($($('#'+id).data('target')).hasClass('active')){
						$('html,body').animate({
							scrollTop: $($('#'+id).data('target')).offset().top
    					}, 500);			
					}
			});		
			
														
		});
		
		
		
		
	</script>
	
</head>

<body class="flatware">
	
<div class="container sixteen colgrid pageHead">
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
		
		<div class="three columns"></div>
		
		</div>
	
		<div class="row desktopOnly">
		
		<h2 class="fifteen columns centered center">Estate sterling silver flatware, hollowware, jewelry, baby silver and silver repair services</h2>
	</div>
</div>

<div class="container sixteen colgrid" id="blackMenu">	
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
		<div class="append field">
			<input class="xwide text input" id="search" placeholder="Search by item name">
				<div class="medium default pretty btn" id="searchButton">
					<i class="icon-search"></i>
				</div>
			</div>
			
		</div>
	</div>
</div>

<div class="container sixteen colgrid desktopOnly navbar categoryMenu" id="categoryNav">
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
</div>

<div class="container sixteen colgrid mainContent">
	
	<div class="row fullWidth">
		<h1>{{headerContent}}</h1>
	</div>

	<div class="row fullWidth" gumby-shuffle="only screen and (min-width: 768px)|0-1-2,only screen and (max-width: 767px)|1-0-2">
		

		<div class="three columns sideMenu" id="popularItems">
			
			<a href="#popularItems" class="toggleList scroll mobileOnly quickLink" data-target="#popularItems">
				<h2>Popular Items</h2>
				<i class="icon-down-open"></i>
			</a>
			
				<? 
					$list=getPopularLinks('f');
					echo $list;
				?>
	
		</div>
		
		<div class="ten columns featuredItemContainer" id="featuredItems">
			<div class="container sixteen colgrid" id="featuredItem">

			</div>
		</div>	

		<div class="three columns sideMenu"  id="brandList">
			<a href="#" class="toggleList mobileOnly quickLink" data-target="#brandList">
				<h2>Flatware by Brand:</h2>
				<i class="icon-down-open"></i>
			</a>
				<? $list=getBrandLinks("f");
					echo $list; ?>
		
		</div>

	</div>


</div>
<div class="container sixteen colgrid">
				<div class="row">
					<div class="sixteen columns center">
							<strong class="showAllLink" data-category="f">View the complete list</strong> of As You Like It Silver Shop's featured items in Flatware.
					</div>
				</div>

			</div>
<footer class="container sixteen colgrid">
	<div class="row">
		<div class="eight columns centered center">
			Copyright <? echo date('Y'); ?> As You Like It Silver Shop
		</div>
	</div>
</footer>

</body>