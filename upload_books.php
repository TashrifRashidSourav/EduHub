<?php
// Include the database connection file
include 'db_connect.php';

// Start the session to get the logged-in user information
session_start();

// Ensure user is logged in
if (!isset($_SESSION['student_id'])) {
    die("You must be logged in to access this page.");
}

$student_id = $_SESSION['student_id']; // Get student ID from session

// Define upload directory paths
$image_upload_dir = 'uploads/images/';
$file_upload_dir = 'uploads/files/';

// Create directories if they do not exist
if (!is_dir($image_upload_dir)) {
    mkdir($image_upload_dir, 0777, true); // Create directory with permissions
}
if (!is_dir($file_upload_dir)) {
    mkdir($file_upload_dir, 0777, true); // Create directory with permissions
}

// Handle the file upload
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $price = $_POST['price'];
    $conditions = $_POST['conditions'];

    // Handle image upload
    $image_path = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_name = $_FILES['image']['name'];
        $image_path = $image_upload_dir . basename($image_name);

        if (move_uploaded_file($image_tmp_name, $image_path)) {
            // Successfully uploaded
        } else {
            $image_path = null; // Failed to upload image
        }
    }

    // Handle file upload
    $file_path = null;
    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $file_tmp_name = $_FILES['file']['tmp_name'];
        $file_name = $_FILES['file']['name'];
        $file_path = $file_upload_dir . basename($file_name);

        if (move_uploaded_file($file_tmp_name, $file_path)) {
            // Successfully uploaded
        } else {
            $file_path = null; // Failed to upload file
        }
    }

    // Insert book information into the database
    $stmt = $conn->prepare("INSERT INTO books (student_id, title, author, price, conditions, image_path, file_path) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('issssss', $student_id, $title, $author, $price, $conditions, $image_path, $file_path);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Book uploaded successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
    $stmt->close();
}

// Handle book deletion
if (isset($_GET['delete'])) {
    $book_id = intval($_GET['delete']);
    
    // Get book details to delete the files
    $stmt = $conn->prepare("SELECT image_path, file_path FROM books WHERE book_id = ? AND student_id = ?");
    $stmt->bind_param('ii', $book_id, $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row['image_path'] && file_exists($row['image_path'])) unlink($row['image_path']);
        if ($row['file_path'] && file_exists($row['file_path'])) unlink($row['file_path']);
        
        // Delete the book record from the database
        $stmt = $conn->prepare("DELETE FROM books WHERE book_id = ? AND student_id = ?");
        $stmt->bind_param('ii', $book_id, $student_id);
        
        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Book deleted successfully!</div>";
        } else {
            echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Error: Book not found or you do not have permission to delete this book.</div>";
    }
    $stmt->close();
}

// Fetch books of the logged-in user
$stmt = $conn->prepare("SELECT * FROM books WHERE student_id = ?");
$stmt->bind_param('i', $student_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload and View Books</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css"> <!-- Link to your external CSS file if any -->
    <style>
        body {
            background-color: #f4f4f9;
        }
        .container {
            margin-top: 50px;
        }
        .book-image {
            max-width: 150px;
            max-height: 200px;
            object-fit: cover;
        }
        .book-list .book-item {
            margin-bottom: 30px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #fff;
        }
        .book-list .book-item:hover {
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .book-list img {
            max-width: 100%;
            height: auto;
        }
        .book-list .btn {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <!-- Include Navbar -->
    <?php include 'navbar.php'; ?>

    <!-- Main Content -->
    <div class="container">
        <h1 class="text-center mb-4">Upload Book</h1>
        <form action="upload_books.php" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" class="form-control" required>
                <div class="invalid-feedback">Please provide a title.</div>
            </div>
            <div class="form-group">
                <label for="author">Author:</label>
                <input type="text" id="author" name="author" class="form-control" required>
                <div class="invalid-feedback">Please provide an author.</div>
            </div>
            <div class="form-group">
                <label for="price">Price:</label>
                <input type="number" step="0.01" id="price" name="price" class="form-control" required>
                <div class="invalid-feedback">Please provide a price.</div>
            </div>
            <div class="form-group">
                <label for="conditions">Conditions:</label>
                <input type="text" id="conditions" name="conditions" class="form-control" required>
                <div class="invalid-feedback">Please provide the conditions.</div>
            </div>
            <div class="form-group">
                <label for="image">Upload Image:</label>
                <input type="file" id="image" name="image" class="form-control-file" accept="image/*">
            </div>
            <div class="form-group">
                <label for="file">Upload File:</label>
                <input type="file" id="file" name="file" class="form-control-file">
            </div>
            <button type="submit" class="btn btn-primary">Upload</button>
        </form>

        <h1 class="text-center mt-5 mb-4">My Books</h1>
        <div class="book-list">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="book-item">
                    <h2><?php echo htmlspecialchars($row['title']); ?></h2>
                    <p><strong>Author:</strong> <?php echo htmlspecialchars($row['author']); ?></p>
                    <p><strong>Price:</strong> $<?php echo htmlspecialchars(number_format($row['price'], 2)); ?></p>
                    <p><strong>Conditions:</strong> <?php echo htmlspecialchars($row['conditions']); ?></p>
                    <?php if ($row['image_path']): ?>
                        <img src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="Book Image" class="book-image">
                    <?php endif; ?>
                    <?php if ($row['file_path']): ?>
                        <p><a href="<?php echo htmlspecialchars($row['file_path']); ?>" download class="btn btn-success">Download File</a></p>
                    <?php endif; ?>
                    <a href="upload_books.php?delete=<?php echo $row['book_id']; ?>" class="btn btn-danger">Delete</a>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <!-- Include Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // Example of form validation script
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                var forms = document.getElementsByClassName('needs-validation');
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();
    </script>
</body>
</html>
<?php
$conn->close();
?>
