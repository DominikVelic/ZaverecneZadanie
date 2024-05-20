<?php
session_start();
require "../header.php";
require_once '../.config.php'; // Include the database configuration file

function checkEmpty($field)
{
    if (empty(trim($field))) {
        return true;
    }
    return false;
}

function checkLength($field, $min, $max)
{
    $string = trim($field);
    $length = strlen($string);
    if ($length < $min || $length > $max) {
        return false;
    }
    return true;
}

function checkUsername($username)
{
    if (!preg_match('/^[a-zA-Z0-9_]+$/', trim($username))) {
        return false;
    }
    return true;
}

function checkGmail($email)
{
    if (!preg_match('/^[\w.+\-]+@gmail\.com$/', trim($email))) {
        return false;
    }
    return true;
}

function userExist($login, $email)
{
    global $conn; // Access the database connection object defined in .config.php
    
    $exist = false;

    $param_login = trim($login);
    $param_email = trim($email);

    $sql = "SELECT id FROM users WHERE login = ? OR email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $param_login, $param_email);

    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $exist = true;
    }

    $stmt->close();

    return $exist;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errmsg = "";

    if (checkEmpty($_POST['login']) === true) {
        $errmsg .= "<p>Zadajte login.</p>";
    } elseif (checkLength($_POST['login'], 6, 32) === false) {
        $errmsg .= "<p>Login musi mat min. 6 a max. 32 znakov.</p>";
    } elseif (checkUsername($_POST['login']) === false) {
        $errmsg .= "<p>Login moze obsahovat iba velke, male pismena, cislice a podtrznik.</p>";
    }

    if (userExist($_POST['login'], $_POST['email']) === true) {
        $errmsg .= "Pouzivatel s tymto e-mailom / loginom uz existuje.</p>";
    }

    //if (checkGmail($_POST['email'])) {  NEMAME REDIRECT
        //$errmsg .= "Prihlaste sa pomocou Google prihlasenia";
        //header("Location: redirect.php");
        //exit(); // Stop further execution
    //}

    if (checkEmpty($_POST['password']) === true) {
        $errmsg .= "<p>Zadajte password.</p>";
    } elseif (checkLength($_POST['password'], 6, 16) === false) {
        $errmsg .= "<p>Password musi mat min. 6 a max. 16 znakov.</p>";
    }

    if (checkEmpty($_POST['firstname']) === true) {
        $errmsg .= "<p>Zadajte meno.</p>";
    } elseif (checkUsername($_POST['firstname']) === false) {
        $errmsg .= "<p>Meno moze obsahovat iba velke, male pismena, cislice a podtrznik.</p>";
    }

    if (checkEmpty($_POST['lastname']) === true) {
        $errmsg .= "<p>Zadajte priezvisko.</p>";
    } elseif (checkUsername($_POST['lastname']) === false) {
        $errmsg .= "<p>Priezvisko moze obsahovat iba velke, male pismena, cislice a podtrznik.</p>";
    }

    if (empty($errmsg)) {
        $sql = "INSERT INTO users (fullname, login, email, password, 2fa_code) VALUES (?, ?, ?, ?, ?)";

        $fullname = $_POST['firstname'] . ' ' . $_POST['lastname'];
        $email = $_POST['email'];
        $login = $_POST['login'];
        $hashed_password = password_hash($_POST['password'], PASSWORD_ARGON2ID);

        $g2fa = new PHPGangsta_GoogleAuthenticator();
        $user_secret = $g2fa->createSecret();
        $codeURL = $g2fa->getQRCodeGoogleUrl('Nobel Prizes', $user_secret);

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $fullname, $login, $email, $hashed_password, $user_secret);

        if ($stmt->execute()) {
            $qrcode = $codeURL;
        } else {
            echo "Ups. Nieco sa pokazilo";
        }

        $stmt->close();
    }
    $conn->close();
}
?>


<body>
<main>
        <div class="form-container">
            <h1>Registracia</h1>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                <div class="row">
                    <div class="mb-3 col">
                        <label for="firstname" class="form-label">Meno:</label>
                        <input type="text" class="form-control" name="firstname" id="firstname" placeholder="napr. Erik" required>
                        <div class="invalid-feedback">
                            Prosim zadajte meno.
                        </div>
                    </div>
                    <div class="mb-3 col">
                        <label for="lastname" class="form-label">Priezvisko:</label>
                        <input type="text" class="form-control" name="lastname" id="lastname" placeholder="napr. Prdár" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">E-mail:</label>
                    <div class="input-group">
                        <div class="input-group-text">@</div>
                        <input type="email" class="form-control" name="email" id="email" placeholder="napr. erik.prdar@example.com" required>
                    </div>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" name="login" id="login" placeholder="napr. prdar" required>
                    <label for="login" class="form-label">Login:</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" name="password" id="password" placeholder="napr. prdar" required>
                    <label for="password" class="form-label">Heslo:</label>
                </div>
                <button type="submit" class="btn btn-primary">Vytvoriť konto</button>
                <?php
                if (!empty($errmsg)) {
                    echo '<div class="alert alert-danger mt-3" role="alert">' . $errmsg . '</div>';
                }
                if (isset($qrcode)) {
                    echo '<p class="mt-3">Naskenujte QR kód do aplikácie Authenticator pre 2FA:</p>';
                    echo '<img src="' . $qrcode . '" alt="QR kód pre aplikáciu Authenticator" class="img-fluid">';
                    echo '<p class="mt-3">Teraz sa môžete prihlásiť: <a href="login.php" class="btn btn-primary">Prihlásiť sa</a></p>';
                }
                ?>
            </form>
            <p class="mt-3">Máte vytvorené konto? <a href="login.php">Prihláste sa tu.</a></p>
        </div>
    </main>

    <?php require "../footer.php" ?>
</body>

</html>