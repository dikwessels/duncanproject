<?

/*

 post variables are
 productId
 pattern
 brand
 item

*/

extract($_GET);
extract($_POST);

function createImageFileName($p,$b,$i,$m,$pid,$ext){

  if($p){
    $f=$p;
    if($b){$f.=" BY $b";}
  }
  
  else{
    if($b){
     $f=$b;  
    }
  }

  if($f){
   $f.=" $i";
  }
  else
  {
   $f=$i;
  } 
 if($m){$f.="-MONOGRAMMED";}
  $f.="-$pid";
  $f.=$ext;
  $f.=".jpg";
  $f=str_replace("/","-",$f);
  $f=str_replace(" - "," ",$f);
  $f=str_replace("&","AND",$f);
  $f=str_replace(" ","-",$f);

 return $f;
}

/*

currently the script takes whatever is in the image field name and does the following:
 - creates a file with the field data in the productImages/BG/ folder
 - creates a smaller file with the same name but a _SM extension and saves it to the productImages/SM/ folder
 - creates a thumbnail file with the extension _TN and saves it to the productImages/TN/ folder

to correct the incorrect file names we need to do the following:
 - rename the existing filenames to the SEO suggested ones
 - first create the correct general name without any category or file extensions

*/

function createImage($image,$w,$h,$ext,$quality) {

$im = "/home/asyoulik/public_html/productImages/_BG/$image"; // name/location of original image.
$new_image = "/home/asyoulik/public_html/productImages/$ext/".substr($image,0,-4)."{$ext}.jpg";; // name/location of generated thumbnail.
if (file_exists($new_image)) { $temp=unlink($new_image); }
$src_img = ImageCreateFromJPEG($im); // make 'connection' to image


$src_width = imagesx($src_img); // width original image
$src_height = imagesy($src_img); // height original image

$dest_width = $w; //width thumbnail (image will scale down completely to this width)
$dest_height = $h; //max height of the thumbnail

$ar = $dest_width / $dest_height; // aspect ratio

$divX = $src_width / $dest_width; // factor to calculate the scaled down height

if($src_height / $divX <= $dest_height){ 
 //if the scaled down height is smaller than the thumbnail max height
  $y = 0; // don`t center crop (there is nothing to center)
  $dest_height = $src_height / $divX; // keep the original scaled down height
  $cropheight = $src_height; // set the cropheight to the original image height (there is nothing to be cropped)
}

else{
  $cropheight = $src_width / $ar; //maintain the proper thumbnail aspect ratio
  $y = $src_height / $divX; //calculate the scaled down height
  $y = ($y - $dest_height) * $divX / 2; //substract the thumbnail height from the scaled down original height than divide by 2 (for centering the crop to the proper height)
}

$dest_img = imagecreatetruecolor($dest_width,$dest_height); 
imagecopyresampled($dest_img, $src_img, 0, 0, 0 ,$y, $dest_width, $dest_height, $src_width, $cropheight); 
imagejpeg($dest_img, $new_image, $quality); 
imagedestroy($src_img); 
imagedestroy($dest_img);

}

function renameImageFileMain($oldImage,$newBG,$tn){

 //get old small and thumbnail images
    $oldSM=substr($oldImage,0,-4)."_SM.jpg";
    $oldTN=substr($oldImage,0,-4)."_TN.jpg";
    
   //get new small and thumbnail file names
    $newSM=substr($newBG,0,-4)."_SM.jpg";
    $newTN=substr($newBG,0,-4)."_TN.jpg";

    //rename main image
    rename("/home/asyoulik/public_html/productImages/_BG/$oldImage","/home/asyoulik/public_html/productImages/_BG/$newBG");    

    //rename smaller image
    rename("/home/asyoulik/public_html/productImages/_SM/$oldSM","/home/asyoulik/public_html/productImages/_SM/$newSM");

    //only rename thumbnail for main image, thumbnails don't exist for secondary images
    if($tn){
      rename("/home/asyoulik/public_html/productImages/_TN/$oldTN","/home/asyoulik/public_html/productImages/_TN/$newTN");
    }    
}

include("/home/asyoulik/connect/mysql_connect.php");


