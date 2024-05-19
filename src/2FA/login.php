<?php

session_start();

// Check if the user is already logged in, if yes then redirect him to the welcome page
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: ../index.php"); // treba sem ten restricted
    exit;
}
require "../header.php";
require_once '../.config.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Check if login and password are provided
    if (empty($_POST["login"]) || empty($_POST["password"])) {
        echo "Zadajte meno a heslo.";
        exit;
    }

    // Prepare SQL query to select user based on login or email
    $sql = "SELECT fullname, email, login, password, created_at, 2fa_code FROM users WHERE login = ? OR email = ?";

    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bind_param("ss", $_POST["login"], $_POST["login"]);

    if ($stmt->execute()) {
        $stmt->store_result();
        if ($stmt->num_rows == 1) {
            // User exists, verify password
            $stmt->bind_result($fullname, $email, $login, $hashed_password, $created_at, $two_fa_code);
            $stmt->fetch();

            if (password_verify($_POST['password'], $hashed_password)) {
                // Password is correct
                $g2fa = new PHPGangsta_GoogleAuthenticator();
                if ($g2fa->verifyCode($two_fa_code, $_POST['2fa'], 2)) {
                    // Both password and 2FA code are correct, user authenticated

                    // Store user data in session
                    $_SESSION["loggedin"] = true;
                    $_SESSION["login"] = $login;
                    $_SESSION["fullname"] = $fullname;
                    $_SESSION["email"] = $email;
                    $_SESSION["created_at"] = $created_at;

                    // Redirect user to restricted page
                    header("location: ../index.php"); //NOOOO TOTO TREBA DOROBIT
                    exit;
                } else {
                    echo "Neplatný kód 2FA.";
                }
            } else {
                echo "Nesprávne meno alebo heslo.";
            }
        } else {
            echo "Nesprávne meno alebo heslo.";
        }
    } else {
        echo "Ups. Niečo sa pokazilo!";
    }

    $stmt->close();
    $conn->close();
}
?>

<body>

    <main>
        <div class="form-container">
            <h1>Prihlasenie</h1>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" class="needs-validation" novalidate>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" name="login" id="login" required>
                    <label for="login" class="form-label">Prihlasovacie meno:</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" name="password" id="password" required>
                    <label for="password" class="form-label">Heslo:</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="number" class="form-control" name="2fa" id="2fa" required>
                    <label for="2fa" class="form-label">2FA kod:</label>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <a href="<?php echo filter_var($auth_url, FILTER_SANITIZE_URL); ?>" class="btn btn-primary">Google prihlasenie</a>
                        <button type="submit" class="btn btn-primary">Prihlásiť sa</button>
                    </div>
                </div>
            </form>
            <p>Este nemate vytvorene konto? <a href="register.php">Registrujte sa tu.</a></p>
        </div>
    </main>

    <?php require "../footer.php" ?>
</body>

</html>