<?php
session_start();
include 'db.php'; // Database connection

// Ensure the admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: Login.html");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $title = $_POST['title'];
    $message = $_POST['message'];

    $stmt = $conn->prepare("INSERT INTO notifications (user_id, title, message) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $title, $message);
    
    if ($stmt->execute()) {
        // Set success message in session
        $_SESSION['message'] = "Notification sent successfully!";
        header("Location: admin_notifications.php"); // Redirect to notifications page
        exit();
    } else {
        $_SESSION['message'] = "Failed to send notification.";
        header("Location: admin_notifications.php"); // Redirect to notifications page
        exit();
    }
}

// Retrieve admin's full name
$adminFullName = $_SESSION['full_name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Notifications</title>
    <link rel="stylesheet" href="admin.css">
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
                <li><a href="admin_notifications.php" class="active"><i class="fas fa-bell"></i> Notifications</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <h2>Send Notification</h2>

            <!-- Display the success or failure message -->
            <?php if (isset($_SESSION['message'])): ?>
                <div class="message-box <?php echo (strpos($_SESSION['message'], 'successfully') !== false) ? 'success' : 'error'; ?>">
                    <p><?php echo htmlspecialchars($_SESSION['message']); ?></p>
                </div>
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>

            <div class="form-container">
                <form method="POST">
                    <label>User ID:</label>
                    <input type="number" name="user_id" required>
                    
                    <label>Title:</label>
                    <input type="text" name="title" required>
                    
                    <label>Message:</label>
                    <textarea name="message" required></textarea>
                    
                    <button type="submit">Send Notification</button>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
