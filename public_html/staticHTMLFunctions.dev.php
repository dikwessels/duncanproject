<?php
/* standardized static HTML functions */

include_once( "/connect/mysql_pdo_connect.php" );

function createFile($fname,$conn_id,$content,$pattern,$brand,$item){
	
	global $login_result;

		$f=fopen('temp/temp.html','w');
		fputs($f,$content);
		fclose($f);
		
	    $msgSuccess = "Successfully created static search results"; 
	    $msgFail = "Could not create static search file";
	       
	    
	    if( ($pattern != "") && ($brand != "") ){
	  	
	  	 $msgSuccess .= " for $pattern by $brand";
	     $msgFail .= " for $pattern by $brand";
	    
	    }
	    else{
		    
	    	if( $brand!="" ){
	    		$msgSuccess.=" for $brand";
	    		$msgFail.=" for $brand";
	    	}
		}	

 if($item){
	 $msgSuccess.=" $item";
	 $msgFail.=" $item";
 }
		$msgSuccess.=" at $fname<BR>";
		$msgFail.=" at $fname<BR>";
	
		if (ftp_put($conn_id, $fname, 'temp/temp.html', FTP_ASCII)){
			echo $msgSuccess;
			 $i =1;
		}
		else{
			echo $msgFail;
		$i=0;
		}
		return $i;
		
}

function checkForFile($filename){
	//echo "checking $filename <br>";
	if(!file_exists($filename)){
		$filename="";
	}
	return $filename;
}

function createCategoryLink($category,$brand){
	
	global $keyCat;
	global $catSubFolders;
	global $db;
	
	$fileCat = $keyCat[$category];
   
	$urlbrand = urlencode($brand);
	
	$catlink = "";
	
	$stmt = "SELECT id FROM inventory WHERE category=:category and brand=:brand and quantity!=0"; 
	
	$query = $db->prepare($stmt);
	
	$query->bindParam(":category",$category,PDO::PARAM_STR);
	$query->bindParam(":brand",$brand,PDO::PARAM_STR);
	
	
	$query->execute();
	
	$qresult = $query->fetchAll();
	
	if( ($qresult) && count($qresult) >0 ){
	
		//$rc = count($qresult);

		$filename=createFileName("/home/asyoulik/public_html/",$fileCat,"",$brand,"");

		if(file_exists($filename)){
			$filelink = "http://www.asyoulikeitsilvershop.com/".str_replace("/home/asyoulik/public_html/","",$filename);
		}
		
		else
		{
			$filelink = "http://www.asyoulikeitsilvershop.com/productSearch.php?category=$category&brand=$urlbrand";
		}
		
		
		$catlink = "<a style='font-size:10px;' href='$filelink'>$brand $keyCat[$category]</a>";
		
	}
	
	return $catlink;
	
}

function createFileName($path,$folder,$pattern,$brand,$item){
	//creates name of category and brand static search files

	global $catSubFolders;
	$item=strtolower($item);
	//$find=array(" ",",","#","/","&","'",".","bros","bro","co");	
	//$replace=array("-","","","","and","","","brothers","brothers","company");
	
		$keyword="";
		$fname="";
		$pattern=urldecode($pattern);
		$brand=urldecode($brand);
		
		$catFolder=$catSubFolders[$folder];
		
		if($item){
		 	$item=standardizeName($item);
			$fname="silver-".$item;
		}
		
		else{
			//create file name of /silver-[category]/pattern-[by]-[brand]-keyword-category.html
			
			if($catFolder!="silver-care" && $catFolder!="silver-storage"){
			
					if(trim(strtolower($brand))!="christofle"){
						$keyword="sterling-silver";
					}
					else{
						$keyword="silverplate";
					}
			}
			
			if($pattern!=""){
					//$pattern=str_replace($find,$replace,$pattern);
					$fname=$pattern;
				}
			if($brand){
					//$brand=str_replace($find,$replace,$brand);
					
					if($fname){
					  $fname.="-by-".$brand;
					}
					else{
					  	$fname=$brand;
					}
				}
			
		
		}
				$fname=standardizeName($fname);
	
		
		/*
		$fname=str_replace(' ', '', $fname);
		$fname=str_replace("'",'',$fname);
		$fname=str_replace('&','and',$fname);
		$fname=str_replace('co.','company',$fname);
		$fname=str_replace('co ','company',$fname);
		$fname=str_replace('bros.','brothers',$fname);
		$fname=str_replace('bro ','brothers',$fname);
		$fname=str_replace('.','',$fname);
		$fname=str_replace(',','',$fname);
		$fname=str_replace("ü", "u", $fname);
		*/
		if(!$item){
		//if(!$item || $pattern || $brand){
			if($catFolder!="baby-silver"&&$catFolder!="coin-silver"){
			$fname=$fname."-".$keyword;
				if($catFolder!="silver"){
					$fname.=str_replace("silver", "", $catFolder);
				}
			}
			else{
				$fname.="-".$catFolder;
			}
		}

		$fname=$path.$catFolder."/".$fname.".html";
		

		$fname=strtolower($fname);
		
	return $fname;	

}

