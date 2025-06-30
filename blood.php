<?php
session_start();
include 'db_connect.php';

$student_id = $_SESSION['student_id'];

// Handle the form submission for a new blood donation request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_request'])) {
        // Delete the request if 'delete_request' is set
        $request_id = $_POST['request_id'];
        $delete_sql = "DELETE FROM blood_donation WHERE request_id = '$request_id' AND student_id = '$student_id'";
        
        if (mysqli_query($conn, $delete_sql)) {
            echo "Blood donation request deleted successfully!";
        } else {
            echo "Error deleting request: " . mysqli_error($conn);
        }
    } else {
        // Insert new blood donation request
        $blood_type = $_POST['blood_type'];
        $patient_problem = $_POST['patient_problem'];
        $location = $_POST['location'];
        $phone_number = $_POST['phone_number'];
        
        $insert_sql = "INSERT INTO blood_donation (student_id, blood_type, patient_problem, location, phone_number) 
                VALUES ('$student_id', '$blood_type', '$patient_problem', '$location', '$phone_number')";
        
        if (mysqli_query($conn, $insert_sql)) {
            echo "Blood Donation Request submitted!";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}

// Fetch all accepted blood donation requests to show on every profile
$accepted_sql = "SELECT bd.*, s.name FROM blood_donation bd 
                 JOIN students s ON bd.student_id = s.student_id 
                 WHERE bd.status = 'accepted'";
$accepted_result = mysqli_query($conn, $accepted_sql);

// Fetch the logged-in user's blood donation requests
$user_requests_sql = "SELECT * FROM blood_donation WHERE student_id = '$student_id'";
$user_requests_result = mysqli_query($conn, $user_requests_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduHub | Blood Donation</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #dc2626 0%, #991b1b 50%, #7f1d1d 100%);
            min-height: 100vh;
            color: #333;
            position: relative;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="heartbeat" x="0" y="0" width="100" height="100" patternUnits="userSpaceOnUse"><path d="M0 50 L20 50 L25 30 L30 70 L35 20 L40 80 L45 50 L100 50" stroke="rgba(255,255,255,0.05)" stroke-width="1" fill="none"/></pattern></defs><rect width="100" height="100" fill="url(%23heartbeat)"/></svg>') repeat;
            z-index: -2;
        }

        .hero-section {
            background: linear-gradient(135deg, rgba(220, 38, 38, 0.95) 0%, rgba(153, 27, 27, 0.95) 100%);
            color: white;
            padding: 4rem 0 2rem 0;
            text-align: center;
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
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: pulse 4s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1) rotate(0deg); opacity: 0.3; }
            50% { transform: scale(1.1) rotate(180deg); opacity: 0.1; }
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-title {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .hero-subtitle {
            font-size: 1.3rem;
            font-weight: 300;
            opacity: 0.9;
            margin-bottom: 2rem;
        }

        .content-container {
            margin-top: -2rem;
            position: relative;
            z-index: 1;
        }

        .main-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            margin-bottom: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .form-container {
            padding: 2.5rem;
            background: linear-gradient(135deg, #fef2f2 0%, #ffffff 100%);
        }

        .section-header {
            font-size: 1.8rem;
            font-weight: 600;
            color: #dc2626;
            margin-bottom: 2rem;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-control {
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: white;
        }

        .form-control:focus {
            border-color: #dc2626;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
            outline: none;
        }

        .blood-type-select {
            background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
            color: white;
            border: none;
            font-weight: 600;
        }

        .blood-type-select option {
            background: white;
            color: #333;
            padding: 0.5rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
            border: none;
            border-radius: 12px;
            padding: 0.875rem 2rem;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(220, 38, 38, 0.3);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #b91c1c 0%, #7f1d1d 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(220, 38, 38, 0.4);
        }

        .request-list {
            padding: 2.5rem;
            background: white;
        }

        .request-card {
            background: linear-gradient(135deg, #ffffff 0%, #fef2f2 100%);
            border: 2px solid #fee2e2;
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .request-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #dc2626, #ef4444, #f87171);
        }

        .request-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(220, 38, 38, 0.15);
            border-color: #fecaca;
        }

        .blood-type-badge {
            background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 700;
            font-size: 1.1rem;
            display: inline-block;
            margin-bottom: 1rem;
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
        }

        .request-info {
            display: grid;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .info-item {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
        }

        .info-icon {
            color: #dc2626;
            font-size: 1rem;
            margin-top: 0.25rem;
            min-width: 20px;
        }

        .info-label {
            font-weight: 600;
            color: #374151;
            min-width: 120px;
        }

        .info-value {
            color: #6b7280;
            flex: 1;
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.875rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-accepted {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            color: white;
        }

        .status-pending {
            background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
            color: white;
        }

        .btn-danger {
            background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
            border: none;
            border-radius: 10px;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .btn-danger:hover {
            background: linear-gradient(135deg, #b91c1c 0%, #7f1d1d 100%);
            transform: translateY(-2px);
        }

        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            color: #6b7280;
        }

        .empty-icon {
            font-size: 3rem;
            color: #d1d5db;
            margin-bottom: 1rem;
        }

        .urgent-banner {
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            border: 2px solid #fecaca;
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            text-align: center;
        }

        .urgent-text {
            color: #dc2626;
            font-weight: 600;
            font-size: 1.1rem;
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.2rem;
            }
            
            .hero-subtitle {
                font-size: 1.1rem;
            }
            
            .form-container,
            .request-list {
                padding: 1.5rem;
            }
            
            .content-container {
                margin-top: 1rem;
            }
        }

        .fade-in {
            animation: fadeIn 0.8s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .heartbeat {
            animation: heartbeat 1.5s ease-in-out infinite;
        }

        @keyframes heartbeat {
            0% { transform: scale(1); }
            25% { transform: scale(1.1); }
            50% { transform: scale(1); }
            75% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
    </style>
</head>
<body>

<iframe src="curved-background.html"
          style="position: fixed; z-index: -1; border: none; width: 100vw; height: 100vh;">
  </iframe>

    <!-- Include Navbar -->
    <?php include 'navbar.php'; ?>

    <!-- Hero Section -->
    <div class="hero-section">
        <div class="hero-content">
            <h1 class="hero-title">
                <i class="fas fa-tint heartbeat"></i>
                Blood Donation Hub
            </h1>
            <p class="hero-subtitle">Save Lives • Share Hope • Make a Difference</p>
        </div>
    </div>

    <div class="container content-container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Urgent Banner -->
                <div class="urgent-banner fade-in">
                    <div class="urgent-text">
                        <i class="fas fa-exclamation-triangle"></i>
                        Every 2 seconds, someone needs blood. Your donation can save up to 3 lives!
                    </div>
                </div>

                <!-- Blood Donation Request Form -->
                <div class="main-card fade-in">
                    <div class="form-container">
                        <h2 class="section-header">
                            <i class="fas fa-hand-holding-heart"></i>
                            Create Blood Donation Request
                        </h2>
                        <form action="blood.php" method="POST">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="blood_type">
                                            <i class="fas fa-tint"></i>
                                            Blood Type Required:
                                        </label>
                                        <select name="blood_type" class="form-control blood-type-select" required>
                                            <option value="A+">A+ (A Positive)</option>
                                            <option value="A-">A- (A Negative)</option>
                                            <option value="B+">B+ (B Positive)</option>
                                            <option value="B-">B- (B Negative)</option>
                                            <option value="AB+">AB+ (AB Positive)</option>
                                            <option value="AB-">AB- (AB Negative)</option>
                                            <option value="O+">O+ (O Positive)</option>
                                            <option value="O-">O- (O Negative)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone_number">
                                            <i class="fas fa-phone"></i>
                                            Contact Number:
                                        </label>
                                        <input type="text" name="phone_number" class="form-control" placeholder="Enter your phone number" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="patient_problem">
                                    <i class="fas fa-notes-medical"></i>
                                    Patient's Medical Condition:
                                </label>
                                <textarea name="patient_problem" class="form-control" rows="4" placeholder="Please describe the patient's condition and urgency level..." required></textarea>
                            </div>

                            <div class="form-group">
                                <label for="location">
                                    <i class="fas fa-map-marker-alt"></i>
                                    Hospital/Location:
                                </label>
                                <input type="text" name="location" class="form-control" placeholder="Enter hospital name and address" required>
                            </div>

                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-paper-plane"></i>
                                Submit Blood Request
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Accepted Blood Donation Requests -->
                <div class="main-card fade-in">
                    <div class="request-list">
                        <h3 class="section-header">
                            <i class="fas fa-check-circle"></i>
                            Active Blood Requests
                        </h3>
                        <?php if (mysqli_num_rows($accepted_result) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($accepted_result)): ?>
                                <div class="request-card">
                                    <div class="blood-type-badge">
                                        <i class="fas fa-tint"></i>
                                        <?= htmlspecialchars($row['blood_type']) ?>
                                    </div>
                                    
                                    <div class="request-info">
                                        <div class="info-item">
                                            <i class="info-icon fas fa-notes-medical"></i>
                                            <span class="info-label">Condition:</span>
                                            <span class="info-value"><?= htmlspecialchars($row['patient_problem']) ?></span>
                                        </div>
                                        <div class="info-item">
                                            <i class="info-icon fas fa-map-marker-alt"></i>
                                            <span class="info-label">Location:</span>
                                            <span class="info-value"><?= htmlspecialchars($row['location']) ?></span>
                                        </div>
                                        <div class="info-item">
                                            <i class="info-icon fas fa-phone"></i>
                                            <span class="info-label">Contact:</span>
                                            <span class="info-value"><?= htmlspecialchars($row['phone_number']) ?></span>
                                        </div>
                                        <div class="info-item">
                                            <i class="info-icon fas fa-user"></i>
                                            <span class="info-label">Requested by:</span>
                                            <span class="info-value"><?= htmlspecialchars($row['name']) ?></span>
                                        </div>
                                        <div class="info-item">
                                            <i class="info-icon fas fa-check"></i>
                                            <span class="info-label">Status:</span>
                                            <span class="status-badge status-accepted"><?= htmlspecialchars($row['status']) ?></span>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <div class="empty-state">
                                <i class="fas fa-heart empty-icon"></i>
                                <h4>No Active Requests</h4>
                                <p>Currently no accepted blood donation requests available.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- User's Blood Donation Requests -->
                <div class="main-card fade-in">
                    <div class="request-list">
                        <h3 class="section-header">
                            <i class="fas fa-user-circle"></i>
                            Your Blood Requests
                        </h3>
                        <?php if (mysqli_num_rows($user_requests_result) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($user_requests_result)): ?>
                                <div class="request-card">
                                    <div class="blood-type-badge">
                                        <i class="fas fa-tint"></i>
                                        <?= htmlspecialchars($row['blood_type']) ?>
                                    </div>
                                    
                                    <div class="request-info">
                                        <div class="info-item">
                                            <i class="info-icon fas fa-notes-medical"></i>
                                            <span class="info-label">Condition:</span>
                                            <span class="info-value"><?= htmlspecialchars($row['patient_problem']) ?></span>
                                        </div>
                                        <div class="info-item">
                                            <i class="info-icon fas fa-map-marker-alt"></i>
                                            <span class="info-label">Location:</span>
                                            <span class="info-value"><?= htmlspecialchars($row['location']) ?></span>
                                        </div>
                                        <div class="info-item">
                                            <i class="info-icon fas fa-phone"></i>
                                            <span class="info-label">Contact:</span>
                                            <span class="info-value"><?= htmlspecialchars($row['phone_number']) ?></span>
                                        </div>
                                        <div class="info-item">
                                            <i class="info-icon fas fa-info-circle"></i>
                                            <span class="info-label">Status:</span>
                                            <span class="status-badge <?= $row['status'] == 'accepted' ? 'status-accepted' : 'status-pending' ?>">
                                                <?= htmlspecialchars($row['status']) ?>
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Delete request option for user's own requests -->
                                    <form action="blood.php" method="POST" class="mt-3">
                                        <input type="hidden" name="request_id" value="<?= $row['request_id'] ?>">
                                        <button type="submit" name="delete_request" class="btn btn-danger" 
                                                onclick="return confirm('Are you sure you want to delete this blood donation request?')">
                                            <i class="fas fa-trash-alt"></i>
                                            Remove Request
                                        </button>
                                    </form>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <div class="empty-state">
                                <i class="fas fa-clipboard-list empty-icon"></i>
                                <h4>No Requests Yet</h4>
                                <p>You haven't submitted any blood donation requests.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
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
    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>