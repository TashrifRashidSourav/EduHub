<?php
session_start(); 

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php"); 
    exit();
}

// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "EduHub");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to get pending records based on table name
function getPendingRecords($conn, $table) {
    $query = "SELECT * FROM $table WHERE status = 'pending'";
    return mysqli_query($conn, $query);
}

// Get all pending items for different sections
$posts = getPendingRecords($conn, 'posts');
$books = getPendingRecords($conn, 'books');
$blood_donation = getPendingRecords($conn, 'blood_donation');
$items = getPendingRecords($conn, 'items');
$instructors = getPendingRecords($conn, 'instructors');

// Check for success message in URL parameter
$message = '';
if (isset($_GET['status']) && $_GET['status'] == 'success') {
    $message = 'Action completed successfully.';
}

// Handle checkbox states via session
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_SESSION['pending_posts'] = isset($_POST['pending-posts']);
    $_SESSION['pending_books'] = isset($_POST['pending-books']);
    $_SESSION['pending_blood'] = isset($_POST['pending-blood']);
    $_SESSION['pending_items'] = isset($_POST['pending-items']);
    $_SESSION['pending_instructors'] = isset($_POST['pending-instructors']);
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
        .pending-section {
            display: none;
        }
        .section-heading {
            margin-top: 20px;
            cursor: pointer;
            color: #007BFF;
            text-decoration: underline;
        }
    </style>
    <script>
        function toggleSection(section) {
            const element = document.getElementById(section);
            if (element.style.display === "block") {
                element.style.display = "none";
            } else {
                element.style.display = "block";
            }
        }

        // Auto-toggle sections based on checkbox states
        window.onload = function() {
            <?php if (isset($_SESSION['pending_posts']) && $_SESSION['pending_posts']) echo 'toggleSection("pending-posts");'; ?>
            <?php if (isset($_SESSION['pending_books']) && $_SESSION['pending_books']) echo 'toggleSection("pending-books");'; ?>
            <?php if (isset($_SESSION['pending_blood']) && $_SESSION['pending_blood']) echo 'toggleSection("pending-blood");'; ?>
            <?php if (isset($_SESSION['pending_items']) && $_SESSION['pending_items']) echo 'toggleSection("pending-items");'; ?>
            <?php if (isset($_SESSION['pending_instructors']) && $_SESSION['pending_instructors']) echo 'toggleSection("pending-instructors");'; ?>
        };
    </script>
</head>
<body>

    <!-- Include Navbar -->
    <?php include 'navbaradmin.php'; ?>

    <div class="container">
        <h1>Admin Index</h1>
        
        <?php if ($message): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="post">
            <h2>Select Pending Sections to Show</h2>
            <div>
                <label><input type="checkbox" name="pending-posts" <?php echo (isset($_SESSION['pending_posts']) && $_SESSION['pending_posts']) ? 'checked' : ''; ?> onclick="toggleSection('pending-posts')"> Pending Posts</label>
                <label><input type="checkbox" name="pending-books" <?php echo (isset($_SESSION['pending_books']) && $_SESSION['pending_books']) ? 'checked' : ''; ?> onclick="toggleSection('pending-books')"> Pending Books</label>
                <label><input type="checkbox" name="pending-blood" <?php echo (isset($_SESSION['pending_blood']) && $_SESSION['pending_blood']) ? 'checked' : ''; ?> onclick="toggleSection('pending-blood')"> Pending Blood Donations</label>
                <label><input type="checkbox" name="pending-items" <?php echo (isset($_SESSION['pending_items']) && $_SESSION['pending_items']) ? 'checked' : ''; ?> onclick="toggleSection('pending-items')"> Pending Items</label>
                <label><input type="checkbox" name="pending-instructors" <?php echo (isset($_SESSION['pending_instructors']) && $_SESSION['pending_instructors']) ? 'checked' : ''; ?> onclick="toggleSection('pending-instructors')"> Pending Instructors</label>
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
        </form>

        <!-- Pending Posts -->
        <div id="pending-posts" class="pending-section">
            <h2 class="section-heading" onclick="toggleSection('pending-posts')">Pending Posts</h2>
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
        </div>

        <!-- Pending Books -->
        <div id="pending-books" class="pending-section">
            <h2 class="section-heading" onclick="toggleSection('pending-books')">Pending Books</h2>
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
        </div>

        <!-- Pending Blood Donation Requests -->
        <div id="pending-blood" class="pending-section">
            <h2 class="section-heading" onclick="toggleSection('pending-blood')">Pending Blood Donation Requests</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Request ID</th>
                        <th>Student ID</th>
                        <th>Blood Type</th>
                        <th>Request Date</th>
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
                        <td class="actions">
                            <a href="approve_blood_request.php?id=<?php echo $row['request_id']; ?>" class="btn btn-success btn-sm">Approve</a>
                            <a href="reject_blood_request.php?id=<?php echo $row['request_id']; ?>" class="btn btn-danger btn-sm">Reject</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <!-- Pending Items -->
        <div id="pending-items" class="pending-section">
            <h2 class="section-heading" onclick="toggleSection('pending-items')">Pending Items</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Item ID</th>
                        <th>Item Name</th>
                        <th>Student ID</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($items)) { ?>
                    <tr>
                        <td><?php echo $row['item_id']; ?></td>
                        <td><?php echo $row['item_name']; ?></td>
                        <td><?php echo $row['student_id']; ?></td>
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

        <!-- Pending Instructors -->
        <div id="pending-instructors" class="pending-section">
            <h2 class="section-heading" onclick="toggleSection('pending-instructors')">Pending Instructors</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Instructor ID</th>
                        <th>Full Name</th>
                        <th>Job Experience</th>
                        <th>Available Courses</th>
                        <th>Expected Money</th>
                        <th>Class Hour</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($instructors)) { ?>
                    <tr>
                        <td><?php echo $row['instructor_id']; ?></td>
                        <td><?php echo $row['full_name']; ?></td>
                        <td><?php echo $row['job_experience']; ?></td>
                        <td><?php echo $row['available_courses']; ?></td>
                        <td><?php echo $row['expected_money']; ?></td>
                        <td><?php echo $row['class_hour']; ?></td>
                        <td><?php echo $row['status']; ?></td>
                        <td class="actions">
                            <a href="approve_instructor.php?id=<?php echo $row['instructor_id']; ?>" class="btn btn-success btn-sm">Approve</a>
                            <a href="reject_instructor.php?id=<?php echo $row['instructor_id']; ?>" class="btn btn-danger btn-sm">Reject</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
mysqli_close($conn);
?>
