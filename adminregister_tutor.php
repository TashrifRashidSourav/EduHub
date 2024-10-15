<?php
require 'db_connect.php'; 

session_start();


if (!isset($_SESSION['student_id'])) {
    header("Location: login.php"); 
    exit();
}

$student_id = $_SESSION['student_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $class_range_start = isset($_POST['class_range_start']) ? $_POST['class_range_start'] : '';
    $class_range_end = isset($_POST['class_range_end']) ? $_POST['class_range_end'] : '';
    $subject = isset($_POST['subject']) ? $_POST['subject'] : '';
    $location = isset($_POST['location']) ? $_POST['location'] : '';
    $phone_number = isset($_POST['phone_number']) ? $_POST['phone_number'] : '';

   
    if ($class_range_start && $class_range_end && $subject && $location && $phone_number) {
        $sql = "INSERT INTO tutors (student_id, class_range_start, class_range_end, subject, location, phone_number)
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iissss", $student_id, $class_range_start, $class_range_end, $subject, $location, $phone_number);

        if ($stmt->execute()) {
            echo "<p class='success'>Tutor registration successful!</p>";
        } else {
            echo "<p class='error'>Error: " . $stmt->error . "</p>";
        }

        $stmt->close();
    } else {
        echo "<p class='error'>Please fill in all fields.</p>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register as Tutor</title>
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

        h1 {
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

        select, input[type="text"], button {
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        button {
            background-color: #007bff;
            color: white;
            cursor: pointer;
            margin-top: 20px;
            padding: 10px;
            border: none;
            border-radius: 5px;
        }

        button:hover {
            background-color: #0056b3;
        }

        .success {
            color: green;
            text-align: center;
            margin-top: 20px;
        }

        .error {
            color: red;
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <?php include 'navbaradmin.php'; ?>

    <div class="container">
        <h1>Register as a Tutor</h1>
        <form method="post" action="register_tutor.php">
            <label for="class_range_start">Class Range Start:</label>
            <select id="class_range_start" name="class_range_start" required>
                <option value="">Select Start Class</option>
                <?php for ($i = 1; $i <= 12; $i++) { echo "<option value=\"$i\">$i</option>"; } ?>
            </select>

            <label for="class_range_end">Class Range End:</label>
            <select id="class_range_end" name="class_range_end" required>
                <option value="">Select End Class</option>
                <?php for ($i = 1; $i <= 12; $i++) { echo "<option value=\"$i\">$i</option>"; } ?>
            </select>

            <label for="subject">Subject:</label>
            <select id="subject" name="subject" required>
                <option value="">Select Subject</option>
                <option value="Science">Science</option>
                <option value="Math">Math</option>
                <option value="English">English</option>
                <option value="Biology">Biology</option>
                <option value="Economics">Economics</option>
                <option value="Chemistry">Chemistry</option>
                <option value="Physics">Physics</option>
            </select>

            <label for="location">Location:</label>
            <select id="location" name="location" required>
                <option value="">Select Location</option>
                <option value="Mirpur">Mirpur</option>
                <option value="Dhanmondi">Dhanmondi</option>
                <option value="Khilkhet">Khilkhet</option>
                <option value="Banani">Banani</option>
                <option value="Uttara">Uttara</option>
                <option value="Mohammadpur">Mohammadpur</option>
                <option value="Bashundhara">Bashundhara</option>
                <option value="Gulshan">Gulshan</option>
            </select>

            <label for="phone_number">Phone Number:</label>
            <input type="text" id="phone_number" name="phone_number" required>

            <button type="submit">Register</button>
        </form>
    </div>

</body>
</html>