$lS=array(
		'sp'=>9,
		'fcs'=>10,
		'h'=>11,
		'bs'=>12,
		'f'=>8,
		'j'=>13,
		'cp'=>14,
		'stp'=>15,
		'xm'=>16,
		'cl'=>17,
		'cs'=>18
		);
		
$fL=array(
		'dinner knife'=>0,
		'place knife'=>1,
		'lunch/place knife'=>2,
		'dinner fork'=>3,
		'place fork'=>4,
		'lunch/place fork'=>5,
		'salad fork'=>6,
		'salad fork (place size)'=>6,
		'teaspoon'=>7
		);

if ($update) {
$ti=microtime();




//check and process 1st image
echo "<script>alert('is uploaded file BEFOFE');</script>";
if(is_uploaded_file($_FILES['userfile']['tmp_name'])) {
  echo "<script>alert('is uploaded file INSIDE');</script>";
  
 $image=createImageFileName($pattern,$brand,$item,$monogram,$productId,"");
 $newBG=$image;
//$image=stripslashes($HTTP_POST_FILES['userfile']['name']);

   //save standard image
	if (file_exists("/home/asyoulik/public_html/productImages/_BG/$image")) { 
		$temp=unlink("/home/asyoulik/public_html/productImages/_BG/$image"); 
		//echo $temp;
	}	
	copy($_FILES['userfile']['tmp_name'], "/home/asyoulik/public_html/productImages/_BG/$image");
	createImage($image,100,100,"_TN",75);
	createImage($image,250,600,"_SM",100);	

    $image=$newBG;
}

else{
//create proper SEO file name and
//check for existence of properly named image file

 $newBG=createImageFileName($pattern,$brand,$item,$monogram,$productId,"");
 
  if($image && $image!=$newBG ){

    if(!file_exists("/home/asyoulik/public_html/productImages/_BG/$newBG")){
     //rename all existing files to new format  
     renameImageFileMain($image,$newBG,"1");
    }

    //save renamed image file name to database variable
    $image=$newBG;
  }
}


//process 2nd image

if (is_uploaded_file($_FILES['userfile2']['tmp_name'])) {	
   
   $image2=createImageFilename($pattern,$brand,$item,$monogram,$productId,"-2");

    //$image2=stripslashes($_FILES['userfile2']['name']);
    //create standard image

	if (file_exists("/home/asyoulik/public_html/productImages/_BG/$image2")) { 
		$temp=unlink("/home/asyoulik/public_html/productImages/_BG/$image2"); 
		}	
	copy($_FILES['userfile2']['tmp_name'], "/home/asyoulik/public_html/productImages/_BG/$image2");

    //create smaller image
    createImage($image2,250,600,"_SM",100);

  }

else{
    
    $newBG=createImageFileName($pattern,$brand,$item,$monogram,$productId,"-2");
    
    if($image2 && $image2!=$newBG){
    
     //check for existence of file with proper name and rename
     if(!file_exists("/home/asyoulik/public_html/productImages/_BG/$newBG")){
      //rename all existing files
      renameImageFileMain($image2,$newBG,"");
     }

      //save new image file name to database field
      $image2=$newBG;
    }
}

if (is_uploaded_file($_FILES['userfile3']['tmp_name'])) {		
    
 $image3=createImageFilename($pattern,$brand,$item,$monogram,$productId,"-3");
 
  //$image3=stripslashes($_FILES['userfile3']['name']);
  	
   //delete existing file
   if (file_exists("/home/asyoulik/public_html/productImages/_BG/$image3")) { 
   		$temp=unlink("/home/asyoulik/public_html/productImages/_BG/$image3"); 
   }	
   
   //create standard sized image
   copy($_FILES['userfile3']['tmp_name'], "/home/asyoulik/public_html/productImages/_BG/$image3");
	
   //create smaller image
   createImage($image3,250,600,"_SM",100);

}


else{

    $newBG=createImageFileName($pattern,$brand,$item,$monogram,$productId,"-3");    
   
     if($image3 && $image3!=$newBG){  
      
     if(!file_exists("/home/asyoulik/public_html/productImages/_BG/$newBG")){
      //rename existing files
       renameImageFileMain($image3,$newBG,"");
      }

      //update data field
      $image3=$newBG;
     }
}


$listOrder=($fL[strtolower($item)])?$fL[strtolower($item)]:$lS[strtolower($category)];
$description=addslashes($description);
if ($id) {
	mysql_query("UPDATE inventory set image2='$image2',image3='$image3',`desc`='$desc',desc2='$desc2',desc3='$desc3',searchCategory='$searchCategory',listOrder='$listOrder',bs='$bs',productId='$productId',brand='$brand',pattern='$pattern',display='$display',item='$item',description='$description',weight='$weight',retail='$retail',dimension='$dimension',monogram='$monogram',category='$category',image='$image',sale='$sale',gift='$gift',`time`='$time',quantity='$quantity',origin='$origin' where id=$id"); 
	}
else {

	mysql_query("INSERT into inventory(image2,image3,`desc`,desc2,desc3,searchCategory,bs,productId,brand,pattern,display,item,description,weight,retail,dimension,monogram,category,image,sale,gift,`time`,quantity,listOrder,origin) values('$image2','$image3','$desc','$desc2','$desc3','$searchCategory','$bs','$productId','$brand','$pattern','$display','$item','$description','$weight','$retail','$dimension','$monogram','$category','$image','$sale','$gift','$time','$quantity','$listOrder','$origin')");
	$id=mysql_insert_id();
}

 echo "<div id=\"staticHTML\" style=\"display:none\">";
  include("createStaticHTML.php");
 echo "</div>";

}

