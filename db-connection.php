<?php


$sname = "localhost";
$uname = "root";
$password = "root";

$db_name = "cinema_db";

// The variable that represents the connection
$conn = mysqli_connect($sname, $uname, $password, $db_name);

if (!$conn){
    echo "Connection to the DB failed!";
    exit();
}