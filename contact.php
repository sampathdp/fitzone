<?php
require 'db.php';

session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: signin.php");
  exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $customer_id = $_SESSION['user_id'];
  $subject = $conn->real_escape_string($_POST['subject']);
  $message = $conn->real_escape_string($_POST['message']);

  $sql = "INSERT INTO CustomerInquiries (customer_id, subject, message) 
            VALUES ('$customer_id', '$subject', '$message')";

  if ($conn->query($sql) === TRUE) {
    $_SESSION['message'] = "<p>Thank you for your inquiry. We will get back to you shortly.</p>";
  } else {
    $_SESSION['error'] = "<p>Error: " . $conn->error . "</p>";
  }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>The Fitness Factory - Contact Us</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      margin: 0;
      padding: 0;
      background-color: #333;
      color: #333;
      font-family: 'Arial', sans-serif;
      line-height: 1.6;
    }

    h1 {
      color: #ddd;
      margin-bottom: 15px;
    }

    h2 {
      color: #333;
      margin-bottom: 15px;
    }

    .header {
      margin-top: 50px;
      text-align: center;
      padding: 20px;
      background-color: #555;
      color: #fff;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    .container {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      justify-content: center;
      padding: 20px;
    }

    .card {
      background-color: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      width: 320px;
      text-align: center;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 12px rgba(0, 0, 0, 0.15);
    }

    .highlight {
      margin-top: 20px;
      padding: 15px;
      background-color: #f9f9f9;
      border-left: 4px solid #007BFF;
      border-radius: 4px;
      text-align: left;
    }

    form input,
    form textarea {
      width: 100%;
      padding: 10px;
      margin-bottom: 10px;
      border: 1px solid #ddd;
      border-radius: 5px;
      background-color: #fff;
    }

    form input:focus,
    form textarea:focus {
      border-color: #007BFF;
      outline: none;
    }

    button {
      background-color: #FFC000;
      color: #fff;
      border: none;
      border-radius: 5px;
      padding: 10px 15px;
      cursor: pointer;
      font-size: 16px;
      transition: background-color 0.3s ease;
    }

    button:hover {
      background-color: #FFC000;
    }

    .message-box {
      color: #fff;
      font-size: 16px;
      position: fixed;
      top: 20px;
      left: 50%;
      transform: translateX(-50%);
      padding: 15px;
      border-radius: 5px;
      animation: fadeOut 5s forwards;
      z-index: 1000;
    }

    .message-box.success {
      background-color: #28a745;
    }

    .message-box.error {
      background-color: #dc3545;
    }

    iframe {
      border: 0;
      width: 100%;
      height: 200px;
      border-radius: 8px;
    }
  </style>
</head>

<body>

  <?php include 'Includes/header.php'; ?>
  <div class="header">
    <h1>Contact Us</h1>
  </div>

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
    <div class="card">
      <h2>Contact Information</h2>
      <p><span>No 80,</span> <span> Dambulla Road,</span> Kurunegala</p>
      <p>Email: <a href="mailto:info@fitnessfactorymaine.com">support@fitzone.com</a></p>
      <p>Phone: <a href="tel:2077975700">+94 071 111 2222</a></p>
      <div class="highlight">
        <h2>Staffed Hours</h2>
        <p>Weekdays: 6:00 AM - 9:00 PM</p>
        <p>Saturday: 6:00 AM - 6:00 PM</p>
        <p>Sunday: 6:00 AM - 2:00 PM</p>
      </div>
    </div>

    <div class="card">
      <h2>Our Location</h2>
      <iframe
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3153.2082077020673!2d80.3570!3d7.4700!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0:0x0!2zNzwzODU2JzEwLjMiTiA4MMK!5e0!3m2!1sen!2s!4v1698410546498!5m2!1sen!2s"
        allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
      </iframe>

      </iframe>
    </div>

    <div class="card">
      <h2>Contact Us</h2>
      <p>Have questions or need help? Submit your inquiry below.</p>
      <form method="POST">
        <input type="text" name="subject" placeholder="Subject" required>
        <textarea name="message" placeholder="Message" rows="4" required></textarea>
        <button type="submit">Submit Inquiry</button>
      </form>
    </div>
  </div>
  <?php include 'Includes/footer.html'; ?>
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