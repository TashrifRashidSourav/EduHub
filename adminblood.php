<?php
session_start();
include 'db_connect.php';

$student_id = $_SESSION['student_id'];

// Handle the form submission for a new blood donation request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_request'])) {
        // Delete the request if 'delete_request' is set
        $request_id = $_POST['request_id'];
        $delete_sql = "DELETE FROM blood_donation WHERE request_id = '$request_id' AND student_id = '$student_id'";
        
        if (mysqli_query($conn, $delete_sql)) {
            echo "Blood donation request deleted successfully!";
        } else {
            echo "Error deleting request: " . mysqli_error($conn);
        }
    } else {
        // Insert new blood donation request
        $blood_type = $_POST['blood_type'];
        $patient_problem = $_POST['patient_problem'];
        $location = $_POST['location'];
        $phone_number = $_POST['phone_number'];
        
        $insert_sql = "INSERT INTO blood_donation (student_id, blood_type, patient_problem, location, phone_number) 
                VALUES ('$student_id', '$blood_type', '$patient_problem', '$location', '$phone_number')";
        
        if (mysqli_query($conn, $insert_sql)) {
            echo "Blood Donation Request submitted!";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}

// Fetch all accepted blood donation requests to show on every profile
$accepted_sql = "SELECT bd.*, s.name FROM blood_donation bd 
                 JOIN students s ON bd.student_id = s.student_id 
                 WHERE bd.status = 'accepted'";
$accepted_result = mysqli_query($conn, $accepted_sql);

// Fetch the logged-in user's blood donation requests
$user_requests_sql = "SELECT * FROM blood_donation WHERE student_id = '$student_id'";
$user_requests_result = mysqli_query($conn, $user_requests_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduHub | Blood Donation</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
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
    </style>
</head>
<body>

    <!-- Include Navbar -->
    <?php include 'navbaradmin.php'; ?>

    <div class="container content-container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- Blood Donation Request Form -->
                <div class="form-container">
                    <h2 class="section-header">Blood Donation Request</h2>
                    <form action="blood.php" method="POST">
                        <div class="form-group">
                            <label for="blood_type">Blood Type:</label>
                            <select name="blood_type" class="form-control" required>
                                <option value="A+">A+</option>
                                <option value="A-">A-</option>
                                <option value="B+">B+</option>
                                <option value="B-">B-</option>
                                <option value="AB+">AB+</option>
                                <option value="AB-">AB-</option>
                                <option value="O+">O+</option>
                                <option value="O-">O-</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="patient_problem">Patient's Problem:</label>
                            <textarea name="patient_problem" class="form-control" required></textarea>
                        </div>

                        <div class="form-group">
                            <label for="location">Location:</label>
                            <input type="text" name="location" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="phone_number">Phone Number:</label>
                            <input type="text" name="phone_number" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Submit Request</button>
                    </form>
                </div>

                <!-- Accepted Blood Donation Requests -->
                <div class="request-list">
                    <h3 class="section-header">Accepted Blood Donation Requests</h3>
                    <?php if (mysqli_num_rows($accepted_result) > 0): ?>
                        <ul class="list-group">
                            <?php while ($row = mysqli_fetch_assoc($accepted_result)): ?>
                                <li class="list-group-item">
                                    <strong>Blood Type:</strong> <?= htmlspecialchars($row['blood_type']) ?><br>
                                    <strong>Patient's Problem:</strong> <?= htmlspecialchars($row['patient_problem']) ?><br>
                                    <strong>Location:</strong> <?= htmlspecialchars($row['location']) ?><br>
                                    <strong>Phone Number:</strong> <?= htmlspecialchars($row['phone_number']) ?><br>
                                    <strong>Posted By:</strong> <?= htmlspecialchars($row['name']) ?><br>
                                    <strong>Status:</strong> <?= htmlspecialchars($row['status']) ?><br>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                    <?php else: ?>
                        <p>No accepted blood donation requests.</p>
                    <?php endif; ?>
                </div>

                <!-- User's Blood Donation Requests -->
                <div class="request-list">
                    <h3 class="section-header">Your Blood Donation Requests</h3>
                    <?php if (mysqli_num_rows($user_requests_result) > 0): ?>
                        <ul class="list-group">
                            <?php while ($row = mysqli_fetch_assoc($user_requests_result)): ?>
                                <li class="list-group-item">
                                    <strong>Blood Type:</strong> <?= htmlspecialchars($row['blood_type']) ?><br>
                                    <strong>Patient's Problem:</strong> <?= htmlspecialchars($row['patient_problem']) ?><br>
                                    <strong>Location:</strong> <?= htmlspecialchars($row['location']) ?><br>
                                    <strong>Phone Number:</strong> <?= htmlspecialchars($row['phone_number']) ?><br>
                                    <strong>Status:</strong> <?= htmlspecialchars($row['status']) ?><br>

                                    <!-- Delete request option for user's own requests -->
                                    <form action="blood.php" method="POST" class="mt-2">
                                        <input type="hidden" name="request_id" value="<?= $row['request_id'] ?>">
                                        <button type="submit" name="delete_request" class="btn btn-danger">Delete Request</button>
                                    </form>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                    <?php else: ?>
                        <p>You have not submitted any blood donation requests.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
