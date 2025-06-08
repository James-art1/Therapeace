<?php
session_start();
require 'db_connect.php';

// Ensure the admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: Login.php");
    exit();
}

// Fetch uploaded resources with assigned users
$query = "SELECT r.*, COALESCE(GROUP_CONCAT(u.full_name SEPARATOR ', '), 'Not Assigned') AS assigned_users 
          FROM resources r
          LEFT JOIN resource_assignments ra ON r.id = ra.resource_id
          LEFT JOIN users u ON ra.user_id = u.id
          GROUP BY r.id";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Resources</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <div class="dashboard">
        <main class="main-content">
            <h2>Uploaded Resources</h2>
            <table>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Category</th>
                    <th>File</th>
                    <th>Link</th>
                    <th>Assigned Users</th>
                </tr>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?= htmlspecialchars($row['title']) ?></td>
                        <td><?= htmlspecialchars($row['description']) ?></td>
                        <td><?= htmlspecialchars($row['category']) ?></td>
                        <td>
                            <?php if (!empty($row['file_path'])) { ?>
                                <a href="<?= htmlspecialchars($row['file_path']) ?>" target="_blank">View File</a>
                            <?php } else { ?>
                                No File
                            <?php } ?>
                        </td>
                        <td>
                            <?php if (!empty($row['resource_link'])) { ?>
                                <a href="<?= htmlspecialchars($row['resource_link']) ?>" target="_blank">View Link</a>
                            <?php } else { ?>
                                No Link
                            <?php } ?>
                        </td>
                        <td><?= htmlspecialchars($row['assigned_users']) ?></td>
                    </tr>
                <?php } ?>
            </table>
        </main>
    </div>
</body>
</html>
