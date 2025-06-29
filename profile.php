<?php
session_start();
require 'db_connect.php'; // Include your database connection file

// Check if the user is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

// Get the student_id from the session
$student_id = $_SESSION['student_id'];

// Fetch user information from students table
$sql_user = "SELECT name, email, profile_picture FROM students WHERE student_id = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $student_id);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
$user = $result_user->fetch_assoc();

// Fetch user information from UserInformation table
$sql_info = "SELECT school, college, university, occupation, job_field FROM userinformation WHERE student_id = ?";
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
    // Sanitize and validate posted data
    $name = !empty($_POST['name']) ? trim($_POST['name']) : $user['name'];
    $email = !empty($_POST['email']) ? trim($_POST['email']) : $user['email'];
    $school = !empty($_POST['school']) ? trim($_POST['school']) : $info['school'];
    $college = !empty($_POST['college']) ? trim($_POST['college']) : $info['college'];
    $university = !empty($_POST['university']) ? trim($_POST['university']) : $info['university'];
    $occupation = !empty($_POST['occupation']) ? trim($_POST['occupation']) : $info['occupation'];
    $job_field = !empty($_POST['job_field']) ? trim($_POST['job_field']) : $info['job_field'];

    // Update students table (name and email) only if the user entered a value, else retain old data
    $sql_update_user = "UPDATE students SET name = ?, email = ? WHERE student_id = ?";
    $stmt_update_user = $conn->prepare($sql_update_user);
    $stmt_update_user->bind_param("ssi", $name, $email, $student_id);

    // Update UserInformation if it exists
    $sql_update_info = "UPDATE userinformation SET 
                        school = ?, 
                        college = ?, 
                        university = ?, 
                        occupation = ?, 
                        job_field = ? 
                        WHERE student_id = ?";
    
    $stmt_update_info = $conn->prepare($sql_update_info);
    $stmt_update_info->bind_param(
        "sssssi", 
        $school, 
        $college, 
        $university, 
        $occupation, 
        $job_field,
        $student_id
    );

    // Handle profile picture upload
    $upload_directory = 'uploads/';
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['profile_picture']['tmp_name'];
        $file_name = basename($_FILES['profile_picture']['name']);
        $file_path = $upload_directory . $file_name;

        // Move uploaded file to the uploads directory
        if (move_uploaded_file($file_tmp, $file_path)) {
            // Update profile_picture in the students table
            $sql_update_picture = "UPDATE students SET profile_picture = ? WHERE student_id = ?";
            $stmt_update_picture = $conn->prepare($sql_update_picture);
            $stmt_update_picture->bind_param("si", $file_path, $student_id);
            $stmt_update_picture->execute();
            $stmt_update_picture->close();
        } else {
            echo "<script>alert('Error uploading profile picture.');</script>";
        }
    }

    // Execute updates and check for success
    $user_update_success = $stmt_update_user->execute();
    $info_update_success = $stmt_update_info->execute();

    // Refresh page to fetch updated info only if both updates were successful
    if ($user_update_success || $info_update_success) {
        // Set updated values to be displayed in the form
        $info['school'] = $school;
        $info['college'] = $college;
        $info['university'] = $university;
        $info['occupation'] = $occupation;
        $info['job_field'] = $job_field;

        // Optionally show a success message
        echo "<script>showSuccessMessage('Information updated successfully!');</script>";
    } else {
        echo "<script>showErrorMessage('Error updating information');</script>";
    }
}

