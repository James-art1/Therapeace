<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

include 'db.php';

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Therapeace - Notifications</title>
  <link rel="stylesheet" href="dashboard.css" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />

  <!-- Internal Dark Mode CSS -->
  <style>
    body.dark-mode {
      background-color: #121212;
      color: #e0e0e0;
    }

    .sidebar.dark-mode {
      background-color: #1e1e1e;
    }

    .main-content.dark-mode {
      background-color: #1a1a1a;
    }

    .notification-card.dark-mode {
      background-color: #2a2a2a;
      border: 1px solid #333;
      color: #e0e0e0;
    }

    .top-bar.dark-mode {
      background-color: #1f1f1f;
      color: #e0e0e0;
    }

    .notification-time.dark-mode {
      color: #aaa;
    }

    /* Toggle Switch Style */
    .dark-mode-toggle {
      position: absolute;
      top: 20px;
      right: 30px;
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 14px;
      font-weight: 500;
    }

    .toggle-switch {
      position: relative;
      width: 50px;
      height: 24px;
    }

    .toggle-switch input {
      opacity: 0;
      width: 0;
      height: 0;
    }

    .slider {
      position: absolute;
      cursor: pointer;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: #ccc;
      border-radius: 24px;
      transition: 0.4s;
    }

    .slider:before {
      position: absolute;
      content: "";
      height: 18px;
      width: 18px;
      left: 3px;
      bottom: 3px;
      background-color: white;
      border-radius: 50%;
      transition: 0.4s;
    }

    input:checked + .slider {
      background-color: #4caf50;
    }

    input:checked + .slider:before {
      transform: translateX(26px);
    }
  </style>
</head>
<body>
  <div class="dashboard">
    <!-- Sidebar -->
    <aside class="sidebar">
      <div class="sidebar-logo">
        <img src="l1.png" alt="Therapeace Logo" />
        <h1>Therapeace</h1>
      </div>
      <ul class="sidebar-menu">
        <li><a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
        <li><a href="appointments.php"><i class="fas fa-calendar"></i> Appointments</a></li>
        <li><a href="feedback.php"><i class="fas fa-comments"></i> Feedback</a></li>
        <li><a href="resources.php"><i class="fas fa-book"></i> Resources</a></li>
        <li><a href="activities.php"><i class="fas fa-spa"></i> Mindfulness</a></li>
        <li><a href="assessment.php"><i class="fas fa-clipboard-check"></i> Self-Assessment</a></li>
        <li><a href="profile.php"><i class="fas fa-user"></i> Profile</a></li>
        <li><a href="notifications.php"><i class="fas fa-bell"></i> Notifications</a></li>
        <li><a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
      </ul>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
      <header class="top-bar">
        <h2>Notifications</h2>

        <!-- Dark Mode Toggle Switch -->
        <div class="top-bar-right">
    <label class="dark-mode-switch">
        <input type="checkbox" id="darkToggle">
        <span class="slider"></span>
    </label>
</div>
      </header>

      <section class="notifications-section">
        <div class="notifications-container">
          <?php if ($result->num_rows > 0) { ?>
            <?php while ($row = $result->fetch_assoc()) { ?>
              <div class="notification-card">
                <div class="notification-header">
                  <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                </div>
                <p><?php echo htmlspecialchars($row['message']); ?></p>
                <span class="notification-time"><?php echo htmlspecialchars($row['created_at']); ?></span>
              </div>
            <?php } ?>
          <?php } else { ?>
            <p class="no-notifications">No notifications found.</p>
          <?php } ?>
        </div>
      </section>
    </main>
  </div>

  <!-- JavaScript for Dark Mode Toggle -->
  <script>
    const toggle = document.getElementById('darkToggle');
    const body = document.body;

    // Load saved theme
    if (localStorage.getItem('darkMode') === 'enabled') {
        body.classList.add('dark-mode');
        toggle.checked = true;
    }

    toggle.addEventListener('change', () => {
        if (toggle.checked) {
            body.classList.add('dark-mode');
            localStorage.setItem('darkMode', 'enabled');
        } else {
            body.classList.remove('dark-mode');
            localStorage.setItem('darkMode', 'disabled');
        }
    });
  </script>
</body>
</html>
