<?php
// db_connect.php - database connection
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

// Start session
session_start();
$rater_id = $_SESSION['student_id']; // Assuming user is logged in and their ID is stored in session

// Handle ranking submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $rated_id = $_POST['rated_user'];
    $rank_value = $_POST['rank_value'];
    $rank_type = 'skill'; // You can change this based on your criteria

    // Prevent self-ranking
    if ($rater_id == $rated_id) {
        echo "<script>alert('You cannot rank yourself.');</script>";
    } else {
        // Insert ranking into the database
        $stmt = $conn->prepare("INSERT INTO ranking (rater_id, rated_id, rank_type, rank_value) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiis", $rater_id, $rated_id, $rank_type, $rank_value);
        
        if ($stmt->execute()) {
            echo "<script>alert('Ranking submitted successfully.');</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }
        
        $stmt->close();
    }
}

// Fetch all students for ranking
$students = [];
$result = $conn->query("SELECT student_id, name FROM students");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
}

// Fetch average rankings
$average_rankings = [];
$result = $conn->query("SELECT rated_id, AVG(rank_value) AS average_rank FROM ranking GROUP BY rated_id");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $average_rankings[$row['rated_id']] = $row['average_rank'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Ranking System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>User Ranking System</h2>

    <form action="" method="POST">
        <div class="form-group">
            <label for="rated_user">Select User to Rank:</label>
            <select name="rated_user" id="rated_user" class="form-control" required>
                <option value="">-- Select a User --</option>
                <?php foreach ($students as $student): ?>
                    <option value="<?php echo $student['student_id']; ?>">
                        <?php echo htmlspecialchars($student['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label for="rank_value">Rank Value:</label>
            <select name="rank_value" id="rank_value" class="form-control" required>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>
        </div>
        
        <button type="submit" class="btn btn-primary">Submit Ranking</button>
    </form>

    <h3 class="mt-5">Average Rankings</h3>
    <table class="table">
        <thead>
            <tr>
                <th>User Name</th>
                <th>Average Rank</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($average_rankings as $rated_id => $avg_rank): ?>
                <?php
                // Fetch the student's name for display
                $student_result = $conn->query("SELECT name FROM students WHERE student_id = $rated_id");
                $student = $student_result->fetch_assoc();
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($student['name']); ?></td>
                    <td><?php echo round($avg_rank, 2); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close(); // Close the database connection
?>
