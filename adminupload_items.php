<?php

include 'db_connect.php';


session_start();


if (!isset($_SESSION['student_id'])) {
    die("You must be logged in to access this page.");
}

$student_id = $_SESSION['student_id']; 


$image_upload_dir = 'uploads/images/';
$file_upload_dir = 'uploads/files/';


if (!is_dir($image_upload_dir)) {
    mkdir($image_upload_dir, 0777, true); 
}
if (!is_dir($file_upload_dir)) {
    mkdir($file_upload_dir, 0777, true); 
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $author_or_brand = $_POST['author_or_brand'];
    $price = $_POST['price'];
    $conditions = $_POST['conditions'];
    $category = $_POST['category'];

    
    $image_path = null;
    if ($category != 'Study Materials' && isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_name = $_FILES['image']['name'];
        $image_path = $image_upload_dir . basename($image_name);

        if (move_uploaded_file($image_tmp_name, $image_path)) {
            
        } else {
            $image_path = null; 
        }
    }


    $file_path = null;
    if ($category == 'Study Materials' && isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $file_tmp_name = $_FILES['file']['tmp_name'];
        $file_name = $_FILES['file']['name'];
        $file_path = $file_upload_dir . basename($file_name);

        if (move_uploaded_file($file_tmp_name, $file_path)) {
          
        } else {
            $file_path = null; 
        }
    }

    $pdf_link_or_image = $category == 'Study Materials' ? $file_path : $image_path;

 
    $stmt = $conn->prepare("INSERT INTO items (student_id, title, author_or_brand, price, conditions, category, pdf_link_or_image) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('issdsss', $student_id, $title, $author_or_brand, $price, $conditions, $category, $pdf_link_or_image);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Item uploaded successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
    $stmt->close();
}


if (isset($_GET['delete'])) {
    $item_id = intval($_GET['delete']);

    
    $stmt = $conn->prepare("SELECT pdf_link_or_image, category FROM items WHERE item_id = ? AND student_id = ?");
    $stmt->bind_param('ii', $item_id, $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row['pdf_link_or_image'] && file_exists($row['pdf_link_or_image'])) unlink($row['pdf_link_or_image']);

        // Delete the item record from the database
        $stmt = $conn->prepare("DELETE FROM items WHERE item_id = ? AND student_id = ?");
        $stmt->bind_param('ii', $item_id, $student_id);

        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Item deleted successfully!</div>";
        } else {
            echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Error: Item not found or you do not have permission to delete this item.</div>";
    }
    $stmt->close();
}


$stmt = $conn->prepare("SELECT * FROM items WHERE student_id = ?");
$stmt->bind_param('i', $student_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload and View Items</title>
   
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background-color: #f4f4f9;
        }
        .container {
            margin-top: 50px;
        }
        .item-card {
            margin-bottom: 30px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #fff;
            transition: 0.3s;
        }
        .item-card:hover {
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .item-image {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body>

    <?php include 'navbaradmin.php'; ?>


    <div class="container">
        <h1 class="text-center mb-4">Upload Item</h1>
        <form action="adminupload_items.php" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" class="form-control" required>
                <div class="invalid-feedback">Please provide a title.</div>
            </div>
            <div class="form-group">
                <label for="author_or_brand">Author or Brand:</label>
                <input type="text" id="author_or_brand" name="author_or_brand" class="form-control" required>
                <div class="invalid-feedback">Please provide the author or brand.</div>
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
                <label for="category">Category:</label>
                <select id="category" name="category" class="form-control" required>
                    <option value="Electronics">Electronics</option>
                    <option value="Study Materials">Study Materials</option>
                    <option value="Computer Components">Computer Components</option>
                </select>
                <div class="invalid-feedback">Please choose a category.</div>
            </div>
            <div class="form-group">
                <label for="image">Upload Image (for non-Study Materials):</label>
                <input type="file" id="image" name="image" class="form-control-file" accept="image/*">
            </div>
            <div class="form-group">
                <label for="file">Upload File (for Study Materials):</label>
                <input type="file" id="file" name="file" class="form-control-file">
            </div>
            <button type="submit" class="btn btn-primary">Upload</button>
        </form>

        <h1 class="text-center mt-5 mb-4">My Items</h1>
        <div class="row">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="col-md-4">
                    <div class="item-card">
                        <h2><?php echo htmlspecialchars($row['title']); ?></h2>
                        <p><strong>Author/Brand:</strong> <?php echo htmlspecialchars($row['author_or_brand']); ?></p>
                        <p><strong>Price:</strong> $<?php echo htmlspecialchars(number_format($row['price'], 2)); ?></p>
                        <p><strong>Conditions:</strong> <?php echo htmlspecialchars($row['conditions']); ?></p>
                        <p><strong>Category:</strong> <?php echo htmlspecialchars($row['category']); ?></p>
                        <?php if ($row['category'] == 'Study Materials' && $row['pdf_link_or_image']): ?>
                            <p><a href="<?php echo htmlspecialchars($row['pdf_link_or_image']); ?>" class="btn btn-secondary" target="_blank">View PDF</a></p>
                        <?php elseif ($row['pdf_link_or_image']): ?>
                            <img src="<?php echo htmlspecialchars($row['pdf_link_or_image']); ?>" alt="Item Image" class="item-image">
                        <?php endif; ?>
                        <a href="?delete=<?php echo $row['item_id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // Example starter JavaScript for disabling form submissions if there are invalid fields
        (function () {
            'use strict';
            window.addEventListener('load', function () {
                var forms = document.getElementsByClassName('needs-validation');
                var validation = Array.prototype.filter.call(forms, function (form) {
                    form.addEventListener('submit', function (event) {
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
