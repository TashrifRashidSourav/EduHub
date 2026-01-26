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
    <title>EduVerse | Skills Development</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4A90E2; 
            --secondary: #50E3C2;
            --accent: #F5A623;
            --light-bg: #F9FAFB;
            --card-glass: rgba(255, 255, 255, 0.85);
            --border-soft: rgba(0, 0, 0, 0.05);
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--light-bg);
            color: #2D3748;
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }

        /* Geometric Background - Light Theme */
        .geo-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
            background: linear-gradient(120deg, #fdfbfb 0%, #ebedee 100%);
        }

        .shape {
            position: absolute;
            background: linear-gradient(135deg, var(--secondary), var(--primary));
            opacity: 0.2;
            filter: blur(60px);
            border-radius: 40% 60% 70% 30% / 40% 50% 60% 50%;
            animation: floatShape 20s infinite alternate;
        }

        .shape-1 {
            top: -10%;
            left: -5%;
            width: 600px;
            height: 600px;
            background: #8ec5fc;
        }

        .shape-2 {
            bottom: -15%;
            right: -10%;
            width: 700px;
            height: 700px;
            background: #e0c3fc;
            animation-delay: -5s;
        }

        .shape-3 {
            top: 40%;
            left: 30%;
            width: 400px;
            height: 400px;
            background: #fbc2eb;
            opacity: 0.15;
            animation-duration: 25s;
        }

        @keyframes floatShape {
            0% { transform: translate(0, 0) rotate(0deg); }
            100% { transform: translate(40px, -40px) rotate(15deg); }
        }

        /* Main Container */
        .glass-panel {
            background: var(--card-glass);
            backdrop-filter: blur(25px);
            border: 1px solid rgba(255,255,255,0.6);
            border-radius: 24px;
            padding: 3rem;
            margin: 3rem auto;
            max-width: 1000px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05); /* Soft shadow */
        }

        h2 {
            text-align: center;
            font-weight: 700;
            margin-bottom: 2.5rem;
            background: linear-gradient(135deg, #2c3e50, #4A90E2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-size: 2.5rem;
            letter-spacing: -0.5px;
        }

        /* Skill Card */
        .skill-group {
            background: #fff;
            border: 1px solid var(--border-soft);
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 4px 6px rgba(0,0,0,0.02);
            position: relative;
            overflow: hidden;
        }

        .skill-group:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.08);
            border-color: rgba(74, 144, 226, 0.3);
        }

        .skill-group::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: linear-gradient(to bottom, var(--primary), var(--secondary));
            opacity: 0;
            transition: opacity 0.3s;
        }

        .skill-group:hover::before {
            opacity: 1;
        }

        /* Custom Checkbox */
        .form-check-input {
            width: 1.4em;
            height: 1.4em;
            cursor: pointer;
            border: 2px solid #CBD5E0;
        }

        .form-check-input:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .form-check-label {
            font-size: 1.2rem;
            font-weight: 600;
            margin-left: 12px;
            color: #2D3748;
            cursor: pointer;
        }

        /* Inputs */
        .custom-input {
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            color: #4A5568;
            border-radius: 8px;
            padding: 12px 15px;
            transition: all 0.3s;
        }

        .custom-input:focus {
            background: #fff;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.1);
            outline: none;
            color: #2D3748;
        }

        .custom-input::placeholder {
            color: #A0AEC0;
        }

        label.sub-label {
            color: #718096;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Buttons */
        .btn-theme {
            background: linear-gradient(135deg, var(--primary) 0%, #2980b9 100%);
            color: white;
            border: none;
            padding: 16px 30px;
            border-radius: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            width: 100%;
            transition: all 0.3s;
            margin-top: 2rem;
            box-shadow: 0 10px 20px rgba(74, 144, 226, 0.3);
        }

        .btn-theme:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(74, 144, 226, 0.4);
            color: white;
        }

        /* Upload Field */
        .upload-box {
            border: 2px dashed #CBD5E0;
            background: #F8FAFC;
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s;
        }

        .upload-box:hover {
            border-color: var(--primary);
            background: #fff;
        }

        input[type="file"] {
            font-size: 0.9rem;
            width: 100%;
        }

        input[type="file"]::file-selector-button {
            margin-right: 15px;
            border: none;
            background: #EDF2F7;
            padding: 8px 16px;
            border-radius: 6px;
            color: #4A5568;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        input[type="file"]::file-selector-button:hover {
            background: #E2E8F0;
            color: var(--primary);
        }

    </style>
</head>
<body>

    <div class="geo-bg">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
    </div>

    <!-- Include Navbar Component -->
    <?php include 'navbar.php'; ?>

    <div class="container">
        <div class="glass-panel">
            <h2><i class="fas fa-layer-group me-3 text-primary"></i>Track Your Growth</h2>
            
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <?php foreach ($skills as $skill): ?>
                        <div class="col-md-12">
                            <div class="skill-group">
                                <div class="form-check d-flex align-items-center mb-0">
                                    <input type="checkbox" class="form-check-input" name="skills[]" id="<?= $skill ?>" value="<?= $skill ?>" <?= isset($skill_data[$skill]) ? 'checked' : '' ?> onclick="toggleSkillDetails(this)">
                                    <label class="form-check-label" for="<?= $skill ?>"><?= $skill ?></label>
                                </div>
                                
                                <div class="row g-3 ps-4 mt-1 skill-details" id="details-<?= $skill ?>" style="display: <?= isset($skill_data[$skill]) ? 'flex' : 'none' ?>;">
                                    <div class="col-md-9">
                                        <label class="sub-label">Self Assessment / Description</label>
                                        <input type="text" class="custom-input w-100" name="description[<?= $skill ?>]" placeholder="E.g. built 5 projects using React" value="<?= $skill_data[$skill]['description'] ?? '' ?>">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="sub-label">Experience (Years)</label>
                                        <input type="number" class="custom-input w-100" name="work_experience_years[<?= $skill ?>]" min="0" max="50" placeholder="0" value="<?= $skill_data[$skill]['work_experience_years'] ?? '' ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="row mt-4 g-4">
                    <div class="col-md-6">
                        <div class="skill-group h-100">
                            <label class="sub-label mb-3 d-block"><i class="fas fa-file-pdf me-2 text-danger"></i>Demo Project (PDF/PPTX)</label>
                            <div class="upload-box">
                                <input type="file" name="demo_project" id="demo_project" accept=".pdf,.pptx">
                                <div class="mt-2 text-muted small">Showcase your best work to impress instructors.</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="skill-group h-100 d-flex flex-column justify-content-center">
                            <label class="sub-label mb-2"><i class="fas fa-globe me-2 text-info"></i>Portfolio URL</label>
                            <input type="text" class="custom-input w-100" name="portfolio" id="portfolio" placeholder="https://your-portfolio.com" value="<?= !empty($skill_data) ? (reset($skill_data)['portfolio'] ?? '') : '' ?>">
                            <div class="mt-2 text-muted small">Link to your GitHub, Behance, or personal site.</div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-theme">
                    <i class="fas fa-save me-2"></i> Save Skills Profile
                </button>
            </form>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function toggleSkillDetails(checkbox) {
            const detailsId = 'details-' + checkbox.value;
            const detailsElement = document.getElementById(detailsId);
            if(checkbox.checked) {
                detailsElement.style.display = 'flex';
                // Add a small fade in animation via class if desired
            } else {
                detailsElement.style.display = 'none';
            }
        }
    </script>
</body>
</html>
<?php $conn->close(); ?>
