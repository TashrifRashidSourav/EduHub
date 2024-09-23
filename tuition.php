<?php
session_start();
include 'db_connect.php';

$student_id = $_SESSION['student_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject = $_POST['subject'];
    $institution = $_POST['institution'];
    $tuition_details = $_POST['tuition_details'];
    
    $sql = "INSERT INTO tuition_suggestions (student_id, subject, institution, tuition_details) 
            VALUES ('$student_id', '$subject', '$institution', '$tuition_details')";
    
    if (mysqli_query($conn, $sql)) {
        echo "Tuition Suggestion submitted!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Tuition Suggestions</title>
</head>
<body>
    <h1>Tuition Suggestions</h1>
    <form action="tuition.php" method="POST">
        <label for="subject">Subject:</label>
        <input type="text" name="subject" required><br>

        <label for="institution">Institution:</label>
        <input type="text" name="institution" required><br>

        <label for="tuition_details">Tuition Details:</label>
        <textarea name="tuition_details" required></textarea><br>

        <button type="submit">Submit Suggestion</button>
    </form>
</body>
</html>
