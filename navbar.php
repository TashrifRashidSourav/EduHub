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

    <!-- Modern Navbar Styles -->
    <style>
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
            margin-right: 2rem;
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

        .nav-link:hover {
            color: #ffffff !important;
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }

        .nav-link.active {
            background: linear-gradient(135deg, #370e69, #4A2C6B);
            color: #ffffff !important;
            box-shadow: 0 4px 15px rgba(116, 185, 255, 0.3);
            font-weight: 600;
        }

        .nav-link i {
            font-size: 1rem;
            opacity: 0.9;
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
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
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

        .dropdown-item i {
            width: 20px;
            text-align: center;
            color: #74b9ff;
        }

        /* Submenu */
        .dropdown-submenu { position: relative; }
        .dropdown-submenu > .dropdown-menu {
            top: 0;
            left: 100%;
            margin-top: -6px;
            margin-left: -1px;
            border-radius: 12px;
        }
        .dropdown-submenu:hover > .dropdown-menu { display: block; }
        .dropdown-submenu > .dropdown-item::after {
            content: '\f054';
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            margin-left: auto;
            font-size: 0.8rem;
            opacity: 0.6;
        }

        /* Mobile */
        @media (max-width: 991.98px) {
            .dropdown-submenu > .dropdown-menu {
                position: static;
                display: block;
                margin-left: 1rem;
                background: rgba(255,255,255,0.05);
            }
        }
        
        /* Loading Animation */
        .navbar { animation: slideDown 0.8s ease-out; }
        @keyframes slideDown { from { transform: translateY(-100%); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
    </style>

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
            <!-- Main Menu (Left) -->
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage == 'index.php' ? 'active' : '' ?>" href="index.php">
                        <i class="fas fa-home"></i> Home
                    </a>
                </li>
                
                <!-- Academics Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= in_array($currentPage, ['courses.php', 'my_courses.php', 'be_an_instructor.php', 'view_tuitions.php', 'add_tuition.php', 'skills_development.php']) ? 'active' : '' ?>" href="#" id="academicsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-book-reader"></i> Academics
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="academicsDropdown">
                        <li><h6 class="dropdown-header text-muted">Courses</h6></li>
                        <li><a class="dropdown-item" href="courses.php"><i class="fas fa-search"></i> Browse Courses</a></li>
                        <li><a class="dropdown-item" href="my_courses.php"><i class="fas fa-chalkboard"></i> My Learning</a></li>
                        <li><hr class="dropdown-divider bg-light opacity-25"></li>
                        <li><h6 class="dropdown-header text-muted">Tuition & Skills</h6></li>
                        <li><a class="dropdown-item" href="view_tuitions.php"><i class="fas fa-chalkboard-teacher"></i> Find Tuition</a></li>
                        <li><a class="dropdown-item" href="add_tuition.php"><i class="fas fa-hand-paper"></i> Request Tutor</a></li>
                        <li><a class="dropdown-item" href="skills_development.php"><i class="fas fa-tools"></i> Skill Development</a></li>
                        <li><a class="dropdown-item" href="be_an_instructor.php"><i class="fas fa-user-tie"></i> Become Instructor</a></li>
                    </ul>
                </li>

                <!-- Marketplace Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= in_array($currentPage, ['buy_books.php', 'upload_books.php', 'buy_items.php', 'upload_items.php']) ? 'active' : '' ?>" href="#" id="marketDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-store"></i> Marketplace
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="marketDropdown">
                        <li class="dropdown-submenu">
                            <a class="dropdown-item dropdown-toggle" href="#"><i class="fas fa-book"></i> Books</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="buy_books.php">Buy Books</a></li>
                                <li><a class="dropdown-item" href="upload_books.php">Sell Books</a></li>
                            </ul>
                        </li>
                        <li class="dropdown-submenu">
                            <a class="dropdown-item dropdown-toggle" href="#"><i class="fas fa-box"></i> Items</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="buy_items.php">Buy Items</a></li>
                                <li><a class="dropdown-item" href="upload_items.php">Sell Items</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>

                <!-- Community Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= in_array($currentPage, ['posts.php', 'chat.php', 'blood.php', 'ranking.php']) ? 'active' : '' ?>" href="#" id="communityDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-users"></i> Community
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="communityDropdown">
                        <li><a class="dropdown-item" href="posts.php"><i class="fas fa-pen-nib"></i> Posts & Forum</a></li>
                        <li><a class="dropdown-item" href="chat.php"><i class="fas fa-comments"></i> Chat</a></li>
                        <li><a class="dropdown-item" href="ranking.php"><i class="fas fa-trophy"></i> Rankings</a></li>
                        <li><hr class="dropdown-divider bg-light opacity-25"></li>
                        <li><a class="dropdown-item" href="blood.php"><i class="fas fa-heart"></i> Blood Donation</a></li>
                    </ul>
                </li>
                
                <!-- Work Dropdown -->
                <li class="nav-item dropdown">
                     <a class="nav-link dropdown-toggle <?= in_array($currentPage, ['work.php', 'searchwork.php']) ? 'active' : '' ?>" href="#" id="workDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-briefcase"></i> Work
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="workDropdown">
                        <li><a class="dropdown-item" href="work.php"><i class="fas fa-plus-circle"></i> Post Work</a></li>
                        <li><a class="dropdown-item" href="searchwork.php"><i class="fas fa-search"></i> Find Work</a></li>
                    </ul>
                </li>
            </ul>

            <!-- Right Side (Search & Profile) -->
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link" href="search.php" title="Search">
                        <i class="fas fa-search"></i>
                    </a>
                </li>
                
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle btn btn-outline-light rounded-pill px-3 py-1 ms-2" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="border: 1px solid rgba(255,255,255,0.3);">
                        <i class="fas fa-user-circle me-1"></i> Account
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                        <li><a class="dropdown-item" href="profile.php"><i class="fas fa-id-card"></i> My Profile</a></li>
                        <li><hr class="dropdown-divider bg-light opacity-25"></li>
                        <li><a class="dropdown-item text-danger" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Add padding to body to account for fixed navbar -->
<div style="padding-top: 90px;"></div>

<script>
// Navbar Scroll Effect
window.addEventListener('scroll', function() {
    const navbar = document.querySelector('.modern-navbar');
    if (navbar) {
        if (window.scrollY > 50) navbar.classList.add('scrolled');
        else navbar.classList.remove('scrolled');
    }
});

// Close mobile menu on outside click
document.addEventListener('click', function(event) {
    const navbar = document.querySelector('.navbar-collapse');
    const toggler = document.querySelector('.navbar-toggler');
    if (navbar && navbar.classList.contains('show') && !navbar.contains(event.target) && !toggler.contains(event.target)) {
        // Use Bootstrap 5 API if available, otherwise fallback
        if (typeof bootstrap !== 'undefined' && bootstrap.Collapse) {
             const bsCollapse = bootstrap.Collapse.getInstance(navbar);
             if (bsCollapse) bsCollapse.hide();
        }
    }
});
</script>