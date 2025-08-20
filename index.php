<?php
include 'Includes/header.php';
require 'db.php';

// Fetch upcoming classes and join with class details for better display
$query = "SELECT 
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
        cs.start_time ASC LIMIT 4";

$result = $conn->query($query);

if ($result === false) {
    echo "Error: " . $conn->error;
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitZone Fitness Center</title>

    <style>
        /* General Body Reset */
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            color: #fff;
            background-color: #111;
            overflow-x: hidden;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        /* Hero Section */
        .hero-section {
            position: relative;
            height: 80vh;
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            margin-top: 70px;
            justify-content: center;
        }

        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(180deg, rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.9));
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .hero-content {
            z-index: 1;
            text-align: center;
            max-width: 700px;
        }

        .hero-content h1 {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 15px;
            color: #ff6f61;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .hero-content p {
            font-size: 1.2rem;
            margin-bottom: 25px;
            color: #f0f0f0;
            opacity: 0.9;
        }

        .btn {
            display: inline-block;
            padding: 12px 30px;
            font-size: 1.1rem;
            font-weight: bold;
            color: #fff;
            background: linear-gradient(90deg, #ff6f61, #ff9d00);
            border-radius: 8px;
            text-decoration: none;
            box-shadow: 0 4px 15px rgba(255, 111, 97, 0.5);
            transition: all 0.3s ease;
        }

        .btn:hover {
            background: linear-gradient(90deg, #ff9d00, #ff6f61);
            transform: translateY(-5px);
        }

        /* Split Section */
        .split-section-dark {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 30px;
            padding: 60px 20px;
            background: #141414;
            color: #fff;
            flex-wrap: wrap;
        }

        .split-section-dark .image-container {
            flex: 1;
            max-width: 450px;
            height: 300px;
            overflow: hidden;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.5);
            position: relative;
        }

        .split-section-dark .image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease, filter 0.4s ease;
            filter: grayscale(20%);
        }

        .split-section-dark .image-container:hover img {
            transform: scale(1.05);
            filter: grayscale(0%);
        }

        .split-section-dark .content {
            flex: 1;
            text-align: left;
            max-width: 500px;
            animation: fadeIn 0.8s ease;
        }

        .split-section-dark .section-title {
            font-size: 2.2rem;
            color: #ff6f00;
            font-weight: bold;
            margin-bottom: 20px;
            line-height: 1.4;
        }

        .split-section-dark .section-text {
            font-size: 1.1rem;
            color: #ddd;
            margin-bottom: 30px;
            line-height: 1.8;
        }

        /* Latest Events Section */
        .latest-events {
            padding-top: 30px;
            background: #141414;
            color: #fff;
            text-align: center;
        }

        .latest-events .section-title {
            font-size: 2.5rem;
            margin-bottom: 30px;
            color: #ff6f00;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-weight: bold;
        }

        .slider {
            overflow: hidden;
            display: flex;
            justify-content: center;
            padding: 10px 0;
        }

        .slider-track {
            display: flex;
            justify-content: center;
            gap: 20px;
            transition: transform 0.5s ease-in-out;
        }

        .card {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 20px;
            width: 300px;
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.4);
            transition: transform 0.4s ease, box-shadow 0.4s ease;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.6);
        }

        .card-image {
            position: relative;
            overflow: hidden;
            border-radius: 12px;
        }

        .card-image img {
            width: 100%;
            display: block;
            transition: transform 0.4s ease;
        }

        .card-image:hover img {
            transform: scale(1.1);
        }

        .date-label {
            position: absolute;
            top: 12px;
            left: 12px;
            background: rgba(0, 0, 0, 0.7);
            color: #fff;
            padding: 8px 12px;
            font-size: 0.8rem;
            font-weight: bold;
            border-radius: 8px;
        }

        .card-content {
            padding: 15px 0;
            text-align: center;
        }

        .event-title {
            font-size: 1.6rem;
            color: #ff9900;
            margin-bottom: 8px;
            font-weight: bold;
        }

        .card-title {
            font-size: 1.2rem;
            color: #fff;
            margin-bottom: 12px;
            font-style: italic;
        }

        .card-location {
            font-size: 1rem;
            color: #ddd;
            line-height: 1.6;
        }

        /* Image Gallery */
        .image-gallery {
            padding: 60px 20px;
            background: #141414;
            text-align: center;
        }

        .gallery-title {
            font-size: 2.5rem;
            color: #ff9900;
            margin-bottom: 40px;
            text-transform: uppercase;
            font-weight: bold;
            letter-spacing: 1.5px;
        }

        /* Masonry Grid */
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .gallery-item {
            position: relative;
            overflow: hidden;
            border-radius: 12px;
            transition: transform 0.3s ease;
        }

        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease, filter 0.5s ease;
        }

        .gallery-item:hover img {
            transform: scale(1.1);
            filter: grayscale(50%) brightness(0.9);
        }

        .gallery-item.wide {
            grid-column: span 2;
        }

        .gallery-item.tall {
            grid-row: span 2;
        }


        /* Why Choose Us Section */
        .why-choose-us {
            padding: 10px;
            text-align: center;
            background: linear-gradient(to bottom, #141414, #1e1e1e);
            color: #fff;
        }

        .why-choose-us h2 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: #ff9900;
            text-transform: uppercase;
        }

        .why-choose-us h3 {
            font-size: 1.6rem;
            margin-bottom: 30px;
            color: #fff;
            font-weight: bold;
            opacity: 0.85;
        }

        .services {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-top: 40px;
        }

        .service {
            background: #222;
            border-radius: 12px;
            padding: 25px;
            text-align: center;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.6);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .service:hover {
            transform: scale(1.05);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.8);
        }

        .service img {
            width: 80px;
            height: 80px;
            margin-bottom: 15px;
            filter: drop-shadow(0 5px 5px rgba(0, 0, 0, 0.5));
            transition: transform 0.3s ease;
        }

        .service:hover img {
            transform: scale(1.15);
        }

        .service h3 {
            font-size: 1.6rem;
            color: #ff9900;
            margin-bottom: 10px;
        }

        .service p {
            font-size: 1rem;
            color: #ccc;
            line-height: 1.6;
            opacity: 0.8;
        }

        /* Testimonial Section */
        #testimonials {
            background: linear-gradient(to bottom, #1e1e1e, #141414);
            padding: 50px 20px;
            color: #fff;
        }

        .testimonial-heading h4 {
            font-size: 2rem;
            text-align: center;
            color: #ff9900;
            margin-bottom: 30px;
        }

        .testimonial-box-container {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .testimonial-box {
            background: #222;
            border-radius: 12px;
            padding: 15px;
            max-width: 270px;
            text-align: center;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.6);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .testimonial-box:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.8);
        }

        .profile-img img {
            border-radius: 50%;
            width: 70px;
            height: 70px;
            margin-bottom: 15px;
        }

        .reviews {
            margin: 8px 0;
            font-size: 1.1rem;
            color: #ffcc00;
        }

        .client-comment {
            font-size: 1rem;
            line-height: 1.6;
            color: #ddd;
        }
    </style>


