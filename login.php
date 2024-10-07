<?php
session_start();
include 'db_connect.php'; // Assuming you have this file for database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password']; // Don't escape the password

    // Use prepared statements
    $stmt = $conn->prepare("SELECT * FROM students WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['student_id'] = $user['student_id']; // Ensure this is set
            $_SESSION['name'] = $user['name'];

            // Redirect based on admin status
            header("Location: " . ($user['isadmin'] === 'yes' ? "adminindex.php" : "index.php"));
            exit();
        } else {
            $error = "Invalid login credentials.";
        }
    } else {
        $error = "No account found with that email.";
    }
}

// Check for messages passed via URL
$message = '';
if (isset($_GET['msg'])) {
    $message = htmlspecialchars($_GET['msg']);
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduHub | Login</title>
    <!-- Tailwind CSS & DaisyUI -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@2.28.0/dist/full.css" rel="stylesheet">
    <style>
        @keyframes bounceIn {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
        }

        .animated-bounce {
            animation: bounceIn 1s ease-in-out infinite;
        }

        body {
            background-color: #f5f5f5;
        }
    </style>
</head>

<body class="bg-gradient-to-r from-indigo-200 via-purple-300 to-pink-400 min-h-screen flex items-center justify-center">
    <div class="container max-w-md mx-auto">
        <div class="card shadow-xl rounded-lg p-8 bg-white animate__animated animate__fadeInDown">
            <h2 class="text-4xl font-bold text-center text-indigo-600 mb-6">Login to EduHub</h2>
            <form action="login.php" method="POST" class="space-y-6">
                <?php if (isset($error)): ?>
                    <p class="error text-center text-red-500"><?= htmlspecialchars($error) ?></p>
                <?php endif; ?>
                <?php if ($message): ?>
                    <p class="message text-center text-green-500"><?= $message ?></p>
                <?php endif; ?>
                <div class="form-control w-full">
                    <label for="email" class="label text-lg font-semibold text-gray-700">
                        <span class="label-text">Email</span>
                    </label>
                    <input type="email" name="email" placeholder="Enter your email"
                        class="input input-bordered w-full rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                </div>
                <div class="form-control w-full">
                    <label for="password" class="label text-lg font-semibold text-gray-700">
                        <span class="label-text">Password</span>
                    </label>
                    <input type="password" name="password" placeholder="Enter your password"
                        class="input input-bordered w-full rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                </div>
                <button type="submit"
                    class="btn w-full btn-primary hover:bg-indigo-600 transition-transform transform hover:scale-105 duration-300">
                    <svg class="h-5 w-5 inline-block mr-2" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M2.00098 11.999L16.001 11.999M16.001 11.999L12.501 8.99902M16.001 11.999L12.501 14.999"
                            stroke="#ffff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M9.00195 7C9.01406 4.82497 9.11051 3.64706 9.87889 2.87868C10.7576 2 12.1718 2 15.0002 2L16.0002 2C18.8286 2 20.2429 2 21.1215 2.87868C22.0002 3.75736 22.0002 5.17157 22.0002 8L22.0002 16C22.0002 18.8284 22.0002 20.2426 21.1215 21.1213C20.3531 21.8897 19.1752 21.9862 17 21.9983M9.00195 17C9.01406 19.175 9.11051 20.3529 9.87889 21.1213C10.5202 21.7626 11.4467 21.9359 13 21.9827"
                            stroke="#ffff" stroke-width="1.5" stroke-linecap="round" />
                    </svg>
                    Login
                </button>
            </form>
            <div class="text-center mt-6 text-sm">
                <p>Don't have an account?
                    <a href="register.php" class="text-indigo-600 hover:text-indigo-800 transition-colors">Register
                        here</a>
                </p>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest"></script>
</body>

</html>
