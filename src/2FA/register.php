<?php

require_once '../.config.php';

// ------- Pomocne funkcie -------
function checkEmpty($field)
{
    // Funkcia pre kontrolu, ci je premenna po orezani bielych znakov prazdna.
    // Metoda trim() oreze a odstrani medzery, tabulatory a ine "whitespaces".
    if (empty(trim($field))) {
        return true;
    }
    return false;
}

function checkLength($field, $min, $max)
{
    // Funkcia, ktora skontroluje, ci je dlzka retazca v ramci "min" a "max".
    // Pouzitie napr. pre "login" alebo "password" aby mali pozadovany pocet znakov.
    $string = trim($field);     // Odstranenie whitespaces.
    $length = strlen($string);      // Zistenie dlzky retazca.
    if ($length < $min || $length > $max) {
        return false;
    }
    return true;
}

function checkUsername($username)
{
    // Funkcia pre kontrolu, ci username obsahuje iba velke, male pismena, cisla a podtrznik.
    if (!preg_match('/^[a-zA-Z0-9_]+$/', trim($username))) {
        return false;
    }
    return true;
}

function checkGmail($email)
{
    // Funkcia pre kontrolu, ci zadany email je gmail.
    if (!preg_match('/^[\w.+\-]+@gmail\.com$/', trim($email))) {
        return false;
    }
    return true;
}

function userExist($db, $login, $email)
{
    // Funkcia pre kontrolu, ci pouzivatel s "login" alebo "email" existuje.
    $exist = false;

    $param_login = trim($login);
    $param_email = trim($email);

    $sql = "SELECT id FROM users WHERE login = :login OR email = :email";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(":login", $param_login, PDO::PARAM_STR);
    $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);

    $stmt->execute();

    if ($stmt->rowCount() == 1) {
        $exist = true;
    }

    unset($stmt);

    return $exist;
}

// ------- ------- ------- -------



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errmsg = "";

    // Validacia username
    if (checkEmpty($_POST['login']) === true) {
        $errmsg .= "<p>Zadajte login.</p>";
    } elseif (checkLength($_POST['login'], 6, 32) === false) {
        $errmsg .= "<p>Login musi mat min. 6 a max. 32 znakov.</p>";
    } elseif (checkUsername($_POST['login']) === false) {
        $errmsg .= "<p>Login moze obsahovat iba velke, male pismena, cislice a podtrznik.</p>";
    }

    // Kontrola pouzivatela
    if (userExist($pdo, $_POST['login'], $_POST['email']) === true) {
        $errmsg .= "Pouzivatel s tymto e-mailom / loginom uz existuje.</p>";
    }

    // Validacia mailu
    if (checkGmail($_POST['email'])) {
        $errmsg .= "Prihlaste sa pomocou Google prihlasenia";
        // Ak pouziva google mail, presmerujem ho na prihlasenie cez Google.
        header("Location: redirect.php");
    }

    // Validacia hesla
    if (checkEmpty($_POST['password']) === true) {
        $errmsg .= "<p>Zadajte password.</p>";
    } elseif (checkLength($_POST['password'], 6, 16) === false) {
        $errmsg .= "<p>Password musi mat min. 6 a max. 16 znakov.</p>";
    }

    // Validacia mena
    if (checkEmpty($_POST['firstname']) === true) {
        $errmsg .= "<p>Zadajte meno.</p>";
    } elseif (checkUsername($_POST['firstname']) === false) {
        $errmsg .= "<p>Meno moze obsahovat iba velke, male pismena, cislice a podtrznik.</p>";
    }

    // Validacia priezviska
    if (checkEmpty($_POST['lastname']) === true) {
        $errmsg .= "<p>Zadajte priezvisko.</p>";
    } elseif (checkUsername($_POST['lastname']) === false) {
        $errmsg .= "<p>Priezvisko moze obsahovat iba velke, male pismena, cislice a podtrznik.</p>";
    }

    if (empty($errmsg)) {
        $sql = "INSERT INTO users (fullname, login, email, password, 2fa_code) VALUES (:fullname, :login, :email, :password, :2fa_code)";

        $fullname = $_POST['firstname'] . ' ' . $_POST['lastname'];
        $email = $_POST['email'];
        $login = $_POST['login'];
        $hashed_password = password_hash($_POST['password'], PASSWORD_ARGON2ID);


        // 2FA pomocou PHPGangsta kniznice: https://github.com/PHPGangsta/GoogleAuthenticator
        $g2fa = new PHPGangsta_GoogleAuthenticator();
        $user_secret = $g2fa->createSecret();
        $codeURL = $g2fa->getQRCodeGoogleUrl('Nobel Prizes', $user_secret);


        // Bind parametrov do SQL
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(":fullname", $fullname, PDO::PARAM_STR);
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->bindParam(":login", $login, PDO::PARAM_STR);
        $stmt->bindParam(":password", $hashed_password, PDO::PARAM_STR);
        $stmt->bindParam(":2fa_code", $user_secret, PDO::PARAM_STR);


        if ($stmt->execute()) {
            // qrcode je premenna, ktora sa vykresli vo formulari v HTML.
            $qrcode = $codeURL;
        } else {
            echo "Ups. Nieco sa pokazilo";
        }

        unset($stmt);
    }
    unset($pdo);
}

?>

<!doctype html>
<html lang="sk">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login/register s 2FA - Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/main.css">
</head>

<body>
    <?php require "../header.php" ?>
    <main>

        <h1>Registracia</h1>

        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">

            <div class="row">
                <div class="mb-3 col">
                    <label for="firstname" class="form-label">Meno:</label>
                    <input type="text" class="form-control" name="firstname" value="" id="firstname" placeholder="napr. Jonatan" required>
                    <div class="invalid-feedback">
                        Prosim zadajte meno.
                    </div>
                </div>
                <div class="mb-3 col">
                    <label for="lastname" class="form-label">Priezvisko:</label>
                    <input type="text" class="form-control" name="lastname" value="" id="lastname" placeholder="napr. Petrzlen" required>
                </div>
            </div>


            <div class="mb-3">
                <label for="email" class="form-label">E-mail:</label>
                <div class="input-group">
                    <div class="input-group-text">@</div>
                    <input type="email" class="form-control" name="email" value="" id="email" placeholder="napr. jpetrzlen@example.com" required>
                </div>
            </div>

            <div class="form-floating mb-3">
                <input type="text" class="form-control" name="login" value="" id="login" placeholder="napr. jperasin" required>
                <label for="login" class="form-label">Login:</label>
            </div>

            <div class="form-floating mb-3">
                <input type="password" class="form-control" name="password" value="" id="password" required>
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

    </main>

    <?php require "../footer.php" ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>