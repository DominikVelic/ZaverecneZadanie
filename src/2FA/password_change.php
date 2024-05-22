<?php
session_start();
require_once '../.config.php'; // Include the database configuration file

function checkEmpty($field)
{
    return empty(trim($field));
}

function checkLength($field, $min, $max)
{
    $string = trim($field);
    $length = strlen($string);
    return $length >= $min && $max >= $length;
}

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    echo "<div class='alert alert-danger mt-3' role='alert'>Please log in first.</div>";
    exit();
}

if (!isset($_SESSION['user_id'])) {
    echo "<div class='alert alert-danger mt-3' role='alert'>User ID is not set in the session.</div>";
    exit();
}

$errmsg = "";
$success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if (checkEmpty($current_password)) {
        $errmsg .= "<p>Please enter your current password.</p>";
    }

    if (checkEmpty($new_password)) {
        $errmsg .= "<p>Please enter a new password.</p>";
    } elseif (!checkLength($new_password, 6, 16)) {
        $errmsg .= "<p>New password must be between 6 and 16 characters long.</p>";
    }

    if ($new_password !== $confirm_password) {
        $errmsg .= "<p>New passwords do not match.</p>";
    }

    if (empty($errmsg)) {
        $sql = "SELECT password FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            $errmsg .= "<p>Prepare failed: " . $conn->error . "</p>";
        } else {
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows == 1) {
                $stmt->bind_result($hashed_password);
                $stmt->fetch();

                if (password_verify($current_password, $hashed_password)) {
                    $new_hashed_password = password_hash($new_password, PASSWORD_ARGON2ID);

                    $update_sql = "UPDATE users SET password = ? WHERE id = ?";
                    $update_stmt = $conn->prepare($update_sql);

                    if (!$update_stmt) {
                        $errmsg .= "<p>Prepare failed: " . $conn->error . "</p>";
                    } else {
                        $update_stmt->bind_param("si", $new_hashed_password, $user_id);

                        if ($update_stmt->execute()) {
                            $success = true;
                        } else {
                            $errmsg .= "<p>Oops! Something went wrong. Please try again.</p>";
                        }

                        $update_stmt->close();
                    }
                } else {
                    $errmsg .= "<p>Current password is incorrect.</p>";
                }
            } else {
                $errmsg .= "<p>Something went wrong. Please try again.</p>";
            }

            $stmt->close();
        }
    }
    $conn->close();
}

if ($success) {
    header("Location: /index.php");
    exit();
}

require "../header.php";

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $lang['password_change_text']; ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
</head>

<body>

    <main>

        <div class="form-container">
            <h1><?php echo $lang['password_change_text']; ?></h1>
            <div id="form_feedback"></div> <!-- Feedback message will be displayed here -->

            <form id="password_change_form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                <div class="mb-3">
                    <label for="current_password" class="form-label"><?php echo $lang['password_text']; ?></label>
                    <input type="password" class="form-control" name="current_password" id="current_password" required>
                </div>
                <div class="mb-3">
                    <label for="new_password" class="form-label"><?php echo $lang['new_password_text']; ?></label>
                    <input type="password" class="form-control" name="new_password" id="new_password" required>
                </div>
                <div class="mb-3">
                    <label for="confirm_password" class="form-label"><?php echo $lang['confirm_new_password_text']; ?></label>
                    <input type="password" class="form-control" name="confirm_password" id="confirm_password" required>
                </div>
                <button type="submit" class="btn btn-primary"><?php echo $lang['change_password_text']; ?></button>
            </form>


        </div>
    </main>
    <?php require "../footer.php" ?>
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
                        window.location.href = '/index.php'; // Redirect on successful login
                    } else {
                        document.getElementById('login_error').textContent = data; // Display error message
                        document.getElementById('login_error').style.display = 'block'; // Show error message

                        // Clear form fields
                        document.getElementById('login').value = '';
                        document.getElementById('password').value = '';
                        document.getElementById('2fa').value = '';
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    </script>

</body>

</html>