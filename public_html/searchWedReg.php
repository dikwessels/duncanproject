<?php
include("/home/asyoulik/connect/mysql_connect.php");
function createFileName($path,$v,$pattern,$brand){
		$brand=str_replace(" ", "-",$brand);
		
		if($pattern!=""){
		$pattern=str_replace("#","",$pattern);
			$pattern=str_replace(" ","-",$pattern);
			$fname=strtolower(str_replace(array('/'),array(''),$pattern))."-by-".strtolower(str_replace(array('/'),array(''),$brand));
		}
		else{
			$fname=strtolower(str_replace(array('/'),array(''),$brand));		
		}
			
		$fname=str_replace(' ', '', $fname);
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

function getQuantity($pattern,$brand,$item,$quantity){
if($item=="4 Piece Lunch/Place Setting"||$item=="4 Piece Lunch Setting" || $item=="4 Piece Dinner Setting"){
 
$query = "SELECT min(quantity) as minQuantity FROM inventory WHERE pattern=\"$pattern\" AND brand=\"$brand\" and (monogram=0 or monogram=\"\") and (item=\"teaspoon\" OR item=\"salad fork\"";
  
switch($item){
  case "4 Piece Lunch/Place Setting":
    $query.=" OR item=\"Lunch/Place Fork\" OR item=\"Lunch/Place Knife\")";
    break;
  case "4 Piece Place Size Setting":
   $query.=" OR item=\"Place Fork\" OR item=\"Place Knife\")";
    break;
  case "4 Piece Dinner Setting":
   $query.=" OR item=\"Dinner Fork\" OR item=\"Dinner Knife\")";
   break;
}

$result=mysql_query($query);
$row=mysql_fetch_assoc($result);
//echo $query;
extract($row);
$q=$minQuantity;

}

else{
 $q = $quantity;
}

return $q;

}

function rewriteSearchURL($pattern,$brand,$searchCategory,$category,$recent,$item,$template){
	$searchURL="href=http://www.asyoulikeitsilvershop.com/showSearch/template/$template/";

	if($category!=""){$searchURL.="/category/$category";}
	if($brand!=""){$searchURL.="/brand/".urlencode($brand);}
	if($pattern!=""){$searchURL.="/pattern/".urlencode($pattern);}
	if($recent!=""){$searchURL.="/recent/$recent";}
	if($searchCategory!=""){$searchURL.="/searchCategory/$searchCategory";}
	if($item!=""){$searchURL.="/item/";}
	
	return $searchURL;
}


function staticURL($pattern,$brand,$filecat,$category,$item,$id){

//if($pattern==""){$pattern="UNKNOWN";}
//if($brand==""){$brand="UNKNOWN";}

//$sURL="/staticHTML/".$category."/".str_replace(array('/'),array('-'),$brand)."/".str_replace(array('/'),array('-'),$pattern)."/";

if($pattern!=""){
//$sURL.=strtolower(str_replace(array('/'),array(''),$pattern))."by";
}

$keyword="STERLING-SILVER-";

if($category=='cp' || $category=='stp' || $category==''){
	$keyword="";
	}

//$sURL.=$keyword.rawurlencode(strtolower(str_replace(array('/'),array('-'),$item)))."-".$id;
$sURL="/staticHTML/$filecat/_".str_replace(array('/'),array(''),$brand)."/_".str_replace(array('/'),array(''),$pattern)."/".$keyword.rawurlencode(str_replace(array('/'),array(''),$item))."-$id";

return $sURL;

}


extract($_POST);
extract($_GET);

if($_POST || $_GET){


if(!$regID){


	$w="WHERE rfname !=''";

	if($fname && $fname!=""){
	$w.=" AND (rfname LIKE '$fname' OR instr(rfname,\"$fname\")>0 OR crfname LIKE '$fname' OR instr(crfname,\"$fname\")>0)";
		//$w.=" AND (rfname=\"$fname\" OR crfname=\"$fname\" OR instr(rfname,\"$fname\")>0 or instr(crfname,\"$fname\")>0) ";
	}

	if($lname && $lname!=""){
		$w.=" AND (rlname=\"$lname\" OR crlname=\"$lname\" OR instr(rlname,\"$lname\")>0 or instr(crlname,\"$lname\")>0)";
	}

	if($smonth){
		$w.=" AND wedmonth=\"$smonth\"";
	}

	if($syear){
		$w.=" AND wedyear=\"$syear\"";
	}


$rs1=1;
$rs2=2;
$rs3=3;
$rs4=4;
$rs5=5;

$rsort=$sort*-1;

switch($sort){
case "":
  	$s = "rlname ,rfname ,crlname,crfname";
  	break;

case -5:
	$s = "event DESC,rlname,rfname";
	$rs5=$rsort;
	break;
  
case -4:
	$s="rstate DESC, rlname,rfname";
	$rs4=$rsort;
	break;

case -3:
	$s= "wedyear DESC,wedmonth DESC,wedday DESC,rlname,rfname";
	$rs3=$rsort;
	break;

case -2:
	$s= "crlname DESC,crfname DESC,wedyear,wedmonth,wedday";
	$rs2=$rsort;
	break;
case -1:
	$s = "rlname DESC,rfname DESC,crlname,crfname";
	$rs1=$rsort;
	break;

case 1:
   	$s = "rlname ,rfname ,crlname,crfname";
	$rs1=$rsort;
	break;

case 2:
	$s= "crlname,crfname,wedyear,wedmonth,wedday";
	$rs2=$rsort;
	break;

case 3:
	$s= "wedyear,wedmonth,wedday,rlname,rfname";
	$rs3=$rsort;
	break;
case 4:
	$s="rstate, rlname,rfname";
	$rs4=$rsort;
	break;
case 5:
	$s="event,rlname,rfname";
	$rs5=$rsort;
	break;

}



$query="SELECT * FROM weddingRegistries $w ORDER BY $s";
//echo $query;

$eventArray=array("a"=>"Anniversary","bbs"=>"Baby Shower","bd"=>"Birthday","brs"=>"Bridal Shower","o"=>"Other","w"=>"Wedding");


$result=mysql_query($query);
   
   $c="
   <div class='sectHead' style='width:770px;border-bottom:1px solid #aaaaaa;color:#444444'>Registry Search Results</div>
   	<div style='margin-top:5px;padding-bottom:5px;max-height:100px;overflow:auto;width:770px;border-bottom:1px solid #aaaaaa;'>
   	 <table width='725' cellpadding='0' style='background-color:transparent;'><tbody style='margin-left:10px'>";
   	
   	$c.="<tr>
   			<span style='display:none;' id='search-variables' data-fname='$fname' data-lname='$lname' data-month='$smonth' data-year='$syear'></span>
   			<td align='left'><a class='sort' data-direction='$rs1'>Registrant</a></td>
   			<td align='left'><a class='sort' data-direction='$rs2'>Co-Registrant</a></td>
   			<td><a class='sort' data-direction='$rs5'>Event Type</a></td>
   			<td><a class='sort' data-direction='$rs3'>Event Date</a></td>
   			<td><a class='sort' data-direction='$rs4'>Location</a></td>
			<td></td>
   		</tr>";
   		

   if(mysql_numrows($result)>0){
	  
   
   	$numrows=mysql_numrows($result);
  
		while($row=mysql_fetch_assoc($result)){
			extract($row);
		
			$c.="<tr>
				<td align='left'>$rfname $rlname</td>
				<td align='left'>$crfname $crlname</td>
				<td>$eventArray[$event]</td>
				<td>$wedmonth"."/".$wedday."/".$wedyear."</td>
				<td>$rstate</td>
			 	<td><a class='view-registry' data-id='$id'>View Registry</a></td>
			 </tr>";
		}		 
	
	}
	
	else{
	$c.="<tr><td>No Results found</td></tr>";
	}
  
}


else{
// return wedding registry items
	
	$bgs=array('f'=>'#ffeedd','h'=>'#ffdddd','bs'=>'#dedeff','gift'=>'#dedeff','m'=>'#ffeedd','s'=>'#ffdddd','xm'=>'#ddffdd','j'=>'#6699CC');
	$staticcats=array('sp'=>'Flatware','fcs'=>'Flatware','f'=>'Flatware','ps'=>'Flatware','h'=>'Hollowware','bs'=>'Baby Silver','j'=>'Jewelry','stp'=>'SilverStorage','cp'=>'SilverCare','xm'=>'Christmas');
	$keyCat=array(''=>'All','sp'=>'Serving Pieces','ps'=>'Flatware','fcs'=>'Complete Sets','f'=>'Flatware','h'=>'Hollowware','bs'=>'Baby Silver','j'=>"Jewelry",'cp'=>'SilverCare','stp'=>'SilverStorage','xm'=>'Christmas');

	

	$query="SELECT * FROM weddingRegistries WHERE id=$regID";
	$result=mysql_query($query);
	$row=mysql_fetch_assoc($result);

        $tableHead=$edit?"Items in your registry:":"Items in $row[rfname] $row[rlname]"."'s Registry:";

$c.="
<div style='font-size:14px;width:770px;color:#444444;border-bottom:1px solid #aaaaaa;'>$tableHead</div>
	<div style='width:770px;max-height:400px;overflow:auto;border-bottom:1px solid #aaaaaa;'>
		<form>
			<table width='740'>";

 

$c.="          <tr>
   					<td></td>
   					<td></td>
   					<td></td>
   					<td></td>
   					<td>Description</td>
   					<td>Retail</td>
   					<td>Requested</td>
   					<td>Purchased</td>
   					<td nowrap='true'>In Stock</td>
   					<td colspan='2'></td>
   				</tr>";


 if($edit==0 or $edit==""){
    /*$c.="<!-- GIFT CARD ROW HERE -->
                             <tr>
                                        <!-- <td colspan='4'></td> -->
                                        <td colspan=\"8\"><span class=\"giftCard\"><sup>New!</sup>As You Like It Silver Shop Gift Card (minimum amount $25):</span></td>
                                        <td colspan=\"2\" style=\"text-align:right\"><input name=\"giftCardAmt\" id=\"giftCardAmt\" type=\"text\" size=\"8\" value=\"25.00\"></td>
                                        <td><input type='button' value='Add to Cart' onclick=\"javascript:if(validCardAmount()==1){location='addItem.php?id=000000&amount='+this.form.giftCardAmt.value+'&quantity=1&regID=$regID&temp=f'}else{alert('Please enter an amount great than or equal to $25.00 for the gift card.')}\">
                             </tr>";*/

    }

$query="SELECT inventory.id,inventory.category,inventory.pattern,inventory.brand,inventory.quantity,inventory.monogram,inventory.image,
		inventory.dimension,inventory.item,inventory.retail,inventory.sale,
		weddingRegistryItems.id as wrID, weddingRegistryItems.qtyRequested,weddingRegistryItems.qtyPurchased,
 if(abs(quantity)>0,if(weddingRegistryItems.qtyPurchased<weddingRegistryItems.qtyRequested,1,2),if(qtyPurchased<qtyRequested,3,1)) as primarySort
		FROM inventory, weddingRegistryItems 
		WHERE weddingRegistryItems.itemID=inventory.id and weddingRegistryItems.regID=$regID 
		ORDER BY primarySort ASC, inventory.quantity DESC, inventory.pattern,inventory.brand, inventory.item";	


$result=mysql_query($query);

if(mysql_num_rows($result)>0){	    
	while($row=mysql_fetch_assoc($result)){		 
			 			
		$row[dimension]=str_replace("\\",'',$row[dimension]); 
		$row[category]=strtolower($row[category]);

                $instock=getQuantity($row[pattern],$row[brand],$row[item],$row[quantity]);

		$price=($row[sale])?$row[sale]:$row[retail];
			
		if (!$row[image] || !file_exists("productImages/_BG/$row[image]")) { 
			
			$handle="/HANDLES/".strtoupper(substr($row[pattern],0,1))."/".strtoupper($row[pattern])." ". str_replace("&", "AND",strtoupper($row[brand])).".jpg";
			$testhandle="/home/asyoulik/public_html".$handle;
			$row[image]=(file_exists($testhandle))? $handle:'/productImages/_TN/noimage_th.jpg'; 
			}  
		 	else {
				 $row[image]='/productImages/_TN/'.substr($row[image],0,-4)."_TN.jpg"; 
			}
		
		$monogram=$row[monogram]?"(monogrammed)":'';
		
		$statURL = staticURL($row[pattern],$row[brand],$staticcats[$row[category]],$row[category],$row[item],$row[id]);
		$pb=$row[pattern]?"$row[pattern] by $row[brand]":$row[brand];
		$wt=($row[weight])?"$row[weight] troy oz":'';
		//<td width='10'><img src='/images/blank.gif' width='10' height='1'></td>
		//<td $bgcolor><p class=$class><b>$row[dimension]".(($row[weight])?"<br>$row[weight] troy oz":'')."</b></td>
		
                $c.= "<tr id='tablerow$row[wrID]' $bgcolor>
			<td colspan='2' width='100' height=50 align='center'><a href='/showItem.php?product=$row[id]' style='text-decoration:none' class=$class><img src='{$row[image]}' border=0 width=50><br><font size=-2>(click for details)</font></a></td>
			<td width='10'><img src='/images/blank.gif' width='10' height='1'></td>
			<td width='10' $bgcolor><img src='/images/blank.gif' width='10' height='1'></td>
			<td $bgcolor><p class=$class><b><a href='/showItem.php?product=$row[id]' class=$class>$pb $row[item]</a></b></p></td>";
		//<br><span style='font-size:12px\">$monogram $row[dimension] $wt</span>
		
		//$qr=$edit?"<input style=\"text-align:center;\" size=\"2\" type=\"text\" id=\"$row[wrID]\" name=\"quantity$row[wrID]\" value=\"$row[qtyRequested]\">":$row[qtyRequested];
		
		if($edit==1){
                  $qr="<input style='text-align:center;' size='2' type='text' id='$row[wrID]' name='quantity$row[wrID]' value='$row[qtyRequested]'>";
		  $qb="<input type='button' value='Update' onclick=\"javascript:updateRegistry($regID,$row[wrID],'')\">";
		  $button="<a href=\"javascript:updateRegistry($regID,$row[wrID],'delete')\" style='text-decoration:none'><span style='font-size:8pt'>Remove</span></a>";
		}
		else{
		$qr=$row[qtyRequested];
		 if($row[quantity]!=0){
		     if($row[qtyPurchased]<$row[qtyRequested]){
                         $qb="<input type='text' value='1' size='2' name=quantity$row[id] id='$row[id]'>";
		         $button = "<input type='button' value='Add to Cart' onclick=\"javascript:location='addItem.php?id=$row[id]&quantity='+this.form.quantity$row[id].value+'&regID=$regID&temp=f'\">";
		     }
                     else{
                        $qb="";
                        $button="<span style='font-size:10pt;color:#888888;'>Completed</span>";
                     }
                 
                }
		 else{
                   $qb="";
                     if($row[qtyPurchased]>=$row[qtyRequested]){ $button="<span style='font-size:10pt;color:#888888;'>Completed</span>";}
		     else{$button="<span style='font-size:10pt;color:#888888;'>Out of stock</span>";}
		 }
		
                }
	
		$c.="
				<td $bgcolor ><p class=$class><b>\$$price</b></p></td>
				<td $bgcolor align='center'>$qr</td>
				<td $bgcolor align='center'>$row[qtyPurchased]</td>
				<td $bgcolor align=center>$instock</td>
				<td $bgcolor>$qb</td>
				<td $bgcolor valign='middle' align='left' width='50'>$button</td>
			</tr>";
		
		
	}

}
	

//<td $bgcolor valign=\"center\" align=right><p class=$class><b><input type='button' value='Add' onClick=\"javascript:location='addItem.php?id=$row[id]&quantity='+this.form.quantity$row[id].value+'&temp=$template'\">&nbsp;</b></p></td>

else{

	$c.="<tr>
			<td>No Results found</td>
		</tr>";
}

$c.="
	</table>
  </form>  
</div>";

}




$c.="</tbody></table>";	

echo $c;

}

?>
