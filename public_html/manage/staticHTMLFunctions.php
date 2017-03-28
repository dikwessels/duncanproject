<?


function getCartButtons($id,$imgTitle,$c){

$cb="<input class=\"staticItemAddButton\" type=\"button\" value=\"Add\" onClick=\"javascript:location='/addItem.php?id=$id&quantity='+this.form.quantity$id.value+'&temp=$c'\">
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

$sl="Share this item:<br>
                <div class=\"shareDiv\" id=\"shareFB$id\" style=\"left:0px;\">
                      <a style=\"border-style:none;\" href=\"javascript:sharePage('www.facebook.com');\">
                       <img style=\"border-style:none;\" src=\"/images/NetworkSiteLinks/FullColor/share-facebook.gif\" title=\"Share this item on Facebook\">
                      </a>
                </div>
                <div class=\"shareDiv\" id=\"shareDelicious$id\" style=\"left:25px;\"> 
                     <a style=\"border-style:none;\" href=\"javascript:sharePage('del.icio.us');\">
                      <img style=\"border-style:none;\" src=\"/images/NetworkSiteLinks/FullColor/share-delicious.gif\" title=\"deli.ico.us\">
                     </a>
                </div>
                <div class=\"shareDiv\" id=\"shareSU$id\" style=\"left:50px;\">
                     <a style=\"border-style:none;\" href=\"javascript:sharePage('www.stumbleupon.com');\">
                      <img style=\"border-style:none;\"  src=\"/images/NetworkSiteLinks/FullColor/share-stumbleupon.gif\" title=\"Stumbleupon.com\">
                     </a>
                </div>";
 
 return $sl;
}


function ftp_rmAll($cId,$dir) {
	$files=ftp_nlist($cId,$dir);
	foreach($files as $st) { 
		if (($st==".")  || ($st==".." )) {continue;} 

			if (substr($st,-3)!='php') {
				ftp_rmAll($cId,"$dir/$st");
				}
			else { echo "delete $st<BR>";
				ftp_delete($cId,"$dir/$st");
				}
			}
 	echo "remove $dir<BR>";
	ftp_rmdir($cId,$dir);
	}	
	
function staticURL($pattern,$brand,$category,$item,$id){

  $findArr=array("/","&","'",",",".");
  $replaceArr=array("","AND","","","");

  $staticcats=array("sp"=>"Flatware","fcs"=>"Flatware","f"=>"Flatware","ps"=>"Flatware","h"=>"Hollowware","bs"=>"Baby Silver","j"=>"Jewelry","stp"=>"SilverStorage","cp"=>"SilverCare","xm"=>"Christmas","cl"=>"Collectibles");
  $path="http://www.asyoulikeitsilvershop.com/staticHTML/";
  $sURL=$path.$staticcats[strtolower($category)];
  $keyword="";
  
  if(strtolower($category)!='cp' && strtolower($category)!='stp'){$keyword="STERLING-SILVER-";}

  $sURL.="/_".str_replace($findArr,$replaceArr,$brand)."/_".str_replace($findArr,$replaceArr,$pattern)."/".$keyword.str_replace($findArr,$replaceArr,$item)."-$id";
  return $sURL;
    
    //below is a correct link used in script debugging
    //http://www.asyoulikeitsilvershop.com/staticHTML/SilverStorage/_HAGERTY/_/PROTECTION%20STRIPS-5515

}

function createCategoryLink($category,$fileCat,$template,$brand){
	$urlbrand=urlencode($brand);
	
	$catlink="";
	$qbrand="SELECT id FROM inventory WHERE category=\"$category\" and brand=\"$brand\" and quantity!=0"; 
	$qresult=mysql_query($qbrand);
	$rc=mysql_numrows($qresult);
	
	if($rc>0){
	
		$filename=createFileName("search/",$fileCat,"",$brand);

		if(file_exists("/home/asyoulik/public_html/".$filename)){
			$filelink="http://www.asyoulikeitsilvershop.com/".$filename;
		}
			else
			{
			
			if($category=='f'){
				$category='f,fcs,ps,sp';
			}
			
				$filelink="http://www.asyoulikeitsilvershop.com/showSearch/template/$template/category/$category/brand/$urlbrand/";
			}
		
		
		$catlink="<a style=\"font-size:10px;\" href=\"$filelink\">$brand $fileCat</a>";
		
	}
	
	return $catlink;
	
}

