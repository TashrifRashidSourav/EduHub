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

// Fetch all posts
$sql = "SELECT p.*, s.name 
        FROM posts p 
        JOIN students s ON p.student_id = s.student_id 
        WHERE (p.status = 'approved' OR p.student_id = ?)";

// Filter by category if needed
if ($category_filter != 'all') {
    $sql .= " AND p.category = ?";
}
$sql .= " ORDER BY post_date DESC";

$stmt = $conn->prepare($sql);
if ($category_filter != 'all') {
    $stmt->bind_param("is", $logged_in_student_id, $category_filter); // Bind both student ID and category
} else {
    $stmt->bind_param("i", $logged_in_student_id); // Only bind the student ID
}
$stmt->execute();
$posts = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posts</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #333;
        }

        .main-container {
            padding: 2rem 0;
            min-height: calc(100vh - 120px);
        }

        .professional-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 2rem;
            margin-bottom: 2rem;
            transition: all 0.3s ease;
        }

        .professional-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        }

        .section-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .section-title i {
            color: #667eea;
        }

        /* Form Styling */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            color: #555;
            margin-bottom: 0.5rem;
            display: block;
        }

        .form-control {
            border: 2px solid #e1e8ed;
            border-radius: 12px;
            padding: 1rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.8);
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            outline: none;
            background: white;
        }

        .form-control[name="content"] {
            min-height: 120px;
            resize: vertical;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 12px;
            padding: 1rem 2rem;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }

        /* Posts Container */
        .posts-container {
            max-height: 70vh;
            overflow-y: auto;
            padding-right: 0.5rem;
        }

        .posts-container::-webkit-scrollbar {
            width: 6px;
        }

        .posts-container::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }

        .posts-container::-webkit-scrollbar-thumb {
            background: rgba(102, 126, 234, 0.3);
            border-radius: 10px;
        }

        .posts-container::-webkit-scrollbar-thumb:hover {
            background: rgba(102, 126, 234, 0.5);
        }

        /* Category Filter */
        .filter-container {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            backdrop-filter: blur(10px);
        }

        .filter-select {
            border: 2px solid #e1e8ed;
            border-radius: 10px;
            padding: 0.75rem;
            font-weight: 500;
            background: white;
        }

        /* Post Items */
        .post-item {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .post-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .post-item:hover {
            transform: translateX(5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .post-category {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.75rem;
        }

        .post-content {
            font-size: 0.95rem;
            line-height: 1.6;
            color: #555;
            margin-bottom: 1rem;
        }

        .post-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid rgba(0, 0, 0, 0.1);
        }

        .post-author {
            font-weight: 600;
            color: #667eea;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .post-date {
            font-size: 0.85rem;
            color: #888;
        }

        .post-image {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            margin: 1rem 0;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .edit-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(102, 126, 234, 0.1);
            color: #667eea;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            transition: all 0.3s ease;
            margin-top: 1rem;
        }

        .edit-link:hover {
            background: #667eea;
            color: white;
            text-decoration: none;
            transform: translateY(-2px);
        }

        .no-posts {
            text-align: center;
            padding: 3rem;
            color: #888;
            font-size: 1.1rem;
        }

        .no-posts i {
            font-size: 3rem;
            color: #ccc;
            margin-bottom: 1rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .main-container {
                padding: 1rem;
            }

            .professional-card {
                padding: 1.5rem;
                margin-bottom: 1rem;
            }

            .section-title {
                font-size: 1.5rem;
            }

            .posts-container {
                max-height: 60vh;
            }

            .post-item {
                padding: 1rem;
            }

            .post-meta {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }
        }

        /* Category specific colors */
        .category-educational {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        .category-entertainment {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        }

        .category-professional {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>
</head>
<body>
<iframe src="curved-background.html"
          style="position: fixed; z-index: -1; border: none; width: 100vw; height: 100vh;">
  </iframe>
<?php include 'navbar.php'; ?>

<div class="main-container">
    <div class="container-fluid">
        <div class="row">
            <!-- Left side: Post creation form -->
            <div class="col-lg-5 col-md-6">
                <div class="professional-card">
                    <h2 class="section-title">
                        <i class="fas <?php echo $edit_mode ? 'fa-edit' : 'fa-plus-circle'; ?>"></i>
                        <?php echo $edit_mode ? 'Edit Post' : 'Create New Post'; ?>
                    </h2>

                    <form action="" method="POST" enctype="multipart/form-data">
                        <?php if ($edit_mode): ?>
                            <input type="hidden" name="post_id" value="<?php echo $post_to_edit['post_id']; ?>">
                        <?php endif; ?>
                        
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-pen"></i> Content
                            </label>
                            <textarea class="form-control" name="content" required placeholder="Share your thoughts, ideas, or experiences..."><?php echo $edit_mode ? htmlspecialchars($post_to_edit['content']) : ''; ?></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-tags"></i> Category
                            </label>
                            <select class="form-control" name="category" required>
                                <option value="">Select a category</option>
                                <option value="educational" <?php echo ($edit_mode && $post_to_edit['category'] == 'educational') ? 'selected' : ''; ?>>
                                    📚 Educational
                                </option>
                                <option value="entertainment" <?php echo ($edit_mode && $post_to_edit['category'] == 'entertainment') ? 'selected' : ''; ?>>
                                    🎬 Entertainment
                                </option>
                                <option value="professional" <?php echo ($edit_mode && $post_to_edit['category'] == 'professional') ? 'selected' : ''; ?>>
                                    💼 Professional
                                </option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-image"></i> Attach Image (Optional)
                            </label>
                            <input type="file" class="form-control" name="picture" accept="image/*">
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas <?php echo $edit_mode ? 'fa-save' : 'fa-share'; ?>"></i>
                            <?php echo $edit_mode ? 'Update Post' : 'Publish Post'; ?>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Right side: Post list with category filter -->
            <div class="col-lg-7 col-md-6">
                <div class="professional-card">
                    <div class="filter-container">
                        <form method="GET" action="">
                            <label class="form-label">
                                <i class="fas fa-filter"></i> Filter by Category
                            </label>
                            <select class="form-control filter-select" name="category" onchange="this.form.submit()">
                                <option value="all" <?php echo $category_filter == 'all' ? 'selected' : ''; ?>>
                                    🌟 All Categories
                                </option>
                                <option value="educational" <?php echo $category_filter == 'educational' ? 'selected' : ''; ?>>
                                    📚 Educational
                                </option>
                                <option value="entertainment" <?php echo $category_filter == 'entertainment' ? 'selected' : ''; ?>>
                                    🎬 Entertainment
                                </option>
                                <option value="professional" <?php echo $category_filter == 'professional' ? 'selected' : ''; ?>>
                                    💼 Professional
                                </option>
                            </select>
                        </form>
                    </div>

                    <h2 class="section-title">
                        <i class="fas fa-stream"></i>
                        Recent Posts
                    </h2>

                    <div class="posts-container">
                        <?php if ($posts->num_rows > 0): ?>
                            <?php while ($row = $posts->fetch_assoc()): ?>
                                <div class="post-item">
                                    <div class="post-category category-<?php echo $row['category']; ?>">
                                        <?php 
                                        $categoryIcons = [
                                            'educational' => '📚',
                                            'entertainment' => '🎬',
                                            'professional' => '💼'
                                        ];
                                        echo $categoryIcons[$row['category']] . ' ' . ucfirst($row['category']); 
                                        ?>
                                    </div>
                                    
                                    <div class="post-content">
                                        <?php echo nl2br(htmlspecialchars($row['content'])); ?>
                                    </div>
                                    
                                    <?php if ($row['picture_path']): ?>
                                        <img src="<?php echo htmlspecialchars($row['picture_path']); ?>" 
                                             alt="Post Image" class="post-image">
                                    <?php endif; ?>
                                    
                                    <div class="post-meta">
                                        <div class="post-author">
                                            <i class="fas fa-user-circle"></i>
                                            <?php echo htmlspecialchars($row['name']); ?>
                                        </div>
                                        <div class="post-date">
                                            <i class="fas fa-calendar-alt"></i>
                                            <?php echo date('M j, Y', strtotime($row['post_date'])); ?>
                                        </div>
                                    </div>

                                    <!-- Show edit link only if the logged-in user is the author of the post -->
                                    <?php if ($logged_in_student_id == $row['student_id']): ?>
                                        <a href="?edit_id=<?php echo $row['post_id']; ?>" class="edit-link">
                                            <i class="fas fa-edit"></i>
                                            Edit Post
                                        </a>
                                    <?php endif; ?>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <div class="no-posts">
                                <i class="fas fa-inbox"></i>
                                <p>No posts available in this category.</p>
                                <small>Be the first to share something!</small>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="footer-placeholder"></div>

<script>
  fetch('footer.html')
    .then(res => res.text())
    .then(data => {
      document.getElementById('footer-placeholder').innerHTML = data;
    });
</script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>