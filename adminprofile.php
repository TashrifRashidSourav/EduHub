<?php
session_start();
include 'db_connect.php'; // Include your database connection

// Check if user is logged in, otherwise redirect to login
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

// Fetch user profile information
$sql = "SELECT * FROM students WHERE student_id = '$student_id'";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduHub | Profile</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f4f4f9;
        }
        .profile-container {
            margin-top: 50px;
        }
        .profile-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .profile-card {
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            border-radius: 10px;
        }
        .profile-info {
            font-size: 1.1rem;
            color: #333;
        }
        .profile-info p {
            margin-bottom: 10px;
        }
        .btn-edit {
            display: block;
            margin: 20px auto 0;
        }
    </style>
</head>
<body>

    <!-- Include Navbar -->
    <?php include 'navbaradmin.php'; ?>

    <!-- Profile Section -->
    <div class="container profile-container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="profile-header">
                    <h2>Your Profile</h2>
                </div>
                <div class="profile-card">
                    <div class="profile-info">
                        <p><strong>Name:</strong> <?= $user['name'] ?></p>
                        <p><strong>Email:</strong> <?= $user['email'] ?></p>
                        <p><strong>Profile Rank:</strong> <?= $user['profile_rank'] ?></p>
                        <p><strong>Career Guidance:</strong> <?= $user['career_guidance'] ?></p>
                        <p><strong>Personalized Suggestions:</strong> <?= $user['personalized_suggestions'] ?></p>
                    </div>
                    <a href="profile_edit.php" class="btn btn-primary btn-edit">Edit Profile</a>
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