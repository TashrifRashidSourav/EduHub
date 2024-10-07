<?php
session_start();
include 'db_connect.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_donor'])) {
    $student_id = $_SESSION['student_id'];
    $location = $_POST['location'];
    $blood_type = $_POST['blood_type'];
    $phone_number = $_POST['phone_number'];
    $last_donation_date = !empty($_POST['last_donation_date']) ? $_POST['last_donation_date'] : 'Not Yet';
    
    $sql = "INSERT INTO blood_find (student_id, location, blood_type, phone_number, last_donation_date) 
            VALUES ('$student_id', '$location', '$blood_type', '$phone_number', '$last_donation_date')";
    
    if (mysqli_query($conn, $sql)) {
        echo "<p class='success'>Blood information successfully added!</p>";
    } else {
        echo "<p class='error'>Error: " . mysqli_error($conn) . "</p>";
    }
}

$search_results = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
    $search_location = $_POST['search_location'];
    $search_blood_type = $_POST['search_blood_type'];

    $search_query = "SELECT students.name, blood_find.phone_number, blood_find.last_donation_date 
                     FROM blood_find 
                     JOIN students ON blood_find.student_id = students.student_id 
                     WHERE blood_find.location = '$search_location' 
                     AND blood_find.blood_type = '$search_blood_type'";
    
    $result = mysqli_query($conn, $search_query);
    
    if (mysqli_num_rows($result) > 0) {
        $search_results = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        echo "<p class='error'>No donors found for the selected criteria.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Find</title>
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

        select, input[type="text"], input[type="date"], button {
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
        }

        button:hover {
            background-color: #0056b3;
        }

        .message, .results {
            margin-top: 20px;
            text-align: center;
        }

        .results {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            margin-top: 30px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        .result-item {
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f1f1f1;
            display: flex;
            justify-content: space-between;
        }

        .result-item strong {
            color: #333;
        }

        .error {
            color: red;
        }

        .success {
            color: green;
        }
    </style>
</head>
<body>

    <?php include 'navbaradmin.php'; ?>
    
    <div class="container">
        <h1>Blood Find Information</h1>

     
        <form action="blood_find.php" method="POST">
            <label for="location">Location:</label>
            <select name="location" required>
                <option value="Mirpur">Mirpur</option>
                <option value="Dhanmondi">Dhanmondi</option>
                <option value="Khilkhet">Khilkhet</option>
                <option value="Uttara">Uttara</option>
                <option value="Banani">Banani</option>
                <option value="Gulshan">Gulshan</option>
                <option value="Motijheel">Motijheel</option>
                <option value="Badda">Badda</option>
                <option value="Farmgate">Farmgate</option>
                <option value="Mohammadpur">Mohammadpur</option>
                <option value="Shahbagh">Shahbagh</option>
            </select>

            <label for="blood_type">Blood Type:</label>
            <select name="blood_type" required>
                <option value="A+">A+</option>
                <option value="A-">A-</option>
                <option value="B+">B+</option>
                <option value="B-">B-</option>
                <option value="AB+">AB+</option>
                <option value="AB-">AB-</option>
                <option value="O+">O+</option>
                <option value="O-">O-</option>
            </select>

            <label for="phone_number">Phone Number:</label>
            <input type="text" name="phone_number" required placeholder="Enter your phone number">

            <label for="last_donation_date">Last Donation Date (optional):</label>
            <input type="date" name="last_donation_date">

            <button type="submit" name="submit_donor">Submit</button>
        </form>

        <!-- Search form to find donors -->
        <h2>Find Donors</h2>
        <form action="blood_find.php" method="POST">
            <label for="search_location">Location:</label>
            <select name="search_location" required>
                <option value="Mirpur">Mirpur</option>
                <option value="Dhanmondi">Dhanmondi</option>
                <option value="Khilkhet">Khilkhet</option>
                <option value="Uttara">Uttara</option>
                <option value="Banani">Banani</option>
                <option value="Gulshan">Gulshan</option>
                <option value="Motijheel">Motijheel</option>
                <option value="Badda">Badda</option>
                <option value="Farmgate">Farmgate</option>
                <option value="Mohammadpur">Mohammadpur</option>
                <option value="Shahbagh">Shahbagh</option>
            </select>

            <label for="search_blood_type">Blood Type:</label>
            <select name="search_blood_type" required>
                <option value="A+">A+</option>
                <option value="A-">A-</option>
                <option value="B+">B+</option>
                <option value="B-">B-</option>
                <option value="AB+">AB+</option>
                <option value="AB-">AB-</option>
                <option value="O+">O+</option>
                <option value="O-">O-</option>
            </select>

            <button type="submit" name="search">Search Donors</button>
        </form>

        <!-- Display search results -->
        <?php if (!empty($search_results)): ?>
            <div class="results">
                <h3>Matching Donors:</h3>
                <?php foreach ($search_results as $donor): ?>
                    <div class="result-item">
                        <strong>Name:</strong> <?php echo $donor['name']; ?>
                        <strong>Phone:</strong> <?php echo $donor['phone_number']; ?>
                        <strong>Last Donation:</strong> <?php echo $donor['last_donation_date']; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>
