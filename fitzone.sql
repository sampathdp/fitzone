-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jan 07, 2025 at 03:01 PM
-- Server version: 5.7.26
-- PHP Version: 7.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fitzone`
--

-- --------------------------------------------------------

--
-- Table structure for table `blogposts`
--

DROP TABLE IF EXISTS `blogposts`;
CREATE TABLE IF NOT EXISTS `blogposts` (
  `post_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `author_id` int(11) NOT NULL,
  `category` varchar(100) NOT NULL,
  `publication_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `blog_image` text NOT NULL,
  PRIMARY KEY (`post_id`),
  KEY `author_id` (`author_id`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `blogposts`
--

INSERT INTO `blogposts` (`post_id`, `title`, `content`, `author_id`, `category`, `publication_date`, `blog_image`) VALUES
(14, '10 Essential Strength Training Exercises for Beginners', 'Strength training is a crucial component of any fitness regimen, especially for beginners looking to build muscle and improve overall health. This blog post outlines 10 essential strength training exercises that are perfect for those just starting out. Each exercise, including squats, lunges, and push-ups, is explained in detail with step-by-step instructions and tips for proper form. Additionally, the post discusses the importance of starting with lighter weights and gradually increasing resistance to avoid injury. With a focus on full-body workouts, readers will learn how to create a balanced routine that targets all major muscle groups. By incorporating these exercises into their fitness journey, beginners can build a solid foundation for long-term success.', 3, 'Workout', '2025-01-05 17:27:35', 'uploads/BlogPost/1736098055_21-Day-Fix-Dirty-30-Workouts-Outline-768x512.jpg'),
(15, 'The Healing Power of Yoga: Poses for Emotional Well-Being', 'Yoga is not just a physical practice; it also offers profound benefits for emotional well-being. This blog post explores specific yoga poses that can help alleviate anxiety, depression, and stress. Poses such as Childâ€™s Pose, Cat-Cow, and Bridge Pose are highlighted for their ability to promote relaxation and emotional balance. Each pose is accompanied by detailed instructions and insights into its emotional benefits, making it easy for readers to incorporate them into their daily routine. The post also discusses the importance of mindfulness and breathwork in enhancing the emotional healing process. By embracing these yoga practices, individuals can cultivate a deeper connection with themselves and foster a sense of inner peace.', 3, 'Yoga', '2025-01-05 17:28:33', 'uploads/BlogPost/1736098113_R.jpeg'),
(16, 'How to Choose the Right Running Shoes for Your Feet', 'Selecting the right running shoes is essential for comfort and injury prevention. This blog post provides a comprehensive guide to choosing the perfect pair of running shoes based on individual foot types and running styles. Readers will learn about the different types of shoes available, including neutral, stability, and motion control options. The post also covers the importance of getting properly fitted at a specialty running store and trying on shoes with the socks you plan to wear while running. Additionally, tips for breaking in new shoes and recognizing when itâ€™s time for a replacement are included. With the right footwear, runners can enhance their performance and enjoy a more comfortable running experience.', 3, 'Running', '2025-01-05 17:29:05', 'uploads/BlogPost/1736098145_R (1).jpeg'),
(17, 'Meal Prep 101: How to Plan and Prepare Healthy Meals', 'Meal prepping is a game-changer for anyone looking to maintain a healthy diet amidst a busy lifestyle. This blog post offers a step-by-step guide to meal prep, including tips on planning, shopping, and cooking. Readers will learn how to create a balanced meal plan that incorporates a variety of nutrients, ensuring they stay energized throughout the week. The post also includes practical advice on batch cooking, portioning meals, and storing them for freshness. With easy-to-follow recipes and a focus on whole foods, readers will discover how meal prepping can save time, reduce stress, and promote healthier eating habits. By the end of the post, readers will be equipped to take control of their nutrition and enjoy delicious, homemade meals.', 3, 'Nutrition', '2025-01-05 17:29:41', 'uploads/BlogPost/1736098181_OIP (2).jpeg'),
(18, 'The Importance of Sleep for Overall Wellness', 'Sleep is often overlooked in discussions about health and wellness, yet it plays a vital role in our physical and mental well-being. This blog post delves into the importance of quality sleep, exploring how it affects mood, cognitive function, and physical health. Readers will learn about the recommended hours of sleep for different age groups and the consequences of sleep deprivation. The post also offers practical tips for improving sleep hygiene, such as establishing a bedtime routine, creating a comfortable sleep environment, and limiting screen time before bed. By prioritizing sleep, individuals can enhance their overall wellness and improve their quality of life.', 3, 'Wellness', '2025-01-05 17:30:33', 'uploads/BlogPost/1736098233_f0f69753899696b9438e5a2baf8f1b5b.jpg'),
(19, '5 Fun Group Workouts to Try with Friends', 'Working out doesnâ€™t have to be a solitary activity! This blog post highlights five fun group workouts that you can enjoy with friends, making fitness a social experience. From dance classes to outdoor boot camps, these workouts are designed to be engaging and motivating. Each workout is described in detail, including what to expect, the benefits of group exercise, and tips for getting the most out of the experience. The post emphasizes the importance of camaraderie and accountability in achieving fitness goals, encouraging readers to invite friends and make exercise a regular part of their social lives. With these group workouts, fitness becomes a fun and enjoyable journey.', 3, 'Workout', '2025-01-05 17:31:22', 'uploads/BlogPost/1736098282_shutterstock_2310881869-scaled.jpg'),
(20, 'Yoga for Beginners: A Guide to Your First Class', 'Starting a yoga practice can be intimidating, especially for beginners. This blog post serves as a comprehensive guide to attending your first yoga class, providing essential tips and insights to ease any apprehensions. Readers will learn about the different styles of yoga, what to expect in a typical class, and how to choose the right class for their skill level. The post also covers essential items to bring, such as a yoga mat and water bottle, and offers advice on how to approach the practice with an open mind. Additionally, it discusses the importance of listening to your body and not comparing yourself to others. By following this guide, beginners can confidently step onto the mat and embark on their yoga journey.', 3, 'Yoga', '2025-01-05 17:32:34', 'uploads/BlogPost/1736098354_b6509-16874419541591-1920.webp'),
(21, 'Understanding Macronutrients: The Building Blocks of Nutrition', 'Nutrition can be overwhelming, but understanding macronutrients is key to making informed dietary choices. This blog post breaks down the three main macronutrientsâ€”carbohydrates, proteins, and fatsâ€”and their roles in the body. Readers will learn how each macronutrient contributes to energy levels, muscle repair, and overall health. The post also discusses the importance of balancing these nutrients in daily meals and provides practical tips for incorporating them into a healthy diet. With easy-to-follow guidelines and examples of macronutrient-rich foods, readers will gain the knowledge needed to optimize their nutrition and support their fitness goals.', 3, 'Nutrition', '2025-01-05 17:33:27', 'uploads/BlogPost/1736098407_R (4).jpeg'),
(22, 'Mindfulness Practices for Everyday Life', 'Mindfulness is a powerful tool for enhancing overall wellness and reducing stress. This blog post introduces readers to various mindfulness practices that can be easily integrated into daily life. From mindful breathing exercises to gratitude journaling, each practice is explained with step-by-step instructions and tips for getting started. The post emphasizes the benefits of mindfulness, including improved focus, emotional regulation, and a greater sense of well-being. By incorporating these practices into their routines, readers can cultivate a more present and balanced mindset, ultimately leading to a healthier and more fulfilling life.', 3, 'Wellness', '2025-01-05 17:33:41', 'uploads/BlogPost/1736098421_R (3).jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `classbookings`
--

DROP TABLE IF EXISTS `classbookings`;
CREATE TABLE IF NOT EXISTS `classbookings` (
  `booking_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `schedule_id` int(11) NOT NULL,
  `booking_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('Confirmed','Cancelled','Attended') DEFAULT 'Confirmed',
  PRIMARY KEY (`booking_id`),
  KEY `customer_id` (`customer_id`),
  KEY `schedule_id` (`schedule_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `classbookings`
