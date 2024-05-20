<?php
session_start();
require "header.php";

function handleFormSubmission() {
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
<div class="form-container">
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
</div>

<!-- Add search bar for 5-character code -->
<div class="form-container">
    <h1>Vyhľadávanie</h1>
    <form action="index.php" method="get" class="needs-validation" novalidate>
        <div class="form-floating mb-3">
            <input type="text" class="form-control" name="code" id="code" maxlength="5" pattern="\d{5}" required>
            <label for="code" class="form-label">Zadajte 5 znakový kód:</label>
            <?php
            if (isset($_SESSION['error'])) {
                echo '<div class="text-danger">' . $_SESSION['error'] . '</div>';
                unset($_SESSION['error']);
            }
            ?>
        </div>
        <div class="row">
            <div class="col mb-3">
                <button type="submit" class="btn btn-primary">Vyhľadať</button>
            </div>
        </div>
    </form>
</div>
<?php require "footer.php"; ?>

</body>
</html>