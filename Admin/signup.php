<?php
session_start(); 

require '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Hash the password
    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    // Escape user inputs for security
    $username = mysqli_real_escape_string($conn, $username);
    $role = mysqli_real_escape_string($conn, $role);

    // Prepare the SQL query
    $sql = "INSERT INTO StaffAccounts (username, password_hash, role) 
            VALUES ('$username', '$password_hash', '$role')";

    // Execute the query
    if (mysqli_query($conn, $sql)) {
        $_SESSION['message'] = "Account successfully created.";
        header("Location: signin.php");
        exit;
    } else {
        $_SESSION['error'] = "Failed to create account: " . mysqli_error($conn);
    }

    // Close the connection
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Sign Up</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="Auth.css">
</head>

<body>
    <div class="container" id="sign-up">
        <?php
        // Display messages if set
        if (!empty($_SESSION['message'])) {
            echo '<div id="message-box" class="message-box success">' . htmlspecialchars($_SESSION['message']) . '</div>';
            unset($_SESSION['message']); 
        }

        if (!empty($_SESSION['error'])) {
            echo '<div id="message-box" class="message-box error">' . htmlspecialchars($_SESSION['error']) . '</div>';
            unset($_SESSION['error']);
        }
        ?>
        <h2>Sign Up</h2>
        <form action="signup.php" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Enter your username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <div class="form-group">
                <label for="role">Role</label>
                <select id="role" name="role" required>
                    <option value="admin">Admin</option>
                    <option value="staff">Staff</option>
                </select>
            </div>
            <button type="submit" class="btn">Sign Up</button>
        </form>
    </div>
</body>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const messageBox = document.getElementById('message-box');
        if (messageBox) {
            setTimeout(() => {
                messageBox.style.opacity = '0';
                setTimeout(() => {
                    messageBox.remove();
                }, 1000);
            }, 5000);
        }
    });
</script>

</html>