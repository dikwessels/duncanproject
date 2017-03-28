

<?
$brand=urlencode($brand);

$f=file_get_contents("http://www.asyoulikeitsilvershop.com/showSearch/template/m/category/f,fcs,ps,s/brand/$brand/");
echo $f;

?>


