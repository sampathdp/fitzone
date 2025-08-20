<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$is_logged_in = isset($_SESSION['user_id']);

?>

<style>
    #container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    #fitzone-navbar {
        background-color: white;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 10px 0;
        position: fixed;
        width: 100%;
        top: 0;
        z-index: 1000;
    }
    #navbar-brand {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    #logo {
        height: 40px;
        width: 40px;
        border-radius: 4px;
        object-fit: cover;
    }

    #navbar-brand span {
        font-size: 1.2rem;
        font-weight: bold;
        color: black;
    }

    /* Navigation Links */
    #nav-links {
        list-style: none;
        display: flex;
        gap: 20px;
    }

    #nav-links li a {
        text-decoration: none;
        color: gray;
        font-weight: 500;
        transition: color 0.3s ease;
        padding: 5px 0;
        position: relative;
    }

    #nav-links li a:hover {
        color: black;
    }

    #nav-links li a::after {
        content: '';
        display: block;
        width: 0;
        height: 2px;
        background: #6200ea;
        transition: width 0.3s ease;
        position: absolute;
        bottom: -2px;
        left: 0;
    }

    #nav-links li a:hover::after {
        width: 100%;
    }

    #join-now-btn {
        background-color: #ffc107;
        color: black;
        text-decoration: none;
        padding: 8px 20px;
        border-radius: 50px;
        font-weight: bold;
        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    #join-now-btn:hover {
        background-color: #ff9800;
        transform: scale(1.05);
    }

    #user-icon {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #ff4b2b;
        transition: background-color 0.3s ease;
        overflow: hidden;
        border: 2px solid white;
    }

    #user-icon-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
    }

    #user-icon:hover {
        background-color: #ff2a1a;
    }

</style>
<header>
    <nav id="fitzone-navbar">
        <div id="container">
            <!-- Logo and Brand Name -->
            <div id="navbar-brand">
                <img src="images/logo.png" alt="FitZone Logo" id="logo">
                <span>FitZone Fitness Center</span>
            </div>
            <!-- Navigation Links -->
            <ul id="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="classes.php">Classes</a></li>
                <li><a href="about_us.php">About</a></li>
                <li><a href="Pricing.php">Pricing</a></li>
                <li><a href="Blog.php">Blog</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>

            <?php if (!$is_logged_in): ?>
                <a href="./signin.php" id="join-now-btn">Join Now</a>
            <?php else: ?>

                <a href="./profile.php" id="user-icon">
                    <img src="images/logo.png" alt="User Icon" id="user-icon-img">
                </a>
            <?php endif; ?>

        </div>
    </nav>
</header>

<script>
    const menuToggle = document.getElementById('menu-toggle');
    const navLinks = document.getElementById('nav-links');

    menuToggle.addEventListener('click', () => {
        navLinks.classList.toggle('active');
    });
</script>