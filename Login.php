<?php
session_start();
include "db.php"; // Include database connection

$login_success = false;
$redirect_url = "";
$error_message = ""; // Variable to hold error message

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST['full_name']);
    $password = $_POST['password'];

    // Server-side validation
    if (empty($full_name)) {
        $error_message = "Full Name is required.";
    } elseif (strlen($password) < 6) {
        $error_message = "Password must be at least 6 characters long.";
    } else {
        // Check if user exists by full name
        $stmt = $conn->prepare("SELECT * FROM users WHERE full_name = ?");
        $stmt->bind_param("s", $full_name);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // Verify password
            if (password_verify($password, $user['password'])) {
                $_SESSION['logged_in'] = true;
                $_SESSION['full_name'] = $full_name;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];

                $login_success = true;
                $redirect_url = ($user['role'] === 'admin') ? 'admin.php' : 'dashboard.php';
            } else {
                $error_message = "Incorrect password. Please try again.";
            }
        } else {
            $error_message = "User not found.";
        }
        $stmt->close();
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Status</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .message-box {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 30px 40px;
            box-shadow: 0 0 20px #00ffcc;
            backdrop-filter: blur(8px);
            text-align: center;
            color: #fff;
            animation: fadeIn 1s ease-out;
        }
        .error {
            border: 2px solid #ff4d4d;
            color: #ff4d4d;
            box-shadow: 0 0 20px #ff4d4d;
        }
        .success {
            border: 2px solid #00ffcc;
            color: #00ffcc;
            box-shadow: 0 0 20px #00ffcc;
        }
        h1 {
            font-size: 2em;
            margin-bottom: 10px;
        }
        p {
            font-size: 1.2em;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <?php if ($login_success): ?>
        <div class="message-box success">
            <h1>Login Successful!</h1>
            <p>Redirecting to your dashboard...</p>
        </div>
        <script>
            setTimeout(() => {
                window.location.href = "<?php echo $redirect_url; ?>";
            }, 3000);
        </script>
    <?php elseif (!empty($error_message)): ?>
        <div class="message-box error">
            <h1>Error</h1>
            <p><?php echo htmlspecialchars($error_message); ?></p>
        </div>
        <script>
            setTimeout(() => {
                window.location.href = "Login.html"; // Redirect to Login page after 3 seconds
            }, 3000);
        </script>
    <?php endif; ?>
</body>
</html>
