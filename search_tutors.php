<?php
require 'db_connect.php';

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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        .hero-section {
            background: linear-gradient(135deg, rgba(30, 60, 114, 0.95) 0%, rgba(42, 82, 152, 0.95) 100%);
            padding: 100px 20px 60px;
            text-align: center;
            position: relative;
        }

        .hero-icon {
            font-size: 4rem;
            color: #ffd700;
            margin-bottom: 20px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.1); opacity: 0.8; }
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .hero-subtitle {
            font-size: 1.3rem;
            opacity: 0.9;
        }

        .main-container {
            max-width: 900px;
            margin: -40px auto 50px;
            padding: 40px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(12px);
            border-radius: 20px;
            box-shadow: 0 0 40px rgba(0,0,0,0.3);
        }

        .form-group label {
            font-weight: 600;
            color: #fff;
        }

        .form-control {
            border-radius: 10px;
            padding: 15px;
            background-color: rgba(255,255,255,0.15);
            color: #fff;
            border: none;
        }

        .form-control::placeholder {
            color: #ddd;
        }

        .form-control:focus {
            background-color: rgba(255,255,255,0.2);
            outline: none;
            box-shadow: 0 0 10px #ffd700;
        }

        .btn-search {
            background: linear-gradient(135deg, #f7971e 0%, #ffd200 100%);
            border: none;
            padding: 15px 40px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1.2rem;
            color: #000;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-search:hover {
            background: linear-gradient(135deg, #ffd200, #f7971e);
        }

        .results-table {
            margin-top: 40px;
        }

        .results-table table {
            width: 100%;
            background-color: rgba(255,255,255,0.95);
            color: #000;
            border-radius: 10px;
            overflow: hidden;
        }

        .results-table th {
            background-color: #1e3c72;
            color: black;
        }

        .results-table td, .results-table th {
            padding: 15px;
            text-align: center;
        }

        .message {
            background-color: #ffcccc;
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            color: #000;
            font-weight: bold;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="hero-section">
    <div class="hero-icon"><i class="fas fa-user-graduate"></i></div>
    <h1 class="hero-title">Find Your Ideal Tutor</h1>
    <p class="hero-subtitle">Search the best tutors by your class & subject and connect instantly</p>
</div>

<div class="main-container">
    <form method="post" action="search_tutors.php">
        <div class="form-group">
            <label for="class">Select Your Class</label>
            <select id="class" name="class" class="form-control" required>
                <option value="">Select Class</option>
                <?php for ($i = 1; $i <= 12; $i++) echo "<option value=\"$i\">Class $i</option>"; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="subject">Choose Subject</label>
            <select id="subject" name="subject" class="form-control" required>
                <option value="">Select Subject</option>
                <?php foreach ($subjects as $subj) echo "<option value=\"$subj\">$subj</option>"; ?>
            </select>
        </div>

        <div class="text-center mt-4">
            <button type="submit" class="btn-search">Search Tutor</button>
        </div>
    </form>

    <?php if ($search): ?>
        <?php if (count($results) > 0): ?>
            <div class="results-table">
                <h3 class="text-center mt-5">Available Tutors:</h3>
                <table class="table table-bordered mt-3">
                    <thead>
                        <tr>
                            <th>Tutor ID</th>
                            <th>Name</th>
                            <th>Class Range</th>
                            <th>Subject</th>
                            <th>Location</th>
                            <th>Phone</th>
                            <th>Registered</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($results as $tutor): ?>
                            <tr>
                                <td><?= htmlspecialchars($tutor['tutor_id']) ?></td>
                                <td><?= htmlspecialchars($tutor['tutor_name']) ?></td>
                                <td><?= htmlspecialchars($tutor['class_range_start']) . ' - ' . htmlspecialchars($tutor['class_range_end']) ?></td>
                                <td><?= htmlspecialchars($tutor['subject']) ?></td>
                                <td><?= htmlspecialchars($tutor['location']) ?></td>
                                <td><?= htmlspecialchars($tutor['phone_number']) ?></td>
                                <td><?= htmlspecialchars($tutor['created_at']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="message">No tutors found for the selected criteria.</div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<div id="footer-placeholder"></div>
<script>
    fetch('footer.html')
        .then(res => res.text())
        .then(data => {
            document.getElementById('footer-placeholder').innerHTML = data;
        });
</script>
</body>
</html>
