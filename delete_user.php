<?php
require 'db.php'; // Include database connection

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Prepare and execute the delete query
    $query = "DELETE FROM users WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        // Bind the user_id to the query
        mysqli_stmt_bind_param($stmt, "i", $user_id);

        if (mysqli_stmt_execute($stmt)) {
            // Redirect back to manage_users.php with success message
            header("Location: manage_users.php?message=User deleted successfully");
            exit();
        } else {
            echo "Error executing query: " . mysqli_error($conn);
        }
    } else {
        echo "Error preparing statement: " . mysqli_error($conn);
    }
} else {
    echo "Invalid request! User ID is missing.";
}
?>
