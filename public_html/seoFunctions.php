<?php

function updateKeywords($cat,$pattern,$brand,$item,$monogram){
 
 global $lastBrand;
 global $lastPattern;
 global $lastItem;
 global $staticcats;

 $sterling="sterling";
 $silver="silver";
 $keywords="";
 
 $fullCat=$staticcats[$cat];


     if($pattern && $pattern!=$lastPattern){
        if(strtolower($pattern)=="christofle"){$sterling="";$silver="silverplate";}
        
        $keywords.="$pattern $silver,$pattern $sterling $silver,$pattern silverware,
$pattern $sterling $silver silverware,$pattern $sterling $silver silver ware,
$pattern $silver silverware,$pattern $silver silver ware,$pattern $fullCat,
$pattern $silver $fullCat,$pattern $sterling $silver $fullCat,$silver $fullCat $patternName,
$sterling $silver $fullCat $patternName,";

       
        $lastPattern=$pattern;
     } 
              
     if($brand && $brand!=$lastBrand){

       $keywords.=" $brand $silver,$brand $silver silverware,$brand $silver silver ware,$brand $sterling $silver,
$brand $sterling $silver silverware,$brand $sterling $silver silver ware,$brand silverware,
$brand silver ware,$brand $fullCat,$brand $silver $fullCat,$brand $sterling $silver $fullCat,
$brand $silver silverware $fullCat,$brand $silver silver ware $fullCat,$brand $sterling $silver silverware $fullCat,
$brand $sterling $silver silver ware $fullCat,$silver $brand $fullCat,$sterling $brand $fullCat,
$sterling $silver $brand $fullCat,$fullCat $brand,$silver $fullCat $brand,$sterling $silver $fullCat $brand,";

       $lastBrand=$brand;
     }  

     if($item!=$lastItem){

        $keywords.="$pattern $item $monogram,$silver $pattern $item $monogram,
$sterling $silver $pattern $item $monogram,$pattern $silver $item $monogram,$pattern $sterling $silver $item $monogram,
$sterling $silver $monogram $item $pattern,$brand $item $monogram,$silver $brand $item $monogram,
$sterling $silver $brand $item $monogram,";

    
        $keywords.="$sterling $silver $item,
                        $sterling $silver $monogram $item,";

        if(substr($item,-1)!="s"){
          $items=$item."s";
          $keywords.="$sterling $silver $items,";
        }
        
        $lastItem=$item;
     }

 return $keywords;

}

?>