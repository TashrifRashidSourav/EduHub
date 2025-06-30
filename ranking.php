<?php  
$servername = "localhost"; 
$username = "root";   
$password = "";       
$dbname = "EduHub";    
$conn = new mysqli($servername, $username, $password, $dbname);  
if ($conn->connect_error) {     
    die("Connection failed: " . $conn->connect_error); 
}  

$student_data = []; 
$sql = "SELECT s.student_id, s.name,                 
        SUM(CASE WHEN p.category = 'educational' AND p.status = 'approved' THEN 1 ELSE 0 END) AS total_educational_posts,                
        SUM(CASE WHEN p.category = 'entertainment' AND p.status = 'approved' THEN 1 ELSE 0 END) AS total_entertainment_posts,                
        SUM(CASE WHEN p.category = 'professional' AND p.status = 'approved' THEN 1 ELSE 0 END) AS total_professional_posts,                
        COUNT(CASE WHEN p.status = 'approved' THEN p.post_id ELSE NULL END) AS total_posts         
        FROM students s         
        LEFT JOIN posts p ON s.student_id = p.student_id         
        GROUP BY s.student_id, s.name         
        ORDER BY total_posts DESC";  

$result = $conn->query($sql); 
if ($result->num_rows > 0) {     
    while ($row = $result->fetch_assoc()) {         
        $student_data[] = $row;     
    } 
}  
?>  

