<!DOCTYPE html>
<html>
<head>
	<title>Inventory Image Sorting</title>
	
	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>	
	<script src="/js/libs/modernizr-2.6.2.min.js"></script>
	<script src="/js/libs/gumby.min.js"></script>
	<script src="/js/libs/ui/gumby.checkbox.js"></script>
	<script src="/js/libs/ui/gumby.radiobtn.js"></script>
	<script src="/js/libs/ui/gumby.fixed.js"></script>
	<script src="/js/libs/ui/gumby.toggleswitch.js"></script>
	<script src="/js/handlebars-v2.0.0.js"></script>	
	
	<link rel="stylesheet" href="/css/Gumby/css/gumby.css">
	
		<script id="entry-template" type="text/x-handlebars-template">
	{{#results}}
		{{#if item}}
		<div class="row" id="{{id}}" style="padding-bottom:10px">
			
		<div class="sixteen colgrid">
			<div class="row">
				<h3 class="sixteen columns" style="text-align:center">{{fullItemName}} - ${{retail}}</h3>		
			</div>	
		
			<div class="row" data-productid="{{id}}">
				{{#each imageArray}}
				
				<div id="div{{imageRowID}}-{{imageIndex}}" data-image="{{file}}" data-index="{{imageIndex}}" class="draggable droppable {{columns}} columns">
					<img id="image{{imageRowID}}-{{imageIndex}}"  src="/productImages/_BG/{{file}}">
					<br>
					<span id="span{{imageRowID}}-{{imageIndex}}">{{file}}</span>
				</div>
				
				{{/each}}
			</div>
		
		{{/if}}
	
		{{#if noresults}}
			<div class="row">
				<div class='sixteen columns'>Sorry, no results were found for '{{word}}', idiot.</div>
			</div>
		{{/if}}
		
		</div>
		
		</div>
	{{/results}}
	</script>
	
	<style type="text/css">
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
		img{
			max-height:250px;
		}
		.columns{
			text-align: center;
		}
		
	</style>
	<script type="text/javascript">
		
		var draggedImage='';
		var dragImageID='';
		var dropImageID='';
		var dragID='';
	    
	    var itemIDS=[];
		
		var curImage='';
		
		var oldval='';
		
		var html='';
		var inventoryArr=[];
		var justSearched=false;
		var oldval='';
		var source='';
		var template='';
		
		$.bindDrags=function(){
		 	$('.draggable').each(function(){
				 $(this).on('drag',function(){
					 //draggedImage="";
					 dragImageID=$(this).children(0).attr('id');
					 if(!itemIDS[dragImageID]){
					 	draggedImage=$(this).data('image');
					 	itemIDS[dragImageID]=draggedImage;
					 }
					 else{
						 draggedImage=itemIDS[dragImageID];
					 }
					 
					 dragSpanID=dragImageID.replace('image','span');
					 dragID=$(this).attr('id');
					 console.log('Dragging ' + draggedImage);
				 });
			 
		 });
		 
		 };
		 
		$.bindDrops=function(){
			
		 	$('.droppable').each(function(){
		 			
				 $(this).on('drop',function(event){
					 /**/
					 
					  dropImageID=$(this).children(0).attr('id');	
						if(!itemIDS[dropImageID]){
						 	curImage=$(this).data('image');
						 	//itemIDS[dropImageID]=curImage;
						}
						else{
							curImage=itemIDS[dropImageID];
						}
					 
					 if(curImage!=draggedImage){
						 var productID=$(this).parent().data('productid');
						 console.log(productID);
						 
						 console.log('image being replaced '+curImage);
						 itemIDS[dropImageID]=draggedImage;
						 console.log('new image:' +itemIDS[dropImageID]);
						 itemIDS[dragImageID]=curImage;
						 
						 dropSpanID=dropImageID.replace('image','span');
					     //$dragParent=$('#'+dragImageID).parent();
					     
					     //console.log('Target id:'+dropImageID);
					     //console.log('Target current image:'+curImage);
					     
					     $(this).attr('data-image',draggedImage); 
						 $('#'+dropImageID).attr('src','/productImages/_BG/'+draggedImage);
						 $('#'+dropSpanID).html(draggedImage);
						 
						 $('#'+dragImageID).attr('src','/productImages/_BG/'+curImage);
						 $('#'+dragID).attr('data-image',curImage);
						 $('#'+dragSpanID).html(curImage);
						 
						 //console.log('Dropped:'+draggedImage);
						 //console.log('Swapped:'+curImage);
						 draggedImage="";
						 curImage="";
						 
						 //console.log('Dropped Index:'+ $(this).data('index')+":"+ $(this).data('image'));
						 
						 //console.log('Dragged Index:'+ $dragParent.data('index')+":"+ $dragParent.data('image'));
						 
						 
						 var jsonArray=[];
						 var i=0;
						
						 	$(this).parent().children().each(function(){
							 console.log($(this).attr('id'));
							 jsonArray[i]=$(this).attr('data-image');
							 i++;
							}
						);
						
						//console.log(jsonArray);
						console.log(JSON.stringify(jsonArray));
						$.updateRecord(productID,jsonArray);
						
					 }
					 else{
						 console.log('same image');
					 }
					 console.log(itemIDS);
					});	
				 
				 $(this).on('dragover',function(event){
					// console.log('I was dropped on'+$(this).data('image'));
					// $(this).addClass('active');
					$.stopEvent(event);
				 });
				 
				 $(this).on('dragleave',function(event){
					 //$(this).removeClass('active');
					 $.stopEvent(event); 
				 });


		 });
		 };
		
		$.searchMain=function(){
		 // console.log('searchMain called');
			var sc=$('.search').val();
			var word=$('#search').val();
			
				//$('#overlay').fadeIn(400,function(){
					$.ajax({
						type:'GET',
						url:'pregTest.php',
						data:'s='+word,
						success:function(response){
							inventoryArr=JSON.parse(response);
							console.log(inventoryArr);
			
							html=template(inventoryArr);
							
							$('#searchResults').html(html);
							$.bindDrags();
							$.bindDrops();
							itemIDS=[];
							$('#overlay').fadeOut();
						}
					});
				};
		
		$.updateRecord=function(productID,jsonArray){
		
		 $.ajax({
				url:"testJSONPost.php",
				data:"id="+productID+"&json="+JSON.stringify(jsonArray),
				success:function(result){
					console.log(result);
								//$('#'+productID).html(result);	
								}
				});
		 };
		 
		$.uploadData=function(files,imagenumber){
		 	var i;
		 	var formdata=new FormData();

		     formdata.append('imagenumber',imagenumber);

			 for(i=0;i<files.length;i++){
				formdata.append('userfile',files[i]); 
			 }
			 
			 $.ajax({
				 url:'http://www.asyoulikeitsilvershop.com/testImageUpload.php',
				 type:'POST',
				 data:formdata,
				 processData:false,
				 contentType:false,
				 success:function(response){
				 		//alert('upload successful');
				 	 var imageArr=JSON.parse(response);
				 	 console.log(imagenumber);
				 	 if(imagenumber==''){imagenumber='1';}
				 	 
				 	 $('#result-'+imagenumber).html('');
				 	 $('#result-'+imagenumber).html('<p>Image Uploaded:</p><img width="100" src="'+imageArr.mainImage+'">');
				 	 $('#status-'+imagenumber).html('');
					  	console.log(response);
					 }
				 
				});
		 };

		$(document).ready(function(){
			
			var dropDiv=$('.image-drop');
			var source=$('#entry-template').html();
		    
		    template=Handlebars.compile(source);
						
			$.bindDrags();
			
			$.bindDrops();
			
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
			
			$('html').on('drop',function(event){
				$.stopEvent(event);	
			});
		 	
			$('html').on('dragover',function(event){
				$.stopEvent(event);
		 	});
		 
			$('html').on('dragleave',function(event){
				$.stopEvent(event);
		 	});
		 	
			
		
			$.stopEvent=function(event){
			 event.preventDefault(); 
			 event.stopPropagation();
		 };
		 	
		});

	</script>
	
</head>
<body>
		
	<div class="container sixteen colgrid header" id="siteTitleContainer">
		<div class="row">
			<h2 class="fourteen columns centered">As You Like It Silver Shop</h2>
			</div>
	</div>

<div class="container sixteen colgrid">
	<div class="row">
<h2 class="sixteen columns">Image Sorting Beta</h2>
</div>
<div class="row">
			<div class="eight columns">
		<div class="append field">
			<input class="xwide text input" id="search" placeholder="Search by item name, product #">
			<div class="medium default pretty btn" id="searchButton"><i class="icon-search"></i></div>
		</div>
			</div>
			<div class="field eight columns">
				<label for="mono-yes" class="inline checkbox">
				<input type="checkbox" id="mono-yes" value="1" name="checkbox[]">
				<span></span>&nbsp;Show In Stock Items Only&nbsp;&nbsp;
			</label>
			</div>
		
</div>

<div class="row">
	<article class="sixteen columns">
		Drag images in order you would like them to appear.
	</article>
</div>

</div>

<div class="container sixteen colgrid" id="searchResults">

<?php
	
	ini_set("display_errors",1);
	include("/connect/mysql_pdo_connect.php");

 $column=array('one',
 			   'two',
 			   'three',
 				'four',
 				'five',
 				'six',
 				'seven',
 				'eight',
 				'nine',
 				'ten',
 				'eleven',
 				'twelve',
 				'thirteen',
 				'fourteen',
 				'fifteen',
 				'sixteen');
 				
	$query=$db->query("SELECT * FROM inventory WHERE images <> ''  and image2<>'' and quantity<>0 limit 10");	

	$query->execute();

	$result=$query->fetchAll();

	foreach($result as $row){
		extract($row);
		$itemName=trim(ucwords(strtolower("$pattern $brand $item - $retail")));
		$content="
		<div class='row' id='$id'>
			<div class='sixteen colgrid'>
		
		<div class='row'>
			<h3 class='sixteen columns'>$itemName</h3>
		</div>
		
		<div class='row' data-productid='$id'>
		";
		
		$imageArr=json_decode($images);

		$columns=floor(16/count($imageArr))-1;
		$j=1;
		foreach($imageArr as $v){
			if(file_exists("/home/asyoulik/public_html/productImages/_BG/".$v)){
				$content.="<div id='div$id-$j' data-image='$v' data-index='$j' class='draggable droppable $column[$columns] columns'>
						<img id='image$id-$j'  src='/productImages/_BG/$v'><br>
						<span id='span$id-$j'>$v</span>
					  </div>";
			}
		$j++;
		}

		$content.="
		</div>
		</div>
		
		</div>";
		echo $content;
	}

?>
	</div>
</body>
</html>