<?php
ini_set("display",1);

//scandir returns all folders and files
 $dir =scandir('/home/asyoulik/public_html/silver-flatware/');

$subdir=preg_grep("[\D*silver.html]", $dir);
print_r($dir);
echo "<br>";
print_r($subdir);

/*foreach($dir as $filename){
//echo $filename."<br>";
	if(preg_match("[\D*silver.html]", $filename)){
		echo "$filename<br>";
		}
	}
//print_r($dir);
 
/* foreach($dir as $file){
	 if(preg_match("-sterling-silver.html", $file)){
		 echo $file."<br>";
	 }
 }
//	print_r(preg_filter(, , ))
 
 
 //print_r($dir);
 
 //ftp_delete(, )
/* foreach($dir as $directory){
	 while($listing=getlisting($listing,$directory)){
		 
	 }
 }
 
 
 getlisting($l,$d){
   
   if($d!="."&&$d!=".."){
	    
   }	 
	 return $l;
 }


/*
 first iteration -
 		 - 
*/ 
?>
