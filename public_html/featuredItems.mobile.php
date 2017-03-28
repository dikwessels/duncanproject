<?php

ini_set("display_errors","1");
extract($_GET);
extract($_POST);

include("/connect/mysql_pdo_connect.php");
include_once("/home/asyoulik/public_html/categoryArrays.php");
//echo 'hello';

$c=getFeaturedItem($cat,$script);

$script=json_encode($script);

echo $c."<!--contentbreak-->".$script;

function getFeaturedItem($cat,&$javascriptArray){
	global	$showAll;
	global  $id;
	global  $cats;
	global $staticcats;
	global $db;
if($cat=="f"){
	$catClause="category='f' OR category='sp' OR category='ps' OR category='fcs'";
}
else{
	$catClause="category='$cat'";
}
if($showAll==1){
$javascriptArray=array();
//echo "$catClause";
$statement="SELECT * from inventory where ($catClause)  and time='n' and display=1 and quantity!=0 and retail>0 order by retail";

$query=$db->prepare($statement);

$query->execute();
$result=$query->fetchAll();
//echo count($result);
if(count($result)>0){ 
$f="<div class='row tableHead'>
	<div class=' sixteen columns center'>
		<b class='recentadesc'><span class='featuredItemHeader'>Featured Items:</span></b>
	</div>
</div>";
	
	foreach($result as $row){
		extract($row);
		$folder=strtoupper(substr($pattern,0,1));
		if ($sale) {
			$price=$sale; 
			}  
		else {
			$price=$retail;
		}
		
		if (!$image || !file_exists("/home/asyoulik/public_html/productImages/_BG/$image")) { 
			$handle="HANDLES/$folder/".str_replace($re,$rw,strtoupper("$pattern $brand")).".jpg";
			$image=(file_exists($handle))? $handle:'productImages/_TN/noimage_th.jpg'; }  
		 else {
		 	$image='productImages/_TN/'.substr($image,0,-4)."_TN.jpg"; 
		 }	
		 
		if ($pattern && $brand) {
			$title="$pattern by $brand"; 
		}
		else {
			$title="$pattern$brand&nbsp;"; 
			}

if($weight!=0 && $weight && $weight!=='' ){$weight.=" troy ounces";}


$f.="
<div class='row'>	

			<div class=' fourColumns topAlign'>
				<img class='noborder showItem' data-product='$id' src='/$image' width='100'>
			</div>
			
			<div class=' twelveColumns'>
			<!--begin second column -->
			  <div class='row '>
			    <div class='sixteen columns'>
					<strong class='showItem' data-product='$id'>$title</strong>
			    </div>	    
			  </div>
			  
			   <div class='row'>
			   	<!-- begin item name -->
			   	<div class=' eight columns'>
			   		<p class=searchrecent>
	
							<span data-product='$id' class='showItem'><strong>$item</strong></span><br>
							<span id='weight-$id'>$weight</span><br>
							<span id='dimension-$id'>$dimension</span>
					</p>
			   	</div>
			   	
			   	<div class=' threeColumns leftAlign'>	
						<p class=searchrecent>
						<strong>\${$price}</strong>
						</p>
				</div>	
			   	
			   	<div class=' threeColumns topAlign leftAlign'>
					<input class='searchResultAddButton' type='button' value='Add Item' onClick='updateCart($id,1)'>
				</div>
				
				<div class=' twoColumns topAlign leftAlign'>
					<input type='hidden' value='1' name='quantity$id'>
					<img src='/images/silverchest_add.gif' name='chestimage$id' onClick='updateCart($id,1);return false;'>
				</div>
				
			   	<!--end row-->
			   </div>
			   <!--end seven columns -->
			   </div>
			   <!-- end row -->
			   </div>
			   ";

		}
}

}

else{
	$statement=($id)?"SELECT pattern,brand,item,weight,dimension,retail,sale,id,image as image1, image2,image3,`desc` as desc1,desc2,desc3,quantity from inventory WHERE id=$id":"SELECT pattern,brand,item,retail,sale,id,image as image1,image2,image3,`desc` as desc1,desc2,desc3, quantity from inventory where ($catClause) and image IS NOT NULL and `time`='n' and display=1 and quantity!=0 and retail>0 order by rand() limit 1";
  $getid=$id;
 //echo $statement."<br>";
  $query=$db->prepare($statement);
  $query->execute();
  $result=$query->fetchAll();
  if(count($result)>0){
  
  $f="
  <div class='container sixteen colgrid'>
  <div class='row tableHead'>
	<div class='sixteen columns center'>
		<strong><span class='featuredItemHeader'>FEATURED ITEM:</span></strong>
	</div>
	</div>";
  
		$row=$result[0];
		extract($row);
		
		$javascriptArray['image1']=$image1;
		$javascriptArray['desc1']=$desc1;
		$javascriptArray['image2']=$image2;
		$javascriptArray['desc2']=$desc2;
		$javascriptArray['image3']=$image3;
		$javascriptArray['desc3']=$desc3;

		$category=strtolower($category);
		$folder=strtoupper(substr($pattern,0,1));
		$template=($bs!='')?'bs':(($category=='h')?'h':'f');
		$instock=($template=='f')?(($quantity>-1)?$quantity:'16'):'yes';
		
if (!$image1 || !file_exists("/home/asyoulik/public_html/productImages/_BG/$image1")) { 
		$handle="HANDLES/$folder/".str_replace($re,$rw,strtoupper("$pattern $brand")).".jpg";
		$image1=($handle)? $handle:'/productImages/_TN/noimage_th.jpg'; 
		$imageS=(file_exists($handle))?$handle:"/productImages/_SM/noimage.jpg";
		$caption="Sorry, there is no product image available for this item.
					<br>We have included a picture of the pattern to assist you.";
		} 
	else {
		$isize= getImageSize("productImages/_BG/".$image1);
		$imageS="/productImages/_SM/".substr($image1,0,-4)."_SM.jpg";
		$caption="To view an enlarged image, click on the image above.";
	}

if($sale){
	$price=$sale;
}
else{
	$price=$retail; 
}

if($pattern && $brand){ 
	$title="$pattern by $brand"; 
}
else{  
	$title=trim("$pattern$brand"); 
}
$title.=" $item";

$monogram=($monogram)?"(monogrammed)":'';

	if($image1!=''){
		$imageB1="/productImages/_BG/$image1";
		$imageS1="/productImages/_SM/".substr($image1,0,-4)."_SM.jpg";
		if(!$getid){
			if($desc1){$LBcaption=$title."<br>".$desc1;}
		}
		else{
			if($description){$LBcaption=$title."<br>".$description;}
		
	
	}
	if($image2!=''){
		$imageB2="/productImages/_BG/$image2";
		$imageS2="/productImages/_SM/".substr($image2,0,-4)."_SM.jpg";
		if(!$getid){
			if($desc2){$LBcaption2=$title."<br>".$desc2;}
		}
		else{
			if($description){$LBcaption2=$title."<br>".$description;}
		}
	}
	
	if($image3!=''){
		$imageB3="/productImages/_BG/$image3";
		$imageS3="/productImages/_SM/".substr($image3,0,-4)."_SM.jpg";
		if(!$getid){
			if($desc3){$LBcaption3=$title."<br>".$desc3;}
		}
		else{
			if($description){$LBcaption3=$title."<br>".$description;}
		}
	}

//echo $imageS1."<br>".$imageS2."<br>".$imageS3;
$f.="
<div class='row'>
 <div class='sixteen columns center'>
 	<h5 class='verticalPad5px'>$title</h5>
 </div>
</div>

<div class='row'>
	<div class=' eight columns center'>
	 <div class='row'>
	   <div class=' sixteen columns'>
	   <div class='row' id='imagecontainer'>
		<a id='mainFeaturedLink' href='$imageB1' rel='lightbox' title='$LBcaption'>
			<img id='mainFeaturedImage' src='$imageS' title='$title' alt='$title'>
		</a>
		<br>
		<span class='imgCaption'>$caption</span>
		
		</div>";
		
if($imageS2!=''||$imageS3!=''){


	$f.="<div id='imageThumbnails' class='row'>
			<div class=' five columns bottomPadFive'>
					<a data-index='1'  class='changepicture'>
					<img id='imageThumb1' class='currentImage' src='$imageS1' width='75' style='border:1px solid #666;' >
					</a>
			 </div>";
			 
				if($imageS2){
		$f.="<div class=' six columns bottomPadFive'>
					<a data-index='2' class='changepicture'>
					<img id='imageThumb2' src='$imageS2' width='75' style='border:1px solid #666;'></a>
			</div>";
					}
					else{
						$f.="<div class=' six columns bottomPadFive'></div>";
					}
		
	if($imageS3){
			$f.="<div class=' five columns bottomPadFive'>
						<a class='changepicture' data-index='3'>
						 <img id='imageThumb3' src='$imageS3' width='75' style='border:1px solid #666;' >
						</a>
					   </div>";
				}
	else {$f.="<div class=' five columns bottomPadFive'></div>";}
	
	$f.="</div>";
	
	}
	$f.="
		</div>
	</div/";
	$f.="<!--end image column -->
	</div>
	
	<div class=' topAlign eight columns'>
	  <div class='row'>
	  <div class=' sixteen columns featuredItemName'>
	  	$item $monogram<br>
		$dimension<br>";

//$descriptiontext=($getid)?$description
$f.="
	 </div>
	 </div>
	 <div class='row'>
	  <div class=' sixteen columns featuredItemDescription new' id='itemDescription'>
		<p id='description1'>$desc1</p>";
		//if($desc2){$f.="<p id='description2'>$desc2</p>";}
		//if($desc3){$f.="<p id='description3'>$desc3</p>";}
		if($dimension!=''){$f.="<p id='dimensions'>Dimensions: $dimension</p>";}
		if($weight!=0){$f.="<p id='troyoz'>$weight troy ounces</p>";}
		$f.="
	  </div>
	 </div>";
  
	$f.="
	<div class='row'>
		<div class=' six columns'>
			<b class='category'>Price:</b>
		</div>
		 
	<div class=' ten columns'>$$price</div>
	</div>
	<div class='row'>
		<div class=' six columns'>
			<b class='category'>In Stock:</b>
		</div> 
		<div class=' ten columns'>$instock</div>
	</div>	
	
	<div class='row'>
	
		<div class=' six columns bottomAlign'>
			<input class='searchResultAddButton' type='button' value='Add to Cart' onClick=\"addFeaturedItemToCart($id,'$cat')\">
		</div>
		<div class=' ten columns bottomAlign'>
			<input class='staticItemAddQty' type='text' value='1' id='qty$id' name='quantity$id' size='2'>
		</div>
		<!--
		<div class=' eleven columns bottomAlign'>
			<img src='/images/silverchest_add.gif' name='chestimage$id' onClick='updateCart($id,1);setCartGraphic2();' align=bottom width=42>
		</div>-->
	</div>
</div>
<div class='row'>
	<div class=' sixteen columns center'>
		<b class='recentadesc' id='showAllLink'>
			<span class='recentadesc showAllLink' data-category='$cat'>View the complete list</span> of As You Like It Silver Shop's featured items in $cats[$cat].</b>
	</div>
</div>
";
 }
 
 else{
	  $f="<div class='row tableHead'>
	<div class=' sixteen columns center'>
		<b class='recentadesc'><span class='featuredItemHeader'>FEATURED ITEM:</span></b>
	</div>
</div>
<div class='row'>
<div class=' sixteen columns center' style='line-height:1.5rem;'>
There are no featured items in our $staticcats[$cat] inventory today.
We do update As You Like It Silver Shop's inventory daily.
Check here often to see a listing of featured items.
<script>document.getElementById('showAllLink').style.visibility='hidden';</script>
</div>
</div>";
	  
  } 
}

  }
//add ajax overlay
 	/*$f="<div class='row' id='featuredItemOverlay'>
 		<div class=' sixteen columns center'>
				    <img src='/images/ajax-loader.gif'><br>
				    Loading...
	    </div>
	   </div>".$f;*/

  return $f;
}
?>
