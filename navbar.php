<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <a class="navbar-brand" href="index.php">EduHub</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mx-auto">
            <li class="nav-item">
                <a class="nav-link" href="index.php"><i class="fas fa-home"></i> Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="profile.php"><i class="fas fa-user"></i> Profile</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="posts.php"><i class="fas fa-comments"></i> Posts</a>
            </li>

            <!-- Books Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="booksDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-book"></i> Books
                </a>
                <ul class="dropdown-menu" aria-labelledby="booksDropdown">
                    <li><a class="dropdown-item" href="upload_books.php">Upload Books</a></li>
                    <li><a class="dropdown-item" href="buy_books.php">Buy Books</a></li>
                </ul>
            </li>

            <!-- Earnings and Costs Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="earnCostDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-money-bill-wave"></i> Earnings & Costs
                </a>
                <ul class="dropdown-menu" aria-labelledby="earnCostDropdown">
                    <li><a class="dropdown-item" href="transactions.php">Earn</a></li>
                    <li><a class="dropdown-item" href="transaction2.php">Costs</a></li>
                </ul>
            </li>

            <!-- Items Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="itemsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-box"></i> Items
                </a>
                <ul class="dropdown-menu" aria-labelledby="itemsDropdown">
                    <li><a class="dropdown-item" href="buy_items.php">Buy Items</a></li>
                    <li><a class="dropdown-item" href="upload_items.php">Upload Items</a></li>
                </ul>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="skills_development.php"><i class="fas fa-tools"></i> My Skills</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="coursesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-graduation-cap"></i> Courses
                </a>
                <ul class="dropdown-menu" aria-labelledby="coursesDropdown">
                    <li><a class="dropdown-item" href="courses.php">Buy Course</a></li>
                    <li><a class="dropdown-item" href="my_courses.php">My Courses</a></li>
                
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="ranking.php"><i class="fas fa-star"></i> Ranking</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="be_an_instructor.php"><i class="fas fa-chalkboard-teacher"></i> Apply for Instructor</a>
            </li>

            <!-- Blood-related Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="bloodDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-tint"></i> Blood
                </a>
                <ul class="dropdown-menu" aria-labelledby="bloodDropdown">
                    <li><a class="dropdown-item" href="blood.php">Blood Donation</a></li>
                    <li><a class="dropdown-item" href="blood_find.php">Find Blood</a></li>
                </ul>
            </li>

            <!-- Tuition-related Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="tuitionDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-money-check-alt"></i> Tuition
                </a>
                <ul class="dropdown-menu" aria-labelledby="tuitionDropdown">
                    <li><a class="dropdown-item" href="add_tuition.php">Ask For Tutor</a></li>
                    <li><a class="dropdown-item" href="view_tuitions.php">Find Tuition</a></li>
                    <li><a class="dropdown-item" href="register_tutor.php">Wanna be a Tutor</a></li>
                    <li><a class="dropdown-item" href="search_tutors.php">Search Tutor</a></li>
                </ul>
            </li>

            <!-- Work Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="workDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-briefcase"></i> Work
                </a>
                <ul class="dropdown-menu" aria-labelledby="workDropdown">
                    <li><a class="dropdown-item" href="work.php">Add Work</a></li>
                    <li><a class="dropdown-item" href="searchwork.php">Search Work</a></li>
                </ul>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="chat.php"><i class="fas fa-comments"></i> Chat</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="my_courses.php"><i class="fas fa-comments"></i>My courses</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </li>
        </ul>
    </div>
</nav>

<!-- Include Bootstrap JS and Popper.js for dropdown functionality -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
