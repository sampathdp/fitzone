<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php"); 
    exit;
}

$user_id = $_SESSION['user_id'];
require 'db.php'; 

// Get user details
$stmt = $conn->prepare("SELECT * FROM customers WHERE customer_id = ?");

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: signin.php"); 
    exit;
}

$user = $result->fetch_assoc();

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['transaction_id'])) {
    $transaction_id = (int)$_POST['transaction_id'];

    if (in_array($status, ['Approved', 'Rejected'])) {
        // Get the current date and time for verifydate
        $verifydate = date('Y-m-d H:i:s');

        // Prepare the update statement
        $stmt = $conn->prepare("UPDATE PaymentTransactions SET status = ?, verifydate = ? WHERE transaction_id = ?");
        $stmt->bind_param('ssi', $status, $verifydate, $transaction_id);
        $stmt->execute();
        $stmt->close();
        $message = "Transaction status updated successfully.";
    } else {
        $message = "Invalid status value.";
    }
}

// Fetch payment transactions based on the filter
$stmt = $conn->prepare("
    SELECT pt.transaction_id, pt.payment_receipt, pt.status, c.first_name, c.last_name, pt.membership_id, mt.membership_name,
           pt.verifydate, DATE_ADD(pt.verifydate, INTERVAL 1 YEAR) AS expire_date
    FROM PaymentTransactions pt
    JOIN Customers c ON pt.customer_id = c.customer_id
    JOIN MembershipTypes mt ON pt.membership_id = mt.membership_id
    WHERE pt.customer_id = ?
");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$transaction_result = $stmt->get_result();

$stmt->close();

// Close connection after query execution
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #000; 
            margin: 0;
            padding: 0;
            color: #fff;
        }

        .profile-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #111;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(255, 215, 0, 0.2); 
            overflow: hidden;
        }

        .profile-header {
            text-align: center;
            padding: 20px 0;
            background: linear-gradient(135deg, #f5c518, #b4881c);
            color: #000;
            border-bottom: 4px solid #333;
        }

        .profile-header h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }

        .logout-btn {
            background-color: red; 
            color: wheat;
            padding: 10px 20px;
            border: none;
            border-radius: 20px;
            font-size: 16px;
            text-transform: uppercase;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .logout-btn:hover {
            background-color: #b4881c; 
        }

        .user-details {
            padding: 20px;
            text-align: center;
        }

        .user-details p {
            font-size: 18px;
            margin: 10px 0;
            color: #fff;
        }

        .membership-info {
            text-align: center;
            padding: 20px;
            margin-top: 20px;
            background: #222; 
            border-radius: 12px;
        }

        .membership-info p {
            font-size: 18px;
            color: #f5c518;
        }

        .membership-info .free-service {
            color: #fff; 
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="profile-container">
        <div class="profile-header">
            <h1>Welcome, <?php echo htmlspecialchars($user['first_name']); ?>!</h1>
            <button class="logout-btn" onclick="location.href='logout.php'">Logout</button>
        </div>

        <div class="user-details">
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><strong>First Name:</strong> <?php echo htmlspecialchars($user['first_name']); ?></p>
            <p><strong>Last Name:</strong> <?php echo htmlspecialchars($user['last_name']); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($user['phone_number']); ?></p>
            <p><strong>Last Login:</strong> <?php echo date('F j, Y, g:i a', strtotime($user['last_login'])); ?></p>
        </div>

        <div class="membership-info">
            <?php if ($transaction_result && $transaction_result->num_rows > 0) { 
                $transaction = $transaction_result->fetch_assoc();
            ?>
                <p><strong>Current Package:</strong> <?php echo htmlspecialchars($transaction['membership_name']); ?></p>
                <p><strong>Expiry Date:</strong> <?php echo date('F j, Y', strtotime($transaction['expire_date'])); ?></p>
            <?php } else { ?>
                <p class="free-service"><strong>Current Package:</strong> Free Service</p>
            <?php } ?>
        </div>
    </div>
</body>

</html>
