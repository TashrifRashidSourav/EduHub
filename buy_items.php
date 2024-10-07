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

// Fetch available items from the 'items' table where status is approved and not sold
$sql = "SELECT * FROM items WHERE status = 'approved' AND student_id != '$logged_in_user_id' AND issold = 'no'";
$result = $conn->query($sql);

// If the user clicks the "Buy" button
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['buy'])) {
    // Sanitize POST data
    $item_id = $conn->real_escape_string($_POST['item_id']);
    $buyer_id = $conn->real_escape_string($_POST['buyer_id']);
    $amount = $conn->real_escape_string($_POST['amount']);
    $seller_id = $conn->real_escape_string($_POST['seller_id']);

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Check if the item_id exists in the 'items' table and is not sold
        $checkItem = "SELECT * FROM items WHERE item_id = '$item_id' AND issold = 'no'";
        $itemResult = $conn->query($checkItem);

        if ($itemResult->num_rows > 0) {
            // Insert the transaction into the 'item_accounts' table
            $insertAccount = "INSERT INTO item_accounts (buyer_id, seller_id, item_id, transaction_id, amount, payment_status) 
                              VALUES ('$buyer_id', '$seller_id', '$item_id', NULL, '$amount', 'completed')";
            if (!$conn->query($insertAccount)) {
                throw new Exception("Error inserting into item_accounts: " . $conn->error);
            }

            // Update the item's issold status to 'yes'
            $updateItem = "UPDATE items SET issold = 'yes' WHERE item_id = '$item_id'";
            if (!$conn->query($updateItem)) {
                throw new Exception("Error updating item status: " . $conn->error);
            }

            // Commit transaction
            $conn->commit();
            echo "<script>alert('You have bought the item successfully!'); window.location.href='buy_items.php';</script>";
            exit(); // Ensure no further code is executed
        } else {
            throw new Exception("Item not found or already sold.");
        }
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buy Items</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f8f9fa; }
        .item-list { margin: 20px auto; max-width: 800px; }
        .item-card { border: 1px solid #ddd; border-radius: 5px; padding: 15px; margin-bottom: 15px; background-color: #ffffff; box-shadow: 0 2px 5px rgba(0,0,0,0.1); transition: transform 0.2s; }
        .item-card:hover { transform: scale(1.02); }
    </style>
</head>
<body>

<!-- Include Navbar -->
<?php include 'navbar.php'; ?>

<div class="container mt-5">
    <h1 class="mb-4 text-center">Available Items</h1>

    <div class="item-list">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='item-card'>";
                echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
                echo "<p>Price: $" . htmlspecialchars($row['price']) . "</p>";
                echo "<p>Condition: " . htmlspecialchars($row['conditions']) . "</p>";
                echo "<p>Category: " . htmlspecialchars($row['category']) . "</p>";

                // Fetch the seller's ID from the student who added the item
                $seller_id = $row['student_id'];

                // Display the purchase form
                echo "<form method='POST' action=''>
                        <input type='hidden' name='item_id' value='" . htmlspecialchars($row['item_id']) . "'>
                        <input type='hidden' name='buyer_id' value='" . htmlspecialchars($logged_in_user_id) . "'>
                        <input type='hidden' name='seller_id' value='" . htmlspecialchars($seller_id) . "'>
                        <input type='hidden' name='amount' value='" . htmlspecialchars($row['price']) . "'>
                        <button type='submit' name='buy' class='btn btn-primary'>Buy Now</button> <!-- Bootstrap Primary Button -->
                      </form>";
                echo "</div>";
            }
        } else {
            echo "<p>No items available at the moment.</p>";
        }
        ?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
