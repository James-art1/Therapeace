<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

$userFullName = $_SESSION['full_name'];
$user_id = $_SESSION['user_id'];

$resource_query = "SELECT r.title, r.description, r.uploaded_at, r.resource_link 
                   FROM resources r
                   JOIN resource_assignments ra ON r.id = ra.resource_id
                   WHERE ra.user_id = ? ORDER BY r.uploaded_at DESC";
$stmt = $conn->prepare($resource_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$resource_result = $stmt->get_result();

$notification_query = "SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC";
$notification_stmt = $conn->prepare($notification_query);
$notification_stmt->bind_param("i", $user_id);
$notification_stmt->execute();
$notification_result = $notification_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Therapeace Dashboard</title>
  <link rel="stylesheet" href="dashboard.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f5f7fa;
    }

    .section {
      margin-top: 30px;
    }

    .section h3 {
      margin-bottom: 15px;
      font-size: 22px;
      font-weight: 600;
    }

    .table-container {
      overflow-x: auto;
      background: rgba(255, 255, 255, 0.07);
      padding: 1.5rem;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 15px;
      text-align: left;
      color: #333;
    }

    thead {
      background-color: #444;
      color: #fff;
    }

    thead th {
      padding: 14px 18px;
      font-weight: 500;
      text-align: left;
    }

    tbody td {
      padding: 14px 18px;
      border-bottom: 1px solid #ccc;
      vertical-align: top;
    }

    tbody tr:hover {
      background-color: #f0f0f0;
    }

    a {
      color: #007BFF;
      text-decoration: none;
    }

    a:hover {
      text-decoration: underline;
    }

    .dark-mode {
      background-color: #222;
      color: #eee;
    }

    .dark-mode .table-container {
      background-color: rgba(255, 255, 255, 0.05);
    }

    .dark-mode table {
      color: #ddd;
    }

    .dark-mode thead {
      background-color: #222;
    }

    .dark-mode tbody tr:hover {
      background-color: #333;
    }
  </style>
</head>
<body>
  <div class="dashboard">
    <!-- Sidebar -->
    <aside class="sidebar">
      <div class="sidebar-logo">
        <img src="l1.png" alt="Therapeace Logo">
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
        <div class="top-bar-left">
          <h2>Welcome, <?php echo htmlspecialchars($userFullName); ?>!</h2>
        </div>
        <div class="top-bar-right">
          <label class="dark-mode-switch">
            <input type="checkbox" id="darkToggle">
            <span class="slider"></span>
            <i class="fas fa-sun sun-icon" id="sunIcon"></i>
            <i class="fas fa-moon moon-icon" id="moonIcon"></i>
          </label>
          <a href="logout.php" class="logout-btn">Logout</a>
        </div>
      </header>

      <!-- Resources Table -->
      <section class="section">
        <h3>📚 Available Resources</h3>
        <div class="table-container">
          <table>
            <thead>
              <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Uploaded At</th>
                <th>Link</th>
              </tr>
            </thead>
            <tbody>
              <?php if ($resource_result && $resource_result->num_rows > 0): ?>
                <?php while($res = $resource_result->fetch_assoc()): ?>
                  <tr>
                    <td><?php echo htmlspecialchars($res['title']); ?></td>
                    <td><?php echo htmlspecialchars($res['description']); ?></td>
                    <td><?php echo htmlspecialchars($res['uploaded_at']); ?></td>
                    <td><a href="<?php echo htmlspecialchars($res['resource_link']); ?>" target="_blank">View</a></td>
                  </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr><td colspan="4">No resources assigned to you.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </section>

      <!-- Notifications Table -->
      <section class="section">
        <h3>🔔 Recent Notifications</h3>
        <div class="table-container">
          <table>
            <thead>
              <tr>
                <th>Title</th>
                <th>Message</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody>
              <?php if ($notification_result && $notification_result->num_rows > 0): ?>
                <?php while($note = $notification_result->fetch_assoc()): ?>
                  <tr>
                    <td><?php echo htmlspecialchars($note['title']); ?></td>
                    <td><?php echo htmlspecialchars($note['message']); ?></td>
                    <td><?php echo htmlspecialchars($note['created_at']); ?></td>
                  </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr><td colspan="3">No notifications found.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </section>
    </main>
  </div>

  <script>
    const toggle = document.getElementById('darkToggle');
    const body = document.body;
    const sunIcon = document.getElementById('sunIcon');
    const moonIcon = document.getElementById('moonIcon');

    if (localStorage.getItem('darkMode') === 'enabled') {
      body.classList.add('dark-mode');
      toggle.checked = true;
      sunIcon.style.display = 'none';
      moonIcon.style.display = 'inline';
    }

    toggle.addEventListener('change', () => {
      if (toggle.checked) {
        body.classList.add('dark-mode');
        localStorage.setItem('darkMode', 'enabled');
        sunIcon.style.display = 'none';
        moonIcon.style.display = 'inline';
      } else {
        body.classList.remove('dark-mode');
        localStorage.setItem('darkMode', 'disabled');
        sunIcon.style.display = 'inline';
        moonIcon.style.display = 'none';
      }
    });
  </script>
</body>
</html>
