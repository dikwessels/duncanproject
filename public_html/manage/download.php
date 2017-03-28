<?
include("/home/asyoulik/connect/mysql_connect.php");
$content='';
$query=mysql_query("SELECT fname,lname,address1,address2,city,state,zip,country,phone,subtotal,time,tax,shipping,items,cardname,cardaddress,cardcity , cardstate ,cardzip ,cardcountry,cardphone,cardemail from customers  where status='shipped'");
while ($row=mysql_fetch_assoc($query)) {
	extract($row);
	$itemList='';
	$item=split('&',substr($items,1));
	$y=substr($time,0,4);$m=substr($time,4,2);$d=substr($time,6,2);
	foreach($item as $v) {
		$x=explode(":",$v);
		$q=mysql_query("SELECT item,brand,pattern  from inventory where id=$x[0]");
		$r=mysql_fetch_assoc($q);
		$itemList.="$r[item]($r[pattern] $r[brand])-$x[1],";	
		}
		$itemList=substr($itemList,0,-1);
	$content.= "$fname	$lname	$address1	$address2	$city	$state	$zip	$country	$phone	$m/$d/$y	$itemList	$subtotal	$tax	$shipping	$cardname	$cardaddress	$cardcity	$cardstate	$cardzip	$cardcountry	$cardphone	$cardemail
";
//$content.=($amount*.02250)."	".(	$amount-($amount*.0225))."	";
	
}
$d=time();
$fh=fopen("download/transactions{$d}.txt",'w');
fwrite($fh,$content);
fclose($fh);

	mysql_query("UPDATE orders set status='downloaded' where status='shipped'");
     header('Cache-Control:private');
    header('Content-Type: ' . 'application/octet-stream');
    header('Expires: ' . $now);
header("Content-Length: ".strlen($content));
        header('Content-Disposition: attachment; filename="transactions'.$d.'.txt"');
echo $content;
?>