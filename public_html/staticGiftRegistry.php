<?php 
ob_start();
$registryTitle="Michael Wagner and Mrs. Michael Wagner";
$f=file_get_contents('/home/asyoulik/public_html/includes/staticheader.inc.html',$f);

$menu=file_get_contents('/home/asyoulik/public_html/silver_cats.in.php');
$f=str_replace('<!--silver_cats-->',$menu,$f);
$f=str_replace('<!--cs-->','',$f);

$f=str_replace("<!--title-->","t_giftregistry.jpg\"",$f);

$f=str_replace('<!--aylissgraphic-->','r.jpg',$f);
$f=str_replace('<!--onLoad-->',"hola",$f);
$f=str_replace('<!--keywords-->',"Gift Registry for $registryTitle at As You Like It Silver Shop",$f);
$f=str_replace('<!--description-->',"Gift Registry for $registryTitle at As You Like It Silver Shop",$f);

$f=str_replace('<!--category-->',"Hollowware",$f);

$f.=file_get_contents("/home/asyoulik/public_html/searchWedReg.php?regID=48");

$f.="<br clear=\"all\"> $registryItems <br clear=\"all\">
<table width=\"760\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" align=\"left\">

<tr>
	<td width=\"50\"><img src=\"images/blank.gif\" width=\"50\" height=\"1\" alt=\"\" border=\"0\"></td>
	<td valign=\"top\">
		<hr width=710 noshade size=1 class=mainsub align=center>
		<p class=\"bottom\">&copy; Copyright 2003-".date('Y'). ". As You Like It Silver Shop.</p>
		<p>&nbsp</p>
	</td>
</tr>

</table>

</body>

</html>";

echo $f;

ob_flush();

?>