function isSelected($rc,$c){

 $selected=$rc==$c?"selected=\"SELECTED\"":"";
 return $selected;

}

?>

<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>


<title> As You Like It Silver Shop </title>
<meta name='description' content='As You Like It Silver Shop in New Orleans Louisiana specializes in silver flatware and holloware in active, inactive and obsolete patterns.' />
<meta name='keywords' content='sterling silver, sterling flatware, silver flatware, antique silver, silver tableware, antique sterling, replacement silver, silver repair, corporate gifts, wedding gifts, silver identification, cleaning silver' />
<script language='javascript'>
<!--
	{

        flatwareon = new Image;
        flatwareon.src = '../images/nav_flatware_2.gif';
        flatwareoff = new Image;
        flatwareoff.src = '../images/nav_flatware_1.gif';
        
        greatgiftson = new Image;
        greatgiftson.src = '../images/nav_greatgifts_2.gif';
        greatgiftsoff = new Image;
        greatgiftsoff.src = '../images/nav_greatgifts_1.gif';
        
        cleaningon = new Image;
        cleaningon.src = '../images/nav_cleaning_2.gif';
        cleaningoff = new Image;
        cleaningoff.src = '../images/nav_cleaning_1.gif';
        
        otheron = new Image;
        otheron.src = '../images/nav_other_2.gif';
        otheroff = new Image;
        otheroff.src = '../images/nav_other_1.gif';
        
        tiffanyon = new Image;
        tiffanyon.src = '../images/nav_tiffany_2.gif';
        tiffanyoff = new Image;
        tiffanyoff.src = '../images/nav_tiffany_1.gif';
        
        hollowwareon = new Image;
        hollowwareon.src = '../images/nav_hollowware_2.gif';
        hollowwareoff = new Image;
        hollowwareoff.src = '../images/nav_hollowware_1.gif';
        
        servingon = new Image;
        servingon.src = '../images/nav_serving_2.gif';
        servingoff = new Image;
        servingoff.src = '../images/nav_serving_1.gif';
        
        babysilveron = new Image;
        babysilveron.src = '../images/nav_babysilver_2.gif';
        babysilveroff = new Image;
        babysilveroff.src = '../images/nav_babysilver_1.gif';

        searchon = new Image;
        searchon.src = '../images/nav_search_2.gif';
        searchoff = new Image;
        searchoff.src = '../images/nav_search_1.gif';
        
        returnhomeon = new Image;
        returnhomeon.src = '../images/nav_home_2.gif';
        returnhomeoff = new Image;
        returnhomeoff.src = '../images/nav_home_1.gif';
        
        
 }
