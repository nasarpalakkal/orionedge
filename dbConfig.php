<?php
session_start();
$dbHost = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'exam';
//Connect and select the database
$db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);
$db -> set_charset("utf8");
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}
?>