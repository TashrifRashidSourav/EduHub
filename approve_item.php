<?php
// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "EduHub");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get the item ID from the URL
$item_id = $_GET['id'];

// Update the item status to 'approved'
$query = "UPDATE items SET status = 'approved' WHERE item_id = $item_id";
mysqli_query($conn, $query);

// Redirect back to admin index with success message
header("Location: adminindex.php?status=success");
exit;
?>