//-->
</script>
<link rel='stylesheet' href='managestyle.css' type='text/css'>
<script language='javascript'>
<?
if ($id) {
	$query=mysql_query("SELECT * from inventory where id=$id");
	$row=@mysql_fetch_assoc($query);
	}
if ($productId) {
	$query=mysql_query("SELECT * from inventory where productId='$productId'");
	$row=@mysql_fetch_assoc($query);
	}
echo "searchCategory='$row[searchCategory]'
category='".strtoupper($row[category])."'
display='$row[display]'
time='$row[time]'
gift='$row[gift]'
origin='$row[origin]'
";
?>

function setUp() {
for (i=0;i<document.forms[0].length;i++) {
	f=document.forms[0].elements[i]
	regexp=/select/
	if (f.type.match(regexp)) {
		tempName=eval(f.name);
		for (j=0;j<f.options.length;j++) {
			if (f.options[j].value==tempName) {
				f.options[j].selected=1
				}
			}
		}	
	}
}
</script>
</head>

<body class='sub' onLoad=setUp()>

<form  action='edit.php' enctype='multipart/form-data' method=post id='productForm'>
<input type='hidden' name='MAX_FILE_SIZE' value='2000000'>
<table width='100%' cellpadding='0' cellspacing='0' border='0' align='left' bgcolor='#A27177' height='85'>
<tr>
	<td width='760'><img src='../images/ayliss_title_main.jpg' width='760' height='85' alt='As You Like It Silver Shop' border='0'><br></td>
	<td width='*'><img src='../images/blank.gif' width='10' height='1' alt='' border='0'></td>
</tr>
</table>
<br clear='all'>
<table width='100%' cellpadding='0' cellspacing='0' border='0' align='left'>
<tr bgcolor='#000000'>
	<th class='control'>CONTROL PANEL : Item Management</th>
	<td width='*'><img src='../images/blank.gif' width='10' height='1' alt='' border='0'></td>
</tr>
<tr>
	<td colspan='2'><img src='../images/blank.gif' width='10' height='10' alt='' border='0'></td>
</tr>
</table>
<? if ($update) { echo "<br clear='all'>UPDATE TOOK ".(round(microtime()-$ti,4))." SECONDS"; } ?>
<br clear='all'>
<?


echo "<input type=hidden name=id value=$row[id]><input type=hidden name=update value=1>
<table width='650' cellpadding='0' cellspacing='0' border='0' align='left'>
<tr>
	<td width='50' align='left'>
		<img src='../images/blank.gif' width='50' height='1' alt='' border='0'><br>
	</td>
	<td width='600' valign='top' align='left'>
			<table width='600' border='0' align='left' cellpadding='0' cellspacing='0'>
				<tr>
					<td valign='top' width='300' align='center'>
                                            <div id='filedrag' style='width:300'><img id='prevImg' src='../productImages/_SM/";
	if (!$row[image]) { 
         echo "noimage.jpg"; 
         } 
         
         else{ 
          echo substr($row[image],0,-4)."_SM.jpg"; 
         } 

$imageURL=str_replace(" ","",$row[image].$row[pattern].$row[brand].$row[item]);
$imageMono=$row[monogram]=='1'?"monogrammed":"";
$imageURL.=$imageMono;

//begin echo statement 1
echo "' width='300' alt=\"$row[image]\" title=\"$row[image]\"></div>
<br>
<br>
					Image: <input type=\"text\" size=\"255\" value=\"$row[image]\" id=image name=image class=imgFileInput>
                                        <br><br> or <input type=file id='userfile' name=userfile value='Browse' class='imgFileInput'><br><br>
					Image2: <input type='text' size='255' value='$row[image2]' name=image2 class='imgFileInput'>
                                         <br><br> or <input type=file name=userfile2 value='Browse' class='imgFileInput'><br><br>
					Image3: <input type='text' size='255' value='$row[image3]' name=image3 class='imgFileInput'>
                                         <br><br> or <input type=file name=userfile3 value='Browse' class='imgFileInput'><br>
                                         <div style='text-align:left'><font color=red>Important: You must click save changes in order for an image to be  uploaded.</font><br><Strong>Maximum Image Size: 8MB.</strong><BR><strong>NOTE:</strong> When uploading an image the following actions take place:<ol><li>Your browser transfers the image to a script on the server.  The speed of this is dependant on how fast your connection is UPLOADING data.<li>The script takes that image and creates two smaller versions of the image.  This is rather resource intensive and can take a few seconds.</ol></div>	</td>
					<td width='10'><img src='../images/blank.gif' width='10' height='1' alt='' border='0'></td>
					<td valign='top' width='290'>
