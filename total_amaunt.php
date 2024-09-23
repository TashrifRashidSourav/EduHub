<?php
session_start();
include 'db_connect.php'; // Database connection

// Check if the user is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['student_id'];
$wallet_balance = 0;
$wallet2_balance = 0;

// Fetch balance from the wallet table
$stmt = $conn->prepare("SELECT balance FROM wallet WHERE student_id = ?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $wallet_balance = $row['balance'];
}

// Fetch balance from the wallet2 table
$stmt2 = $conn->prepare("SELECT balance FROM wallet2 WHERE student_id = ?");
$stmt2->bind_param("i", $student_id);
$stmt2->execute();
$result2 = $stmt2->get_result();
if ($row2 = $result2->fetch_assoc()) {
    $wallet2_balance = $row2['balance'];
}

// Close the statements
$stmt->close();
$stmt2->close();
$conn->close();

// Calculate total amount
$total_amount = $wallet_balance - $wallet2_balance;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Total Amount</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
        }
        .amount {
            font-size: 24px;
            color: #333;
            text-align: center;
        }
        .negative {
            color: red;
            font-weight: bold;
            text-align: center;
        }
        a {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: white;
            background-color: #007BFF;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
        }
        a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Total Amount</h1>
        <div class="amount">
            Total: $<?php echo number_format($total_amount, 2); ?>
        </div>
        <?php if ($total_amount < 0): ?>
            <div class="negative">
                Negative Balance Detected!
            </div>
        <?php endif; ?>
        <a href="index.php">Go to Dashboard</a>
    </div>
</body>
</html>
