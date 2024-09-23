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

// Fetch all instructor data
$sql = "SELECT full_name, job_experience, available_courses, expected_money, class_hour, video_upload_path, created_at FROM instructors";
$result = $conn->query($sql);
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

        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
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
                                <button class="btn btn-primary" data-toggle="modal" data-target="#buyNowModal" data-id="<?php echo $row['full_name']; ?>">Buy Now</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-data">No instructor data found.</p>
        <?php endif; ?>
    </div>

    <!-- Buy Now Modal -->
    <div class="modal fade" id="buyNowModal" tabindex="-1" role="dialog" aria-labelledby="buyNowModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="buyNowModalLabel">Buy Course</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to purchase this course from <span id="instructorName"></span>?</p>
                    <form action="buynow.php" method="POST">
                        <input type="hidden" name="instructor_name" id="instructor_name" value="">
                        <button type="submit" class="btn btn-success">Confirm Purchase</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // Capture the button click event to set the instructor's name in the modal
        $('#buyNowModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var instructorName = button.data('id'); // Extract info from data-* attributes
            var modal = $(this);
            modal.find('#instructorName').text(instructorName); // Update the modal content
            modal.find('#instructor_name').val(instructorName); // Set the hidden input field value
        });
    </script>
</body>
</html>

<?php
$conn->close();
?>