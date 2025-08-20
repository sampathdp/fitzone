<?php
include 'db.php';

if (isset($_GET['id'])) {
    $post_id = intval($_GET['id']);

    $sql = "SELECT * FROM BlogPosts WHERE post_id = $post_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $post = $result->fetch_assoc();
    } else {
        die('Post not found.');
    }
} else {
    die('Invalid post ID.');
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($post['title']) ?></title>
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #111;
            color: #fff;
            line-height: 1.6;
        }

        /* Page Container */
        .post-container {
            width: 90%;
            max-width: 800px;
            margin: 30px auto;
            background-color: #222;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
        }

        /* Post Title */
        .post-title {
            font-size: 2rem;
            margin-bottom: 20px;
            color: #fdbf00;
        }

        /* Post Metadata */
        .post-meta {
            font-size: 0.9rem;
            color: #bbb;
            margin-bottom: 20px;
        }

        /* Post Image */
        .post-image {
            width: 100%;
            max-height: 400px;
            object-fit: cover;
            object-position: center;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        /* Post Content */
        .post-content {
            font-size: 1rem;
            color: #ddd;
        }

        /* Footer Link */
        .back-link {
            display: inline-block;
            margin-top: 20px;
            font-size: 0.9rem;
            color: #fdbf00;
            text-decoration: none;
            transition: color 0.3s;
        }

        .back-link:hover {
            color: #fff;
        }
    </style>
</head>

<body>
    <div class="post-container">
        <h1 class="post-title"><?= htmlspecialchars($post['title']) ?></h1>
        <p class="post-meta">By <?= htmlspecialchars($post['author_id']) ?> | <?= date('M d, Y', strtotime($post['publication_date'])) ?></p>
        <?php if (!empty($post['blog_image'])): ?>
            <img src="./Admin/<?= htmlspecialchars($post['blog_image']) ?>" alt="Post Image" class="post-image">
        <?php endif; ?>
        <div class="post-content">
            <?= nl2br(htmlspecialchars($post['content'])) ?>
        </div>
        <a href="Blog.php" class="back-link">← Back to Blog</a>
    </div>
</body>

</html>
