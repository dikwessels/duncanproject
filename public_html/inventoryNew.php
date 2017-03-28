<html>

<head>
<title>As You Like It Silver Shop</title>

<style>
  .inventoryEntry{
    display:block;
    position:relative;
    width:900px;
    height:25px;
  }
 
 .dataField{
  display:inline;
  position:absolute;
  width:auto;  
 }

#filterCriteria{
  border:1px solid #aaa;
  padding:10px;
  position:absolute;
  display:inline;
  left:10px;
  top:10px;
  width:130px;
}

#searchResults{
  border:1px solid #aaa;
  display:inline;
  left:170px;
  padding:10px;
  position:absolute;
  width:900px;
  top:10px;
}

</style>

<script type="text/javascript" language="javascript">


function showData(){

<?php 

include('/connect/mysql_connect.php');

$sql="SELECT * FROM inventory WHERE category='f' ORDER BY brand LIMIT 5";

  $result=mysql_query($sql);
  $i=1;

  $javascript="var brands={};\nbrands={\n\t";

  while($row=mysql_fetch_assoc($result)){

   extract($row);
   $item=str_replace("'","",$item);
   $javascript.="$productId:{productId:$productId,pattern:$pattern,brand:$brand,item:$item},\n\t";
 
  }

  $javascript=substr($javascript,0,strlen($javascript)-3);

  $javascript.="\n\n};";

 //echo $javascript;

?>

 alert(77650.length);

}

 function filterResults(filter){
  var idText= new String();
  var children=new Array();
  var i = 0;
  children=document.getElementById('searchResults').childNodes;
  
  //alert(children.length);
  
  if(filter){
  var patt=new RegExp(filter,'gi');
  
   for(i=0;i<children.length;i++){
    idText=children[i].id;
    if(idText){
      //alert(idText);
      if(!idText.match(patt)){
       document.getElementById(idText).style.visibility='hidden';
       document.getElementById(idText).style.height='0px';
      }
   }

   }
  }
  else{
   for(i=0;i<children.length;i++){
    if(children[i].id){
     document.getElementById(children[i].id).style.visibility='visible';
    document.getElementById(children[i].id).style.height='25px';
    } 
  }
  }

 }
</script>

</head>
<body>

<div id="filterCriteria">
  <input type="button" onclick="filterResults('GorhamChantilly');" value="Filter">
  <input type="button" onclick="filterResults();" value="Remove Filter">
  <input size="15">
  <input name="productId" type="button" onclick="showData()" value="Show Item Info">

<?php
 $sql="SELECT DISTINCT brand FROM inventory WHERE category='f' ORDER BY ";
?>
</div>

<div id="searchResults">

<?php 
/*
 $sql="SELECT * FROM inventory WHERE category='f'";
 $result=mysql_query($sql);

 $i=1;
 while($row=mysql_fetch_assoc($result)){
  extract($row);
  $it=str_replace("/","",$item);
  
  echo "<div class=\"inventoryEntry\" style=\"display:block\" id=\"$it$brand$pattern$price$id\">
    <div class=\"dataField\" style=\"left:0px;width:300px\">$i $pattern by $brand</div>
    <div class=\"dataField\" style=\"left:310px\">$item</div>
  </div>";

  $i++;
 }
*/
?>

</div>
</body>
</html>