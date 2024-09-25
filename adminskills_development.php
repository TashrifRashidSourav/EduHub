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

// Fetch skills from the database
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle form submission
    $selected_skills = $_POST['skills'] ?? [];
    $descriptions = $_POST['description'] ?? [];
    $work_experience_years = $_POST['work_experience_years'] ?? [];
    $portfolio = $_POST['portfolio'] ?? '';

    // Handle file upload for the demo project
    $demo_project = '';
    if (isset($_FILES['demo_project']) && $_FILES['demo_project']['error'] == UPLOAD_ERR_OK) {
        $file_tmp_path = $_FILES['demo_project']['tmp_name'];
        $file_name = $_FILES['demo_project']['name'];
        $file_size = $_FILES['demo_project']['size'];
        $file_type = $_FILES['demo_project']['type'];
        
        // Validate file type (only pptx and pdf)
        $allowed_file_types = ['application/pdf', 'application/vnd.openxmlformats-officedocument.presentationml.presentation'];
        if (in_array($file_type, $allowed_file_types)) {
            $upload_path = 'uploads/' . basename($file_name); // Path to save the uploaded file
            move_uploaded_file($file_tmp_path, $upload_path);
            $demo_project = $upload_path; // Save the path of the uploaded file
        } else {
            echo "Only PDF and PPTX files are allowed.";
        }
    }

    // Save selected skills and other info to the database
    foreach ($selected_skills as $skill_name) {
        $description = $descriptions[$skill_name] ?? '';
        $work_experience = $work_experience_years[$skill_name] ?? 0;

        // Prepare the SQL statement
        $stmt = $conn->prepare("INSERT INTO student_skills (student_id, skill_name, description, work_experience_years, demo_project, portfolio) 
                                 VALUES (?, ?, ?, ?, ?, ?)
                                 ON DUPLICATE KEY UPDATE description=?, work_experience_years=?, demo_project=?, portfolio=?");

        // Check if the prepare failed
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        // Bind parameters
        $stmt->bind_param("ississssss", $student_id, $skill_name, $description, $work_experience, $demo_project, $portfolio, 
                          $description, $work_experience, $demo_project, $portfolio);
        
        // Execute the statement
        $stmt->execute();
        
        // Optional: Check for errors during execution
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

// Skills array
$skills = ['PowerPoint', 'Word', 'Excel', 'Web Development', 'Frontend', 'Fullstack'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Skills</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
        }

        .skills-section {
            margin-bottom: 20px;
        }

        .skill-item {
            margin-bottom: 10px;
        }

        .input-group {
            margin-bottom: 15px;
        }

        input[type="text"], input[type="number"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="file"] {
            margin-top: 5px;
        }

        button {
            background-color: #28a745;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
<?php include 'navbaradmin.php'; ?>
    <div class="container">
        <h2>Student Skills</h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="skills-section">
                <?php foreach ($skills as $skill): ?>
                    <div class="skill-item">
                        <input type="checkbox" name="skills[]" value="<?= $skill ?>" id="<?= $skill ?>" 
                            <?= isset($skill_data[$skill]) ? 'checked' : '' ?>>
                        <label for="<?= $skill ?>"><?= $skill ?></label>
                        <input type="text" name="description[<?= $skill ?>]" 
                            placeholder="Description" 
                            value="<?= isset($skill_data[$skill]) ? $skill_data[$skill]['description'] : '' ?>">
                        <input type="number" name="work_experience_years[<?= $skill ?>]" 
                            placeholder="Years of Experience" 
                            value="<?= isset($skill_data[$skill]) ? $skill_data[$skill]['work_experience_years'] : '' ?>">
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="input-group">
                <label for="demo_project">Demo Project (Upload PDF or PPTX):</label>
                <input type="file" name="demo_project" id="demo_project" accept=".pptx,.pdf">
            </div>
            <div class="input-group">
                <label for="portfolio">Portfolio:</label>
                <input type="text" name="portfolio" id="portfolio" placeholder="Link to portfolio" 
                       value="<?= !empty($skill_data) ? (reset($skill_data)['portfolio'] ?? '') : '' ?>">
            </div>
            <button type="submit">Save Skills</button>
        </form>
    </div>
</body>
</html>

<?php
$conn->close();
?>
