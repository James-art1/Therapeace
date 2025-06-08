<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $name = $_POST['name'];
    $gender = $_POST['gender'];
    $collegeType = $_POST['clg'];
    $class = $_POST['class'];
    $stream = $_POST['stream'] ?? ''; // Ensure stream is captured
    $roll = $_POST['roll'];
    $referredBy = $_POST['referredBy'];
    $firstTime = $_POST['time'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];

    // Check if stream is properly set
    if (empty($stream)) {
        $_SESSION['error_message'] = "Stream selection is required!";
        header("Location: appointments.php");
        exit();
    }

$stmt = $conn->prepare("INSERT INTO appointments (user_id, full_name, gender, college_type, class, stream, roll_number, referred_by, first_time_visit, appointment_date, appointment_time) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("issssssssss", $user_id, $name, $gender, $collegeType, $class, $stream, $roll, $referredBy, $firstTime, $appointment_date, $appointment_time);


    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Appointment booked successfully!";
        header("Location: appointments.php");
        exit();
    } else {
        $_SESSION['error_message'] = "Error booking appointment!";
        header("Location: appointments.php");
        exit();
    }
}
?>
