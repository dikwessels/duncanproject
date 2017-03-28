<!DOCTYPE html>
<html>
<head>
	<title>
	Hollowware Item Search Categories
	</title>
	
	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>	
	<script src="/js/libs/modernizr-2.6.2.min.js"></script>
	<script src="/js/libs/gumby.min.js"></script>
	<script src="/js/libs/ui/gumby.checkbox.js"></script>
	<script src="/js/libs/ui/gumby.radiobtn.js"></script>
	<script src="/js/libs/ui/gumby.fixed.js"></script>
	<script src="/js/handlebars-v2.0.0.js"></script>
	
	<!--<link rel="stylesheet" href="ayliss_style_uni.css">-->
	<link rel="stylesheet" href="/css/Gumby/css/gumby.css">
	<script id="entry-template" type="text/x-handlebars-template">
	{{#inventoryItem}}
		{{#if item}}
		<div class="row">
		<div class="eight columns"><h5>{{item}}</h5></div>
		<div class="eight columns field">
			<div class="picker">
				<select class="item" data-value="{{searchCategory}}" data-item="{{item}}">";
				<option value="0">No Search Category</option>
				<option value="1">Baskets, Centerpieces, Vases and Epergnes</option>
				<option value="2">Bowls,Compotes &amp; Cake Stands</option>
				<option value="3">Bread &amp; Butter Items</option>
				<option value="4">Candleware</option>
				<option value="5">Gift</option>
				<option value="6">Goblets, Mint Julep Cups &amp; Other Cups</option>
				<option value="7">Jewelry and Personal Accessories</option>
				<option value="8">Napkin Rings</option>
				<option value="9">Picture Frames</option>
				<option value="10">Pitchers &amp; Urns</option>
				<option value="11">Salt &amp; Pepper Items</option>
				<option value="12">Tabletop Items</option>
				<option value="13">Tea and Coffee</option>
				<option value="14">Trays</option>
				<option value="15">Vanity Items</option>
				<option value="16">Wine &amp; Bar Items</option>
				<option value="17">Chafing Dishes</option>
				</select>
			</div>
		</div>
		</div>
		{{/if}}
	
		{{#if noresults}}
			<div class="row">
				<div class='sixteen columns'>Sorry, no results were found for '{{word}}'</div>
		</div>
		{{/if}}
	{{/inventoryItem}}
	</script>
	
	<script type="text/javascript">
		var str = new String;
		
		var html='';
		var inventoryArr=[];
		var justSearched=false;
		var oldval='';
		var source='';
		//var template='';
		var templateArray=[];
		
		$.bindItemCategorySelect=function(){
		
			$('.item').each(function(){
			   
				$(this).val($(this).data('value'));
			
				$(this).on('change',function(){
				
				var item=$(this).data('item');
				var category=$(this).val();
				var params='item='+encodeURIComponent(item)+'&sc='+category;
				var rowID= $(this).parent().parent().parent().attr('id');
				var overlayID='#'+rowID+'overlay';
				
				console.log('called,'+overlayID);
				$('<div class="overlay rowoverlay" id="'+rowID+'overlay"><h4>Updating '+ item + '...</h4></div>').appendTo('#'+rowID);
				$(overlayID).css('height',$('#'+rowID).height());
				$(overlayID).fadeIn(400,function(){
				
				    $.ajax({type:'GET',
							url:'updateSearchCategory.php',
							data:params,
							success:function(result){
							 var msg='<h4>'+item + ' search category updated.</h4>';
							 
							 if(result==0){
									msg='<h4>Sorry, an error occurred, please try again</h4>';
							 }
							
							 $(overlayID).html(msg);
							 
							 setTimeout(function(){
								$(overlayID).fadeOut(400,function(){
									  $(overlayID).remove();
								});
							 }, 2500);
							
							}
					});

				
					
				});
									
				
					
				});
				});
			
			};

		$(document).ready(function(){
			 
			 $.get("/templates/searchCategory.manage.tmp",
			 	function(response){
				 source=response;
				 template=Handlebars.compile(source);
				 }
			 );

	
			$.searchMain();
			$.bindItemCategorySelect();
		
		  	$('.filter').each(function(){
		  	 $(this).on('change',function(){
			  	 console.log('binding...');
		  	 });
		  
			});
			
			$('.search').on('change',function(){
			//console.log('changed');
				$.searchMain();
				//justSearched=false;	
			});
			
			$('#search').on('keypress',function(event){
			
			if(event.keyCode==13){
			 if($(this).val()!=oldval){
			  $.searchMain();
			  oldval=$(this).val();
			  justSearched=true;
			  }

			}
			
			});
			
			$('#search').on('blur',function(){
				var newval=$(this).val();
				if(newval!=oldval||!justSearched){
					$.searchMain();
					justSearched=true;
					oldval=newval;
				}
					
			});
			
			$('#searchButton').on('click',function(){
				$.searchMain();
			});
		
		});
	
		$.searchMain=function(){
		 // console.log('searchMain called');
			var sc=$('.search').val();
			var word=$('#search').val();
			
				$('#overlay').fadeIn(400,function(){
					$.ajax({
						type:'GET',
						url:'searchCategories.json.php',
						data:'d=1&fields=item,searchCategory&category=h&sc='+sc+'&word='+word,
						success:function(response){
							inventoryArr=JSON.parse(response);
						
							//alert(inventoryArr.length);
							html=template(inventoryArr);
							console.log(inventoryArr);
							$('#searchResults').html(html);
							$.bindItemCategorySelect();
							$('#overlay').fadeOut();
						}
					});
				});
		};
		

		
	</script>
	
	<style>

		.overlay{
			display: none;
			color:black;
			opacity: .85;
			-moz-opacity: .85;
			-webkit-opacity: .85;
			filter: alpha(opacity=85);
			background: white;
			padding-top:5%;
			text-align: center;
			position: absolute;
			height: 100%;
			width:100%;
			z-index: 1000;
			left: 0px;
		}
		.overlay.rowoverlay{
			padding-top: 0%;
			max-height:auto;
		}
		.header{
			background-color: #a27178;
			margin-bottom: 10px;
			
		}
		.header h2,.header h3{
			color: white;
		}
		
		div#siteTitleContainer{
			background-image: url("//www.asyoulikeitsilvershop.com/images/window.jpg");
			background-position: center center;
			background-repeat: no-repeat;
			height:auto;
			max-height: auto;
			text-align: center;
			margin:0 auto;
		}
		
		.row{
			padding:10px 0px;
		}
		
	</style>

</head>

<body>
	
	<div class="container sixteen colgrid header" id="siteTitleContainer">
		<div class="row">
			<h2 class="fourteen columns centered">As You Like It Silver Shop</h2>
			</div>
	</div>

	<div class="container sixteen colgrid">

		<div class="row">
			<h3 class="sixteen columns">Update Inventory Search Categories</h3>
		</div>
		<div class="row">
			<div class="eight columns">
		<div class="append field">
			<input class="xwide text input" id="search" placeholder="Search by item name">
			<div class="medium default pretty btn" id="searchButton"><i class="icon-search"></i></div>
		</div>
	</div>
			<div class="eight columns field">
		<div class="picker">
		<select class="search">
			<option value="0">All items</option>
			<option value="1">Items With No Search Category</option>
		</select>
		</div>
	</div>
		</div>
		<div class="row">
			<h5 class="eight columns">Item</h5>
			<h5 class="eight columns">Search Category</h5>
		</div>
		<div class="row" id="resultsRow">
		<div id="overlay" class="overlay"><h2>Searching...</h2></div>
			<div class="container sixteen colgrid" id="searchResults">
				<?php
				//	include($_SERVER['DOCUMENT_ROOT'].'/getHollowwareItems.php');
				?>
			</div>
		</div>
	</div>

</body>

</html>