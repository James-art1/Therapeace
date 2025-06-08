<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Total users and appointments
$totalUsers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM users"))['total'];
$totalAppointments = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM appointments"))['total'];

// Monthly appointments
$monthlyQuery = "
    SELECT DATE_FORMAT(appointment_date, '%Y-%m') AS month, COUNT(*) AS count 
    FROM appointments 
    WHERE status = 'Confirmed' 
    GROUP BY month 
    ORDER BY month
";
$monthlyResult = mysqli_query($conn, $monthlyQuery);

$months = [];
$counts = [];
while ($row = mysqli_fetch_assoc($monthlyResult)) {
    $months[] = $row['month'];
    $counts[] = $row['count'];
}

// User-wise appointment summary
$userProgressQuery = "
    SELECT u.id, u.full_name, u.email, 
           COUNT(a.id) AS total_appointments,
           SUM(CASE WHEN a.status = 'Confirmed' THEN 1 ELSE 0 END) AS confirmed,
           SUM(CASE WHEN a.status = 'Pending' THEN 1 ELSE 0 END) AS pending,
           SUM(CASE WHEN a.status = 'Cancelled' THEN 1 ELSE 0 END) AS cancelled
    FROM users u
    LEFT JOIN appointments a ON u.id = a.user_id
    GROUP BY u.id
";
$userProgressResult = mysqli_query($conn, $userProgressQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Generate Reports - Therapeace</title>
    <link rel="stylesheet" href="admin.css">
	  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .report-box {
            background: #fff;
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .report-box h3 {
            margin-top: 0;
            color: #2c3e50;
        }

        .user-table {
            width: 100%;
            border-collapse: collapse;
        }

        .user-table th, .user-table td {
            padding: 0.75rem;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        .user-table th {
            background-color: #f0f4f7;
        }

        .chart-img {
            width: 100%;
            max-width: 600px;
            display: block;
            margin: 1rem auto;
            border: 1px solid #ccc;
            border-radius: 10px;
        }
    </style>
</head>
<body>
<div class="dashboard">
    <aside class="sidebar">
        <div class="sidebar-logo">
            <img src="l1.png" alt="Therapeace Logo">
            <h1>Therapeace</h1>
        </div>
        <ul class="sidebar-menu">
            <li><a href="admin.php"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="manage_users.php"><i class="fas fa-users"></i> Manage Users</a></li>
            <li><a href="view_appointments.php"><i class="fas fa-calendar-check"></i> View Appointments</a></li>
            <li><a href="view_feedback.php"><i class="fas fa-comments"></i> View Feedback</a></li>
            <li><a href="generate_reports.php"><i class="fas fa-file-alt"></i> Generate Reports</a></li>
            <li><a href="add_resources.php"><i class="fas fa-book"></i> Add Resources</a></li>
            <li><a href="admin_notifications.php"><i class="fas fa-bell"></i> Notifications</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <h2>System Reports</h2>

        <div class="report-box">
            <h3>Overall Summary</h3>
            <p><strong>Total Users:</strong> <?= $totalUsers ?></p>
            <p><strong>Total Appointments:</strong> <?= $totalAppointments ?></p>
        </div>

        <div class="report-box">
            <h3>Monthly Confirmed Appointments Trend</h3>
            <img src="monthly_appointments_chart.png" alt="Monthly Appointment Chart" class="chart-img">
        </div>

        <div class="report-box">
            <h3>User-wise Appointment Summary</h3>
            <table class="user-table">
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Total</th>
                        <th>Confirmed</th>
                        <th>Pending</th>
                        <th>Cancelled</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($userProgressResult)) { ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['full_name']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= $row['total_appointments'] ?></td>
                            <td><?= $row['confirmed'] ?></td>
                            <td><?= $row['pending'] ?></td>
                            <td><?= $row['cancelled'] ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </main>
</div>
</body>
</html>
