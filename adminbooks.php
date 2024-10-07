<?php

session_start();


include 'db_connect.php';

if (!isset($_SESSION['student_id'])) {
    
    header('Location: login.php');
    exit();
}

$student_id = $_SESSION['student_id'];


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $price = $_POST['price'];
    $conditions = $_POST['conditions'];

    
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_name = $_FILES['image']['name'];
        $image_path = 'uploads/images/' . $image_name;
        move_uploaded_file($image_tmp_name, $image_path);
    } else {
        $image_path = null;
    }

   
    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $file_tmp_name = $_FILES['file']['tmp_name'];
        $file_name = $_FILES['file']['name'];
        $file_path = 'uploads/files/' . $file_name;
        move_uploaded_file($file_tmp_name, $file_path);
    } else {
        $file_path = null;
    }

   
    $sql = "INSERT INTO books (student_id, title, author, price, conditions, image_path, file_path) 
            VALUES ('$student_id', '$title', '$author', '$price', '$conditions', '$image_path', '$file_path')";
    
    if ($conn->query($sql) === TRUE) {
        echo "Book uploaded successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}

if (isset($_GET['delete'])) {
    $book_id = $_GET['delete'];
    
  
    $sql = "SELECT image_path, file_path FROM books WHERE book_id = $book_id";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row['image_path']) unlink($row['image_path']);
        if ($row['file_path']) unlink($row['file_path']);
    }

    
    $sql = "DELETE FROM books WHERE book_id = $book_id";
    
    if ($conn->query($sql) === TRUE) {
        echo "Book deleted successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}


$sql = "SELECT * FROM books WHERE student_id = $student_id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload and View Books</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .book-image {
            max-width: 150px;
            max-height: 150px;
        }
        .form-label {
            font-size: 0.9rem;
        }
        .form-control {
            font-size: 0.85rem;
        }
        .book-list .book-item {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 5px;
        }
        .book-list h2 {
            font-size: 1.25rem;
        }
    </style>
</head>
<body class="bg-light">

    <?php include 'navbaradmin.php'; ?>

    <div class="container mt-5">
        <h1 class="mb-4">Upload Book</h1>
        <form action="upload_books.php" method="post" enctype="multipart/form-data" class="mb-5">
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" id="title" name="title" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="author" class="form-label">Author</label>
                <input type="text" id="author" name="author" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <input type="number" step="0.01" id="price" name="price" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="conditions" class="form-label">Conditions</label>
                <input type="text" id="conditions" name="conditions" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Upload Image</label>
                <input type="file" id="image" name="image" class="form-control" accept="image/*">
            </div>
            <div class="mb-3">
                <label for="file" class="form-label">Upload File</label>
                <input type="file" id="file" name="file" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Upload</button>
        </form>

        <h1>My Books</h1>
        <div class="book-list">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="book-item">
                    <h2><?php echo htmlspecialchars($row['title']); ?></h2>
                    <p><strong>Author:</strong> <?php echo htmlspecialchars($row['author']); ?></p>
                    <p><strong>Price:</strong> <?php echo htmlspecialchars($row['price']); ?></p>
                    <p><strong>Conditions:</strong> <?php echo htmlspecialchars($row['conditions']); ?></p>
                    <?php if ($row['image_path']): ?>
                        <img src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="Book Image" class="book-image">
                    <?php endif; ?>
                    <?php if ($row['file_path']): ?>
                        <p><a href="<?php echo htmlspecialchars($row['file_path']); ?>" class="btn btn-info btn-sm" download>Download File</a></p>
                    <?php endif; ?>
                    <a href="upload_books.php?delete=<?php echo $row['book_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this book?');">Delete</a>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
