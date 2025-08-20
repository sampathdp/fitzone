<?php
require 'db.php';

$message = "";
$schedule_to_edit = null;

// Handle Add/Edit Schedule form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $schedule_id = isset($_POST['schedule_id']) ? intval($_POST['schedule_id']) : null;
    $class_id = intval($_POST['class_id']);
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $available_slots = intval($_POST['max_slots']);

    // Initialize the used_slots variable
    $used_slots = 0;

    if ($schedule_id) {
        // Update schedule
        $stmt = $conn->prepare("UPDATE ClassSchedules SET class_id = ?, start_time = ?, end_time = ?, max_slots = ?, used_slots = ? WHERE schedule_id = ?");
        $stmt->bind_param("issiii", $class_id, $start_time, $end_time, $available_slots, $used_slots, $schedule_id);
    } else {
        // Add new schedule
        $stmt = $conn->prepare("INSERT INTO ClassSchedules (class_id, start_time, end_time, max_slots, used_slots) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issii", $class_id, $start_time, $end_time, $available_slots, $used_slots);
    }

    if ($stmt->execute()) {
        $message = $schedule_id ? "Schedule updated successfully." : "Schedule added successfully.";
    } else {
        $message = "Error: " . $stmt->error;
    }

    $stmt->close();
}


// Handle Delete Schedule
if (isset($_GET['delete'])) {
    $schedule_id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM ClassSchedules WHERE schedule_id = ?");
    $stmt->bind_param("i", $schedule_id);

    if ($stmt->execute()) {
        $message = "Schedule deleted successfully.";
    } else {
        $message = "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Handle Edit Schedule
if (isset($_GET['edit'])) {
    $schedule_id = intval($_GET['edit']);
    $stmt = $conn->prepare("SELECT * FROM ClassSchedules WHERE schedule_id = ?");
    $stmt->bind_param("i", $schedule_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $schedule_to_edit = $result->fetch_assoc();
    $stmt->close();
}

// Fetch all schedules
$query = "SELECT cs.*, fc.class_name FROM ClassSchedules cs JOIN FitnessClasses fc ON cs.class_id = fc.class_id";
$result = $conn->query($query);

// Fetch all fitness classes
$classes_query = "SELECT * FROM FitnessClasses";
$classes_result = $conn->query($classes_query);
$classes = $classes_result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Class Schedules</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f7f9fc;
            display: flex;
        }

        .container {
            margin-left: 250px;
            padding: 20px;
            width: calc(100% - 250px);
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .add-schedule {
            margin: 0 auto;
            background-color: #ffffff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            padding: 20px;
            width: 80%;
        }

        .schedule-list {
            margin: 0 auto;
            width: 80%;
        }

        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
        }

        .message {
            margin: 20px auto;
            padding: 15px;
            background-color: #2ecc71;
            color: white;
            border-radius: 8px;
            text-align: center;
            width: 80%;
        }



        .add-schedule h2 {
            text-align: center;
            color: #34495e;
            margin-bottom: 20px;
        }

        .add-schedule form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .add-schedule label {
            font-weight: bold;
            color: #34495e;
        }

        .add-schedule input,
        .add-schedule select,
        .add-schedule button {
            padding: 12px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            width: 100%;
        }

        .add-schedule button {
            background-color: #27ae60;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .add-schedule button:hover {
            background-color: #2ecc71;
        }


        .schedule-list h2 {
            text-align: center;
            color: #34495e;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #ffffff;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
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

        table tr:hover {
            background-color: #f1f1f1;
        }

        table th {
            font-weight: bold;
        }

        table td {
            color: #34495e;
        }

        table a {
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        table a[href*="edit"] {
            background-color: #3498db;
            color: white;
        }

        table a[href*="edit"]:hover {
            background-color: #2980b9;
        }

        table a[href*="delete"] {
            background-color: #e74c3c;
            color: white;
        }

        table a[href*="delete"]:hover {
            background-color: #c0392b;
        }
    </style>

</head>

<body>
    <?php include('sidebar.php'); ?>


    <div class="container">
        <h1>Manage Class Schedules</h1>

        <?php if ($message) { ?>
            <div class="message"><?= htmlspecialchars($message) ?></div>
        <?php } ?>
        <div class="add-schedule">
            <h2><?= $schedule_to_edit ? "Edit Schedule" : "Add New Schedule" ?></h2>
            <form action="" method="POST">
                <?php if ($schedule_to_edit) { ?>
                    <input type="hidden" name="schedule_id"
                        value="<?= htmlspecialchars($schedule_to_edit['schedule_id']) ?>">
                <?php } ?>

                <label for="class_id">Class:</label>
                <select name="class_id" id="class_id" required>
                    <option value="">Select a Class</option>
                    <?php foreach ($classes as $class) { ?>
                        <option value="<?= $class['class_id'] ?>" <?= $schedule_to_edit && $schedule_to_edit['class_id'] == $class['class_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($class['class_name']) ?>
                        </option>
                    <?php } ?>
                </select>

                <label for="start_time">Start Time:</label>
                <input type="datetime-local" name="start_time" id="start_time"
                    value="<?= $schedule_to_edit['start_time'] ?>" required>

                <label for="end_time">End Time:</label>
                <input type="datetime-local" name="end_time" id="end_time" value="<?= $schedule_to_edit['end_time'] ?>"
                    required>

                <label for="available_slots">Available Slots:</label>
                <input type="number" name="max_slots" id="max_slots" value="<?= $schedule_to_edit['max_slots'] ?>"
                    required>

                <button type="submit"><?= $schedule_to_edit ? "Update Schedule" : "Add Schedule" ?></button>
            </form>
        </div>

        <div class="schedule-list">
            <h2>Existing Schedules</h2>
            <table>
                <thead>
                    <tr>
                        <th>Class Name</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Available Slots</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) {

                        $available_slots = $row['max_slots'] - $row['used_slots'];
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($row['class_name']) ?></td>
                            <td><?= htmlspecialchars($row['start_time']) ?></td>
                            <td><?= htmlspecialchars($row['end_time']) ?></td>
                            <td><?= htmlspecialchars($available_slots) ?></td>
                            <td>
                                <a href="?edit=<?= htmlspecialchars($row['schedule_id']) ?>">Edit</a> |
                                <a href="?delete=<?= htmlspecialchars($row['schedule_id']) ?>"
                                    onclick="return confirm('Are you sure you want to delete this schedule?')">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>