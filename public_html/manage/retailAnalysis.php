<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Final//EN"> 
<HTML> 
<head> 

<script language="javascript" src="http://www.asyoulikeitsilvershop.com/js/ajax.js"></script>
<script type="text/javascript">

function getSpotPrice(){
 var url='http://www.chappymusic.com';
 var onComplete = '\'alert(request.responseText);\'';

  getURL(url,'',onComplete);

  //   alert("function called");
}

function mineData(t){
// var t='<span id="silver_today">$46.28</span>';
 var find='id="silver_today"';
 var start=t.indexOf(find)+find.length+2;
 var end=t.indexOf('<',start);
 var spotPrice=t.substring(start,end);

 alert(spotPrice);

}

</script>

</head>

<BODY onload="getSpotPrice()">

</BODY>
</HTML>