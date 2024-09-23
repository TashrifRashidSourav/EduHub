<?php
session_start();
include 'db_connect.php';

$student_id = $_SESSION['student_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $skill_id = $_POST['skill_id'];
    $skill_level = $_POST['skill_level'];
    $cv_link = $_POST['cv_link'];
    
    $sql = "INSERT INTO freelancing (student_id, skill_id, skill_level, cv_link) 
            VALUES ('$student_id', '$skill_id', '$skill_level', '$cv_link')";
    
    if (mysqli_query($conn, $sql)) {
        echo "Freelancing details submitted for approval!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Fetch available skills for the dropdown
$skills_sql = "SELECT * FROM skills";
$skills_result = mysqli_query($conn, $skills_sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Skills & Freelancing</title>
</head>
<body>
    <h1>Share Your Skills & Freelance Opportunities</h1>
    <form action="skills_development.php" method="POST">
        <label for="skill_id">Skill:</label>
        <select name="skill_id" required>
            <?php while ($skill = mysqli_fetch_assoc($skills_result)): ?>
                <option value="<?= $skill['skill_id'] ?>"><?= $skill['skill_name'] ?></option>
            <?php endwhile; ?>
        </select><br>

        <label for="skill_level">Skill Level:</label>
        <input type="number" name="skill_level" min="1" max="5" required><br>

        <label for="cv_link">CV Link:</label>
        <input type="url" name="cv_link" required><br>

        <button type="submit">Submit</button>
    </form>
</body>
</html>
