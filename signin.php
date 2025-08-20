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