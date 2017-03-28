<!DOCTYPE html>
<html>
<head>
	<title>
	Add Photos to Website
	</title>
	
	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>	
	<link rel="stylesheet" href="/css/Gumby/css/gumby.css">
	<link rel="stylesheet" href="ayliss_style_uni.css">
	<script type="text/javascript">
		$(document).ready(function(){
		 $('html').on('drop',function(event){
			$.stopEvent(event);	
				});
		 $('html').on('dragover',function(event){
			 $.stopEvent(event);
		 });
		 
		 $('html').on('dragleave',function(event){
			 $.stopEvent(event);
		 });
		 
		 var dropDiv=$('.image-drop');
		 
		 $('.image-drop').each(function(){
				 $(this).on('drop',function(event){
					 var files=event.originalEvent.dataTransfer.files;
					 var imagenumber=$(this).data('imagenumber');
					 //console.log(files); 
					 
					 if(files[0].type=='image/jpeg'){
					    var productID=files[0].name;
					    var productID=productID.substr(0,5);
					    var statusID=$(this).data('status');
					    console.log(statusID);
					    console.log(productID);
					  // alert(files.mozFullPath);
					    $.ajax({
					    		type:'GET',
						    	url:'http://www.asyoulikeitsilvershop.com/getItemName.php',
						    	data:'productId='+productID,
						    	success:function(response){
						    	console.log(response);
						    	console.log(statusID);
							    $('#'+statusID).html(response);
							    $.uploadData(files,imagenumber);
						    	}
					    });
					 	
					 	}
					 else{
						 alert ('Images must be .jpg');
					 }
					
					$(this).removeClass('active');	 
				 
				 });	
				 
				 $(this).on('dragover',function(event){
					 $(this).addClass('active');
					 $.stopEvent(event);
				 });
				 
				 $(this).on('dragleave',function(event){
					 $(this).removeClass('active');
					 $.stopEvent(event); 
				 });


		 });

		$.stopEvent=function(event){
			 event.preventDefault(); 
			 event.stopPropagation();
		 };
		 
		 $.uploadData=function(files,imagenumber){
		 	var i;
		 	var formdata=new FormData();

		     formdata.append('imagenumber',imagenumber);

			 for(i=0;i<files.length;i++){
				formdata.append('userfile',files[i]); 
			 }
			 
			 $.ajax({
				 url:'http://www.asyoulikeitsilvershop.com/dragImageUpload.php',
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
		 }
		});
	</script>

	<style type="text/css">
		#drop-zone.active{
			background-color:#c9d8f2;
		}
		
		.image-drop{
			border:3px dashed #aaaadd;
			border-radius:15px;
			height:150px;
			padding-top: 25%;
			text-align: center;
			vertical-align: middle;
			width:150px;
			font-size: 3rem;
			color: #aaaadd;
		
		}
		.status{
			font-size: .8rem;
		}
	</style>
</head>
<body>

<div class="container sixteen colgrid">

<div class="row">
	<div class="sixteen columns">
	<h3>Add Photos to Website</h3>
	<h4>Instructions</h4>
	<p>Drag photo files onto the boxes below to add them to the website. The boxes below will update images 1-3 for a given inventory item. The Main Image will appear in website search results. Images 2 and 3 appear with featured items on the main category pages.</p>
	<p><strong>Important: Only .jpg files can be used. Image file names must begin with the product id of the item you want to update.</strong></p>
	
	</div>
	
</div>
<div class="row">
	<div class="four columns push_one centeralign">
		Main Image
		<div id="drop-zone" class="image-drop" data-imagenumber="" data-status="status-1" style="">
		<i class="icon-picture"></i></div>
		<div class="status" id="status-1"></div>
		<div id="result-1"></div>
	</div>
	<div class="four columns push_one">
		Image 2
		<div id="drop-zone" class="image-drop" data-imagenumber="2" data-status="status-2" style="">
			<i class="icon-picture"></i>
		</div>
		<div class="status"id="status-2"></div>
		<div id="result-2"></div>
	</div>
	<div class="four columns push_one">
		Image 3
		<div id="drop-zone" class="image-drop" data-imagenumber="3" data-status="status-3" style="">
			<i class="icon-picture"></i>
		</div>
		<div class="status" id="status-3"></div>
		<div id="result-3"></div>
	</div>
</div>
</div>

</body>
</html>