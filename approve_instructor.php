<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php"); // Redirect to login page
    exit();
}

// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "EduHub");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the instructor ID is provided
if (isset($_GET['id'])) {
    $instructor_id = $_GET['id'];

    // Prepare an SQL statement to update the instructor status
    $query = "UPDATE instructors SET status = 'approved' WHERE instructor_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $instructor_id);

    if ($stmt->execute()) {
        // Success: redirect back with a success status
        header("Location: adminindex.php?status=success");
    } else {
        // Failure: log the error (optional)
        error_log("Error approving instructor: " . $stmt->error);
        // Redirect back with an error message
        header("Location: adminindex.php?status=error");
    }

    // Close the prepared statement
    $stmt->close();
} else {
    // Redirect if no ID was provided
    header("Location: admin_index.php");
}

// Close the database connection
mysqli_close($conn);
?>
