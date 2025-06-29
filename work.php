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

// Handle adding new work
if (isset($_POST['add_work'])) {
    $skill_requirement = $_POST['skill_requirement'];
    $experience_requirement_year = $_POST['experience_requirement_year'];
    $details = $_POST['details'];
    $salary = $_POST['salary'];

    $query = "INSERT INTO works (student_id, skill_requirement, experience_requirement_year, details, salary) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die("SQL Error: " . $conn->error);
    }
    $stmt->bind_param("sssss", $student_id, $skill_requirement, $experience_requirement_year, $details, $salary);
    $stmt->execute();
    $stmt->close();
}

// Fetch existing works
$query = "SELECT * FROM works WHERE student_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $student_id);
$stmt->execute();
$works = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Work Management</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 80%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 119, 198, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(120, 219, 255, 0.15) 0%, transparent 50%);
            pointer-events: none;
            z-index: 0;
        }

        .main-container {
            position: relative;
            z-index: 1;
            padding: 2rem 0;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .page-header {
            text-align: center;
            margin-bottom: 3rem;
            padding: 2rem 0;
        }

        .page-title {
            font-size: 3rem;
            font-weight: 800;
            background: linear-gradient(135deg, #ffffff, #e2e8f0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
            text-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .page-subtitle {
            font-size: 1.2rem;
            color: rgba(255, 255, 255, 0.8);
            font-weight: 400;
        }

        .content-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 
                0 32px 64px rgba(0, 0, 0, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.2);
            position: relative;
            overflow: hidden;
        }

        .content-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(102, 126, 234, 0.3), transparent);
        }

        .section-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid rgba(102, 126, 234, 0.1);
        }

        .section-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
        }

        .section-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }

        .form-container {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.05), rgba(118, 75, 162, 0.05));
            border-radius: 16px;
            padding: 2rem;
            border: 1px solid rgba(102, 126, 234, 0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .form-group {
            margin-bottom: 0;
        }

        .form-group label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
            display: block;
            font-size: 0.9rem;
        }

        .form-control {
            border: 2px solid rgba(0, 0, 0, 0.08);
            border-radius: 12px;
            padding: 0.875rem 1rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: white;
            font-family: inherit;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            outline: none;
            transform: translateY(-1px);
        }

        textarea.form-control {
            min-height: 100px;
            resize: vertical;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            border-radius: 12px;
            padding: 0.875rem 2rem;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
            background: linear-gradient(135deg, #5a6fd8, #6b46a3);
        }

        .works-grid {
            display: grid;
            gap: 1.5rem;
            margin-top: 1rem;
        }

        .work-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .work-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, #667eea, #764ba2);
        }

        .work-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.12);
        }

        .work-header {
            display: flex;
            justify-content: between;
            align-items: flex-start;
            margin-bottom: 1rem;
            gap: 1rem;
        }

        .work-skill {
            font-size: 1.2rem;
            font-weight: 700;
            color: #1e293b;
            flex: 1;
        }

        .work-salary {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
            white-space: nowrap;
        }

        .work-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .detail-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .detail-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: rgba(102, 126, 234, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #667eea;
            font-size: 14px;
            flex-shrink: 0;
        }

        .detail-content {
            flex: 1;
        }

        .detail-label {
            font-size: 0.8rem;
            color: #64748b;
            font-weight: 500;
            margin-bottom: 2px;
        }

        .detail-value {
            font-size: 0.9rem;
            color: #1e293b;
            font-weight: 600;
        }

        .work-description {
            background: rgba(102, 126, 234, 0.05);
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid #667eea;
        }

        .work-description-text {
            color: #374151;
            line-height: 1.6;
            margin: 0;
            font-size: 0.9rem;
        }

        .work-actions {
            display: flex;
            gap: 0.75rem;
            justify-content: flex-end;
            padding-top: 1rem;
            border-top: 1px solid rgba(0, 0, 0, 0.06);
        }

        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-warning {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            border: none;
            color: white;
        }

        .btn-warning:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
            background: linear-gradient(135deg, #d97706, #b45309);
        }

        .btn-danger {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            border: none;
            color: white;
        }

        .btn-danger:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
            background: linear-gradient(135deg, #dc2626, #b91c1c);
        }

        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            color: #64748b;
        }

        .empty-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 32px;
            color: #667eea;
        }

        .empty-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        .empty-text {
            font-size: 0.95rem;
            color: #64748b;
        }

        @media (max-width: 768px) {
            .page-title {
                font-size: 2rem;
            }
            
            .content-section {
                padding: 1.5rem;
                margin-bottom: 1.5rem;
            }
            
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .work-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .work-actions {
                justify-content: center;
            }
        }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="main-container">
    <div class="container">
        <div class="page-header">
            <h1 class="page-title">Work Management</h1>
            <p class="page-subtitle">Create and manage your work opportunities</p>
        </div>

        <!-- Add Work Form -->
        <div class="content-section">
            <div class="section-header">
                <div class="section-icon">
                    <i class="fas fa-plus"></i>
                </div>
                <h2 class="section-title">Add New Work</h2>
            </div>
            
            <div class="form-container">
                <form method="POST">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="skill_requirement">
                                <i class="fas fa-tools"></i> Skill Requirement
                            </label>
                            <input type="text" class="form-control" name="skill_requirement" placeholder="e.g., PHP, JavaScript, Design" required>
                        </div>
                        <div class="form-group">
                            <label for="experience_requirement_year">
                                <i class="fas fa-calendar-alt"></i> Experience Required (Years)
                            </label>
                            <input type="number" class="form-control" name="experience_requirement_year" min="0" placeholder="0" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="salary">
                                <i class="fas fa-dollar-sign"></i> Salary
                            </label>
                            <input type="text" class="form-control" name="salary" placeholder="e.g., $50,000 - $70,000" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="details">
                            <i class="fas fa-align-left"></i> Job Details
                        </label>
                        <textarea class="form-control" name="details" placeholder="Describe the job responsibilities, requirements, and other important details..." required></textarea>
                    </div>
                    
                    <div class="text-center" style="margin-top: 2rem;">
                        <button type="submit" name="add_work" class="btn btn-primary">
                            <i class="fas fa-plus-circle"></i> Add Work Opportunity
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Existing Works -->
        <div class="content-section">
            <div class="section-header">
                <div class="section-icon">
                    <i class="fas fa-briefcase"></i>
                </div>
                <h2 class="section-title">Your Work Opportunities</h2>
            </div>
            
            <div class="works-grid">
                <?php if ($works->num_rows > 0): ?>
                    <?php while ($work = $works->fetch_assoc()): ?>
                        <div class="work-card">
                            <form method="POST">
                                <input type="hidden" name="work_id" value="<?php echo $work['work_id']; ?>">
                                
                                <div class="work-header">
                                    <div class="work-skill">
                                        <input type="text" name="skill_requirement" value="<?php echo htmlspecialchars($work['skill_requirement']); ?>" 
                                               style="border: none; background: transparent; font-size: 1.2rem; font-weight: 700; color: #1e293b; width: 100%;" required>
                                    </div>
                                    <div class="work-salary">
                                        <input type="text" name="salary" value="<?php echo htmlspecialchars($work['salary']); ?>" 
                                               style="border: none; background: transparent; color: white; font-weight: 600; text-align: center; width: 100%;" required>
                                    </div>
                                </div>
                                
                                <div class="work-details">
                                    <div class="detail-item">
                                        <div class="detail-icon">
                                            <i class="fas fa-clock"></i>
                                        </div>
                                        <div class="detail-content">
                                            <div class="detail-label">Experience Required</div>
                                            <div class="detail-value">
                                                <input type="number" name="experience_requirement_year" value="<?php echo htmlspecialchars($work['experience_requirement_year']); ?>" 
                                                       min="0" style="border: none; background: transparent; font-weight: 600; width: 60px;" required> years
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="work-description">
                                    <textarea name="details" class="work-description-text" 
                                              style="border: none; background: transparent; width: 100%; resize: vertical; min-height: 80px;" required><?php echo htmlspecialchars($work['details']); ?></textarea>
                                </div>
                                
                                <div class="work-actions">
                                    <button type="submit" name="update_work" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Update
                                    </button>
                                    <a href="?delete=<?php echo $work['work_id']; ?>" class="btn btn-danger btn-sm" 
                                       onclick="return confirm('Are you sure you want to delete this work opportunity?')">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                </div>
                            </form>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-briefcase"></i>
                        </div>
                        <h3 class="empty-title">No Work Opportunities Yet</h3>
                        <p class="empty-text">Start by adding your first work opportunity using the form above.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>