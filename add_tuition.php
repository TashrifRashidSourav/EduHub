<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "eduhub";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_tuition'])) {
    $student_id = 1; 
    $class_level = $_POST['class_level'];
    $subject = $_POST['subject'];
    $location = $_POST['location'];
    $institution = $_POST['institution'];
    $phone_number = $_POST['phone_number'];
    $preferred_time = $_POST['preferred_time'];

    $sql = "INSERT INTO tuitions (student_id, class_level, subject, location, institution, phone_number, preferred_time)
            VALUES ('$student_id', '$class_level', '$subject', '$location', '$institution', '$phone_number', '$preferred_time')";

    if ($conn->query($sql) === TRUE) {
       
        header("Location: add_tuition.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post a Tuition Offer</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .container {
            margin-top: 50px;
        }
        .tuition-form {
            margin-bottom: 30px;
        }
        .navbar {
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
 
    <?php include 'navbar.php'; ?>

    <div class="container">
        <h1 class="text-center">Post a Tuition Offer</h1>
        
        <form method="post" class="tuition-form">
            <div class="form-group">
                <label for="class_level">Class Level</label>
                <select class="form-control" id="class_level" name="class_level" required>
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
                    <option value="Science">Science</option>
                    <option value="Math">Math</option>
                    <option value="English">English</option>
                    <option value="Biology">Biology</option>
                    <option value="Economics">Economics</option>
                    <option value="Chemistry">Chemistry</option>
                    <option value="Physics">Physics</option>
                </select>
            </div>

            <div class="form-group">
                <label for="location">Location</label>
                <select class="form-control" id="location" name="location" required>
                    <option value="Mirpur">Mirpur</option>
                    <option value="Dhanmondi">Dhanmondi</option>
                    <option value="Khilkhet">Khilkhet</option>
                    <option value="Banani">Banani</option>
                    <option value="Uttara">Uttara</option>
                    <option value="Mohammadpur">Mohammadpur</option>
                    <option value="Bashundhara">Bashundhara</option>
                    <option value="Gulshan">Gulshan</option>
                </select>
            </div>

            <div class="form-group">
                <label for="institution">Institution</label>
                <input type="text" class="form-control" id="institution" name="institution" required>
            </div>

            <div class="form-group">
                <label for="phone_number">Phone Number</label>
                <input type="text" class="form-control" id="phone_number" name="phone_number" required>
            </div>

            <div class="form-group">
                <label for="preferred_time">Preferred Time</label>
                <input type="text" class="form-control" id="preferred_time" name="preferred_time" required>
            </div>

            <button type="submit" class="btn btn-primary" name="submit_tuition">Submit</button>
        </form>
    </div>
</body>
</html>
