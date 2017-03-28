<?
include("/home/asyoulik/connect/mysql_connect.php");
if ($text) {
$s=($id)?"UPDATE featuredArticle set  title='$title',`text`='$text' where articleId=$id":"INSERT into featuredArticle (title,`text`,`articleDate`) values ('$title','$text',NOW())";
mysql_query($s);
if (!$id) { $id=mysql_insert_id(); }
echo "id=$id;";
}

if ($display) {
	mysql_query("UPDATE featuredArticle set display=''");
	mysql_query("UPDATE featuredArticle set display=$n where articleId=$display");
	}
?>