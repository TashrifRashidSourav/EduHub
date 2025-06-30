<?php $currentPage = basename($_SERVER['PHP_SELF']); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduVerse</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #52357B 0%, #4A2C6B 50%, #3F2459 100%);
            min-height: 100vh;
        }

        /* Modern Navbar Styling */
        .modern-navbar {
            background: rgba(44, 62, 80, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            padding: 0.8rem 0;
            transition: all 0.3s ease;
        }

        .modern-navbar.scrolled {
            background: rgba(44, 62, 80, 0.98);
            padding: 0.5rem 0;
        }

        /* Brand Styling */
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: #ffffff !important;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-left: 2rem;
            transition: all 0.3s ease;
        }

        .navbar-brand:hover {
            color: #74b9ff !important;
            transform: translateY(-1px);
        }

        .navbar-brand i {
            font-size: 1.8rem;
            color: #74b9ff;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        /* Navigation Links */
        .navbar-nav {
            gap: 0.5rem;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            padding: 0.6rem 1rem !important;
            border-radius: 8px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.95rem;
            text-decoration: none;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #74b9ff, #0984e3);
            transform: translateX(-50%);
            transition: width 0.3s ease;
        }

        .nav-link:hover {
            color: #ffffff !important;
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }

        .nav-link:hover::before {
            width: 80%;
        }

        .nav-link.active {
            background: linear-gradient(135deg, #370e69, #4A2C6B);
            color: #ffffff !important;
            box-shadow: 0 4px 15px rgba(116, 185, 255, 0.3);
            font-weight: 600;
        }

        .nav-link.active::before {
            width: 80%;
        }

        .nav-link i {
            font-size: 1rem;
            opacity: 0.9;
            transition: all 0.3s ease;
        }

        .nav-link:hover i {
            opacity: 1;
            transform: scale(1.1);
        }

        /* Dropdown Menus */
        .dropdown-menu {
            background: rgba(44, 62, 80, 0.98);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
            padding: 0.5rem 0;
            margin-top: 0.5rem;
            min-width: 220px;
            animation: dropdownSlide 0.3s ease-out;
        }

        @keyframes dropdownSlide {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .dropdown-item {
            color: rgba(255, 255, 255, 0.9) !important;
            padding: 0.7rem 1.2rem;
            font-weight: 500;
            border-radius: 8px;
            margin: 0.2rem 0.5rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.9rem;
        }

        .dropdown-item:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #ffffff !important;
            transform: translateX(5px);
        }

        .dropdown-item.active {
            background: linear-gradient(135deg, #370e69, #4A2C6B);
            color: #ffffff !important;
        }

        .dropdown-item i {
            width: 16px;
            text-align: center;
            opacity: 0.8;
        }

        /* Submenu Styling */
        .dropdown-submenu {
            position: relative;
        }

        .dropdown-submenu > .dropdown-menu {
            top: 0;
            left: 100%;
            margin-top: -6px;
            margin-left: -1px;
            border-radius: 12px;
            animation: submenuSlide 0.3s ease-out;
        }

        @keyframes submenuSlide {
            from {
                opacity: 0;
                transform: translateX(-10px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .dropdown-submenu:hover > .dropdown-menu {
            display: block;
        }

        .dropdown-submenu > .dropdown-item::after {
            content: '\f054';
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            margin-left: auto;
            font-size: 0.8rem;
            opacity: 0.6;
            transition: all 0.3s ease;
        }

        .dropdown-submenu:hover > .dropdown-item::after {
            opacity: 1;
            transform: translateX(3px);
        }

        /* Mobile Responsiveness */
        @media (max-width: 991.98px) {
            .navbar-brand {
                margin-left: 1rem;
            }
            
            .dropdown-submenu > .dropdown-menu {
                position: static;
                display: block;
                margin-left: 1rem;
                margin-top: 0.5rem;
                box-shadow: none;
                border: none;
                background: rgba(255, 255, 255, 0.05);
            }

            .dropdown-submenu > .dropdown-item::after {
                content: '\f078';
            }

            .nav-link::before {
                display: none;
            }
        }

        /* Smooth Scrolling Effect */
        html {
            scroll-behavior: smooth;
        }

        /* Container Adjustments */
        .navbar-collapse {
            background: rgba(44, 62, 80, 0.95);
            border-radius: 12px;
            margin-top: 0.5rem;
            padding: 1rem;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }

        @media (min-width: 992px) {
            .navbar-collapse {
                background: transparent;
                border-radius: 0;
                margin-top: 0;
                padding: 0;
                backdrop-filter: none;
                -webkit-backdrop-filter: none;
            }
        }

        /* Toggler Button */
        .navbar-toggler {
            border: none;
            padding: 0.5rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .navbar-toggler:focus {
            box-shadow: 0 0 0 0.2rem rgba(116, 185, 255, 0.3);
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 0.8%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        /* Loading Animation */
        .navbar {
            animation: slideDown 0.8s ease-out;
        }

        @keyframes slideDown {
            from {
                transform: translateY(-100%);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark modern-navbar fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">
            <i class="fas fa-graduation-cap"></i>
            EduVerse
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage == 'index.php' ? 'active' : '' ?>" href="index.php">
                        <i class="fas fa-home"></i> Home
                    </a>
                </li>
                
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= in_array($currentPage, ['profile.php', 'skills_development.php', 'ranking.php']) ? 'active' : '' ?>" href="#" id="userPanelDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user"></i> My Account
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="userPanelDropdown">
                        <li><a class="dropdown-item <?= $currentPage == 'profile.php' ? 'active' : '' ?>" href="profile.php"><i class="fas fa-id-badge"></i> Profile</a></li>
                        <li><a class="dropdown-item <?= $currentPage == 'skills_development.php' ? 'active' : '' ?>" href="skills_development.php"><i class="fas fa-tools"></i> My Skills</a></li>
                        <li><a class="dropdown-item <?= $currentPage == 'ranking.php' ? 'active' : '' ?>" href="ranking.php"><i class="fas fa-chart-line"></i> Ranking</a></li>
                    </ul>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage == 'posts.php' ? 'active' : '' ?>" href="posts.php">
                        <i class="fas fa-pen"></i> Posts
                    </a>
                </li>
                
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= in_array($currentPage, ['upload_books.php', 'buy_books.php', 'buy_items.php', 'upload_items.php']) ? 'active' : '' ?>" href="#" id="marketplaceDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-store"></i> Marketplace
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="marketplaceDropdown">
                        <li class="dropdown-submenu">
                            <a class="dropdown-item dropdown-toggle" href="#"><i class="fas fa-book"></i> Books</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item <?= $currentPage == 'upload_books.php' ? 'active' : '' ?>" href="upload_books.php"><i class="fas fa-upload"></i> Sell Books</a></li>
                                <li><a class="dropdown-item <?= $currentPage == 'buy_books.php' ? 'active' : '' ?>" href="buy_books.php"><i class="fas fa-shopping-bag"></i> Get Books</a></li>
                            </ul>
                        </li>
                        <li class="dropdown-submenu">
                            <a class="dropdown-item dropdown-toggle" href="#"><i class="fas fa-box"></i> Items</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item <?= $currentPage == 'buy_items.php' ? 'active' : '' ?>" href="buy_items.php"><i class="fas fa-shopping-cart"></i> Get Items</a></li>
                                <li><a class="dropdown-item <?= $currentPage == 'upload_items.php' ? 'active' : '' ?>" href="upload_items.php"><i class="fas fa-box-open"></i> Sell Items</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= in_array($currentPage, ['courses.php', 'my_courses.php', 'be_an_instructor.php']) ? 'active' : '' ?>" href="#" id="studyDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-book-reader"></i> Study
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="studyDropdown">
                        <li class="dropdown-submenu">
                            <a class="dropdown-item dropdown-toggle" href="#"><i class="fas fa-video"></i> Courses</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item <?= $currentPage == 'courses.php' ? 'active' : '' ?>" href="courses.php"><i class="fas fa-play-circle"></i> Get Course</a></li>
                                <li><a class="dropdown-item <?= $currentPage == 'my_courses.php' ? 'active' : '' ?>" href="my_courses.php"><i class="fas fa-folder-open"></i> My Courses</a></li>
                            </ul>
                        </li>
                        <li><a class="dropdown-item <?= $currentPage == 'be_an_instructor.php' ? 'active' : '' ?>" href="be_an_instructor.php"><i class="fas fa-chalkboard-teacher"></i> Apply for Instructor</a></li>
                    </ul>
                </li>
                
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= in_array($currentPage, ['blood.php', 'blood_find.php']) ? 'active' : '' ?>" href="#" id="bloodDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-tint"></i> Blood
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="bloodDropdown">
                        <li><a class="dropdown-item <?= $currentPage == 'blood.php' ? 'active' : '' ?>" href="blood.php"><i class="fas fa-hand-holding-medical"></i> Blood Donation</a></li>
                        <li><a class="dropdown-item <?= $currentPage == 'blood_find.php' ? 'active' : '' ?>" href="blood_find.php"><i class="fas fa-search"></i> Find Blood</a></li>
                    </ul>
                </li>
                
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= in_array($currentPage, ['add_tuition.php', 'view_tuitions.php', 'register_tutor.php', 'search_tutors.php']) ? 'active' : '' ?>" href="#" id="tuitionDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-graduate"></i> Tuition
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="tuitionDropdown">
                        <li><a class="dropdown-item <?= $currentPage == 'add_tuition.php' ? 'active' : '' ?>" href="add_tuition.php"><i class="fas fa-plus-circle"></i> Ask For Tutor</a></li>
                        <li><a class="dropdown-item <?= $currentPage == 'view_tuitions.php' ? 'active' : '' ?>" href="view_tuitions.php"><i class="fas fa-list"></i> Find Tuition</a></li>
                        <li><a class="dropdown-item <?= $currentPage == 'register_tutor.php' ? 'active' : '' ?>" href="register_tutor.php"><i class="fas fa-user-plus"></i> Become a Tutor</a></li>
                        <li><a class="dropdown-item <?= $currentPage == 'search_tutors.php' ? 'active' : '' ?>" href="search_tutors.php"><i class="fas fa-search"></i> Search Tutor</a></li>
                    </ul>
                </li>
                
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= in_array($currentPage, ['work.php', 'searchwork.php']) ? 'active' : '' ?>" href="#" id="workDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-briefcase"></i> Work
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="workDropdown">
                        <li><a class="dropdown-item <?= $currentPage == 'work.php' ? 'active' : '' ?>" href="work.php"><i class="fas fa-plus"></i> Add Work</a></li>
                        <li><a class="dropdown-item <?= $currentPage == 'searchwork.php' ? 'active' : '' ?>" href="searchwork.php"><i class="fas fa-search"></i> Search Work</a></li>
                    </ul>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage == 'chat.php' ? 'active' : '' ?>" href="chat.php">
                        <i class="fas fa-comments"></i> Chat
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage == 'search.php' ? 'active' : '' ?>" href="search.php">
                        <i class="fas fa-search"></i> Search
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage == 'logout.php' ? 'active' : '' ?>" href="logout.php">
                        <i class="fas fa-sign-out-alt"></i> 
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Add padding to body to account for fixed navbar -->
<div style="padding-top: 90px;"></div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

<script>
// Add scroll effect to navbar
window.addEventListener('scroll', function() {
    const navbar = document.querySelector('.modern-navbar');
    if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
    } else {
        navbar.classList.remove('scrolled');
    }
});

// Close mobile menu when clicking outside
document.addEventListener('click', function(event) {
    const navbar = document.querySelector('.navbar-collapse');
    const toggler = document.querySelector('.navbar-toggler');
    
    if (!navbar.contains(event.target) && !toggler.contains(event.target)) {
        const bsCollapse = new bootstrap.Collapse(navbar, {
            toggle: false
        });
        bsCollapse.hide();
    }
});
</script>

</body>
</html>