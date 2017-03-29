<?

/* connect to the database */
include_once("../connect/mysql_connect.php");

$enabled = 0;
$notificationMsg = '';

$query = mysql_query("SELECT enabled, message from tblNotification");
$row = mysql_fetch_assoc($query);
if (mysql_num_rows($query)) {
    $notificationMsg = $row['message'];
    $enabled = $row['enabled'];
}

if ($enabled == 1 && strlen($notificationMsg) > 0){

?>

<div class="row">
		<p class="sixteen columns" style="text-align:center">
		<? echo $notificationMsg ?>
		</p>
</div>

<?
}
?>