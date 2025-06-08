<?php
session_start();
require 'db_connect.php';

// Ensure the admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: Login.php");
    exit();
}

$message = "";

// Fetch users for assignment
$user_query = "SELECT id, full_name FROM users WHERE role='user'";
$user_result = mysqli_query($conn, $user_query);

// Handle Resource Upload
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $resource_link = mysqli_real_escape_string($conn, $_POST['resource_link']);
    $file = $_FILES['file'];
    $assigned_users = $_POST['assigned_users']; // Array of selected users

    // Validate: At least one of file or link must be provided
    if (empty($resource_link) && $file['error'] !== 0) {
        $message = "Please provide either a file or a link!";
    } else {
        $filePath = "";

        // Handle File Upload if a file is selected
        if ($file['error'] === 0) {
            $fileName = time() . "_" . basename($file['name']);
            $filePath = "uploads/" . $fileName;
            move_uploaded_file($file['tmp_name'], $filePath);
        }

        // Insert into database
        $query = "INSERT INTO resources (title, description, file_path, category, resource_link) VALUES ('$title', '$description', '$filePath', '$category', '$resource_link')";
        if (mysqli_query($conn, $query)) {
            $resource_id = mysqli_insert_id($conn);

            // Assign resource to selected users
            foreach ($assigned_users as $user_id) {
                mysqli_query($conn, "INSERT INTO resource_assignments (resource_id, user_id) VALUES ('$resource_id', '$user_id')");
            }

            $message = "Resource added and assigned successfully!";
        } else {
            $message = "Database error: " . mysqli_error($conn);
        }
    }
}

// Retrieve admin's full name
$adminFullName = $_SESSION['full_name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add Resources</title>
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
                <li><a href="add_resources.php" class="active"><i class="fas fa-book"></i> Add Resources</a></li>
               
                <li><a href="admin_notifications.php"><i class="fas fa-bell"></i> Notifications</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <h2>Add Resources</h2>

            <?php if ($message) { echo "<p class='success-message'>$message</p>"; } ?>

            <div class="form-container">
                <form action="add_resources.php" method="POST" enctype="multipart/form-data">
                    <label>Title:</label>
                    <input type="text" name="title" >
                    
                    <label>Description:</label>
                    <textarea name="description" ></textarea>

                    <label>Category:</label>
                    <input type="text" name="category" >

                    <label>Upload File (Optional):</label>
                    <input type="file" name="file">

                    <label>Link (YouTube, Website, etc. - Optional):</label>
                    <input type="url" name="resource_link">

                    <label>Assign to Users:</label>
                    <select name="assigned_users[]" multiple required>
                        <?php while ($user = mysqli_fetch_assoc($user_result)) { ?>
                            <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['full_name']) ?></option>
                        <?php } ?>
                    </select>

                    <button type="submit">Add Resource</button>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
