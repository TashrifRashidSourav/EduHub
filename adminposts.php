<?php
// Include the database connection and session start
include 'db_connect.php';
session_start();

// Get the logged-in student's ID from session
$logged_in_student_id = $_SESSION['student_id'];

// Initialize variables for edit mode
$edit_mode = false;
$post_to_edit = null;

// Handle form submission for new post or updating a post
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $logged_in_student_id; // Use the logged-in student's ID
    $content = $_POST['content'];
    $category = $_POST['category'];

    // Handle file upload
    $target_file = null;
    if (isset($_FILES['picture']) && $_FILES['picture']['error'] == 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["picture"]["name"]);
        move_uploaded_file($_FILES["picture"]["tmp_name"], $target_file);
    }

    if (isset($_POST['post_id']) && $_POST['post_id']) {
        // Edit existing post
        $post_id = $_POST['post_id'];
        $sql = "UPDATE posts SET content = ?, category = ?, picture_path = ? WHERE post_id = ? AND student_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssii", $content, $category, $target_file, $post_id, $student_id);
        $stmt->execute();
    } else {
        // Insert new post
        $sql = "INSERT INTO posts (student_id, content, category, picture_path) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isss", $student_id, $content, $category, $target_file);
        $stmt->execute();
    }
}

// Handle request to edit an existing post
if (isset($_GET['edit_id'])) {
    $edit_mode = true;
    $edit_id = $_GET['edit_id'];
    $sql = "SELECT * FROM posts WHERE post_id = ? AND student_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $edit_id, $logged_in_student_id); // Ensure the post belongs to the logged-in user
    $stmt->execute();
    $result = $stmt->get_result();
    $post_to_edit = $result->fetch_assoc();
}

// Fetch all posts and their authors from the database in descending order
$category_filter = isset($_GET['category']) ? $_GET['category'] : 'all';
$sql = "SELECT p.*, s.name FROM posts p JOIN students s ON p.student_id = s.student_id";
if ($category_filter != 'all') {
    $sql .= " WHERE p.category = '$category_filter'";
}
$sql .= " ORDER BY post_date DESC";
$posts = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posts</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body, html {
            height: 100%;
            background-color: #f8f9fa;
        }
        .container {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        h1 {
            color: #007BFF;
            text-align: center;
        }
        .form-container, .posts-container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            height: 90vh;
        }
        textarea, select, input[type="file"], button {
            width: 100%;
            margin-top: 10px;
        }
        button {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            margin-top: 15px;
        }
        button:hover {
            background-color: #0056b3;
        }
        img {
            display: block;
            margin-top: 10px;
            max-width: 100px;
            max-height: 100px;
        }
        .edit-link {
            display: inline-block;
            margin-top: 10px;
            color: #007BFF;
        }
        .edit-link:hover {
            color: #0056b3;
            text-decoration: underline;
        }
        .row {
            height: 100%;
        }
        .filter-select {
            width: 100%;
            margin-bottom: 20px;
            position: sticky;
            top: 0;
            z-index: 1000;
            background-color: white;
        }
        .posts-container {
            overflow-y: auto;
        }
    </style>
</head>
<body>
<?php include 'navbaradmin.php'; ?>
<div class="container">
    <div class="row w-100">
        <!-- Left side: Post creation form -->
        <div class="col-md-6">
            <div class="form-container">
                <h1><?php echo $edit_mode ? 'Edit Post' : 'Create a Post'; ?></h1>

                <form action="" method="POST" enctype="multipart/form-data">
                    <?php if ($edit_mode): ?>
                        <input type="hidden" name="post_id" value="<?php echo $post_to_edit['post_id']; ?>">
                    <?php endif; ?>
                    <div class="form-group">
                        <textarea class="form-control" name="content" required placeholder="Write your post here..."><?php echo $edit_mode ? htmlspecialchars($post_to_edit['content']) : ''; ?></textarea>
                    </div>
                    <div class="form-group">
                        <select class="form-control" name="category" required>
                            <option value="educational" <?php echo ($edit_mode && $post_to_edit['category'] == 'educational') ? 'selected' : ''; ?>>Educational</option>
                            <option value="entertainment" <?php echo ($edit_mode && $post_to_edit['category'] == 'entertainment') ? 'selected' : ''; ?>>Entertainment</option>
                            <option value="professional" <?php echo ($edit_mode && $post_to_edit['category'] == 'professional') ? 'selected' : ''; ?>>Professional</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="file" class="form-control" name="picture" accept="image/*">
                    </div>
                    <button type="submit" class="btn btn-primary"><?php echo $edit_mode ? 'Update Post' : 'Submit Post'; ?></button>
                </form>
            </div>
        </div>

        <!-- Right side: Post list with category filter -->
        <div class="col-md-6">
            <div class="posts-container">
                <form method="GET" action="">
                    <select class="form-control filter-select" name="category" onchange="this.form.submit()">
                        <option value="all" <?php echo $category_filter == 'all' ? 'selected' : ''; ?>>All</option>
                        <option value="educational" <?php echo $category_filter == 'educational' ? 'selected' : ''; ?>>Educational</option>
                        <option value="entertainment" <?php echo $category_filter == 'entertainment' ? 'selected' : ''; ?>>Entertainment</option>
                        <option value="professional" <?php echo $category_filter == 'professional' ? 'selected' : ''; ?>>Professional</option>
                    </select>
                </form>

                <h2 class="mt-3">Posts</h2>
                <?php if ($posts->num_rows > 0): ?>
                    <ul class="list-group">
                        <?php while ($row = $posts->fetch_assoc()): ?>
                            <li class="list-group-item">
                                <h4><?php echo htmlspecialchars($row['category']); ?></h4>
                                <p><?php echo nl2br(htmlspecialchars($row['content'])); ?></p>
                                <p><strong>By:</strong> <?php echo htmlspecialchars($row['name']); ?></p>
                                <?php if ($row['picture_path']): ?>
                                    <img src="<?php echo htmlspecialchars($row['picture_path']); ?>" alt="Post Image">
                                <?php endif; ?>
                                <p>Date: <?php echo $row['post_date']; ?></p>

                                <!-- Show edit link only if the logged-in user is the author of the post -->
                                <?php if ($logged_in_student_id == $row['student_id']): ?>
                                    <a href="?edit_id=<?php echo $row['post_id']; ?>" class="edit-link">Edit</a>
                                <?php endif; ?>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                <?php else: ?>
                    <p>No posts available.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
</body>
</html>
