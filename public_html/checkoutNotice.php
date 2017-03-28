<?php 
	
	$startTime 	= "2:00pm";
	
	$endTime 	= 	"6:00pm";
	
	$noticeDate = "March 8th, 2016";
	
	$msg	= '<div class="row">
					<div class="sixteenColumns">
					<strong>Notice:</strong>
						Our checkout system is undergoing maintenance and upgrades and will be unavailable from ';
						
	$msg 	.=  $startTime.' EST until '.$endTime.' EST '.$noticeDate.' We apologize for any inconvenience.<br>
				If you would like to purchase something from our website during this time, please call us at 1-800-828-2311
				</div>
			
			</div>';

	echo $msg;
	
?>