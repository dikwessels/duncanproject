<html>

<head>
    <title>As You Like It Silver Shop</title>
    <link rel="stylesheet" type="text/css" href="managestyle.css">
    <style type="text/css">

        table {
            border: 1px solid;
            padding: 10px 10px 20px 10px;
        }

        table td {
            border-style: none;
        }

        td.label {
            border: 0px none transparent;
            text-align: left;
            color: #781212;
            font-family: 'Century Gothic', 'Franklin Gothic', 'Trade Gothic', 'Capitals', Helvetica, sans-serif;
            font-size: 14px;
            padding: 2px 4px 2px 4px;
        }

        td.tablehead {
            border-style: none;
            padding: 4px 2px 4px 2px;
            font-size: 14px;
            color: inherit;
        }

        body {
            padding-top: 20px;
        }

        input {
            border: 1px solid #781212;
            padding-left: 2px;
        }

    </style>

</head>
<?

//Initialize the variables
$notificationMsg = '';
$enabled = 0;

$focusOn = '';
$msg = "To change the notification message on the home page, please specify the following:";

//Handle the database fetch
function loadNotificationInfoFromDB()
{
    global $notificationMsg;
    global $enabled;

    /* connect to the database */
    include_once("../../connect/mysql_connect.php");

    $query = mysql_query("SELECT enabled, message from tblNotification");
    $row = mysql_fetch_assoc($query);
    if (mysql_num_rows($query)) {
        $notificationMsg = $row['message'];
        $enabled = $row['enabled'];
    }
}

/* extract post data and init variables */
//extract($_POST);

/* check to see if data was posted and check form for proper data */

if (isset($_POST['notificationMsg'])) {
    $notificationMsg = $_POST['notificationMsg'];

    if ($_POST['enableCheck']) {
        $enabled = 1;
    } else {
        $enabled = 0;
    }


    if ($enabled == 1 && strlen($notificationMsg) == 0) {
        $msg = "Please input the message once you want to make it visible.";
        $focusOn = "notificationMsg";
    }
    else{

        /* connect to the database */
        include_once("../../connect/mysql_connect.php");

        /*update the notification */

        $query = "REPLACE INTO tblNotification (message, enabled, id) VALUES ('$notificationMsg',$enabled,1)";

        $result=mysql_query($query);

        if($result){
            $msg = "Notification Message is successfully updated.";
        }
        else{
            $msg = "Error on storing in DB. Please try again";
        }
    }


}
else{
    loadNotificationInfoFromDB();
}







echo("<body onLoad=document.forms[0].$focusOn.focus()>");

echo("<form method=post><table width=450 border=1 align=center><tr><td colspan=2>");

echo("<tr><td class=tablehead colspan=2>$msg</td></tr>");

echo("<tr><td class=label valign='top'>Message:</td><td><textarea rows='5' cols='75' class='reg' name=notificationMsg>$notificationMsg</textarea></td></tr>");

echo("<tr><td class=label>Enable:</td><td><input type=checkbox name=enableCheck ");
if ($enabled == 1) echo "checked";
echo("/></td></tr>");

echo("<tr><td align=center colspan=2><input type=submit value='Update'></td></tr>");

echo("</table></form>");
?>


</body>

</html>
