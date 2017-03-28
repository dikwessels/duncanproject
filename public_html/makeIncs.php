<? 
include_once("/home/asyoulik/connect/mysql_connect.php");



makeInc('f','ps');
makeInc('','f');
makeInc('sp','sp');
makeInc('fcs','fcs');
makeInc('h','h');
makeInc('j','j');
makeInc('bs','bs');
makeInc('cl','cl');
makeInc('cs','cs');


makeSideIncs('f');
makeSideIncs('h');
makeSideIncs('bs');
makeSideIncs('j');
makeSideIncs('cl');
makeSideIncs('cs');


$q=mysql_query("DROP table handles");
$q=mysql_query("CREATE TABLE `handles` (
  `id` int(11) NOT NULL auto_increment,
  `pattern` varchar(50) NOT NULL default '',
  `brand` varchar(50) NOT NULL default '',
  `image` char(1) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ");
$q=mysql_query("INSERT into handles(pattern,brand) SELECT DISTINCT pattern, brand FROM inventory"); 
$q=mysql_query("SELECT * from handles");
while ($r=mysql_fetch_assoc($q)) {
	extract($r);
	$re=array("&",",",'.','#','by','BROTHERS','INTERNATIONAL');$rw=array("AND",'','','','','BROS','INTL');
	$file=str_replace($re,$rw,strtoupper("$pattern $brand")).".jpg";
	$dir=strtoupper(substr($pattern,0,1)); 
	if (file_exists("resizedHandles/$dir/$file")) {
		mysql_query("update handles set image=1 where id=$id");echo "<font color=green>$file</font><br>";
		}
	else { echo "<font color=red>$file</font><BR>"; }
	}

	$q=mysql_query("SELECT * from inventory where category='ps' and retail>0");
	while ($r=mysql_fetch_assoc($q)) {
		$quantity=0;
		set_time_limit(10);
		extract($r);
		switch($item) {
			case '4 Piece Dinner Setting':
			$where="(item='Dinner Knife' or item='Dinner Fork' or item='Salad FORK' or item='TEASPOON')";	
			break;
			case '4 Piece Lunch/Place Setting':
			$where="(item='Lunch/Place Knife' or item='Lunch/Place Fork' or item='Salad Fork' or item='TEASPOON')";
			break;
			case '4 Piece Place Setting':
			$where="(item='Place Knife' or item='Place Fork' or item='Salad Fork' or item='SALAD FORK (PLACE SIZE)' or item='TEASPOON')";
			break;
			default:
			continue;
			break;
			}

		$statement="SELECT quantity as q ,item,id from inventory where pattern='$pattern' and brand='$brand' and $where and monogram=0 and retail>0 and quantity!=0";
		$sq=mysql_query($statement);$quantity=0;
		if (mysql_num_rows($sq)>3) {
			while ($sr=mysql_fetch_assoc($sq)) {
				$qty=abs($sr[q]);
				if ($sr[item]=='SALAD FORK (PLACE SIZE)') { $sr[item]='Salad Fork'; }
				$quants[$sr[item]]+=$qty;
				}
			if (count($quants)==4) {
				sort($quants);
				$quantity=array_shift($quants);
				}
				}

		mysql_query("UPDATE inventory set quantity=$quantity where id=$id");
		unset($quants);
	}

mysql_query("UPDATE inventory set similliarItems=''");
$q=mysql_query("SELECT count( *  ) , i.item
FROM inventory AS i, inventory AS i2
WHERE i.id != i2.id AND i.item = i2.item
GROUP  BY i.item");
while ($r=mysql_fetch_assoc($q)) { mysql_query("UPDATE inventory set similliarItems=1 where item=\"$r[item]\""); }


//mysql_query("UPDATE customers set cardnumber='' where status='' and unix_timestamp( ) - unix_timestamp( time )>7200");
mysql_query("OPTIMIZE TABLE inventory,sideMenu");
mysql_query("UPDATE lastBackUp set time=now()");

$lS=array('sp'=>9,'fcs'=>10,'h'=>11,'bs'=>13,'f'=>8,'j'=>12,'cp'=>13,'stp'=>14,'cs'=>15); 
$fL=array('dinner knife'=>0,'place knife'=>1,'lunch/place knife'=>2,'dinner fork'=>3,'place fork'=>4,'lunch/place fork'=>5,'salad fork'=>6,'salad fork (place size)'=>6,'teaspoon'=>7);

foreach($lS as $k=>$v) { mysql_query("UPDATE inventory set listOrder=$v where category='$k'"); echo "UPDATE inventory set listOrder=$v where category='$k'";}
foreach($fL as $k=>$v) { mysql_query("UPDATE inventory set listOrder=$v where item='$k'"); }




function makeInc($category,$fileName) {
if (!$category) { $category="sp' or substring(category,1,1)='f";$cat='f,fcs,sp,ps'; } else { $cat=$category;};
$searchcategory="(category='$category')";	

$current="";
$brands="";
$options="";

	$query=mysql_query("SELECT distinct(brand) as p from inventory as a where $searchcategory and quantity!=0 and retail!=0  and brand!='' order by p");
	while ($row=mysql_fetch_assoc($query)) {

		$brand.="\"$row[p]\",";
		}
	$brands= "brands['any']=new Array('');\nbrands['all']=new Array($brand";

		$cB='all';
		$sq=mysql_query("SELECT pattern as p,brand as b from inventory  where $searchcategory  and quantity!=0 and retail!=0 and (pattern!='' or brand!='') group by pattern,brand order by pattern");

		while ($sr=mysql_fetch_assoc($sq)) {
			if (!$sr[p]) {$sr[p]="No Pattern"; }
			if ($sr[p]!=$cB) { 
				$options.= "<option value=\"".addslashes($sr[p])."\">".substr($sr[p],0,40);
				$cB=$sr[p];
				$brands= substr($brands,0,-1).")\r\n"; 
				$brands.="\r\nbrands[\"".addslashes($sr['p'])."\"]=new Array(";
				}
			$brands.="'".addslashes($sr['b'])."',";
			}
		$brands= substr($brands,0,-1).")\n";
	$f=fopen("includes/$fileName.inc",'w');
	fwrite($f,$brands);
	fclose($f);
	$f=fopen("includes/{$fileName}Options.inc",'w');
	fwrite($f,$options);
	fclose($f);

}




function makeSideIncs($cat) {
$catS=($cat=='f')?"f,cs,sp,ps":$cat;

//$catS=($cat=='f')?'f%2Cfcs%2Csp,h,ps':$cat;
//$catS=($cat=='j')?'j':$cat;
$q=mysql_query("SELECT * from sideMenu where category='$cat'");
$num=mysql_num_rows($q);

   while ($r=mysql_fetch_assoc($q)) {
	extract($r);
	$searchText=str_replace(array('=','&'),array('/','/'),$searchText);
	/*$content.="<li>  <a href=\"/showSearch/category/$catS/template/$cat/$searchText/submit/Display\" class=listme>$text</a>";*/
	$content.="<li>  <a href=\"/showSearch/category/$catS/template/$cat/$searchText/\" class=listme>$text</a>";
	}
	
	if($cat=='h'){
	$content.="<li><a href=\"/showProducts.php?category=xm&template=xm\" class=listme>Christmas Items</a>";
	}
	
	$f=fopen("includes/{$cat}Menu.inc",'w');
	fwrite($f,$content);
	fclose($f);
	
  
}





?>
