<?php
session_start(); // Start the session

// Check if student_id is set in the session
if (!isset($_SESSION['student_id'])) {
    // Redirect to login page if student_id is not set
    header("Location: login.php");
    exit();
}

$logged_in_user_id = $_SESSION['student_id']; // Retrieve student_id from session

// Include your database connection
include('db_connect.php');

// Fetch available books from the 'books' table where status is approved, not uploaded by the current user, and not sold
$sql = "SELECT * FROM books WHERE status = 'approved' AND student_id != '$logged_in_user_id' AND issold = 'no'";
$result = $conn->query($sql);

// If the user clicks the "Buy" button
if (isset($_POST['buy'])) {
    // Sanitize POST data
    $book_id = $conn->real_escape_string($_POST['book_id']);
    $buyer_id = $conn->real_escape_string($_POST['buyer_id']);
    $amount = $conn->real_escape_string($_POST['amount']);
    $seller_id = $conn->real_escape_string($_POST['seller_id']);

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Check if the book_id exists in the 'books' table and is not sold
        $checkBook = "SELECT * FROM books WHERE book_id = '$book_id' AND issold = 'no'";
        $bookResult = $conn->query($checkBook);

        if ($bookResult->num_rows > 0) {
            // Insert the transaction into the 'book_accounts' table
            $insertAccount = "INSERT INTO book_accounts (buyer_id, seller_id, book_id, transaction_id, amount, payment_status) 
                              VALUES ('$buyer_id', '$seller_id', '$book_id', NULL, '$amount', 'completed')";
            if (!$conn->query($insertAccount)) {
                throw new Exception("Error inserting into book_accounts: " . $conn->error);
            }

            // Update the book's issold status to 'yes'
            $updateBook = "UPDATE books SET issold = 'yes' WHERE book_id = '$book_id'";
            if (!$conn->query($updateBook)) {
                throw new Exception("Error updating book status: " . $conn->error);
            }

            // Commit transaction
            $conn->commit();

            // Display success message and reload the page to prevent form resubmission
            echo "<script>alert('You have bought the book successfully!'); window.location.reload();</script>";
        } else {
            throw new Exception("Book not found or already sold.");
        }
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buy Books</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        .book-list {
            margin: 20px auto;
        }
        .book-card {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 15px;
            background-color: #ffffff;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }
        .book-card:hover {
            transform: scale(1.02);
        }
        .btn-buy {
            background-color: #007bff;
            color: white;
        }
        .btn-buy:hover {
            background-color: #0056b3;
        }
        .book-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            margin-bottom: 15px;
        }
        .card-title {
            font-size: 18px;
            font-weight: bold;
        }
        .card-text {
            font-size: 14px;
        }
    </style>
</head>
<body>

<!-- Include Navbar -->
<?php include 'navbaradmin.php'; ?>

<div class="container mt-5">
    <h1 class="mb-4 text-center">Available Books</h1>

    <div class="row">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='col-lg-3 col-md-6 mb-4'>";
                echo "<div class='book-card'>";
                echo "<h3 class='card-title'>" . htmlspecialchars($row['title']) . " by " . htmlspecialchars($row['author']) . "</h3>";
                echo "<p class='card-text'>Price: $" . htmlspecialchars($row['price']) . "</p>";
                echo "<p class='card-text'>Condition: " . htmlspecialchars($row['conditions']) . "</p>";

                // Display book image
                if (!empty($row['image_path'])) {
                    echo "<img src='" . htmlspecialchars($row['image_path']) . "' alt='Book Image' class='book-image'>";
                } else {
                    echo "<img src='default-book.png' alt='Default Book Image' class='book-image'>";
                }

                // Fetch the seller's ID from the student who added the book
                $seller_id = $row['student_id'];

                // Display the purchase form
                echo "<form method='POST' action=''>
                        <input type='hidden' name='book_id' value='" . htmlspecialchars($row['book_id']) . "'>
                        <input type='hidden' name='buyer_id' value='" . htmlspecialchars($logged_in_user_id) . "'>
                        <input type='hidden' name='seller_id' value='" . htmlspecialchars($seller_id) . "'>
                        <input type='hidden' name='amount' value='" . htmlspecialchars($row['price']) . "'>
                        <button type='submit' name='buy' class='btn btn-buy btn-block'>Buy Now</button>
                      </form>";
                echo "</div>"; // book-card
                echo "</div>"; // col-lg-3
            }
        } else {
            echo "<p>No books available at the moment.</p>";
        }
        ?>
    </div>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
