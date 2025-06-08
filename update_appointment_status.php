<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $appointment_id = $_POST['appointment_id'];
    $new_status = $_POST['status'];

    $query = "UPDATE appointments SET status = '$new_status' WHERE id = $appointment_id";
    
    if (mysqli_query($conn, $query)) {
        $_SESSION['success_message'] = "Appointment status updated successfully!";
    } else {
        $_SESSION['error_message'] = "Error updating status.";
    }

    header("Location: view_appointments.php");
    exit();
}
?>
