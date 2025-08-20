<?php

session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: signin.php");
  exit();
}

require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_SESSION['user_id'])) {
        $customer_id = $_SESSION['user_id'];
        $membership_id = $_GET['membership_id'];

        // Handle file upload
        if (isset($_FILES['payment_receipt']) && $_FILES['payment_receipt']['error'] == 0) {
            $payment_receipt = $_FILES['payment_receipt']['name'];
            $target_dir = "Admin/uploads/PaymentSlips/";
            $target_file = $target_dir . basename($_FILES["payment_receipt"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            if (getimagesize($_FILES["payment_receipt"]["tmp_name"]) == false) {
                $_SESSION['error'] = "File is not an image.";
                $uploadOk = 0;
            }

            if ($_FILES["payment_receipt"]["size"] > 5000000) {
                $_SESSION['error'] = "Sorry, your file is too large.";
                $uploadOk = 0;
            }

            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                $_SESSION['error'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $uploadOk = 0;
            }


            if ($uploadOk == 0) {
                $_SESSION['error'] = "Sorry, your file was not uploaded.";
            } else {
                if (move_uploaded_file($_FILES["payment_receipt"]["tmp_name"], $target_file)) {

                    $stmt = $conn->prepare("INSERT INTO PaymentTransactions (customer_id, payment_receipt, membership_id, status) VALUES (?, ?, ?, ?)");
                    $status = 'Pending';
                    $stmt->bind_param("isss", $customer_id, $payment_receipt, $membership_id, $status);

                    if ($stmt->execute()) {
                        $_SESSION['message'] = "Your payment has been submitted successfully.";

                        echo "<script>
                        setTimeout(function() {
                            window.location.href = 'index.php';
                        }, 3000);
                      </script>";
                      
                    } else {
                        $_SESSION['error'] = "Error: " . $stmt->error;
                    }

                    $stmt->close();
                } else {
                    $_SESSION['error'] = "Sorry, there was an error uploading your file.";
                }
            }
        } else {
            $_SESSION['error'] = "No payment receipt was uploaded or there was an error with the file.";
        }
    } else {
        $_SESSION['error'] = "User session is not valid.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrollment Page</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
        }

        h1,
        h2 {
            font-weight: bold;
            color: #4CAF50;
        }

        h1 {
            text-align: center;
            margin-top: 40px;
        }

        .container {
            max-width: 600px;
            margin: 30px auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* Form Styles */
        .form-group {
            margin-top: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: bold;
        }

        .form-group input,
        .form-group button {
            width: 100%;
            padding: 12px;
            margin-top: 8px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .form-group input[type="file"] {
            padding: 8px;
        }

        .form-group button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }

        .form-group button:hover {
            background-color: #45a049;
        }

        /* Bank Details */
        .bank-details {
            margin-top: 30px;
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .bank-details p {
            font-size: 14px;
            margin: 5px 0;
        }

        /* Info Section */
        .info {
            font-size: 14px;
            color: #777;
            margin-top: 20px;
            text-align: center;
        }

        .info p {
            margin: 10px 0;
        }

        .message-box {
            position: fixed;
            top: 80px;
            left: 50%;
            transform: translateX(-50%);
            padding: 15px;
            border-radius: 5px;
            color: #fff;
            font-size: 16px;
            animation: fadeOut 5s forwards;
        }

        .message-box.success {
            background-color: #4caf50;
        }

        .message-box.error {
            background-color: #f44336;
        }
    </style>
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
    <div class="container">
        <h1>Enroll in Our Program</h1>

        <!-- Bank Details -->
        <div class="bank-details">
            <h2>Bank Details for Payment</h2>
            <p><strong>Bank Name:</strong> ABC Bank</p>
            <p><strong>Account Number:</strong> 123456789</p>
            <p><strong>Account Name:</strong> FitZone Pvt Ltd</p>
            <p><strong>Branch:</strong> Colombo Main</p>
        </div>

        <!-- Payment Form -->
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="payment_receipt">Upload Payment Receipt</label>
                <input type="file" id="payment_receipt" name="payment_receipt" accept="image/*" required>
            </div>
            <div class="form-group">
                <button type="submit">Submit Payment Receipt</button>
            </div>
        </form>

        <div class="info">
            <p>Ensure you provide the correct payment details and keep a record of your receipt for verification.</p>
            <p>Once your payment receipt is received, we will review it and update your status accordingly.</p>
        </div>
    </div>
</body>
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

</html>