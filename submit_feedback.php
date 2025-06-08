<?php
session_start();
require 'db.php';

// Ensure only users (not admins) can submit feedback
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$sessionFeedback = trim($_POST['sessionFeedback']);
$improvements = trim($_POST['improvements']);
$emoji_rating = $_POST['emoji_rating'] ?? null;

// Validate required fields
if (empty($sessionFeedback) || empty($improvements)) {
    $_SESSION['error'] = "All fields are required.";
    header("Location: feedback.php");
    exit();
}

// Insert feedback into the database
$stmt = $conn->prepare("INSERT INTO feedback (user_id, session_feedback, improvements, emoji_rating, submitted_at, status, admin_reply) 
                        VALUES (?, ?, ?, ?, NOW(), 'Pending', NULL)");
$stmt->bind_param("isss", $user_id, $sessionFeedback, $improvements, $emoji_rating);

if ($stmt->execute()) {
    $_SESSION['success'] = "Thank you for your feedback!";
} else {
    $_SESSION['error'] = "Error submitting feedback. Please try again.";
}

$stmt->close();
$conn->close();

header("Location: feedback.php");
exit();
?>
