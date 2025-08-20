<?php
session_start(); // Ensure session_start is at the top of the file
require 'db.php'; // Include the database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['sign_up'])) {
    // Get data from POST request
    $first_name = htmlspecialchars($_POST['first_name']);
    $last_name = htmlspecialchars($_POST['last_name']);
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];
    $phone_number = htmlspecialchars($_POST['phone_number']);
    $date_of_birth = $_POST['date_of_birth'];
    $gender = $_POST['gender'];

    // Validate input
    $errors = [];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }

    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long";
    }

    if (!preg_match('/^[0-9]{10}$/', $phone_number)) {
        $errors[] = "Invalid phone number. Please enter 10 digits.";
    }

    if (!empty($errors)) {
        $_SESSION['error'] = implode(', ', $errors);
        header("Location: signup.php");
        exit;
    }

    // Check if email is already registered
    $email_check_query = "SELECT email FROM customers WHERE email = ?";
    $stmt = $conn->prepare($email_check_query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION['error'] = "Email already registered!";
        header("Location: signup.php");
        $stmt->close();
        $conn->close();
        exit;
    }
    $stmt->close();

    // Hash the password
    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    // Insert the user into the database
    $insert_query = "INSERT INTO customers (email, password_hash, first_name, last_name, phone_number, date_of_birth, gender, registration_date) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("sssssss", $email, $password_hash, $first_name, $last_name, $phone_number, $date_of_birth, $gender);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Registration successful! Please log in.";
        echo "<script>
                        setTimeout(function() {
                            window.location.href = 'signin.php';
                        }, 3000);
                      </script>";
    } else {
        $_SESSION['error'] = "An error occurred. Please try again.";
        header("Location: signup.php");
    }

    $stmt->close();
    $conn->close();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="Styles/Auth.css">
</head>

<body>
    <div class="container" id="sign-up">
        <!-- Display success or error messages -->
        <?php if (!empty($_SESSION['message'])): ?>
            <div class="message-box success" id="success-box">
                <?php echo $_SESSION['message'];
                unset($_SESSION['message']); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($_SESSION['error'])): ?>
            <div class="message-box error" id="error-box">
                <?php echo $_SESSION['error'];
                unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <h2>Sign Up</h2>
        <form action="signup.php" method="POST" id="signupForm">
            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" id="first_name" name="first_name" placeholder="Enter your first name" required>
            </div>
            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" id="last_name" name="last_name" placeholder="Enter your last name" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <div class="form-group">
                <label for="phone_number">Phone Number</label>
                <input type="text" id="phone_number" name="phone_number" placeholder="Enter your phone number" required>
            </div>
            <div class="form-group">
                <label for="date_of_birth">Date of Birth</label>
                <input type="date" id="date_of_birth" name="date_of_birth" required>
            </div>
            <div class="form-group">
                <label for="gender">Gender</label>
                <select id="gender" name="gender" required>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <button type="submit" name="sign_up" class="btn">Sign Up</button>
            <div class="link">
                <p>Already have an account? <a href="signin.php">Sign In</a></p>
            </div>
        </form>
    </div>

    <script>
        // Fade out messages after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const successBox = document.getElementById('success-box');
            const errorBox = document.getElementById('error-box');

            [successBox, errorBox].forEach(box => {
                if (box) {
                    setTimeout(() => {
                        box.style.opacity = '0';
                        setTimeout(() => box.remove(), 1000);
                    }, 5000);
                }
            });
        });
    </script>
</body>

</html>