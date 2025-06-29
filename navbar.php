<?php $currentPage = basename($_SERVER['PHP_SELF']); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduHub</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
body {
  background: #52357B;
}

.dropdown-submenu > .dropdown-menu {
    top: 0;
    left: 100%;
    margin-top: -1px;
    display: none;
    position: absolute;
}

.dropdown-submenu:hover > .dropdown-menu {
    display: block;
}

.navbar .nav-link.active {
    background-color: #370e69;
    border-radius: 5px;
    font-weight: bold;
    color: #ffffff !important;
    opacity: 1 !important;
}

.navbar .nav-link {
    display: flex;
    align-items: center;
    gap: 6px;
    opacity: 1;
    color: #ffffff !important;
}

.navbar .nav-link:hover {
    opacity: 1;
    color: #fff;
}
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark" style="background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);">

    <a class="navbar-brand" href="index.php" style="margin-left: 150px;"><i class="fas fa-graduation-cap"></i> EduHub</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mx-auto">
            <li class="nav-item">
                <a class="nav-link <?= $currentPage == 'index.php' ? 'active' : '' ?>" href="index.php"><i class="fas fa-home"></i> Home</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle <?= in_array($currentPage, ['profile.php', 'skills_development.php', 'ranking.php']) ? 'active' : '' ?>" href="#" id="userPanelDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user"></i> My Account</a>
                <ul class="dropdown-menu" aria-labelledby="userPanelDropdown">
                    <li><a class="dropdown-item <?= $currentPage == 'profile.php' ? 'active' : '' ?>" href="profile.php"><i class="fas fa-id-badge"></i> Profile</a></li>
                    <li><a class="dropdown-item <?= $currentPage == 'skills_development.php' ? 'active' : '' ?>" href="skills_development.php"><i class="fas fa-tools"></i> My Skills</a></li>
                    <li><a class="dropdown-item <?= $currentPage == 'ranking.php' ? 'active' : '' ?>" href="ranking.php"><i class="fas fa-chart-line"></i> Ranking</a></li>
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $currentPage == 'posts.php' ? 'active' : '' ?>" href="posts.php"><i class="fas fa-pen"></i> Posts</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle <?= in_array($currentPage, ['upload_books.php', 'buy_books.php', 'buy_items.php', 'upload_items.php']) ? 'active' : '' ?>" href="#" id="marketplaceDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-store"></i> Marketplace</a>
                <ul class="dropdown-menu" aria-labelledby="marketplaceDropdown">
                    <li class="dropdown-submenu">
                        <a class="dropdown-item dropdown-toggle" href="#">Books</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item <?= $currentPage == 'upload_books.php' ? 'active' : '' ?>" href="upload_books.php"><i class="fas fa-upload"></i> Sell Books</a></li>
                            <li><a class="dropdown-item <?= $currentPage == 'buy_books.php' ? 'active' : '' ?>" href="buy_books.php"><i class="fas fa-book"></i> Get Books</a></li>
                        </ul>
                    </li>
                    <li class="dropdown-submenu">
                        <a class="dropdown-item dropdown-toggle" href="#">Items</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item <?= $currentPage == 'buy_items.php' ? 'active' : '' ?>" href="buy_items.php"><i class="fas fa-shopping-cart"></i> Get Items</a></li>
                            <li><a class="dropdown-item <?= $currentPage == 'upload_items.php' ? 'active' : '' ?>" href="upload_items.php"><i class="fas fa-box-open"></i> Sell Items</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle <?= in_array($currentPage, ['courses.php', 'my_courses.php', 'be_an_instructor.php']) ? 'active' : '' ?>" href="#" id="studyDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-book-reader"></i> Study</a>
                <ul class="dropdown-menu" aria-labelledby="studyDropdown">
                    <li class="dropdown-submenu">
                        <a class="dropdown-item dropdown-toggle" href="#">Courses</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item <?= $currentPage == 'courses.php' ? 'active' : '' ?>" href="courses.php"><i class="fas fa-play-circle"></i> Get Course</a></li>
                            <li><a class="dropdown-item <?= $currentPage == 'my_courses.php' ? 'active' : '' ?>" href="my_courses.php"><i class="fas fa-folder-open"></i> My Courses</a></li>
                        </ul>
                    </li>
                    <li><a class="dropdown-item <?= $currentPage == 'be_an_instructor.php' ? 'active' : '' ?>" href="be_an_instructor.php"><i class="fas fa-chalkboard-teacher"></i> Apply for Instructor</a></li>
                </ul>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle <?= in_array($currentPage, ['blood.php', 'blood_find.php']) ? 'active' : '' ?>" href="#" id="bloodDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-tint"></i> Blood</a>
                <ul class="dropdown-menu" aria-labelledby="bloodDropdown">
                    <li><a class="dropdown-item <?= $currentPage == 'blood.php' ? 'active' : '' ?>" href="blood.php"><i class="fas fa-hand-holding-medical"></i> Blood Donation</a></li>
                    <li><a class="dropdown-item <?= $currentPage == 'blood_find.php' ? 'active' : '' ?>" href="blood_find.php"><i class="fas fa-search"></i> Find Blood</a></li>
                </ul>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle <?= in_array($currentPage, ['add_tuition.php', 'view_tuitions.php', 'register_tutor.php', 'search_tutors.php']) ? 'active' : '' ?>" href="#" id="tuitionDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user-graduate"></i> Tuition</a>
                <ul class="dropdown-menu" aria-labelledby="tuitionDropdown">
                    <li><a class="dropdown-item <?= $currentPage == 'add_tuition.php' ? 'active' : '' ?>" href="add_tuition.php"><i class="fas fa-plus-circle"></i> Ask For Tutor</a></li>
                    <li><a class="dropdown-item <?= $currentPage == 'view_tuitions.php' ? 'active' : '' ?>" href="view_tuitions.php"><i class="fas fa-list"></i> Find Tuition</a></li>
                    <li><a class="dropdown-item <?= $currentPage == 'register_tutor.php' ? 'active' : '' ?>" href="register_tutor.php"><i class="fas fa-user-plus"></i> Become a Tutor</a></li>
                    <li><a class="dropdown-item <?= $currentPage == 'search_tutors.php' ? 'active' : '' ?>" href="search_tutors.php"><i class="fas fa-search"></i> Search Tutor</a></li>
                </ul>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle <?= in_array($currentPage, ['work.php', 'searchwork.php']) ? 'active' : '' ?>" href="#" id="workDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-briefcase"></i> Work</a>
                <ul class="dropdown-menu" aria-labelledby="workDropdown">
                    <li><a class="dropdown-item <?= $currentPage == 'work.php' ? 'active' : '' ?>" href="work.php"><i class="fas fa-plus"></i> Add Work</a></li>
                    <li><a class="dropdown-item <?= $currentPage == 'searchwork.php' ? 'active' : '' ?>" href="searchwork.php"><i class="fas fa-search"></i> Search Work</a></li>
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $currentPage == 'chat.php' ? 'active' : '' ?>" href="chat.php"><i class="fas fa-comments"></i> Chat</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $currentPage == 'search.php' ? 'active' : '' ?>" href="search.php"><i class="fas fa-search"></i> Search</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $currentPage == 'logout.php' ? 'active' : '' ?>" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </li>
        </ul>
    </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