function createStandaloneFileName($id, $pattern,$brand,$item,$monogram,$keyword){
	//creates a file name of the form [pattern by brand] || [brand] [monogrammed] [item] [id].html

if($pattern){
  if($brand){
  	  $patBrand="$pattern by $brand";
	 
  }
  else{
	  $patBrand="$pattern";
  }	
}
else{
	if($brand!="unknown"&&$brand!=""){
		$patBrand=$brand;	
	}
}

 $fname=trim($keyword);
 
 if($patBrand){$fname=$patBrand." ".$fname;}
 if($monogram){$fname.=" ".trim($monogram);}
 
 $fname.=" ".trim($item)." ".trim($id);
 $fname=standardizeName($fname);
 
 $fname.=".html";
 return $fname;
 
}

function standardizeName($fname){

$fname=strtolower($fname);

  	$find=array("    ",
  				"   ",
  				"  ",
  				",",
  				"#",
  				"no.",
  				"/",
  				"&",
  				"'",
  				".",
  				" bros ",
  				" bro ",
  				" co ",
  				"ü",
  				")",
  				"(",
  				" ",
  				"--");	
  				
	$replace=array(
				" ",
				" ",
				" ",
				"",
				"number",
				"number",
				"",
				"and",
				"",
				"",
				" brothers ",
				" brothers ",
				" company ",
				"u",
				"",
				"",
				"-",
				"-");

 
 $fname=str_replace($find, $replace, $fname);
 //$fname=str_replace(" ", "-", $fname);
 //$fname=str_replace("--","-",$fname);

 return $fname;

}

function createSubFolder($conn_id,$path,$fbrand,$fpattern){
 global $filepath;
 
  if(!$fbrand||$fbrand=="UNKNOWN"||$fbrand==""){$fbrand="unknown-brand";}
  
  //if(!$fpattern||$fpattern==""){$fpattern="unknown-pattern";}
  	
  $findArr=array("/","&","'",",","."," ");
  $replaceArr=array("","AND","","","","-");
  $fbrand=str_replace($findArr,$replaceArr,$fbrand);
  $fpattern=str_replace($findArr,$replaceArr,$fpattern);
  
  $fbrand=strtolower($fbrand);
  $fpattern=strtolower($fpattern);
  
  $rtnmsg="";
  //make subdirectories
  //echo "$fpattern by $fbrand";

    if (!is_dir($path."/")) {
	ftp_mkdir($conn_id,$path);
	//$chmod_cmd="CHMOD 0777 $path$keyCat[$category]"; 
	$rtnmsg.="Directory $path doesn't exist<br>";
	}
	else{
	$rtnmsg.="Directory $path exists<br>";
	 $filepath=$path;
	}

    if (!is_dir($path."/$fbrand"."-silver")) {
	$rtnmsg.="Directory $path/$fbrand-silver doesn't exist<br>";
	if(ftp_mkdir($conn_id,$path."/$fbrand"."-silver")){
		$rtnmsg.="New directory created at $path/$fbrand-silver<br>";
		$filepath=$path."/".$fbrand."-silver";
	}
	
	else{
	 echo "Directory could not be created at $path/$fbrand-silver<br>";
	}
	
	//$chmod_cmd="CHMOD 0777 $path.$keyCat[$category]"; 
	}
	
	else{
	 $rtnmsg.="directory $path/$fbrand-silver exists<br>";
	 $filepath=$path."/".$fbrand."-silver";
	}
	
	
	if($fpattern){

        if (!is_dir($path."/$fbrand"."-silver/"."$fpattern")) {
	$rtnmsg.="Directory $path/$fbrand-silver/$fpattern doesn't exist.<br>";
	if(ftp_mkdir($conn_id,$path."/$fbrand-silver/$fpattern")){
		$rtnmsg.= "New directory created at $path/$fbrand-silver/$fpattern<br>"; 
		$filepath=$path."/".$fbrand."-silver"."/".$fpattern;
	}
	
	else{
		$rtnmsg.= "Directory could not be created at $path/$fbrand-silver/$fpattern<br>";
	}
	
	//$chmod_cmd="CHMOD 0777 $path$keyCat[$category]"; 
	}
	else{
	$rtnmsg.="Directory $path/$fbrand-silver/$fpattern exists<br>";
		$filepath=$path."/".$fbrand."-silver"."/".$fpattern;
	}
	}
	
echo $rtnmsg;

//return  $rtnmsg; 
}

