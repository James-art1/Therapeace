<?php
session_start();
require 'db.php';

// Ensure only admins can access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Fetch all feedback with user details
$query = "SELECT f.id, u.full_name, f.session_feedback, f.improvements, f.emoji_rating, f.status, f.admin_reply, f.submitted_at 
          FROM feedback f 
          JOIN users u ON f.user_id = u.id 
          ORDER BY f.submitted_at DESC";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin - View Feedback</title>
    <link rel="stylesheet" href="admin.css">
    <style>
    .success-message {
        background-color: #d4edda;
        color: #155724;
        padding: 12px 16px;
        margin-bottom: 15px;
        border-left: 5px solid #28a745;
        border-radius: 5px;
    }

    .error-message {
        background-color: #f8d7da;
        color: #721c24;
        padding: 12px 16px;
        margin-bottom: 15px;
        border-left: 5px solid #dc3545;
        border-radius: 5px;
    }

    table.feedback-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        font-family: Arial, sans-serif;
        background-color: #fff;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        border: 2px solid #333; /* visible outer border */
    }

    table.feedback-table thead {
        background-color: #444;
        color: white;
    }

    table.feedback-table th,
    table.feedback-table td {
        border: 1px solid #999 !important; /* strong visible cell borders */
        padding: 12px 15px;
        text-align: left;
        vertical-align: top;
    }

    table.feedback-table td form {
        display: flex;
        flex-direction: column;
    }

    table.feedback-table select,
    table.feedback-table input[type="text"],
    table.feedback-table button {
        margin-top: 5px;
        padding: 6px;
        border: 1px solid #888;
        border-radius: 4px;
        font-size: 14px;
    }

    table.feedback-table button {
        background-color: #4CAF50;
        color: white;
        cursor: pointer;
    }

    table.feedback-table button:hover {
        background-color: #45a049;
    }
</style>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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

        <!-- Main Content -->
        <main class="main-content">
            <?php if (isset($_SESSION['feedback_success'])): ?>
                <div class="success-message">
                    <?php 
                        echo $_SESSION['feedback_success']; 
                        unset($_SESSION['feedback_success']); 
                    ?>
                </div>
            <?php elseif (isset($_SESSION['feedback_error'])): ?>
                <div class="error-message">
                    <?php 
                        echo $_SESSION['feedback_error']; 
                        unset($_SESSION['feedback_error']); 
                    ?>
                </div>
            <?php endif; ?>

            <h2>User Feedback</h2>

            <table class="feedback-table">
                <thead>
                    <tr>
                        <th>Full Name</th>
                        <th>Feedback</th>
                        <th>Improvements</th>
                        <th>Emoji Rating</th>
                        <th>Submitted At</th>
                        <th>Status</th>
                        <th>Admin Reply</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                        <td><?php echo nl2br(htmlspecialchars($row['session_feedback'])); ?></td>
                        <td><?php echo nl2br(htmlspecialchars($row['improvements'])); ?></td>
                        <td><?php echo htmlspecialchars($row['emoji_rating']); ?></td>
                        <td><?php echo date("d M Y, h:i A", strtotime($row['submitted_at'])); ?></td>
                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                        <td><?php echo nl2br(htmlspecialchars($row['admin_reply'] ?? "No reply yet")); ?></td>
                        <td>
                            <form action="update_feedback.php" method="POST">
                                <input type="hidden" name="feedback_id" value="<?php echo $row['id']; ?>">
                                <select name="status">
                                    <option value="Pending" <?php if ($row['status'] == "Pending") echo "selected"; ?>>Pending</option>
                                    <option value="Reviewed" <?php if ($row['status'] == "Reviewed") echo "selected"; ?>>Reviewed</option>
                                    <option value="Resolved" <?php if ($row['status'] == "Resolved") echo "selected"; ?>>Resolved</option>
                                </select>
                                <input type="text" name="reply" placeholder="Reply..." required>
                                <button type="submit">Update</button>
                            </form>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </main>
    </div>
</body>
</html>
