<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "eduhub";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get student ID from session
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php"); // redirect if not logged in
    exit();
}
$student_id = $_SESSION['student_id']; // ✅ dynamic student ID

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_tuition'])) {
    $class_level = $_POST['class_level'];
    $subject = $_POST['subject'];
    $location = $_POST['location'];
    $institution = $_POST['institution'];
    $phone_number = $_POST['phone_number'];
    $preferred_time = $_POST['preferred_time'];

    $sql = "INSERT INTO tuitions (student_id, class_level, subject, location, institution, phone_number, preferred_time)
            VALUES ('$student_id', '$class_level', '$subject', '$location', '$institution', '$phone_number', '$preferred_time')";

    if ($conn->query($sql) === TRUE) {
        header("Location: add_tuition.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post a Tuition Offer</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
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
            background: 
                radial-gradient(ellipse at top left, rgba(255, 255, 255, 0.1) 0%, transparent 60%),
                radial-gradient(ellipse at bottom right, rgba(120, 119, 198, 0.3) 0%, transparent 60%),
                radial-gradient(ellipse at center, rgba(102, 126, 234, 0.2) 0%, transparent 70%);
            z-index: -1;
            animation: backgroundFloat 15s ease-in-out infinite;
        }

        @keyframes backgroundFloat {
            0%, 100% { opacity: 0.8; transform: scale(1); }
            50% { opacity: 1; transform: scale(1.05); }
        }

        .hero-section {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.95) 0%, rgba(118, 75, 162, 0.95) 100%);
            padding: 80px 0 60px;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="0.5"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
            animation: slidePattern 20s linear infinite;
        }

        @keyframes slidePattern {
            0% { transform: translate(-50%, -50%) rotate(0deg); }
            100% { transform: translate(-50%, -50%) rotate(360deg); }
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-icon {
            font-size: 4rem;
            color: #ffd700;
            margin-bottom: 20px;
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-10px); }
            60% { transform: translateY(-5px); }
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 15px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            animation: fadeInUp 1s ease-out;
        }

        .hero-subtitle {
            font-size: 1.3rem;
            font-weight: 400;
            opacity: 0.9;
            margin-bottom: 30px;
            animation: fadeInUp 1s ease-out 0.3s both;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .main-container {
            max-width: 900px;
            margin: -40px auto 50px;
            padding: 0 20px;
            position: relative;
            z-index: 10;
        }

        .form-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 30px;
            padding: 50px;
            box-shadow: 
                0 25px 80px rgba(0, 0, 0, 0.15),
                0 10px 30px rgba(0, 0, 0, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(255, 255, 255, 0.3);
            position: relative;
            overflow: hidden;
            animation: slideInUp 0.8s ease-out;
        }

        .form-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2, #667eea);
            background-size: 200% 100%;
            animation: gradientShift 3s ease-in-out infinite;
        }

        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        @keyframes slideInUp {
            from { opacity: 0; transform: translateY(50px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #2c3e50;
            text-align: center;
            margin-bottom: 40px;
            position: relative;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2);
            border-radius: 2px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }

        .form-group {
            position: relative;
            margin-bottom: 0;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: #555;
            margin-bottom: 10px;
            font-size: 1rem;
            transition: color 0.3s ease;
            position: relative;
            padding-left: 30px;
        }

        .form-label::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .form-label[for="class_level"]::before { content: '📚'; }
        .form-label[for="subject"]::before { content: '📖'; }
        .form-label[for="location"]::before { content: '📍'; }
        .form-label[for="institution"]::before { content: '🏫'; }
        .form-label[for="phone_number"]::before { content: '📞'; }
        .form-label[for="preferred_time"]::before { content: '⏰'; }

        .form-control {
            width: 100%;
            padding: 18px 25px;
            border: 2px solid #e1e8ed;
            border-radius: 20px;
            font-size: 16px;
            font-weight: 500;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            position: relative;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 
                0 0 0 4px rgba(102, 126, 234, 0.1),
                0 10px 25px rgba(102, 126, 234, 0.15);
            transform: translateY(-2px);
            background: rgba(255, 255, 255, 1);
        }

        .form-control:focus + .form-label {
            color: #667eea;
        }

        .form-control::placeholder {
            color: #a0a0a0;
            font-weight: 400;
        }

        .btn-submit {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 20px 50px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1.2rem;
            color: white;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
            text-transform: uppercase;
            letter-spacing: 1px;
            display: block;
            margin: 0 auto;
            min-width: 250px;
        }

        .btn-submit::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: left 0.6s;
        }

        .btn-submit:hover::before {
            left: 100%;
        }

        .btn-submit:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(102, 126, 234, 0.5);
        }

        .btn-submit:active {
            transform: translateY(-2px);
        }

        .features-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .feature-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(247, 250, 252, 0.9));
            backdrop-filter: blur(10px);
            padding: 30px;
            border-radius: 20px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #667eea, #764ba2);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .feature-card:hover::before {
            transform: scaleX(1);
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .feature-icon {
            font-size: 2.5rem;
            color: #667eea;
            margin-bottom: 15px;
            transition: transform 0.3s ease;
        }

        .feature-card:hover .feature-icon {
            transform: scale(1.1);
        }

        .feature-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
        }

        .feature-description {
            color: #7f8c8d;
            font-size: 0.95rem;
            line-height: 1.5;
        }

        .success-message {
            background: linear-gradient(135deg, #00b894, #00a085);
            color: white;
            padding: 20px 30px;
            border-radius: 20px;
            text-align: center;
            margin: 30px 0;
            font-weight: 600;
            box-shadow: 0 10px 30px rgba(0, 184, 148, 0.3);
            animation: slideInDown 0.5s ease-out;
        }

        .error-message {
            background: linear-gradient(135deg, #ff6b6b, #ee5a52);
            color: white;
            padding: 20px 30px;
            border-radius: 20px;
            text-align: center;
            margin: 30px 0;
            font-weight: 600;
            box-shadow: 0 10px 30px rgba(255, 107, 107, 0.3);
            animation: slideInDown 0.5s ease-out;
        }

        @keyframes slideInDown {
            from { opacity: 0; transform: translateY(-30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .form-floating-elements {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            pointer-events: none;
            overflow: hidden;
        }

        .floating-shape {
            position: absolute;
            opacity: 0.1;
            animation: float 6s ease-in-out infinite;
        }

        .floating-shape:nth-child(1) {
            top: 10%;
            left: 80%;
            animation-delay: -2s;
        }

        .floating-shape:nth-child(2) {
            top: 70%;
            left: 10%;
            animation-delay: -4s;
        }

        .floating-shape:nth-child(3) {
            top: 30%;
            right: 10%;
            animation-delay: -1s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-subtitle {
                font-size: 1.1rem;
            }
            
            .main-container {
                padding: 0 15px;
                margin-top: -20px;
            }
            
            .form-container {
                padding: 30px 25px;
                border-radius: 25px;
            }
            
            .form-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .btn-submit {
                padding: 18px 40px;
                font-size: 1.1rem;
                min-width: 200px;
            }
            
            .section-title {
                font-size: 2rem;
            }
        }

        @media (max-width: 480px) {
            .form-container {
                padding: 25px 20px;
            }
            
            .form-control {
                padding: 16px 20px;
                font-size: 15px;
            }
            
            .features-section {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
<iframe src="curved-background.html"
          style="position: fixed; z-index: -1; border: none; width: 100vw; height: 100vh;">
  </iframe>
    <?php include 'navbar.php'; ?>

    <div class="hero-section">
        <div class="hero-content">
            <div class="hero-icon">
                <i class="fas fa-chalkboard-teacher"></i>
            </div>
            <h1 class="hero-title">Tuition Marketplace</h1>
            <p class="hero-subtitle">Connect students with qualified tutors • Share knowledge, shape futures</p>
        </div>
    </div>

    <div class="main-container">
        <div class="features-section">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="feature-title">Quality Education</div>
                <div class="feature-description">Connect with experienced tutors for personalized learning</div>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div class="feature-title">Local Network</div>
                <div class="feature-description">Find tutors in your area for convenient scheduling</div>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="feature-title">Flexible Timing</div>
                <div class="feature-description">Choose your preferred time slots that work for you</div>
            </div>
        </div>

        <div class="form-container">
            <div class="form-floating-elements">
                <div class="floating-shape">
                    <i class="fas fa-book" style="font-size: 30px; color: #667eea;"></i>
                </div>
                <div class="floating-shape">
                    <i class="fas fa-pencil-alt" style="font-size: 25px; color: #764ba2;"></i>
                </div>
                <div class="floating-shape">
                    <i class="fas fa-lightbulb" style="font-size: 28px; color: #ffd700;"></i>
                </div>
            </div>

            <h2 class="section-title">Post Your Tuition Offer</h2>
            
            <form method="post" class="tuition-form">
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label" for="class_level">Class Level</label>
                        <select class="form-control" id="class_level" name="class_level" required>
                            <option value="">Select class level</option>
                            <option value="1">Class 1</option>
                            <option value="2">Class 2</option>
                            <option value="3">Class 3</option>
                            <option value="4">Class 4</option>
                            <option value="5">Class 5</option>
                            <option value="6">Class 6</option>
                            <option value="7">Class 7</option>
                            <option value="8">Class 8</option>
                            <option value="9">Class 9</option>
                            <option value="10">Class 10</option>
                            <option value="11">Class 11</option>
                            <option value="12">Class 12</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="subject">Subject</label>
                        <select class="form-control" id="subject" name="subject" required>
                            <option value="">Select subject</option>
                            <option value="Science">Science</option>
                            <option value="Math">Mathematics</option>
                            <option value="English">English</option>
                            <option value="Biology">Biology</option>
                            <option value="Economics">Economics</option>
                            <option value="Chemistry">Chemistry</option>
                            <option value="Physics">Physics</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="location">Location</label>
                        <select class="form-control" id="location" name="location" required>
                            <option value="">Select location</option>
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

                    <div class="form-group">
                        <label class="form-label" for="institution">Institution</label>
                        <input type="text" class="form-control" id="institution" name="institution" required placeholder="Enter your institution name">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="phone_number">Phone Number</label>
                        <input type="text" class="form-control" id="phone_number" name="phone_number" required placeholder="Enter your contact number">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="preferred_time">Preferred Time</label>
                        <input type="text" class="form-control" id="preferred_time" name="preferred_time" required placeholder="e.g., Morning 9AM-12PM">
                    </div>
                </div>

                <button type="submit" class="btn-submit" name="submit_tuition">
                    <i class="fas fa-paper-plane" style="margin-right: 10px;"></i>
                    Post Tuition Offer
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