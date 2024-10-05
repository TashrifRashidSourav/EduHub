<?php
session_start();
include 'db_connect.php';

// Ensure user is logged in
if (!isset($_SESSION['student_id'])) {
    echo "Error: User not logged in.";
    exit();
}

$logged_in_student_id = $_SESSION['student_id'];

// Fetch courses the logged-in student has already purchased
$purchased_sql = "
SELECT i.full_name, c.course_name, p.purchase_date, i.video_upload_path
FROM purchased_courses p
JOIN courses c ON p.course_id = c.course_id
JOIN instructors i ON p.instructor_id = i.instructor_id
WHERE p.buyer_id = $logged_in_student_id
";

$purchased_result = $conn->query($purchased_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Purchased Courses</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<?php include 'navbaradmin.php'; ?>
<div class="container mt-5">
    <h2>My Purchased Courses</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Instructor</th>
                <th>Course Name</th>
                <th>Purchase Date</th>
                <th>Course Video</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($purchased_result && $purchased_result->num_rows > 0): ?>
                <?php while ($row = $purchased_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['course_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['purchase_date']); ?></td>
                        <td><a href="<?php echo htmlspecialchars($row['video_upload_path']); ?>" target="_blank">Watch Video</a></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">You have not purchased any courses yet.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>
