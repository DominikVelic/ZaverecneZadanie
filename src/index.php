<?php
session_start();
require "header.php";

function handleFormSubmission()
{
    if (isset($_GET['code'])) {
        $code = htmlspecialchars($_GET['code']);
        // Ensure the code is exactly 5 digits
        if (preg_match('/^\d{5}$/', $code)) {
            $url = "https://nodeXX.webte.fei.stuba.sk/$code";
            header("Location: $url");
            exit();
        } else {
            $_SESSION['error'] = "Invalid code. Please enter exactly 5 digits.";
        }
    } else {
        $_SESSION['error'] = "No code provided.";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['code'])) {
    handleFormSubmission();
}


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