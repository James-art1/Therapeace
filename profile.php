<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$userFullName = $_SESSION['full_name'];

// Fetch the latest appointment details
$query = "SELECT * FROM appointments WHERE user_id = ? ORDER BY appointment_date DESC, appointment_time DESC LIMIT 1";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$appointment = $result->fetch_assoc();

// Assign default values if data is missing
$gender = $appointment['gender'] ?? 'Not Available';
$collegeType = $appointment['college_type'] ?? 'Not Available';
$class = $appointment['class'] ?? 'Not Available';
$stream = $appointment['stream'] ?? 'Not Available';
$roll = $appointment['roll_number'] ?? 'Not Available';
$referredBy = $appointment['referred_by'] ?? 'Not Available';
$firstTimeVisit = $appointment['first_time_visit'] ?? 'Not Available';
$lastAppointment = (!empty($appointment)) ? $appointment['appointment_date'] . ' at ' . $appointment['appointment_time'] : 'No Appointments Found';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Therapeace - Profile</title>
    <link rel="stylesheet" href="dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
	<style>
	/* General body light/dark styles */
body {
    transition: background-color 0.3s ease, color 0.3s ease;
}

body.dark-mode {
    background-color: #0f172a;
    color: #e0e0e0;
}

/* Top bar */
body.dark-mode .top-bar {
    background-color: #1e293b;
    color: #f1f1f1;
    border-bottom: 1px solid #333;
}



/* Profile Card */
body.dark-mode .glass-card {
    background: blue;
    color: #ffffff;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

/* Dark mode toggle styles */
.dark-mode-switch {
    position: relative;
    display: inline-block;
    width: 50px;
    height: 24px;
}

.dark-mode-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.dark-mode-switch .slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    border-radius: 24px;
    transition: .4s;
}

.dark-mode-switch .slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    border-radius: 50%;
    transition: .4s;
}

.dark-mode-switch input:checked + .slider {
    background-color:green;
}

.dark-mode-switch input:checked + .slider:before {
    transform: translateX(26px);
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
                <h2>Profile</h2>
				<div class="top-bar-right">
    <label class="dark-mode-switch">
        <input type="checkbox" id="darkToggle">
        <span class="slider"></span>
    </label>
</div>

            </header>

            <section class="profile-section">
                <div class="profile-card glass-card">
                   
                    <p><strong>Full Name:</strong> <?php echo htmlspecialchars($userFullName); ?></p>
                    <p><strong>Gender:</strong> <?php echo htmlspecialchars($gender); ?></p>
                    <p><strong>College Type:</strong> <?php echo htmlspecialchars($collegeType); ?></p>
                    <p><strong>Class:</strong> <?php echo htmlspecialchars($class); ?></p>
                    <p><strong>Stream:</strong> <?php echo htmlspecialchars($stream); ?></p>
                    <p><strong>Roll Number:</strong> <?php echo htmlspecialchars($roll); ?></p>
                    <p><strong>Referred By:</strong> <?php echo htmlspecialchars($referredBy); ?></p>
                    <p><strong>First Time Visit:</strong> <?php echo htmlspecialchars($firstTimeVisit); ?></p>
                    <p><strong>Last Appointment:</strong> <?php echo htmlspecialchars($lastAppointment); ?></p>
                </div>
            </section>
        </main>
    </div>
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
