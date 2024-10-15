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

// Handle adding new work
if (isset($_POST['add_work'])) {
    $skill_requirement = $_POST['skill_requirement'];
    $experience_requirement_year = $_POST['experience_requirement_year'];
    $details = $_POST['details'];
    $salary = $_POST['salary'];

    $query = "INSERT INTO works (student_id, skill_requirement, experience_requirement_year, details, salary) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die("SQL Error: " . $conn->error);
    }
    $stmt->bind_param("sssss", $student_id, $skill_requirement, $experience_requirement_year, $details, $salary);
    $stmt->execute();
    $stmt->close();
}

// Fetch existing works
$query = "SELECT * FROM works WHERE student_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $student_id);
$stmt->execute();
$works = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Work Management</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<?php include 'navbaradmin.php'; ?>
<div class="container">
    <h2>Manage Your Work</h2>


    <h3>Add Work</h3>
    <form method="POST">
        <div class="form-group">
            <label for="skill_requirement">Skill Requirement:</label>
            <input type="text" class="form-control" name="skill_requirement" required>
        </div>
        <div class="form-group">
            <label for="experience_requirement_year">Experience Requirement (Years):</label>
            <input type="number" class="form-control" name="experience_requirement_year" min="0" required>
        </div>
        <div class="form-group">
            <label for="details">Details:</label>
            <textarea class="form-control" name="details" required></textarea>
        </div>
        <div class="form-group">
            <label for="salary">Salary:</label>
            <input type="text" class="form-control" name="salary" required>
        </div>
        <button type="submit" name="add_work" class="btn btn-success">Add Work</button>
    </form>

    <!-- Existing Works -->
    <h3>Your Works</h3>
    <table class="table">
        <thead>
        <tr>
            <th>Skill Requirement</th>
            <th>Experience Requirement (Years)</th>
            <th>Details</th>
            <th>Salary</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($work = $works->fetch_assoc()): ?>
            <tr>
                <form method="POST">
                    <input type="hidden" name="work_id" value="<?php echo $work['work_id']; ?>">
                    <td><input type="text" name="skill_requirement" value="<?php echo htmlspecialchars($work['skill_requirement']); ?>" required></td>
                    <td><input type="number" name="experience_requirement_year" value="<?php echo htmlspecialchars($work['experience_requirement_year']); ?>" min="0" required></td>
                    <td><textarea name="details" required><?php echo htmlspecialchars($work['details']); ?></textarea></td>
                    <td><input type="text" name="salary" value="<?php echo htmlspecialchars($work['salary']); ?>" required></td>
                    <td>
                        <button type="submit" name="update_work" class="btn btn-warning">Update</button>
                        <a href="?delete=<?php echo $work['work_id']; ?>" class="btn btn-danger">Delete</a>
                    </td>
                </form>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