<!--product details-->

<table border='0'>
<tr>
	<td>Product Id:</td>
	<td><input type='text' size='10' value='$row[productId]' class='reg' name=productId></td>
</tr>
<tr>
	<td>Pattern:</td>
	<td><input type='text' size='30' value='$row[pattern]' class='reg' name=pattern></td>
</tr>
<tr>
	<td>Manufacturer:</td>
	<td><input type='text' size='30' value='$row[brand]' class='reg' name=brand></td>
</tr>
<tr>
	<td>Item Type:</td>
	<td><input type='text' size='30' value='$row[item]' class='reg' name=item></td>
</tr>
<tr>
	<td valign='top'>Description:</td>
	<td><textarea rows='15' cols='50' class='reg' name=description>$row[description]</textarea></td>
</tr>
<tr>	
	<td>Weight:</td>
	<td><input type='text' size='30' value='$row[weight]' class='reg' name=weight></td>
</tr>
<tr>
	<td>Dimensions:</td>
	<td><input type='text' size='70' value='$row[dimension]' class='reg' name=dimension></td>
</tr>
<tr>
	<td>Category:</td>
	<td><SELECT id=select3 name=category class='reg'>
    	        <OPTION value=H";
//end of echo statement 1

 echo isSelected($rc,"h").">Hollowware</option>
  		<OPTION value=F>"; echo isSelected($rc,"f")."Flatware</option>
		<OPTION value=FCS>"; echo isSelected($rc,"fcs")."Flatware Complete Sets</option>
		<OPTION value=BS>"; echo isSelected($rc,"bs")."Baby Silver</option>
		<OPTION value=CS>"; echo isSelected($rc,"cs")."Coin Silver</option>
                <OPTION value=CL>"; echo isSelected($rc,"cl")."Collectibles</option>
		<OPTION value=J>"; echo isSelected($rc,"j")."Jewelry</option>

<!--	
	<OPTION  value=BSCS "; if ($cat=='BSCS') { echo "selected"; } echo ">Baby Silver Complete Sets</option>
				<OPTION  value=HCS "; if ($row[category]=='HCS') { echo "selected"; } echo ">Holloware Complete Sets</option>		
<OPTION  value=SPCS "; if ($row[category]=='SPCS') { echo "selected"; } echo ">Serving Pieces Complete Sets</option> 

-->
		<OPTION value=SP>"; echo isSelected($rc,"sp")."Serving Pieces</option>
		<OPTION value=CP>"; echo isSelected($rc,"cp")."Cleaning Products</option>
		<OPTION value=STP>"; echo isSelected($rc,"stp")."Storage Products</option>
		<OPTION value=XM>"; echo isSelected($rc,"xm")."Christmas Ornaments</option>
		</select>

<input type=checkbox name=bs";

 

 if ($row[bs]) { echo " checked"; }
		echo ">Baby Silver
	</td>
</tr>
<tr>
	<td>Monogram:</td>
	<td><input type='text' size='10' value='$row[monogram]' class='reg' name=monogram> 
</tr>
<tr>
	<td>Price:</td>
	<td><input type='text' size='10' value='$row[retail]' class='reg' name=retail> 
</tr>
<tr>
	<td>Sale Price:</td>
	<td><input type='text' size='10' value='$row[sale]' class='reg' name=sale> 
</tr>
<tr>	
	<td>Display:</td>
	<td> <SELECT  name=display class='reg'>	
	
		<OPTION value='1'>Yes</option>		
		<OPTION value=''>No</option>		
		</select>
	</td>
</tr>
<tr>	
	<td>Featured Item:</td>
	<td> <SELECT  name=time class='reg'>	
		<OPTION value='n'>Yes</option>	
		<OPTION value=''>No</option>		
		
		</select>
	</td>
