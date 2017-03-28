<?include("/home/asyoulik/connect/mysql_connect.php");?>
<html>
<head>
<title> As You Like It Silver Shop </title>
<meta name='description' content='As You Like It Silver Shop in New Orleans Louisiana specializes in silver flatware and holloware in active, inactive and obsolete patterns.' />
<meta name='keywords' content='sterling silver, sterling flatware, silver flatware, antique silver, silver tableware, antique sterling, replacement silver, silver repair, corporate gifts, wedding gifts, silver identification, cleaning silver' />
<script language=javascript src=contact_xmlhttp.js></script>

<link rel='stylesheet' href='managestyle.css' type='text/css'>
<script language='javascript'>




function checkForImage(e) {
allowSave()
if( !e ) {
    //if the browser did not pass the event information to the
    //function, we will have to obtain it from the event register
    if( window.event ) {
      //Internet Explorer
      e = window.event;
    } else {
      //total failure, we have no way of referencing the event
      return;
    }
  }

  if( typeof( e.keyCode ) == 'number'  ) {
    //DOM
    k = e.keyCode;
  } else if( typeof( e.which ) == 'number' ) { 
    //NS 4 compatible
    k = e.which; 
  } else if( typeof( e.charCode ) == 'number'  ) {
    //also NS 6+, Mozilla 0.9+
    k = e.charCode;
  } else {
    //total failure, we have no way of obtaining the key code
    return;
  } c=''

switch(k) {
case 190:
curEntry='.'
break;
case 73:
if (curEntry=='.') curEntry='.i'
break;
case 77:
if (curEntry=='.i') curEntry='.im' 
break;
case 71:
if (curEntry=='.im') insertImage();curEntry='';
break;
case 68:
if (curEntry=='.i') { insertLink();curEntry=''; }
break;
default : 
curEntry='';
showDisplay()
}
}


function insertLink() {
	
}
function insertImage() { 
	o=window.open('uploadFeaturedArticleImage.php','o','width=800,height=600')


	}
	curEntry='';
	
function showDisplay() {
t=document.forms[0].text.value
t=t.replace(/\n\n/g,"</p><P>");
t=t.replace(/\n/g,"<BR>");

t=t.replace(/(<[iatbp][^>])+\s+/g,RegExp.$1+' ')
t=t.replace(/  /g,"&nbsp;&nbsp;");

document.getElementById('viewer').innerHTML=t
}

changed=0;
id='<? echo $id;?>'

function update(f) {
if(!changed) return;
var updateConn = new XHConn(); 
	str="id="+id+"&title="+f.title.value+"&text="+f.text.value;
	updateConn.connect("updateArticle.php", "POST", str,disallowSave);
	}

function disallowSave(request) { 
	eval(request.responseText);
	with( document.getElementById('submit').style) {
		backgroundColor='#CCCCCC'; color='#AAAAAA'
		changed=0;
		document.forms[0].id.value= request.responseText
		}
	}
function allowSave() {
	with( document.getElementById('submit').style) {
		backgroundColor='#FFFFCC'; color='#331111'
		changed=1;
		}
	}
</script>
</head>

<body class='sub' onLoad=showDisplay()>

<form   method=post onSubmit='return false'>


<br clear='all'>
<table width='100%' cellpadding='0' cellspacing='0' border='0' align='left'>
<tr bgcolor='#000000'>
	<th style=color:white> Featured Article</th>
</tr>
<tr>
	<td colspan='2'><img src='../images/blank.gif' width='10' height='10' alt='' border='0'></td>
</tr><tr><td colspan=2>Enter text in the texbox on the left and it will appear in the box on the right.  To add an image type .img(you must allow pop up windows for this page).</td></tr>
</table>

<br clear='all'>
<?
if ($id) {$q=mysql_query("SELECT * from featuredArticle where articleId=$id");
$r=mysql_fetch_assoc($q);extract($r); }

echo "
<table width='650' cellpadding='0' cellspacing='0' border='0' align='left'>
<tr>
	<td width='50' align='left'>
		<img src='../images/blank.gif' width='10' height='1' alt='' border='0'><br>
	</td>
	<td valign='top' align='left'>
			<table width='600' border='0' align='left' cellpadding='0' cellspacing='0'>
				<tr><td valign=top><textarea rows=42 cols=70 name=text id=texta onKeyUp=checkForImage(event)>$text</textarea></td>
";
?>
	<td valign=top><div id=viewer style=width:600;border-style:solid;border-width:1;height:100%;padding:6px></div></td></tr><table>
	</td>
</tr>
</table></td></tr> <tr><td><img src='../images/blank.gif' width='10' height='1' alt='' border='0'></td><td >Title:<input name=title value='<? echo $title;?>' onKeyUp= allowSave()><input style='backgroundColor:#CCCCCC;color:#AAAAAA' type=button onClick=update(this.form) id=submit value=Update></td></tr></table>

		</form>
</body>
</html>