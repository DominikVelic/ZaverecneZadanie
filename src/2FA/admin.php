<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || !isset($_SESSION["admin"]) || $_SESSION["admin"] !== true) {
    header("location: /index.php");
    exit;
}

require_once '../.config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['delete_user_id'])) {
        // Delete the user from the database
        $delete_user_id = $_POST['delete_user_id'];

        $sql = "DELETE FROM users WHERE id = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $delete_user_id);

            if ($stmt->execute()) {
                $success_message = "User deleted successfully.";
            } else {
                $error_message = "Error deleting user.";
            }

            $stmt->close();
        } else {
            $error_message = "Error preparing the delete statement.";
        }
    }

    if (isset($_POST['update_password_user_id']) && !empty($_POST['new_password'])) {
        // Update the user's password in the database
        $update_password_user_id = $_POST['update_password_user_id'];
        $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

        $sql = "UPDATE users SET password = ? WHERE id = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("si", $new_password, $update_password_user_id);

            if ($stmt->execute()) {
                $success_message = "User password updated successfully.";
            } else {
                $error_message = "Error updating user password.";
            }

            $stmt->close();
        } else {
            $error_message = "Error preparing the update statement.";
        }
    }
}

// Fetch users from the database
$sql = "SELECT id, fullname, email, login, created_at, admin FROM users";
$result = $conn->query($sql);

$conn->close();

require "../header.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/main.css">
</head>

<body>
    <div class="container">
        <h1>Admin Panel</h1>
        <?php if (isset($success_message)) : ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        <?php if (isset($error_message)) : ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th><?php echo $lang['full_name_text']; ?></th>
                    <th>Email</th>
                    <th>Login</th>
                    <th><?php echo $lang['created_at_text']; ?></th>
                    <th>Admin</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0) : ?>
                    <?php while ($row = $result->fetch_assoc()) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['fullname']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['login']); ?></td>
                            <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                            <td><?php echo $row['admin'] ? 'Yes' : 'No'; ?></td>
                            <td>
                                <!-- Delete Form -->
                                <form method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                    <input type="hidden" name="delete_user_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                                <!-- Update Password Form -->
                                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#updatePasswordModal" data-user-id="<?php echo $row['id']; ?>" data-user-name="<?php echo htmlspecialchars($row['fullname']); ?>">
                                    Update Password
                                </button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="7" class="text-center">No users found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Update Password Modal -->
    <div class="modal fade" id="updatePasswordModal" tabindex="-1" aria-labelledby="updatePasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updatePasswordModalLabel">Update Password</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="update_password_user_id" id="update_password_user_id">
                        <div class="mb-3">
                            <label for="new_password" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFy5rZA+uj0v4/JmMQIatI0rsKYYCXsmY5RxpLv6MRj2l6RYz8pA/6iw/8" crossorigin="anonymous"></script>
    <script>
        var updatePasswordModal = document.getElementById('updatePasswordModal');
        updatePasswordModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;
            var userId = button.getAttribute('data-user-id');
            var userName = button.getAttribute('data-user-name');

            var modalTitle = updatePasswordModal.querySelector('.modal-title');
            var modalUserId = updatePasswordModal.querySelector('#update_password_user_id');

            modalTitle.textContent = 'Update Password for ' + userName;
            modalUserId.value = userId;
        });
    </script>
</body>

</html>

<?php require "../footer.php"; ?>