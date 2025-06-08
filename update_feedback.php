<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $feedback_id = $_POST['feedback_id'];
    $status = $_POST['status'];
    $reply = mysqli_real_escape_string($conn, $_POST['reply']);

    $update_query = "UPDATE feedback 
                     SET status = '$status', admin_reply = '$reply' 
                     WHERE id = $feedback_id";

    if (mysqli_query($conn, $update_query)) {
        $_SESSION['feedback_success'] = "Feedback updated successfully.";
    } else {
        $_SESSION['feedback_error'] = "Failed to update feedback.";
    }

    header("Location: view_feedback.php");
    exit();
}
?>
