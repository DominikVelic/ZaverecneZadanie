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
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Login</th>
                    <th>Created At</th>
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
</body>

</html>

<?php require "../footer.php"; ?>