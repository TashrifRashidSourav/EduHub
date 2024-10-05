<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Secure password hashing

    // Check if the email already exists
    $check_sql = "SELECT * FROM students WHERE email = '$email'";
    $check_result = mysqli_query($conn, $check_sql);
    if (mysqli_num_rows($check_result) > 0) {
        $error = "Email already exists. Please use a different email.";
    } else {
        // Insert the user into the students table
        $sql = "INSERT INTO students (name, email, password) VALUES ('$name', '$email', '$hashed_password')";
        if (mysqli_query($conn, $sql)) {
            $success = "Registration successful. <a href='login.php'>Login here</a>";
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduHub | Register</title>
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- DaisyUI -->
    <link href="https://cdn.jsdelivr.net/npm/daisyui@1.14.1/dist/full.css" rel="stylesheet">
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-color: #f0f4ff;
        }

        .card {
            animation: scaleUp 0.5s ease-in-out;
        }

        @keyframes scaleUp {
            from {
                transform: scale(0.95);
                opacity: 0;
            }

            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        .input:focus {
            box-shadow: 0 0 0 2px rgba(67, 56, 202, 0.5);
        }
    </style>
</head>

<body class="bg-gradient-to-r from-indigo-400 via-purple-500 to-pink-400 min-h-screen flex items-center justify-center">

    <div class="container mx-auto">
        <div class="flex justify-center">
            <div class="w-full max-w-md">
                <div class="card bg-white p-8 rounded-3xl shadow-xl mt-10">
                    <h2 class="text-center text-3xl font-bold text-indigo-600 mb-6">Register for EduHub</h2>

                    <!-- Success or Error Message -->
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger text-center"><?= $error ?></div>
                    <?php endif; ?>
                    <?php if (isset($success)): ?>
                        <div class="alert alert-success text-center"><?= $success ?></div>
                    <?php endif; ?>

                    <form action="register.php" method="POST">
                        <div class="form-control mb-5">
                            <label for="name" class="label flex items-center">
                                <i class="fas fa-user text-indigo-500 mr-2"></i>
                            </label>
                            <input type="text" name="name" class="input input-bordered w-full"
                                placeholder="Enter your name" required>
                        </div>
                        <div class="form-control mb-5">
                            <label for="email" class="label flex items-center">
                                <i class="fas fa-envelope text-indigo-500 mr-2"></i>
                            </label>
                            <input type="email" name="email" class="input input-bordered w-full"
                                placeholder="Enter your email" required>
                        </div>
                        <div class="form-control mb-5">
                            <label for="password" class="label flex items-center">
                                <i class="fas fa-lock text-indigo-500 mr-2"></i>
                            </label>
                            <input type="password" name="password" class="input input-bordered w-full"
                                placeholder="Enter your password" required>
                        </div>
                        <button type="submit"
                            class="btn btn-primary w-full bg-indigo-600 hover:bg-indigo-700 transition duration-200">Register</button>
                    </form>
                    <div class="text-center mt-4">
                        <p class="text-sm">Already have an account? <a href="login.php"
                                class="text-indigo-600 hover:underline">Login here</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tailwind CSS & DaisyUI -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/forms/dist/forms.js"></script>
</body>

</html>