<!DOCTYPE html> 
<html lang="en"> 
<head>     
    <meta charset="UTF-8">     
    <meta name="viewport" content="width=device-width, initial-scale=1.0">     
    <title>Top Contributer</title>     
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .leaderboard-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin: 2rem auto;
            max-width: 1200px;
        }
        
        .leaderboard-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .leaderboard-title {
            font-size: 2.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
        }
        
        .leaderboard-subtitle {
            color: #6c757d;
            font-size: 1.1rem;
            font-weight: 400;
        }
        
        .modern-table {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            border: none;
        }
        
        .modern-table thead th {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            padding: 1.2rem 1rem;
            border: none;
            position: relative;
        }
        
        .modern-table tbody td {
            padding: 1.2rem 1rem;
            border: none;
            border-bottom: 1px solid #f1f3f4;
            vertical-align: middle;
            font-weight: 500;
        }
        
        .modern-table tbody tr {
            transition: all 0.3s ease;
        }
        
        .modern-table tbody tr:hover {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.05), rgba(118, 75, 162, 0.05));
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .rank-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            font-weight: 700;
            font-size: 1rem;
        }
        
        .rank-1 {
            background: linear-gradient(135deg, #ffd700, #ffed4e);
            color: #b8860b;
            box-shadow: 0 4px 15px rgba(255, 215, 0, 0.3);
        }
        
        .rank-2 {
            background: linear-gradient(135deg, #c0c0c0, #e8e8e8);
            color: #696969;
            box-shadow: 0 4px 15px rgba(192, 192, 192, 0.3);
        }
        
        .rank-3 {
            background: linear-gradient(135deg, #cd7f32, #daa520);
            color: #8b4513;
            box-shadow: 0 4px 15px rgba(205, 127, 50, 0.3);
        }
        
        .rank-other {
            background: linear-gradient(135deg, #6c757d, #adb5bd);
            color: white;
        }
        
        .user-name {
            font-weight: 600;
            color: #2d3748;
            font-size: 1.1rem;
        }
        
        .post-count {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.4rem 0.8rem;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        .category-count {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.3rem 0.6rem;
            border-radius: 15px;
            font-weight: 500;
            font-size: 0.85rem;
            margin: 0.1rem;
            min-width: 40px;
            justify-content: center;
        }
        
        .educational-count {
            background: rgba(40, 167, 69, 0.1);
            color: #28a745;
            border: 1px solid rgba(40, 167, 69, 0.2);
        }
        
        .entertainment-count {
            background: rgba(255, 193, 7, 0.1);
            color: #ffc107;
            border: 1px solid rgba(255, 193, 7, 0.2);
        }
        
        .professional-count {
            background: rgba(0, 123, 255, 0.1);
            color: #007bff;
            border: 1px solid rgba(0, 123, 255, 0.2);
        }
        
        .crown-icon {
            margin-left: 0.5rem;
            color: #ffd700;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        
        .stats-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            justify-content: center;
        }
        
        @media (max-width: 768px) {
            .leaderboard-container {
                margin: 1rem;
                padding: 1rem;
            }
            
            .leaderboard-title {
                font-size: 2rem;
            }
            
            .modern-table {
                font-size: 0.9rem;
            }
            
            .modern-table thead th,
            .modern-table tbody td {
                padding: 0.8rem 0.5rem;
            }
        }
    </style>
</head> 
<body>  

<iframe src="curved-background.html"           
        style="position: fixed; z-index: -1; border: none; width: 100vw; height: 100vh;">   
</iframe> 

<?php include 'navbar.php'; ?> 

<div class="container-fluid">
    <div class="leaderboard-container">
        <div class="leaderboard-header">
            <h1 class="leaderboard-title">
                <i class="fas fa-trophy"></i> Leaderboard
            </h1>
            <p class="leaderboard-subtitle">Top Contributors in EduHub Community</p>
        </div>
        
        <div class="table-responsive">
            <table class="table modern-table">
                <thead>
                    <tr>
                        <th><i class="fas fa-medal"></i> Rank</th>
                        <th><i class="fas fa-user"></i> User Name</th>
                        <th><i class="fas fa-chart-bar"></i> Total Approved Posts</th>
                        <th><i class="fas fa-graduation-cap"></i> Educational</th>
                        <th><i class="fas fa-gamepad"></i> Entertainment</th>
                        <th><i class="fas fa-briefcase"></i> Professional</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $rank = 1;
                    foreach ($student_data as $student):                 
                        $total_posts = $student['total_posts'] ?? 0;
                        $educational_posts = $student['total_educational_posts'] ?? 0;
                        $entertainment_posts = $student['total_entertainment_posts'] ?? 0;
                        $professional_posts = $student['total_professional_posts'] ?? 0;
                        
                        $rank_class = '';
                        if ($rank == 1) $rank_class = 'rank-1';
                        elseif ($rank == 2) $rank_class = 'rank-2';
                        elseif ($rank == 3) $rank_class = 'rank-3';
                        else $rank_class = 'rank-other';
                    ?>
                    <tr>
                        <td>
                            <div class="rank-badge <?php echo $rank_class; ?>">
                                <?php echo $rank; ?>
                            </div>
                        </td>
                        <td>
                            <div class="user-name">
                                <?php echo htmlspecialchars($student['name']); ?>
                                <?php if ($rank <= 3): ?>
                                    <i class="fas fa-crown crown-icon"></i>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td>
                            <div class="post-count">
                                <i class="fas fa-fire"></i>
                                <?php echo $total_posts; ?>
                            </div>
                        </td>
                        <td>
                            <div class="category-count educational-count">
                                <?php echo $educational_posts; ?>
                            </div>
                        </td>
                        <td>
                            <div class="category-count entertainment-count">
                                <?php echo $entertainment_posts; ?>
                            </div>
                        </td>
                        <td>
                            <div class="category-count professional-count">
                                <?php echo $professional_posts; ?>
                            </div>
                        </td>
                    </tr>
                    <?php 
                    $rank++;
                    endforeach; 
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="footer-placeholder"></div>  

<script>   
fetch('footer.html')     
    .then(res => res.text())     
    .then(data => {       
        document.getElementById('footer-placeholder').innerHTML = data;     
    }); 
</script>  

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script> 
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script> 
</body> 
</html>  

<?php 
$conn->close();  
?>