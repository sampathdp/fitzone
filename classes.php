<?php

include 'db.php';
include 'Includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['join_class'])) {
    $schedule_id = $_POST['schedule_id'];
    $customer_id = $_SESSION['user_id'];
    // Check if user has already joined the class
    $sql_check = "SELECT * FROM ClassBookings WHERE customer_id = ? AND schedule_id = ?";
    $stmt_check = $conn->prepare($sql_check);

    $stmt_check->bind_param("ii", $customer_id, $schedule_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        $_SESSION['error'] = "You have already joined this class";
    } else {
        // Check if schedule ID is valid and slots are available
        $sql = "SELECT max_slots, used_slots FROM ClassSchedules WHERE schedule_id = ?";
        $stmt = $conn->prepare($sql);

        $stmt->bind_param("i", $schedule_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $row = $result->fetch_assoc();

        if ($row && $row['max_slots'] > $row['used_slots']) {
            // Increment the used slots
            $sql_update = "UPDATE ClassSchedules SET used_slots = used_slots + 1 WHERE schedule_id = ?";
            $stmt_update = $conn->prepare($sql_update);


            $stmt_update->bind_param("i", $schedule_id);
            $stmt_update->execute();

            // Insert booking record into ClassBookings table
            $sql_booking = "INSERT INTO ClassBookings (customer_id, schedule_id, status) VALUES (?, ?, 'Confirmed')";
            $stmt_booking = $conn->prepare($sql_booking);

            $stmt_booking->bind_param("ii", $customer_id, $schedule_id);
            $stmt_booking->execute();

            $_SESSION['message'] = "Successfully joined the class and booking details saved";
        } else {
            $_SESSION['error'] = "Sorry, no available slots for this class.</p>";
        }
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classes & Events | FitZone Fitness Center</title>
    <style>
        /* General Body Style */
        body {
            margin: 0;
            font-family: 'Roboto', Arial, sans-serif;
            background-color: #121212;
            color: #f5f5f5;
            line-height: 1.6;
        }

        /* Header Styles */
        header {
            background: linear-gradient(90deg, #ff6600, #ff3300);
            padding: 1em 0;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
        }

        header h1 {
            color: #fff;
            font-size: 2.2rem;
            margin: 0;
            letter-spacing: 2px;
        }

        /* Container Styles */
        .container {
            padding: 2em;
            max-width: 1200px;
            margin: 0 auto;
        }

        .section-header {
            text-align: center;
            margin-bottom: 2em;
        }

        .section-header h1 {
            color: #ff6600;
            font-size: 2.5rem;
            margin-bottom: 0.5em;
        }

        .section-header p {
            font-size: 1.2rem;
            color: #ddd;
            margin: 0 auto;
            max-width: 600px;
        }

        /* Grid Layout */
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5em;
            justify-content: center;
            align-items: stretch;
        }

        /* Card Styles */
        .card {
            background: #1e1e1e;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            text-align: center;
            padding: 1em;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4);
        }

        /* Image Container */
        .card img {
            width: auto;
            height: 150px;
            object-fit: contain;
            /* Show the entire image without cutting */
            display: block;
            margin: 0 auto;
            /* Center the image horizontally */
        }

        .card h3 {
            color: #ff6600;
            font-size: 1.3rem;
            margin-bottom: 0.5em;
        }

        .card p {
            font-size: 0.95rem;
            color: #ddd;
            margin: 0.5em 0;
        }

        .card p strong {
            color: #fff;
        }

        /* Button Styles */
        .card .btn {
            display: inline-block;
            padding: 0.6em 1.2em;
            background: #ff6600;
            color: #fff;
            text-decoration: none;
            border-radius: 20px;
            font-size: 0.95rem;
            box-shadow: 0 3px 8px rgba(255, 102, 0, 0.4);
        }

        .card .btn:hover {
            background: #cc5200;
            transform: translateY(-3px);
            box-shadow: 0 5px 10px rgba(255, 102, 0, 0.5);
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

    <div class="container">
        <!-- Classes Section -->
        <div class="section-header">
            <h1>Classes</h1>
            <p>Explore our exciting range of fitness classes tailored to your needs.</p>
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



        $sql = "SELECT 
            cs.schedule_id, 
            fc.class_name, 
            fc.description, 
            fc.class_image, 
            cs.start_time, 
            cs.end_time, 
            cs.max_slots, 
            cs.used_slots,
            t.trainer_name
        FROM ClassSchedules cs
        JOIN FitnessClasses fc ON cs.class_id = fc.class_id
        LEFT JOIN Trainers t ON fc.trainer_id = t.trainer_id WHERE 
        cs.start_time >= NOW()
    ORDER BY 
        cs.start_time ASC";

        $result = mysqli_query($conn, $sql);


        // Check if the query succeeded
        if (!$result) {
            die("Query failed: " . mysqli_error($conn));
        }

        // Check if records exist
        if (mysqli_num_rows($result) > 0) {
            echo '<div class="grid">';

            // Loop through the data and generate cards
            while ($row = mysqli_fetch_assoc($result)) {

                $available_slots = $row['max_slots'] - $row['used_slots'];

                echo '
        <div class="card">
            <img src="Admin/' . $row['class_image'] . '" alt="' . $row['class_name'] . '">
            <h3>' . $row['class_name'] . '</h3>
            <p>' . $row['description'] . '</p>
            <p><strong>Trainer:</strong> ' . $row['trainer_name'] . '</p>
            <p><strong>Start Time:</strong> ' . date("F j, Y, g:i a", strtotime($row['start_time'])) . '</p>
            <p><strong>End Time:</strong> ' . date("F j, Y, g:i a", strtotime($row['end_time'])) . '</p>
            <p><strong>Available Slots:</strong> ' . $available_slots . '</p>
            <a href="javascript:void(0)" onclick="openModal(' . $row['schedule_id'] . ')" class="btn">Join Now</a>
        </div>
        ';
            }

            echo '</div>';
        } else {
            echo '<p>No classes available at the moment.</p>';
        }

        // Close the connection
        mysqli_close($conn);
        ?>


        <!-- Events Section -->
        <div class="section-header" style="margin-top: 4em;">
            <h1>Events</h1>
            <p>Don't miss out on our exciting fitness events and workshops!</p>
        </div>
        <div class="grid">
            <div class="card">
                <img src="images/Marathon.jpg" alt="Marathon Event">
                <h3>Annual Marathon</h3>
                <p>Participate in our marathon and challenge your endurance.</p>
            </div>
            <div class="card">
                <img src="images/nutrition.jpg" alt="Nutrition Seminar">
                <h3>Nutrition Seminar</h3>
                <p>Learn to craft a healthy diet plan with expert nutritionists.</p>
            </div>
            <div class="card">
                <img src="images/body_building.jpg" alt="Bodybuilding Event">
                <h3>Bodybuilding Bootcamp</h3>
                <p>Level up your fitness game with our intense bodybuilding workshop.</p>
            </div>
            <div class="card">
                <img src="images/fitness_.jpg" alt="Fitness Challenge">
                <h3>Fitness Challenge</h3>
                <p>Compete with others and win exciting prizes in our fitness challenge.</p>
            </div>
        </div>
    </div>
    <form id="joinClassForm" method="POST" action="classes.php">
        <input type="hidden" name="schedule_id" value="">
        <input type="hidden" name="join_class" value="1">
    </form>

    <?php include 'Includes/footer.html'; ?>
</body>
<script>
    function openModal(scheduleId) {
        // Check if the user is logged in
        <?php if (!isset($_SESSION['user_id'])): ?>
            alert("You must log in to join a class.");
            return;
        <?php endif; ?>

        // Show the modal for confirmation
        const confirmJoin = confirm("Do you want to join this class?");
        if (confirmJoin) {
            // Populate the hidden form and submit it
            const form = document.getElementById('joinClassForm');
            form.schedule_id.value = scheduleId;
            form.submit();
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
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