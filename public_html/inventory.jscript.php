<!DOCTYPE html>
<html>
<head>
<title></title><base href="//www.asyoulikeitsilvershop.com">
<link rel="stylesheet" href="/css/Gumby/css/gumby.css">
<link rel="stylesheet" href="ayliss_style_uni.css">

<style type="text/css">
 a.search{
	 text-decoration:underline;
	 font-size: 14px;
 }
 .categoryLinksContainerSub ul.dropdown li a{
	 color: white;
	 font-size:12px;
 
}
.row.categoryLinksContainerSub{
	padding: 0px;
}
.row.otherLinksContainer{
	height: 25px;
	padding: 0px;
}
 p.search{
 	font-size:14px;
	 text-decoration: none;
 }
img.productThumbnail, img.productThumbnail.large{
	 max-width: 250px;
	 max-height: 250px;

 }
.row.black{
	background-color: black;
}
.pageHead .row{
	max-width: 100%;
	width: 100%;
}
.pageHead.tan{
	background-color: #cc9966;
}
H1.siteTitle{
	top:0px;
}

</style>

<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="/js/handlebars-v2.0.0.js"></script>
<script id="thumbnail-template" type="text/x-handlebars-template"></script>

<script id="entry-template" type="text/x-handlebars-template">
  {{#each product}}
  
  {{#if newRow}}
 	 <div class="row">
  {{/if}}
  
  <div class="four columns" style="border:1px solid #aaa;min-height:200px;" id="{{productId}}-{{id}}">
		
		<div class="container sixteen colgrid">
			<div class="row">
				<div class="fifteen columns push_one">
				<a href="/showItem.php?product={{id}}">
					<span style="font-size:.75rem;">
					{{pattern}} by {{brand}}</span>
					<br><span style="font-size:.75rem">{{item}}</span>
				</a>
				</div>
			
			</div>
			<div class="row">
				<div class="fifteen columns push_one">
					{{#if handle}}
					<img src="{{handle}}" alt="{{pattern}} by {{brand}} {{item}} - ${{retail}}" title="{{pattern}} by {{brand}} {{item}} - ${{retail}}">
					{{/if}}
				</div>
			</div>
			<div class="row">
				<div class="eight columns">	
					{{#if image}}
					<img style="margin:5px;border:1px solid #aaa;"src="{{image}}">
					{{/if}}
				</div>
				<div class="seven columns push_one">
				  	<div style="font-size:.75rem">
				  	{{#if dimension}}
				  		{{dimension}}<br>
				  	{{/if}}
				  	
				  	{{#if weight}}
				  		{{weight}}<br>
				  	{{/if}}
				  	</div>
				  	<span style="color:#333">${{retail}}.00</span><br>
				  	In Stock:{{quantity}}
				  	<div class="field">
				  	<button class="small metro btn primary">
				  		<a href='#' class='add-item' data-item="{{productId}}-{{id}}">Add</a>
				  	</button>
				  	</div>
				</div>
			</div>
			<div class="row">
			  	<div class="sixteen columns">
				  	<span style="font-size:.75rem">{{desc}}</span>
			  	</div>
			</div>
		</div>
</div>

{{#if endRow}}
  </div>
{{/if}}

{{/each}}
</script>

<!--<script type="text/javascript" src="/js/mustache/mustache.js"></script>-->

<script type="text/javascript">
	var inventoryArr=[];
	var content='';
	
	$(document).ready(function(){
	  $.get("/templates/searchResults.tmp",function(response){
		   var source=response;
		   var template = Handlebars.compile(source);

		   $.get('http://www.asyoulikeitsilvershop.com/inventory.json.php',
		  		'pattern=chantilly&limit=100',
		  		function(response){
		  		// console.log(response);				
							 		
					inventoryArr=JSON.parse(response);
				
					var html=template(inventoryArr);
				
					$('#results').html(html);
				
					console.log(inventoryArr);	
					console.log(JSON.parse(response));
				
			}
			);
	  	}
	  );
	 
	
	});
	
	$.ucwords=function(lcword){
	var word='';
	var c=0;
	console.log(lcword);
	if(lcword){
	lcword=lcword.toLowerCase(lcword);
	  for(c=0;c<lcword.length;c++){
	    l=lcword.charAt(c);
		if(c==0){l=l.toUpperCase();}
		else{
		 //prel=
			if(lcword.charAt(c-1)==' '){
			 l=l.toUpperCase();	
			}
		}  
	   word=word+l;
	  }
	 }
	  return word;	
	};
	
	$.showInventory=function(arr,template){
		var fieldArray=['id','productId','pattern','brand','handle','image','item','desc','retail','dimension','quantity','retail','weight'];
	
		var content='';
		var strFind='';
		var strReplace='';
		var colindex=0;
		var i=0;
		var j=0;
		var row='';
		for(i=0;i<arr.length;i++){
		  
		  curitem=template;
		    for(j=0;j<fieldArray.length;j++){
		    uword='';
		      strFind='{{'+fieldArray[j]+'}}';
		     
		      strReplace=arr[i][fieldArray[j]];
		       if(fieldArray[j]=='retail'){
			          strReplace='$' +strReplace;
		       }
		       if(fieldArray[j]!='image' && fieldArray[j]!='handle'){
		       	strReplace=$.ucwords(strReplace);
			   }
			    curitem=curitem.replace(strFind,strReplace);
			  } 
	
			row=row+curitem;
		   colindex++;    
		
		  if(colindex%4==0){
		  	row='<div class="row">'+row.concat('</div>');
		  	content+=row;
		  	colindex=0;
		  	row='';
		  }
		  
		  //console.log(colindex+':'+i);
		  
		  
		}
	  
	  if(colindex%4!=0){
	  		row='<div class="row">'+row.concat('</div>');
		  	content+=row;
	}
	  
	  $('#results').html(content);
	
	};
	
</script>

</head>

<body>
	
<div class="container sixteen colgrid nopad pageHead tan">
	<div class="row nopad">
	<div class="two columns"></div>
		<h1 class="twelve columns text-center siteTitle">
			As You Like It Silver Shop
		</h1>
	</div>

	<div class="row fullWidth nopad black otherLinksContainer">
		<? include("otherlinks.responsive.php"); ?>
	</div>
	<? include("categoryMenu.html"); ?>

</div>

<div class="container sixteen colgrid" id="results">
	
</div>
</body>

</html>