<?php
session_start();
include 'db_connect.php';

// Insert blood donor information if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_donor'])) {
    $student_id = $_SESSION['student_id'];
    $location = $_POST['location'];
    $blood_type = $_POST['blood_type'];
    $phone_number = $_POST['phone_number'];
    $last_donation_date = !empty($_POST['last_donation_date']) ? $_POST['last_donation_date'] : 'Not Yet';
    
    $sql = "INSERT INTO blood_find (student_id, location, blood_type, phone_number, last_donation_date) 
            VALUES ('$student_id', '$location', '$blood_type', '$phone_number', '$last_donation_date')";
    
    if (mysqli_query($conn, $sql)) {
        echo "<p class='success'>Blood information successfully added!</p>";
    } else {
        echo "<p class='error'>Error: " . mysqli_error($conn) . "</p>";
    }
}

// Search blood donors based on filters
$search_results = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
    $search_location = $_POST['search_location'];
    $search_blood_type = $_POST['search_blood_type'];

    $search_query = "SELECT students.name, blood_find.phone_number, blood_find.last_donation_date 
                     FROM blood_find 
                     JOIN students ON blood_find.student_id = students.student_id 
                     WHERE blood_find.location = '$search_location' 
                     AND blood_find.blood_type = '$search_blood_type'";
    
    $result = mysqli_query($conn, $search_query);
    
    if (mysqli_num_rows($result) > 0) {
        $search_results = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        echo "<p class='error'>No donors found for the selected criteria.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Find</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
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
                radial-gradient(circle at 20% 50%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 80%, rgba(120, 119, 198, 0.2) 0%, transparent 50%);
            z-index: -1;
        }

        .hero-section {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.9) 0%, rgba(118, 75, 162, 0.9) 100%);
            padding: 80px 0;
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
            background: repeating-linear-gradient(
                45deg,
                transparent,
                transparent 2px,
                rgba(255, 255, 255, 0.03) 2px,
                rgba(255, 255, 255, 0.03) 4px
            );
            animation: float 20s linear infinite;
        }

        @keyframes float {
            0% { transform: translate(-50%, -50%) rotate(0deg); }
            100% { transform: translate(-50%, -50%) rotate(360deg); }
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            animation: slideInDown 1s ease-out;
        }

        .hero-subtitle {
            font-size: 1.3rem;
            font-weight: 300;
            margin-bottom: 30px;
            opacity: 0.9;
            animation: slideInUp 1s ease-out 0.3s both;
        }

        .blood-icon {
            font-size: 4rem;
            color: #ff4757;
            margin-bottom: 20px;
            animation: pulse 2s infinite;
        }

        @keyframes slideInDown {
            from { opacity: 0; transform: translateY(-50px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideInUp {
            from { opacity: 0; transform: translateY(50px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .main-container {
            max-width: 1200px;
            margin: -50px auto 50px;
            padding: 0 20px;
            position: relative;
            z-index: 10;
        }

        .card-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 25px;
            padding: 40px;
            margin-bottom: 30px;
            box-shadow: 
                0 20px 60px rgba(0, 0, 0, 0.1),
                0 8px 25px rgba(0, 0, 0, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
            animation: slideInUp 0.8s ease-out;
        }

        .card-container:hover {
            transform: translateY(-5px);
            box-shadow: 
                0 30px 80px rgba(0, 0, 0, 0.15),
                0 12px 35px rgba(0, 0, 0, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.8);
        }

        .section-title {
            font-size: 2.2rem;
            font-weight: 600;
            color: #2c3e50;
            text-align: center;
            margin-bottom: 30px;
            position: relative;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2);
            border-radius: 2px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 30px;
        }

        .form-group {
            position: relative;
            margin-bottom: 25px;
        }

        .form-label {
            display: block;
            font-weight: 500;
            color: #555;
            margin-bottom: 8px;
            font-size: 0.95rem;
            transition: color 0.3s ease;
        }

        .form-control {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid #e1e8ed;
            border-radius: 15px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 20px rgba(102, 126, 234, 0.2);
            transform: translateY(-2px);
        }

        .form-control:focus + .form-label {
            color: #667eea;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 15px 40px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
        }

        .btn-search {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
            box-shadow: 0 8px 25px rgba(255, 107, 107, 0.3);
        }

        .btn-search:hover {
            box-shadow: 0 15px 35px rgba(255, 107, 107, 0.4);
        }

        .results-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 25px;
            padding: 40px;
            box-shadow: 
                0 20px 60px rgba(0, 0, 0, 0.1),
                0 8px 25px rgba(0, 0, 0, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: slideInUp 0.8s ease-out;
        }

        .donor-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(247, 250, 252, 0.9) 100%);
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 20px;
            border: 1px solid rgba(102, 126, 234, 0.1);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .donor-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(135deg, #667eea, #764ba2);
        }

        .donor-card:hover {
            transform: translateX(10px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
        }

        .donor-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            align-items: center;
        }

        .info-item {
            display: flex;
            align-items: center;
            font-size: 1rem;
        }

        .info-label {
            font-weight: 600;
            color: #667eea;
            margin-right: 10px;
            min-width: 80px;
        }

        .info-value {
            color: #2c3e50;
            font-weight: 500;
        }

        .success {
            background: linear-gradient(135deg, #00b894, #00a085);
            color: white;
            padding: 15px 25px;
            border-radius: 15px;
            text-align: center;
            margin: 20px 0;
            font-weight: 500;
            box-shadow: 0 8px 25px rgba(0, 184, 148, 0.3);
            animation: slideInDown 0.5s ease-out;
        }

        .error {
            background: linear-gradient(135deg, #ff6b6b, #ee5a52);
            color: white;
            padding: 15px 25px;
            border-radius: 15px;
            text-align: center;
            margin: 20px 0;
            font-weight: 500;
            box-shadow: 0 8px 25px rgba(255, 107, 107, 0.3);
            animation: slideInDown 0.5s ease-out;
        }

        .blood-type-badge {
            display: inline-block;
            background: linear-gradient(135deg, #ff6b6b, #ee5a52);
            color: white;
            padding: 5px 15px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 0.9rem;
            margin-left: 10px;
        }

        .stats-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(247, 250, 252, 0.9));
            padding: 30px;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            font-size: 3rem;
            color: #667eea;
            margin-bottom: 15px;
        }

        .stat-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .stat-subtitle {
            color: #7f8c8d;
            font-size: 0.9rem;
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
                margin-top: -30px;
            }
            
            .card-container {
                padding: 25px;
                border-radius: 20px;
            }
            
            .form-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .donor-info {
                grid-template-columns: 1fr;
                gap: 10px;
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
            <div class="blood-icon">
                <i class="fas fa-tint"></i>
            </div>
            <h1 class="hero-title">Blood Find System</h1>
            <p class="hero-subtitle">Connect donors with those in need • Save lives together</p>
        </div>
    </div>

    <div class="main-container">
        <div class="stats-section">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-hand-holding-heart"></i>
                </div>
                <div class="stat-title">Be a Donor</div>
                <div class="stat-subtitle">Register to help save lives</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-search"></i>
                </div>
                <div class="stat-title">Find Donors</div>
                <div class="stat-subtitle">Search by location & blood type</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-title">Connect</div>
                <div class="stat-subtitle">Direct contact with donors</div>
            </div>
        </div>

        <div class="card-container">
            <h2 class="section-title">
                <i class="fas fa-user-plus" style="color: #667eea; margin-right: 15px;"></i>
                Register as Blood Donor
            </h2>
            
            <form action="blood_find.php" method="POST">
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label" for="location">
                            <i class="fas fa-map-marker-alt" style="margin-right: 8px; color: #667eea;"></i>
                            Location
                        </label>
                        <select name="location" class="form-control" required>
                            <option value="">Select your location</option>
                            <option value="Mirpur">Mirpur</option>
                            <option value="Dhanmondi">Dhanmondi</option>
                            <option value="Khilkhet">Khilkhet</option>
                            <option value="Uttara">Uttara</option>
                            <option value="Banani">Banani</option>
                            <option value="Gulshan">Gulshan</option>
                            <option value="Motijheel">Motijheel</option>
                            <option value="Badda">Badda</option>
                            <option value="Farmgate">Farmgate</option>
                            <option value="Mohammadpur">Mohammadpur</option>
                            <option value="Shahbagh">Shahbagh</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="blood_type">
                            <i class="fas fa-tint" style="margin-right: 8px; color: #ff6b6b;"></i>
                            Blood Type
                        </label>
                        <select name="blood_type" class="form-control" required>
                            <option value="">Select blood type</option>
                            <option value="A+">A+</option>
                            <option value="A-">A-</option>
                            <option value="B+">B+</option>
                            <option value="B-">B-</option>
                            <option value="AB+">AB+</option>
                            <option value="AB-">AB-</option>
                            <option value="O+">O+</option>
                            <option value="O-">O-</option>
                        </select>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label" for="phone_number">
                            <i class="fas fa-phone" style="margin-right: 8px; color: #667eea;"></i>
                            Phone Number
                        </label>
                        <input type="text" name="phone_number" class="form-control" required placeholder="Enter your phone number">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="last_donation_date">
                            <i class="fas fa-calendar-alt" style="margin-right: 8px; color: #667eea;"></i>
                            Last Donation Date (Optional)
                        </label>
                        <input type="date" name="last_donation_date" class="form-control">
                    </div>
                </div>

                <div style="text-align: center; margin-top: 30px;">
                    <button type="submit" name="submit_donor" class="btn-primary">
                        <i class="fas fa-heart" style="margin-right: 10px;"></i>
                        Register as Donor
                    </button>
                </div>
            </form>
        </div>

        <div class="card-container">
            <h2 class="section-title">
                <i class="fas fa-search" style="color: #ff6b6b; margin-right: 15px;"></i>
                Find Blood Donors
            </h2>
            
            <form action="blood_find.php" method="POST">
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label" for="search_location">
                            <i class="fas fa-map-marker-alt" style="margin-right: 8px; color: #667eea;"></i>
                            Search Location
                        </label>
                        <select name="search_location" class="form-control" required>
                            <option value="">Select location</option>
                            <option value="Mirpur">Mirpur</option>
                            <option value="Dhanmondi">Dhanmondi</option>
                            <option value="Khilkhet">Khilkhet</option>
                            <option value="Uttara">Uttara</option>
                            <option value="Banani">Banani</option>
                            <option value="Gulshan">Gulshan</option>
                            <option value="Motijheel">Motijheel</option>
                            <option value="Badda">Badda</option>
                            <option value="Farmgate">Farmgate</option>
                            <option value="Mohammadpur">Mohammadpur</option>
                            <option value="Shahbagh">Shahbagh</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="search_blood_type">
                            <i class="fas fa-tint" style="margin-right: 8px; color: #ff6b6b;"></i>
                            Required Blood Type
                        </label>
                        <select name="search_blood_type" class="form-control" required>
                            <option value="">Select blood type</option>
                            <option value="A+">A+</option>
                            <option value="A-">A-</option>
                            <option value="B+">B+</option>
                            <option value="B-">B-</option>
                            <option value="AB+">AB+</option>
                            <option value="AB-">AB-</option>
                            <option value="O+">O+</option>
                            <option value="O-">O-</option>
                        </select>
                    </div>
                </div>

                <div style="text-align: center; margin-top: 30px;">
                    <button type="submit" name="search" class="btn-primary btn-search">
                        <i class="fas fa-search" style="margin-right: 10px;"></i>
                        Search Donors
                    </button>
                </div>
            </form>
        </div>

        <?php if (!empty($search_results)): ?>
            <div class="results-container">
                <h3 class="section-title">
                    <i class="fas fa-users" style="color: #00b894; margin-right: 15px;"></i>
                    Available Donors
                </h3>
                <?php foreach ($search_results as $donor): ?>
                    <div class="donor-card">
                        <div class="donor-info">
                            <div class="info-item">
                                <span class="info-label">
                                    <i class="fas fa-user"></i> Name:
                                </span>
                                <span class="info-value"><?php echo $donor['name']; ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">
                                    <i class="fas fa-phone"></i> Phone:
                                </span>
                                <span class="info-value"><?php echo $donor['phone_number']; ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">
                                    <i class="fas fa-calendar"></i> Last Donation:
                                </span>
                                <span class="info-value"><?php echo $donor['last_donation_date']; ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
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