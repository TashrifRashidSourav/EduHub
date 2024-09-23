<?php
// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "EduHub");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get the post ID from the URL
$post_id = $_GET['id'];

// Update the post status to rejected
$query = "UPDATE posts SET status = 'rejected' WHERE post_id = $post_id";
mysqli_query($conn, $query);

// Redirect back to admin index with success message
header("Location: adminindex.php?status=success");
exit;
?>
