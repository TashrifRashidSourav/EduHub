<?php
session_start();
include 'db_connect.php';

// Check if student is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$success_message = '';
$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_SESSION['student_id'];
    $full_name = $_POST['full_name'];
    $job_experience = $_POST['job_experience'];
    $available_courses = $_POST['available_courses'];
    $expected_money = $_POST['expected_money'];
    $class_hour = $_POST['class_hour'];

    // Define directories for uploads
    $pdf_target_dir = 'uploads/pdf/';
    $video_target_dir = 'uploads/video/';

    // Check and create directories if necessary
    if (!is_dir($pdf_target_dir)) {
        mkdir($pdf_target_dir, 0777, true);
    }
    if (!is_dir($video_target_dir)) {
        mkdir($video_target_dir, 0777, true);
    }

    $pdf_target = '';
    $video_target = '';

    // Handle PDF file upload
    if (isset($_FILES['pdf_upload']) && $_FILES['pdf_upload']['error'] == UPLOAD_ERR_OK) {
        $pdf_target = $pdf_target_dir . basename($_FILES['pdf_upload']['name']);
        if (!move_uploaded_file($_FILES['pdf_upload']['tmp_name'], $pdf_target)) {
            $error_message .= "Failed to upload PDF file. ";
        }
    }

    // Handle video file upload
    if (isset($_FILES['video_upload']) && $_FILES['video_upload']['error'] == UPLOAD_ERR_OK) {
        $video_target = $video_target_dir . basename($_FILES['video_upload']['name']);
        if (!move_uploaded_file($_FILES['video_upload']['tmp_name'], $video_target)) {
            $error_message .= "Failed to upload video file. ";
        }
    }

    // Insert data into the instructors table
    $sql = "INSERT INTO instructors (student_id, full_name, job_experience, available_courses, expected_money, class_hour, pdf_upload_path, video_upload_path) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssdiss", $student_id, $full_name, $job_experience, $available_courses, $expected_money, $class_hour, $pdf_target, $video_target);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "You have successfully applied to be an instructor!";
        header("Location: be_an_instructor.php");
        exit();
    } else {
        $error_message .= "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}

if (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Be an Instructor</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }

        .container {
            width: 70%;
            margin: 20px auto;
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
            margin-bottom: 20px;
        }

        label {
            margin-top: 10px;
            font-weight: bold;
        }

        select, input[type="text"], input[type="number"], input[type="file"], textarea {
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        button {
            background-color: #007bff;
            color: white;
            padding: 10px;
            margin-top: 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .success-message, .error-message {
            margin-top: 20px;
            text-align: center;
        }

        .success-message {
            color: green;
        }

        .error-message {
            color: red;
        }
    </style>
</head>
<body>

    <?php include 'navbar.php'; ?>

    <div class="container">
        <h1>Be an Instructor</h1>
        <?php if ($success_message): ?>
            <div class="success-message"><?php echo $success_message; ?></div>
        <?php elseif ($error_message): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form action="be_an_instructor.php" method="POST" enctype="multipart/form-data">
            <label for="full_name">Full Name:</label>
            <input type="text" id="full_name" name="full_name" required>

            <label for="job_experience">Job Experience:</label>
            <textarea id="job_experience" name="job_experience" required></textarea>

            <label for="available_courses">Available Courses:</label>
            <select id="available_courses" name="available_courses" required>
                <option value="PowerPoint">PowerPoint</option>
                <option value="Word">Word</option>
                <option value="Excel">Excel</option>
                <option value="Web Frontend">Web Frontend</option>
                <option value="Web Backend">Web Backend</option>
                <option value="Web Fullstack">Web Fullstack</option>
                <option value="Electronics Projects">Electronics Projects</option>
            </select>

            <label for="expected_money">Expected Money:</label>
            <input type="number" step="0.01" id="expected_money" name="expected_money" required>

            <label for="class_hour">Class Hour:</label>
            <input type="number" id="class_hour" name="class_hour" required>

            <label for="pdf_upload">Upload PDF (Optional):</label>
            <input type="file" id="pdf_upload" name="pdf_upload" accept=".pdf">

            <label for="video_upload">Upload Video (Optional):</label>
            <input type="file" id="video_upload" name="video_upload" accept="video/*">

            <button type="submit">Submit</button>
        </form>
    </div>

</body>
</html>
