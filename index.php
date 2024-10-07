<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduHub</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css"> <!-- Link to your external CSS file if any -->
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet">
    <style>
        /* Custom styles */
        html, body {
            overflow: hidden; /* Prevent scrolling */
            height: 100%; /* Ensure the body takes full height */
            margin: 0; /* Remove default margin */
        }
        body {
            background-color: #f4f4f9; /* Fallback color */
            background-image: url("uploads/bg2.gif"); /* Set your background image */
            background-size: cover; /* Cover the entire viewport */
            background-repeat: no-repeat; /* Prevent repeating the image */
            background-position: center; /* Center the image */
        }
        main {
            padding: 60px 20px;
            text-align: center;
        }
        h1 {
            font-family: 'Lobster', cursive; /* Unique font style */
            font-size: 4rem; /* Increase font size */
            margin-bottom: 20px;
            color: #333;
            white-space: nowrap; /* Prevent line breaks */
            overflow: hidden; /* Hide overflow */
            border-right: 0.15em solid orange; /* Cursor effect */
            animation: blink 0.75s step-end infinite; /* Blinking cursor */
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2); /* Add shadow effect */
            letter-spacing: 2px; /* Increase letter spacing for a more stylish look */
            line-height: 1.5; /* Add line spacing */
            border-radius: 3px; /* Optional: Add some padding around the text */
        }
        @keyframes blink {
            50% { border-color: transparent; }
        }
        p {
            font-size: 1.2rem;
            color: #555;
        }
        .image-frame {
            width: 230%;
            overflow: hidden; /* Hides overflow */
            position: relative; /* For positioning child elements */
            border-radius: 10px; /* Optional rounded corners */
            margin-left: -23px;
        }
        .image-row {
            display: flex;
            animation: slide 60s linear infinite; /* Continuous animation */
        }
        .image-item {
            flex: 0 0 12.5%; /* Each item takes 12.5% of the row */
            padding: 10px;
            box-sizing: border-box;
        }
        .image-item img {
            width: 100%; /* Makes images responsive to the item size */
            height: 60%; /* Maintain aspect ratio */
            border-radius: 5px; /* Optional rounded corners for images */
            transition: transform 0.3s ease; /* Smooth transition for hover effect */
            border: 2px solid;
            object-fit: cover; 
        }
        .image-item img:hover {
            transform: scale(1.05); /* Scale up slightly on hover */
        }
        .image-title {
            text-align: center;
            margin-top: 10px;
            font-weight: bold;
            color: black;
        }
        @keyframes slide {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); } /* Move to the left by half the row's width */
        }
    </style>
</head>
<body>
    <!-- Include Navbar -->
    <?php include 'navbar.php'; ?>

    <!-- Main Content -->
    <main>
        <h1 id="typingEffect"></h1>
        <p style="color: black;">Your comprehensive educational platform!</p>
 
        
        <!-- Image Carousel -->
        <div class="image-frame">
            <div class="image-row">
                <div class="image-item">
                    <img src="uploads/community.jpeg" alt="Image 1">
                    <div class="image-title">Community</div>
                </div>
                <div class="image-item">
                    <img src="uploads/course.jpeg" alt="Image 2">
                    <div class="image-title">Online Courses</div>
                </div>
                <div class="image-item">
                    <img src="uploads/digital marketing.jpeg" alt="Image 3">
                    <div class="image-title">Freelancing</div>
                </div>
                <div class="image-item">
                    <img src="uploads/buysell.jpeg" alt="Image 4">
                    <div class="image-title">Buy & Sell</div>
                </div>
                <div class="image-item">
                    <img src="uploads/tution.jpeg" alt="Image 5">
                    <div class="image-title">Tuition</div>
                </div>
                <div class="image-item">
                    <img src="uploads/blooddonation.jpeg" alt="Image 6">
                    <div class="image-title">Blood Donation</div>
                </div>
            
                <div class="image-item">
                    <img src="uploads/community.jpeg" alt="Image 1">
                    <div class="image-title">Community</div>
                </div>
                <div class="image-item">
                    <img src="uploads/course.jpeg" alt="Image 2">
                    <div class="image-title">Online Courses</div>
                </div>
                <div class="image-item">
                    <img src="uploads/digital marketing.jpeg" alt="Image 3">
                    <div class="image-title">Freelancing</div>
                </div>
                <div class="image-item">
                    <img src="uploads/buysell.jpeg" alt="Image 4">
                    <div class="image-title">Buy & Sell</div>
                </div>
                <div class="image-item">
                    <img src="uploads/tution.jpeg" alt="Image 5">
                    <div class="image-title">Tuition</div>
                </div>
                <div class="image-item">
                    <img src="uploads/blooddonation.jpeg" alt="Image 6">
                    <div class="image-title">Blood Donation</div>
                </div>
                <div class="image-item">
                    <img src="https://via.placeholder.com/300" alt="Image 7">
                    <div class="image-title">Title 7</div>
                </div>
               
            </div>
        </div>
    </main>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        // Typing effect for the h1 element
        const text = "WELCOME TO EduHub";
        const typingSpeed = 100; // milliseconds
        let index = 0;

        function type() {
            if (index < text.length) {
                document.getElementById("typingEffect").textContent += text.charAt(index);
                index++;
                setTimeout(type, typingSpeed);
            }
        }

        // Start typing effect
        type();
    </script>
</body>
</html>
