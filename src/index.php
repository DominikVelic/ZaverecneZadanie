<?php
session_start();
require "header.php";

?>

<body>

    <?php
    // Check if the user is logged in
    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        // Unauthenticated user, show guest user content.
        $page = "guest_user.php";
    } else {
        // Authenticated user, show logged-in user content.
        $page = "logged_user.php";
    }

    // Check if the file exists
    if (file_exists($page)) {
        require $page;
    } else {
        // If not, show error
        echo "Error: Page not found.";
    }
    ?>



    <?php require "footer.php"; ?>

</body>

</html>