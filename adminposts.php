<?php

include 'db_connect.php';
session_start();

$logged_in_student_id = $_SESSION['student_id'];


$edit_mode = false;
$post_to_edit = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $logged_in_student_id; 
    $content = $_POST['content'];
    $category = $_POST['category'];

    $target_file = null;
    if (isset($_FILES['picture']) && $_FILES['picture']['error'] == 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["picture"]["name"]);
        move_uploaded_file($_FILES["picture"]["tmp_name"], $target_file);
    }

    if (isset($_POST['post_id']) && $_POST['post_id']) {
       
        $post_id = $_POST['post_id'];
        $sql = "UPDATE posts SET content = ?, category = ?, picture_path = ? WHERE post_id = ? AND student_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssii", $content, $category, $target_file, $post_id, $student_id);
        $stmt->execute();
    } else {
     
        $sql = "INSERT INTO posts (student_id, content, category, picture_path) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isss", $student_id, $content, $category, $target_file);
        $stmt->execute();
    }
}

if (isset($_GET['edit_id'])) {
    $edit_mode = true;
    $edit_id = $_GET['edit_id'];
    $sql = "SELECT * FROM posts WHERE post_id = ? AND student_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $edit_id, $logged_in_student_id); 
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
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
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
            min-height: 100vh;
        }

        .page-header {
            text-align: center;
            margin-bottom: 30px;
            color: white;
        }

        .page-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        .page-header p {
            font-size: 1.1rem;
            opacity: 0.9;
            font-weight: 300;
        }

        .content-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-top: 20px;
        }

        .card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            border: 1px solid rgba(255,255,255,0.2);
        }

        .form-card {
            position: sticky;
            top: 20px;
            height: fit-content;
        }

        .form-header {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }

        .form-header i {
            font-size: 1.5rem;
            color: #667eea;
            margin-right: 12px;
        }

        .form-header h2 {
            font-size: 1.5rem;
            font-weight: 600;
            color: #333;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #555;
            font-size: 0.95rem;
        }

        .form-control {
            width: 100%;
            padding: 15px;
            border: 2px solid #e1e5e9;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8f9fa;
            font-family: 'Inter', sans-serif;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            transform: translateY(-1px);
        }

        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }

        .category-select {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 12px center;
            background-repeat: no-repeat;
            background-size: 16px;
            appearance: none;
        }

        .file-input-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
            width: 100%;
        }

        .file-input-wrapper input[type=file] {
            position: absolute;
            left: -9999px;
        }

        .file-input-label {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 15px;
            background: #f8f9fa;
            border: 2px dashed #e1e5e9;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
            color: #666;
        }

        .file-input-label:hover {
            border-color: #667eea;
            background: rgba(102, 126, 234, 0.05);
            color: #667eea;
        }

        .file-input-label i {
            margin-right: 8px;
            font-size: 1.1rem;
        }

        .submit-btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .posts-card {
            max-height: calc(100vh - 40px);
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .posts-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }

        .posts-title {
            display: flex;
            align-items: center;
        }

        .posts-title i {
            font-size: 1.5rem;
            color: #667eea;
            margin-right: 12px;
        }

        .posts-title h2 {
            font-size: 1.5rem;
            font-weight: 600;
            color: #333;
        }

        .filter-wrapper {
            position: relative;
            min-width: 200px;
        }

        .filter-select {
            padding: 10px 15px;
            border: 2px solid #e1e5e9;
            border-radius: 8px;
            background: white;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .filter-select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .posts-list {
            flex: 1;
            overflow-y: auto;
            padding-right: 10px;
        }

        .posts-list::-webkit-scrollbar {
            width: 6px;
        }

        .posts-list::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        .posts-list::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        .posts-list::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        .post-item {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .post-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .post-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }

        .post-category {
            display: inline-block;
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 15px;
        }

        .category-educational {
            background: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }

        .category-entertainment {
            background: rgba(255, 193, 7, 0.1);
            color: #ffc107;
        }

        .category-professional {
            background: rgba(102, 126, 234, 0.1);
            color: #667eea;
        }

        .post-content {
            font-size: 1rem;
            line-height: 1.6;
            color: #333;
            margin-bottom: 15px;
            font-weight: 400;
        }

        .post-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #e9ecef;
            font-size: 0.9rem;
            color: #666;
        }

        .post-author {
            display: flex;
            align-items: center;
        }

        .post-author i {
            margin-right: 8px;
            color: #667eea;
        }

        .post-date {
            display: flex;
            align-items: center;
        }

        .post-date i {
            margin-right: 8px;
            color: #667eea;
        }

        .post-image {
            margin: 15px 0;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .post-image img {
            width: 100%;
            max-height: 200px;
            object-fit: cover;
            display: block;
        }

        .edit-link {
            display: inline-flex;
            align-items: center;
            padding: 8px 15px;
            background: rgba(102, 126, 234, 0.1);
            color: #667eea;
            text-decoration: none;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.3s ease;
            margin-top: 15px;
        }

        .edit-link:hover {
            background: #667eea;
            color: white;
            text-decoration: none;
            transform: translateY(-1px);
        }

        .edit-link i {
            margin-right: 6px;
        }

        .no-posts {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }

        .no-posts i {
            font-size: 3rem;
            color: #ddd;
            margin-bottom: 20px;
        }

        .no-posts h3 {
            font-size: 1.3rem;
            margin-bottom: 10px;
            color: #333;
        }

        .no-posts p {
            font-size: 1rem;
            opacity: 0.7;
        }

        @media (max-width: 992px) {
            .content-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .form-card {
                position: static;
            }

            .main-container {
                padding: 15px;
            }

            .page-header h1 {
                font-size: 2rem;
            }

            .posts-header {
                flex-direction: column;
                align-items: stretch;
                gap: 15px;
            }

            .filter-wrapper {
                min-width: auto;
            }
        }

        @media (max-width: 576px) {
            .card {
                padding: 20px;
            }

            .page-header h1 {
                font-size: 1.8rem;
            }

            .post-meta {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
        }
    </style>
</head>
<body>
    <?php include 'navbaradmin.php'; ?>
    
    <div class="main-container">
        <div class="page-header">
            <h1><i class="fas fa-comments"></i> Admin Notice</h1>
            <p>Share your thoughts, ideas, and connect with fellow students</p>
        </div>

        <div class="content-grid">
            <!-- Left side: Post creation form -->
            <div class="card form-card">
                <div class="form-header">
                    <i class="<?php echo $edit_mode ? 'fas fa-edit' : 'fas fa-plus-circle'; ?>"></i>
                    <h2><?php echo $edit_mode ? 'Edit Post' : 'Create New Post'; ?></h2>
                </div>

                <form action="" method="POST" enctype="multipart/form-data">
                    <?php if ($edit_mode): ?>
                        <input type="hidden" name="post_id" value="<?php echo $post_to_edit['post_id']; ?>">
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label for="content"><i class="fas fa-pen"></i> What's on your mind?</label>
                        <textarea class="form-control" name="content" id="content" required placeholder="Share your thoughts, ideas, or experiences..."><?php echo $edit_mode ? htmlspecialchars($post_to_edit['content']) : ''; ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="category"><i class="fas fa-tags"></i> Category</label>
                        <select class="form-control category-select" name="category" id="category" required>
                            <option value="">Select a category</option>
                            <option value="educational" <?php echo ($edit_mode && $post_to_edit['category'] == 'educational') ? 'selected' : ''; ?>>📚 Educational</option>
                            <option value="entertainment" <?php echo ($edit_mode && $post_to_edit['category'] == 'entertainment') ? 'selected' : ''; ?>>🎮 Entertainment</option>
                            <option value="professional" <?php echo ($edit_mode && $post_to_edit['category'] == 'professional') ? 'selected' : ''; ?>>💼 Professional</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label><i class="fas fa-image"></i> Add Image (Optional)</label>
                        <div class="file-input-wrapper">
                            <input type="file" name="picture" id="picture" accept="image/*">
                            <label for="picture" class="file-input-label">
                                <i class="fas fa-cloud-upload-alt"></i>
                                Choose Image
                            </label>
                        </div>
                    </div>
                    
                    <button type="submit" class="submit-btn">
                        <i class="<?php echo $edit_mode ? 'fas fa-save' : 'fas fa-paper-plane'; ?>"></i>
                        <?php echo $edit_mode ? 'Update Post' : 'Publish Post'; ?>
                    </button>
                </form>
            </div>

            <!-- Right side: Posts list -->
            <div class="card posts-card">
                <div class="posts-header">
                    <div class="posts-title">
                        <i class="fas fa-stream"></i>
                        <h2>Recent Posts</h2>
                    </div>
                    <div class="filter-wrapper">
                        <form method="GET" action="">
                            <select class="filter-select" name="category" onchange="this.form.submit()">
                                <option value="all" <?php echo $category_filter == 'all' ? 'selected' : ''; ?>>🌟 All Categories</option>
                                <option value="educational" <?php echo $category_filter == 'educational' ? 'selected' : ''; ?>>📚 Educational</option>
                                <option value="entertainment" <?php echo $category_filter == 'entertainment' ? 'selected' : ''; ?>>🎮 Entertainment</option>
                                <option value="professional" <?php echo $category_filter == 'professional' ? 'selected' : ''; ?>>💼 Professional</option>
                            </select>
                        </form>
                    </div>
                </div>

                <div class="posts-list">
                    <?php if ($posts->num_rows > 0): ?>
                        <?php while ($row = $posts->fetch_assoc()): ?>
                            <div class="post-item">
                                <div class="post-category category-<?php echo $row['category']; ?>">
                                    <?php 
                                    $icons = [
                                        'educational' => '📚',
                                        'entertainment' => '🎮',
                                        'professional' => '💼'
                                    ];
                                    echo $icons[$row['category']] . ' ' . ucfirst($row['category']); 
                                    ?>
                                </div>
                                
                                <div class="post-content">
                                    <?php echo nl2br(htmlspecialchars($row['content'])); ?>
                                </div>
                                
                                <?php if ($row['picture_path']): ?>
                                    <div class="post-image">
                                        <img src="<?php echo htmlspecialchars($row['picture_path']); ?>" alt="Post Image">
                                    </div>
                                <?php endif; ?>
                                
                                <div class="post-meta">
                                    <div class="post-author">
                                        <i class="fas fa-user"></i>
                                        <strong><?php echo htmlspecialchars($row['name']); ?></strong>
                                    </div>
                                    <div class="post-date">
                                        <i class="fas fa-calendar-alt"></i>
                                        <?php echo date('M j, Y', strtotime($row['post_date'])); ?>
                                    </div>
                                </div>
                                
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
                            <i class="fas fa-comments"></i>
                            <h3>No posts yet</h3>
                            <p>Be the first to share something with the community!</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <script>
        // File input enhancement
        document.getElementById('picture').addEventListener('change', function(e) {
            const label = document.querySelector('.file-input-label');
            const fileName = e.target.files[0]?.name;
            if (fileName) {
                label.innerHTML = `<i class="fas fa-check-circle"></i> ${fileName}`;
                label.style.color = '#28a745';
                label.style.borderColor = '#28a745';
            } else {
                label.innerHTML = `<i class="fas fa-cloud-upload-alt"></i> Choose Image`;
                label.style.color = '#666';
                label.style.borderColor = '#e1e5e9';
            }
        });

        // Auto-resize textarea
        const textarea = document.getElementById('content');
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 300) + 'px';
        });

        // Smooth scrolling for posts
        document.querySelectorAll('.edit-link').forEach(link => {
            link.addEventListener('click', function() {
                setTimeout(() => {
                    document.querySelector('.form-card').scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }, 100);
            });
        });

        // Form validation feedback
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const submitBtn = document.querySelector('.submit-btn');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Publishing...';
            submitBtn.disabled = true;
        });
    </script>
</body>
</html>