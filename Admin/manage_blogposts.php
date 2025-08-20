<?php
include('sidebar.php');
require 'db.php';

$message = "";

// Handle Add Blog Post
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['blog_form'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category = $_POST['category'];
    $author_id = $_SESSION['user_id'];
    $blog_image = null;

    // Handle file upload
    if (isset($_FILES['blog_image']) && $_FILES['blog_image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = "uploads/BlogPost/";
        $image_name = basename($_FILES['blog_image']['name']);
        $target_file = $upload_dir . time() . "_" . $image_name;

        if (move_uploaded_file($_FILES['blog_image']['tmp_name'], $target_file)) {
            $blog_image = $target_file;
        } else {
            $message = "Error uploading the image.";
        }
    }

    // Insert into blogposts table (include image path)
    $stmt = $conn->prepare("INSERT INTO blogposts (title, content, category, author_id, publication_date, blog_image) VALUES (?, ?, ?, ?, NOW(), ?)");
    $stmt->bind_param("sssis", $title, $content, $category, $author_id, $blog_image);

    if ($stmt->execute()) {
        $message = "Blog post added successfully.";
    } else {
        $message = "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Fetch existing blog posts
$posts = $conn->query("SELECT post_id, title, content, category, author_id, publication_date, blog_image FROM blogposts");

if ($posts === false) {
    die('Error: Could not retrieve blog posts.');
}

// Handle Delete Blog Post
if (isset($_GET['delete_post'])) {
    $post_id = $_GET['delete_post'];

    // First, get the image path if it exists to delete the image from the server
    $stmt = $conn->prepare("SELECT blog_image FROM blogposts WHERE post_id = ?");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $image_path = $row['blog_image'];

        // Delete the blog post from the database
        $delete_stmt = $conn->prepare("DELETE FROM blogposts WHERE post_id = ?");
        $delete_stmt->bind_param("i", $post_id);

        if ($delete_stmt->execute()) {
            // If the post is deleted and an image exists, remove the image file from the server
            if ($image_path && file_exists($image_path)) {
                unlink($image_path);
            }
            $message = "Blog post deleted successfully.";
        } else {
            $message = "Error deleting the blog post.";
        }
        $delete_stmt->close();
    } else {
        $message = "Blog post not found.";
    }

    $stmt->close();

    exit;
}


$categories = ['Workout', 'Yoga', 'Running', 'Nutrition', 'Wellness'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Blog Posts</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9f9f9;
            display: flex;
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
            margin: 20px;
            border-radius: 4px;
            text-align: center;
        }

        .container {
            padding: 20px;
            width: calc(100% - 250px);
        }

        .add-blog form {
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
            background-color: #3498db;
            color: white;
            border: none;
            transition: background-color 0.3s ease;
            cursor: pointer;
        }

        form button:hover {
            background-color: #2980b9;
        }

        .blog-list table {
            width: 100%;
            border-collapse: collapse;
            background-color: #ffffff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
            margin-top: 20px;
        }

        .blog-list table thead {
            background-color: #34495e;
            color: white;
        }

        .blog-list table th,
        .blog-list table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .blog-list table tr:hover {
            background-color: #f1f1f1;
        }

        .blog-list table th {
            font-weight: bold;
        }

        .blog-list table td {
            color: #2c3e50;
        }

        .blog-list img {
            max-width: 80px;
            height: auto;
            border-radius: 4px;
        }

        .blog-list a {
            text-decoration: none;
            padding: 5px 10px;
            background-color: #e74c3c;
            color: white;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .blog-list a:hover {
            background-color: #c0392b;
        }
    </style>


</head>

<body>

    <h1>Manage Blog Posts</h1>


    <div class="container">
        
    <?php if ($message) { ?>
        <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php } ?>
        <div class="add-blog">
            <h2>Add Blog Post</h2>
            <form action="" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="blog_form" value="1">

                <label for="title">Title:</label>
                <input type="text" name="title" id="title" required>

                <label for="content">Content:</label>
                <textarea name="content" id="content" rows="5" required></textarea>

                <label for="category">Category:</label>
                <select name="category" id="category" required>
                    <?php foreach ($categories as $cat) { ?>
                        <option value="<?= htmlspecialchars($cat) ?>"><?= htmlspecialchars($cat) ?></option>
                    <?php } ?>
                </select>

                <label for="blog_image">Blog Image:</label>
                <input type="file" name="blog_image" id="blog_image" accept="image/*">

                <button type="submit">Save Blog Post</button>
            </form>
        </div>


        <div class="blog-list">
            <h2>Existing Blog Posts</h2>
            <table>
                <thead>
                    <tr>
                        <th>Post ID</th>
                        <th>Title</th>
                        <th>Content</th>
                        <th>Category</th>
                        <th>Author</th>
                        <th>Publication Date</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $posts->fetch_assoc()) { ?>
                        <tr>
                            <td><?= htmlspecialchars($row['post_id']) ?></td>
                            <td><?= htmlspecialchars($row['title']) ?></td>
                            <td><?= htmlspecialchars(substr($row['content'], 0, 50)) ?>...</td>
                            <td><?= htmlspecialchars($row['category']) ?></td>
                            <td><?= htmlspecialchars($row['author_id']) ?></td>
                            <td><?= htmlspecialchars($row['publication_date']) ?></td>
                            <td>
                                <?php if ($row['blog_image']) { ?>
                                    <img src="<?= htmlspecialchars($row['blog_image']) ?>" alt="Blog Image">
                                <?php } else { ?>
                                    No Image
                                <?php } ?>
                            </td>
                            <td>
                                <a href="?delete_post=<?= $row['post_id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>


</html>