<?php require "header.php" ?>

<body>

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

</body>

</html>