// Close the statement
$stmt_user->close();
$stmt_info->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile - EduHub</title>
    
    <!-- Professional Form Styling -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
    <style>
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --accent-color: #ff6b6b;
            --success-color: #2ecc71;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --light-gray: #f8f9fa;
            --medium-gray: #6c757d;
            --dark-gray: #343a40;
            --border-radius: 12px;
            --box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #333;
            line-height: 1.6;
        }

        /* Professional Container */
        .profile-container {
            max-width: 1000px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        /* Profile Header Card */
        .profile-header-card {
    background: rgba(255, 255, 255, 0.14); /* semi-transparent white */
    backdrop-filter: blur(10px); /* adds blur effect */
    -webkit-backdrop-filter: blur(10px); /* Safari support */
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
    padding: 2rem;
    margin: 0 auto 2rem auto; /* center horizontally and margin-bottom */
    text-align: center;
    position: relative;
    overflow: hidden;
    width: 60%; /* added width */
}



        .profile-header-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
        }

        .profile-avatar {
            position: relative;
            display: inline-block;
            margin-bottom: 1.5rem;
        }

        .profile-img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid white;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            transition: var(--transition);
        }

        .profile-img:hover {
            transform: scale(1.05);
        }

        .profile-name {
            font-family: 'Poppins', sans-serif;
            font-size: 1.8rem;
            font-weight: 600;
            color: var(--dark-gray);
            margin-bottom: 0.5rem;
        }

        .profile-email {
            color: var(--medium-gray);
            font-size: 1rem;
        }

        /* Professional Form Card */
        .form-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 2.5rem;
            margin-bottom: 2rem;
        }

        .form-title {
            font-family: 'Poppins', sans-serif;
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--dark-gray);
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-title i {
            color: var(--primary-color);
        }

        /* Professional Form Groups */
        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--dark-gray);
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Professional Input Styling */
        .form-control {
            border: 2px solid #e9ecef;
            border-radius: var(--border-radius);
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: var(--transition);
            background: #f8f9fa;
            position: relative;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            background: white;
            outline: none;
        }

        .form-control:hover {
            border-color: var(--primary-color);
            background: white;
        }

        /* Input Icons */
        .input-group {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--medium-gray);
            z-index: 2;
        }

        .form-control.with-icon {
            padding-left: 3rem;
        }

        /* File Upload Styling */
        .file-upload-wrapper {
            position: relative;
            display: inline-block;
            width: 100%;
        }

        .file-upload-input {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .file-upload-label {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            background: var(--light-gray);
            border: 2px dashed #dee2e6;
            border-radius: var(--border-radius);
            cursor: pointer;
            transition: var(--transition);
            text-align: center;
        }

        .file-upload-label:hover {
            background: #e9ecef;
            border-color: var(--primary-color);
        }

        .file-upload-label i {
            color: var(--primary-color);
            font-size: 1.2rem;
        }

        /* Professional Buttons */
        .btn-professional {
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: var(--border-radius);
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .btn-professional:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
            color: white;
        }

        .btn-professional:active {
            transform: translateY(0);
        }

        .btn-secondary-professional {
            background: white;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
        }

        .btn-secondary-professional:hover {
            background: var(--primary-color);
            color: white;
        }

        /* Form Sections */
        .form-section {
            margin-bottom: 2rem;
            padding-bottom: 2rem;
            border-bottom: 1px solid #e9ecef;
        }

        .form-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .form-section-title {
            font-family: 'Poppins', sans-serif;
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--dark-gray);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-section-title i {
            color: var(--primary-color);
            font-size: 1rem;
        }

        /* Grid Layout */
        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        /* Notification Styles */
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 1rem 1.5rem;
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            z-index: 1000;
            transform: translateX(100%);
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            min-width: 300px;
        }

        .notification.show {
            transform: translateX(0);
        }

        .notification.success {
            border-left: 4px solid var(--success-color);
        }

        .notification.error {
            border-left: 4px solid var(--danger-color);
        }

        .notification i {
            font-size: 1.2rem;
        }

        .notification.success i {
            color: var(--success-color);
        }

        .notification.error i {
            color: var(--danger-color);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .profile-container {
                margin: 1rem auto;
                padding: 0 0.5rem;
            }

            .form-card {
                padding: 1.5rem;
            }

            .form-row {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .profile-name {
                font-size: 1.5rem;
            }
        }

        /* Loading States */
        .btn-professional.loading {
            pointer-events: none;
            opacity: 0.7;
        }

        .btn-professional.loading::after {
            content: '';
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top: 2px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-left: 0.5rem;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Form Validation Styles */
        .form-control.is-valid {
            border-color: var(--success-color);
        }

        .form-control.is-invalid {
            border-color: var(--danger-color);
        }

        .valid-feedback, .invalid-feedback {
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .valid-feedback {
            color: var(--success-color);
        }

        .invalid-feedback {
            color: var(--danger-color);
        }
    </style>
</head>
<body>
<iframe src="curved-background.html"
          style="position: fixed; z-index: -1; border: none; width: 100vw; height: 100vh ;">
  </iframe>

    <?php include 'navbar.php'; ?>
    
    <div class="profile-container">
        <!-- Profile Header -->
        <div class="profile-header-card">
            <div class="profile-avatar">
                <img src="<?php echo htmlspecialchars($user['profile_picture'] ?: 'default-profile.png'); ?>" 
                     alt="Profile Picture" class="profile-img" id="currentProfileImg">
            </div>
            <h1 class="profile-name"><?php echo htmlspecialchars($user['name']); ?></h1>
            <p class="profile-email"><?php echo htmlspecialchars($user['email']); ?></p>
        </div>

        <!-- Profile Form -->
        <div class="form-card" style="background: rgba(255, 255, 255, 0.14); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); width: 60%; margin: 0 auto; padding: 2rem; border-radius: 1rem;">

            <h2 class="form-title">
                <i class="fas fa-user-edit"></i>
                Edit Profile Information
            </h2>
            
            <form method="POST" enctype="multipart/form-data" id="profileForm">
                <!-- Personal Information Section -->
                <div class="form-section">
                    <h3 class="form-section-title">
                        <i class="fas fa-user"></i>
                        Personal Information
                    </h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="name">Full Name</label>
                            <div class="input-group">
                                <i class="fas fa-user input-icon"></i>
                                <input type="text" class="form-control with-icon" id="name" name="name" 
                                       value="<?php echo htmlspecialchars($user['name']); ?>" 
                                       placeholder="Enter your full name">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label" for="email">Email Address</label>
                            <div class="input-group">
                                <i class="fas fa-envelope input-icon"></i>
                                <input type="email" class="form-control with-icon" id="email" name="email" 
                                       value="<?php echo htmlspecialchars($user['email']); ?>" 
                                       placeholder="Enter your email">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Educational Background Section -->
                <div class="form-section">
                    <h3 class="form-section-title">
                        <i class="fas fa-graduation-cap"></i>
                        Educational Background
                    </h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="school">School</label>
                            <div class="input-group">
                                <i class="fas fa-school input-icon"></i>
                                <input type="text" class="form-control with-icon" id="school" name="school" 
                                       value="<?php echo htmlspecialchars($info['school']); ?>" 
                                       placeholder="Enter your school name">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label" for="college">College</label>
                            <div class="input-group">
                                <i class="fas fa-university input-icon"></i>
                                <input type="text" class="form-control with-icon" id="college" name="college" 
                                       value="<?php echo htmlspecialchars($info['college']); ?>" 
                                       placeholder="Enter your college name">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="university">University</label>
                        <div class="input-group">
                            <i class="fas fa-university input-icon"></i>
                            <input type="text" class="form-control with-icon" id="university" name="university" 
                                   value="<?php echo htmlspecialchars($info['university']); ?>" 
                                   placeholder="Enter your university name">
                        </div>
                    </div>
                </div>

                <!-- Professional Information Section -->
                <div class="form-section">
                    <h3 class="form-section-title">
                        <i class="fas fa-briefcase"></i>
                        Professional Information
                    </h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="occupation">Occupation</label>
                            <div class="input-group">
                                <i class="fas fa-briefcase input-icon"></i>
                                <input type="text" class="form-control with-icon" id="occupation" name="occupation" 
                                       value="<?php echo htmlspecialchars($info['occupation']); ?>" 
                                       placeholder="Enter your occupation">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label" for="job_field">Job Field</label>
                            <div class="input-group">
                                <i class="fas fa-industry input-icon"></i>
                                <input type="text" class="form-control with-icon" id="job_field" name="job_field" 
                                       value="<?php echo htmlspecialchars($info['job_field']); ?>" 
                                       placeholder="Enter your job field">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Profile Picture Section -->
                <div class="form-section">
                    <h3 class="form-section-title">
                        <i class="fas fa-camera"></i>
                        Profile Picture
                    </h3>
                    
                    <div class="form-group">
                        <label class="form-label">Upload New Picture</label>
                        <div class="file-upload-wrapper">
                            <input type="file" class="file-upload-input" id="profile_picture" name="profile_picture" 
                                   accept="image/*" onchange="previewImage(this)">
                            <label for="profile_picture" class="file-upload-label">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <span>Choose a new profile picture</span>
                            </label>
                        </div>
                        <small class="text-muted">Supported formats: JPG, PNG, GIF (Max size: 5MB)</small>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex justify-content-between align-items-center">
                    <a href="index.php" class="btn-professional btn-secondary-professional">
                        <i class="fas fa-arrow-left"></i>
                        Back to Dashboard
                    </a>
                    
                    <button type="submit" class="btn-professional" id="submitBtn">
                        <i class="fas fa-save"></i>
                        Update Profile
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Notification Container -->
    <div id="notification" class="notification">
        <i class="fas fa-check-circle"></i>
        <span id="notificationText">Profile updated successfully!</span>
    </div>


    <div id="footer-placeholder"></div>

<script>
  fetch('footer.html')
    .then(res => res.text())
    .then(data => {
      document.getElementById('footer-placeholder').innerHTML = data;
    });
</script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>



        // Image Preview Function
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('currentProfileImg').src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Form Submission with Loading State
        document.getElementById('profileForm').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.classList.add('loading');
            submitBtn.disabled = true;
        });

        // Show Success/Error Messages
        function showSuccessMessage(message) {
            showNotification(message, 'success');
        }

        function showErrorMessage(message) {
            showNotification(message, 'error');
        }

        function showNotification(message, type) {
            const notification = document.getElementById('notification');
            const notificationText = document.getElementById('notificationText');
            const icon = notification.querySelector('i');
            
            // Set message
            notificationText.textContent = message;
            
            // Set type
            notification.className = `notification ${type}`;
            
            // Set icon
            if (type === 'success') {
                icon.className = 'fas fa-check-circle';
            } else {
                icon.className = 'fas fa-exclamation-circle';
            }
            
            // Show notification
            notification.classList.add('show');
            
            // Hide after 5 seconds
            setTimeout(() => {
                notification.classList.remove('show');
            }, 5000);
        }

        // Form Validation
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('blur', function() {
                validateField(this);
            });
        });

        function validateField(field) {
            const value = field.value.trim();
            
            if (field.type === 'email') {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (value && !emailRegex.test(value)) {
                    field.classList.add('is-invalid');
                    field.classList.remove('is-valid');
                } else if (value) {
                    field.classList.add('is-valid');
                    field.classList.remove('is-invalid');
                }
            }
        }

        // Enhanced file upload feedback
        document.getElementById('profile_picture').addEventListener('change', function() {
            const label = this.nextElementSibling;
            const fileName = this.files[0] ? this.files[0].name : 'Choose a new profile picture';
            label.querySelector('span').textContent = fileName;
        });

        // Smooth scroll for better UX
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>