<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require 'db.php'; // Include the database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Use isset() to handle older PHP versions
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // Prepare the query using MySQLi
    $query = "SELECT * FROM customers WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password_hash'])) {
        // Set session variables
        $_SESSION['user_id'] = $user['customer_id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['first_name'] = $user['first_name'];

        // Update last login
        $updateQuery = "UPDATE customers SET last_login = NOW() WHERE customer_id = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("i", $user['customer_id']);
        $updateStmt->execute();

        $_SESSION['customer_id'] = $user['customer_id'];

        echo "<script>
        setTimeout(function() {
            window.location.href = 'index.php';
        }, 3000);
      </script>";
        exit;
    } else {
        $_SESSION['error'] = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" href="Styles/Auth.css">
</head>

<body>
    <?php
    if (!empty($_SESSION['message'])) {
        echo '<div class="message-box success" id="message-box">' . $_SESSION['message'] . '</div>';
        unset($_SESSION['message']);
    }

    if (!empty($_SESSION['error'])) {
        echo '<div class="message-box error" id="message-box">' . $_SESSION['error'] . '</div>';
        unset($_SESSION['error']);
    }
    ?>

    <div class="container" id="sign-in">
        <h2>Sign In</h2>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" name="sign_in" class="btn">Sign In</button>
            <div class="link">
                <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const messageBox = document.querySelector('.message-box');
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
</body>

</html>