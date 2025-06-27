<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduHub - Professional Learning Platform</title>
  
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
            color: #333;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        /* Hero Section */
        .hero-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            /* background: linear-gradient(135deg, rgba(102, 126, 234, 0.9) 0%, rgba(118, 75, 162, 0.9) 100%); */
            overflow: hidden;

            background: url('./images/bg2.jpg') no-repeat center center fixed;
  background-size: cover;
  min-height: 100vh;
  margin: 0;
  padding: 0;

        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><polygon fill="rgba(255,255,255,0.05)" points="0,1000 1000,0 1000,1000"/></svg>');
            pointer-events: none;
        }

        .hero-content {
            text-align: center;
            color: white;
            z-index: 2;
            position: relative;
            max-width: 1200px;
            padding: 0 20px;
        }

        .hero-title {
            font-family: 'Poppins', sans-serif;
            font-size: 4rem;
            font-weight: 700;
            margin-bottom: 20px;
            text-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            letter-spacing: -0.02em;
            line-height: 1.1;
        }

        .hero-subtitle {
            font-size: 1.4rem;
            font-weight: 300;
            margin-bottom: 40px;
            opacity: 0.9;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .cta-button {
            background: linear-gradient(45deg, #ff6b6b, #ee5a24);
            color: white;
            padding: 15px 35px;
            border: none;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(255, 107, 107, 0.3);
            margin: 10px;
        }

        .cta-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(255, 107, 107, 0.4);
            color: white;
            text-decoration: none;
        }

        .cta-secondary {
            background: transparent;
            border: 2px solid white;
            box-shadow: none;
        }

        .cta-secondary:hover {
            background: white;
            color: #667eea;
        }

        /* Services Section */
        .services-section {
            padding: 100px 0;
            background: white;
            position: relative;
        }

        .section-title {
            text-align: center;
            font-family: 'Poppins', sans-serif;
            font-size: 2.5rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 20px;
        }

        .section-subtitle {
            text-align: center;
            font-size: 1.2rem;
            color: #7f8c8d;
            margin-bottom: 60px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .service-card {
            background: white;
            border-radius: 20px;
            padding: 40px 30px;
            text-align: center;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.05);
            position: relative;
            overflow: hidden;
        }

        .service-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(45deg, #667eea, #764ba2);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }

        .service-card:hover::before {
            transform: scaleX(1);
        }

        .service-icon {
            font-size: 3rem;
            color: #667eea;
            margin-bottom: 20px;
        }

        .service-title {
            font-family: 'Poppins', sans-serif;
            font-size: 1.4rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 15px;
        }

        .service-description {
            color: #7f8c8d;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .service-link {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .service-link:hover {
            color: #764ba2;
            text-decoration: none;
        }

        /* Stats Section */
        .stats-section {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            padding: 80px 0;
            color: white;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 40px;
            max-width: 1000px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-family: 'Poppins', sans-serif;
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .stat-label {
            font-size: 1.1rem;
            opacity: 0.8;
        }

        /* Professional Footer */
        .footer {
            background: #1a1a1a;
            color: white;
            padding: 60px 0 20px;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
            margin-bottom: 40px;
        }

        .footer-section h3 {
            font-family: 'Poppins', sans-serif;
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 20px;
            color: #667eea;
        }

        .footer-section p,
        .footer-section a {
            color: #ccc;
            text-decoration: none;
            line-height: 1.8;
            transition: color 0.3s ease;
        }

        .footer-section a:hover {
            color: #667eea;
        }

        .footer-social {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .social-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: #333;
            border-radius: 50%;
            color: white;
            transition: all 0.3s ease;
        }

        .social-link:hover {
            background: #667eea;
            transform: translateY(-2px);
        }

        .footer-bottom {
            text-align: center;
            padding-top: 40px;
            border-top: 1px solid #333;
            color: #999;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-subtitle {
                font-size: 1.2rem;
            }
            
            .services-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 480px) {
            .hero-title {
                font-size: 2rem;
            }
            
            .section-title {
                font-size: 2rem;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
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

        .fade-in-up {
            animation: fadeInUp 0.8s ease forwards;
        }

        /* Typing effect */
        .typing-cursor {
            border-right: 3px solid rgba(255, 255, 255, 0.8);
            animation: blink 1s infinite;
        }

        @keyframes blink {
            0%, 50% { border-color: rgba(255, 255, 255, 0.8); }
            51%, 100% { border-color: transparent; }
        }
    </style>
</head>
<body>
    <!-- Include Navbar -->
    <?php include 'navbar.php'; ?>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-content">
            <h1 class="hero-title" id="typingEffect"></h1>
            <p class="hero-subtitle">Transform your learning journey with our comprehensive educational platform. Access courses, connect with tutors, and join a thriving community of learners.</p>
            <div>
                <a href="#services" class="cta-button">Explore Services</a>
                <a href="/EduHub/login.php" class="cta-button cta-secondary">Get Started</a>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="services-section" id="services">
        <div class="container">
            <h2 class="section-title">Our Services</h2>
            <p class="section-subtitle">Discover a world of learning opportunities designed to help you succeed in your educational and professional journey.</p>
            
            <div class="services-grid">
                <div class="service-card fade-in-up">
                    <div class="service-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="service-title">Community</h3>
                    <p class="service-description">Connect with like-minded learners, share knowledge, and build lasting professional relationships in our vibrant community.</p>
                    <a href="#" class="service-link">Join Community →</a>
                </div>

                <div class="service-card fade-in-up">
                    <div class="service-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h3 class="service-title">Online Courses</h3>
                    <p class="service-description">Access high-quality courses from industry experts. Learn at your own pace with interactive content and certifications.</p>
                    <a href="#" class="service-link">Browse Courses →</a>
                </div>

                <div class="service-card fade-in-up">
                    <div class="service-icon">
                        <i class="fas fa-laptop-code"></i>
                    </div>
                    <h3 class="service-title">Freelancing</h3>
                    <p class="service-description">Master digital marketing and freelancing skills. Learn to build your own business and work from anywhere.</p>
                    <a href="#" class="service-link">Start Learning →</a>
                </div>

                <div class="service-card fade-in-up">
                    <div class="service-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <h3 class="service-title">Buy & Sell</h3>
                    <p class="service-description">Marketplace for educational resources, books, and study materials. Buy and sell with fellow students safely.</p>
                    <a href="#" class="service-link">Visit Marketplace →</a>
                </div>

                <div class="service-card fade-in-up">
                    <div class="service-icon">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <h3 class="service-title">Tuition</h3>
                    <p class="service-description">Find qualified tutors for personalized learning. One-on-one sessions tailored to your specific needs and goals.</p>
                    <a href="#" class="service-link">Find Tutors →</a>
                </div>

                <div class="service-card fade-in-up">
                    <div class="service-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h3 class="service-title">Blood Donation</h3>
                    <p class="service-description">Make a difference in your community. Connect with blood donation drives and help save lives through our platform.</p>
                    <a href="#" class="service-link">Learn More →</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number" data-count="10000">0</div>
                    <div class="stat-label">Active Students</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" data-count="500">0</div>
                    <div class="stat-label">Expert Instructors</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" data-count="250">0</div>
                    <div class="stat-label">Courses Available</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" data-count="95">0</div>
                    <div class="stat-label">Success Rate</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-grid">
                <div class="footer-section">
                    <h3>EduHub</h3>
                    <p>Empowering learners worldwide with comprehensive educational resources and community-driven learning experiences.</p>
                    <div class="footer-social">
                        <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                
                <div class="footer-section">
                    <h3>Services</h3>
                    <p><a href="#">Online Courses</a></p>
                    <p><a href="#">Tutoring</a></p>
                    <p><a href="#">Community</a></p>
                    <p><a href="#">Freelancing</a></p>
                    <p><a href="#">Marketplace</a></p>
                </div>
                
                <div class="footer-section">
                    <h3>Support</h3>
                    <p><a href="#">Help Center</a></p>
                    <p><a href="#">Contact Us</a></p>
                    <p><a href="#">Privacy Policy</a></p>
                    <p><a href="#">Terms of Service</a></p>
                    <p><a href="#">FAQ</a></p>
                </div>
                
                <div class="footer-section">
                    <h3>Contact Info</h3>
                    <p><i class="fas fa-envelope"></i> info@eduhub.com</p>
                    <p><i class="fas fa-phone"></i> +880 123 456 789</p>
                    <p><i class="fas fa-map-marker-alt"></i> Dhaka, Bangladesh</p>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2025 EduHub. All rights reserved. Built with passion for education.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        // Typing Effect
        const text = "WELCOME TO EduHub";
        const typingSpeed = 100;
        let index = 0;

        function type() {
            if (index < text.length) {
                document.getElementById("typingEffect").textContent += text.charAt(index);
                index++;
                setTimeout(type, typingSpeed);
            } else {
                document.getElementById("typingEffect").classList.add('typing-cursor');
            }
        }

        // Start typing effect when page loads
        window.addEventListener('load', () => {
            setTimeout(type, 500);
        });

        // Counter Animation
        function animateCounters() {
            const counters = document.querySelectorAll('.stat-number');
            
            counters.forEach(counter => {
                const target = parseInt(counter.getAttribute('data-count'));
                const duration = 2000;
                const increment = target / (duration / 16);
                let current = 0;
                
                const updateCounter = () => {
                    if (current < target) {
                        current += increment;
                        counter.textContent = Math.ceil(current);
                        requestAnimationFrame(updateCounter);
                    } else {
                        counter.textContent = target;
                    }
                };
                
                updateCounter();
            });
        }

        // Intersection Observer for animations
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    if (entry.target.classList.contains('stats-section')) {
                        animateCounters();
                    }
                }
            });
        });

        // Observe elements
        document.addEventListener('DOMContentLoaded', () => {
            const statsSection = document.querySelector('.stats-section');
            if (statsSection) {
                observer.observe(statsSection);
            }
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>