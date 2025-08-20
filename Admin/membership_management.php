<?php

require 'db.php';

// Handle adding or updating a membership type
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $membership_id = isset($_POST['membership_id']) ? $_POST['membership_id'] : null;
    $membership_name = $_POST['membership_name'];
    $description = $_POST['description'];
    $monthly_fee = $_POST['monthly_fee'];
    $annual_fee = $_POST['annual_fee'];
    $max_class_bookings = $_POST['max_class_bookings'];
    $personal_training_sessions = $_POST['personal_training_sessions'];
    $nutrition_consultations = $_POST['nutrition_consultations'];
    $status = isset($_POST['status']) && $_POST['status'] === 'Active' ? 'Active' : 'Inactive';

    if ($membership_id) {
        // Update existing membership
        $sql = "UPDATE MembershipTypes 
                SET membership_name='$membership_name', description='$description', monthly_fee='$monthly_fee', 
                    annual_fee='$annual_fee', max_class_bookings='$max_class_bookings', 
                    personal_training_sessions='$personal_training_sessions', 
                    nutrition_consultations='$nutrition_consultations', status='$status' 
                WHERE membership_id='$membership_id'";
    } else {
        // Insert new membership
        $sql = "INSERT INTO MembershipTypes (membership_name, description, monthly_fee, annual_fee, 
                                             max_class_bookings, personal_training_sessions, 
                                             nutrition_consultations, status)
                VALUES ('$membership_name', '$description', '$monthly_fee', '$annual_fee', 
                        '$max_class_bookings', '$personal_training_sessions', 
                        '$nutrition_consultations', '$status')";
    }

    if ($conn->query($sql) === TRUE) {
        $message = $membership_id ? "Membership updated successfully" : "New membership added successfully";
    } else {
        $message = "Error: " . $conn->error;
    }
}

// Handle deleting a membership type
if (isset($_GET['delete'])) {
    $membership_id = $_GET['delete'];
    $sql = "DELETE FROM MembershipTypes WHERE membership_id='$membership_id'";

    if ($conn->query($sql) === TRUE) {
        $message = "Membership deleted successfully";
    } else {
        $message = "Error: " . $conn->error;
    }
}

$sql = "SELECT * FROM MembershipTypes";
$result = $conn->query($sql);

