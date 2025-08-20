<?php

if (!empty($_SESSION['message'])) {
    echo '<div class="message-box success">' . $_SESSION['message'] . '</div>';
    unset($_SESSION['message']);
}

if (!empty($_SESSION['error'])) {
    echo '<div class="message-box error">' . $_SESSION['error'] . '</div>';
    unset($_SESSION['error']);
}

?>
<?php
include('db.php');

// Fetch data with error handling
$members = $conn->query("SELECT COUNT(*) AS total_members FROM Customers")->fetch_assoc()['total_members'];
$classes = $conn->query("SELECT COUNT(*) AS total_classes FROM ClassSchedules WHERE DATE(start_time) = CURDATE()")->fetch_assoc()['total_classes'];

// Fetch inquiries for the table with customer names
$result = $conn->query("
    SELECT Customers.first_name AS customer_name, CustomerInquiries.subject,CustomerInquiries.submission_date, 
        CustomerInquiries.status  
    FROM CustomerInquiries 
    INNER JOIN Customers 
    ON CustomerInquiries.customer_id = Customers.customer_id 
    ORDER BY CustomerInquiries.submission_date DESC 
    LIMIT 5
");

if (!$result) {
    die("Query failed: " . $conn->error);
}

$inquiry_data = $result->fetch_all(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .main-content {
            margin-left: 250px;
            width: calc(100% - 250px);
            padding: 20px;
            background-color: #f4f4f4;
            min-height: 100vh;
        }

        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
        }

        .overview {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .card {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        .card h2 {
            color: #34495e;
            font-size: 24px;
            margin-bottom: 10px;
        }

        .card p {
            font-size: 18px;
            color: #7f8c8d;
        }

        .inquiries {
            margin-top: 20px;
        }

        .inquiries h2 {
            font-size: 28px;
            color: #34495e;
            margin-bottom: 20px;
        }

        .inquiries table {
            width: 100%;
            border-collapse: collapse;
            background-color: #ffffff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        .inquiries table thead {
            background-color: #34495e;
            color: white;
        }

        .inquiries th,
        .inquiries td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .inquiries tr:hover {
            background-color: #f1f1f1;
        }

        .inquiries th {
            font-weight: bold;
        }

        .inquiries td {
            color: #2c3e50;
        }

        .footer {
            text-align: center;
            padding: 15px;
            background-color: #2c3e50;
            color: white;
            position: fixed;
            width: 100%;
            bottom: 0;
        }

        .footer p {
            margin: 0;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <?php include('sidebar.php'); ?>

    <div class="main-content">
        <h1>Dashboard</h1>

        <div class="overview">
            <div class="card">
                <h2>Members</h2>
                <p>Total: <?php echo htmlspecialchars($members); ?></p>
            </div>
            <div class="card">
                <h2>Classes Today</h2>
                <p>Scheduled: <?php echo htmlspecialchars($classes); ?></p>
            </div>
        </div>

        <div class="inquiries">
            <h2>Recent Inquiries</h2>
            <table>
                <thead>
                    <tr>
                        <th>Customer Name</th>
                        <th>Subject</th>
                        <th>Submission Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($inquiry_data as $inquiry): ?>
                        <tr>
                            <td><?php echo $inquiry['customer_name']; ?></td>
                            <td><?php echo $inquiry['subject']; ?></td>
                            <td><?php echo $inquiry['submission_date']; ?></td>
                            <td><?php echo $inquiry['status']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </div>
</body>

</html>