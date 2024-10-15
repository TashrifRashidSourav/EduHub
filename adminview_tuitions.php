<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "eduhub";

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$results = [];
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search_tuition'])) {
    $class_level = $_POST['class_level'];
    $subject = $_POST['subject'];

    $sql = "SELECT * FROM tuitions WHERE class_level = '$class_level' AND subject = '$subject'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $results[] = $row;
        }
    } else {
        $results[] = ['message' => 'No results found.'];
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Tuition Offers</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .container {
            margin-top: 50px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .results-table {
            margin-top: 30px;
        }
    </style>
</head>
<body>
 
    <?php include 'navbaradmin.php'; ?>

    <div class="container">
        <h1 class="text-center">View Tuition Offers</h1>

        <form method="post">
            <div class="form-group">
                <label for="class_level">Class Level</label>
                <select class="form-control" id="class_level" name="class_level" required>
                    <option value="">Select Class</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                </select>
            </div>

            <div class="form-group">
                <label for="subject">Subject</label>
                <select class="form-control" id="subject" name="subject" required>
                    <option value="">Select Subject</option>
                    <option value="Science">Science</option>
                    <option value="Math">Math</option>
                    <option value="English">English</option>
                    <option value="Biology">Biology</option>
                    <option value="Economics">Economics</option>
                    <option value="Chemistry">Chemistry</option>
                    <option value="Physics">Physics</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary" name="search_tuition">Search</button>
        </form>

        <?php if (!empty($results)): ?>
        <div class="results-table">
            <h2>Search Results</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Class Level</th>
                        <th>Subject</th>
                        <th>Location</th>
                        <th>Institution</th>
                        <th>Phone Number</th>
                        <th>Preferred Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['class_level']); ?></td>
                        <td><?php echo htmlspecialchars($row['subject']); ?></td>
                        <td><?php echo htmlspecialchars($row['location']); ?></td>
                        <td><?php echo htmlspecialchars($row['institution']); ?></td>
                        <td><?php echo htmlspecialchars($row['phone_number']); ?></td>
                        <td><?php echo htmlspecialchars($row['preferred_time']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>
