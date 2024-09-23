<?php
require 'db_connect.php'; // Include your database connection file

// Fetch subjects for dropdown
$subjects = ['Science', 'Math', 'English', 'Biology', 'Economics', 'Chemistry', 'Physics'];

$results = [];
$search = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $class = isset($_POST['class']) ? $_POST['class'] : '';
    $subject = isset($_POST['subject']) ? $_POST['subject'] : '';

    if ($class && $subject) {
        $sql = "SELECT t.tutor_id, s.name AS tutor_name, t.class_range_start, t.class_range_end, t.subject, t.location, t.phone_number, t.created_at 
                FROM tutors t
                JOIN students s ON t.student_id = s.student_id
                WHERE t.class_range_start <= ? 
                AND t.class_range_end >= ? 
                AND t.subject = ? 
                ORDER BY t.class_range_start ASC";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iis", $class, $class, $subject);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $results = $result->fetch_all(MYSQLI_ASSOC);
            $search = true;
        } else {
            echo "<p class='error'>Error: " . $stmt->error . "</p>";
        }

        $stmt->close();
    } else {
        echo "<p class='error'>Please select all fields.</p>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Tutors</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 70%;
            margin: 20px auto;
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        h1, h2 {
            text-align: center;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
            margin-bottom: 20px;
        }

        label {
            margin-top: 10px;
            font-weight: bold;
        }

        select, input[type="submit"] {
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: white;
            cursor: pointer;
            border: none;
            padding: 10px;
            margin-top: 20px;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .table {
            margin-top: 20px;
        }

        .table th, .table td {
            text-align: center;
        }

        .error {
            color: red;
            text-align: center;
            margin-top: 20px;
        }

        .message {
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>

    <?php include 'navbar.php'; ?>

    <div class="container">
        <h1>Find Tutors</h1>
        <form method="post" action="search_tutors.php">
            <label for="class">Class:</label>
            <select id="class" name="class" required>
                <option value="">Select Class</option>
                <?php for ($i = 1; $i <= 12; $i++) { echo "<option value=\"$i\">$i</option>"; } ?>
            </select>

            <label for="subject">Subject:</label>
            <select id="subject" name="subject" required>
                <option value="">Select Subject</option>
                <?php foreach ($subjects as $subj) { echo "<option value=\"$subj\">$subj</option>"; } ?>
            </select>

            <input type="submit" value="Search">
        </form>

        <?php if ($search): ?>
            <?php if (count($results) > 0): ?>
                <h2>Search Results:</h2>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Tutor ID</th>
                            <th>Tutor Name</th>
                            <th>Class Range</th>
                            <th>Subject</th>
                            <th>Location</th>
                            <th>Phone Number</th>
                            <th>Registered At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($results as $tutor): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($tutor['tutor_id']); ?></td>
                                <td><?php echo htmlspecialchars($tutor['tutor_name']); ?></td>
                                <td><?php echo htmlspecialchars($tutor['class_range_start']) . ' - ' . htmlspecialchars($tutor['class_range_end']); ?></td>
                                <td><?php echo htmlspecialchars($tutor['subject']); ?></td>
                                <td><?php echo htmlspecialchars($tutor['location']); ?></td>
                                <td><?php echo htmlspecialchars($tutor['phone_number']); ?></td>
                                <td><?php echo htmlspecialchars($tutor['created_at']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="message">No tutors found for the selected criteria.</p>
            <?php endif; ?>
        <?php endif; ?>
    </div>

</body>
</html>
