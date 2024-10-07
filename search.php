<?php
session_start();
require 'db_connect.php'; // Include your database connection file

// Initialize variables
$searchQuery = '';
$results = [];

// Handle search form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $searchQuery = trim($_POST['search_query']);
    
    // Prepare the SQL query to search in multiple fields
    $sql = "SELECT s.name, s.profile_picture, ui.student_id, ui.school, ui.college, ui.university, ui.occupation, ui.job_field
            FROM students s
            JOIN UserInformation ui ON s.student_id = ui.student_id
            WHERE s.name LIKE ? OR
                  ui.school LIKE ? OR
                  ui.college LIKE ? OR
                  ui.university LIKE ? OR
                  ui.occupation LIKE ? OR
                  ui.job_field LIKE ?";
    
    $likeQuery = "%$searchQuery%";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $likeQuery, $likeQuery, $likeQuery, $likeQuery, $likeQuery, $likeQuery);
    
    // Execute the statement and fetch results
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Fetch all results
    while ($row = $result->fetch_assoc()) {
        $results[] = $row;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Search</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .result-card {
            margin-bottom: 20px;
        }
        .profile-img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
        }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container">
    <h2>User Search</h2>
    <form method="POST" class="mb-4">
        <div class="form-group">
            <input type="text" class="form-control" name="search_query" placeholder="Search by name, school, college, university, occupation, job field..." value="<?php echo htmlspecialchars($searchQuery); ?>">
        </div>
        <button type="submit" class="btn btn-primary">Search</button>
    </form>

    <?php if (!empty($results)): ?>
        <h4>Search Results:</h4>
        <div class="row">
            <?php foreach ($results as $user): ?>
                <div class="col-md-4">
                    <div class="card result-card">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($user['name']); ?></h5>
                            <img src="<?php echo htmlspecialchars($user['profile_picture'] ?: 'default-profile.png'); ?>" alt="Profile Picture" class="profile-img">
                            <p class="card-text">School: <?php echo htmlspecialchars($user['school']); ?></p>
                            <p class="card-text">College: <?php echo htmlspecialchars($user['college']); ?></p>
                            <p class="card-text">University: <?php echo htmlspecialchars($user['university']); ?></p>
                            <p class="card-text">Occupation: <?php echo htmlspecialchars($user['occupation']); ?></p>
                            <p class="card-text">Job Field: <?php echo htmlspecialchars($user['job_field']); ?></p>
                            <a href="adminchat.php?receiver_id=<?php echo htmlspecialchars($user['student_id']); ?>&receiver_name=<?php echo urlencode($user['name']); ?>&receiver_picture=<?php echo urlencode($user['profile_picture']); ?>" class="btn btn-success">Message</a>

                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php elseif ($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
        <p>No results found for "<?php echo htmlspecialchars($searchQuery); ?>".</p>
    <?php endif; ?>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