</head>

<body>


    <header class="hero-section" style="background-image: url('images/cardio.jpg');">

        <div class="hero-overlay">
            <div class="hero-content">
                <h1>Hard Work is for Every Success</h1>
                <p>We Are The Best Consulting Agency</p>
                <a href="Pricing.php" class="btn">Our Services</a>
            </div>
        </div>
    </header>
    <section class="split-section-dark">
        <div class="image-container">
            <img src="images/maria.jpg" alt="Gym Image">
        </div>
        <div class="content">
            <h2 class="section-title">WE’RE A HIGH-QUALITY GYM DEDICATED TO AFFORDABLE HEALTH AND WELLNESS.</h2>
            <p class="section-text">
                Discover the ultimate fitness experience with top-notch facilities,
                cutting-edge equipment, and personalized training. We’re here to
                redefine how you achieve your health goals.
            </p>
        </div>
    </section>


    <section class="latest-events">
        <h2 class="section-title" style="font-size: 2.5rem; margin-bottom: 20px; color: #ff6f00;">Latest Classes</h2>
        <div class="container">
            <div class="slider">
                <div class="slider-track">
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <div class="card">
                                <div class="card-image">
                                    <img src="Admin/<?php echo htmlspecialchars($row['class_image']); ?>" alt="<?= htmlspecialchars($row['class_name']) ?>">
                                    <span class="date-label"><?= date("M d, Y", strtotime($row['start_time'])) ?></span>
                                </div>
                                <div class="card-content">
                                    <h4 class="event-title"><?= htmlspecialchars($row['class_name']) ?></h4>
                                    <h3 class="card-title"><?= htmlspecialchars($row['trainer_name']) ?></h3>
                                    <p class="card-location">
                                        Time: <?= date("h:i A", strtotime($row['start_time'])) ?> - <?= date("h:i A", strtotime($row['end_time'])) ?><br>
                                    </p>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p>No upcoming classes or events at the moment.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
    <section class="image-gallery">
        <h2 class="gallery-title">Our Gallery</h2>
        <div class="gallery-grid">
            <div class="gallery-item">
                <img src="images/postitem1.jpg" alt="Gallery Image 1">
            </div>
            <div class="gallery-item tall">
                <img src="images/postitem2.jpg" alt="Gallery Image 2">
            </div>
            <div class="gallery-item wide">
                <img src="images/postitem3.jpg" alt="Gallery Image 3">
            </div>
            <div class="gallery-item">
                <img src="images/postitem4.jpg" alt="Gallery Image 4">
            </div>
            <div class="gallery-item wide">
                <img src="images/postitem5.jpg" alt="Gallery Image 5">
            </div>
            <div class="gallery-item tall">
                <img src="images/postitem6.jpg" alt="Gallery Image 6">
            </div>
            <div class="gallery-item">
                <img src="images/postitem7.jpg" alt="Gallery Image 7">
            </div>
            <div class="gallery-item">
                <img src="images/postitem8.jpg" alt="Gallery Image 8">
            </div>
        </div>
    </section>




    <section class="why-choose-us">
        <h2 style="font-size: 2.5rem; margin-bottom: 20px;"> Why Choose Us?</h2>
        <h3 style="font-size: 2rem; margin-bottom: 40px; color: #fff; font-weight: bold;">Push Your Limits Forward</h3>
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


    <section id="testimonials">
        <div class="testimonial-heading">
            <h4>What Our Clients Say</h4>
        </div>
        <div class="testimonial-box-container">
            <div class="testimonial-box">
                <div class="profile">
                    <div class="profile-img">
                        <img src="https://cdn3.iconfinder.com/data/icons/avatars-15/64/_Ninja-2-512.png" alt="Profile" />
                    </div>
                    <div class="name-user">
                        <strong>Amila Perera</strong>
                        <span>@amila_perera</span>
                    </div>
                    <div class="reviews">★★★★★</div>
                </div>
                <p class="client-comment">"I have been training at this gym for months now, and the progress I've seen is amazing. The trainers are highly knowledgeable, and the equipment is top-notch. Highly recommend!"</p>
            </div>
            <div class="testimonial-box">
                <div class="profile">
                    <div class="profile-img">
                        <img src="https://cdn3.iconfinder.com/data/icons/avatars-15/64/_Ninja-2-512.png" alt="Profile" />
                    </div>
                    <div class="name-user">
                        <strong>Susan Wijesinghe</strong>
                        <span>@susan_wijesinghe</span>
                    </div>
                    <div class="reviews">★★★★★</div>
                </div>
                <p class="client-comment">"The best gym I've been to in Sri Lanka! The environment is so motivating, and the personal trainers go above and beyond to help you reach your fitness goals. I’ve never felt more confident!"</p>
            </div>
            <div class="testimonial-box">
                <div class="profile">
                    <div class="profile-img">
                        <img src="https://cdn3.iconfinder.com/data/icons/avatars-15/64/_Ninja-2-512.png" alt="Profile" />
                    </div>
                    <div class="name-user">
                        <strong>Rashmi Jayasinghe</strong>
                        <span>@rashmi_jayasinghe</span>
                    </div>
                    <div class="reviews">★★★★☆</div>
                </div>
                <p class="client-comment">"I love the variety of classes offered, from HIIT to yoga. The gym has everything I need, and the trainers are always available for guidance. My only suggestion is to increase class timings during peak hours." </p>
            </div>
        </div>
    </section>

    <?php include 'Includes/footer.html'; ?>



</body>


</html>