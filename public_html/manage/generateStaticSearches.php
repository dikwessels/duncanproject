<?php

include("/home/asyoulik/connect/ftp_connect.php");
include("/home/asyoulik/connect/mysql_connect.php");
$c=0;
 $path="/home/asyoulik/public_html/"; 

$keyCat=array(''=>'All',
                'sp'=>'Serving Pieces',
                'ps'=>'Flatware',
                'fcs'=>'Complete Sets',
                'f'=>'Flatware',
                'h'=>'Hollowware',
                'bs'=>'Baby Silver',
                'j'=>"Jewelry",
                'cp'=>'SilverCare',
                'stp'=>'SilverStorage',
                'xm'=>'Christmas',
                'cl'=>'Collectibles');
                
$searchCat=array(""=>"",
 				"bs"=>"bs",
 				"cl"=>"cl",
 				"cp"=>"cp",
 				"f"=>"f",
 				"fcs"=>"f",
 				"h"=>"h",
 				"j"=>"j",
 				"sp"=>"sp",
 				"stp"=>"stp",
 				"xm"=>"xm");               


function createFile($fname,$conn_id,$content,$pattern,$brand){
		
		$f=fopen('temp/temp.html','w');
		fputs($f,$content);
		fclose($f);
		
	    $msgSuccess="Successfully created static search results"; 
	    $msgFail="Could not create static search file";
	       
	    
	    if($pattern!="" && $brand!=""){
	  	 $msgSuccess.=" for $pattern by $brand";
	     $msgFail.=" for $pattern by $brand";
	    }
	    else{
	    	if($brand!=""){
	    		$msgSuccess.=" for $brand";
	    		$msgFail.=" for $brand";
	    	}
		}	

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

       
function createFileName($v,$pattern,$brand,$item){
global $path;		
		$silverplateBrands = array('Christofle');	
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
		
		 if(strtolower($brand)=='christofle'){
		 	$keyword="-silverplate";
		}	
		
		$lowerCat=($v!="All")?"-".strtolower($v):"";


if($item){

if(substr($item, -1)!="s"){$item.="s";}
	$item=urldecode($item);
    $item=str_replace("+","-",$item);
    $item=str_replace(" ","-",$item);
    $item=str_replace("&","and", $item);    
	$fname=$path.$v."/".$item.".html";
	
}		
else{
		$fname=$path.$v."/".$fname."$keyword".$lowerCat.".html";
		$fname=str_replace(" ", "-",$fname);
		$fname=str_replace("--","-",$fname);
	}		
	return $fname;	
}      
 
 
function createSubFolder($conn_id,$fbrand,$fpattern){

global $path;

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
 
function itemPages(){
 global	$keyCat;

	$itemQuery="SELECT DISTINCT item, category FROM inventory WHERE quantity IS NOT NULL and quantity !=0 ORDER BY item";
		
	$itemResult=mysql_query($itemQuery);
	
	
	while($row=mysql_fetch_assoc($itemResult)){
		extract($row);
		
		$searchURL=($v=="Flatware")?"flatwareSearch.php":"productSearch.php";
		$searchURL="/home/asyoulik/public_html/includes/".$searchURL."?";
		if($category){$searchURL.="&category=".$category;}
		$searchURL.="&item=".urlencode($item);
		$searchResults=include($searchURL); 
		$filename=createFileName($keyCat[$category],"","",$item);
		echo $filename."<br>";
	}
	
	
	
} 
 
function main(){  

 global $keyCat;
 global $c;
            
	foreach($keyCat as $k=>$v){
		$bQuery="SELECT DISTINCT brand FROM inventory WHERE quantity IS NOT NULL and quantity!=0";
	
		if($k){$bQuery.=" AND category=\"$k\"";}
			$bQuery.=" ORDER BY brand ASC";
			
			$bResult=mysql_query($bQuery);
	 
			$searchURL=($v=="Flatware")?"flatwareSearch.php":"productSearch.php";
			$searchURL="/home/asyoulik/public_html/includes/".$searchURL."?";
			if($k){$searchURL.="&category=".$k;}
	 
			while($bRow=mysql_fetch_assoc($bResult)){
				extract($bRow);
				$includeURL=$searchURL."brand=".$brand;
	 	        $filename=createFileName($v,$pattern,$brand);   
	 	        //$searchResults=include($includeURL);   
	 	        //createFile($filename,$conn_id,$searchResults,$pattern,$brand);
	 	        

	 	        $pQuery="SELECT DISTINCT pattern FROM inventory WHERE quantity IS NOT NULL and quantity!=0 and brand=\"$brand\"";
	 	          
	 	        if($k){$pQuery.=" AND category=\"$k\"";}
	 	        $pQuery.=" ORDER BY pattern ASC";
	 	        //echo $pQuery."<br>";
	 	        $c++;
	 	        echo "Page ".$c.": ".$filename."<br>";
	 	        
	 	        $pResult=mysql_query($pQuery);
	 	        
	 	        while($pRow=mysql_fetch_assoc($pResult)){
		 	        extract($pRow);
		 	        $includeURL=$searchURL."brand=".$brand."&pattern=".$pattern;
		 	        $filename=createFileName($v,$pattern,$brand);
		 	        $c++;
		 	        echo "Page ".$c.": ".$filename."<br>";
		 	        //$searchResults=include($includeURL); 
		 	        //createFile($filename,$conn_id,$searchResults,$pattern,$brand);
		 	        
	 	        }//end sub pattern loop
	 	       
	 	       }//end brand loop
	
	
		}
}// end main


function makePatternName($pattern,$brand){

 $patName=ucwords(strtolower($pattern));

                if($patName){
                  $patName.=" by ";
                  $patName.=ucwords(strtolower($brand));
                }
                else{
                    if($brand && $brand!="UNKNOWN"){
                      $patName=ucwords(strtolower($brand));
                    }
                }
 return $patName;

}


function sideMenuPages(){
 global $keyCat;
 global $c;
 
 $sQuery="SELECT * FROM sideMenu";
 
 $sResult=mysql_query($sQuery);
 
 while($sRow=mysql_fetch_assoc($sResult)){
	 extract($sRow);
	 $brand="";
	 $pattern="";
	 $item="";
	 $searchTerms=explode("&",$searchText);

	 $searchURL=($keyCat[$category]=="Flatware")?"flatwareSearch.php":"productSearch.php";
	 $searchURL="/home/asyoulik/public_html/includes/".$searchURL."?";
	 $searchURL.="category=".$category;

	 foreach($searchTerms as $s){
	   $values=explode("=", $s);
	   if($values[0]=="brand"){$brand=$values[1];}
	   if($values[0]=="item"){$item=$values[1];}
	   if($values[0]=="pattern"){$pattern=$values[1];}  	 
	   
	   if($item){	
	   	$searchURL.="&brand=".$brand."&pattern=".$pattern."&item=".urlencode($item);
	    echo $searchURL."<br>";
	    $searchResults=include($searchURL);
	    if($searchResults){
	    echo $searchResults;
	    }
	    else{echo "No search results returned";}
	    exit();
	    
	    $filename=createFileName($keyCat[$category],$pattern,$brand,$item);
	    createSubFolder($conn_id,$brand,$pattern);
	    createFile($filename,$conn_id,$searchResults,$pattern,$brand);
	    $c++;
	    echo "Page ".$c.": ".$filename."<br>";
	   } 
	    		 	 
	 }//end foreach searchTerms
	 
 }//end while 		
	
}

//main();
sideMenuPages();

//itemPages();

?>