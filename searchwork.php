<?php
session_start();
include 'db_connect.php'; // Ensure this file contains your database connection details

// Check if the user is logged in
if (!isset($_SESSION['student_id'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit();
}

// Initialize variables
$student_id = $_SESSION['student_id'];
$works = [];
$skills = ['PowerPoint', 'Word', 'Excel', 'Web Development', 'Frontend', 'Fullstack'];

// Handle search
if (isset($_POST['search_work'])) {
    $selected_skill = $_POST['skill'];

    // Fetch works based on the selected skill
    $query = "SELECT * FROM works WHERE skill_requirement = ?";
    $stmt = $conn->prepare($query);
    
    if ($stmt) {
        $stmt->bind_param("s", $selected_skill);
        $stmt->execute();
        $works = $stmt->get_result();
        $stmt->close();
    } else {
        echo "Error in SQL statement: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Work</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container">
    <h2>Search for Work</h2>

    <!-- Search Work Form -->
    <form method="POST">
        <div class="form-group">
            <label for="skill">Select Skill:</label>
            <select class="form-control" name="skill" required>
                <option value="">-- Select Skill --</option>
                <?php foreach ($skills as $skill): ?>
                    <option value="<?php echo htmlspecialchars($skill); ?>"><?php echo htmlspecialchars($skill); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" name="search_work" class="btn btn-primary">Search</button>
    </form>

    <h3>Available Works</h3>
    <table class="table">
        <thead>
        <tr>
            <th>Skill Requirement</th>
            <th>Experience Requirement (Years)</th>
            <th>Details</th>
            <th>Salary</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($works): ?>
            <?php while ($work = $works->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($work['skill_requirement']); ?></td>
                    <td><?php echo htmlspecialchars($work['experience_requirement_year']); ?></td>
                    <td><?php echo htmlspecialchars($work['details']); ?></td>
                    <td><?php echo htmlspecialchars($work['salary']); ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="4" class="text-center">No works found for the selected skill.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