$membership_to_edit = null;
if (isset($_GET['edit'])) {
    $membership_id = $_GET['edit'];
    $sql = "SELECT * FROM MembershipTypes WHERE membership_id='$membership_id'";
    $result_edit = $conn->query($sql);
    $membership_to_edit = $result_edit->fetch_assoc();
}
?>
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

    .main-content {
        margin-left: 250px;
        padding: 20px;
        width: calc(100% - 250px);
    }

    .main-content h1 {
        text-align: center;
        color: #2c3e50;
        margin-bottom: 30px;
    }

    .message {
        background-color: #27ae60;
        color: white;
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 4px;
        text-align: center;
    }

    .add-membership,
    .membership-list {
        margin-bottom: 40px;
    }

    .add-membership h2,
    .membership-list h2 {
        color: #34495e;
        margin-bottom: 20px;
    }

    form {
        background-color: #ffffff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    form label {
        display: block;
        margin-bottom: 10px;
        color: #2c3e50;
    }

    form input,
    form textarea,
    form select,
    form button {
        width: 100%;
        padding: 10px;
        margin-bottom: 20px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
    }

    form textarea {
        height: 100px;
    }

    form button {
        background-color: #27ae60;
        color: white;
        border: none;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    form button:hover {
        background-color: #2ecc71;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background-color: #ffffff;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        overflow: hidden;
        margin-bottom: 20px;
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

    table tr:hover {
        background-color: #f1f1f1;
    }

    table th {
        font-weight: bold;
    }

    table td {
        color: #2c3e50;
    }

    .membership-list a {
        text-decoration: none;
        padding: 5px 10px;
        margin-right: 5px;
        border-radius: 4px;
        color: white;
        transition: background-color 0.3s ease;
    }

    .membership-list a.edit {
        background-color: #3498db;
    }

    .membership-list a.edit:hover {
        background-color: #2980b9;
    }

    .membership-list a.delete {
        background-color: #e74c3c;
    }

    .membership-list a.delete:hover {
        background-color: #c0392b;
    }
</style>

<div class="main-content">
    <!-- Include Sidebar -->
    <?php include('sidebar.php'); ?>
    <h1>Manage Membership Types</h1>

    <?php if (isset($message)) { ?>
        <div class="message"><?= $message ?></div>
    <?php } ?>

    <div class="add-membership">
        <h2><?= $membership_to_edit ? "Edit Membership" : "Add New Membership" ?></h2>
        <form action="membership_management.php" method="POST">
            <?php if ($membership_to_edit) { ?>
                <input type="hidden" name="membership_id" value="<?= $membership_to_edit['membership_id'] ?>">
            <?php } ?>

            <label for="membership_name">Membership Name:</label>
            <input type="text" name="membership_name" id="membership_name" value="<?= $membership_to_edit ? $membership_to_edit['membership_name'] : '' ?>" required>

            <label for="description">Description:</label>
            <textarea name="description" id="description" required><?= $membership_to_edit ? $membership_to_edit['description'] : '' ?></textarea>

            <label for="monthly_fee">Monthly Fee:</label>
            <input type="number" name="monthly_fee" id="monthly_fee" step="0.01" value="<?= $membership_to_edit ? $membership_to_edit['monthly_fee'] : '' ?>" required>

            <label for="annual_fee">Annual Fee:</label>
            <input type="number" name="annual_fee" id="annual_fee" step="0.01" value="<?= $membership_to_edit ? $membership_to_edit['annual_fee'] : '' ?>" required>

            <label for="max_class_bookings">Max Class Bookings:</label>
            <input type="number" name="max_class_bookings" id="max_class_bookings" value="<?= $membership_to_edit ? $membership_to_edit['max_class_bookings'] : '' ?>">

            <label for="personal_training_sessions">Personal Training Sessions:</label>
            <input type="number" name="personal_training_sessions" id="personal_training_sessions" value="<?= $membership_to_edit ? $membership_to_edit['personal_training_sessions'] : '' ?>">

            <label for="nutrition_consultations">Nutrition Consultations:</label>
            <input type="number" name="nutrition_consultations" id="nutrition_consultations" value="<?= $membership_to_edit ? $membership_to_edit['nutrition_consultations'] : '' ?>">

            <label for="status">Status:</label>
            <select name="status" id="status">
                <option value="Active" <?= $membership_to_edit && $membership_to_edit['status'] === 'Active' ? 'selected' : '' ?>>Active</option>
                <option value="Inactive" <?= $membership_to_edit && $membership_to_edit['status'] === 'Inactive' ? 'selected' : '' ?>>Inactive</option>
            </select>

            <button type="submit"><?= $membership_to_edit ? "Update Membership" : "Add Membership" ?></button>
        </form>
    </div>

    <div class="membership-list">
        <h2>Existing Membership Types</h2>
        <table>
            <thead>
                <tr>
                    <th>Membership Name</th>
                    <th>Description</th>
                    <th>Monthly Fee</th>
                    <th>Annual Fee</th>
                    <th>Max Bookings</th>
                    <th>Training Sessions</th>
                    <th>Nutrition Consultations</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $row['membership_name'] ?></td>
                        <td><?= $row['description'] ?></td>
                        <td><?= $row['monthly_fee'] ?></td>
                        <td><?= $row['annual_fee'] ?></td>
                        <td><?= $row['max_class_bookings'] ?></td>
                        <td><?= $row['personal_training_sessions'] ?></td>
                        <td><?= $row['nutrition_consultations'] ?></td>
                        <td><?= $row['status'] ?></td>
                        <td>
                            <a class="edit" href="membership_management.php?edit=<?= $row['membership_id'] ?>">Edit</a> |
                            <a class="delete" href="membership_management.php?delete=<?= $row['membership_id'] ?>" onclick="return confirm('Are you sure you want to delete this membership?')">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php
$conn->close();
?>