</tr>
<tr>
	<td valign='top'>Featured Description(image):</td>
	<td><textarea rows='5' cols='50' class='reg' name=desc>$row[desc]</textarea></td>
</tr>
<tr>
	<td valign='top'>Featured Description2(image2):</td>
	<td><textarea rows='5' cols='50' class='reg' name=desc2>$row[desc2]</textarea></td>
</tr>
<tr>
	<td valign='top'>Featured Description3(image3):</td>
	<td><textarea rows='5' cols='50' class='reg' name=desc3>$row[desc3]</textarea></td>
</tr>
<tr>	
	<td>Country of Origin:</td>
	<td> <SELECT name=origin>
<option>
<option value=USA>USA
<option value=Australia>Australia
<option value=Austria>Austria
<option value=Belgium>Belgium
<option value=Canada>Canada
<option value=Denmark>Denmark
<option value='Eastern Europe'>Eastern Europe
<option value='England'>England
<option value=France>France
<option value='West Africa'>West Africa
<option value=Germany>Germany
<option value=Hungary>Hungary
<option value='Ireland'>Ireland
<option value=Italy>Italy
<option value=Mexico>Mexico
<option value='Middle East'>Middle East
<option value=Netherlands>Netherlands
<option value='North Africa'>North Africa
<option value=Portugal>Portugal
<option value=Russia>Russia
<option value=Scotland>Scotland
<option value='South America'>South America
<option value=Spain>Spain
<option value=Switzerland>Switzerland </select></td>
		
	</td>
</tr>
<tr>	
	<td>Gift:</td>
	<td> <SELECT  name=gift class='reg'>	
			<OPTION value='y'>Yes</option>
		<OPTION value=''>No</option>		
		
		</select>
	</td>
</tr>
<tr>	
	<td>Quantity:<br>(- values are infinite)</td>
	<td> <input type=text name=quantity value='$row[quantity]' size=2></td>
	
	</td>
</tr>
<!--<tr>
	<td>Style:</td>
	<td> <SELECT id=select2 name=select2 class='reg'>	
	
		<OPTION value='semiornate'>Semi-Ornate</option>		
		<OPTION value='simple'>Simple</option>		
		<OPTION value='ornate'>Ornate</option>	
				</select>
	</td>
</tr> -->";

if (strtolower($row[category])=='h') {
echo "
<tr><td>Search Category (Holloware only)</td><td><select name=searchCategory><option value=0><option value=1>Baskets, Centerpieces, Vases and Epergnes<option value=2>Bowls, Compotes & Cake Stands<option value=3>Bread & Butter Items<option value=4>Candleware
<option value=5>Gift
<option value=6>Goblets, Mint Julep Cups & Other Cups
<option value=7>Jewelry
<option value=8>Napkin Rings
<option value=9>Picture Frames
<option value=10>Pitchers & Urns
<option value=11>Salt & Pepper Items
<option value=12>Tabletop Items
<option value=13>Tea and Coffee
<option value=14>Trays
<option value=15>Vanity Items
<option value=16>Wine & Bar Items
<option value=17>Chafing Dish</select></td></tr>";}
?>
</table>




<!--end product details-->

					</td>
				</tr>
<tr><td colspan=3><hr></td></tr>
<td><br><a href=manage.php?<? echo "anchor=$row[id]"; ?>>Return to Main Menu</a></td>
	<td  align='center'><p align='center'>
		<input type='submit' value='SAVE CHANGES'>
	</td>
	<td align=right><a href=edit.php>Add New Item</a></td>
</tr>
			</table>
	
	</td>
</tr>
</table>

		</form>
<script type="text/javascript" src="filedrag.js"></script>

<style type="text/css">
#filedrag
{
  display: none;
  font-weight: bold;
  text-align: center;
  /*padding: 1em 0;
  margin: 1em 0;*/
  color: #555;
  border: 2px dashed #555;
  border-radius: 7px;
  cursor: default;
}

#filedrag.hover
{
  color: #f00;
  border-color: #f00;
  border-style: solid;
  box-shadow: inset 0 3px 4px #888;
}

</style>
</body>
</html>