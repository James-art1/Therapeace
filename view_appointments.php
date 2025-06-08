<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Fetch all appointments with user details
$query = "SELECT a.id, a.full_name, a.gender, a.college_type, a.class, a.stream, 
                 a.roll_number, a.referred_by, a.first_time_visit, 
                 a.appointment_date, a.appointment_time, a.status, u.email 
          FROM appointments a 
          JOIN users u ON a.user_id = u.id 
          ORDER BY a.appointment_date DESC";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>View Appointments</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <style>
        .appointments-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            border: 2px solid #333; /* visible outer border */
        }

        .appointments-table thead {
            background-color: #444;
            color: white;
        }

        .appointments-table th,
        .appointments-table td {
            border: 1px solid #999 !important; /* visible inner borders */
            padding: 10px 12px;
            text-align: left;
            vertical-align: top;
        }

        .appointments-table select,
        .appointments-table button {
            padding: 5px;
            font-size: 14px;
            border: 1px solid #888;
            border-radius: 4px;
        }

        .update-btn {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 6px 10px;
            border-radius: 4px;
            margin-top: 5px;
            cursor: pointer;
        }

        .update-btn:hover {
            background-color: #218838;
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
            <header class="top-bar">
                <h2>Appointments</h2>
            </header>

            <section class="appointments-section">
                <table class="appointments-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Gender</th>
                            <th>College</th>
                            <th>Class</th>
                            <th>Stream</th>
                            <th>Roll No</th>
                            <th>Referred By</th>
                            <th>First Visit</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['full_name']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo ucfirst($row['gender']); ?></td>
                            <td><?php echo ucfirst($row['college_type']); ?></td>
                            <td><?php echo strtoupper($row['class']); ?></td>
                            <td><?php echo ucfirst($row['stream']); ?></td>
                            <td><?php echo $row['roll_number']; ?></td>
                            <td><?php echo ucfirst($row['referred_by']); ?></td>
                            <td><?php echo ucfirst($row['first_time_visit']); ?></td>
                            <td><?php echo $row['appointment_date']; ?></td>
                            <td><?php echo date("h:i A", strtotime($row['appointment_time'])); ?></td>
                            <td>
                                <form action="update_appointment_status.php" method="POST">
                                    <input type="hidden" name="appointment_id" value="<?php echo $row['id']; ?>">
                                    <select name="status">
                                        <option value="Pending" <?php if ($row['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                                        <option value="Confirmed" <?php if ($row['status'] == 'Confirmed') echo 'selected'; ?>>Confirmed</option>
                                        <option value="Cancelled" <?php if ($row['status'] == 'Cancelled') echo 'selected'; ?>>Cancelled</option>
                                    </select>
                                    <button type="submit" class="update-btn"><i class="fas fa-save"></i></button>
                                </form>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>
</body>
</html>
