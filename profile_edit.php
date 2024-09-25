<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

// Fetch current user information
$sql = "SELECT * FROM students WHERE student_id = '$student_id'";
$result = mysqli_query($conn, $sql);

if ($result) {
    $user = mysqli_fetch_assoc($result);
} else {
    echo "Error fetching user data: " . mysqli_error($conn);
    exit();
}

// If form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = !empty($_POST['password']) ? mysqli_real_escape_string($conn, $_POST['password']) : null;
    $hashed_password = $password ? password_hash($password, PASSWORD_DEFAULT) : $user['password'];

    // Handle profile picture upload
    $profile_picture = $user['profile_picture']; // Default to the current picture
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $target_dir = "uploads/profile_pictures/";
        $target_file = $target_dir . basename($_FILES['profile_picture']['name']);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_file_types = ['jpg', 'jpeg', 'png', 'gif'];

        // Ensure the uploads folder exists
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);  // Create the directory if it doesn't exist
        }

        // Check if file is a valid image type
        if (in_array($imageFileType, $allowed_file_types)) {
            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file)) {
                $profile_picture = $target_file; // Update with new profile picture path
            } else {
                echo "Error uploading the profile picture.";
            }
        } else {
            echo "Invalid file type. Only JPG, JPEG, PNG & GIF files are allowed.";
        }
    }

    // Update student info
    $update_sql = "UPDATE students SET 
                    name = '$name', 
                    email = '$email', 
                    password = '$hashed_password', 
                    profile_picture = '$profile_picture'
                  WHERE student_id = '$student_id'";

    if (mysqli_query($conn, $update_sql)) {
        echo "Profile updated successfully!";
        $_SESSION['name'] = $name; // Update session name
    } else {
        echo "Error updating profile: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <!-- Include Navbar -->
    <?php include 'navbar.php'; ?>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2>Edit Profile</h2>
                <form action="profile_edit.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name'], ENT_QUOTES) ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email'], ENT_QUOTES) ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Password (Leave blank to keep current):</label>
                        <input type="password" name="password" class="form-control" placeholder="New password">
                    </div>

                    <div class="form-group">
                        <label for="profile_picture">Profile Picture:</label><br>
                        <input type="file" name="profile_picture" class="form-control-file">
                        <?php if (!empty($user['profile_picture'])): ?>
                            <p>Current Picture:</p>
                            <img src="<?= htmlspecialchars($user['profile_picture'], ENT_QUOTES) ?>" alt="Profile Picture" width="100">
                        <?php endif; ?>
                    </div>

                    <button type="submit" class="btn btn-primary">Update Profile</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
