<? 
include_once("/home/asyoulik/connect/mysql_connect.php");

extract($_POST);
extract($_GET);

function renameImageFiles($im,$pattern,$brand,$item,$monogram,$productId){

  if($pattern){
    $newImage=$pattern;
    if($brand){$newImage.=" BY $brand";}
  }
  else{
   if($brand){
     $newImage=$brand;
   }
  }

  if($newImage){
   $newImage=$newImage." $item";
  }
  else
  {
   $newImage=$item;
  }
     
  
   if($monogram==1){$newImage.=" MONOGRAMMED";} 
  
   $newImage=str_replace(" ","-",$newImage);
   $newImage=str_replace("&","AND",$newImage);

 return $newImage;

}

 $query="SELECT * FROM inventory "; 

 if($pid){$query.=" WHERE productId=$pid ";}

 $query.=" ORDER BY id";

 $result=mysql_query($query);

 while($row=mysql_fetch_assoc($result)){
 
  extract($row);
  if($image){

   $oldImage=$image;
   $oldSM=substr($oldImage,0,-4)."_SM.jpg";
   $oldTN=substr($oldImage,0,-4)."_TN.jpg";

   $newImage= renameImageFiles($image,$pattern,$brand,$item,$monogram,$productId);

   $newSM=$newImage."_SM.jpg";
   $newTN=$newImage."_TN.jpg";

   echo "<img src=\"http://www.asyoulikeitsilvershop.com/productImages/_SM/$oldSM\">
         <br>
         <img src=\"http://www.asyoulikeitsilvershop.com/productImages/_TN/$oldTN\">
         Item $productId current image name is $oldImage, new image name is $newImage.jpg
         <br><br>";

   $newImage.=".jpg";


   $success= rename("/home/asyoulik/public_html/productImages/_BG/$oldImage","/home/asyoulik/public_html/productImages/_BG/$newImage");
   
//success if 1
   if($success){

   echo "Renamed /home/asyoulik/public_html/productImages/_BG/$oldImage to /home/asyoulik/public_html/productImages/_BG/$newImage<br>";
   
   $success= rename("/home/asyoulik/public_html/productImages/_SM/$oldSM","/home/asyoulik/public_html/productImages/_SM/$newSM");
 
//success if 2   
     if($success){
     echo "Renamed /home/asyoulik/public_html/productImages/_SM/$oldSM to /home/asyoulik/public_html/productImages/_SM/$newSM<br>";

     $success=rename("/home/asyoulik/public_html/productImages/_TN/$oldTN","/home/asyoulik/public_html/productImages/_TN/$newTN");
     
    //success if 3      
        if($success){
         echo "Renamed /home/asyoulik/public_html/productImages/_TN/$oldTN to /home/asyoulik/public_html/productImages/_TN/$newTN<br>";
         echo "Updating inventory image data...<br>";

         $result=mysql_query("UPDATE inventory SET image='$newImage' WHERE id='$id'");
         if(mysql_affected_rows()>0){
            
           echo "Table data updated.<br>
            <img src=\"http://www.asyoulikeitsilvershop.com/productImages/_BG/$newImage\">
            <br><img src=\"http://www.asyoulikeitsilvershop.com/productImages/_SM/$newSM\">
            <br><img src=\"http://www.asyoulikeitsilvershop.com/productImages/_TN/$newTN\">";
        }//end if affected    
    }
    else{
          echo "Error renaming thumbnail, undoing other file name changes...<br>";
          rename("/home/asyoulik/public_html/productImages/_BG/$newImage","/home/asyoulik/public_html/productImages/_BG/$oldImage");
          rename("/home/asyoulik/public_html/productImages/_SM/$newSM","/home/asyoulik/public_html/productImages/_SM/$oldSM");
    }// end success if 3
   }

   else{
       echo "Unable to rename small image file, undoing changes";
       rename("/home/asyoulik/public_html/productImages/_BG/$newImage","/home/asyoulik/public_html/productImages/_BG/$oldImage");
   }// end success if 2  
  }//end success if 1  
 }//end if image
}//end while
?>
