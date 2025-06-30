<?php
session_start();
include 'db_connect.php';


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

    $pdf_target_dir = 'uploads/pdf/';
    $video_target_dir = 'uploads/video/';

    if (!is_dir($pdf_target_dir)) {
        mkdir($pdf_target_dir, 0777, true);
    }
    if (!is_dir($video_target_dir)) {
        mkdir($video_target_dir, 0777, true);
    }

    $pdf_target = '';
    $video_target = '';

 
    if (isset($_FILES['pdf_upload']) && $_FILES['pdf_upload']['error'] == UPLOAD_ERR_OK) {
        $pdf_target = $pdf_target_dir . basename($_FILES['pdf_upload']['name']);
        if (!move_uploaded_file($_FILES['pdf_upload']['tmp_name'], $pdf_target)) {
            $error_message .= "Failed to upload PDF file. ";
        }
    }

  
    if (isset($_FILES['video_upload']) && $_FILES['video_upload']['error'] == UPLOAD_ERR_OK) {
        $video_target = $video_target_dir . basename($_FILES['video_upload']['name']);
        if (!move_uploaded_file($_FILES['video_upload']['tmp_name'], $video_target)) {
            $error_message .= "Failed to upload video file. ";
        }
    }

  
    $sql = "INSERT INTO instructors (student_id, full_name, job_experience, available_courses, expected_money, class_hour, pdf_upload_path, video_upload_path) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssdiss", $student_id, $full_name, $job_experience, $available_courses, $expected_money, $class_hour, $pdf_target, $video_target);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "You have successfully applied to be an instructor!";
        header("Location:be_an_instructor.php");
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 0;
            margin: 0;
        }

        .form-container {
            max-width: 800px;
            margin: 2rem auto;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            position: relative;
        }

        .form-header {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 2.5rem 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .form-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: rotate 20s linear infinite;
        }

        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .form-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin: 0;
            position: relative;
            z-index: 2;
        }

        .form-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-top: 0.5rem;
            position: relative;
            z-index: 2;
        }

        .form-body {
            padding: 2.5rem;
        }

        .form-group {
            margin-bottom: 2rem;
            position: relative;
        }

        .form-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 0.8rem;
            font-size: 1rem;
        }

        .form-label i {
            color: #667eea;
            width: 20px;
        }

        .form-input {
            width: 100%;
            padding: 1rem 1.5rem;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8fafc;
            font-family: inherit;
        }

        .form-input:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            transform: translateY(-1px);
        }

        .form-textarea {
            min-height: 120px;
            resize: vertical;
        }

        .form-select {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 1rem center;
            background-repeat: no-repeat;
            background-size: 1rem;
            appearance: none;
        }

        .file-upload-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
            width: 100%;
        }

        .file-upload-input {
            position: absolute;
            left: -9999px;
        }

        .file-upload-label {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 1.5rem;
            background: linear-gradient(135deg, #f8fafc, #e2e8f0);
            border: 2px dashed #cbd5e0;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
            color: #4a5568;
        }

        .file-upload-label:hover {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border-color: #667eea;
            transform: translateY(-2px);
        }

        .file-upload-icon {
            font-size: 1.5rem;
        }

        .submit-btn {
            width: 100%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 1.2rem 2rem;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 1rem;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        .alert {
            padding: 1.2rem 1.5rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            border: none;
            font-weight: 500;
        }

        .alert-success {
            background: linear-gradient(135deg, rgba(72, 187, 120, 0.1), rgba(56, 178, 172, 0.1));
            color: #2f855a;
            border-left: 4px solid #48bb78;
        }

        .alert-danger {
            background: linear-gradient(135deg, rgba(245, 101, 101, 0.1), rgba(229, 62, 62, 0.1));
            color: #c53030;
            border-left: 4px solid #f56565;
        }

        .course-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .course-option {
            padding: 1rem;
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .course-option:hover {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
            border-color: #667eea;
        }

        .floating-elements {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            overflow: hidden;
        }

        .floating-shape {
            position: absolute;
            opacity: 0.1;
            animation: float 6s ease-in-out infinite;
        }

        .floating-shape:nth-child(1) {
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }

        .floating-shape:nth-child(2) {
            top: 60%;
            right: 10%;
            animation-delay: 2s;
        }

        .floating-shape:nth-child(3) {
            bottom: 30%;
            left: 20%;
            animation-delay: 4s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        @media (max-width: 768px) {
            .form-container {
                margin: 1rem;
                border-radius: 15px;
            }

            .form-body {
                padding: 1.5rem;
            }

            .form-header {
                padding: 2rem 1.5rem;
            }

            .form-title {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>

    <?php include 'navbar.php'; ?>

    <div class="form-container">
        <div class="floating-elements">
            <div class="floating-shape">
                <i class="fas fa-graduation-cap" style="font-size: 3rem; color: #667eea;"></i>
            </div>
            <div class="floating-shape">
                <i class="fas fa-chalkboard-teacher" style="font-size: 2.5rem; color: #764ba2;"></i>
            </div>
            <div class="floating-shape">
                <i class="fas fa-book" style="font-size: 2rem; color: #667eea;"></i>
            </div>
        </div>

        <div class="form-header">
            <h1 class="form-title">
                <i class="fas fa-chalkboard-teacher"></i> Be an Instructor
            </h1>
            <p class="form-subtitle">Share your knowledge and inspire the next generation</p>
        </div>

        <div class="form-body">
            <?php if ($success_message): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <?php echo $success_message; ?>
                </div>
            <?php elseif ($error_message): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i> <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <form action="be_an_instructor.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="full_name" class="form-label">
                        <i class="fas fa-user"></i> Full Name
                    </label>
                    <input type="text" id="full_name" name="full_name" class="form-input" placeholder="Enter your full name" required>
                </div>

                <div class="form-group">
                    <label for="job_experience" class="form-label">
                        <i class="fas fa-briefcase"></i> Job Experience
                    </label>
                    <textarea id="job_experience" name="job_experience" class="form-input form-textarea" placeholder="Describe your professional experience and qualifications..." required></textarea>
                </div>

                <div class="form-group">
                    <label for="available_courses" class="form-label">
                        <i class="fas fa-laptop-code"></i> Available Courses
                    </label>
                    <select id="available_courses" name="available_courses" class="form-input form-select" required>
                        <option value="">Select a course to teach</option>
                        <option value="PowerPoint">📊 PowerPoint</option>
                        <option value="Word">📝 Microsoft Word</option>
                        <option value="Excel">📈 Microsoft Excel</option>
                        <option value="Web Frontend">🎨 Web Frontend Development</option>
                        <option value="Web Backend">⚙️ Web Backend Development</option>
                        <option value="Web Fullstack">🚀 Web Fullstack Development</option>
                        <option value="Electronics Projects">🔌 Electronics Projects</option>
                    </select>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="expected_money" class="form-label">
                                <i class="fas fa-dollar-sign"></i> Expected Payment
                            </label>
                            <input type="number" step="0.01" id="expected_money" name="expected_money" class="form-input" placeholder="0.00" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="class_hour" class="form-label">
                                <i class="fas fa-clock"></i> Class Hours
                            </label>
                            <input type="number" id="class_hour" name="class_hour" class="form-input" placeholder="Hours per week" required>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-file-pdf"></i> Upload Your CV
                    </label>
                    <div class="file-upload-wrapper">
                        <input type="file" id="pdf_upload" name="pdf_upload" accept=".pdf" class="file-upload-input">
                        <label for="pdf_upload" class="file-upload-label">
                            <i class="fas fa-cloud-upload-alt file-upload-icon"></i>
                            <span>Choose PDF file or drag and drop</span>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-video"></i> Upload Demo Class Video
                    </label>
                    <div class="file-upload-wrapper">
                        <input type="file" id="video_upload" name="video_upload" accept="video/*" class="file-upload-input">
                        <label for="video_upload" class="file-upload-label">
                            <i class="fas fa-film file-upload-icon"></i>
                            <span>Choose video file or drag and drop</span>
                        </label>
                    </div>
                </div>

                <button type="submit" class="submit-btn">
                    <i class="fas fa-paper-plane"></i> Submit Application
                </button>
            </form>
        </div>
    </div>

    <div id="footer-placeholder"></div>

    <script>
      fetch('footer.html')
        .then(res => res.text())
        .then(data => {
          document.getElementById('footer-placeholder').innerHTML = data;
        });
    </script>

</body>
</html>