function createFileName($path,$v,$pattern,$brand){
		
		$brand=str_replace(" ", "-",$brand);
		
		if($pattern!=""){
		$pattern=str_replace("#","",$pattern);
			$pattern=str_replace(" ","-",$pattern);
			$fname=strtolower(str_replace(array('/'),array(''),$pattern))."by".strtolower(str_replace(array('/'),array(''),$brand));
		}
		else{
			$fname=strtolower(str_replace(array('/'),array(''),$brand));		
		}
			
		$fname=str_replace(' ','', $fname);
		$fname=str_replace("'",'',$fname);
		$fname=str_replace('&','and',$fname);
		$fname=str_replace('co.','company',$fname);
		$fname=str_replace('co ','company',$fname);
		$fname=str_replace('bros.','brothers',$fname);
		$fname=str_replace('bro ','brothers',$fname);
		$fname=str_replace('.','',$fname);
		$fname=str_replace(',','',$fname);
		
			$keyword="";
			if($v!="SilverCare" && $v!="SilverStorage"){
			$keyword="-sterling-silver";
		}
		
		$fname=$path.$v."/".$fname."$keyword.html";
	
	
	return $fname;	
}

function createSubFolder($conn_id,$path,$fbrand,$fpattern){
 
  $findArr=array("/","&","'",",",".");
  $replaceArr=array("","AND","","","");
  $fbrand=str_replace($findArr,$replaceArr,$fbrand);
  $fpattern=str_replace($findArr,$replaceArr,$fpattern);


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
	}

        if (!is_dir($path."/_$fbrand")) {
	$rtnmsg.="Directory $path/$fbrand doesn't exist<br>";
	if(ftp_mkdir($conn_id,$path."/_$fbrand")){
	
	$rtnmsg.="New directory created at $path/_$fbrand<br>";
	}
	
	else{
	 echo "Directory could not be created at $path/$fbrand<br>";
	}
	
	//$chmod_cmd="CHMOD 0777 $path.$keyCat[$category]"; 
	}
	else{
	 $rtnmsg.="directory $path/_$fbrand exists<br>";
	}

        if (!is_dir($path."/_$fbrand/_$fpattern")) {
	$rtnmsg.="Directory $path/_$fbrand/_$fpattern doesn't exist.<br>";
	if(ftp_mkdir($conn_id,$path."/_$fbrand/_$fpattern")){
		$rtnmsg.= "New directory created at $path/_$fbrand/_$fpattern<br>"; 
	}
	
	else{
		$rtnmsg.= "Directory could not be created at $path/_$fbrand/_$fpattern<br>";
	}
	
	//$chmod_cmd="CHMOD 0777 $path$keyCat[$category]"; 
	}
	else{
	$rtnmsg.="Directory $path/_$fbrand/_$fpattern exists<br>";
	}
echo $rtnmsg;

//return  $rtnmsg; 
}

function rewriteSearchURL($pattern,$brand,$searchCategory,$category,$recent,$item,$template,$order,$limit,$gift,$pos){
	$searchURL="http://www.asyoulikeitsilvershop.com/showSearch/template/$template";

	if($category!=""){$searchURL.="/category/$category";}
	if($brand!=""){$searchURL.="/brand/".urlencode($brand);}
	if($pattern!=""){$searchURL.="/pattern/".urlencode($pattern);}
	if($recent!=""){$searchURL.="/recent/$recent";}
	if($searchCategory!="" && $searchcategory!="0"){$searchURL.="/searchCategory/$searchCategory";}
	
	if($item!=""){
	$item=str_replace("/","",$item);
	$searchURL.="/item/$item";
	}
	
	if($gift!=""){$searchURL.="/gift/$gift";}
	if($order!=""){$searchURL.="/order/".urlencode($order);}
	if($limit!=""){$searchURL.="/limit/$limit";}
	if($pos!=""){$searchURL.="/pos/$pos";}
	
	return $searchURL;
}


