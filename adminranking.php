<?php

$servername = "localhost";
$username = "root";  
$password = "";      
$dbname = "EduHub";  

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$student_data = [];
$sql = "SELECT s.student_id, s.name, 
               SUM(CASE WHEN p.category = 'educational' AND p.status = 'approved' THEN 1 ELSE 0 END) AS total_educational_posts,
               SUM(CASE WHEN p.category = 'entertainment' AND p.status = 'approved' THEN 1 ELSE 0 END) AS total_entertainment_posts,
               SUM(CASE WHEN p.category = 'professional' AND p.status = 'approved' THEN 1 ELSE 0 END) AS total_professional_posts,
               COUNT(CASE WHEN p.status = 'approved' THEN p.post_id ELSE NULL END) AS total_posts
        FROM students s
        LEFT JOIN posts p ON s.student_id = p.student_id
        GROUP BY s.student_id, s.name
        ORDER BY total_posts DESC";

$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $student_data[] = $row;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top Contributer</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<?php include 'navbaradmin.php'; ?>
<div class="container mt-5">
    <h2>Leaderboard</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Rank</th>
                <th>User Name</th>
                <th>Total Approved Posts</th>
                <th>Educational Posts</th>
                <th>Entertainment Posts</th>
                <th>Professional Posts</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $rank = 1;
            foreach ($student_data as $student): 
                $total_posts = $student['total_posts'] ?? 0;
                $educational_posts = $student['total_educational_posts'] ?? 0;
                $entertainment_posts = $student['total_entertainment_posts'] ?? 0;
                $professional_posts = $student['total_professional_posts'] ?? 0;
            ?>
            <tr>
                <td><?php echo $rank++; ?></td>
                <td><?php echo htmlspecialchars($student['name']); ?></td>
                <td><?php echo $total_posts; ?></td>
                <td><?php echo $educational_posts; ?></td>
                <td><?php echo $entertainment_posts; ?></td>
                <td><?php echo $professional_posts; ?></td>
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
$conn->close(); 
?>
