<?php
// message.php
$message = $_GET['message'] ?? 'Operation completed successfully.';
$redirect = $_GET['redirect'] ?? 'Login.html';
$delay = $_GET['delay'] ?? 3; // Delay in seconds
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Notification</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            font-family: 'Arial', sans-serif;
            color: #fff;
            margin: 0;
            overflow: hidden;
        }

        .message-box {
            background: rgba(255, 255, 255, 0.1);
            padding: 40px 50px;
            border-radius: 15px;
            box-shadow: 0 0 30px rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(10px);
            text-align: center;
            animation: glow 2s ease-in-out infinite alternate;
            transition: opacity 1s ease-in-out;
        }

        @keyframes glow {
            0% {
                box-shadow: 0 0 10px rgba(255, 255, 255, 0.3);
                opacity: 0.8;
            }
            100% {
                box-shadow: 0 0 30px rgba(255, 255, 255, 0.6);
                opacity: 1;
            }
        }

        h1 {
            margin-bottom: 20px;
            font-size: 28px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-weight: 600;
            letter-spacing: 1px;
            color: #fff;
        }

        p {
            font-size: 16px;
            font-family: 'Arial', sans-serif;
            margin-top: 10px;
            color: #fff;
        }

        .message-box p {
            font-size: 18px;
            font-weight: 500;
        }

    </style>
    <script>
        setTimeout(function() {
            window.location.href = "<?php echo htmlspecialchars($redirect); ?>";
        }, <?php echo (int)$delay * 1000; ?>);
    </script>
</head>
<body>
    <div class="message-box">
        <h1><?php echo htmlspecialchars($message); ?></h1>
        <p>Redirecting in <?php echo (int)$delay; ?> seconds...</p>
    </div>
</body>
</html>
