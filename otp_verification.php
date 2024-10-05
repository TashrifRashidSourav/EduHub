<?php
session_start(); // Start the session

// Check if the user is already verified or if they are trying to access this page without a session variable
if (isset($_SESSION['is_verified']) && $_SESSION['is_verified'] == 1) {
    echo "Your account is already verified.";
    exit;
}

// Process OTP submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $entered_otp = $_POST['otp'];
    
    // Retrieve the actual OTP from the session or database
    // For this example, we assume the OTP is stored in the session
    $actual_otp = $_SESSION['otp'];

    // Check if the entered OTP matches the actual OTP
    if ($entered_otp == $actual_otp) {
        // If the OTP is correct, update the user's verification status in the database
        // Assuming you have a database connection in db_connect.php
        include 'db_connect.php';
        
        $student_id = $_SESSION['student_id']; // Assuming you stored the student ID in the session
        
        $sql = "UPDATE students SET is_verified = 1 WHERE student_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $student_id);
        
        if ($stmt->execute()) {
            $_SESSION['is_verified'] = 1; // Update session variable
            echo "Your account has been verified successfully!";
        } else {
            echo "Error updating record: " . $conn->error;
        }
        
        $stmt->close();
        $conn->close();
    } else {
        $error_message = "Invalid OTP. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>OTP Verification</h2>
    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger"><?php echo $error_message; ?></div>
    <?php endif; ?>
    <form action="" method="post">
        <div class="form-group">
            <label for="otp">Enter your OTP:</label>
            <input type="text" class="form-control" name="otp" id="otp" required>
        </div>
        <button type="submit" class="btn btn-primary">Verify OTP</button>
    </form>
</div>
</body>
</html>
