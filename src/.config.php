<?php

$hostname = "db";
$username = 'user';
$password = "password";
$database = 'db';

$conn = new mysqli($hostname, $username, $password, $database);

require_once __DIR__ . '/PHPGangsta/GoogleAuthenticator.php';

if (!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
}

error_reporting(E_ALL);
ini_set('display_errors', 1);
