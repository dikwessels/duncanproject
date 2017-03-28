<?php
	
/*
	categoryLinks.php
	Author:			Michael Wagner
	Date:			6/1/12
	Description:	Creates category menu for use on all pages
	Updates:		3/15/16
					- removed dropdown links as they were not being used
		
*/

$catMenuContent="<div class=\"categoryLinksContainerSub clearfix\">
      <ul class=\"dropdown dropdown-horizontal\">";

$catMenuContent.=catLinksMain();

return $catMenuContent;

function catLinksMain(){

$localcatArray=array(
				"f"=>"Flatware",
                "h"=>"Hollowware",
                "j"=>"Jewelry",
                "bs"=>"Baby Silver",
                "cl"=>"Collectibles",
                "xm"=>"Christmas Items",
                "cs"=>"Coin Silver",
                "g"=>"Gift Registry");
                
global $homePage;

foreach($localcatArray as $catk=>$catv){
    $nofollow="";
    
    if($catk=="g"){$nofollow="rel='nofollow'";}

	$catC.="<li><a $nofollow class='red' href='$homePage[$catk]'>$catv</a>";
	
	$catC.="</li>";

}
$catC.="
<li><a href='/silver-patterns/' class='red'>Pattern Guide</a></li>";
$catC.="</ul></div>";

return $catC;

}



?>