<?php include 'Includes/header.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitZone Fitness Center</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #000;
            color: #fff;
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)),
                url('images/fitness.jpg') center/cover;
            padding: 120px 0;
            color: #ff6600;
            text-align: center;
        }

        .hero-section h1 {
            font-size: 4rem;
            margin-bottom: 20px;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .hero-section p {
            font-size: 1.2rem;
            margin-bottom: 30px;
            max-width: 800px;
            margin: 0 auto;
            line-height: 1.6;
        }

        /* About Us Section */
        .about-us {
            background-color: #000;
            color: #fff;
            padding: 50px 20px;
            border-bottom: 4px solid #ff6600;
        }

        .about-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .about-header h1 {
            font-size: 3.5rem;
            color: #ff6600;
            text-transform: uppercase;
            letter-spacing: 3px;
            margin-bottom: 0.5em;
        }

        .about-header p {
            font-size: 1.3rem;
            color: #ccc;
        }

        /* Services Section */
        .services {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2em;
            margin-top: 3em;
            padding: 0 2em;
        }

        .service {
            background: #000;
            border-radius: 10px;
            padding: 2em;
            text-align: center;
            color: #fff;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease-in-out;
            border: 2px solid #ff6600;
        }

        .service img {
            width: 100%;
            height: auto;
            border-radius: 10px;
            margin-bottom: 1em;
        }

        .service h3 {
            font-size: 1.8rem;
            color: #ff6600;
            margin-bottom: 1em;
        }

        .service p {
            font-size: 1.1rem;
        }

        .service:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 30px rgba(255, 102, 0, 0.3);
        }

        /* Our Trainers Section */
        .trainers {
            background: linear-gradient(135deg, #1c1c1c, #0d0d0d);
            color: #f4f4f4;
            padding: 60px 20px;
            text-align: center;
            border-top: 5px solid #ff5722;
        }

        .trainers-header h1 {
            font-size: 3rem;
            color: #ff5722;
            text-transform: uppercase;
            letter-spacing: 4px;
            margin-bottom: 0.5em;
        }

        .trainers-header p {
            font-size: 1.2rem;
            color: #ddd;
            margin-bottom: 2em;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
            line-height: 1.6;
        }

        .trainers-cards {
            display: flex;
            flex-wrap: wrap;
            gap: 1.5em;
            justify-content: center;
        }

        .trainer-card {
            background: #262626;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.4);
            transition: transform 0.3s, box-shadow 0.3s;
            max-width: 320px;
            flex: 1 1 calc(30% - 1em);
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            border: 3px solid transparent;
            cursor: pointer;
        }

        .trainer-card img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 1em;
            border: 4px solid #ff5722;
            transition: transform 0.3s ease-in-out;
        }

        .trainer-card h3 {
            font-size: 1.8rem;
            color: #ff5722;
            margin-bottom: 0.5em;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .trainer-card p {
            font-size: 1rem;
            color: #bbb;
            line-height: 1.6;
            margin-bottom: 1em;
        }

        .trainer-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 12px 30px rgba(255, 87, 34, 0.3);
            border-color: #ff5722;
        }

        .trainer-card:hover img {
            transform: scale(1.1);
        }
    </style>
</head>

<body>

    <section class="hero-section">
        <h1>Welcome to FitZone Fitness Center</h1>
        <p>Your fitness journey begins here. Achieve your fitness goals with the right equipment, training, and support!</p>
    </section>

    <section class="about-us">
        <div class="about-header">
            <h1>Why chose us?</h1>
            <p style="padding-left: 80PX; padding-right: 80px;">Choosing FitZone Fitness Center means you’re choosing a gym that truly cares about your fitness journey.
                We provide a comprehensive range of fitness services, including group classes, personal training, and advanced equipment,
                all in a supportive and motivating environment. Our flexible membership options, coupled with our experienced trainers,
                ensure you receive personalized guidance and value for your investment. Join FitZone and take the first step toward a healthier,
                stronger, and more confident you.</p>
        </div>

        <div class="services">
            <div class="service">
                <img src="images/bench_830010.png" alt="Modern Equipment">
                <h3>Modern Equipment</h3>
                <p>State-of-the-art equipment for all your fitness needs.</p>
            </div>
            <div class="service">
                <img src="images/education_13502967.png" alt="Healthy Nutrition Plan">
                <h3>Healthy Nutrition Plan</h3>
                <p>Personalized meal plans to achieve your fitness goals.</p>
            </div>
            <div class="service">
                <img src="images/trainer_3927985.png" alt="Professional Training">
                <h3>Professional Training</h3>
                <p>Guidance from certified trainers tailored to your needs.</p>
            </div>
            <div class="service">
                <img src="images/gym_15973754.png" alt="Group Classes">
                <h3>Group Classes</h3>
                <p>Motivational group classes like Zumba, Yoga, and more.</p>
            </div>
        </div>
    </section>

    <section class="trainers">
        <div class="trainers-header">
            <h1>Meet Our Trainers</h1>
            <p>Our team of certified trainers is here to help you achieve your fitness goals. Get personalized training from the best in the business.</p>
        </div>
        <div class="trainers-cards">
            <?php
            // Include database connection
            require 'db.php';

            // Fetch trainer data from the database
            $query = "SELECT trainer_name, specialization, bio, image_path FROM trainers";
            $result = $conn->query($query);

            // Check if trainers exist in the database
            if ($result && $result->num_rows > 0) {
                while ($trainer = $result->fetch_assoc()) {
            ?>
                    <div class="trainer-card">
                        <img src="admin/<?= htmlspecialchars($trainer['image_path'] ?: 'images/default.jpg') ?>" alt="<?= htmlspecialchars($trainer['trainer_name']) ?>">
                        <h3><?= htmlspecialchars($trainer['trainer_name']) ?></h3>
                        <p><?= htmlspecialchars($trainer['specialization']) ?></p>
                        <p><?= htmlspecialchars($trainer['bio']) ?></p>
                    </div>
            <?php
                }
            } else {
                // Display a message if no trainers are found
                echo "<p>No trainers are available at the moment. Please check back later.</p>";
            }
            ?>
        </div>
    </section>

    <?php include 'Includes/footer.html'; ?>
</body>

</html>