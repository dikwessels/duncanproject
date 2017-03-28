<?php

function getImageTitle($p,$b,$i,$m){
  if($p){
    $f=$p;
    if($b){$f.=" by $b";}
  }
  
  else{
    if($b){
     $f=$b;  
    }
  }

  if($f){
   $f.=" $i";
  }
  else
  {
   $f=$i;
  } 
  
 if($m){$f.=", monogrammed";}
 $f=strtolower($f);

 return $f;

}

function makePatternName($pattern,$brand){
 $j=0;

 $patName=ucwords(strtolower($pattern));

                if($patName){
                  //$patName.=" by ";
                  if($brand){
                  $patName.=" by ".ucwords(strtolower($brand));
                  }
                  else{
	                 $bQuery="SELECT DISTINCT brand FROM inventory WHERE pattern=\"$pattern\""; 
	                 $bResult=mysql_query($bQuery);
	                 if(mysql_num_rows($bResult)>0){
		                 $patName.= " by ";
		                 while($bRow=mysql_fetch_assoc($bResult)){
			     			
			     			if($j>0){$patName.=", ";}
			     			
			     			extract($bRow);            
			                $patName.=ucwords(strtolower($brand)); 
			                $j++; 
		                 }
		                 
	                 }

                  }
                }
                else{
                    if($brand && $brand!="UNKNOWN"){
                      $patName=ucwords(strtolower($brand));
                    }
                }
                
 return $patName;

}

?>