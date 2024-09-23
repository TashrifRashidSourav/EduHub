<?php
session_start();
include 'db_connect.php';

$student_id = $_SESSION['student_id'];  // The ID of the student who is giving the rating

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rated_student_id = $_POST['rated_student_id'];  // The student who is being rated
    $rank_type = $_POST['rank_type'];
    $rank_value = $_POST['rank_value'];

    // Check if the student is trying to rate themselves (this should not be allowed)
    if ($student_id == $rated_student_id) {
        echo "You cannot rate yourself.";
        exit;
    }

    // Check if the rating for this student and rank type already exists
    $check_sql = "SELECT * FROM ranking WHERE student_id = '$rated_student_id' AND rank_type = '$rank_type'";
    $check_result = mysqli_query($conn, $check_sql);

    if (mysqli_num_rows($check_result) > 0) {
        // If it exists, update the ranking
        $update_sql = "UPDATE ranking SET rank_value = '$rank_value' WHERE student_id = '$rated_student_id' AND rank_type = '$rank_type'";
        if (mysqli_query($conn, $update_sql)) {
            echo "Ranking updated successfully!";
        } else {
            echo "Error updating ranking: " . mysqli_error($conn);
        }
    } else {
        // If it does not exist, insert a new ranking
        $insert_sql = "INSERT INTO ranking (student_id, rank_type, rank_value) VALUES ('$rated_student_id', '$rank_type', '$rank_value')";
        if (mysqli_query($conn, $insert_sql)) {
            echo "Ranking submitted successfully!";
        } else {
            echo "Error inserting ranking: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Rate Student</title>
</head>
<body>
    <p><a href="ranking.php">Go back to Ranking</a></p>
</body>
</html>
