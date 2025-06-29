<?php 
session_start(); 
include 'db_connect.php';  

// Ensure user is logged in 
if (!isset($_SESSION['student_id'])) {     
    echo "Error: User not logged in.";     
    exit(); 
}  

$logged_in_student_id = $_SESSION['student_id'];  

// Fetch courses the logged-in student has already purchased 
$purchased_sql = " 
SELECT i.full_name, c.course_name, p.purchase_date, i.video_upload_path 
FROM purchased_courses p 
JOIN courses c ON p.course_id = c.course_id 
JOIN instructors i ON p.instructor_id = i.instructor_id 
WHERE p.buyer_id = $logged_in_student_id 
";  

$purchased_result = $conn->query($purchased_sql); 
?>  

<!DOCTYPE html> 
<html lang="en"> 
<head>     
    <meta charset="UTF-8">     
    <meta name="viewport" content="width=device-width, initial-scale=1.0">     
    <title>My Purchased Courses</title>     
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #333;
        }

        .main-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            margin: 2rem auto;
            padding: 0;
            overflow: hidden;
            max-width: 1200px;
        }

        .header-section {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: white;
            padding: 3rem 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .header-section::before {
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

        .header-content {
            position: relative;
            z-index: 2;
        }

        .page-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .page-subtitle {
            font-size: 1.2rem;
            font-weight: 300;
            opacity: 0.9;
        }

        .content-section {
            padding: 2rem;
        }

        .courses-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .course-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.2);
            position: relative;
        }

        .course-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .course-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #4f46e5, #7c3aed, #ec4899);
        }

        .card-body {
            padding: 1.5rem;
        }

        .instructor-info {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }

        .instructor-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            margin-right: 0.75rem;
        }

        .instructor-name {
            font-weight: 600;
            color: #374151;
            font-size: 0.9rem;
        }

        .course-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #111827;
            margin-bottom: 0.75rem;
            line-height: 1.4;
        }

        .purchase-date {
            display: flex;
            align-items: center;
            color: #6b7280;
            font-size: 0.875rem;
            margin-bottom: 1.25rem;
        }

        .purchase-date i {
            margin-right: 0.5rem;
            color: #4f46e5;
        }

        .watch-btn {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            transition: all 0.3s ease;
            width: 100%;
            justify-content: center;
        }

        .watch-btn:hover {
            background: linear-gradient(135deg, #3730a3 0%, #581c87 100%);
            color: white;
            text-decoration: none;
            transform: translateY(-2px);
        }

        .watch-btn i {
            margin-right: 0.5rem;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #6b7280;
        }

        .empty-icon {
            font-size: 4rem;
            color: #d1d5db;
            margin-bottom: 1rem;
        }

        .empty-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #374151;
        }

        .empty-subtitle {
            font-size: 1rem;
            margin-bottom: 2rem;
        }

        .browse-btn {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            color: white;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 10px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            transition: all 0.3s ease;
        }

        .browse-btn:hover {
            background: linear-gradient(135deg, #047857 0%, #065f46 100%);
            color: white;
            text-decoration: none;
            transform: translateY(-2px);
        }

        .stats-bar {
            background: rgba(79, 70, 229, 0.1);
            padding: 1rem 2rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            text-align: center;
        }

        .stats-item {
            display: inline-block;
            margin: 0 1rem;
        }

        .stats-number {
            font-size: 1.5rem;
            font-weight: 700;
            color: #4f46e5;
        }

        .stats-label {
            font-size: 0.875rem;
            color: #6b7280;
            margin-top: 0.25rem;
        }

        @media (max-width: 768px) {
            .page-title {
                font-size: 2rem;
            }
            
            .courses-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .main-container {
                margin: 1rem;
                border-radius: 16px;
            }
            
            .header-section {
                padding: 2rem 1rem;
            }
            
            .content-section {
                padding: 1rem;
            }
        }

        .fade-in {
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head> 
<body> 
    <?php include 'navbar.php'; ?> 
    
    <div class="container-fluid">
        <div class="main-container fade-in">
            <div class="header-section">
                <div class="header-content">
                    <h1 class="page-title">
                        <i class="fas fa-graduation-cap"></i>
                        My Learning Journey
                    </h1>
                    <p class="page-subtitle">Access your purchased courses and continue learning</p>
                </div>
            </div>
            
            <div class="content-section">
                <?php if ($purchased_result && $purchased_result->num_rows > 0): ?>
                    <div class="stats-bar">
                        <div class="stats-item">
                            <div class="stats-number"><?php echo $purchased_result->num_rows; ?></div>
                            <div class="stats-label">Courses Owned</div>
                        </div>
                        <div class="stats-item">
                            <div class="stats-number">∞</div>
                            <div class="stats-label">Lifetime Access</div>
                        </div>
                    </div>
                    
                    <div class="courses-grid">
                        <?php while ($row = $purchased_result->fetch_assoc()): ?>
                            <div class="course-card">
                                <div class="card-body">
                                    <div class="instructor-info">
                                        <div class="instructor-avatar">
                                            <?php echo strtoupper(substr($row['full_name'], 0, 1)); ?>
                                        </div>
                                        <div class="instructor-name">
                                            <?php echo htmlspecialchars($row['full_name']); ?>
                                        </div>
                                    </div>
                                    
                                    <h3 class="course-title">
                                        <?php echo htmlspecialchars($row['course_name']); ?>
                                    </h3>
                                    
                                    <div class="purchase-date">
                                        <i class="fas fa-calendar-alt"></i>
                                        Purchased on <?php echo date('M j, Y', strtotime($row['purchase_date'])); ?>
                                    </div>
                                    
                                    <a href="<?php echo htmlspecialchars($row['video_upload_path']); ?>" 
                                       target="_blank" 
                                       class="watch-btn">
                                        <i class="fas fa-play"></i>
                                        Start Learning
                                    </a>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-book-open empty-icon"></i>
                        <h3 class="empty-title">No Courses Yet</h3>
                        <p class="empty-subtitle">You haven't purchased any courses yet. Start your learning journey today!</p>
                        <a href="courses.php" class="browse-btn">
                            <i class="fas fa-search"></i>
                            Browse Courses
                        </a>
                    </div>
                <?php endif; ?>
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

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script> 
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script> 
</body> 
</html>  

<?php $conn->close(); ?>