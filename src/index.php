<?php
session_start();

require "language_change.php"
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/main.css">
</head>

<body>

    <?php require "header.php" ?>

    <?php
    // skontroluj ci je pouzivatel prihlaseny
    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        // Neprihlaseny pouzivatel, zobraz neprihlaseneho pouzivatela content.
        $page = "guest_user.php";
    } else {
        // Prihlaseny pouzivatel, zobraz prihlaseneho pouzivatela content.
        $page = "logged_user.php";
    }

    // skontroluj ci vobec file existuje
    if (file_exists($page)) {
        require $page;
    } else {
        // ak nie tak error
        echo "Error: Page not found.";
    }
    ?>




    <?php require "footer.php" ?>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    <script src="./js/main.js"></script>

</body>

</html>