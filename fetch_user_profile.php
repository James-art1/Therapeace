<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: Login.html");
    exit();
}

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM profiles WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$profile = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Therapeace - Profile</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <div class="dashboard">
        <aside class="sidebar">
            <ul class="sidebar-menu">
                <li><a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="profile.php" class="active"><i class="fas fa-user"></i> Profile</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <header class="top-bar">
                <h2>My Profile</h2>
            </header>

            <section class="profile-section">
                <div class="profile-card">
                    <img src="<?php echo $profile['profile_picture']; ?>" alt="Profile Picture">
                    <h3><?php echo htmlspecialchars($profile['full_name']); ?></h3>
                    <p>Email: <?php echo htmlspecialchars($profile['email']); ?></p>
                    <p>Phone: <?php echo htmlspecialchars($profile['phone']); ?></p>
                    <p>Address: <?php echo htmlspecialchars($profile['address']); ?></p>
                    <p>Bio: <?php echo htmlspecialchars($profile['bio']); ?></p>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
