<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduHub Navbar</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        .navbar {
            background-color: #007bff; /* Primary color */
        }

        
        .navbar-brand {
            font-weight: bold;
            color: #fff !important; /* White color for brand */
        }
        .nav-link {
            color: #fff !important; /* White color for links */
            transition: color 0.3s, background-color 0.3s;
        }
        .nav-link:hover {
            color: #007bff; /* Change text color on hover */
            background-color: rgba(255, 255, 255, 0.2); /* Background color on hover */
            border-radius: 5px; /* Rounded corners */
        }
        .dropdown-menu {
            background-color: #ffffff; /* Dropdown background color */
        }
        .dropdown-item {
            color: #007bff; /* Color for dropdown items */
            transition: background-color 0.3s;
        }
        .dropdown-item:hover {
            background-color: #007bff; /* Change background on hover */
            color: #fff; /* Change text color on hover */
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <a class="navbar-brand" href="index.php">EduHub</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mx-auto">
            <li class="nav-item">
                <a class="nav-link" href="index.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="profile.php">Profile</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="posts.php">Posts</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="booksDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Books</a>
                <ul class="dropdown-menu" aria-labelledby="booksDropdown">
                    <li><a class="dropdown-item" href="upload_books.php">Sell Books</a></li>
                    <li><a class="dropdown-item" href="buy_books.php">Buy Books</a></li>
                </ul>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="earnCostDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Earnings & Costs</a>
                <ul class="dropdown-menu" aria-labelledby="earnCostDropdown">
                    <li><a class="dropdown-item" href="transactions.php">Earn</a></li>
                    <li><a class="dropdown-item" href="transaction2.php">Costs</a></li>
                </ul>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="itemsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Items</a>
                <ul class="dropdown-menu" aria-labelledby="itemsDropdown">
                    <li><a class="dropdown-item" href="buy_items.php">Buy Items</a></li>
                    <li><a class="dropdown-item" href="upload_items.php">Sell Items</a></li>
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="skills_development.php">My Skills</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="coursesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Courses</a>
                <ul class="dropdown-menu" aria-labelledby="coursesDropdown">
                    <li><a class="dropdown-item" href="courses.php">Buy Course</a></li>
                    <li><a class="dropdown-item" href="my_courses.php">My Courses</a></li>
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="ranking.php">Ranking</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="be_an_instructor.php">Apply for Instructor</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="bloodDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Blood</a>
                <ul class="dropdown-menu" aria-labelledby="bloodDropdown">
                    <li><a class="dropdown-item" href="blood.php">Blood Donation</a></li>
                    <li><a class="dropdown-item" href="blood_find.php">Find Blood</a></li>
                </ul>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="tuitionDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Tuition</a>
                <ul class="dropdown-menu" aria-labelledby="tuitionDropdown">
                    <li><a class="dropdown-item" href="add_tuition.php">Ask For Tutor</a></li>
                    <li><a class="dropdown-item" href="view_tuitions.php">Find Tuition</a></li>
                    <li><a class="dropdown-item" href="register_tutor.php">Wanna be a Tutor</a></li>
                    <li><a class="dropdown-item" href="search_tutors.php">Search Tutor</a></li>
                </ul>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="workDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Work</a>
                <ul class="dropdown-menu" aria-labelledby="workDropdown">
                    <li><a class="dropdown-item" href="work.php">Add Work</a></li>
                    <li><a class="dropdown-item" href="searchwork.php">Search Work</a></li>
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="chat.php">Chat</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="search.php">Search</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Logout</a>
            </li>
        </ul>
    </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
