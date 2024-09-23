<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduHub</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">    -->
     <!-- upper line icon add -->

    <link rel="stylesheet" href="style.css"> <!-- Link to your external CSS file if any -->
    <style>
        /* Custom styles */
        body {
            background-color: #f4f4f9;
        }
        main {
            padding: 60px 20px;
            text-align: center;
        }
        h1 {
            font-size: 3rem;
            margin-bottom: 20px;
            color: #333;
        }
        p {
            font-size: 1.2rem;
            color: #555;
        }
        .navbar {
            background-color: #007bff; /* Bootstrap primary color */
        }
        .navbar-nav .nav-link {
            color: #fff !important;
            font-weight: bold;
        }
        .navbar-nav .nav-link:hover {
            color: #f0f0f0 !important;
        }
    </style>
</head>
<body>
    <!-- Include Navbar -->
    <?php include 'navbar.php'; ?>

    <!-- Main Content -->
    <main>
        <h1>Welcome to EduHub</h1>
        <p>Your comprehensive educational platform!</p>
    </main>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
