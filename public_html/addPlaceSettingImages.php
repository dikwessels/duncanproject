<?php 
ini_set("display_errors",1);

	include_once("/connect/mysql_pdo_connect.php");
?>

<!DOCTYPE html>
<html>
<head>
	<title>
	Add Place Setting Images to Website
	</title>
	
	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>	
	<script src="/css/Gumby/js/libs/modernizr-2.6.2.min.js"></script>
	<link rel="stylesheet" href="/css/Gumby/css/gumby.css">
	<script src="/css/Gumby/js/libs/gumby.min.js"></script>
	
	
	<!--<link rel="stylesheet" href="ayliss_style_uni.css">-->
	<script type="text/javascript">
	var ids=[];
	var optionnid="";
	var lastpattern="";
	var curpattern="";
	
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
		 
		 $('#pattern').on('change',function(){
		   curpattern=$(this).val();
		   if(curpattern!=lastpattern){
		    lastpattern=curpattern;
		    $.updateImageContainers(1);
		    
		   }
		 });
		 
		 $('#pattern').on('blur',function(){
		 	curpattern=$(this).val();
		    if(curpattern!=lastpattern){
		    lastpattern=curpattern;
			 $.updateImageContainers(1);
			 }
		 });
		 
		 $('#pattern').on('click',function(){
		  curpattern=$(this).val();
		   if(curpattern!=lastpattern){
		   lastpattern=curpattern;
			 $.updateImageContainers();
			 }
		 });
		 
		 $('#pattern').on('keydown',
		 	function(event){
			 if(event.which === 38 || event.which === 40){
			// $(this).trigger('click');
			 }  	 
			}
		 );
		 
		 var dropDiv=$('.image-drop');
		 
		 $('.image-drop').each(function(){
				 $(this).on('drop',function(event){
					 var files=event.originalEvent.dataTransfer.files;
					 if(files[0].type=='image/jpeg'){
					   if($(this).data('setting')!=''){
					    var settingid=$(this).data('setting');
					    
					    console.log(settingid);
						$.uploadData(files,settingid);
					 	}
					 	else{
						 	alert ('Please select a pattern from the list');
					 	}
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
		 
		 $.updateImageContainers=function(i){
			//var i=0;
		    var imagecontent="";
		    var imagesrc="";
		    var d=new Date();
		    //console.log('current pattern: ' + curpattern);
		    //console.log('last pattern: ' + lastpattern);
		    
		    optionid=$('#pattern option:selected').attr('id');
		   
		    ids[0]=$('#pattern option:selected').data('2');
		    ids[1]=$('#pattern option:selected').data('3');
		    ids[2]=$('#pattern option:selected').data('1');
		    
			if(i<4){
				$('#drop-zone'+i).attr('data-setting',ids[i-1]);
				$('.result-status-'+i).html('<div class="status" id="status-'+ids[i-1]+'"></div><div id="result-'+ids[i-1]+'" ></div>');
				
				imagesrc=$('#pattern option:selected').data('image'+ids[i-1]);
				console.log('image id:'+ids[i-1]);
				console.log('image id ' + ids[i-1]+ ' source: ' +imagesrc);
				if(imagesrc){
				  imagesrc=imagesrc.replace('.jpg','_SM.jpg');
				  imagesrc+='?'+d.getTime();
				  imagecontent='<p>Current Image</p>';
				  imagecontent+='<img class="currentImage" src="/productImages/_SM/'+imagesrc+'">';
				}
				else{
					imagecontent='<p>No Image</p>';
				}
				$('#result-'+ids[i-1]).fadeOut(0,'swing',
					function(){
						$('#result-'+ids[i-1]).html(imagecontent);
						//$('#result-'+ids[i-1]).parent().attr('data-pattern',patternid);
						//$('#result-'+ids[i-1]).fadeIn(200,'swing',
						//	function(){
								i++;
								$.updateImageContainers(i);
						//	});
				});	
			}
			else{
				for(i=1;i<4;i++){
					$('#result-'+ids[i-1]).fadeIn(1000);
				}
			}
		 };
		 
		 $.uploadData=function(files,id){
		 	var i;
		 	var formdata=new FormData();
		 	var imageArr=[];
		 	var imgsrc='';
		 	console.log('item id: '+id);
		     formdata.append('imagenumber',"");
			 formdata.append('id',id);
			 for(i=0;i<files.length;i++){
				formdata.append('userfile',files[i]); 
			 }
			 $('#status-'+id).html('Uploading image...');
			 
			 $.ajax({
				 url:'http://www.asyoulikeitsilvershop.com/testImageUpload.php',
				 type:'POST',
				 data:formdata,
				 processData:false,
				 contentType:false,
				 success:function(response){
				 imageArr=JSON.parse(response);
				   		$('#status-'+id).html('');
			            console.log('updating option ' +optionid+'...');
			            imgsrc=imageArr.mainImage.replace('/productImages/_BG/', '');
			            imgsrc=imgsrc.replace('..','');
				   		$('#'+optionid).attr('data-image'+id,imgsrc);
				   		$.updateImageContainers(1);
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
		.currentImage{
			border: 1px solid #aaa;
			max-width: 150px;
			width: 150px;
			
		}
		.nopad{
			
			padding: 0%;
		}
		.pagehead{
			background-color: #a27178;
		}
		H1.siteTitle, H2.siteTagLine {
			color: white;
			font-family: "CardoRegular","Times",serif;
			font-size: 3em;
			font-variant: small-caps;
			font-weight: normal;
			position: relative;
			text-shadow: 0 2px 4px black;
		}
	</style>
</head>
<body>
<div class="container sixteen colgrid nopad pagehead">
<div class="row">
	<div class="two columns">
	</div>
	<h1 class="twelve columns text-center siteTitle">As You Like It Silver Shop</h1>
	
</div>
</div>
<div class="container sixteen colgrid">

<div class="row">
	<div class="sixteen columns">
	<h6><strong>Add Place Setting Photos to Website</strong></h6>
	</div>
</div>
<div class="row">
	<div class="thirteen columns push_three">
		<p>Select a pattern from the list below, and then drag the associated photo for that pattern's place setting into one of the three boxes below. The page will also show you any current images uploaded for a place setting after you select a pattern. <strong>Only .jpg files can be used.</strong>
			
		</p>
	
	</div>
	
</div>

<div class="row">
	<label for="pattern-div" class="three columns"></label>
	<div class="thirteen columns field" id="pattern-div">
	<div class="picker">
			<select id="pattern">
			<option id="0" data-0="0" data-image0="">Please select a pattern</option>
			<?
			 $optionid="1";
			  $query=$db->query("SELECT DISTINCT pattern, brand from inventory WHERE category='ps' and pattern<>'' and brand<> '' ORDER BY pattern ASC");
			  $result=$query->fetchAll();
			  foreach($result as $row){
			  	extract($row); 
			    
			    $subquery=$db->query("SELECT id,image FROM inventory where category='ps' and pattern='$pattern' and brand='$brand' ORDER BY item ASC");
				 
				 $subresult=$subquery->fetchAll();
				 $optiondata="";
				 
				 for($i=0;$i<count($subresult);$i++){
					extract($subresult[$i]);
					$dataimage="";
					//if($image){$dataimage=$image;}
					$j=$i+1;
					$optiondata.=" data-$j='$id' data-image$id='$image'";
				 }
				 
				 $fullpattern=ucwords(strtolower($pattern))." by ".ucwords(strtolower($brand));			
				//str_replace(, , )				 
				 $optionlist.="<option id='$optionid' value='$fullpattern' $optiondata>$fullpattern</option>";
			  
			    $optionid++;
			  }
			  echo $optionlist;

			?>
			</select>
	</div>
	</div>

</div>
<div class="row">
	
	<div class="four columns push_one centeralign">
		Lunch/Place Setting
		<div id="drop-zone1" data-pattern="" class="image-drop" data-setting="2" data-productid="" data-status="status-1" style="">
		<i class="icon-picture"></i></div>
		<div class="result-status-1"></div>
	</div>
	
	<div class="four columns push_one">
		Place Size Setting
		<div id="drop-zone2" data-pattern="" class="image-drop" data-setting="3" data-productid="" data-status="status-2" style="">
		<i class="icon-picture"></i>
		</div>
		<div class="result-status-2"></div>
	</div>
	
	<div class="four columns push_one">
		Dinner Setting
		<div id="drop-zone3" data-pattern="" class="image-drop" data-setting="1" data-productid="" data-status="status-3" style="">
		<i class="icon-picture"></i>
		</div>
		<div class="result-status-3"></div>
	</div>
</div>
</div>

</body>
</html>