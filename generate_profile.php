<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: Login.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $bio = $_POST['bio'];

    $query = "INSERT INTO profiles (user_id, full_name, email, phone, address, bio) 
              VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("isssss", $user_id, $full_name, $email, $phone, $address, $bio);
    
    if ($stmt->execute()) {
        echo "<script>alert('User profile created successfully!'); window.location.href='manage_users.php';</script>";
    } else {
        echo "<script>alert('Error creating profile.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate User Profile</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <div class="container">
        <h2>Create User Profile</h2>
        <form action="generate_profile.php" method="POST">
            <label for="user_id">User ID:</label>
            <input type="number" name="user_id" required>

            <label for="full_name">Full Name:</label>
            <input type="text" name="full_name" required>

            <label for="email">Email:</label>
            <input type="email" name="email" required>

            <label for="phone">Phone:</label>
            <input type="tel" name="phone">

            <label for="address">Address:</label>
            <textarea name="address"></textarea>

            <label for="bio">Bio:</label>
            <textarea name="bio"></textarea>

            <button type="submit">Create Profile</button>
        </form>
    </div>
</body>
</html>
