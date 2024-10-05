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

// Fetch average rankings and educational posts for all users
$student_data = [];
$sql = "SELECT s.student_id, s.name, 
               IFNULL(AVG(r.rank_value), 0) AS average_rank, 
               IFNULL(COUNT(DISTINCT p.post_id), 0) AS total_educational_posts
        FROM students s
        LEFT JOIN ranking r ON s.student_id = r.rated_id
        LEFT JOIN posts p ON s.student_id = p.student_id AND p.category = 'educational'
        GROUP BY s.student_id, s.name";

$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $student_data[] = $row;
    }
}

// Sort students by average rank and educational posts
usort($student_data, function($a, $b) {
    $rankA = $a['average_rank'] ?? 0;
    $rankB = $b['average_rank'] ?? 0;
    $postA = $a['total_educational_posts'];
    $postB = $b['total_educational_posts'];

    // Sort by average rank first, then by number of posts
    if ($rankB == $rankA) {
        return $postB <=> $postA;
    }
    return $rankB <=> $rankA;
});
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Leaderboard</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Rank</th>
                <th>User Name</th>
                <th>Average Rank</th>
                <th>Total Educational Posts</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $rank = 1;
            foreach ($student_data as $student): 
                $avg_rank = isset($student['average_rank']) ? round($student['average_rank'], 2) : 0;
                $total_posts = $student['total_educational_posts'];
            ?>
            <tr>
                <td><?php echo $rank++; ?></td>
                <td><?php echo htmlspecialchars($student['name']); ?></td>
                <td><?php echo $avg_rank; ?></td>
                <td><?php echo $total_posts; ?></td>
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
