<?php
require 'db.php';

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $inquiry_id = (int)$_POST['inquiry_id'];
    $status = $_POST['status'];
    $resolution_date = ($status === 'Resolved') ? date('Y-m-d H:i:s') : NULL;

    $stmt = $conn->prepare("UPDATE CustomerInquiries SET status = ?, resolution_date = ? WHERE inquiry_id = ?");
    if ($stmt) {
        $stmt->bind_param("ssi", $status, $resolution_date, $inquiry_id);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: manage_inquiries.php");
    exit;
}

// Retrieve all inquiries with customer details
$query = "
    SELECT 
        ci.inquiry_id, ci.subject, ci.message, ci.status, ci.submission_date,
        c.first_name, c.last_name, c.email AS customer_email
    FROM 
        CustomerInquiries ci
    JOIN 
        Customers c ON ci.customer_id = c.customer_id
    ORDER BY 
        ci.submission_date DESC
";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Customer Inquiries</title>
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
    }

    .container {
        margin-left: 250px;
        padding: 20px;
        width: calc(100% - 250px);
    }

    h1 {
        text-align: center;
        color: #2c3e50;
        margin-bottom: 30px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        background-color: #ffffff;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        overflow: hidden;
    }

    table thead {
        background-color: #34495e;
        color: white;
    }

    table th,
    table td {
        padding: 15px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    table tbody tr:hover {
        background-color: #f1f1f1;
    }

    table th {
        font-weight: bold;
    }

    table td {
        color: #2c3e50;
    }

    .status-select {
        padding: 5px;
        border-radius: 4px;
        border: 1px solid #ccc;
        background-color: #ffffff;
        color: #2c3e50;
        cursor: pointer;
    }

    .status-select:focus {
        outline: none;
        border-color: #27ae60;
    }

    .btn {
        padding: 5px 10px;
        border-radius: 4px;
        border: none;
        background-color: #27ae60;
        color: white;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .btn:hover {
        background-color: #2ecc71;
    }

    td[colspan="7"] {
        font-style: italic;
        color: #7f8c8d;
    }
</style>

</head>

<body>
    <?php include('sidebar.php'); ?>
    <div class="container">
        <h1>Manage Customer Inquiries</h1>
        <table>
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Subject</th>
                    <th>Message</th>
                    <th>Status</th>
                    <th>Submission Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?> (<?php echo htmlspecialchars($row['customer_email']); ?>)</td>
                            <td><?php echo htmlspecialchars($row['subject']); ?></td>
                            <td><?php echo htmlspecialchars($row['message']); ?></td>
                            <td><?php echo htmlspecialchars($row['status']); ?></td>
                            <td><?php echo date('F j, Y, g:i a', strtotime($row['submission_date'])); ?></td>
                            <td>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="inquiry_id" value="<?php echo $row['inquiry_id']; ?>">
                                    <select name="status" class="status-select">
                                        <option value="Open" <?php echo $row['status'] === 'Open' ? 'selected' : ''; ?>>Open</option>
                                        <option value="In Progress" <?php echo $row['status'] === 'In Progress' ? 'selected' : ''; ?>>In Progress</option>
                                        <option value="Resolved" <?php echo $row['status'] === 'Resolved' ? 'selected' : ''; ?>>Resolved</option>
                                    </select>
                                    <button type="submit" name="update_status" class="btn">Update</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align: center;">No inquiries found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>

</html>

<?php
$conn->close();
?>