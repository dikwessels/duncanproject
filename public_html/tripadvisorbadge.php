<?php
	
$ch=curl_init('http://www.tripadvisor.com/Attraction_Review-g60864-d265717-Reviews-As_You_Like_It_Silver_Shop-New_Orleans_Louisiana.html');

curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

$content=curl_exec($ch);

$start=substr($content, strpos($content, "document.forms.REVIEW_FILTER_FORM.filterRating.value='5'"));
$find='<div class="valueCount fr part">';
//$find='<span class="compositeCount">';

$excellentspanIndex=strpos($start,$find);
//echo $excellentspan;

$excellentcount=substr($start, $excellentspanIndex);



$excellentcount=substr($excellentcount, strlen($find));
$excellentendIndex=strpos($excellentcount, "</div>");
$excellentcount-=substr($excellentcount, $excellentendIndex);
$checkcount=$excellentcount;
if($checkcount==0){
	mail("wagner_michaeljames@yahoo.com","Check the Trip Advisor Badge", "Trip advisor may have changed it's code again, please check the trip advisor page. - Web robot","From:webrobot@asyoulikeitsilvershop.com"."\r\n"."Reply-To:wagner_michaeljames@yahoo.com");
}
//$start=substr($start,$excellentspan);
//$end=strpos($start, '</form>');

echo '
<div style="position: absolute; top: 180px;" data-notes="custom" class="TA_excellent" id="TA_excellent578">
	<div class="widEXC" id="CDSWIDEXC" style="border-color: rgb(162, 113, 120); margin: 0px;">
	  <div class="widEXCLINK" id="CDSWIDEXCLINK"> 
	  <a href="http://www.tripadvisor.com/Attraction_Review-g60864-d265717-Reviews-As_You_Like_It_Silver_Shop-New_Orleans_Louisiana.html" target="_blank">As You Like It Silver Shop rated "excellent" by '.$excellentcount.' travelers</a>
	  </div> 
	  <div class="widEXCTALOGO"> 
	  	<a href="http://www.tripadvisor.com/Attraction_Review-g60864-d265717-Reviews-As_You_Like_It_Silver_Shop-New_Orleans_Louisiana.html" target="_blank">
	  		<img id="CDSWIDEXCLOGO" class="widEXCIMG" alt="TripAdvisor" src="https://c1.tacdn.com/img2/widget/tripadvisor_logo_115x18.gif"></a>
	  </div> 
	</div>

</div>';

?>