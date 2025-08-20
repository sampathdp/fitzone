<?php
require 'db.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pricing and Experts</title>
  <style>
    /* General Styles */
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #121212;
      color: #fff;
    }

    h1,
    h2 {
      color: #ff6600;
      text-align: center;
      margin-bottom: 1em;
    }

    .section-title {
      font-size: 2.5rem;
      text-transform: uppercase;
      letter-spacing: 2px;
    }

    .sub-title {
      font-size: 1.5rem;
      color: #ccc;
    }

    .banner {
      position: relative;
      text-align: center;
      margin-bottom: 2em;
    }

    .banner-image {
      width: 100%;
      height: auto;
      max-height: 400px;
      object-fit: cover;
    }

    /* Pricing Section */
    .pricing-container {
      padding: 50px 20px;
      background-color: #1a1a1a;
    }

    .pricing-plans {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 2em;
      padding: 2em;
    }

    /* Membership Plan Cards */
    .membership-plan {
      background: linear-gradient(145deg, #1e1e1e, #262626);
      border-radius: 12px;
      padding: 25px;
      text-align: center;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      color: #fff;
      position: relative;
      overflow: hidden;
    }

    .membership-plan h3 {
      font-size: 1.8rem;
      color: #ff6600;
      margin-bottom: 1em;
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    .membership-plan p {
      font-size: 1.1rem;
      color: #ccc;
      margin: 0.5em 0;
    }

    .membership-plan p strong {
      color: #ff6600;
    }

    .membership-plan:hover {
      transform: translateY(-8px);
      box-shadow: 0 12px 40px rgba(255, 102, 0, 0.4);
    }

    .enroll-btn {
      background-color: #ff6600;
      color: #fff;
      padding: 10px 20px;
      border-radius: 5px;
      text-decoration: none;
      font-weight: bold;
      transition: background-color 0.3s ease;
      margin-top: 1.5em;
      display: inline-block;
    }

    .enroll-btn:hover {
      background-color: #e65c00;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .section-title {
        font-size: 2rem;
      }

      .sub-title {
        font-size: 1.2rem;
      }

      .membership-plan {
        padding: 20px;
      }

      .membership-plan h3 {
        font-size: 1.6rem;
      }

      .membership-plan p {
        font-size: 1rem;
      }
    }
  </style>
</head>

<body>
  <?php include 'Includes/header.php'; ?>
  <!-- Pricing Section -->
  <section class="pricing-container">
    <!-- Banner Section -->
    <div class="banner">
      <img src="images/blogbanner.jpg" alt="Exclusive Membership Plans" class="banner-image">

    </div>
    <h1 class="section-title">Transform Your Life Today!</h1>
    <h2 class="sub-title">Choose the plan that best suits your fitness journey.</h2>
    <div class="pricing-plans">
      <?php
      $sql = "SELECT * FROM MembershipTypes WHERE status = 'Active'";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          $membership_id = $row['membership_id'];
          $membership_name = $row['membership_name'];
          $description = $row['description'];
          $monthly_fee = $row['monthly_fee'];
          $annual_fee = $row['annual_fee'];
          $max_class_bookings = $row['max_class_bookings'];
          $personal_training_sessions = $row['personal_training_sessions'];
          $nutrition_consultations = $row['nutrition_consultations'];
          ?>

          <div class="membership-plan">
            <h3><?php echo $membership_name; ?></h3>
            <p><strong>Description:</strong> <?php echo $description; ?></p>
            <p><strong>Monthly Fee:</strong> Rs.<?php echo number_format($monthly_fee, 2); ?></p>
            <p><strong>Annual Fee:</strong> Rs.<?php echo number_format($annual_fee, 2); ?></p>
            <p><strong>Max Class Bookings:</strong> <?php echo $max_class_bookings; ?></p>
            <p><strong>Personal Training Sessions:</strong> <?php echo $personal_training_sessions; ?></p>
            <p><strong>Nutrition Consultations:</strong> <?php echo $nutrition_consultations; ?></p>
            <a href="enroll.php?membership_id=<?php echo $membership_id; ?>" class="enroll-btn">Enroll Now</a>
          </div>

          <?php
        }
      } else {
        echo "<p>No active membership plans available.</p>";
      }
      ?>
    </div>
  </section>
  <?php include 'Includes/footer.html'; ?>
</body>

</html>