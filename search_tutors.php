<?php
require 'db_connect.php';

$subjects = ['Science', 'Math', 'English', 'Biology', 'Economics', 'Chemistry', 'Physics'];

$results = [];
$search = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $class = isset($_POST['class']) ? $_POST['class'] : '';
    $subject = isset($_POST['subject']) ? $_POST['subject'] : '';

    if ($class && $subject) {
        $sql = "SELECT t.tutor_id, s.name AS tutor_name, t.class_range_start, t.class_range_end, t.subject, t.location, t.phone_number, t.created_at 
                FROM tutors t
                JOIN students s ON t.student_id = s.student_id
                WHERE t.class_range_start <= ? 
                AND t.class_range_end >= ? 
                AND t.subject = ? 
                ORDER BY t.class_range_start ASC";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iis", $class, $class, $subject);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $results = $result->fetch_all(MYSQLI_ASSOC);
            $search = true;
        } else {
            echo "<p class='error'>Error: " . $stmt->error . "</p>";
        }

        $stmt->close();
    } else {
        echo "<p class='error'>Please select all fields.</p>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Tutors</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
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
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.05)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.05)"/><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.03)"/><circle cx="20" cy="80" r="0.5" fill="rgba(255,255,255,0.03)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            z-index: -1;
            pointer-events: none;
        }

        .hero-section {
            padding: 8rem 2rem 4rem;
            text-align: center;
            position: relative;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
        }

        .floating-shapes {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            overflow: hidden;
        }

        .shape {
            position: absolute;
            opacity: 0.1;
            animation: float 8s ease-in-out infinite;
        }

        .shape:nth-child(1) {
            top: 20%;
            left: 10%;
            animation-delay: 0s;
            color: #ffd700;
        }

        .shape:nth-child(2) {
            top: 60%;
            right: 15%;
            animation-delay: 2s;
            color: #ff6b6b;
        }

        .shape:nth-child(3) {
            bottom: 30%;
            left: 20%;
            animation-delay: 4s;
            color: #4ecdc4;
        }

        .shape:nth-child(4) {
            top: 40%;
            right: 40%;
            animation-delay: 6s;
            color: #45b7d1;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-30px) rotate(180deg); }
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-icon {
            font-size: 5rem;
            color: #ffd700;
            margin-bottom: 1.5rem;
            text-shadow: 0 0 30px rgba(255, 215, 0, 0.5);
            animation: glow 3s ease-in-out infinite alternate;
        }

        @keyframes glow {
            from { text-shadow: 0 0 30px rgba(255, 215, 0, 0.5); }
            to { text-shadow: 0 0 40px rgba(255, 215, 0, 0.8), 0 0 60px rgba(255, 215, 0, 0.6); }
        }

        .hero-title {
            font-size: 4rem;
            font-weight: 800;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, #fff, #e0e6ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: none;
            line-height: 1.2;
        }

        .hero-subtitle {
            font-size: 1.4rem;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 400;
            max-width: 600px;
            margin: 0 auto;
            line-height: 1.6;
        }

        .search-container {
            max-width: 800px;
            margin: -2rem auto 4rem;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 25px;
            padding: 3rem;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.15);
            position: relative;
            overflow: hidden;
        }

        .search-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2, #667eea);
            background-size: 200% 100%;
            animation: shimmer 3s ease-in-out infinite;
        }

        @keyframes shimmer {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .search-title {
            text-align: center;
            color: #2d3748;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 2rem;
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
            margin-bottom: 2rem;
            position: relative;
        }

        .form-label {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 1rem;
            font-size: 1.1rem;
        }

        .form-label i {
            color: #667eea;
            font-size: 1.2rem;
        }

        .form-control {
            border: 2px solid #e2e8f0;
            border-radius: 15px;
            padding: 1.2rem 1.5rem;
            font-size: 1rem;
            background: #f8fafc;
            color: #2d3748;
            transition: all 0.3s ease;
            font-family: inherit;
            font-weight: 500;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            transform: translateY(-2px);
        }

        .form-control::placeholder {
            color: #a0aec0;
            font-weight: 400;
        }

        .search-btn-container {
            text-align: center;
            margin-top: 2.5rem;
        }

        .btn-search {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            padding: 1.2rem 3rem;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1.1rem;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
            position: relative;
            overflow: hidden;
        }

        .btn-search::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .btn-search:hover::before {
            left: 100%;
        }

        .btn-search:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
        }

        .btn-search:active {
            transform: translateY(-1px);
        }

        .results-section {
            max-width: 1200px;
            margin: 0 auto 4rem;
            padding: 0 2rem;
        }

        .results-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .results-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: white;
            margin-bottom: 0.5rem;
        }

        .results-count {
            font-size: 1.2rem;
            color: rgba(255, 255, 255, 0.8);
        }

        .tutors-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .tutor-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .tutor-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2);
        }

        .tutor-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        }

        .tutor-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .tutor-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            font-weight: 700;
            flex-shrink: 0;
        }

        .tutor-info h3 {
            margin: 0 0 0.5rem 0;
            color: #2d3748;
            font-size: 1.3rem;
            font-weight: 600;
        }

        .tutor-id {
            color: #667eea;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .tutor-details {
            space-y: 1rem;
        }

        .detail-item {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            margin-bottom: 1rem;
            padding: 0.8rem;
            background: #f8fafc;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .detail-item:hover {
            background: #e2e8f0;
        }

        .detail-icon {
            width: 35px;
            height: 35px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            flex-shrink: 0;
        }

        .subject-icon {
            background: linear-gradient(135deg, #48bb78, #38b2ac);
            color: white;
        }

        .class-icon {
            background: linear-gradient(135deg, #ed8936, #f56500);
            color: white;
        }

        .location-icon {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }

        .phone-icon {
            background: linear-gradient(135deg, #e53e3e, #c53030);
            color: white;
        }

        .date-icon {
            background: linear-gradient(135deg, #805ad5, #6b46c1);
            color: white;
        }

        .detail-text {
            color: #4a5568;
            font-weight: 500;
            flex: 1;
        }

        .no-results {
            text-align: center;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 4rem 2rem;
            margin: 2rem auto;
            max-width: 600px;
        }

        .no-results-icon {
            font-size: 4rem;
            color: rgba(255, 255, 255, 0.6);
            margin-bottom: 1.5rem;
        }

        .no-results h3 {
            color: white;
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .no-results p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.1rem;
            line-height: 1.6;
        }

        .error {
            background: linear-gradient(135deg, rgba(245, 101, 101, 0.1), rgba(229, 62, 62, 0.1));
            color: #e53e3e;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            margin: 1rem 0;
            border-left: 4px solid #e53e3e;
            font-weight: 500;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }

            .hero-subtitle {
                font-size: 1.1rem;
            }

            .search-container {
                margin: -1rem 1rem 2rem;
                padding: 2rem;
                border-radius: 20px;
            }

            .tutors-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .tutor-card {
                padding: 1.5rem;
            }

            .hero-section {
                padding: 6rem 1rem 3rem;
            }
        }

        @media (max-width: 480px) {
            .hero-title {
                font-size: 2rem;
            }

            .search-container {
                padding: 1.5rem;
            }

            .btn-search {
                padding: 1rem 2rem;
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="hero-section">
    <div class="floating-shapes">
        <div class="shape"><i class="fas fa-graduation-cap" style="font-size: 3rem;"></i></div>
        <div class="shape"><i class="fas fa-book" style="font-size: 2.5rem;"></i></div>
        <div class="shape"><i class="fas fa-chalkboard-teacher" style="font-size: 2rem;"></i></div>
        <div class="shape"><i class="fas fa-pencil-alt" style="font-size: 2.8rem;"></i></div>
    </div>
    
    <div class="hero-content">
        <div class="hero-icon">
            <i class="fas fa-search"></i>
        </div>
        <h1 class="hero-title">Find Your Perfect Tutor</h1>
        <p class="hero-subtitle">Connect with experienced tutors who match your learning needs and help you achieve academic excellence</p>
    </div>
</div>

<div class="search-container">
    <h2 class="search-title">
        <i class="fas fa-filter"></i> Search Tutors
    </h2>
    
    <form method="post" action="search_tutors.php">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="class" class="form-label">
                        <i class="fas fa-layer-group"></i> Select Your Class
                    </label>
                    <select id="class" name="class" class="form-control" required>
                        <option value="">Choose your class level</option>
                        <?php for ($i = 1; $i <= 12; $i++) echo "<option value=\"$i\">Class $i</option>"; ?>
                    </select>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="form-group">
                    <label for="subject" class="form-label">
                        <i class="fas fa-book-open"></i> Choose Subject
                    </label>
                    <select id="subject" name="subject" class="form-control" required>
                        <option value="">Select your subject</option>
                        <?php foreach ($subjects as $subj) echo "<option value=\"$subj\">$subj</option>"; ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="search-btn-container">
            <button type="submit" class="btn-search">
                <i class="fas fa-search"></i> Find Tutors
            </button>
        </div>
    </form>
</div>

<?php if ($search): ?>
    <div class="results-section">
        <?php if (count($results) > 0): ?>
            <div class="results-header">
                <h2 class="results-title">Available Tutors</h2>
                <p class="results-count">Found <?= count($results) ?> tutor<?= count($results) > 1 ? 's' : '' ?> matching your criteria</p>
            </div>
            
            <div class="tutors-grid">
                <?php foreach ($results as $tutor): ?>
                    <div class="tutor-card">
                        <div class="tutor-header">
                            <div class="tutor-avatar">
                                <?= strtoupper(substr(htmlspecialchars($tutor['tutor_name']), 0, 1)) ?>
                            </div>
                            <div class="tutor-info">
                                <h3><?= htmlspecialchars($tutor['tutor_name']) ?></h3>
                                <div class="tutor-id">ID: <?= htmlspecialchars($tutor['tutor_id']) ?></div>
                            </div>
                        </div>
                        
                        <div class="tutor-details">
                            <div class="detail-item">
                                <div class="detail-icon subject-icon">
                                    <i class="fas fa-book"></i>
                                </div>
                                <div class="detail-text"><?= htmlspecialchars($tutor['subject']) ?></div>
                            </div>
                            
                            <div class="detail-item">
                                <div class="detail-icon class-icon">
                                    <i class="fas fa-layer-group"></i>
                                </div>
                                <div class="detail-text">Class <?= htmlspecialchars($tutor['class_range_start']) ?> - <?= htmlspecialchars($tutor['class_range_end']) ?></div>
                            </div>
                            
                            <div class="detail-item">
                                <div class="detail-icon location-icon">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div class="detail-text"><?= htmlspecialchars($tutor['location']) ?></div>
                            </div>
                            
                            <div class="detail-item">
                                <div class="detail-icon phone-icon">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <div class="detail-text"><?= htmlspecialchars($tutor['phone_number']) ?></div>
                            </div>
                            
                            <div class="detail-item">
                                <div class="detail-icon date-icon">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                <div class="detail-text">Joined: <?= date('M j, Y', strtotime($tutor['created_at'])) ?></div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-results">
                <div class="no-results-icon">
                    <i class="fas fa-search-minus"></i>
                </div>
                <h3>No Tutors Found</h3>
                <p>We couldn't find any tutors matching your search criteria. Try adjusting your class or subject selection, or check back later as new tutors join our platform regularly.</p>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>

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