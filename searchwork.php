<?php
session_start();
include 'db_connect.php'; // Ensure this file contains your database connection details

// Check if the user is logged in
if (!isset($_SESSION['student_id'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit();
}

// Initialize variables
$student_id = $_SESSION['student_id'];
$works = [];
$skills = ['PowerPoint', 'Word', 'Excel', 'Web Development', 'Frontend', 'Fullstack'];

// Handle search
if (isset($_POST['search_work'])) {
    $selected_skill = $_POST['skill'];

    // Fetch works based on the selected skill
    $query = "SELECT * FROM works WHERE skill_requirement = ?";
    $stmt = $conn->prepare($query);
    
    if ($stmt) {
        $stmt->bind_param("s", $selected_skill);
        $stmt->execute();
        $works = $stmt->get_result();
        $stmt->close();
    } else {
        echo "Error in SQL statement: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Work - EduHub</title>
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
            color: #333;
        }

        .main-container {
            padding: 2rem 0;
            margin-top: 90px;
        }

        /* Hero Section */
        .hero-section {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 3rem 2rem;
            margin-bottom: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            text-align: center;
            color: white;
        }

        .hero-section h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, #74b9ff, #0984e3);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-section p {
            font-size: 1.2rem;
            opacity: 0.9;
            margin-bottom: 0;
        }

        /* Search Form Card */
        .search-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 2.5rem;
            margin-bottom: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .search-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.15);
        }

        .search-card h2 {
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .search-card h2 i {
            color: #74b9ff;
            font-size: 1.5rem;
        }

        /* Form Styling */
        .form-label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }

        .form-select {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 0.8rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: white;
        }

        .form-select:focus {
            border-color: #74b9ff;
            box-shadow: 0 0 0 0.2rem rgba(116, 185, 255, 0.25);
            outline: none;
        }

        /* Search Button */
        .search-btn {
            background: linear-gradient(135deg, #74b9ff, #0984e3);
            border: none;
            border-radius: 12px;
            padding: 0.8rem 2rem;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(116, 185, 255, 0.3);
        }

        .search-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(116, 185, 255, 0.4);
            background: linear-gradient(135deg, #0984e3, #74b9ff);
        }

        /* Results Section */
        .results-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .results-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 1.5rem;
            color: #2c3e50;
        }

        .results-header h3 {
            font-weight: 600;
            margin: 0;
        }

        .results-header i {
            color: #74b9ff;
            font-size: 1.3rem;
        }

        /* Modern Table Styling */
        .modern-table {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: none;
        }

        .modern-table thead {
            background: linear-gradient(135deg, #2c3e50, #34495e);
            color: white;
        }

        .modern-table thead th {
            border: none;
            padding: 1.2rem 1rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.9rem;
        }

        .modern-table tbody tr {
            transition: all 0.3s ease;
            border: none;
        }

        .modern-table tbody tr:hover {
            background: linear-gradient(135deg, rgba(116, 185, 255, 0.1), rgba(9, 132, 227, 0.05));
            transform: scale(1.01);
        }

        .modern-table tbody td {
            padding: 1.2rem 1rem;
            border: none;
            border-bottom: 1px solid #f8f9fa;
            vertical-align: middle;
        }

        .modern-table tbody tr:last-child td {
            border-bottom: none;
        }

        /* Skill Tags */
        .skill-tag {
            background: linear-gradient(135deg, #74b9ff, #0984e3);
            color: white;
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
            display: inline-block;
        }

        /* Experience Badge */
        .experience-badge {
            background: linear-gradient(135deg, #00b894, #00a085);
            color: white;
            padding: 0.3rem 0.6rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        /* Salary Badge */
        .salary-badge {
            background: linear-gradient(135deg, #fdcb6e, #e17055);
            color: white;
            padding: 0.4rem 0.8rem;
            border-radius: 15px;
            font-weight: 600;
            font-size: 1rem;
        }

        /* No Results Message */
        .no-results {
            text-align: center;
            padding: 3rem;
            color: #6c757d;
        }

        .no-results i {
            font-size: 4rem;
            color: #dee2e6;
            margin-bottom: 1rem;
        }

        .no-results h4 {
            color: #495057;
            margin-bottom: 0.5rem;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .search-card,
        .results-section,
        .hero-section {
            animation: fadeInUp 0.8s ease-out;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .hero-section {
                padding: 2rem 1rem;
            }

            .hero-section h1 {
                font-size: 2rem;
            }

            .search-card,
            .results-section {
                padding: 1.5rem;
                margin: 1rem 0;
            }

            .modern-table {
                font-size: 0.9rem;
            }

            .modern-table thead th,
            .modern-table tbody td {
                padding: 0.8rem 0.5rem;
            }
        }

        /* Loading Animation */
        .loading {
            display: none;
            text-align: center;
            padding: 2rem;
        }

        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #74b9ff;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 1rem;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="container main-container">
        <!-- Hero Section -->
        <div class="hero-section">
            <h1><i class="fas fa-search"></i> Find Your Perfect Job</h1>
            <p>Discover opportunities that match your skills and experience level</p>
        </div>

        <!-- Search Form -->
        <div class="search-card">
            <h2><i class="fas fa-filter"></i> Search for Work Opportunities</h2>
            <form method="POST" id="searchForm">
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="skill" class="form-label">
                                <i class="fas fa-cogs"></i> Select Your Skill
                            </label>
                            <select class="form-select" name="skill" id="skill" required>
                                <option value="">-- Choose your expertise --</option>
                                <?php foreach ($skills as $skill): ?>
                                    <option value="<?php echo htmlspecialchars($skill); ?>" 
                                            <?php echo (isset($_POST['skill']) && $_POST['skill'] == $skill) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($skill); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <div class="mb-3 w-100">
                            <button type="submit" name="search_work" class="btn search-btn w-100">
                                <i class="fas fa-search"></i> Search Jobs
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Loading Animation -->
        <div class="loading" id="loading">
            <div class="spinner"></div>
            <p>Searching for opportunities...</p>
        </div>

        <!-- Results Section -->
        <?php if (isset($_POST['search_work'])): ?>
        <div class="results-section">
            <div class="results-header">
                <i class="fas fa-briefcase"></i>
                <h3>Available Opportunities</h3>
                <?php if ($works && $works->num_rows > 0): ?>
                    <span class="badge bg-success ms-auto"><?php echo $works->num_rows; ?> Jobs Found</span>
                <?php endif; ?>
            </div>

            <?php if ($works && $works->num_rows > 0): ?>
                <div class="table-responsive">
                    <table class="table modern-table">
                        <thead>
                            <tr>
                                <th><i class="fas fa-tools"></i> Required Skill</th>
                                <th><i class="fas fa-calendar-alt"></i> Experience</th>
                                <th><i class="fas fa-info-circle"></i> Job Details</th>
                                <th><i class="fas fa-dollar-sign"></i> Salary</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            // Reset the result pointer to the beginning
                            $works->data_seek(0);
                            while ($work = $works->fetch_assoc()): 
                            ?>
                                <tr>
                                    <td>
                                        <span class="skill-tag">
                                            <?php echo htmlspecialchars($work['skill_requirement']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="experience-badge">
                                            <?php echo htmlspecialchars($work['experience_requirement_year']); ?> Years
                                        </span>
                                    </td>
                                    <td>
                                        <div style="max-width: 300px;">
                                            <?php echo htmlspecialchars($work['details']); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="salary-badge">
                                            $<?php echo number_format(htmlspecialchars($work['salary'])); ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="no-results">
                    <i class="fas fa-search"></i>
                    <h4>No Jobs Found</h4>
                    <p>We couldn't find any opportunities matching "<strong><?php echo htmlspecialchars($_POST['skill']); ?></strong>"</p>
                    <p class="text-muted">Try searching with a different skill or check back later for new postings.</p>
                </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>

    <div id="footer-placeholder"></div>

    <script>
        // Loading animation
        document.getElementById('searchForm').addEventListener('submit', function() {
            document.getElementById('loading').style.display = 'block';
        });

        // Footer loading
        fetch('footer.html')
            .then(res => res.text())
            .then(data => {
                document.getElementById('footer-placeholder').innerHTML = data;
            })
            .catch(err => console.log('Footer loading failed:', err));

        // Add smooth scrolling to results
        if (document.querySelector('.results-section')) {
            setTimeout(() => {
                document.querySelector('.results-section').scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }, 100);
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>