
<HTML>
<HEAD>
<TITLE></TITLE>
<META name="description" content="">
<META name="keywords" content="">
<script language='javascript'>
document.oncontextmenu = new Function("return false");
function mouseup()
{if (window.getSelection)
	{
		sel= window.getSelection();
	}
	else if (document.getSelection)
	{
		sel = document.getSelection();
	}
	else if (document.selection)
	{
		sel = document.selection.createRange().text;
		}
}

cats=new Array('No Category','Baskets, Centerpieces, Vases and Epergnes','Bowls,Compotes & Cake Stands','Bread & Butter Items','Candleware','Gift','Goblets, Mint Julep Cups & Other Cups','Jewelry and Personal Accessories','Napkin Rings','Picture Frames','Pitchers & Urns','Salt & Pepper Items','Tabletop Items','Tea and Coffee','Trays','Vanity Items','Wine & Bar Items','Chafing Dishes');
var currentI='<?
include("/home/asyoulik/connect/mysql_connect.php");
if ($move) {
	$items=explode(',',$move);
	foreach ($items as $v) {
		if ($v) {
			mysql_query("UPDATE inventory set searchCategory=$newCat where id=$v");
			}
		}
	echo $move;
	}
?>'

function highlight() {
	items=currentI.split(',');
	for (i=0;i<items.length-1;i++) { 
		document.getElementById(items[i]).style.backgroundColor='ffcccc'
		}
	}


function fill(cObj,eObj) {
	dir=(eObj.orderNumber-cObj.orderNumber);

	while (cObj.id!=eObj.id) {
		cObj=(dir>0)?cObj.nextSibling.nextSibling:cObj.previousSibling.previousSibling;
		if (cObj.style.backgroundColor!='#ffcccc') {
			cObj.style.backgroundColor='ffcccc';
			currentI+=cObj.id+","
			}		
		}
	}

	
function selectI(id) { 	
	if (document.addEventListener)event=arguments[1];	

	if (event.altKey && currentI) {
		str=currentI.substring(0,currentI.length-1);
		begining=(str.lastIndexOf(",")>0)? str.substring(str.lastIndexOf(",")+1):str

		bObj=	document.getElementById(begining)
		nObj=document.getElementById(id)
		if (nObj.group==bObj.group) {
			fill(bObj,nObj)
			}
		return
		}
	if (currentI && event.button == 1) {
		items=currentI.split(',')
		for (i=0;i<items.length-1;i++) {
			document.getElementById(items[i]).style.backgroundColor='ffffff';
			}
		currentI='';
		}

	if (document.getElementById(id).style.backgroundColor!='#ffcccc') {
		document.getElementById(id).style.backgroundColor='ffcccc';
		currentI+=id+","
		}
	}
function keypress() { 
	if (!currentI)  {return; }
	if (document.addEventListener)event=arguments[0];	
	list='';
	ev=event.keyCode;	
	if (ev>47 && ev<59) { ev-=48;}
	else if (ev>96 && ev<105) {ev-=87;}
	else  return
	items=currentI.split(',');
	for (i=0;i<items.length-1;i++) { 
		list+=","+document.getElementById(items[i]).innerHTML
		}
	list=list.substr(1);
	if (confirm("Move "+list+" to "+cats[ev])) { location='searchCategories.php?move='+currentI+'&newCat='+ev }
	}
if (document.addEventListener) {document.addEventListener('mouseup', mouseup,'false');document.addEventListener('keydown', keypress,'false')} else {document.attachEvent('onmouseup', mouseup)
document.attachEvent('onkeypress', keypress); }
</script>
<style type='text/css'></style>
</HEAD>
<BODY onLoad=highlight()>DIRECTIONS:<br>
1.Highlight an item by clicking it.  To highlight more then one item use the right click.  If you hold down the alt key while clicking an item, it will select all items in between the item and the last selected item.<br>
2. Type 1 through H to move the selected items to the corresponding category.
<TABLE cellpadding=2 cellspacing=2 border=1 style=font-size:12px	><tr><th>0.No category</th><th>1.Baskets...</td><th>2.Bowls...</td><th>3.Bread Butter</td><th>4.Candleware
</td><th>5.Gift
</td><th>6.Goblets...
</td><th>7.Jewelry and Personal Accessories
</td><th>8.Napkin Rings
</td><th>9.Frames
</td><th>A.Pitchers...
</td><th>B.Salt Pepper
</td><th>C.Tabletop 
</td><th>D.Tea Coffee
</td><th>E.Trays
</td><th>F.Vanity Items
</td><th>G.Wine & Bar</td><th>H. Chafing Dishes</td></tr><tr><td valign=top nowrap>
<?

$searchCategory;$c=0;$i=0;
$q=mysql_query("SELECT item,id,searchCategory as nc,pattern,brand  from inventory where category='h' order by searchCategory,item");
while ($r=mysql_fetch_assoc($q)) { extract($r);$i++;
		while ($c!=$nc && $nc>0) {		
			echo "</td><td valign=top nowrap>";
			$c++;
			}
	echo "<span id=$id onMouseDown=selectI($id,event) group=$nc orderNumber=$i>$item</span><br>	";
	}
?>
</td></tr></table>
</BODY>
</HTML>
