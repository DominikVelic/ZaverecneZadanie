<?php
session_start();

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: /index.php"); // Already logged in
    exit;
}

require_once '../.config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if login, password, and 2FA are provided
    if (empty($_POST["login"]) || empty($_POST["password"]) || empty($_POST["2fa"])) {
        echo "Zadajte meno, heslo a 2FA kód.";
        exit;
    }

    // Prepare SQL query to select user based on login or email
    $sql = "SELECT id, fullname, email, login, password, created_at, 2fa_code, admin FROM users WHERE login = ? OR email = ?";
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bind_param("ss", $_POST["login"], $_POST["login"]);

    if ($stmt->execute()) {
        $stmt->store_result();
        if ($stmt->num_rows == 1) {
            // User exists, verify password
            $stmt->bind_result($user_id, $fullname, $email, $login, $hashed_password, $created_at, $two_fa_code, $admin);
            $stmt->fetch();

            if (password_verify($_POST['password'], $hashed_password)) {
                // Password is correct
                $g2fa = new PHPGangsta_GoogleAuthenticator();
                if ($g2fa->verifyCode($two_fa_code, $_POST['2fa'], 2)) {
                    // Both password and 2FA code are correct, user authenticated

                    // Store user data in session
                    $_SESSION["loggedin"] = true;
                    $_SESSION["user_id"] = $user_id;
                    $_SESSION["login"] = $login;
                    $_SESSION["fullname"] = $fullname;
                    $_SESSION["email"] = $email;
                    $_SESSION["created_at"] = $created_at;
                    $_SESSION["admin"] = !is_null($admin) && $admin ? true : false;

                    echo "success"; // Indicate successful login
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
    exit;
}
require "../header.php";
?>

<body>
    <main>
        <div class="form-container">
            <h1><?php echo $lang['login_text']; ?></h1>
            <form id="login_form" class="needs-validation" novalidate>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" name="login" id="login" required>
                    <label for="login" class="form-label"><?php echo $lang['login_name_text']; ?></label>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" name="password" id="password" required>
                    <label for="password" class="form-label"><?php echo $lang['password_text']; ?></label>
                </div>
                <div class="form-floating mb-3">
                    <input type="number" class="form-control" name="2fa" id="2fa" required>
                    <label for="2fa" class="form-label">2FA kod:</label>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <a href="<?php echo filter_var($auth_url, FILTER_SANITIZE_URL); ?>" class="btn btn-primary"><?php echo $lang['google_login_text']; ?></a>
                        <button type="submit" class="btn btn-primary"><?php echo $lang['login_text']; ?></button>
                    </div>
                </div>
            </form>
            <p><?php echo $lang['make_account_here_text']; ?> <a href="register_form.php"><?php echo $lang['register_here_text']; ?>.</a></p>
            <div id="login_error" class="alert alert-danger mt-3" role="alert" style="display: none;"></div>
        </div>
    </main>

    <script>
        document.getElementById('login_form').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent form submission

            var formData = new FormData(this);

            fetch("<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>", {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    if (data === 'success') {
                        window.location.href = '../index.php'; // Redirect on successful login
                    } else {
                        document.getElementById('login_error').textContent = data; // Display error message
                        document.getElementById('login_error').style.display = 'block'; // Show error message
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    </script>
    <?php require "../footer.php" ?>
</body>

</html>