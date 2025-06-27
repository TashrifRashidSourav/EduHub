<?php
// db_connect.php content
$servername = "localhost";
$username = "root";  // Change this if you have a different username
$password = "";      // Change this if your MySQL server has a password
$dbname = "EduHub";  // Name of the database

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$student_id = 1; // This should come from the logged-in user session
$skills = [];
$skill_data = [];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $selected_skills = $_POST['skills'] ?? [];
    $descriptions = $_POST['description'] ?? [];
    $work_experience_years = $_POST['work_experience_years'] ?? [];
    $portfolio = $_POST['portfolio'] ?? '';

    $demo_project = '';
    if (isset($_FILES['demo_project']) && $_FILES['demo_project']['error'] == UPLOAD_ERR_OK) {
        $file_tmp_path = $_FILES['demo_project']['tmp_name'];
        $file_name = time() . '_' . basename($_FILES['demo_project']['name']);
        $file_size = $_FILES['demo_project']['size'];
        $file_type = $_FILES['demo_project']['type'];

        $allowed_file_types = ['application/pdf', 'application/vnd.openxmlformats-officedocument.presentationml.presentation'];
        if (in_array($file_type, $allowed_file_types)) {
            if (!file_exists('uploads')) {
                mkdir('uploads', 0777, true);
            }
            $upload_path = 'uploads/' . $file_name;
            move_uploaded_file($file_tmp_path, $upload_path);
            $demo_project = $upload_path;
        } else {
            echo "Only PDF and PPTX files are allowed.";
        }
    }

    foreach ($selected_skills as $skill_name) {
        $description = $descriptions[$skill_name] ?? '';
        $work_experience = $work_experience_years[$skill_name] ?? 0;

        $stmt = $conn->prepare("INSERT INTO student_skills (student_id, skill_name, description, work_experience_years, demo_project, portfolio) 
                                 VALUES (?, ?, ?, ?, ?, ?) 
                                 ON DUPLICATE KEY UPDATE description=?, work_experience_years=?, demo_project=?, portfolio=?");

        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("ississssss", $student_id, $skill_name, $description, $work_experience, $demo_project, $portfolio,
                          $description, $work_experience, $demo_project, $portfolio);

        $stmt->execute();

        if ($stmt->error) {
            die("Execute failed: " . $stmt->error);
        }
    }
}

// Fetch skills for editing
$sql = "SELECT * FROM student_skills WHERE student_id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $skill_data[$row['skill_name']] = $row;
}

$skills = ['PowerPoint', 'Word', 'Excel', 'Web Development', 'Frontend', 'Fullstack'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Skills</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --accent-color: #ff6b6b;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 900px;
            margin: 40px auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: var(--primary-color);
        }
        .skill-item {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            background-color: #f8f9fa;
        }
        .input-group label {
            font-weight: 600;
        }
        input[type="text"], input[type="number"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="file"] {
            margin-top: 10px;
        }
        button {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background-color: var(--secondary-color);
        }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container">
    <h2>Student Skills</h2>
    <form action="" method="POST" enctype="multipart/form-data">
        <?php foreach ($skills as $skill): ?>
            <div class="skill-item">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="skills[]" id="<?= $skill ?>" value="<?= $skill ?>" <?= isset($skill_data[$skill]) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="<?= $skill ?>"><strong><?= $skill ?></strong></label>
                </div>
                <div class="input-group mt-2">
                    <label>Description:</label>
                    <input type="text" name="description[<?= $skill ?>]" placeholder="Describe your skill" value="<?= $skill_data[$skill]['description'] ?? '' ?>">
                </div>
                <div class="input-group mt-2">
                    <label>Years of Experience:</label>
                    <input type="number" name="work_experience_years[<?= $skill ?>]" min="0" max="50" placeholder="Years" value="<?= $skill_data[$skill]['work_experience_years'] ?? '' ?>">
                </div>
            </div>
        <?php endforeach; ?>

        <div class="form-group">
            <label for="demo_project">Demo Project (PDF/PPTX only):</label>
            <input type="file" name="demo_project" id="demo_project" accept=".pdf,.pptx">
        </div>

        <div class="form-group">
            <label for="portfolio">Portfolio URL:</label>
            <input type="text" name="portfolio" id="portfolio" placeholder="https://example.com" value="<?= !empty($skill_data) ? (reset($skill_data)['portfolio'] ?? '') : '' ?>">
        </div>

        <button type="submit">Save Skills</button>
    </form>
</div>
</body>
</html>
<div id="footer-placeholder"></div>

<script>
  fetch('footer.html')
    .then(res => res.text())
    .then(data => {
      document.getElementById('footer-placeholder').innerHTML = data;
    });
</script>

<?php
$conn->close();
?>
