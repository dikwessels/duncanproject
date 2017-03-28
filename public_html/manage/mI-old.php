<?
	
include_once("/home/asyoulik/connect/mysql_pdo_connect.php");

ini_set("display_errors",1);

function makeInc($category,$fileName) {
	global $db;
	
	if (!$category) { 
			$category = "sp' or substring(category,1,1)='f";
			$cat = 'f,fcs,sp,ps'; 
	} 
	else { 
		$cat = $category;
	}
	
	$searchcategory = "(category='$category')";	
	$current = '';
	$brands = "";
	$options = '';
	
	$strSQL = "SELECT distinct(brand) as p from inventory as a where $searchcategory and quantity!=0 and retail!=0  and brand!='' order by p";
	
	echo "$strSQL<br>";
	
	$query = $db->prepare($strSQL);
	
	$query->execute();
	
	//$query = mysql_query("SELECT distinct(brand) as p from inventory as a where $searchcategory and quantity!=0 and retail!=0  and brand!='' order by p");
	
	$result = $query->fetchAll();
	
	foreach($result as $row){
	
	//while ($row=mysql_fetch_assoc($query)) {
		echo $row['p']."<br>";
		$brand .= "'".str_replace("'","\'",$row['p'])."',";
	
	}
	
	$brands = "brands['any']=new Array('');\n\rbrands['all']=new Array($brand";

		$cB='all';
	
	$strSQL = "SELECT pattern as p,brand as b 
			  FROM inventory  WHERE
			  $searchcategory  AND quantity!=0 AND retail!=0 AND (pattern!='' or brand!='') 
			  GROUP BY pattern, brand order by pattern";
	
	$query = $db->prepare($strSQL);
	
	$query->execute();
	
	
//	$sq=mysql_query("SELECT pattern as p,brand as b from inventory  where $searchcategory  and quantity!=0 and retail!=0 and (pattern!='' or brand!='') group by pattern,brand order by pattern");

	$result = $query->fetchAll();
	
	foreach($result as $sr) {
		
		if (!$sr[p]) {$sr[p]='No Pattern'; }
		
			if ($sr[p]!=$cB) { 
			
				echo $sr['p']."<br>";

				$options .= "<option value='".str_replace("'","\'",$sr['p'])."'>".substr($sr[p],0,40);
				$cB = $sr[p];
				$brands = substr($brands,0,-1).")\r\n"; 
				$brands .= "\r\nbrands[\"".str_replace("'", "\'", $sr['p'])."\"] = new Array(";
			}
			
			$brands .= "'".str_replace("'","\'",$sr['b'])."',";
			
		}
		
		$brands = substr($brands,0,-1).")\n";
		
		$f = fopen("../includes/$fileName.inc",'w');
	
		fwrite($f,$brands);
		fclose($f);
	
		$f = fopen("../includes/{$fileName}Options.inc",'w');
		
		fwrite($f,$options);
		fclose($f);

	}
	
	$categoryArray = array(	'f' 	=> 'ps',
							''		=>  'f',
							'sp'	=> 	'sp',
							'fcs'	=>	'fsc',
							'h'		=>	'h',
							'j'		=>	'j',
							'bs'	=>	'bs',
							'cs'	=>	'cs',
							'cl'	=>	'cl');
							

foreach($categoryArray as $k=>$v){
	makeInc($k,$v);
}
/*
makeInc('f','ps');
makeInc('','f');
makeInc('sp','sp');
makeInc('fcs','fcs');
makeInc('h','h');
makeInc('j','j');
makeInc('bs','bs');
makeInf('cs','cs');
*/

function makeSideIncs($cat) {

$catS=($cat=='f')?'f%2Cfcs%2Csp,h,ps':$cat;

$q = mysql_query("SELECT * from sideMenu where category='$cat'");
	
	$num = mysql_num_rows($q);while ($r=mysql_fetch_assoc($q)) {
	
		extract($r);
		$searchText=str_replace(array('=','&'),array('/','/'),$searchText);
		$content.="<li>  <a href=\"/showSearch/category/$catS/template/$cat/$searchText/submit/Display\" class=listme>$text</a>
	";
		$f=fopen("../includes/{$cat}Menu.inc",'w');
		fwrite($f,$content);
		fclose($f);
		
	}
}

exit;
/*
makeSideIncs('f');
makeSideIncs('h');
makeSideIncs('bs');
makeSideIncs('j');


$re=array("&",",",'.','#','by','BROTHERS','INTERNATIONAL');$rw=array("AND",'','','','','BROS','INTL');
$q=mysql_query("DELETE from handles");
$q=mysql_query("INSERT into handles(pattern,brand) SELECT DISTINCT pattern, brand
FROM inventory order by pattern"); 
$q=mysql_query("SELECT * from handles");
while ($r=mysql_fetch_assoc($q)) {
	extract($r);
	$folder=substr($pattern,0,1);
	$file=strtoupper("$pattern $brand");
	$file=str_replace($re,$rw,$file).".jpg";
	if (file_exists("../resizedHandles/$folder/$file")) {
		mysql_query("update handles set image=1 where id=$id");
		}
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

mysql_query("OPTIMIZE TABLE inventory,sideMenu");
mysql_query("UPDATE lastBackUp set time=now()");
*/

?>
