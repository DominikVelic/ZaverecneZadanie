<?php
session_start();


// Check if language is set in the session, otherwise default to English
if (isset($_SESSION['lang'])) {
    include($_SESSION['lang'] . '.php');
} else {
    include('en.php');
}

// Check if language switch is requested
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
    include($_GET['lang'] . '.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./css/main.css">
</head>

<body>

    <?php require "header.php" ?>


    <h1>Init</h1>
    <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Beatae autem sapiente corrupti? Eveniet beatae atque id nulla quaerat sit inventore sed distinctio sapiente. Aliquid quas quisquam placeat, ex sit expedita.</p>


    <?php require "footer.php" ?>
</body>

</html>