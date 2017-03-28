<?php 
	
$jsScript.="
fieldArray=new Array(brands);

function populate(num,val) {
var j=0;
	rx='/\\/g';
	val=val.replace(rx,\"\");
	currentPId=0;
	field=	document.search.brand;
	currentPattern=field.options[field.selectedIndex].value;
	field.options.length = 0;
	if (fieldArray[num][val].length>1) { 
		field.options[field.options.length]=new Option('All manufacturers', '', false, false);
		}
	for (j=0; j< fieldArray[num][val].length;j++) {
		if (fieldArray[num][val][j]==currentPattern) { 
                 currentPId=field.options.length;		
                }
		field.options[field.options.length]=new Option(fieldArray[num][val][j], fieldArray[num][val][j], false, false);
		}
		field.selectedIndex=currentPId;
}


function updateIt(lev) {
var i=lev;
	for (i=lev;i>0;i-=1) {
		elem=document.forms[0].elements[1];
		populate((i-1),elem.options[elem.selectedIndex].value);
		}
}


</script>";

return $jsScript;

?>