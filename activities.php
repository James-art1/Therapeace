<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

$userFullName = $_SESSION['full_name'];
$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mindfulness & Activities | Therapeace</title>
    <link rel="stylesheet" href="dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Dark Mode - Mindfulness Page Styling */
        body.dark-mode {
            background-color:  #0f172a;
            color: #e0e0e0;
        }

        body.dark-mode .top-bar {
            background-color: #1e293b;
            border-bottom: 1px solid #333;
        }

        body.dark-mode .top-bar-left h2 {
            color: #f1f1f1;
        }

        body.dark-mode .activity-section {
            background-color: transparent;
            padding: 20px;
            color: #e0e0e0;
        }

        body.dark-mode .activity-section h1,
        body.dark-mode .activity-section p {
            color: #f1f1f1;
        }

        body.dark-mode .activities-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        body.dark-mode .activity-card {
            background-color: #1e1e1e;
            color: #fff;
            border-radius: 12px;
            padding: 20px;
            flex: 1;
            min-width: 280px;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.05);
            transition: background-color 0.3s ease;
        }

        body.dark-mode .activity-card h3 {
            color: #a5d6a7;
        }

        body.dark-mode .activity-card p {
            color: #cccccc;
        }

        body.dark-mode .activity-card button {
            background-color: #4a90e2;
            color: #fff;
            border: none;
            padding: 10px 18px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }

        body.dark-mode .activity-card button:hover {
            background-color: #2563eb;
        }

        body.dark-mode audio {
            width: 100%;
            margin-top: 10px;
            background-color: #2c2c2c;
            border-radius: 8px;
        }

        /* Glowing Message Styles */
        .glow-message {
            text-align: center;
            font-size: 1.2rem;
            font-weight: 500;
            padding: 15px 25px;
            margin-bottom: 20px;
            background: linear-gradient(90deg, #4ade80, #60a5fa);
            color: #fff;
            border-radius: 12px;
            animation: glow 1.5s ease-in-out infinite alternate;
            box-shadow: 0 0 15px rgba(96, 165, 250, 0.6);
        }

        @keyframes glow {
            from {
                box-shadow: 0 0 8px #60a5fa;
            }
            to {
                box-shadow: 0 0 18px #4ade80;
            }
        }

        body.dark-mode .glow-message {
            background: linear-gradient(90deg, #34d399, #3b82f6);
            color: #f0fdf4;
            box-shadow: 0 0 15px rgba(59, 130, 246, 0.4);
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
                <li><a href="activities.php" ><i class="fas fa-spa"></i> Mindfulness</a></li>
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
                    <h2>Mindfulness</h2>
                </div>
                <div class="top-bar-right">
                    <!-- Dark Mode Toggle -->
                    <label class="dark-mode-switch">
                        <input type="checkbox" id="darkToggle">
                        <span class="slider"></span>
                    </label>
                </div>
            </header>

            <section class="activity-section">
                <h1>🧘 Mindfulness & Activities</h1>
                <p>Calm your mind with our guided sessions and positive vibes 🌿</p>

                <div id="glow-message" class="glow-message" style="display: none;">
                    <p>Start breathing slowly... Inhale... Exhale...</p>
                </div>

                <div id="affirmation-message" class="glow-message" style="display: none;">
                    <p id="affirmation-text">You are enough.</p>
                </div>

                <div class="activities-grid">
                    <!-- Breathing Exercise -->
                    <div class="activity-card">
                        <h3>🧘 Breathing Exercise</h3>
                        <p>A simple 2-minute breathing activity to relax your body.</p>
                        <button onclick="startBreathing()">Start</button>
                    </div>

                    <!-- Meditation Audio -->
                    <div class="activity-card">
                        <h3>🎧 Guided Meditation</h3>
                        <p>Listen to a short 5-minute calming meditation.</p>
                        <audio controls>
                            <source src="media/meditation.mp3" type="audio/mpeg">
                            Your browser does not support the audio element.
                        </audio>
                    </div>

                    <!-- Affirmation Cards -->
                    <div class="activity-card">
                        <h3>💬 Affirmation Cards</h3>
                        <p>Daily affirmations to boost your self-belief.</p>
                        <button onclick="showAffirmation()">Show Affirmation</button>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <script>
        // Dark Mode Toggle
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

        // Start Breathing Exercise
        function startBreathing() {
            document.getElementById('glow-message').style.display = 'block';
            setTimeout(() => {
                document.getElementById('glow-message').style.display = 'none';
            }, 5000);
        }

        // Show Affirmation
        function showAffirmation() {
            const affirmations = [
                "You are enough.",
                "You’ve overcome so much already.",
                "Take one step at a time.",
                "You are growing every day.",
                "This too shall pass."
            ];
            const random = affirmations[Math.floor(Math.random() * affirmations.length)];
            const messageBox = document.getElementById('affirmation-message');
            const messageText = document.getElementById('affirmation-text');
            messageText.textContent = "💬 " + random;
            messageBox.style.display = 'block';
            setTimeout(() => {
                messageBox.style.display = 'none';
            }, 5000);
        }
    </script>
</body>
</html>
