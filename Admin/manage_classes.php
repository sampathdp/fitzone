<?php

require 'db.php';

$message = "";

// Handle Add or Update Class
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['class_form'])) {
    $class_name = $_POST['class_name'];
    $description = $_POST['description'];
    $trainer_id = $_POST['trainer_id'];
    $class_id = $_POST['class_id'];
    $class_image = null;

    // Handle file upload
    if (isset($_FILES['class_image']) && $_FILES['class_image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = "uploads/Classes/"; // Define your upload directory
        $image_name = basename($_FILES['class_image']['name']);
        $target_file = $upload_dir . time() . "_" . $image_name;

        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES['class_image']['tmp_name'], $target_file)) {
            $class_image = $target_file;
        } else {
            $message = "Error uploading the image.";
        }
    }

    if ($class_id) {
        // Update existing class
        $stmt = $conn->prepare("UPDATE fitnessclasses SET class_name = ?, description = ?, trainer_id = ?, class_image = IFNULL(?, class_image) WHERE class_id = ?");
        $stmt->bind_param("ssisi", $class_name, $description, $trainer_id, $class_image, $class_id);

        if ($stmt->execute()) {
            $message = "Class updated successfully.";
        } else {
            $message = "Error: " . $stmt->error;
        }
    } else {
        // Add new class
        $stmt = $conn->prepare("INSERT INTO fitnessclasses (class_name, description, trainer_id, class_image) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssis", $class_name, $description, $trainer_id, $class_image);

        if ($stmt->execute()) {
            $message = "Class added successfully.";
        } else {
            $message = "Error: " . $stmt->error;
        }
    }

    $stmt->close();
}

// Handle Delete Class
if (isset($_GET['delete_class'])) {
    $class_id = $_GET['delete_class'];

    // Prepare the delete statement
    $stmt = $conn->prepare("DELETE FROM fitnessclasses WHERE class_id = ?");
    $stmt->bind_param("i", $class_id);

    if ($stmt->execute()) {
        $message = "Class deleted successfully.";
    } else {
        $message = "Error deleting class: " . $stmt->error;
    }
    $stmt->close();
}

// Fetch all trainers for the trainer dropdown
$trainers = $conn->query("SELECT trainer_id, trainer_name FROM trainers");

// Fetch existing classes for displaying
$class_types = $conn->query("SELECT class_id, class_name, description, trainer_id, class_image FROM fitnessclasses");

if ($class_types === false) {
    die('Error: Could not retrieve class types.');
}

// Fetch data if editing
if (isset($_GET['edit_class'])) {
    $class_id = $_GET['edit_class'];
    $edit_query = $conn->prepare("SELECT class_name, description, trainer_id, class_image FROM fitnessclasses WHERE class_id = ?");
    $edit_query->bind_param("i", $class_id);
    $edit_query->execute();
    $edit_result = $edit_query->get_result();
    $class_data = $edit_result->fetch_assoc();
    $edit_query->close();
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class Management</title>
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

        .message {
            background-color: #27ae60;
            color: white;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        form {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        form label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
            color: #2c3e50;
        }

        form input,
        form select,
        form button {
            display: block;
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        form button {
            background-color: #27ae60;
            color: white;
            border: none;
            transition: background-color 0.3s ease;
        }

        form button:hover {
            background-color: #2ecc71;
        }

        /* Table styles */
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

        .actions {
            display: flex;
            justify-content: flex-start;
            align-items: center;
        }

        .actions a {
            text-decoration: none;
            padding: 6px 12px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 14px;
            display: inline-block;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .actions a.delete-button {
            background-color: #e74c3c;
            color: white;
        }

        .actions a.delete-button:hover {
            background-color: #c0392b;
            transform: translateY(-1px);
        }

        .actions a.edit-button {
            background-color: #3498db;
            color: white;
        }

        .actions a.edit-button:hover {
            background-color: #2980b9;
            transform: translateY(-1px);
        }

        .actions .divider {
            color: #bdc3c7;
            /* Light gray divider */
            font-size: 14px;
            font-weight: bold;
        }


        img {
            max-width: 100px;
            height: auto;
            border-radius: 4px;
        }
    </style>
</head>

<body>
    <!-- Include Sidebar -->
    <?php include('sidebar.php'); ?>
    <div class="container">
        <h1>Manage Classes</h1>

        <?php if ($message) { ?>
            <div class="message"> <?= htmlspecialchars($message) ?> </div>
        <?php } ?>

        <h2><?php echo isset($class_data) ? 'Update' : 'Add'; ?> Class</h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="class_form" value="1">
            <input type="hidden" name="class_id" id="class_id" value="<?= isset($class_data) ? $class_id : '' ?>">

            <label for="class_name">Class Name:</label>
            <input type="text" name="class_name" id="class_name" required value="<?= isset($class_data) ? htmlspecialchars($class_data['class_name']) : '' ?>">

            <label for="description">Description:</label>
            <input type="text" name="description" id="description" required value="<?= isset($class_data) ? htmlspecialchars($class_data['description']) : '' ?>">

            <label for="trainer_id">Trainer Name:</label>
            <select name="trainer_id" id="trainer_id" required>
                <?php while ($trainer = $trainers->fetch_assoc()) { ?>
                    <option value="<?= $trainer['trainer_id'] ?>" <?= isset($class_data) && $class_data['trainer_id'] == $trainer['trainer_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($trainer['trainer_name']) ?>
                    </option>
                <?php } ?>
            </select>

            <label for="class_image">Class Image:</label>
            <input type="file" name="class_image" id="class_image" accept="image/*">

            <?php if (isset($class_data['class_image']) && $class_data['class_image']) { ?>
                <p>Current Image: <img src="<?= htmlspecialchars($class_data['class_image']) ?>" alt="Class Image" style="max-width: 100px;"></p>
            <?php } ?>

            <button type="submit"><?= isset($class_data) ? 'Update' : 'Save'; ?> Class</button>
        </form>

        <h2>Existing Classes</h2>
        <table>
            <thead>
                <tr>
                    <th>Class ID</th>
                    <th>Class Name</th>
                    <th>Description</th>
                    <th>Class Image</th>
                    <th>Trainer</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $class_types->fetch_assoc()) { ?>
                    <tr>
                        <td><?= htmlspecialchars($row['class_id']) ?></td>
                        <td><?= htmlspecialchars($row['class_name']) ?></td>
                        <td><?= htmlspecialchars($row['description']) ?></td>
                        <td>
                            <?php if (!empty($row['class_image'])) { ?>
                                <img src="<?= htmlspecialchars($row['class_image']) ?>" alt="Class Image" style="max-width: 100px;">
                            <?php } else { ?>
                                No Image
                            <?php } ?>
                        </td>
                        <td>
                            <?php
                            $trainer_query = $conn->prepare("SELECT trainer_name FROM trainers WHERE trainer_id = ?");
                            $trainer_query->bind_param("i", $row['trainer_id']);
                            $trainer_query->execute();
                            $trainer_result = $trainer_query->get_result();
                            $trainer = $trainer_result->fetch_assoc();
                            echo htmlspecialchars($trainer['trainer_name']);
                            ?>
                        </td>
                        <td class="actions">
                            <a href="?edit_class=<?= htmlspecialchars($row['class_id']) ?>" class="edit-button">Edit</a>
                            <a href="?delete_class=<?= htmlspecialchars($row['class_id']) ?>" onclick="return confirm('Are you sure you want to delete this class?')" class="delete-button">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>

</html>