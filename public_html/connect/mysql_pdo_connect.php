<?php

$database_options = array(
    // important! use actual prepared statements (default: emulate prepared statements)
    PDO::ATTR_EMULATE_PREPARES => false
    // throw exceptions in case of errors (default: stay silent)
    , PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    // fetch associative arrays (default: mixed arrays)
    , PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    , PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
);

$db = new PDO("mysql:host=localhost;dbname=asyoulik_ayliss;","asyoulik_admin","UoO2vA4B",$database_options);

?>
