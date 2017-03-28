t=null;
function setCartGraphic() {
	c=unescape(document.cookie);
	document.nums3.src=num_.src;document.nums2.src=num_.src;document.nums1.src=num0.src;
	begin=c.indexOf("items=");
	if (begin<0) {document.chest.src=chestempty.src;return false; }
	end=c.indexOf(";",begin+6)
	if (end<1) {end=c.length;} 
	items=c.substring((	begin+6),end);

	numItems=items.split("&");n=0;
	for( i=1;i<(numItems.length);i++) {
		temp=numItems[i].split(":");n+=parseInt(temp[1]);
	//	eval("if (document.forms['itemsForm'].quantity"+temp[0]+") { 	document.forms['itemsForm'].quantity"+temp[0]+".value="+temp[1]+";}");
	} 
	if (n>0) { document.chest.src=chestfull.src;} 
else { document.chest.src=chestempty.src; }
	n=""+n;
	for (i=0;i<n.length;i++) {
		v=n.substr(i,1);col=n.length-i;
		eval("document.nums"+col+".src=num"+v+".src");

		}
	}

function update(item,add) {
	eval("quantity=parseInt(document.forms['itemsForm'].quantity"+item+".value)");

	itemCookie=unescape(document.cookie);
	begin=	itemCookie.indexOf("items=");
	if (begin<0) { itemCookie=''; }
	else {
		if ((end=itemCookie.indexOf(";",begin+6))<1) {end=itemCookie.length;} 
		if (itemCookie)  { itemCookie=itemCookie.substring((itemCookie.indexOf("items=")+6),end)}
		}
	eval("re=/&"+item+":(\\d+)/g");
//	re.exec(itemCookie);
//	if (RegExp.index!=-1) {
	if (itemCookie.match(re)) { 
		if (quantity>0) {
			if (add) {
				quantity=parseInt(RegExp.$1)+quantity
				}
			itemCookie=itemCookie.replace("&"+item+":"+RegExp.$1,"&"+item+":"+quantity);		
			}
		else {
			itemCookie=itemCookie.replace("&"+item+":"+RegExp.$1,'');
			}
		}
	else {
		if (quantity>0) {
			itemCookie+="&"+item+":"+quantity;
			}
		}
	var now = new Date();
	now.setTime(now.getTime()+60 * 60 * 1000*4);
	curCookie="items="+escape(itemCookie)+"; expires="+now.toGMTString()+"; path=/"
	document.cookie=curCookie

}

function showChest(i) {
	if (t) { tempimage.src=chestadd.src;clearTimeout(t);t=null; }
	eval("tempimage=document.chestimage"+i);
	tempimage.src=chestanimate.src;
	t=setTimeout('tempimage.src=chestadd.src;t=null;',1000)
	}


function updateCart(item,a) {
	update(item,a);
	setCartGraphic();
	showChest(item);

}

function preLoad() { 
if (arguments[0]) {document.getElementById(arguments[0]).className='onred';}
setCartGraphic();
 }  

function updateItems(items) {
	var it=items.split(",");
	for (i in it) {
		update(it[i],0);
		}	
	location='orderdetails.php'
	}


function enlarge(image,w,h,item) {
w+=100;h=(h>500)?600:h+=100;
if (window.imgwindow && !window.imgwindow.closed) { imgwindow.close(); }
extras='width='+w+',height='+h+((h>500)?',scrollbars,resizable':'');
imgwindow=window.open('','',extras);
imgwindow.moveTo(0,0);
stylelink=(window.document.styleSheets[0].href)?window.document.styleSheets[0].href:'ayliss_style_blue.css';

content="<HTML><HEAD><TITLE>"+item+"</TITLE><link rel=stylesheet href='/"+stylelink+"' type=text/css></HEAD><BODY><table width=100% align=center height=100% valign=center><tr><td width=100% align=center><img src='/productImages/_BG/"+image+"'><br><a href='javascript:self.close()'>CLOSE</a></td></tr></table></BODY></HTML>"
imgwindow.document.write(content)
}