<?php
require 'db_connect.php'; // Include your database connection file

session_start();

// Check if user is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

$student_id = $_SESSION['student_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the form fields are set and not empty
    $class_range_start = isset($_POST['class_range_start']) ? $_POST['class_range_start'] : '';
    $class_range_end = isset($_POST['class_range_end']) ? $_POST['class_range_end'] : '';
    $subject = isset($_POST['subject']) ? $_POST['subject'] : '';
    $location = isset($_POST['location']) ? $_POST['location'] : '';
    $phone_number = isset($_POST['phone_number']) ? $_POST['phone_number'] : '';

    // Validate the input (basic validation)
    if ($class_range_start && $class_range_end && $subject && $location && $phone_number) {
        $sql = "INSERT INTO tutors (student_id, class_range_start, class_range_end, subject, location, phone_number)
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iissss", $student_id, $class_range_start, $class_range_end, $subject, $location, $phone_number);

        if ($stmt->execute()) {
            echo "<p class='success'>Tutor registration successful!</p>";
        } else {
            echo "<p class='error'>Error: " . $stmt->error . "</p>";
        }

        $stmt->close();
    } else {
        echo "<p class='error'>Please fill in all fields.</p>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register as Tutor</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            margin: 0;
            padding: 0;
            position: relative;
            overflow-x: hidden;
        }
        
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="white" stroke-width="0.5" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
            pointer-events: none;
            z-index: -1;
        }
        
        .hero-banner {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.95), rgba(118, 75, 162, 0.95));
            color: white;
            padding: 80px 0 60px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .hero-banner::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
            background-size: 50px 50px;
            animation: moveBackground 20s linear infinite;
        }
        
        @keyframes moveBackground {
            0% { transform: translate(0, 0); }
            100% { transform: translate(50px, 50px); }
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
        }
        
        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            animation: slideInDown 1s ease-out;
        }
        
        .hero-subtitle {
            font-size: 1.4rem;
            opacity: 0.9;
            margin-bottom: 0;
            animation: slideInUp 1s ease-out 0.3s both;
        }
        
        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .registration-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 25px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.15);
            padding: 50px;
            margin: -80px auto 60px;
            max-width: 800px;
            position: relative;
            z-index: 3;
            border: 1px solid rgba(255,255,255,0.3);
            animation: fadeInUp 1s ease-out 0.5s both;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .form-title {
            color: #2c3e50;
            font-size: 2.5rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 40px;
            position: relative;
        }
        
        .form-title::after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 2px;
        }
        
        .form-row {
            margin-bottom: 30px;
        }
        
        .form-group {
            margin-bottom: 25px;
            position: relative;
        }
        
        .form-group label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 10px;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 15px;
            padding: 18px 20px;
            font-size: 1.05rem;
            transition: all 0.3s ease;
            background: rgba(248, 249, 250, 0.8);
            position: relative;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            background: white;
            transform: translateY(-2px);
            outline: none;
        }
        
        .form-control:hover {
            border-color: #ced4da;
            background: white;
        }
        
        .phone-input-group {
            position: relative;
        }
        
        .phone-input-group::before {
            content: '📱';
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1.2rem;
            z-index: 1;
        }
        
        .phone-input {
            padding-left: 50px !important;
        }
        
        .submit-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 50px;
            padding: 18px 60px;
            font-size: 1.2rem;
            font-weight: 600;
            color: white;
            transition: all 0.4s ease;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
            display: block;
            margin: 40px auto 0;
            min-width: 250px;
            position: relative;
            overflow: hidden;
        }
        
        .submit-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .submit-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(102, 126, 234, 0.6);
            color: white;
        }
        
        .submit-btn:hover::before {
            left: 100%;
        }
        
        .submit-btn:active {
            transform: translateY(-1px);
        }
        
        .success {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 20px;
            border-radius: 15px;
            text-align: center;
            margin: 30px 0;
            font-size: 1.2rem;
            font-weight: 600;
            box-shadow: 0 10px 25px rgba(40, 167, 69, 0.3);
            animation: successPulse 0.6s ease-out;
        }
        
        @keyframes successPulse {
            0% {
                transform: scale(0.9);
                opacity: 0;
            }
            50% {
                transform: scale(1.05);
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }
        
        .error {
            background: linear-gradient(135deg, #dc3545, #fd7e14);
            color: white;
            padding: 20px;
            border-radius: 15px;
            text-align: center;
            margin: 30px 0;
            font-size: 1.2rem;
            font-weight: 600;
            box-shadow: 0 10px 25px rgba(220, 53, 69, 0.3);
            animation: errorShake 0.6s ease-out;
        }
        
        @keyframes errorShake {
            0%, 20%, 40%, 60%, 80% {
                transform: translateX(-5px);
            }
            10%, 30%, 50%, 70%, 90% {
                transform: translateX(5px);
            }
            100% {
                transform: translateX(0);
            }
        }
        
        .icon-decoration {
            color: #667eea;
            margin-right: 8px;
        }
        
        .floating-shapes {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            pointer-events: none;
            z-index: 1;
        }
        
        .shape {
            position: absolute;
            opacity: 0.1;
            animation: float 8s ease-in-out infinite;
        }
        
        .shape:nth-child(1) {
            top: 20%;
            left: 5%;
            font-size: 3rem;
            animation-delay: 0s;
        }
        
        .shape:nth-child(2) {
            top: 70%;
            right: 10%;
            font-size: 2.5rem;
            animation-delay: 2s;
        }
        
        .shape:nth-child(3) {
            bottom: 30%;
            left: 15%;
            font-size: 2rem;
            animation-delay: 4s;
        }
        
        @keyframes float {
            0%, 100% {
                transform: translateY(0px) rotate(0deg);
            }
            50% {
                transform: translateY(-30px) rotate(180deg);
            }
        }
        
        .container-fluid {
            padding: 0;
            position: relative;
        }
        
        .main-content {
            padding: 0 15px;
            position: relative;
            z-index: 2;
        }
        
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-subtitle {
                font-size: 1.2rem;
            }
            
            .registration-container {
                margin: -50px 15px 40px;
                padding: 30px 25px;
            }
            
            .form-title {
                font-size: 2rem;
            }
            
            .submit-btn {
                padding: 15px 40px;
                font-size: 1.1rem;
                min-width: 200px;
            }
            
            .form-control {
                padding: 15px 18px;
                font-size: 1rem;
            }
        }
        
        @media (max-width: 576px) {
            .hero-banner {
                padding: 60px 0 40px;
            }
            
            .hero-title {
                font-size: 2rem;
            }
            
            .registration-container {
                border-radius: 20px;
                padding: 25px 20px;
            }
            
            .form-title {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>

    <?php include 'navbar.php'; ?>

    <div class="container-fluid">
        <!-- Hero Banner -->
        <div class="hero-banner">
            <div class="floating-shapes">
                <i class="fas fa-chalkboard-teacher shape"></i>
                <i class="fas fa-graduation-cap shape"></i>
                <i class="fas fa-book-open shape"></i>
            </div>
            <div class="hero-content">
                <h1 class="hero-title">
                    <i class="fas fa-user-graduate mr-3"></i>Become a Tutor
                </h1>
                <p class="hero-subtitle">Share your knowledge and inspire the next generation</p>
            </div>
        </div>

        <div class="main-content">
            <!-- Registration Form -->
            <div class="registration-container">
                <h2 class="form-title">Tutor Registration</h2>
                
                <form method="post" action="register_tutor.php">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="class_range_start">
                                    <i class="fas fa-play icon-decoration"></i>
                                    Class Range Start
                                </label>
                                <select id="class_range_start" name="class_range_start" class="form-control" required>
                                    <option value="">Select Start Class</option>
                                    <?php for ($i = 1; $i <= 12; $i++) { echo "<option value=\"$i\">Class $i</option>"; } ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="class_range_end">
                                    <i class="fas fa-stop icon-decoration"></i>
                                    Class Range End
                                </label>
                                <select id="class_range_end" name="class_range_end" class="form-control" required>
                                    <option value="">Select End Class</option>
                                    <?php for ($i = 1; $i <= 12; $i++) { echo "<option value=\"$i\">Class $i</option>"; } ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="subject">
                                    <i class="fas fa-book icon-decoration"></i>
                                    Subject
                                </label>
                                <select id="subject" name="subject" class="form-control" required>
                                    <option value="">Select Subject</option>
                                    <option value="Science">Science</option>
                                    <option value="Math">Math</option>
                                    <option value="English">English</option>
                                    <option value="Biology">Biology</option>
                                    <option value="Economics">Economics</option>
                                    <option value="Chemistry">Chemistry</option>
                                    <option value="Physics">Physics</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="location">
                                    <i class="fas fa-map-marker-alt icon-decoration"></i>
                                    Location
                                </label>
                                <select id="location" name="location" class="form-control" required>
                                    <option value="">Select Location</option>
                                    <option value="Mirpur">Mirpur</option>
                                    <option value="Dhanmondi">Dhanmondi</option>
                                    <option value="Khilkhet">Khilkhet</option>
                                    <option value="Banani">Banani</option>
                                    <option value="Uttara">Uttara</option>
                                    <option value="Mohammadpur">Mohammadpur</option>
                                    <option value="Bashundhara">Bashundhara</option>
                                    <option value="Gulshan">Gulshan</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="phone_number">
                            <i class="fas fa-phone icon-decoration"></i>
                            Phone Number
                        </label>
                        <div class="phone-input-group">
                            <input type="text" id="phone_number" name="phone_number" class="form-control phone-input" placeholder="Enter your phone number" required>
                        </div>
                    </div>

                    <button type="submit" class="submit-btn">
                        <i class="fas fa-user-plus mr-2"></i>Register as Tutor
                    </button>
                </form>
            </div>
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