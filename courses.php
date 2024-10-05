<?php
// Database connection
$servername = "localhost";
$username = "root";  // Your MySQL username
$password = "";      // Your MySQL password
$dbname = "EduHub";  // Database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Start session to access session variables
session_start();

// Assuming you have a session variable for student_id after login
$student_id = $_SESSION['student_id']; // Retrieve the actual logged-in student's ID

// Handle filter form submission
$course_filters = isset($_POST['course_filters']) ? $_POST['course_filters'] : [];
$course_filter_query = '';

// Prepare filtering for available courses
if (!empty($course_filters)) {
    $course_filter_query = " AND available_courses IN ('" . implode("', '", $course_filters) . "')";
}

// Handle purchase request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['instructor_name'])) {
    $instructor_name = $conn->real_escape_string($_POST['instructor_name']);
    $instructor_id = $conn->real_escape_string($_POST['instructor_id']); // Get the instructor ID
    $course_id = 1; // Replace with the actual course ID being purchased
    $amount = 100; // Replace with the actual amount (can be fetched based on course_id)

    // Prepare and bind the purchase statement
    $stmt = $conn->prepare("INSERT INTO course_accounts (student_id, instructor_id, course_id, amount) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiid", $student_id, $instructor_id, $course_id, $amount);

    // Execute the statement and check for errors
    if ($stmt->execute()) {
        echo "<script>alert('You have purchased the course from " . htmlspecialchars($instructor_name) . "!');</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}

// Fetch approved instructor data with optional course filtering, excluding already purchased courses and instructors with the same ID as the student
$sql = "SELECT full_name, job_experience, available_courses, expected_money, class_hour, video_upload_path, created_at, instructor_id 
        FROM instructors 
        WHERE status = 'approved' 
        AND instructor_id <> ? 
        AND instructor_id NOT IN (SELECT DISTINCT instructor_id FROM course_accounts WHERE student_id = ?) 
        $course_filter_query";

// Prepare and execute the statement for fetching instructors
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $student_id, $student_id); // Bind the student ID
$stmt->execute();
$result = $stmt->get_result();

// Check for query execution errors
if ($result === false) {
    die("Query failed: " . $conn->error);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructor Information</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 20px auto;
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: center;
            font-size: 16px;
        }

        th {
            background-color: #f2f2f2;
            color: #333;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .no-data {
            text-align: center;
            font-size: 18px;
            margin-top: 20px;
            color: #888;
        }
    </style>
</head>
<body>

    <!-- Navbar inclusion -->
    <?php include 'navbar.php'; ?>

    <div class="container">
        <h1>Instructor Information</h1>

        <!-- Course Filter Form -->
        <form method="POST">
            <h5>Filter by Course:</h5>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="course_filters[]" value="PowerPoint" id="powerpoint">
                <label class="form-check-label" for="powerpoint">PowerPoint</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="course_filters[]" value="Word" id="word">
                <label class="form-check-label" for="word">Word</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="course_filters[]" value="Excel" id="excel">
                <label class="form-check-label" for="excel">Excel</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="course_filters[]" value="Web Frontend" id="web_frontend">
                <label class="form-check-label" for="web_frontend">Web Frontend</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="course_filters[]" value="Web Backend" id="web_backend">
                <label class="form-check-label" for="web_backend">Web Backend</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="course_filters[]" value="Web Fullstack" id="web_fullstack">
                <label class="form-check-label" for="web_fullstack">Web Fullstack</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="course_filters[]" value="Electronics Projects" id="electronics_projects">
                <label class="form-check-label" for="electronics_projects">Electronics Projects</label>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Filter</button>
        </form>

        <?php if ($result->num_rows > 0): ?>
            <table class="table table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th>Full Name</th>
                        <th>Job Experience (Year)</th>
                        <th>Available Courses</th>
                        <th>Expected Payment (BDT)</th>
                        <th>Class Hours</th>
                        <th>Sample Class</th>
                        <th>Course Added Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['job_experience']); ?></td>
                            <td><?php echo htmlspecialchars($row['available_courses']); ?></td>
                            <td><?php echo number_format($row['expected_money'], 2); ?></td>
                            <td><?php echo htmlspecialchars($row['class_hour']); ?> hours</td>
                            <td><a href="<?php echo htmlspecialchars($row['video_upload_path']); ?>" target="_blank">View Video</a></td>
                            <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                            <td>
                                <!-- Buy Now Button that triggers the modal -->
                                <button class="btn btn-primary" data-toggle="modal" data-target="#buyNowModal" data-id="<?php echo $row['full_name']; ?>" data-instructor-id="<?php echo $row['instructor_id']; ?>">Buy Now</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-data">No approved instructor data found.</p>
        <?php endif; ?>
    </div>

    <!-- Buy Now Modal -->
    <div class="modal fade" id="buyNowModal" tabindex="-1" role="dialog" aria-labelledby="buyNowModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="buyNowModalLabel">Confirm Purchase</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to purchase the course from <strong id="instructorName"></strong>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <form method="POST">
                        <input type="hidden" name="instructor_id" id="instructorId">
                        <input type="hidden" name="instructor_name" id="instructorNameHidden">
                        <button type="submit" class="btn btn-primary">Confirm Purchase</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        $('#buyNowModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var instructorName = button.data('id'); // Extract info from data-* attributes
            var instructorId = button.data('instructor-id');

            // Update the modal's content
            var modal = $(this);
            modal.find('#instructorName').text(instructorName);
            modal.find('#instructorId').val(instructorId);
            modal.find('#instructorNameHidden').val(instructorName);
        });
    </script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
