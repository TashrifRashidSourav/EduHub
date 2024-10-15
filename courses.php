<?php
session_start();
include 'db_connect.php';

// Ensure user is logged in
if (!isset($_SESSION['student_id'])) {
    echo "Error: User not logged in.";
    exit();
}

$logged_in_student_id = $_SESSION['student_id'];

// Fetch all available courses for purchase (excluding purchased and own courses)
$sql = "
SELECT i.instructor_id, i.full_name, i.job_experience, i.available_courses, i.expected_money, i.class_hour, i.pdf_upload_path, i.video_upload_path, c.course_id, c.course_name
FROM instructors i
JOIN courses c ON i.instructor_id = c.instructor_id
WHERE i.student_id != $logged_in_student_id 
AND i.status = 'approved'
AND c.course_id NOT IN (SELECT course_id FROM purchased_courses WHERE buyer_id = $logged_in_student_id)
";

$result = $conn->query($sql);

// Process buying a course
if (isset($_POST['buy_course'])) {
    $course_id = $_POST['course_id'];
    $instructor_id = $_POST['instructor_id'];

    // Insert into purchased_courses table directly
    $buy_sql = "INSERT INTO purchased_courses (course_id, instructor_id, buyer_id) VALUES ($course_id, $instructor_id, $logged_in_student_id)";
    
    if ($conn->query($buy_sql) === TRUE) {
        echo "Course successfully purchased!";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buy Courses</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container mt-5">
    <h2>Available Courses for Purchase</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Instructor</th>
                <th>Course Name</th>
                <th>Experience</th>
                <th>Class Hours</th>
                <th>Expected Money</th>
                <th>Buy Now</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['course_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['job_experience']); ?></td>
                        <td><?php echo htmlspecialchars($row['class_hour']); ?></td>
                        <td><?php echo htmlspecialchars($row['expected_money']); ?></td>
                        <td>
                            <form method="POST" action="">
                                <input type="hidden" name="course_id" value="<?php echo $row['course_id']; ?>">
                                <input type="hidden" name="instructor_id" value="<?php echo $row['instructor_id']; ?>">
                                <button type="submit" name="buy_course" class="btn btn-primary">Buy Now</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">No courses available for purchase.</td>
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
