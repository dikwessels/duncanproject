<?	
include('/home/asyoulik/connect/mysql_connect.php');

$query="SELECT id from inventory WHERE category='xm'";
echo "Querying database...<br>";
$result=mysql_query($query);
$rc=mysql_numrows($result);

echo "Results found: $rc<br>";

while($row=mysql_fetch_assoc($result)){
	$id=$row['id'];
	include("createStaticHTML.php");

}

?>