--

INSERT INTO `classbookings` (`booking_id`, `customer_id`, `schedule_id`, `booking_date`, `status`) VALUES
(1, 3, 4, '2025-01-07 10:45:42', 'Confirmed'),
(2, 3, 2, '2025-01-07 10:45:52', 'Confirmed'),
(3, 3, 3, '2025-01-07 10:47:22', 'Confirmed');

-- --------------------------------------------------------

--
-- Table structure for table `classschedules`
--

DROP TABLE IF EXISTS `classschedules`;
CREATE TABLE IF NOT EXISTS `classschedules` (
  `schedule_id` int(11) NOT NULL AUTO_INCREMENT,
  `class_id` int(11) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `max_slots` int(11) DEFAULT NULL,
  `used_slots` int(11) NOT NULL,
  PRIMARY KEY (`schedule_id`),
  KEY `class_id` (`class_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `classschedules`
--

INSERT INTO `classschedules` (`schedule_id`, `class_id`, `start_time`, `end_time`, `max_slots`, `used_slots`) VALUES
(1, 1, '2025-01-09 16:00:00', '2025-01-09 18:00:00', 4, 0),
(2, 2, '2025-01-10 19:00:00', '2025-01-10 21:00:00', 8, 1),
(3, 3, '2025-01-12 08:00:00', '2025-01-12 11:00:00', 20, 1),
(4, 5, '2025-01-08 18:00:00', '2025-01-08 21:01:00', 6, 1);

-- --------------------------------------------------------

--
-- Table structure for table `customerinquiries`
--

DROP TABLE IF EXISTS `customerinquiries`;
CREATE TABLE IF NOT EXISTS `customerinquiries` (
  `inquiry_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `submission_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('Open','In Progress','Resolved') DEFAULT 'Open',
  `assigned_staff_id` int(11) DEFAULT NULL,
  `resolution_date` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`inquiry_id`),
  KEY `customer_id` (`customer_id`),
  KEY `assigned_staff_id` (`assigned_staff_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customerinquiries`
--

INSERT INTO `customerinquiries` (`inquiry_id`, `customer_id`, `subject`, `message`, `submission_date`, `status`, `assigned_staff_id`, `resolution_date`) VALUES
(1, 3, 'Inquiry About Class Schedules', 'I would like to know more about the available classes and their timings. Could you provide me with a detailed schedule?', '2025-01-07 10:24:14', 'Open', NULL, NULL),
(2, 3, 'Request for Personal Training Information', 'I am looking for personal training services. Could you share information about the trainers and pricing for these sessions?', '2025-01-07 10:24:32', 'Open', NULL, NULL),
(3, 3, 'Membership Options', ' I am interested in joining the gym. Could you provide details on the membership plans and any ongoing promotions?', '2025-01-07 10:33:38', 'Open', NULL, NULL),
(4, 4, 'Event Participation', 'I saw an upcoming event on your website. Can you provide more details about how to register and participate?', '2025-01-07 10:37:04', 'Open', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
CREATE TABLE IF NOT EXISTS `customers` (
  `customer_id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `registration_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `last_login` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`customer_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`customer_id`, `email`, `password_hash`, `first_name`, `last_name`, `phone_number`, `date_of_birth`, `gender`, `registration_date`, `last_login`) VALUES
(1, 'sampathdp128@gmail.com', '$2y$10$6IHpDDK68SBE9E96e0nMle5kO6bpYWCcrFDXJ0y55cCcxXY02zfPi', 'sampath', 'Dananjaya', '0754453334', '1999-10-28', 'Male', '2025-01-05 17:41:52', '2025-01-06 18:45:37'),
(3, 'sampathdp28@gmail.com', '$2y$10$lQRljEpLpC7TDO/llyOF2OWzH3hzaknpaxpkSZGpnqmQ5QlSImzK.', 'sampath', 'Dananjaya', '0754453334', '2025-01-06', 'Male', '2025-01-06 09:53:28', '2025-01-07 10:43:19'),
(4, 'ssd@gmail.com', '$2y$10$7qVClMpGX3xPAiMkUujfLuJD1QlUL/xPPY8roE3CkjcJvfdcYh8x.', 'sampath', 'Dananjaya', '0754453334', '2016-01-07', 'Male', '2025-01-07 10:36:09', '2025-01-07 10:36:18');

-- --------------------------------------------------------

--
-- Table structure for table `fitnessclasses`
--

DROP TABLE IF EXISTS `fitnessclasses`;
CREATE TABLE IF NOT EXISTS `fitnessclasses` (
  `class_id` int(11) NOT NULL AUTO_INCREMENT,
  `class_name` varchar(100) NOT NULL,
  `description` text,
  `trainer_id` int(11) DEFAULT NULL,
  `class_image` varchar(255) NOT NULL,
  PRIMARY KEY (`class_id`),
  KEY `trainer_id` (`trainer_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `fitnessclasses`
--

INSERT INTO `fitnessclasses` (`class_id`, `class_name`, `description`, `trainer_id`, `class_image`) VALUES
(1, 'Personalized Fitness Training', 'Achieve your fitness goals with tailored workout plans! Join Nimal for one-on-one sessions that focus on your unique needs and help you transform your body.', 1, 'uploads/Classes/1736019352_LVU07842.jpg'),
(2, 'Mindful Yoga Flow', 'Find your inner peace and strength! Join Anjali for a rejuvenating yoga class that blends various styles, focusing on mindfulness and holistic wellness.', 2, 'uploads/Classes/1736019770_shutterstock_400864777.jpg'),
(3, 'Strength Camp & Conditioning Bootcamp', 'Elevate your performance! Join Rohan for a high-energy bootcamp designed to build strength, improve endurance, and enhance athletic performance.', 3, 'uploads/Classes/1736019780_pexels-photo-2261485.webp'),
(5, 'High-Energy Group Fitness', 'Get fit together! Join Priya for dynamic group fitness classes that combine fun and motivation, perfect for all fitness levels looking to stay active.', 4, 'uploads/Classes/1736019789_Her-Fitness.jpg'),
(6, 'Nutrition Camp & Wellness Coaching', 'Transform your eating habits! Join Sanjay for personalized nutrition coaching that empowers you to make healthier choices and achieve your dietary goals.', 1, 'uploads/Classes/1736019794_Nutrition-coaching.png');

-- --------------------------------------------------------

--
-- Table structure for table `membershiptypes`
--

DROP TABLE IF EXISTS `membershiptypes`;
CREATE TABLE IF NOT EXISTS `membershiptypes` (
  `membership_id` int(11) NOT NULL AUTO_INCREMENT,
  `membership_name` varchar(100) NOT NULL,
  `description` text,
  `monthly_fee` decimal(10,2) NOT NULL,
  `annual_fee` decimal(10,2) NOT NULL,
  `max_class_bookings` int(11) DEFAULT NULL,
  `personal_training_sessions` int(11) DEFAULT NULL,
  `nutrition_consultations` int(11) DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  PRIMARY KEY (`membership_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `membershiptypes`
--

INSERT INTO `membershiptypes` (`membership_id`, `membership_name`, `description`, `monthly_fee`, `annual_fee`, `max_class_bookings`, `personal_training_sessions`, `nutrition_consultations`, `status`) VALUES
(1, 'Basic', 'Entry-level membership with basic access', '3500.00', '38000.00', 4, 0, 0, 'Active'),
(2, 'Premium', 'Enhanced membership with more benefits', '4200.00', '43500.00', 8, 2, 1, 'Active'),
(3, 'Elite', 'Top-tier membership with full access', '5000.00', '55000.00', 12, 4, 2, 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `paymenttransactions`
--

DROP TABLE IF EXISTS `paymenttransactions`;
CREATE TABLE IF NOT EXISTS `paymenttransactions` (
  `transaction_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `payment_receipt` varchar(255) NOT NULL,
  `membership_id` varchar(255) NOT NULL,
  `verifydate` timestamp NULL DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  PRIMARY KEY (`transaction_id`),
  KEY `customer_id` (`customer_id`),
  KEY `membership_id` (`membership_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `paymenttransactions`
--

INSERT INTO `paymenttransactions` (`transaction_id`, `customer_id`, `payment_receipt`, `membership_id`, `verifydate`, `status`) VALUES
(1, 3, 'R.jpg', '1', '2025-01-07 05:54:22', 'Pending'),
(2, 3, 'OIP.jpg', '3', NULL, 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `staffaccounts`
--

DROP TABLE IF EXISTS `staffaccounts`;
CREATE TABLE IF NOT EXISTS `staffaccounts` (
  `staff_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','staff') NOT NULL,
  `access_level` enum('read','write','admin') NOT NULL,
  PRIMARY KEY (`staff_id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `staffaccounts`
--

INSERT INTO `staffaccounts` (`staff_id`, `username`, `password_hash`, `role`, `access_level`) VALUES
(1, 'sampath1028', '$2y$10$5O8sZ8fllCBKHhtDhW4fc.HU83r5ArllGpoWF8DCH7jtVTIsxoQl6', 'admin', 'read'),
(2, 'www1', '$2y$10$RwRPy57m0LlnKyvJdDxTuegRuir/g6YjqSIL0v2FFXm828iBmNHfq', 'admin', 'read'),
(3, 'admin', '$2y$10$jiXKzADKBcEQK2DX.tg31et4j.S2kWfGNqLaX3KxwOQN6QFX2qOUW', 'admin', 'read'),
(4, 'user', '$2y$10$GQOefUkszg/XXh/DComcZu8OwvFa7Y8sAgu6UwJ1rYfjUhX0ur7cG', 'staff', 'read'),
(5, 'aa', '$2y$10$0Xq0oKp0FxQ1nCSWOwMr6.L5LAeUOZ/xB/7EdbdflCrICj.AW3Jg6', 'staff', 'read');

-- --------------------------------------------------------

--
-- Table structure for table `trainers`
--

DROP TABLE IF EXISTS `trainers`;
CREATE TABLE IF NOT EXISTS `trainers` (
  `trainer_id` int(11) NOT NULL AUTO_INCREMENT,
  `trainer_name` varchar(100) NOT NULL,
  `specialization` varchar(100) DEFAULT NULL,
  `hourly_rate` decimal(10,2) DEFAULT NULL,
  `experience_years` int(11) DEFAULT NULL,
  `bio` varchar(225) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`trainer_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `trainers`
--

INSERT INTO `trainers` (`trainer_id`, `trainer_name`, `specialization`, `hourly_rate`, `experience_years`, `bio`, `image_path`) VALUES
(1, 'Nimal Perera', 'Personal Training', '3000.00', 5, 'Nimal is a certified personal trainer with over 5 years of experience in helping clients achieve their fitness goals through personalized workout plans and nutrition guidance.', 'uploads/trainers/1736012103_7fc96392f8d7012171a46072710addfd.jpg'),
(2, 'Anjali Fernando', 'Yoga Instructor', '2500.00', 7, 'Anjali is a passionate yoga instructor with 7 years of experience in teaching various styles of yoga, focusing on mindfulness and holistic wellness.', 'uploads/trainers/1736012114_Shivani-Gupta-1068x927.jpg'),
(3, 'Rohan Silva', 'Strength and Conditioning', '3500.00', 8, 'Rohan specializes in strength and conditioning training, with 8 years of experience in helping athletes improve their performance and achieve their fitness goals.', 'uploads/trainers/1736012134_OIP.jpeg'),
(4, 'Priya Kumari', 'Group Fitness', '2000.00', 4, 'Priya is a dynamic group fitness instructor with 4 years of experience leading high-energy classes that motivate and inspire participants.', 'uploads/trainers/1736012146_the-thickest-an82nw356r-1080x1209.jpg'),
(5, 'Sanjay Rajapaksha', 'Nutrition Coaching', '4000.00', 6, 'Sanjay is a certified nutrition coach with 6 years of experience in helping clients develop healthy eating habits and achieve their dietary goals.', 'uploads/trainers/1736012178_OIP (1).jpeg');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
