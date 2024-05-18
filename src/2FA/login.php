<?php require "../header.php" ?>

<?php
// Check if the user is already logged in, if yes then redirect him to welcome pageaaaaaaaaaaaaaaaaaaaaaaaaaaaaa
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: /index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // TODO: Skontrolovat ci login a password su zadane (podobne ako v register.php).

    $sql = "SELECT fullname, email, login, password, created_at, 2fa_code FROM users WHERE login = :login";

    $stmt = $pdo->prepare($sql);

    // TODO: Upravit SQL tak, aby mohol pouzivatel pri logine zadat login aj email.
    $stmt->bindParam(":login", $_POST["login"], PDO::PARAM_STR);

    if ($stmt->execute()) {
        if ($stmt->rowCount() == 1) {
            // Uzivatel existuje, skontroluj heslo.
            $row = $stmt->fetch();
            $hashed_password = $row["password"];

            if (password_verify($_POST['password'], $hashed_password)) {
                // Heslo je spravne.
                $g2fa = new PHPGangsta_GoogleAuthenticator();
                if ($g2fa->verifyCode($row["2fa_code"], $_POST['2fa'], 2)) {
                    // Heslo aj kod su spravne, pouzivatel autentifikovany.

                    // Uloz data pouzivatela do session.
                    $_SESSION["loggedin"] = true;
                    $_SESSION["login"] = $row['login'];
                    $_SESSION["fullname"] = $row['fullname'];
                    $_SESSION["email"] = $row['email'];
                    $_SESSION["created_at"] = $row['created_at'];

                    // Presmeruj pouzivatela na zabezpecenu stranku.
                    header("location: ../restricted.php");
                } else {
                    echo "Neplatny kod 2FA.";
                }
            } else {
                echo "Nespravne meno alebo heslo.";
            }
        } else {
            echo "Nespravne meno alebo heslo.";
        }
    } else {
        echo "Ups. Nieco sa pokazilo!";
    }

    unset($stmt);
    unset($pdo);
}

?>

<body>

    <main>

        <h1>Prihlasenie</h1>

        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" class="needs-validation" novalidate>

            <div class="form-floating mb-3">
                <input type="text" class="form-control" name="login" value="" id="login" required>
                <label for="login" class="form-label">Prihlasovacie meno:</label>

            </div>

            <div class="form-floating mb-3">
                <input type="password" class="form-control" name="password" value="" id="password" required>
                <label for="password" class="form-label">Heslo:</label>

            </div>

            <div class="form-floating mb-3">
                <input type="number" class="form-control" name="2fa" value="" id="2fa" required>
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
    </main>

    <?php require "../footer.php" ?>
</body>

</html>