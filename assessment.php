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
    <title>Self-Assessment | Therapeace</title>
    <link rel="stylesheet" href="dashboard.css">
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .quiz-section {
            padding: 2rem;
        }
        .quiz-box {
            background: #ffffff;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            max-width: 700px;
            margin: auto;
        }
        .quiz-box h2 {
            margin-bottom: 20px;
        }
        .question {
            margin-bottom: 20px;
        }
        .question p {
            font-weight: 500;
        }
        .question label {
            display: block;
            margin: 5px 0;
            cursor: pointer;
        }
        .quiz-box button {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #4caf50;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }
        .result-box {
            margin-top: 30px;
            padding: 20px;
            background-color: #f1f1f1;
            border-radius: 12px;
        }
		/* Dark Mode - Self-Assessment Styling (matches Activities page) */
body.dark-mode {
    background-color: #0f172a;
    color: #e0e0e0;
}

body.dark-mode .top-bar {
    background-color: #1e293b;
    border-bottom: 1px solid #333;
}

body.dark-mode .top-bar-left h2 {
    color: #f1f1f1;
}

body.dark-mode .quiz-box {
    background-color: #1e1e1e;
    color: #e0e0e0;
    box-shadow: 0 4px 15px rgba(255, 255, 255, 0.05);
}

body.dark-mode .quiz-box h2 {
    color: #a5d6a7;
}

body.dark-mode .question p {
    color: #f1f1f1;
}

body.dark-mode .question label {
    color: #cccccc;
}

body.dark-mode .quiz-box button {
    background-color: #4a90e2;
    color: #fff;
    border: none;
    padding: 10px 18px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 500;
    transition: background-color 0.3s ease;
}

body.dark-mode .quiz-box button:hover {
    background-color: #2563eb;
}

body.dark-mode .result-box {
    background-color: #2c2c2c;
    color: #ffffff;
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
                    <h2>Assesment</h2>
                </div>
				<div class="top-bar-right">
        <!-- Dark Mode Toggle -->
        <label class="dark-mode-switch">
            <input type="checkbox" id="darkToggle">
            <span class="slider"></span>
        </label>
    </div>
               
            </header>

            <!-- Self-Assessment Section -->
            <section class="quiz-section">
                <div class="quiz-box">
                    <h2>🧠 Stress Self-Assessment Quiz</h2>
                    <form id="quizForm">
                        <div class="question">
                            <p>1. I feel overwhelmed by daily tasks.</p>
                            <label><input type="radio" name="q1" value="2"> Always</label>
                            <label><input type="radio" name="q1" value="1"> Sometimes</label>
                            <label><input type="radio" name="q1" value="0"> Never</label>
                        </div>
                        <div class="question">
                            <p>2. I have trouble sleeping due to worry.</p>
                            <label><input type="radio" name="q2" value="2"> Always</label>
                            <label><input type="radio" name="q2" value="1"> Sometimes</label>
                            <label><input type="radio" name="q2" value="0"> Never</label>
                        </div>
                        <div class="question">
                            <p>3. I feel tired even after resting.</p>
                            <label><input type="radio" name="q3" value="2"> Always</label>
                            <label><input type="radio" name="q3" value="1"> Sometimes</label>
                            <label><input type="radio" name="q3" value="0"> Never</label>
                        </div>
                        <button type="button" onclick="calculateScore()">Submit</button>
                    </form>
                    <div class="result-box" id="resultBox" style="display:none;"></div>
                </div>
            </section>
        </main>
    </div>

    <script>
        function calculateScore() {
            const form = document.getElementById('quizForm');
            const formData = new FormData(form);
            let score = 0;

            for (let value of formData.values()) {
                score += parseInt(value);
            }

            let resultText = '';
            if (score <= 1) {
                resultText = '😌 You seem relaxed. Keep up the good habits!';
            } else if (score <= 3) {
                resultText = '😐 Mild stress detected. Try some mindfulness or talk to someone.';
            } else {
                resultText = '😟 High stress level. Consider scheduling an appointment with a counselor.';
            }

            const resultBox = document.getElementById('resultBox');
            resultBox.style.display = 'block';
            resultBox.innerHTML = `<strong>Your Score:</strong> ${score}/6<br><p>${resultText}</p>`;
        }
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
