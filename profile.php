<?php
session_start();
require 'db_connect.php'; // Include your database connection file

// Assuming the user is logged in and we have the student_id in the session
$student_id = $_SESSION['student_id'];

// Fetch user information from students table
$sql_user = "SELECT name, email, profile_picture FROM students WHERE student_id = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $student_id);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
$user = $result_user->fetch_assoc();

// Fetch user information from UserInformation table
$sql_info = "SELECT school, college, university, occupation, job_field FROM UserInformation WHERE student_id = ?";
$stmt_info = $conn->prepare($sql_info);
$stmt_info->bind_param("i", $student_id);
$stmt_info->execute();
$result_info = $stmt_info->get_result();
$info = $result_info->fetch_assoc();

// Check if user information exists, if not initialize it
if (!$info) {
    $info = [
        'school' => '',
        'college' => '',
        'university' => '',
        'occupation' => '',
        'job_field' => ''
    ];
}

// Handle form submission for editing information
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get posted data
    $name = !empty($_POST['name']) ? $_POST['name'] : $user['name'];
    $email = !empty($_POST['email']) ? $_POST['email'] : $user['email'];
    $school = $_POST['school'] ?? '';
    $college = $_POST['college'] ?? '';
    $university = $_POST['university'] ?? '';
    $occupation = $_POST['occupation'] ?? '';
    $job_field = $_POST['job_field'] ?? '';

    // Update students table (name and email) only if the user entered a value, else retain old data
    $sql_update_user = "UPDATE students SET name = ?, email = ? WHERE student_id = ?";
    $stmt_update_user = $conn->prepare($sql_update_user);
    $stmt_update_user->bind_param("ssi", $name, $email, $student_id);

    // Update or insert UserInformation (optional fields)
    $sql_update_info = "INSERT INTO UserInformation (student_id, school, college, university, occupation, job_field)
                        VALUES (?, ?, ?, ?, ?, ?)
                        ON DUPLICATE KEY UPDATE 
                        school = VALUES(school), 
                        college = VALUES(college), 
                        university = VALUES(university), 
                        occupation = VALUES(occupation), 
                        job_field = VALUES(job_field)";
    
    $stmt_update_info = $conn->prepare($sql_update_info);
    $stmt_update_info->bind_param(
        "isssss", 
        $student_id, 
        $school, 
        $college, 
        $university, 
        $occupation, 
        $job_field
    );

    // Execute updates
    if ($stmt_update_user->execute() && $stmt_update_info->execute()) {
        echo "<script>alert('Information updated successfully!');</script>";
        // Refresh the page to fetch updated info
        header("Refresh:0");
    } else {
        echo "<script>alert('Error updating information: " . $stmt_update_user->error . "');</script>";
    }

    $stmt_update_user->close();
    $stmt_update_info->close();
}

$stmt_user->close();
$stmt_info->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
       
            background-color: #f8f9fa;
        }
        .profile-header {
            margin-bottom: 30px;
        }
        .profile-img {
            width: 150px;
            height: 150px;
            border-radius: 75px;
        }
        .form-group {
            margin-top: 10px;
        }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container">
    <div class="profile-header text-center">
        <h2><?php echo htmlspecialchars($user['name']); ?>'s Profile</h2>
        <img src="<?php echo $user['profile_picture'] ?: 'default-profile.png'; ?>" alt="Profile Picture" class="profile-img">
        <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
    </div>

    <form method="POST">
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>">
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>">
        </div>
        <div class="form-group">
            <label for="school">School:</label>
            <input type="text" class="form-control" id="school" name="school" value="<?php echo htmlspecialchars($info['school']); ?>">
        </div>
        <div class="form-group">
            <label for="college">College:</label>
            <input type="text" class="form-control" id="college" name="college" value="<?php echo htmlspecialchars($info['college']); ?>">
        </div>
        <div class="form-group">
            <label for="university">University:</label>
            <input type="text" class="form-control" id="university" name="university" value="<?php echo htmlspecialchars($info['university']); ?>">
        </div>
        <div class="form-group">
            <label for="occupation">Occupation:</label>
            <input type="text" class="form-control" id="occupation" name="occupation" value="<?php echo htmlspecialchars($info['occupation']); ?>">
        </div>
        <div class="form-group">
            <label for="job_field">Job Field:</label>
            <input type="text" class="form-control" id="job_field" name="job_field" value="<?php echo htmlspecialchars($info['job_field']); ?>">
        </div>
        <button type="submit" class="btn btn-primary">Update Information</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
