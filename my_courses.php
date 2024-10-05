<?php
// Include the database connection
include 'db_connect.php';

// Start session to get the current user's ID
session_start();
$student_id = $_SESSION['student_id']; // Assuming student ID is stored in session after login

// Check if student ID is set
if (!isset($student_id)) {
    die("You need to log in first.");
}

// SQL query to fetch purchased courses along with instructor details
$sql = "
    SELECT 
        c.course_name,
        i.full_name AS instructor_name,
        i.video_upload_path
    FROM 
        course_accounts ca
    JOIN 
        courses c ON ca.course_id = c.course_id
    JOIN 
        instructors i ON ca.instructor_id = i.instructor_id
    WHERE 
        ca.student_id = ?
";

// Prepare and execute the statement
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

// Start HTML output
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Courses</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>


body {
            background-color: #f4f4f9;
        }
        .content-container {
            margin-top: 50px;
        }
        .section-header {
            margin-bottom: 30px;
            text-align: center;
        }
        .form-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .request-list {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }
        .btn-danger, .btn-primary {
            width: 100%;
        }
   
        h2 {
            color: #333;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    
<?php include 'navbar.php'; ?>
    <div class="container">
        <h2>Your Purchased Courses</h2>
        <?php
        // Check if there are any results
        if ($result->num_rows > 0) {
            echo "<table class='table table-bordered'>
                    <tr>
                        <th>Course Name</th>
                        <th>Instructor Name</th>
                        <th>Video</th>
                    </tr>";
            // Fetch and display the courses
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . htmlspecialchars($row['course_name']) . "</td>
                        <td>" . htmlspecialchars($row['instructor_name']) . "</td>
                        <td><a href='" . htmlspecialchars($row['video_upload_path']) . "' target='_blank' class='btn btn-primary'>Watch Video</a></td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No courses purchased yet.</p>";
        }

        // Close the statement and connection
        $stmt->close();
        $conn->close();
        ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
