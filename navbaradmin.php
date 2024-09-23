<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <a class="navbar-brand" href="index.php">EduHub</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mx-auto">
            <li class="nav-item">
                <a class="nav-link" href="adminindex.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="profile.php">Profile</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="posts.php">Posts</a>
            </li>

            <!-- Books Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="booksDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Books
                </a>
                <ul class="dropdown-menu" aria-labelledby="booksDropdown">
                    <li><a class="dropdown-item" href="upload_books.php">Upload Books</a></li>
                    <li><a class="dropdown-item" href="buy_books.php">Buy Books</a></li>
                </ul>
            </li>

            <!-- Earnings and Costs Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="earnCostDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Earnings & Costs
                </a>
                <ul class="dropdown-menu" aria-labelledby="earnCostDropdown">
                    <li><a class="dropdown-item" href="transactions.php">Earn</a></li>
                    <li><a class="dropdown-item" href="transaction2.php">Costs</a></li>
                </ul>
            </li>

            <!-- Items Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="itemsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Items
                </a>
                <ul class="dropdown-menu" aria-labelledby="itemsDropdown">
                    <li><a class="dropdown-item" href="buy_items.php">Buy Items</a></li>
                    <li><a class="dropdown-item" href="upload_items.php">Upload Items</a></li>
                </ul>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="skills_development.php">Skills & Freelancing</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="courses.php">Courses</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="ranking.php">Ranking</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="be_an_instructor.php">Apply for Instructor</a>
            </li>

            <!-- Blood-related Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="bloodDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Blood
                </a>
                <ul class="dropdown-menu" aria-labelledby="bloodDropdown">
                    <li><a class="dropdown-item" href="blood.php">Blood Donation</a></li>
                    <li><a class="dropdown-item" href="blood_find.php">Find Blood</a></li>
                </ul>
            </li>

            <!-- Tuition-related Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="tuitionDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Tuition
                </a>
                <ul class="dropdown-menu" aria-labelledby="tuitionDropdown">
                    <li><a class="dropdown-item" href="add_tuition.php">Ask For Tutor</a></li>
                    <li><a class="dropdown-item" href="view_tuitions.php">Find Tuition</a></li>
                    <li><a class="dropdown-item" href="register_tutor.php">Wanna be a Tutor</a></li>
                    <li><a class="dropdown-item" href="search_tutors.php">Search Tutor</a></li>
                </ul>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="chatting.php">Chat</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Logout</a>
            </li>
        </ul>
    </div>
</nav>

<!-- Include Bootstrap JS and Popper.js for dropdown functionality -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
