<?php
// Start session if needed (for consistency with your app)
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "eduhub";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$results = [];

$class_level = "";
$subject = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search_tuition'])) {
    $class_level = isset($_POST['class_level']) ? $_POST['class_level'] : '';
    $subject = isset($_POST['subject']) ? $_POST['subject'] : '';

    if (!empty($class_level) && !empty($subject)) {
        $sql = "SELECT * FROM tuitions WHERE class_level = '$class_level' AND subject = '$subject'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $results[] = $row;
            }
        } else {
            $results[] = ['message' => 'No results found.'];
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Tuition Offers</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding-bottom: 100px;
        }
        
        .hero-section {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.9), rgba(118, 75, 162, 0.9));
            color: white;
            padding: 80px 0 60px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="1" fill="white" opacity="0.05"/><circle cx="20" cy="80" r="1" fill="white" opacity="0.05"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
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
            animation: fadeInUp 1s ease-out;
        }
        
        .hero-subtitle {
            font-size: 1.3rem;
            opacity: 0.9;
            margin-bottom: 0;
            animation: fadeInUp 1s ease-out 0.2s both;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .search-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            padding: 40px;
            margin: -50px auto 50px;
            max-width: 800px;
            position: relative;
            z-index: 3;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
        }
        
        .search-title {
            color: #2c3e50;
            font-size: 2rem;
            font-weight: 600;
            text-align: center;
            margin-bottom: 30px;
            position: relative;
        }
        
        .search-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 2px;
        }
        
        .form-group {
            margin-bottom: 25px;
            position: relative;
        }
        
        .form-group label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 15px 20px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            background: white;
            transform: translateY(-2px);
        }
        
        .search-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 50px;
            padding: 15px 40px;
            font-size: 1.1rem;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            display: block;
            margin: 30px auto 0;
            min-width: 200px;
        }
        
        .search-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.6);
            color: white;
        }
        
        .search-btn:active {
            transform: translateY(-1px);
        }
        
        .results-section {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            padding: 40px;
            margin: 30px auto;
            max-width: 1200px;
        }
        
        .results-title {
            color: #2c3e50;
            font-size: 2.2rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 30px;
            position: relative;
        }
        
        .results-title::before {
            content: '🎯';
            margin-right: 15px;
        }
        
        .table-container {
            overflow-x: auto;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
        
        .table {
            margin-bottom: 0;
            border-radius: 15px;
            overflow: hidden;
        }
        
        .table thead th {
            background: linear-gradient(135deg, #2c3e50, #34495e);
            color: white;
            border: none;
            padding: 20px 15px;
            font-weight: 600;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            position: relative;
        }
        
        .table tbody tr {
            transition: all 0.3s ease;
            border: none;
        }
        
        .table tbody tr:hover {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            transform: scale(1.01);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .table td {
            padding: 20px 15px;
            border: none;
            border-bottom: 1px solid #e9ecef;
            vertical-align: middle;
            font-size: 0.95rem;
        }
        
        .no-result-container {
            text-align: center;
            padding: 60px 20px;
            background: linear-gradient(135deg, #ff6b6b, #ffa726);
            border-radius: 20px;
            color: white;
            margin: 30px auto;
            max-width: 600px;
            box-shadow: 0 15px 35px rgba(255, 107, 107, 0.3);
        }
        
        .no-result-icon {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.8;
        }
        
        .no-result-message {
            font-size: 1.3rem;
            font-weight: 600;
            margin: 0;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
        }
        
        .container-fluid {
            padding: 0;
        }
        
        .main-container {
            padding: 0 15px;
        }
        
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-subtitle {
                font-size: 1.1rem;
            }
            
            .search-container {
                margin: -30px 15px 30px;
                padding: 30px 20px;
            }
            
            .results-section {
                margin: 20px 15px;
                padding: 30px 20px;
            }
            
            .table thead th,
            .table td {
                padding: 15px 10px;
                font-size: 0.9rem;
            }
            
            .search-btn {
                padding: 12px 30px;
                min-width: 180px;
            }
        }
        
        .floating-elements {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            pointer-events: none;
        }
        
        .floating-element {
            position: absolute;
            opacity: 0.1;
            animation: float 6s ease-in-out infinite;
        }
        
        .floating-element:nth-child(1) {
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }
        
        .floating-element:nth-child(2) {
            top: 60%;
            right: 10%;
            animation-delay: 2s;
        }
        
        .floating-element:nth-child(3) {
            bottom: 20%;
            left: 20%;
            animation-delay: 4s;
        }
        
        @keyframes float {
            0%, 100% {
                transform: translateY(0px) rotate(0deg);
            }
            50% {
                transform: translateY(-20px) rotate(180deg);
            }
        }
    </style>
</head>
<body>

    <!-- Include Navbar -->
    <?php include 'navbar.php'; ?>

    <div class="container-fluid">
        <!-- Hero Section -->
        <div class="hero-section">
            <div class="floating-elements">
                <i class="fas fa-graduation-cap floating-element" style="font-size: 3rem;"></i>
                <i class="fas fa-book floating-element" style="font-size: 2.5rem;"></i>
                <i class="fas fa-users floating-element" style="font-size: 2rem;"></i>
            </div>
            <div class="hero-content">
                <h1 class="hero-title">
                    <i class="fas fa-search mr-3"></i>Discover Perfect Tuition
                </h1>
                <p class="hero-subtitle">Find the best tutors for your academic journey</p>
            </div>
        </div>

        <div class="main-container">
            <!-- Search Form -->
            <div class="search-container">
                <h2 class="search-title">Search Tuition Offers</h2>
                
                <form method="post">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="class_level">
                                    <i class="fas fa-layer-group text-primary"></i>
                                    Class Level
                                </label>
                                <select class="form-control" id="class_level" name="class_level" required>
                                    <option value="">Select Class</option>
                                    <?php
                                    for ($i = 1; $i <= 12; $i++) {
                                        echo "<option value='$i'" . ($class_level == $i ? " selected" : "") . ">Class $i</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="subject">
                                    <i class="fas fa-book-open text-success"></i>
                                    Subject
                                </label>
                                <select class="form-control" id="subject" name="subject" required>
                                    <option value="">Select Subject</option>
                                    <?php
                                    $subjects = ['Science', 'Math', 'English', 'Biology', 'Economics', 'Chemistry', 'Physics'];
                                    foreach ($subjects as $sub) {
                                        echo "<option value='$sub'" . ($subject == $sub ? " selected" : "") . ">$sub</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="search-btn" name="search_tuition">
                        <i class="fas fa-search mr-2"></i>Search Tuitions
                    </button>
                </form>
            </div>

            <!-- Results Section -->
            <?php if (!empty($results)): ?>
                <?php if (isset($results[0]['message'])): ?>
                    <div class="no-result-container">
                        <div class="no-result-icon">
                            <i class="fas fa-search-minus"></i>
                        </div>
                        <p class="no-result-message"><?= htmlspecialchars($results[0]['message']) ?></p>
                        <p style="margin-top: 15px; opacity: 0.9;">Try adjusting your search criteria</p>
                    </div>
                <?php else: ?>
                    <div class="results-section">
                        <h2 class="results-title">Search Results</h2>
                        <div class="table-container">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th><i class="fas fa-layer-group mr-2"></i>Class Level</th>
                                        <th><i class="fas fa-book mr-2"></i>Subject</th>
                                        <th><i class="fas fa-map-marker-alt mr-2"></i>Location</th>
                                        <th><i class="fas fa-university mr-2"></i>Institution</th>
                                        <th><i class="fas fa-phone mr-2"></i>Phone Number</th>
                                        <th><i class="fas fa-clock mr-2"></i>Preferred Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($results as $row): ?>
                                    <tr>
                                        <td><strong>Class <?= htmlspecialchars($row['class_level']) ?></strong></td>
                                        <td><span class="badge badge-primary p-2"><?= htmlspecialchars($row['subject']) ?></span></td>
                                        <td><i class="fas fa-map-marker-alt text-danger mr-1"></i><?= htmlspecialchars($row['location']) ?></td>
                                        <td><?= htmlspecialchars($row['institution']) ?></td>
                                        <td><a href="tel:<?= htmlspecialchars($row['phone_number']) ?>" class="text-success"><i class="fas fa-phone mr-1"></i><?= htmlspecialchars($row['phone_number']) ?></a></td>
                                        <td><i class="fas fa-clock text-info mr-1"></i><?= htmlspecialchars($row['preferred_time']) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
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