<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Dashboard Navbar</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    :root {
      --primary-color: #4f46e5;
      --dark-color: #1e293b;
      --light-color: #f1f5f9;
      --hover-color: #4338ca;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', sans-serif;
      background-color: var(--light-color);
    }

    .navbar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      background-color: var(--dark-color);
      color: white;
      padding: 1rem 2rem;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .navbar .logo {
      font-size: 1.5rem;
      font-weight: 600;
    }

    .navbar ul {
      list-style: none;
      display: flex;
      align-items: center;
      gap: 1.5rem;
    }

    .navbar ul li {
      position: relative;
    }

    .navbar ul li a {
      color: white;
      text-decoration: none;
      font-weight: 500;
      transition: color 0.2s ease-in-out;
      cursor: pointer;
    }

    .navbar ul li a:hover {
      color: var(--hover-color);
    }

    .dropdown-menu {
      display: none;
      position: absolute;
      top: 120%;
      left: 0;
      background-color: rgb(0, 0, 0);
      border-radius: 8px;
      min-width: 180px;
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
      z-index: 999;
    }

    .dropdown-menu a {
      color: #000000;
      background-color: #4f46e5;
      display: block;
      padding: 10px 15px;
      text-decoration: none;
      border-bottom: 1px solid #e0e0e0;
      transition: background-color 0.2s ease, color 0.2s ease;
    }

    .dropdown-menu a:hover {
      background-color: rgb(19, 54, 89);
      color: var(--primary-color);
    }

    .dropdown.open .dropdown-menu {
      display: block;
    }

    .profile-icon {
      background: var(--hover-color);
      padding: 0.5rem 1rem;
      border-radius: 20px;
      font-size: 0.9rem;
    }

    @media (max-width: 768px) {
      .navbar ul {
        flex-direction: column;
        background: var(--dark-color);
        position: absolute;
        top: 100%;
        left: 0;
        width: 100%;
        display: none;
      }

      .navbar ul.show {
        display: flex;
      }

      .menu-toggle {
        display: block;
        font-size: 1.5rem;
        cursor: pointer;
      }
    }

    .menu-toggle {
      display: none;
    }
  </style>
</head>
<body>
  <nav class="navbar">
    <div class="logo">AdminPanel</div>
    <div class="menu-toggle" id="menu-toggle">
      <i class="fas fa-bars"></i>
    </div>
    <ul id="nav-menu">
      <li><a href="adminindex.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
      
      <li class="dropdown" id="manageDropdown">
        <a onclick="toggleDropdown('manageDropdown')"><i class="fas fa-user-cog"></i> Manage</a>
        <div class="dropdown-menu">
          <a href="adminposts.php">Notice</a>
          <a href="#">Roles</a>
          <a href="#">Permissions</a>
        </div>
      </li>

      <li class="dropdown" id="dataDropdown">
        <a onclick="toggleDropdown('dataDropdown')"><i class="fas fa-database"></i> Data</a>
        <div class="dropdown-menu">
          <a href="#">Logs</a>
          <a href="#">Backups</a>
          <a href="#">Reports</a>
        </div>
      </li>

      <li><a href="#"><i class="fas fa-cogs"></i> Settings</a></li>
      <li><a href="logout.php" class="profile-icon"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
  </nav>

  <script>
    const toggle = document.getElementById('menu-toggle');
    const menu = document.getElementById('nav-menu');

    toggle.addEventListener('click', () => {
      menu.classList.toggle('show');
    });

    function toggleDropdown(id) {
      const dropdown = document.getElementById(id);
      dropdown.classList.toggle('open');

      // Close other dropdowns
      document.querySelectorAll('.dropdown').forEach(el => {
        if (el.id !== id) {
          el.classList.remove('open');
        }
      });
    }

    // Close dropdowns when clicking outside
    document.addEventListener('click', function (e) {
      if (!e.target.closest('.dropdown')) {
        document.querySelectorAll('.dropdown').forEach(el => el.classList.remove('open'));
      }
    });
  </script>
</body>
</html>
