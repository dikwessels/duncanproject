<?php 
//ini_set("display_errors","1");

$searchForm="<form name='search' action='//www.asyoulikeitsilvershop.com/productSearch.php'>
			<input type=hidden value='$cat' name=category>
			<!--optional-description-->
			<div class='row'>
			 <div class='cell sixteenColumns'>
			  Search $staticcats[$category] at As You Like It Silver Shop:
			 </div>
			</div>
			
			<div class='row'>
				<div class='cell sixColumns leftAlign'>
				<select class='categorySearchSelect' name='pattern' onChange='populate(0,this.options[this.selectedIndex].value)'>
				<option value=''>All Patterns";
				
$searchForm.=file_get_contents("/home/asyoulik/public_html/includes/{$category}Options.inc");
				
				$searchForm.="</select>
				</div>
				<div class='cell sixColumns leftAlign '>
					<select class='categorySearchSelect' name='brand'> 
					<option value='all' selected >All Manufacturers</select>
				</div>
				<div class='cell threeColumns leftAlign'>
					<input class='searchResultAddButton' type='submit' value='Search'>
				</div>
			</div>
			</form>";

return $searchForm;

?>
