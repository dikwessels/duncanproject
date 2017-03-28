<?php




main();

function createFile($fname,$conn_id,$content){
		
		$f=fopen('temp/temp.html','w');
		fputs($f,$content);
		fclose($f);
		
	    $msgSuccess="Successfully created static search results"; 
	    $msgFail="Could not create static search file";
	       

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

function main(){


include('/home/asyoulik/connect/mysql_connect.php');
include('/home/asyoulik/connect/ftp_connect.php');
$content='
<div class="row border-bottom">
	<div class="cell sixteenColumns" style="padding-left:15px">
		<h3>Search our inventory:</h3>
	</div>
</div>
	<form>
		<div class="row"></div>
		<div class="row">
					<div class="cell twoColumns"></div>
					<div class="cell threeColumns">
						Pattern:
					</div>
					<div class="cell elevenColumns">
						<select class="large-input" name="pattern" id="selectPattern" onchange="showPatternLink()">';
					
$query=mysql_query('SELECT DISTINCT CONCAT(ucase(pattern)," BY ",IF(brand<>"",ucase(brand),"UNKNOWN")) as p,ucase(pattern) as pvalue FROM inventory WHERE quantity!=0 AND pattern!="" ORDER BY pattern,brand');

		$content.= '<option value=""></option>';
		$content.= '<option value="NON">NON-PATTERNED</option>';
		
		while ($r=mysql_fetch_assoc($query)) {
	    	$p=$r['p'];
			$pvalue=$r['pvalue'];
			$selected=$pvalue==urldecode($pattern)?'selected="selected"':"";
			$content.= "<option value='$pvalue' $selected>$p</option>\n";
		}
								
$content.='</select><span id="patternLink"></span>
								
							</div>
						</div>
		<div class="row">
			<div class="cell twoColumns"></div>
			<div class="cell threeColumns">
								Maker:
			</div>
			<div class="cell elevenColumns">
				<select class="large-input" name="brand" id="selectBrand">';

$query=mysql_query("SELECT distinct(ucase(brand)) as b from inventory where quantity!=0 AND brand!='' order by b");
		
		$content.= '<option value=""></option>';
		
		while ($r=mysql_fetch_assoc($query)) {
	    	$b=$r['b'];
	    	
			$selected=$b==urldecode($brand)?'selected="selected"':'';
		
			$content.= "<option value='$b' $selected>$b</option>\n";
		}
								
$content.='</select>
			</div>
			</div>
						
			<div class="row">
			<div class="cell twoColumns"></div>
				<div class="cell threeColumns">
					Category:
				</div>
				<div class="cell elevenColumns">
					<select class="medium-input" name="category" id="selectCategory">';


	$catArray = array("BS"=>"Baby Silver",
					"CP"=>"Cleaning Products",
					"F"=>"Flatware",
					"CL"=>"Collectibles",
					"FCS"=>"Complete Sets",
					"XM"=>"Christmas Ornaments",
					"H"=>"Hollowware",
					"J"=>"Jewelry",
					"SP"=>"Serving Pieces",
					"STP"=>"Storage Products"
					);
		
		$content.= '<option value=""></option>';
	
		foreach($catArray as $k=>$v){
			$selected=$k==$category?'selected="selected"':"";
			$content.= "<option value='$k' $selected>$v</option>\n";
		}
								
$content.='</select>
			</div>
			</div>
			<div class="row">
			<div class="cell twoColumns"></div>
			<div class="cell threeColumns">
				Item:
			</div>
			<div class="cell elevenColumns">
				<input class="large-input" type="text" name="item" id="selectItem">';
			/*
								<select class=\"reg\" name=\"item\" id=\"selectItem\">";

		$q=mysql_query("SELECT distinct(ucase(item)) as i from inventory where item <> '' and item IS NOT NULL order by item");
		
		$content.= "<option value=\"\"></option>";
		
		while ($r=mysql_fetch_assoc($q)) {
		$i=$r['i'];
		
		$urli=urlencode($i);
		$selected=$i==stripslashes($item)?"selected=\"selected\"":"";
			$content.= "<option value=\"$urli\" $selected >$i</option></select>";
		
		}
								*/
$content.='</div>
		</div>
		<div class="row border-bottom">
		<div class="cell twoColumns"></div>
			<div class="cell threeColumns">
				Retail Price:
			</div>
			<div class="cell threeColumns">
				<input class="small-input" type="text" size="10" name="minretail" id="minretail">
			</div>
			<div class="cell threeColumns">
				<input class="small-input" type="text" size="10" name="maxretail" id="maxretail">
			</div>
		</div>
		<div class="row centered">
			<div class="cell sixteenColumns">
				<input type="button" title="Search Inventory" name="Search" onclick="javascript:searchInventory()" value="Search Inventory">
			</div>
			</div>
			<div class="row border-bottom">
			<div class="cell sixteenColumns"></div>
			</div>
';
echo $content;

createFile("/home/asyoulik/public_html/wedRegInvSearch.html",$conn_id,$content);


}






?>