function getBrandFromPattern($pattern){
	
	global $db;
	
	$stmt = "SELECT DISTINCT brand FROM inventory WHERE pattern=:pattern"; 
	
	$query = $db->prepare($stmt);
	
	$query->bindParam(":pattern",$pattern,PDO::PARAM_STR);
	
	$query->execute();
	
	$result = $query->fetchAll();
	
	foreach($result as $row){
		$foundBrand.=$row[brand].":";
	}
	
	return $foundBrand;
}

function getBreadCrumbLink($category,$pattern,$brand,$item){

//$item is a boolean so it knows to do both a pattern if applicable and a brand
 $breadCrumbLink="";
 if($item){
	if($pattern){
		if(!$brand){$brand=getBrandFromPattern($pattern);}
		//create two links first for the brand, then for the pattern within that category
			
			$breadCrumbLink=getBreadCrumbLinkSub("",$brand,$category);
			$breadCrumbLink.=getBreadCrumbLinkSub($pattern,$brand,$category);			
		}
		
		else{
		// just do the brand breadcrumb link
				if($brand!="UNKNOWN"&& $brand){ 
					$breadCrumbLink=getBreadCrumbLinkSub("",$brand,$category);
				}
		}
 }
 
 else{
	if($pattern){
   //create a breadcrumb link for the brand
  		if(!$brand){
  			$brand=getBrandFromPattern($pattern);
  		}
  		$breadCrumbLink=getBreadCrumbLinkSub("",$brand,$category);  
  	}
}	

	return $breadCrumbLink;

}

function getBreadCrumbLinkSub($pattern,$brand,$category){
	global $keyCat;
	global $catSubFolders;
	
    $linkCat=$keyCat[$category]=="All"?"Silver":$keyCat[$category];
	
	$pageCategory=$keyCat[$category];
	
	$breadCrumbLink="<h2 class=\"h2BreadCrumb\">";
	//echo $fileLink;
			$fileLink=createFileName("/home/asyoulik/public_html/",$pageCategory,$pattern,$brand,"");
			
			$fileLink=checkForFile($fileLink);
			if($fileLink!=""){
				$fileLink=str_replace("/home/asyoulik/public_html/", "", $fileLink);
			}
			else{
			   $fileLink="productSearch.php?";
			   
				if($category){$args.="&category=$category";}
				if($brand){$args.="&brand=".str_replace("&","%26",$brand);}
				if($pattern){$args.="&pattern=".str_replace("&","%26",$pattern);}
				$args=substr($args, 1);
				$fileLink.=$args;
				
			} 
  	  
			$breadCrumbLink.="<a href=\"http://www.asyoulikeitsilvershop.com/$fileLink\"> / ";
			if($pattern && $brand){
			$breadCrumbLink.=ucwords(strtolower($pattern." by ".str_replace(":","/",$brand)." ".$linkCat))."</a> </h2>";
			}
			else{
				$breadCrumbLink.=ucwords(strtolower(str_replace(":","/",$brand)." ".$linkCat))."</a></h2>";
			}
	
return	$breadCrumbLink;	
}

