<?php
include 'db_connect.php';

$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $otp = mysqli_real_escape_string($conn, $_POST['otp']);

    // Use prepared statements to prevent SQL injection
    $check_sql = "SELECT * FROM students WHERE email = ? AND otp = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("ss", $email, $otp);
    $stmt->execute();
    $check_result = $stmt->get_result();

    if ($check_result->num_rows > 0) {
        // If OTP is valid, set is_verified to 1
        $update_sql = "UPDATE students SET is_verified = 1 WHERE email = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("s", $email);

        if ($update_stmt->execute()) {
            header("Location: login.php?msg=Your account has been verified. Please login.");
            exit();
        } else {
            $error = "Failed to verify OTP. Please try again.";
        }
    } else {
        $error = "Invalid OTP. Please check and try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduHub | Verify OTP</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@1.14.1/dist/full.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-r from-indigo-400 via-purple-500 to-pink-400 min-h-screen flex items-center justify-center">
    <div class="container mx-auto">
        <div class="flex justify-center">
            <div class="w-full max-w-md">
                <div class="card bg-white p-8 rounded-3xl shadow-xl mt-10">
                    <h2 class="text-center text-3xl font-bold text-indigo-600 mb-6">Verify Your OTP</h2>

                    <!-- Success or Error Message -->
                    <?php if ($error): ?>
                        <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <form action="verify_otp.php" method="POST">
                        <input type="hidden" name="email" value="<?= htmlspecialchars($_GET['email']) ?>">
                        <div class="form-control mb-5">
                            <input type="text" name="otp" class="input input-bordered w-full"
                                placeholder="Enter your OTP" required>
                        </div>
                        <button type="submit"
                            class="btn btn-primary w-full bg-indigo-600 hover:bg-indigo-700 transition duration-200">Verify OTP</button>
                    </form>
                    <div class="text-center mt-4">
                        <p class="text-sm">Didn't receive an OTP? <a href="register.php"
                                class="text-indigo-600 hover:underline">Resend OTP</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
