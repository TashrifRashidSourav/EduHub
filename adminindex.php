<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php"); // Redirect to login page
    exit();
}

// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "EduHub");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to get pending records
function getPendingRecords($conn, $table) {
    $query = "SELECT * FROM $table WHERE status = 'pending'";
    return mysqli_query($conn, $query);
}

// Get all pending items
$posts = getPendingRecords($conn, 'posts');
$books = getPendingRecords($conn, 'books');
$blood_donation = getPendingRecords($conn, 'blood_donation');
$items = getPendingRecords($conn, 'items');

// Check for success messages
$message = '';
if (isset($_GET['status']) && $_GET['status'] == 'success') {
    $message = 'Action completed successfully.';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Index</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 30px;
        }
        h1 {
            margin-bottom: 30px;
            text-align: center;
            color: #007BFF;
        }
        .message {
            color: green;
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            margin-bottom: 40px;
        }
        th {
            background-color: #007BFF;
            color: white;
        }
        tr:hover {
            background-color: #e9ecef;
            transition: background-color 0.3s;
        }
        .actions a {
            margin-right: 10px;
        }
    </style>
</head>
<body>

    <!-- Include Navbar -->
    <?php include 'navbaradmin.php'; ?>

    <div class="container">
        <h1>Admin Index</h1>
        
        <?php if ($message): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <!-- Pending Posts -->
        <h2>Pending Posts</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Post ID</th>
                    <th>Content</th>
                    <th>Student ID</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($posts)) { ?>
                <tr>
                    <td><?php echo $row['post_id']; ?></td>
                    <td><?php echo $row['content']; ?></td>
                    <td><?php echo $row['student_id']; ?></td>
                    <td><?php echo $row['status']; ?></td>
                    <td class="actions">
                        <a href="approve_post.php?id=<?php echo $row['post_id']; ?>" class="btn btn-success btn-sm">Approve</a>
                        <a href="reject_post.php?id=<?php echo $row['post_id']; ?>" class="btn btn-danger btn-sm">Reject</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

        <!-- Pending Books -->
        <h2>Pending Books</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Book ID</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Student ID</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($books)) { ?>
                <tr>
                    <td><?php echo $row['book_id']; ?></td>
                    <td><?php echo $row['title']; ?></td>
                    <td><?php echo $row['author']; ?></td>
                    <td><?php echo $row['student_id']; ?></td>
                    <td><?php echo $row['status']; ?></td>
                    <td class="actions">
                        <a href="approve_book.php?id=<?php echo $row['book_id']; ?>" class="btn btn-success btn-sm">Approve</a>
                        <a href="reject_book.php?id=<?php echo $row['book_id']; ?>" class="btn btn-danger btn-sm">Reject</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

        <!-- Pending Blood Donation Requests -->
        <h2>Pending Blood Donation Requests</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Request ID</th>
                    <th>Student ID</th>
                    <th>Blood Type</th>
                    <th>Request Date</th>
                    <th>Patient Problem</th>
                    <th>Location</th>
                    <th>Phone Number</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($blood_donation)) { ?>
                <tr>
                    <td><?php echo $row['request_id']; ?></td>
                    <td><?php echo $row['student_id']; ?></td>
                    <td><?php echo $row['blood_type']; ?></td>
                    <td><?php echo $row['request_date']; ?></td>
                    <td><?php echo $row['patient_problem']; ?></td>
                    <td><?php echo $row['location']; ?></td>
                    <td><?php echo $row['phone_number']; ?></td>
                    <td><?php echo $row['status']; ?></td>
                    <td class="actions">
                        <a href="approve_blood.php?id=<?php echo $row['request_id']; ?>" class="btn btn-success btn-sm">Approve</a>
                        <a href="reject_blood.php?id=<?php echo $row['request_id']; ?>" class="btn btn-danger btn-sm">Reject</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

        <!-- Pending Items -->
        <h2>Pending Items</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Item ID</th>
                    <th>Title</th>
                    <th>Author/Brand</th>
                    <th>Price</th>
                    <th>Condition</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($items)) { ?>
                <tr>
                    <td><?php echo $row['item_id']; ?></td>
                    <td><?php echo $row['title']; ?></td>
                    <td><?php echo $row['author_or_brand']; ?></td>
                    <td><?php echo $row['price']; ?></td>
                    <td><?php echo $row['conditions']; ?></td>
                    <td><?php echo $row['category']; ?></td>
                    <td><?php echo $row['status']; ?></td>
                    <td class="actions">
                        <a href="approve_item.php?id=<?php echo $row['item_id']; ?>" class="btn btn-success btn-sm">Approve</a>
                        <a href="reject_item.php?id=<?php echo $row['item_id']; ?>" class="btn btn-danger btn-sm">Reject</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
