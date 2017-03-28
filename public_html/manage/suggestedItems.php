<?php

include('/home/asyoulik/connect/mysql_connect.php');

if($_GET){extract($_GET);}
if($_POST){extract($_POST);}

echo(suggestedItems($id));

function getItemName($pattern,$brand,$item){
$itemName=$item;
/*if($brand!=""){
    $itemName="$brand $itemName";
    if($pattern!=""){$itemName="$pattern by $iteName";}
  }
   else
    {
     if($pattern!=""){$itemName="$pattern $itemName";}
    }
*/
return $itemName;

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

function suggestedItems($id){
        //echo "Suggested Items function called for item $id<br>";

        $c="";

        $query="SELECT suggestedItems FROM inventory WHERE id=$id";
        //echo "Query: $query<br>";

        $result=mysql_query($query);
        //echo "Rows found: ".mysql_num_rows($result)."<br>";

        if(mysql_num_rows($result)>0){

        $c="<br clear=\"all\"><div id=\"suggestedItems\">
              <table width=\"690\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"left\">
              <tbody>
	        <tr>
		    <td colspan=\"3\">
			We recommend the following cleaning and storage items for this product:<br><br>
		    </td>
	        </tr>
        <tr>";

 
    $row=mysql_fetch_assoc($result);
        extract($row);
        //echo "Data found: $suggestedItems<br>";
         $suggestedItems=substr($suggestedItems,0,strlen($suggestedItems)-1);
    
        //split data into array
        $arrItems=explode(";",$suggestedItems);

        //loop through array and retrieve content for each item
        $i=0;
        foreach($arrItems as $k=>$v){
            $query="SELECT * FROM inventory WHERE id=$v";
            //echo "Query called: $query<br>";
            $result=mysql_query($query);
       
            if(mysql_num_rows($result)>0){
              $row=mysql_fetch_assoc($result);
              $statURL = staticURL($row[pattern],$row[brand],$row[category],$row[item],$row[id]);
              $itemName=getItemName($row[pattern],$row[brand],$row[item]);
	      $imageLink=getImageLink($row[image],$row[handle],$row[pattern],$row[brand]);
  
             //$c.="<td valign=\"bottom\" align=\"center\"><a href=\"$statURL\"><img src=\"$imageLink\"></a><br><a href=\"$statURL\"><span style=\"font-size:10px\">$itemName</span></a></td>";

               if($i==0){$c.="<tr>";}
	
                if($i<3){
   	 	        $c.="<td valign=\"bottom\" align=\"center\">
                              <a href=\"$statURL\"><img src=\"$imageLink\"></a>
                              <br>
                              <a href=\"$statURL\"><span style=\"font-size:10px\">$itemName - \$$row[retail]<br>Click Image for details</span></a>
                              <br>
                              <span id=\"addSugItem$id\"><a href=\"javascript:updateCart($id,1,1);document.getElementById('addSugItem$id').innerHTML='Item Has Been Added';\">Add to Cart</a></span>
                       </td>";
	        }
	          else{
         	    $c.="</tr><tr><td colspan=\"3\"><br><br></td></tr>"; 
 		    $i=-1;
                  }
	       
       //end if statement
       }
      $i++;

     //end foreach loop
      }


   $c.="</tr></tbody></table></div>";

   //end if statement 1
   }
else{
$c="";
}

 //echo $c;
 return $c;
}

function staticURL($pattern,$brand,$category,$item,$id){

  $staticcats=array("sp"=>"Flatware","fcs"=>"Flatware","f"=>"Flatware","ps"=>"Flatware","h"=>"Hollowware","bs"=>"Baby Silver","j"=>"Jewelry","stp"=>"SilverStorage","cp"=>"SilverCare","xm"=>"Christmas");
  $path="http://www.asyoulikeitsilvershop.com/staticHTML/";
  $sURL=$path.$staticcats[strtolower($category)];
  $keyword="";

  if(strtolower($category)!='cp' && strtolower($category)!='stp'){$keyword="sterling-silver-";}

  $sURL.="/_".str_replace(array('/'),array(''),$brand)."/_".str_replace(array('/'),array(''),$pattern)."/".$keyword.str_replace(array('/'),array(''),$item)."-$id";
  return $sURL;
    
    //below is a correct link used in script debugging
    //http://www.asyoulikeitsilvershop.com/staticHTML/SilverStorage/_HAGERTY/_/PROTECTION%20STRIPS-5515

}

?>