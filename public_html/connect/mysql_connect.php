<?php

    $db = mysql_connect("localhost", "asyoulik_admin",'UoO2vA4B') or die ('I cannot connect to the database because: ' . mysql_error());
    if (!$db) {
      echo("Connection to database failed.  Please, try again later.");
     // exit();
    }

    if (!@mysql_select_db("asyoulik_ayliss")) {
      echo("Database connection failed.  Please, try again later.");
      // exit ();
    }
?>