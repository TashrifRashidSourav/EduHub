<?php
session_start();
include 'db_connect.php';

$student_id = $_SESSION['student_id'];

// Fetch rankings of the logged-in user
$sql = "SELECT rank_type, rank_value FROM ranking WHERE student_id = '$student_id'";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Ranking</title>
</head>
<body>
    <h1>Your Rankings</h1>
    <table border="1">
        <tr>
            <th>Rank Type</th>
            <th>Rank Value</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?= $row['rank_type'] ?></td>
            <td><?= $row['rank_value'] ?></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <h2>Rate Other Students</h2>
    <form action="rate_student.php" method="POST">
        <label for="rated_student_id">Select Student:</label>
        <select name="rated_student_id" required>
            <?php
            // Fetch all students except the logged-in user for rating
            $students_sql = "SELECT student_id, name FROM students WHERE student_id != '$student_id'";
            $students_result = mysqli_query($conn, $students_sql);
            while ($student = mysqli_fetch_assoc($students_result)): ?>
                <option value="<?= $student['student_id'] ?>"><?= $student['name'] ?></option>
            <?php endwhile; ?>
        </select><br>

        <label for="rank_type">Rank Type:</label>
        <select name="rank_type" required>
            <option value="PowerPoint">PowerPoint</option>
            <option value="Word">Word</option>
            <option value="Excel">Excel</option>
            <option value="Web Development">Web Development</option>
            <option value="Backend">Backend</option>
            <option value="Frontend">Frontend</option>
            <option value="Fullstack">Fullstack</option>
        </select><br>

        <label for="rank_value">Rank Value (1 to 5):</label>
        <input type="number" name="rank_value" min="1" max="5" required><br>

        <button type="submit">Submit Rating</button>
    </form>
</body>
</html>