function getImageTitle($p,$b,$i,$m){
  if($p){
    $f=$p;
    if($b){$f.=" by $b";}
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
 
 if($m){$f.=", monogrammed";}
 $f=strtolower($f);

 return $f;

}


function getMoreItems($pfilelink,$bcfilelink,$bfilelink,$category,$p,$b,$cat){

 if($category=="h" || $category=="bs"){
      if($p!=""){
	$plink = "<a href=\"$pfilelink\">View more items in $p by $b</a><br>";
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

/* ******** SUGGESTED ITEM FUNCTION ********* */

function suggestedItems($id,$cat){
   
        $c="";
        $query="SELECT suggestedItems FROM inventory WHERE id=$id and suggestedItems and suggestedItems<>''";

        $result=mysql_query($query);
        $nr=mysql_num_rows($result);

        if($nr==0||$nr<1){
         //echo "No suggested items for this piece, using default values for $cat<br>";
         $query="SELECT suggestedItems FROM tblSuggestedItems WHERE category=\"$cat\"";
         $result=mysql_query($query);
        }
               
        if(mysql_num_rows($result)>0){
   
        $c="<br clear=\"all\">
            <div id=\"suggestedItems\">
      
			Recommended cleaning and storage items for this product:<br><br>";

 

         $row=mysql_fetch_assoc($result);
        extract($row);
        //echo "Data found: $suggestedItems<br>";
         $suggestedItems=substr($suggestedItems,0,strlen($suggestedItems)-1);
    
        //split data into array
        $arrItems=explode(";",$suggestedItems);

        //loop through array and retrieve content for each item
        $i=0;
        $top=0-95;
        
        foreach($arrItems as $k=>$v){
            $query="SELECT * FROM inventory WHERE id=$v";
            //echo "Query called: $query<br>";
            $result=mysql_query($query);
       
            if(mysql_num_rows($result)>0){
              $row=mysql_fetch_assoc($result);
              $statURL = staticURL($row[pattern],$row[brand],$row[category],$row[item],$row[id]);
              $itemName=getItemName($row[pattern],$row[brand],$row[item]);
	      $imageLink=getImageLink($row[image],$row[handle],$row[pattern],$row[brand]);
              $imgTitle=getImageTitle($row[pattern],$row[brand],$row[item],$row[monogram]);
                    $c.="<div class=\"suggestedItemContainer\">
                          <span style=\"font-size:11px\">$itemName - \$$row[retail]</span><br>
                              <a href=\"$statURL\"><img height=\"75\" src=\"$imageLink\" title=\"$imgTitle\" alt=\"$imgTitle\" ></a>
                              <a href=\"$statURL\"><span style=\"font-size:10px\"><br>Click Image for details</span></a><br><br>
                                    <span id=\"addSugItem$row[id]\">
                                        <a href=\"javascript:updateCart($row[id],1,1);updateSugItemAddButton($row[id]);\" style=\"text-decoration:none;font-size:12px;\">Add Item</a>
                                    </span>
                                <img title=\"Add $imgTitle to your cart.\" alt=\"Add $imgTitle to your cart.\" src='/images/silverchest_add.gif' name='chestimage$row[id]' onClick=\"updateCart($row[id],1,1);updateSugItemAddButton($row[id]);\" align=bottom width=\"30\">
                             </div>";
	       
             //end if statement
           }


        //end foreach loop
         }


   $c.="</div>";
   //end if statement 1
   }

   else{
     $c="";
   }
 return $c;
}

?>