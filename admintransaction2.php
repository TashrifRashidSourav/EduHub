<?php
session_start();

// Assuming you have a session variable 'student_id' after login
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

$student_id = $_SESSION['student_id'];

// Include your database connection
include('db_connect.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Wallet</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
        }
        h1 {
            margin-bottom: 30px;
            text-align: center;
            color: #333;
        }
        .table {
            background-color: #fff;
        }
        .table thead th {
            background-color: #007BFF;
            color: white;
        }
        .table tbody tr:hover {
            background-color: #f1f1f1;
        }
        .balance-total {
            font-size: 1.5rem;
            font-weight: bold;
            text-align: right;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <!-- Include Navbar -->
    <?php include 'navbaradmin.php'; ?>

    <div class="container">
        <h1>My Wallet Information</h1>
        <?php
        // Fetch wallet information with buyer names
        $query = "
            SELECT w.balance, w.material, w.`from`, w.created_at, s.name AS buyer_name 
            FROM wallet2 w 
            JOIN students s ON w.`from` = s.student_id 
            WHERE w.student_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $student_id);
        $stmt->execute();
        $result = $stmt->get_result();

        // Calculate total balance
        $total_balance = 0;
        ?>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Material (Book Name)</th>
                    <th>From (Buyer Name)</th>
                    <th>Amount</th>
                    <th>Transaction Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $total_balance += $row['balance'];
                        echo "<tr>
                                <td>" . htmlspecialchars($row['material']) . "</td>
                                <td>" . htmlspecialchars($row['buyer_name']) . "</td>
                                <td>$" . htmlspecialchars(number_format($row['balance'], 2)) . "</td>
                                <td>" . htmlspecialchars(date('F j, Y', strtotime($row['created_at']))) . "</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4' class='text-center'>No wallet information found.</td></tr>";
                }

                $stmt->close();
                $conn->close();
                ?>
            </tbody>
        </table>

        <div class="balance-total">
            Total Cost: $<?php echo number_format($total_balance, 2); ?>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
