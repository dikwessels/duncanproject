
<?php 
	
	$defaultSurcharges=array(
		"Ground (8-10 days)" 	=>  4,
		"3-Day Select" 			=>	10,
		"2nd Day Air"			=>  27,
		"Next-Day Air"			=>  53
	);

$handling = 5;
$i = 0;

foreach( $defaultSurcharges as $k => $v){
	$selected = "";

	if($i == 0){$selected = "selected = 'selected'";}

	$defaultShippingOptions.="<option $selected value='$v' data-method='$i'>$k: $".number_format($v+$handling,2)."</option>";
	
	$i++;
}


echo $defaultShippingOptions;