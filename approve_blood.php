<?php
// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "EduHub");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get the request ID from the URL
$request_id = $_GET['id'];

// Update the blood donation status to 'accepted'
$query = "UPDATE blood_donation SET status = 'accepted' WHERE request_id = $request_id";
mysqli_query($conn, $query);

// Redirect back to admin index with success message
header("Location: adminindex.php?status=success");
exit;
?>
