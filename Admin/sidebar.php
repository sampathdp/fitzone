
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_role'])) {
    header("Location: signin.php");
    exit;
}

$user_role = $_SESSION['user_role']; // Possible values: 'admin', 'staff'

// Define role-based access control
$menu_items = [
    'Dashboard Overview' => ['href' => 'index.php', 'roles' => ['admin', 'staff']],
    'Members Management' => ['href' => 'members_management.php', 'roles' => ['admin']],
    'Membership Types' => ['href' => 'membership_management.php', 'roles' => ['admin']],
    'Class Management' => ['href' => 'manage_classes.php', 'roles' => ['admin']],
    'Class Schedules' => ['href' => 'class_schedule.php', 'roles' => ['admin', 'staff']],
    'Manage Blogs' => ['href' => 'manage_blogposts.php', 'roles' => ['admin', 'staff']],
    'Trainer Management' => ['href' => 'manage_trainers.php', 'roles' => ['admin']],
    'Inquiries' => ['href' => 'manage_inquiries.php', 'roles' => ['admin', 'staff']],
    'Manage Payments' => ['href' => 'payments.php', 'roles' => ['admin']],
    'User Management' => ['href' => 'signup.php', 'roles' => ['admin']],
    'Log Out' => ['href' => 'logout.php', 'roles' => ['admin', 'staff']],
];
?>

<div class="sidebar">
    <ul>
        <?php foreach ($menu_items as $label => $item): ?>
            <?php if (in_array($user_role, $item['roles'])): ?>
                <li><a href="<?= htmlspecialchars($item['href']); ?>"><?= htmlspecialchars($label); ?></a></li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
</div>

<!-- 
<div class="sidebar">
    <ul>
        <li><a href="index.php">Dashboard Overview</a></li>
        
            <li><a href="members_management.php">Members Management</a></li>
            <li><a href="membership_management.php">Membership Types</a></li>
            <li><a href="manage_classes.php">Class Management</a></li>
            <li><a href="class_schedule.php">Class Schedules</a></li>
            <li><a href="manage_trainers.php">Trainer Management</a></li>
            <li><a href="manage_inquiries.php">Inquiries</a></li>
            <li><a href="manage_blogposts.php">Blogs</a></li>
            <li><a href="#settings">Settings</a></li>
        
        <li><a href="logout.php">Log Out</a></li>
    </ul>
</div> -->
<style>
    .sidebar {
        width: 250px;
        background-color: #2c3e50;
        color: white;
        padding-top: 20px;
        position: fixed;
        height: 100%;
        top: 0;
        left: 0;
    }

    .sidebar ul {
        list-style-type: none;
    }

    .sidebar ul li {
        padding: 10px;
        text-align: left;
    }

    .sidebar ul li a {
        color: white;
        text-decoration: none;
        display: block;
        padding: 10px;
        font-size: 16px;
    }

    .sidebar ul li a:hover {
        background-color: #34495e;
    }
</style>