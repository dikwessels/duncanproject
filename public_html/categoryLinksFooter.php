<?php
	
//	include_once("/home/asyoulik/connect/mysql_pdo_connect.php");
//	include_once("/home/asyoulik/public_html/categoryArrays.php");
//	include_once("/home/asyoulik/public_html/staticHTMLFunctions.php");

include_once("../connect/mysql_pdo_connect.php");
include_once("categoryArrays.php");
include_once("staticHTMLFunctions.php");

$catMenuContent.=catLinksMain();

echo $catMenuContent;


function catLinksMain(){

$localcatArray=array(
				"f"=>"Flatware",
                "h"=>"Hollowware",
                "j"=>"Jewelry",
                "bs"=>"Baby Silver",
                "cl"=>"Collectibles");
                
global $homePage;

global $db;

$catC = "";
foreach($localcatArray as $catk=>$catv){
    $nofollow="";
    if($catk == "g"){$nofollow="rel='nofollow'";}
    
    $colPush = "";

    if( $catk == "f" ){
	    $first = "first";
	    $colPush = "push_one";
    }
    
    $first = $catk == "f"?"first":"";
	
	$catC .= "<div class='footerCatDiv  three columns $colPush $first'>
			<ul>
			<li class='nodecoration'>
				<a $nofollow class='footerCategoryLink large' title='$catv' href='$homePage[$catk]'>$catv</a>
			</li>";

	$stmt = "SELECT * from sideMenu where category='$catk' and NOT searchCategory";

	$query = $db->prepare($stmt);
	$query->execute();
	
	$catresult = $query->fetchAll();
	
	//$catresult=mysql_query($catquery);

	$num = count($catresult);

	if($num>0){

	$catS=($catk=='f')?"f,cs,sp,ps":$catk; 
     
     foreach($catresult as $catr){
	     
	 extract($catr);

	 $catsearchTerms=explode("&",$searchText);

	 $catsText="";
	 foreach($catsearchTerms as $cats){
	  
	   $values=explode("=", $cats);
	   if($values[0]=="brand"){$catbrand=$values[1];}
	   if($values[0]=="item"){$catitem=$values[1];}
	   if($values[0]=="pattern"){$catpattern=$values[1];}  	 
	   
	 } 
	   
	   //end foreach searchTerms
	   if($catbrand && $catbrand!="all"){$catsText.="&brand=$catbrand";}
	   if($catpattern && $catpattern!="all"){$catsText.="&pattern=$catpattern";}
	   if($catitem){$sText.="&searchItem=$catitem";}
	  
	   $testText=$text;
	   	if($catk=="f"){$testText="";}
	   		
	$catstaticFileName=createFileName("/home/asyoulik/public_html/",$catv,$catpattern,$catbrand,$testText);
	//$catC.=$catstaticFileName."<br>";
	
	$catC.="<!--$staticFileName, $text-->";
    
    //check to see if static file exists
    $catstaticFileName=checkForFile($catstaticFileName);
    
    if($catstaticFileName!=""){
	    $catfileLink=str_replace("/home/asyoulik/public_html/","",$catstaticFileName);
    }
    else{
        $scatText=str_replace("+","%2B",$catsText);  
	    $catfileLink="productSearch.php?category=".$category.$catsText;
	    $catfileLink.="&h2=".urlencode($text);
    }

	  $catC.="<li class='nodecoration'>
                 
                 	<a href='/$catfileLink' class='footerCategoryLink' style='font-size: 1rem;' title='$text' alt='$text'>$text</a>
                 
              </li>";
     }
	
     $catC.="</ul>";
   }

  $catC.="</div>";

}

return $catC;

}
?>
