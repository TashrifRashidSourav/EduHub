<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['student_id'])) {
    echo "Error: User not logged in.";
    exit();
}

$logged_in_student_id = $_SESSION['student_id'];

if (isset($_POST['buy_course'])) {
    $course_id = intval($_POST['course_id']);
    $instructor_id = intval($_POST['instructor_id']);

    $stmt = $conn->prepare("INSERT INTO purchased_courses (course_id, instructor_id, buyer_id) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $course_id, $instructor_id, $logged_in_student_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Course successfully purchased!";
    } else {
        $_SESSION['error'] = "Error purchasing course.";
    }
    $stmt->close();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

$sql = "SELECT i.instructor_id, i.full_name, i.job_experience, i.available_courses, i.expected_money, i.class_hour, i.pdf_upload_path, i.video_upload_path, c.course_id, c.course_name 
        FROM instructors i 
        JOIN courses c ON i.instructor_id = c.instructor_id 
        WHERE i.student_id != ? 
        AND i.status = 'approved' 
        AND c.course_id NOT IN (SELECT course_id FROM purchased_courses WHERE buyer_id = ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $logged_in_student_id, $logged_in_student_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>EduVerse | Explore Courses</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary: #667eea;
      --secondary: #764ba2;
      --glass-bg: rgba(255, 255, 255, 0.9);
      --card-bg: rgba(255, 255, 255, 0.7);
    }

    body {
      font-family: 'Poppins', sans-serif;
      background: #f0f2f5;
      min-height: 100vh;
      overflow-x: hidden;
      position: relative;
    }

    /* Animated Geometric Background */
    .bg-shapes {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: -1;
        overflow: hidden;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    }

    .shape {
        position: absolute;
        filter: blur(50px);
        opacity: 0.6;
        animation: float 20s infinite;
    }

    .shape-1 {
        top: -10%;
        left: -10%;
        width: 500px;
        height: 500px;
        background: #a18cd1;
        border-radius: 50%;
        animation-delay: 0s;
    }

    .shape-2 {
        bottom: -10%;
        right: -10%;
        width: 600px;
        height: 600px;
        background: #fbc2eb;
        border-radius: 40% 60% 70% 30% / 40% 50% 60% 50%;
        animation-delay: -5s;
    }

    .shape-3 {
        top: 40%;
        left: 40%;
        width: 300px;
        height: 300px;
        background: #8fd3f4;
        border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
        animation-delay: -10s;
    }

    @keyframes float {
        0%, 100% { transform: translate(0, 0) rotate(0deg); }
        33% { transform: translate(30px, -50px) rotate(10deg); }
        66% { transform: translate(-20px, 20px) rotate(-5deg); }
    }

    /* Main Container */
    .glass-container {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 20px;
        padding: 2rem;
        margin-top: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.1);
    }

    .page-title {
        font-weight: 700;
        color: #2c3e50;
        text-align: center;
        margin-bottom: 3rem;
        position: relative;
        display: inline-block;
        left: 50%;
        transform: translateX(-50%);
    }

    .page-title::after {
        content: '';
        display: block;
        width: 60%;
        height: 4px;
        background: linear-gradient(90deg, var(--primary), var(--secondary));
        margin: 10px auto 0;
        border-radius: 2px;
    }

    /* Grid Layout */
    .courses-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 2rem;
    }

    /* Course Card */
    .course-card {
        background: var(--card-bg);
        backdrop-filter: blur(5px);
        border-radius: 15px;
        border: 1px solid rgba(255, 255, 255, 0.4);
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        display: flex;
        flex-direction: column;
        height: 100%;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        position: relative;
    }

    .course-card:hover {
        transform: translateY(-10px) scale(1.02);
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        background: rgba(255, 255, 255, 0.9);
        border-color: rgba(255, 255, 255, 0.8);
    }

    .card-header-gradient {
        height: 100px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        clip-path: polygon(0 0, 100% 0, 100% 80%, 0 100%);
    }

    .course-icon-wrapper {
        width: 60px;
        height: 60px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        position: absolute;
        bottom: -10px;
    }

    .course-icon-wrapper i {
        font-size: 1.5rem;
        background: -webkit-linear-gradient(45deg, #667eea, #764ba2);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .course-body {
        padding: 2.5rem 1.5rem 1.5rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .course-title {
        font-weight: 600;
        font-size: 1.25rem;
        color: #2d3436;
        margin-bottom: 1rem;
        text-align: center;
        line-height: 1.4;
    }

    .instructor-badge {
        background: rgba(102, 126, 234, 0.1);
        color: #667eea;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        margin: 0 auto 1.5rem;
    }

    .course-meta {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        margin-bottom: 1.5rem;
        background: rgba(255,255,255,0.5);
        padding: 10px;
        border-radius: 10px;
    }

    .meta-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        font-size: 0.85rem;
        color: #636e72;
    }

    .meta-item i {
        font-size: 1rem;
        margin-bottom: 4px;
        color: #764ba2;
    }

    .price-tag {
        font-size: 1.2rem;
        font-weight: 700;
        color: #2c3e50;
        text-align: center;
        margin-bottom: 1.5rem;
    }

    .price-tag span {
        font-size: 0.9rem;
        color: #95a5a6;
        font-weight: 400;
    }

    .btn-buy {
        margin-top: auto;
        border: none;
        background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 12px;
        border-radius: 12px;
        font-weight: 600;
        letter-spacing: 0.5px;
        transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(118, 75, 162, 0.3);
        width: 100%;
    }

    .btn-buy:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(118, 75, 162, 0.4);
        background: linear-gradient(90deg, #764ba2 0%, #667eea 100%);
    }

    /* Alert Styling */
    .alert {
        border-radius: 15px;
        border: none;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        backdrop-filter: blur(5px);
    }
  </style>
</head>
<body>

    <!-- Dynamic Background -->
    <div class="bg-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
    </div>

    <?php include 'navbar.php'; ?>

    <div class="container main-content">
        <div class="glass-container">
            <h2 class="page-title">
                Explore Premium Courses
            </h2>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success d-flex align-items-center" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <div><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger d-flex align-items-center" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <div><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
                </div>
            <?php endif; ?>

            <?php if ($result && $result->num_rows > 0): ?>
                <div class="courses-grid">
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="course-card">
                            <div class="card-header-gradient">
                                <div class="course-icon-wrapper">
                                    <i class="fas fa-book-reader"></i>
                                </div>
                            </div>
                            
                            <div class="course-body">
                                <div class="instructor-badge">
                                    <i class="fas fa-chalkboard-teacher"></i>
                                    <?php echo htmlspecialchars($row['full_name']); ?>
                                </div>

                                <h3 class="course-title"><?php echo htmlspecialchars($row['course_name']); ?></h3>
                                
                                <div class="course-meta">
                                    <div class="meta-item">
                                        <i class="fas fa-briefcase"></i>
                                        <span><?php echo htmlspecialchars($row['job_experience']); ?> Exp</span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-clock"></i>
                                        <span><?php echo htmlspecialchars($row['class_hour']); ?> Hours</span>
                                    </div>
                                </div>

                                <div class="price-tag">
                                    TK <?php echo htmlspecialchars($row['expected_money']); ?>
                                    <span>/ Course</span>
                                </div>

                                <form method="POST" action="">
                                    <input type="hidden" name="course_id" value="<?php echo $row['course_id']; ?>">
                                    <input type="hidden" name="instructor_id" value="<?php echo $row['instructor_id']; ?>">
                                    <button type="submit" name="buy_course" class="btn-buy">
                                        <i class="fas fa-cart-plus me-2"></i> Enroll Now
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <img src="https://cdni.iconscout.com/illustration/premium/thumb/no-data-found-8867280-7265556.png" alt="No courses" style="width: 200px; opacity: 0.8;">
                    <h4 class="text-muted mt-3">No courses available properly!</h4>
                    <p class="text-muted">You might have purchased all available courses or none are posted yet.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php $conn->close(); ?>
