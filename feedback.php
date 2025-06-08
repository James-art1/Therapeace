<?php
session_start();
require 'db.php';

// Ensure only logged-in users (not admins) can access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit();
}

$full_name = $_SESSION['full_name']; // Fetch user's full name
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Therapeace - Feedback</title>
    <link rel="stylesheet" href="dashboard.css">
   <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Notification Messages */
        .message {
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
            display: none;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
		#darkToggle {
    width: 40px;
    height: 20px;
    position: relative;
    appearance: none;
    background-color: #ccc;
    border-radius: 50px;
    cursor: pointer;
    transition: background-color 0.3s;
}

#darkToggle:checked {
    background-color: green;
}

#darkToggle:before {
    content: "";
    position: absolute;
    width: 16px;
    height: 16px;
    top: 2px;
    left: 2px;
    background-color: white;
    border-radius: 50%;
    transition: left 0.3s;
}

#darkToggle:checked:before {
    left: 22px;
}

    </style>
</head>
<body>
    <div class="dashboard">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-logo">
                <img src="l1.png" alt="Therapeace Logo">
                <h1>Therapeace</h1>
            </div>
                        <ul class="sidebar-menu">
                <li><a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="appointments.php"><i class="fas fa-calendar"></i> Appointments</a></li>
                <li><a href="feedback.php"><i class="fas fa-comments"></i> Feedback</a></li>
                <li><a href="resources.php"><i class="fas fa-book"></i> Resources</a></li>
                <li><a href="activities.php"><i class="fas fa-spa"></i> Mindfulness</a></li>
                <li><a href="assessment.php" ><i class="fas fa-clipboard-check"></i> Self-Assessment</a></li>
                <li><a href="profile.php"><i class="fas fa-user"></i> Profile</a></li>
                <li><a href="notifications.php"><i class="fas fa-bell"></i> Notifications</a></li>
                <li><a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="top-bar">
    <div class="top-bar-left">
        <h2>Feedback</h2>
    </div>
    <div class="top-bar-right">
        <!-- Dark Mode Toggle (without outer container) -->
        <input type="checkbox" id="darkToggle">
    </div>
</header>
			

            <!-- Message Notifications -->
            <div id="messageBox">
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="message success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
                <?php elseif (isset($_SESSION['error'])): ?>
                    <div class="message error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
                <?php endif; ?>
            </div>

            <!-- Feedback Section -->
            <section class="feedback-section">
                <div class="feedback-container">
                    <h3>We Value Your Feedback</h3>
                    <p>Your feedback helps us improve our services. Please share your thoughts below.</p>

                    <form action="submit_feedback.php" method="POST" class="feedback-form">
                        <label for="sessionFeedback"><i class="fas fa-comment-dots"></i> How was your last session?</label>
                        <textarea id="sessionFeedback" name="sessionFeedback" rows="4" required placeholder="Share your experience..."></textarea>

                        <label for="improvements"><i class="fas fa-lightbulb"></i> What can we do better?</label>
                        <textarea id="improvements" name="improvements" rows="4" required placeholder="Suggest improvements..."></textarea>
                        
                        <!-- Emoji Rating -->
                        <div class="emoji-rating">
                            <span data-value="1">😡</span>
                            <span data-value="2">😐</span>
                            <span data-value="3">😊</span>
                            <span data-value="4">😍</span>
                            <input type="hidden" name="emoji_rating" id="emoji_rating">
                        </div>

                        <button type="submit" class="submit-btn"><i class="fas fa-paper-plane"></i> Submit Feedback</button>
                    </form>
                </div>
            </section>
        </main>
    </div>

    <script>
        // Show Message Box
        document.addEventListener("DOMContentLoaded", function () {
            let messageBox = document.querySelector("#messageBox .message");
            if (messageBox) {
                messageBox.style.display = "block";
                setTimeout(() => {
                    messageBox.style.display = "none";
                }, 3000);
            }

            // Emoji Rating Selection
            const emojis = document.querySelectorAll(".emoji-rating span");
            const ratingInput = document.getElementById("emoji_rating");

            emojis.forEach(emoji => {
                emoji.addEventListener("click", function () {
                    emojis.forEach(e => e.classList.remove("selected"));
                    this.classList.add("selected");
                    ratingInput.value = this.getAttribute("data-value");
                });
            });
        });
		
  const toggle = document.getElementById('darkToggle');
  const body = document.body;

  // Load theme from localStorage
  if (localStorage.getItem('darkMode') === 'enabled') {
    body.classList.add('dark-mode');
    toggle.checked = true;
  }

  toggle.addEventListener('change', () => {
    if (toggle.checked) {
      body.classList.add('dark-mode');
      localStorage.setItem('darkMode', 'enabled');
    } else {
      body.classList.remove('dark-mode');
      localStorage.setItem('darkMode', 'disabled');
    }
  });


    </script>
</body>
</html>
