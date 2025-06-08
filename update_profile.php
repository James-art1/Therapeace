<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$full_name = $_POST['full_name'];
$phone = $_POST['phone'];
$address = $_POST['address'];
$bio = $_POST['bio'];

// Handle Profile Picture Upload
$profile_picture = $_FILES['profile_picture']['name'];
if ($profile_picture) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($profile_picture);
    move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file);
} else {
    $target_file = $_POST['current_profile_picture'] ?? "default-avatar.png";
}

// Update Profile in Database
$sql = "UPDATE profiles SET full_name=?, phone=?, address=?, bio=?, profile_picture=? WHERE user_id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssi", $full_name, $phone, $address, $bio, $target_file, $user_id);

if ($stmt->execute()) {
    header("Location: profile.php?success=1");
} else {
    echo "Error updating profile.";
}
?>
