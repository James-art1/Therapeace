<?php
session_start();
include "db.php"; // make sure your db.php sets up $conn

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE full_name = ?");
    $stmt->bind_param("s", $full_name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['logged_in'] = true;
            $_SESSION['full_name'] = $full_name;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            $redirect_url = ($user['role'] === 'admin') ? 'admin.php' : 'dashboard.php';

            // Optional success screen
            echo '
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="UTF-8">
                <title>Login Successful</title>
                <style>
                    body {
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        height: 100vh;
                        background: linear-gradient(135deg, #6a11cb, #2575fc);
                        font-family: Arial, sans-serif;
                    }
                    .success-message {
                        background: rgba(255, 255, 255, 0.1);
                        border: 2px solid #00ffcc;
                        border-radius: 15px;
                        padding: 30px 40px;
                        text-align: center;
                        color: #fff;
                        box-shadow: 0 0 20px #00ffcc;
                    }
                    .success-message h1 {
                        color: #00ffcc;
                        margin-bottom: 10px;
                    }
                    .success-message p {
                        font-size: 1.2em;
                    }
                </style>
            </head>
            <body>
                <div class="success-message">
                    <h1>Login Successful!</h1>
                    <p>Redirecting to your dashboard...</p>
                </div>
                <script>
                    setTimeout(() => {
                        window.location.href = "' . $redirect_url . '";
                    }, 2000);
                </script>
            </body>
            </html>';
            exit();
        } else {
            $_SESSION['login_error'] = "Incorrect password.";
            header("Location: Login.php");
            exit();
        }
    } else {
        $_SESSION['login_error'] = "Username not found.";
        header("Location: Login.php");
        exit();
    }
}
?>
