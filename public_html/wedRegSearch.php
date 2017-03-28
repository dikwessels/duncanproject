<?php

include("/home/asyoulik/connect/mysql_connect.php");

if($_POST || $_GET){
	extract($_POST);
	extract($_GET);
	
	
//echo "$brand $pattern $item $category";

$w="WHERE id!=0 and retail>0";


if($pattern || $brand || $category || $item || $minretail || $maxretail){

	if($pattern && $pattern!="NON-PATTERNED"){
	$pattern=urldecode($pattern);
		$w.=" AND pattern=\"$pattern\"";
	}

	if($brand){
	 $brand=urldecode($brand);
		$w.=" AND brand=\"$brand\"";
	}

	if($category){
		$w.=" AND category=\"". strtolower($category) ."\"";
	}

	if($item){
		$item=urldecode($item);
		if(strchr($item, "O CLOCK")>0){
 		str_replace($item, "O ", "O'");
	}

	$w.=" AND (item=\"".urldecode($item)."\" OR instr(item,\"$item\")>0 or item LIKE \"$item\")";
}

if($minretail){
$w.=" AND retail>=$minretail";
}
if($maxretail){
$w.=" AND retail <=$maxretail";
}

$bgs=array('f'=>'#ffeedd','h'=>'#ffdddd','bs'=>'#dedeff','gift'=>'#dedeff','m'=>'#ffeedd','s'=>'#ffdddd','xm'=>'#ddffdd','j'=>'#6699CC');
$staticcats=array('sp'=>'Flatware','fcs'=>'Flatware','f'=>'Flatware','ps'=>'Flatware','h'=>'Hollowware','bs'=>'Baby Silver','j'=>'Jewelry','stp'=>'SilverStorage','cp'=>'SilverCare','xm'=>'Christmas');
$keyCat=array(''=>'All','sp'=>'Serving Pieces','ps'=>'Flatware','fcs'=>'Complete Sets','f'=>'Flatware','h'=>'Hollowware','bs'=>'Baby Silver','j'=>"Jewelry",'cp'=>'SilverCare','stp'=>'SilverStorage','xm'=>'Christmas');

$query="SELECT * FROM inventory $w ORDER BY quantity DESC, item,brand,pattern";
echo "<input type='hidden' value=''>";

$result=mysql_query($query);
   
$c="<table width=\"740\" cellpadding=\"0\" style=\"background-color:transparent;\">";

$c.="<tr>
   			<td></td>
   			<td></td>
   			<td></td>
   			<td></td>
   			<td>Item</td>
   			<td>Retail</td>
   			<td width=\"40\" nowrap=\"true\">In Stock</td>
   			<td colspan=\"2\"></td>
   </tr>";
   		
 
   if(mysql_numrows($result)>0){
  
   
   	$numrows=mysql_numrows($result);
  
	while($row=mysql_fetch_assoc($result)){

		$row[dimension]=str_replace("\\",'',$row[dimension]); 
		$row[category]=strtolower($row[category]);
		$instock=abs($row[quantity]);;
		$price=($row[sale])?$row[sale]:$row[retail];
	
		if (!$row[image] || !file_exists("productImages/_BG/$row[image]")) { 
			$handle="/HANDLES/".strtoupper(substr($row[pattern],0,1))."/".strtoupper($row[pattern])." ".strtoupper($row[brand]).".jpg";
			$testhandle="/home/asyoulik/public_html".$handle;
			$row[image]=(file_exists($testhandle))? $handle:'/productImages/_TN/noimage_th.jpg'; 
		}  
		 else {
			 $row[image]='/productImages/_TN/'.substr($row[image],0,-4)."_TN.jpg"; 
		}
		
		$monogram=$row[monogram]?"(monogrammed)":'';
		
		$pb=$row[pattern]?"$row[pattern] by $row[brand]":$row[brand];
		$wt=($row[weight])?"$row[weight] troy oz":'';
		
		//<td $bgcolor><p class=$class><b>$row[dimension]".(($row[weight])?"<br>$row[weight] troy oz":'')."</b></td>
		$c.= "<tr $bgcolor>
			<td width='10'><img src='/images/blank.gif' width='10' height='1'></td>
			<td width='100' height=100 align='center'><a href=\"/showItem.php?product=".$row[id]."\" style=\"text-decoration:none\" class=$class><img src='{$row[image]}' border=0 width=75><br><font size=-2>(click for details)</font></a></td>
			<td width='10'><img src='/images/blank.gif' width='10' height='1'></td>
			<td width='10' $bgcolor><img src='/images/blank.gif' width='10' height='1'></td>
			<td $bgcolor><p class=$class><b><a href=\"/showItem.php?product=".$row[id]."\" class=$class>$pb $row[item]</a></b><br><span style=\"font-size:12px\">$monogram $row[dimension] $wt</span></p></td>";
			
			
		$brandfname		= "nofile.html";
		$patternfname	= "nofile.html";
		$pattern		= $row[pattern];
		$brand			= $row[brand];
		$item			= str_replace("/","", $row[item]);

		$category=$row[category];
		
		if($row[brand]!=""){
		//	$brandfname=createFileName("search/","All","",$row[brand]);
		}
		
		if($row[pattern]!=""){
			//$patternfname=createFileName("search/","All",$row[pattern],$row[brand]);
		}
	
		if(file_exists("/home/asyoulik/public_html/$brandfname")){
			$bfilelink="http://www.asyoulikeitsilvershop.com/$brandfname";
		}
		
		else{
			}
		
		if(file_exists("/home/asyoulik/public_html/$patternfname")){
			 $pfilelink="http://www.asyoulikeitsilvershop.com/$patternfname";
		}
		else{
				}

		$c.="
			<td $bgcolor ><p class=$class><b>\$$price</b></p></td>
			<td $bgcolor align=center>$instock</td>
			<td $bgcolor ><input type='text' value='1' size=2 name=quantity$row[id] id=\"$row[id]\"></td>
			<td $bgcolor valign=\"center\" align=right><p class=$class><b><form><input type='button' value='Add to Registry' onclick=\"javascript:additem($row[id],0,0,1)\"></form></b></p></td>
			</tr>";
		
		
	}
	
	

}


else{
	$c.="<tr><td>No Results found</td></tr>";
}

$c.="	</tbody>
	</table>
";	

echo $c;

}


}





?>