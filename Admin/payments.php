<?php

include('db.php');

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['transaction_id'], $_POST['status'])) {
    $transaction_id = (int)$_POST['transaction_id'];
    $status = $_POST['status'];

    if (in_array($status, ['Approved', 'Rejected'])) {
        // Get the current date and time
        $verifydate = date('Y-m-d H:i:s');

        // Prepare the update statement
        $stmt = $conn->prepare("UPDATE PaymentTransactions SET status = ?, verifydate = ? WHERE transaction_id = ?");
        $stmt->bind_param('ssi', $status, $verifydate, $transaction_id);
        $stmt->execute();
        $stmt->close();
        $message = "Transaction status updated successfully.";
    } else {
        $message = "Invalid status value.";
    }
}

// Determine status to filter
$status_filter = isset($_GET['status']) ? $_GET['status'] : 'Pending';

// Fetch payment transactions based on the filter
$stmt = $conn->prepare("
    SELECT pt.transaction_id, pt.payment_receipt, pt.status, c.first_name, c.last_name, pt.membership_id
    FROM PaymentTransactions pt
    JOIN Customers c ON pt.customer_id = c.customer_id
    WHERE pt.status = ?
");
$stmt->bind_param('s', $status_filter);
$stmt->execute();
$result = $stmt->get_result();

$stmt->close();
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Payment Transactions</title>
    <style>* {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
        }

        .container {
            margin: 20px auto;
            margin-left: 250px;
            max-width: calc(100% - 250px);
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
        }

        .filters {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .filters a {
            margin: 0 10px;
            padding: 10px 20px;
            text-decoration: none;
            color: #fff;
            background-color: #007bff;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .filters a:hover {
            background-color: #0056b3;
        }

        .filters a.active {
            background-color: #28a745;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #343a40;
            color: #fff;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .btn {
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-approve {
            background-color: #4CAF50;
            color: white;
        }

        .btn-reject {
            background-color: #f44336;
            color: white;
        }

        .message {
            padding: 10px;
            margin-bottom: 20px;
            color: #fff;
            background-color: #28a745;
            border-radius: 4px;
            text-align: center;
        }
    </style>
</head>

<body>
    <?php include('sidebar.php'); ?>
    <div class="container">
        <h1>Manage Payment Transactions</h1>

        <?php if (isset($message)): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <!-- Filter Links -->
        <div class="filters">
            <a href="?status=Pending" class="<?php echo $status_filter === 'Pending' ? 'active' : ''; ?>">Pending</a>
            <a href="?status=Approved" class="<?php echo $status_filter === 'Approved' ? 'active' : ''; ?>">Approved</a>
            <a href="?status=Rejected" class="<?php echo $status_filter === 'Rejected' ? 'active' : ''; ?>">Rejected</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Transaction ID</th>
                    <th>Customer Name</th>
                    <th>Membership ID</th>
                    <th>Receipt</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['transaction_id']; ?></td>
                            <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['membership_id']); ?></td>
                            <td><a href="uploads/PaymentSlips/<?php echo htmlspecialchars($row['payment_receipt']); ?>" target="_blank">View Receipt</a></td>
                            <td><?php echo htmlspecialchars($row['status']); ?></td>
                            <td>
                                <?php if ($status_filter === 'Pending'): ?>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="transaction_id" value="<?php echo $row['transaction_id']; ?>">
                                        <button type="submit" name="status" value="Approved" class="btn btn-approve">Approve</button>
                                        <button type="submit" name="status" value="Rejected" class="btn btn-reject">Reject</button>
                                    </form>
                                <?php else: ?>
                                    N/A
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center;">No transactions found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>

</html>