function getCartButtons($id,$imgTitle,$c){

$cb="<input class=\"searchResultAddButton\" type=\"button\" value=\"Add to Cart\" onClick=\"javascript:location='/addItem.php?id=$id&quantity='+this.form.quantity$id.value+'&temp=$c'\">
     <input class=\"staticItemAddQty\" type='text' value='1' name=\"quantity$id\" size=\"2\">";
/*
 $cb="<input class=\"staticItemAdd\" type='button' value='Add' onClick=\"javascript:location='/addItem.php?id=$id&quantity='+this.form.quantity$id.value+'&temp=$c'\">
         <input class=\"staticItemAddQty\" type='text' value='1' name=\"quantity$id\" size=\"2\">";
*/

/*
         <div id=\"smallCartContainer\">
	 <img alt=\"Add $imgTitle to your cart.\" title=\"Add $imgTitle to your cart.\"";
 $cb.=" src='/images/silverchest_add.gif' name='chestimage$row[id]' onClick=\"updateCart($id,1);\" align=\"bottom\" width=\"42\">

 </div>";
*/

 return $cb;

}

function getImageLink($image,$handle,$pattern,$brand){

    $imageFileTest="/home/asyoulik/public_html/productImages/_TN/".substr($image,0,-4)."_TN.jpg";

    //echo "$imageFileTest<br>";
    $handleFileName = "/home/asyoulik/public_html/HANDLES/".strtoupper(substr($pattern,0,1))."/".str_replace(" ",'',strtolower($pattern)."by".strtolower($brand).".jpg");

    if(!file_exists($imageFileTest)){
        if(!file_exists($handleFileName)){
              $imageLink="/productImages/_TN/noimage_th.jpg";
        }
        else{
              $imageLink=substr($handleFileName,2);
        }
   }
   else{
       $imageLink="/productImages/_TN/".substr($image,0,-4)."_TN.jpg";
    }



    return $imageLink;
}

function getItemName($pattern,$brand,$item){
  $itemName=$item;
  return $itemName;
}

function getShareLinks($id){

$sl="<div class=\"row\">
                <div class=\"cell sixteenColumns last\" id=\"shareFB$id\">
                	 Share:
                      <a style=\"border-style:none;\" href=\"javascript:sharePage('www.facebook.com');\">
                       <img style=\"border-style:none;\" src=\"/images/NetworkSiteLinks/FullColor/share-facebook.gif\" title=\"Share this item on Facebook\">
                      </a>
                </div>
               </div>          
        ";
 return $sl;
}

function getMoreItems($pfilelink,$bcfilelink,$bfilelink,$category,$p,$b,$cat){

 if($category=="h" || $category=="bs"){
      if($p!=""){
      	//echo $pfilelink;
      	if(file_exists($pfilelink)){
	      	$pfilelink=str_replace("/home/asyoulik/public_html/", "http://www.asyoulikeitsilvershop.com/", $pfilelink);
      	}
      	else{
	      	$pfilelink="http://www.asyoulikeitsilvershop.com/productSearch.php?pattern=".urlencode($p)."&brand=".urlencode($b);
      	}
      	
	      $plink = "<a href=\"$pfilelink\">View more items in ".ucwords(strtolower($p))." by ".ucwords(strtolower($b))."</a><br>";
      }		
      else{
	$plink="";
      }
	
    if($row[brand]!=""){
	if($category!="stp" && $category!="cp"){
           $companytype="Silversmiths";
         }
     	    
	 $bclink="<a href=\"$bcfilelink\">View all $b $companytype $cat</a><br>";
	 $blink="<a href=\"$bfilelink\">View all inventory by $b $companytype</a>";
      }
      else{
       $blink="";	
      }
  }

 $mi=$plink.$bclink.$blink;

 return $mi;

}

function handleFilePath($pattern,$brand){
	$re=array("&",",",'.','#','by','BROTHERS','INTERNATIONAL');
	$rw=array("AND",'','','','','BROS','INTL');
	
	$path="/home/asyoulik/public_html/HANDLES/";	
  
    $folder=strtoupper(substr($pattern,0,1));
    $fp=$path.$folder."/".str_replace($re,$rw,strtoupper("$pattern $brand")).".jpg";	
    
    return $fp;
    
}

function rewriteSearchURL($pattern,$brand,$searchCategory,$category,$recent,$item){
	
	$searchURL="http://www.asyoulikeitsilvershop.com/productSearch.php?";
	
	if( $category != "" ){$args.="&category=$category";}
	if( $brand != "" ){$args.="&brand=".urlencode($brand);}
	if( $pattern != "" ){$args.="&pattern=".urlencode($pattern);}
	if( $recent != "" ){$args.="&recent=".$recent;}

	if($category!="f"&&$category!="sp"&&$category!="ps"&&$category!="fcs"){	
		if($searchCategory!=""){$args.="&searchCategory=".$searchCategory;}
		if($item!=""){$args.="&searchItem=".$item;}
	}
	
	$args=substr($args, 1);
	
	return $searchURL.$args;
}

