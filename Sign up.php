<?php
session_start();
include "db.php"; // Include database connection

// Initialize message variable
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user input and sanitize
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Server-side validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !str_ends_with($email, "@gmail.com")) {
        $message = "Please enter a valid Gmail address.";
    } elseif (strlen($password) < 6) {
        $message = "Password must be at least 6 characters long.";
    } else {
        // Hash the password before saving it
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Check if the email already exists
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $message = "Email already exists. Please choose another one.";
        } else {
            // Insert new user into the database
            $stmt = $conn->prepare("INSERT INTO users (full_name, email, password, role) VALUES (?, ?, ?, ?)");
            $role = 'user';
            $stmt->bind_param("ssss", $full_name, $email, $hashed_password, $role);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                header("Location: message.php?message=Registration+successful!+You+can+now+log+in.&redirect=Login.html&delay=3");
                exit();
            } else {
                $message = "An error occurred. Please try again later.";
            }
        }
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="Sign up.css">
    <script>
        function validateForm() {
            const fullName = document.getElementById("full_name").value.trim();
            const email = document.getElementById("email").value.trim();
            const password = document.getElementById("password").value;

            if (fullName === "") {
                alert("Full Name is required.");
                return false;
            }

            if (!email.endsWith("@gmail.com") || !email.includes("@")) {
                alert("Please enter a valid Gmail address (example@gmail.com).");
                return false;
            }

            if (password.length < 6) {
                alert("Password must be at least 6 characters long.");
                return false;
            }

            return true;
        }
    </script>
</head>
<body>
    <div class="signup-container">
        <form class="signup-form" action="Sign up.php" method="post" id="signupForm" onsubmit="return validateForm()">
            <h1>Sign Up</h1>

            <?php if (!empty($message)): ?>
                <div class="message"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>

            <div class="input-group">
                <input type="text" id="full_name" name="full_name" placeholder=" " required>
                <label for="full_name">Full Name</label>
            </div>
            <div class="input-group">
                <input type="email" id="email" name="email" placeholder=" " required>
                <label for="email">Email</label>
            </div>
            <div class="input-group">
                <input type="password" id="password" name="password" placeholder=" " required>
                <label for="password">Password</label>
            </div>

            <button type="submit" class="signup-btn">Sign Up</button>

            <p class="redirect">
                Already have an account? <a href="Login.html">Login here</a>
            </p>
        </form>
    </div>
</body>
</html>
