<?php

$hostname = "localhost";
$username = 'user';
$password = "password";
$database = 'db';

$conn = new mysqli($hostname, $username, $password, $database);

if (!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
}

error_reporting(E_ALL);
ini_set('display_errors', 1);
