<?php
include 'Includes/header.php';
include 'db.php';

$search_query = '';
$category_filter = '';

if (isset($_GET['search'])) {
    $search_query = $_GET['search'];
}

if (isset($_GET['category'])) {
    $category_filter = $_GET['category'];
}

// Modify SQL query to handle search and category filters
$sql = "SELECT * FROM BlogPosts";
$conditions = [];

if (!empty($search_query)) {
    $conditions[] = "(title LIKE '%" . $conn->real_escape_string($search_query) . "%' 
                     OR content LIKE '%" . $conn->real_escape_string($search_query) . "%')";
}

if (!empty($category_filter)) {
    $conditions[] = "category = '" . $conn->real_escape_string($category_filter) . "'";
}

if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Store the result in an array
    $posts = [];
    while ($row = $result->fetch_assoc()) {
        $posts[] = $row;
    }
} else {
    $posts = [];
}

// Fetch categories and their post counts
$category_query = "
    SELECT category, COUNT(*) AS category_count
    FROM BlogPosts
    GROUP BY category;
";

$category_result = $conn->query($category_query);

if ($category_result === false) {
    die('Error: Could not retrieve categories.');
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitness Blog Template</title>
    <style>
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

        .container {
            display: flex;
            justify-content: space-between;
            width: 90%;
            margin: 20px auto;
        }

        h1 {
            color: #ff6600;
            text-align: center;
            margin-bottom: 1em;
        }

        .section-title {
            font-size: 2.5rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-top: 80px;
        }

        .blog-posts {
            width: 65%;
        }

        .post {
            display: flex;
            margin-bottom: 20px;
            background: #222;
            border: 1px solid #333;
            border-radius: 8px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .post:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
        }

        /* Post Image Style */
        .post img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            object-position: center;
            border-right: 1px solid #333;
        }

        .blog-image-placeholder {
            width: 150px;
            height: 150px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #444;
            color: #fff;
            font-size: 0.9rem;
            font-style: italic;
            border-right: 1px solid #333;
        }

        .post-details {
            padding: 15px;
            flex: 1;
        }

        .post-details h3 {
            font-size: 1.2rem;
            margin-bottom: 10px;
            color: #fdbf00;
        }

        .post-details h3 a {
            text-decoration: none;
            color: #fdbf00;
            transition: color 0.3s ease;
        }

        .post-details h3 a:hover {
            color: #fff;
        }

        /* Author and Date Info */
        .post-details p {
            font-size: 0.9rem;
            margin-bottom: 10px;
            color: #ccc;
        }

        .post-details p:last-child {
            color: #aaa;
        }

        .sidebar {
            width: 30%;
            padding-left: 20px;
        }

        .categories,
        .featured-posts {
            margin-bottom: 30px;
        }

        .categories h3,
        .featured-posts h3 {
            font-size: 1.3rem;
            margin-bottom: 10px;
            border-bottom: 2px solid #444;
            padding-bottom: 5px;
        }

        .categories ul {
            list-style: none;
        }

        .categories ul li {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            font-size: 0.9rem;
            border-bottom: 1px solid #333;
        }

        .categories ul li a {
            color: #fdbf00;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .categories ul li a:hover {
            color: #fff;
        }

        .featured-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }


        .featured-item img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            object-position: center;
        }

        .featured-item p {
            font-size: 0.9rem;
            margin-bottom: 5px;
            color: #fdbf00;
        }

        .featured-item span {
            font-size: 0.8rem;
            color: #aaa;
        }

        .featured-item-link {
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .featured-item-link:hover .featured-item {
            background-color: #333;
            transition: background-color 0.3s ease;
        }

        /* Add styles for the search bar */
        .search-bar {
            width: 90%;
            margin: 20px auto;
            display: flex;
            justify-content: center;
        }

        .search-bar input[type="text"] {
            width: 70%;
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #444;
            border-radius: 4px;
            background: #222;
            color: #fff;
            outline: none;
        }

        .search-bar input[type="submit"] {
            padding: 10px 20px;
            margin-left: 10px;
            font-size: 1rem;
            background-color: #ff6600;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .search-bar input[type="submit"]:hover {
            background-color: #e65c00;
        }
    </style>
</head>

<body>
    <h1 class="section-title">Blogs</h1>

    <!-- Search Bar -->
    <form class="search-bar" method="GET" action="">
        <input type="text" name="search" placeholder="Search blogs by title or content..."
            value="<?= htmlspecialchars($search_query) ?>">
        <input type="submit" value="Search">
    </form>

    <!-- Main Container -->
    <div class="container">
        <!-- Left Section: Blog Posts -->
        <section class="blog-posts">
            <?php if (count($posts) > 0): ?>
                <?php foreach ($posts as $post): ?>
                    <div class="post">
                        <?php if ($post['blog_image']): ?>
                            <img src="./Admin/<?= htmlspecialchars($post['blog_image']) ?>" alt="Post Image">
                        <?php else: ?>
                            <div class="blog-image-placeholder">No Image</div>
                        <?php endif; ?>
                        <div class="post-details">
                            <h3>
                                <a href="post.php?id=<?= htmlspecialchars($post['post_id']) ?>">
                                    <?= htmlspecialchars($post['title']) ?>
                                </a>
                            </h3>
                            <p> <?= htmlspecialchars($post['category']) ?> |
                                <?= date('M d, Y', strtotime($post['publication_date'])) ?>
                            </p>
                            <p><?= htmlspecialchars(substr($post['content'], 0, 100)) ?>...</p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No blogs found for your search query.</p>
            <?php endif; ?>
        </section>

        <!-- Right Section: Categories -->
        <aside class="sidebar">
            <!-- Categories -->
            <div class="categories">
                <h3>CATEGORIES</h3>
                <ul>
                    <?php while ($category = $category_result->fetch_assoc()) { ?>
                        <li>
                            <a href="?category=<?= htmlspecialchars($category['category']) ?>">
                                <?= htmlspecialchars($category['category']) ?>
                            </a>
                            <span>(<?= htmlspecialchars($category['category_count']) ?>)</span>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </aside>

    </div>
    <?php include 'Includes/footer.html'; ?>
</body>

</html>