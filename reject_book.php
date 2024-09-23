<?php
$conn = mysqli_connect("localhost", "root", "", "EduHub");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$book_id = $_GET['id'];
$query = "UPDATE books SET status = 'rejected' WHERE book_id = $book_id";
mysqli_query($conn, $query);

header("Location: adminindex.php?status=success");
exit;
?>
