<?php
session_start();
require 'db.php';

// Ensure only logged-in users (not admins) can access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit();
}

// Get the logged-in user ID
$user_id = $_SESSION['user_id'];

// Fetch only resources assigned to the logged-in user
$sql = "SELECT r.title, r.description, r.file_path, r.resource_link 
        FROM resources r
        JOIN resource_assignments ra ON r.id = ra.resource_id
        WHERE ra.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Therapeace - Resources</title>
    <link rel="stylesheet" href="dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Resource Table Styling */
        .resource-section {
            padding: 20px;
        }

        .resource-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .resource-table th, .resource-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .resource-table th {
            background: linear-gradient(to right, #4CAF50, #2E7D32);
            color: white;
        }

        .resource-table tr:hover {
            background-color: #f9f9f9;
        }

        .download-btn {
            background: #007bff;
            color: white;
            padding: 6px 12px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            transition: 0.3s;
        }

        .download-btn:hover {
            background: #0056b3;
        }

        /* Responsive Table */
        @media (max-width: 768px) {
            .resource-table th, .resource-table td {
                padding: 10px;
                font-size: 14px;
            }
        }
		#darkToggle {
    width: 40px;
    height: 20px;
    position: relative;
    appearance: none;
    background-color: #ccc;
    border-radius: 50px;
    cursor: pointer;
    transition: background-color 0.3s;
}

#darkToggle:checked {
    background-color: green;
}

#darkToggle:before {
    content: "";
    position: absolute;
    width: 16px;
    height: 16px;
    top: 2px;
    left: 2px;
    background-color: white;
    border-radius: 50%;
    transition: left 0.3s;
}

#darkToggle:checked:before {
    left: 22px;
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
                <li><a href="assessment.php" ><i class="fas fa-clipboard-check"></i> Self-Assessment</a></li>
                <li><a href="profile.php"><i class="fas fa-user"></i> Profile</a></li>
                <li><a href="notifications.php"><i class="fas fa-bell"></i> Notifications</a></li>
                <li><a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </aside>


        <!-- Main Content -->
        <main class="main-content">
            <header class="top-bar">
                <div class="top-bar-left">
                    <h2>Resources</h2>
					 </div>
    <div class="top-bar-right">
        <!-- Dark Mode Toggle -->
        <label class="dark-mode-switch">
            <input type="checkbox" id="darkToggle">
            <span class="slider"></span>
        </label>
    </div>
            </header>

            <!-- Resources Section -->
            <section class="resource-section">
                <h3>Available Resources</h3>

                <!-- Resource Table -->
                <table class="resource-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Download / View</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['title']); ?></td>
                                    <td><?= htmlspecialchars($row['description']); ?></td>
                                    <td>
                                        <?php if (!empty($row['file_path'])): ?>
                                            <a href="<?= htmlspecialchars($row['file_path']); ?>" class="download-btn" target="_blank">
                                                <i class="fas fa-download"></i> Download
                                            </a>
                                        <?php elseif (!empty($row['resource_link'])): ?>
                                            <a href="<?= htmlspecialchars($row['resource_link']); ?>" class="download-btn" target="_blank">
                                                <i class="fas fa-external-link-alt"></i> View
                                            </a>
                                        <?php else: ?>
                                            <span style="color: red;">No file/link</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" style="text-align: center; font-weight: bold;">No resources available</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>

    <script>
        // Highlight active sidebar menu
        document.addEventListener("DOMContentLoaded", function () {
            let links = document.querySelectorAll(".sidebar-menu li a");
            links.forEach(link => {
                if (link.href === window.location.href) {
                    link.classList.add("active");
                }
            });
        });
				
  const toggle = document.getElementById('darkToggle');
  const body = document.body;

  // Load theme from localStorage
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
