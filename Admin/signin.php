<?php

session_start();

require '../db.php'; // Ensure this script establishes a connection with the database using mysqli

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the input values from the form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare the SQL query to select staff_id, password_hash, and role from the StaffAccounts table
    $username = mysqli_real_escape_string($conn, $username);
    $sql = "SELECT staff_id, password_hash, role FROM StaffAccounts WHERE username = '$username'";

    // Execute the query
    $result = mysqli_query($conn, $sql);

    // Check if the user exists
    if ($result && mysqli_num_rows($result) > 0) {
        // Fetch the user data (staff_id, password_hash, and role)
        $user = mysqli_fetch_assoc($result);

        // Verify the password
        if (password_verify($password, $user['password_hash'])) {
            // Set the session variables and message
            $_SESSION['user_id'] = $user['staff_id'];
            $_SESSION['user_role'] = $user['role'];

            $_SESSION['message'] = "Welcome back, " . htmlspecialchars($user['role']) . "!";

            // Redirect to index.php
            header("Location: index.php");
            exit;
        } else {
            $_SESSION['error'] = "Invalid credentials.";
        }
    } else {
        $_SESSION['error'] = "No user found with that username.";
    }

    // Free the result set and close the connection
    if ($result) {
        mysqli_free_result($result);
    }
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Sign Up & Sign In</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="Auth.css">
</head>

<body>
    <?php
    if (!empty($_SESSION['message'])) {
        echo '<div class="message-box success">' . $_SESSION['message'] . '</div>';
        unset($_SESSION['message']);
    }

    if (!empty($_SESSION['error'])) {
        echo '<div class="message-box error">' . $_SESSION['error'] . '</div>';
        unset($_SESSION['error']);
    }
    ?>
    <div class="container" id="sign-in">
        <h2>Sign In</h2>
        <form action="signin.php" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Enter your username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="btn">Sign In</button>
            <!-- <div class="link">
                <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
            </div> -->
        </form>
    </div>
</body>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const messageBoxes = document.querySelectorAll('.message-box');
        messageBoxes.forEach((box) => {
            setTimeout(() => {
                box.style.opacity = '0';
                setTimeout(() => {
                    box.remove();
                }, 1000);
            }, 5000);
        });
    });
</script>

</html>