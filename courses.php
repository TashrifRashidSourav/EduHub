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
  <title>Buy Courses</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      margin: 0;
      padding: 0;
      position: relative;
    }

    body::before {
      content: '';
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/></svg>');
      pointer-events: none;
      z-index: -1;
    }

    .main-container {
      background: rgba(255, 255, 255, 0.9);
      backdrop-filter: blur(20px);
      border-radius: 20px;
      padding: 2rem;
      margin: 3rem auto;
      width: 95%;
      max-width: 1200px;
      box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }

    .page-header {
      text-align: center;
      margin-bottom: 2rem;
    }

    .page-header h2 {
      font-weight: 700;
      color: #333;
    }

    .courses-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
      gap: 1.5rem;
    }

    .course-card {
      background: #fff;
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
      display: flex;
      flex-direction: column;
      transition: transform 0.3s ease;
    }

    .course-card:hover {
      transform: translateY(-5px);
    }

    .course-header {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      padding: 1rem 1.5rem;
    }

    .course-body {
      padding: 1.5rem;
      flex: 1;
      display: flex;
      flex-direction: column;
    }

    .course-title {
      font-size: 1.2rem;
      font-weight: 600;
      margin: 0;
    }

    .instructor-name {
      font-size: 0.95rem;
      opacity: 0.9;
    }

    .info-list {
      margin-top: 1rem;
      flex: 1;
    }

    .info-item {
      display: flex;
      justify-content: space-between;
      margin-bottom: 0.5rem;
      font-size: 0.95rem;
    }

    .buy-btn {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      border: none;
      padding: 0.8rem;
      font-weight: 600;
      text-transform: uppercase;
      border-radius: 40px;
      transition: all 0.3s ease;
      margin-top: 1rem;
      width: 100%;
    }

    .buy-btn:hover {
      opacity: 0.9;
      box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
    }

    .alert {
      margin-bottom: 1.5rem;
      border-radius: 10px;
      font-weight: 500;
    }

    @media (max-width: 768px) {
      .courses-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="main-container">

  <div class="page-header">
    <h2><i class="fas fa-graduation-cap"></i> Available Courses</h2>
  </div>

  <?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
  <?php endif; ?>
  <?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
  <?php endif; ?>

  <?php if ($result && $result->num_rows > 0): ?>
    <div class="courses-grid">
      <?php while ($row = $result->fetch_assoc()): ?>
        <div class="course-card">
          <div class="course-header">
            <h3 class="course-title"><?php echo htmlspecialchars($row['course_name']); ?></h3>
            <p class="instructor-name"><i class="fas fa-user-tie"></i> <?php echo htmlspecialchars($row['full_name']); ?></p>
          </div>
          <div class="course-body">
            <div class="info-list">
              <div class="info-item"><span>Experience:</span> <span><?php echo htmlspecialchars($row['job_experience']); ?></span></div>
              <div class="info-item"><span>Class Hours:</span> <span><?php echo htmlspecialchars($row['class_hour']); ?></span></div>
              <div class="info-item"><span>Expected Fee:</span> <span><?php echo htmlspecialchars($row['expected_money']); ?></span></div>
            </div>
            <form method="POST" action="">
              <input type="hidden" name="course_id" value="<?php echo $row['course_id']; ?>">
              <input type="hidden" name="instructor_id" value="<?php echo $row['instructor_id']; ?>">
              <button type="submit" name="buy_course" class="buy-btn"><i class="fas fa-cart-plus"></i> Buy Now</button>
            </form>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  <?php else: ?>
    <p class="text-center">No courses available for purchase.</p>
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

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php $conn->close(); ?>
