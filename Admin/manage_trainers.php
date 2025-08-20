<?php

require 'db.php';

$message = "";
$trainer_to_edit = null;

// Handle Add/Edit Trainer form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $trainer_id = isset($_POST['trainer_id']) ? intval($_POST['trainer_id']) : null;
    $trainer_name = $_POST['trainer_name'];
    $specialization = $_POST['specialization'];
    $hourly_rate = $_POST['hourly_rate'];
    $experience_years = $_POST['experience_years'];
    $bio = $_POST['bio'];
    $image_path = null;

    // Handle image upload
    if (isset($_FILES['trainer_image']) && $_FILES['trainer_image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/trainers/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $image_name = time() . '_' . basename($_FILES['trainer_image']['name']); // Avoid duplicate names
        $image_path = $upload_dir . $image_name;
        if (!move_uploaded_file($_FILES['trainer_image']['tmp_name'], $image_path)) {
            $message = "Error uploading image.";
            $image_path = null;
        }
    }

    if ($trainer_id) {
        // Fetch existing image path if no new image is uploaded
        if (!$image_path) {
            $stmt = $conn->prepare("SELECT image_path FROM trainers WHERE trainer_id = ?");
            $stmt->bind_param("i", $trainer_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $image_path = $row['image_path'];
            $stmt->close();
        }

        // Update trainer
        $stmt = $conn->prepare("UPDATE trainers SET trainer_name = ?, specialization = ?, hourly_rate = ?, experience_years = ?, bio = ?, image_path = ? WHERE trainer_id = ?");
        $stmt->bind_param("ssdissi", $trainer_name, $specialization, $hourly_rate, $experience_years, $bio, $image_path, $trainer_id);
    } else {
        // Add new trainer
        $stmt = $conn->prepare("INSERT INTO trainers (trainer_name, specialization, hourly_rate, experience_years, bio, image_path) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdsss", $trainer_name, $specialization, $hourly_rate, $experience_years, $bio, $image_path);
    }

    if ($stmt->execute()) {
        $message = $trainer_id ? "Trainer updated successfully." : "Trainer added successfully.";
    } else {
        $message = "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Handle Delete Trainer
if (isset($_GET['delete'])) {
    $trainer_id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM trainers WHERE trainer_id = ?");
    $stmt->bind_param("i", $trainer_id);

    if ($stmt->execute()) {
        $message = "Trainer deleted successfully.";
    } else {
        $message = "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Handle Edit Trainer
if (isset($_GET['edit'])) {
    $trainer_id = intval($_GET['edit']);
    $stmt = $conn->prepare("SELECT * FROM trainers WHERE trainer_id = ?");
    $stmt->bind_param("i", $trainer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $trainer_to_edit = $result->fetch_assoc();
    $stmt->close();
}

// Fetch all trainers
$query = "SELECT * FROM trainers";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trainer Management</title>

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

        h1,
        h2 {
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
            text-align: center;
        }

        .add-trainer form {
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
        form textarea,
        form button {
            display: block;
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        form textarea {
            resize: vertical;
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
        .trainer-list table {
            width: 100%;
            border-collapse: collapse;
            background-color: #ffffff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
            margin-top: 30px;
        }

        .trainer-list table thead {
            background-color: #34495e;
            color: white;
        }

        img {
            max-width: 100px;
            height: auto;
            border-radius: 4px;
        }

        .trainer-list table th,
        .trainer-list table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .trainer-list table tr:hover {
            background-color: #f1f1f1;
        }

        .trainer-list table th {
            font-weight: bold;
        }

        .trainer-list table td {
            color: #2c3e50;
        }

        .actions a {
            text-decoration: none;
            padding: 5px 10px;
            margin-right: 5px;
            background-color: #e74c3c;
            color: white;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .actions a:hover {
            background-color: #c0392b;
        }

        .actions a:first-child {
            background-color: #2980b9;
        }

        .actions a:first-child:hover {
            background-color: #3498db;
        }

        img {
            max-width: 100px;
            height: auto;
            border-radius: 4px;
        }
    </style>

</head>

<body>

    <?php include('sidebar.php'); ?>
    <div class="container">
        <h1>Manage Trainers</h1>

        <?php if ($message) { ?>
            <div class="message"><?= htmlspecialchars($message) ?></div>
        <?php } ?>

        <div class="add-trainer">
            <h2><?= $trainer_to_edit ? "Edit Trainer" : "Add New Trainer" ?></h2>
            <form action="" method="POST" enctype="multipart/form-data">
                <?php if ($trainer_to_edit) { ?>
                    <input type="hidden" name="trainer_id" value="<?= htmlspecialchars($trainer_to_edit['trainer_id']) ?>">
                <?php } ?>

                <label for="trainer_name">Trainer Name:</label>
                <input type="text" name="trainer_name" id="trainer_name" value="<?= htmlspecialchars($trainer_to_edit['trainer_name'] ) ?>" required>

                <label for="specialization">Specialization:</label>
                <input type="text" name="specialization" id="specialization" value="<?= htmlspecialchars($trainer_to_edit['specialization'] ) ?>" required>

                <label for="hourly_rate">Hourly Rate:</label>
                <input type="number" name="hourly_rate" id="hourly_rate" step="0.01" value="<?= htmlspecialchars($trainer_to_edit['hourly_rate'] ) ?>" required>

                <label for="experience_years">Experience (Years):</label>
                <input type="number" name="experience_years" id="experience_years" value="<?= htmlspecialchars($trainer_to_edit['experience_years'] ) ?>" required>

                <label for="bio">Bio:</label>
                <textarea name="bio" id="bio" required><?= htmlspecialchars($trainer_to_edit['bio'] ) ?></textarea>

                <label for="trainer_image">Trainer Image:</label>
                <input type="file" name="trainer_image" id="trainer_image" accept="image/*">

                <button type="submit"><?= $trainer_to_edit ? "Update Trainer" : "Add Trainer" ?></button>
            </form>
        </div>

        <div class="trainer-list">
            <h2>Existing Trainers</h2>
            <table>
                <thead>
                    <tr>
                        <th>Trainer Name</th>
                        <th>Specialization</th>
                        <th>Hourly Rate</th>
                        <th>Experience</th>
                        <th>Bio</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?= htmlspecialchars($row['trainer_name']) ?></td>
                            <td><?= htmlspecialchars($row['specialization']) ?></td>
                            <td><?= htmlspecialchars($row['hourly_rate']) ?></td>
                            <td><?= htmlspecialchars($row['experience_years']) ?></td>
                            <td><?= htmlspecialchars($row['bio']) ?></td>
                            <td>
                                <?php if ($row['image_path']) { ?>
                                    <img src="<?= htmlspecialchars($row['image_path']) ?>" alt="Trainer Image" style="width: 100px; height: auto;">
                                <?php } else { ?>
                                    <span>No Image</span>
                                <?php } ?>
                            </td>
                            <td>
                                <a href="?edit=<?= htmlspecialchars($row['trainer_id']) ?>">Edit</a> |
                                <a href="?delete=<?= htmlspecialchars($row['trainer_id']) ?>" onclick="return confirm('Are you sure you want to delete this trainer?')">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>