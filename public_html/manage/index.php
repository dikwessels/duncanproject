<?

    extract($_POST);
    extract($_GET);

    include_once("../../connect/mysql_connect.php");
    function logOn($tryagain)
    {
        include('manageHeader.html');
?>

    <html>
    <body onLoad=document.forms[0].username.focus()>
    <form method=post>
        <table border=1 align=center>
            <?
            if ($tryagain) {
                ?>
                <tr>
                    <td colspan=2>Sorry, that is not valid user information</td>
                </tr>
                <?
            }
            ?>
            <tr>
                <td align=right>username</td>
                <td><input name=username></td>
            </tr>
            <tr>
                <td align=right>password</td>
                <td><input name=password type=password></td>
            </tr>
            <tr>
                <td colspan=2 align=center><input type=submit value=GO></td>
            </tr>
        </table>
    </form>
    <table valign=bottom>
        <tr>
            <td><br><br><br><font color=red>note: if a blank screen comes up after clicking 'go' click the Refresh
                    button.</font></td>
        </tr>
    </table>
    </body>
    </html>
    <?
}


function choosePassword($tryagain, $name)
{
    include('manageHeader.html');
    ?>
    <html>
    <body onLoad=document.forms[0].username.focus()>
    <form method=post>
        <table border=1 align=center>
            <tr>
                <td colspan=2>
                    <?
                    if ($tryagain) { ?>Sorry that password is taken<? } else { ?>Before continuing you must choose a password<?
                    } ?></td>
            </tr>
            <tr>
                <td align=right>password</td>
                <td><input name='newpassword' type=password></td>
            </tr>
        </table>
        <input name=username type=hidden value='<? echo $name; ?>'></td></tr></table><font color=red>note: if a blank
            screen comes up after clicking 'go' click the Refresh button.</font></form>
    </body>
    </html>
    <?
}

if (!isset($_COOKIE['accessLevel'])) {

    if (isset($_POST['username'])) {

        if (isset($_POST['newpassword'])) {

            $query = mysql_query("UPDATE users set password='$newpassword',temp=0 where username='$username'");

            if (mysql_affected_rows() < 1) {

                choosePassword(1, $_POST['username']);
                exit;

            }

            $password = $newpassword;

        }

        $query = mysql_query("SELECT groupNumber,temp from users where username='$username' and password='$password'");
        $row = mysql_fetch_assoc($query);
        if (!mysql_num_rows($query)) {

            logOn(1);
            exit;
        }
        if ($row['temp']) {
            choosePassword(0, $_POST['username']);
            exit;
        }

        $accessLevel = $row['groupNumber'];


//        header("Set-Cookie: accessLevel = $accessLevel;time()+3600*2;path=/\n\n");
//        header("Set-Cookie: userName=" . $_POST['username'] . ";time()+3600*2;path=/\n\n");

        setcookie("accessLevel", $accessLevel, time()+3600*2, "/");
        setcookie("userName", $_POST['username'], time()+3600*2, "/");
    } else {
        logOn(0);
        exit;
    }
}

?>
<html>
<head>
    <title>As You Like It Silver Shop Admin</title>
</head>
<frameset rows='200,*' frameborder=0>
    <frame src=manage_top.php?header=Main+Menu name=topframe scrolling=no>
    <frame src=manage_bottom.php name=bottom>
</frameset>
</html>
