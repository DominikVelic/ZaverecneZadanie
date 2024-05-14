<?php
session_start();


// Check if language is set in the session, otherwise default to English
if (isset($_SESSION['lang'])) {
    include('./language/' . $_SESSION['lang'] . '.php');
} else {
    include('./language/sk.php');
}

// Check if language switch is requesteded
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
    include('./language/' . $_GET['lang'] . '.php');
}
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


    <div class="container">
        <div class="row">
            <div class="column">
                <h1>Init</h1>
                <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Beatae autem sapiente corrupti? Eveniet beatae atque id nulla quaerat sit inventore sed distinctio sapiente. Aliquid quas quisquam placeat, ex sit expedita.</p>
            </div>
        </div>
    </div>



    <?php require "footer.php" ?>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    <script src="./js/main.js"></script>

</body>

</html>