function staticURL($pattern,$brand,$filecat,$category,$item,$id){

  $findArr = array("/","&","'",",",".");
  $replaceArr = array("","AND","","","");

   $keyword = "STERLING-SILVER-";
   
   if( ($category == 'cp') || ( $category == 'stp' ) || ( $category == '') ){
	$keyword = "";
   }


   $sURL="/staticHTML/$filecat/_".str_replace($findArr,$replaceArr,$brand)."/_".str_replace($findArr,$replaceArr,$pattern)."/".$keyword.str_replace($findArr,$replaceArr,$item)."-$id";

  return $sURL;

}

/* ******** SUGGESTED ITEM FUNCTION ********* */
function suggestedItems($id,$cat){
   
   	global $db;
   	
   
        $c = "";
        
        $stmt = "SELECT suggestedItems FROM inventory WHERE id=:id and suggestedItems and suggestedItems<>''";

		$query = $db->prepare($stmt);
		$query->bindParam(":id",$id);
		
		$query->execute();
		$result = $query->fetchAll();
		      
        if( (!$result) || (count($result) < 1) ){
	     //return the suggested items for the category
   
         $stmt="SELECT suggestedItems FROM tblSuggestedItems WHERE category=:cat";

         $query = $db->prepare($stmt);
		 $query->bindParam(":cat",$cat);
		 $query->execute();
		 
         $result = $query->fetchAll();
        
        }
       
        if( $result && count($result) > 0 ){
   
        $c = "
        	<div class=\"row nopad\">
        		<div class=\"cell sixteenColumns\">
        			Recommended cleaning and storage items for this product:
        		</div>
        	</div>
        	 <div class=\"row\">";
        	 
        $row = $result[0];
         
        extract($row);
        
        $suggestedItems=substr($suggestedItems,0,strlen($suggestedItems)-1);
    
        $arrItems=explode(";",$suggestedItems);

        //loop through array and retrieve content for each item
        $i=0;
        $top=0-95;
        
        foreach( $arrItems as $k=>$v ){
	        
            $stmt = "SELECT * FROM inventory WHERE id=$v";
            
            $query = $db->prepare($stmt);
            $query->execute();
           
            $result = $query->fetchAll();
       
            if( $result && count($result) > 0 ){
	            
            	$row = $result[0];
            	
				$statURL = "http://www.asyoulikeitsilvershop.com/showItem.php?product=$row[id]";
             
				$itemName = getItemName($row['pattern'],$row['brand'],$row['item']);
				
				$imageLink = getImageLink($row['image'],$row['handle'],$row['pattern'],$row['brand']);
				
				$imgTitle = getImageTitle($row['pattern'],$row['brand'],$row['item'],$row['monogram']);
                
                $c .= "
                   
                    <div class=\"suggestedItemContainer\">
                          <span style=\"font-size:11px\">$itemName - \$$row[retail]</span><br>
                              <a href=\"$statURL\"><img height=\"75\" src=\"$imageLink\" title=\"$imgTitle\" alt=\"$imgTitle\" ></a>
                              <a href=\"$statURL\"><span style=\"font-size:10px\"><br>Click Image for details</span></a><br><br>
                                    <span id=\"addSugItem$row[id]\">
                                        <a href=\"javascript:updateCart($row[id],1,1);updateSugItemAddButton($row[id]);\" style=\"text-decoration:none;font-size:12px;\">Add to Cart</a>
                                    </span>
                                <!--<img title=\"Add $imgTitle to your cart.\" alt=\"Add $imgTitle to your cart.\" src='/images/silverchest_add.gif' name='chestimage$row[id]' onClick=\"updateCart($row[id],1,1);updateSugItemAddButton($row[id]);\" align=bottom width=\"30\">-->
                             </div>";
	       
             //end if statement
           }


        //end foreach loop
         }


   $c.="</div>";
   $c="<div class=\"cell fifteenColumns\" id=\"suggestedItems\">$c</div>";
   //end if statement 1
   }

		else{
			$c = "";
		}

 return $c;
}



?>
