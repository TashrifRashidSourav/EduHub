<?php
session_start();
require 'db_connect.php';

$searchQuery = '';
$results = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $searchQuery = trim($_POST['search_query']);
    $sql = "SELECT s.name, s.profile_picture, ui.student_id, ui.school, ui.college, ui.university, ui.occupation, ui.job_field
            FROM students s
            JOIN UserInformation ui ON s.student_id = ui.student_id
            WHERE s.name LIKE ? OR
                  ui.school LIKE ? OR
                  ui.college LIKE ? OR
                  ui.university LIKE ? OR
                  ui.occupation LIKE ? OR
                  ui.job_field LIKE ?";
    $likeQuery = "%$searchQuery%";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $likeQuery, $likeQuery, $likeQuery, $likeQuery, $likeQuery, $likeQuery);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $results[] = $row;
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Search Users</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(to right, #4b6cb7, #182848);
      color: white;
      min-height: 100vh;
    }
    .container {
      max-width: 1000px;
      margin-top: 80px;
      background: rgba(255, 255, 255, 0.1);
      padding: 40px;
      border-radius: 20px;
      backdrop-filter: blur(15px);
      box-shadow: 0 0 20px rgba(0,0,0,0.3);
    }
    h2 {
      text-align: center;
      margin-bottom: 30px;
    }
    .form-control {
      background-color: rgba(255,255,255,0.15);
      color: white;
      border: none;
      border-radius: 10px;
      padding: 15px;
    }
    .form-control::placeholder {
      color: #ccc;
    }
    .form-control:focus {
      background-color: rgba(255,255,255,0.2);
      box-shadow: 0 0 8px #ffd700;
    }
    .btn-primary {
      background: linear-gradient(135deg, #f7971e, #ffd200);
      border: none;
      font-weight: bold;
      padding: 12px 25px;
      border-radius: 50px;
      color: #000;
    }
    .btn-primary:hover {
      background: linear-gradient(135deg, #ffd200, #f7971e);
    }
    .result-card {
      background-color: rgba(255,255,255,0.9);
      color: #000;
      border-radius: 15px;
      padding: 20px;
      margin-bottom: 20px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.15);
      transition: transform 0.3s ease;
    }
    .result-card:hover {
      transform: translateY(-5px);
    }
    .profile-img {
      width: 70px;
      height: 70px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid #f7971e;
    }
  </style>
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container">
  <h2><i class="fas fa-search"></i> Search Users</h2>
  <form method="POST" class="mb-5">
    <div class="form-group">
      <input type="text" class="form-control" name="search_query" placeholder="Search by name, school, college, university, occupation, job field..." value="<?php echo htmlspecialchars($searchQuery); ?>">
    </div>
    <div class="text-center">
      <button type="submit" class="btn btn-primary">Search</button>
    </div>
  </form>

  <?php if (!empty($results)): ?>
    <div class="row">
      <?php foreach ($results as $user): ?>
        <div class="col-md-6">
          <div class="result-card">
            <div class="d-flex align-items-center mb-3">
              <img src="<?php echo htmlspecialchars($user['profile_picture'] ?: 'default-profile.png'); ?>" class="profile-img mr-3" alt="Profile Picture">
              <h5 class="mb-0"><?php echo htmlspecialchars($user['name']); ?></h5>
            </div>
            <p><strong>School:</strong> <?php echo htmlspecialchars($user['school']); ?></p>
            <p><strong>College:</strong> <?php echo htmlspecialchars($user['college']); ?></p>
            <p><strong>University:</strong> <?php echo htmlspecialchars($user['university']); ?></p>
            <p><strong>Occupation:</strong> <?php echo htmlspecialchars($user['occupation']); ?></p>
            <p><strong>Job Field:</strong> <?php echo htmlspecialchars($user['job_field']); ?></p>
            <a href="chat.php?receiver_id=<?php echo htmlspecialchars($user['student_id']); ?>&receiver_name=<?php echo urlencode($user['name']); ?>&receiver_picture=<?php echo urlencode($user['profile_picture']); ?>" class="btn btn-success">Message</a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php elseif ($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
    <p class="text-center mt-4">No results found for "<?php echo htmlspecialchars($searchQuery); ?>".</p>
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
