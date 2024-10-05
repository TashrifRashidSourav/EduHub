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

    // Check if any skills are selected; default to "no skills" if none selected
    $skills = isset($_POST['skills']) ? $_POST['skills'] : ['no skills'];

    $career_guidance = isset($_POST['career_guidance']) ? mysqli_real_escape_string($conn, $_POST['career_guidance']) : '';
    $personalized_suggestions = isset($_POST['personalized_suggestions']) ? mysqli_real_escape_string($conn, $_POST['personalized_suggestions']) : '';

    // Update student info
    $update_sql = "UPDATE students SET 
                    name = '$name', 
                    email = '$email', 
                    password = '$hashed_password', 
                    career_guidance = '$career_guidance', 
                    personalized_suggestions = '$personalized_suggestions' 
                  WHERE student_id = '$student_id'";

    if (mysqli_query($conn, $update_sql)) {
        // Clear old skills
        $delete_sql = "DELETE FROM student_skills WHERE student_id = '$student_id'";
        mysqli_query($conn, $delete_sql);

        // Process skills
        if ($skills[0] === 'no skills') {
            // Default to "no skills"
            $skill_id_sql = "SELECT skill_id FROM skills WHERE skill_name = 'no skills'";
            $skill_id_result = mysqli_query($conn, $skill_id_sql);

            if ($skill_id_row = mysqli_fetch_assoc($skill_id_result)) {
                $skill_id = $skill_id_row['skill_id'];
                $insert_sql = "INSERT INTO student_skills (student_id, skill_id, skill_level) VALUES ('$student_id', '$skill_id', 1)";
                mysqli_query($conn, $insert_sql);
            }
        } else {
            // Insert selected skills
            foreach ($skills as $skill_name) {
                $skill_name = mysqli_real_escape_string($conn, trim($skill_name));
                $skill_id_sql = "SELECT skill_id FROM skills WHERE skill_name = '$skill_name'";
                $skill_id_result = mysqli_query($conn, $skill_id_sql);

                if ($skill_id_row = mysqli_fetch_assoc($skill_id_result)) {
                    $skill_id = $skill_id_row['skill_id'];
                    $insert_sql = "INSERT INTO student_skills (student_id, skill_id, skill_level) VALUES ('$student_id', '$skill_id', 1)";
                    mysqli_query($conn, $insert_sql);
                }
            }
        }
        echo "Profile updated successfully!";
        $_SESSION['name'] = $name; // Update session name
    } else {
        echo "Error updating profile: " . mysqli_error($conn);
    }
}

// Fetch skills for checkboxes
$skills_sql = "SELECT * FROM skills";
$skills_result = mysqli_query($conn, $skills_sql);

// Fetch current user's skills
$user_skills_sql = "SELECT skill_id FROM student_skills WHERE student_id = '$student_id'";
$user_skills_result = mysqli_query($conn, $user_skills_sql);
$user_skills = [];

while ($row = mysqli_fetch_assoc($user_skills_result)) {
    $user_skills[] = $row['skill_id'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduHub | Edit Profile</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f4f9;
        }
        .edit-profile-container {
            margin-top: 50px;
        }
        .edit-profile-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .edit-profile-card {
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        .btn-update {
            display: block;
            width: 100%;
        }
    </style>
</head>
<body>

    <!-- Include Navbar -->
    <?php include 'navbaradmin.php'; ?>

    <!-- Edit Profile Section -->
    <div class="container edit-profile-container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="edit-profile-header">
                    <h2>Edit Your Profile</h2>
                </div>
                <div class="edit-profile-card">
                    <form action="profile_edit.php" method="POST">
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
                            <label for="skills">Skills:</label><br>
                            <?php while ($row = mysqli_fetch_assoc($skills_result)): ?>
                                <div class="form-check">
                                    <input type="checkbox" name="skills[]" value="<?= htmlspecialchars($row['skill_name'], ENT_QUOTES) ?>"
                                        class="form-check-input" <?= in_array($row['skill_id'], $user_skills) ? 'checked' : '' ?>>
                                    <label class="form-check-label"><?= htmlspecialchars($row['skill_name'], ENT_QUOTES) ?></label>
                                </div>
                            <?php endwhile; ?>
                        </div>

                        <div class="form-group">
                            <label for="career_guidance">Career Guidance:</label>
                            <textarea name="career_guidance" class="form-control"><?= htmlspecialchars($user['career_guidance'] ?? '', ENT_QUOTES) ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="personalized_suggestions">Personalized Suggestions:</label>
                            <textarea name="personalized_suggestions" class="form-control"><?= htmlspecialchars($user['personalized_suggestions'] ?? '', ENT_QUOTES) ?></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary btn-update">Update Profile</button>
                    </form>
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
