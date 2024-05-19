<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/PHPGangsta/GoogleAuthenticator.php';

$hostname = "db";
$username = 'user';
$password = "password";
$database = 'db';

$conn = new mysqli($hostname, $username, $password, $database);



if (!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
}


