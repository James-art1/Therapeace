<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

$userFullName = $_SESSION['full_name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Therapeace - Appointments</title>
    <link rel="stylesheet" href="dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
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
                <li><a href="assessment.php"><i class="fas fa-clipboard-check"></i> Self-Assessment</a></li>
                <li><a href="profile.php"><i class="fas fa-user"></i> Profile</a></li>
                <li><a href="notifications.php"><i class="fas fa-bell"></i> Notifications</a></li>
                <li><a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="top-bar">
                <div class="top-bar-left">
                    <h2>Appointment</h2>
                </div>
                <div class="top-bar-right">
                    <!-- Dark Mode Toggle (without outer container) -->
                    <input type="checkbox" id="darkToggle">
                </div>
            </header>

            <?php
            if (isset($_SESSION['success_message'])) {
                echo '<div class="alert success">' . $_SESSION['success_message'] . '</div>';
                unset($_SESSION['success_message']);
            }

            if (isset($_SESSION['error_message'])) {
                echo '<div class="alert error">' . $_SESSION['error_message'] . '</div>';
                unset($_SESSION['error_message']);
            }
            ?>
            
            <!-- Appointment Form Section -->
            <section class="appointment-section">
                <div class="appointment-form glass-card">
                    <h3 class="form-title">Book Your Appointment</h3>
                    <form id="appointmentForm" action="book_appointment.php" method="POST">
                        <div class="form-grid">
                            <!-- Personal Information -->
                            <div class="form-group">
                                <label for="name">Full Name</label>
                                <input type="text" id="name" name="name" required>
                            </div>

                            <div class="form-group">
                                <label>Gender</label>
                                <div class="radio-group">
                                    <label><input type="radio" name="gender" value="male" required> Male</label>
                                    <label><input type="radio" name="gender" value="female" required> Female</label>
                                </div>
                            </div>

                            <!-- College Information -->
                            <div class="form-group">
                                <label>College Type</label>
                                <div class="radio-group">
                                    <label>
                                        <input type="radio" name="clg" value="degree" required> Degree College
                                    </label>
                                    <label>
                                        <input type="radio" name="clg" value="junior" required> Junior College
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="class">Class</label>
                                <select id="class" name="class" required>
                                    <option value="">Select Class</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="stream">Stream</label>
                                <select id="stream" name="stream" required>
                                    <option value="">Select Stream</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="roll">Roll Number</label>
                                <input type="text" id="roll" name="roll" required pattern="[0-9]{1,5}" title="Please enter a valid Roll Number (1 to 5 digits only).">
                            </div>

                            <!-- Appointment Details -->
                            <div class="form-group">
                                <label>Referred By</label>
                                <div class="radio-group">
                                    <label><input type="radio" name="referredBy" value="self" required> Self</label>
                                    <label><input type="radio" name="referredBy" value="parent" required> Parent</label>
                                    <label><input type="radio" name="referredBy" value="teacher" required> Teacher</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>First Time Visit?</label>
                                <div class="radio-group">
                                    <label><input type="radio" name="time" value="yes" required> Yes</label>
                                    <label><input type="radio" name="time" value="no" required> No</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="appointment_date">Appointment Date</label>
                                <input type="date" id="appointment_date" name="appointment_date" required min="<?php echo date('Y-m-d'); ?>">
                            </div>

                            <div class="form-group">
                                <label for="appointment_time">Appointment Time</label>
                                <input type="time" id="appointment_time" name="appointment_time" required>
                            </div>
                        </div>

                        <button type="submit" class="button submit-btn">
                            <i class="fas fa-calendar-check"></i> Book Appointment
                        </button>
                    </form>
                </div>
            </section>
        </main>
    </div>

    <script>
        // Dynamic Class and Stream Selection
        document.addEventListener("DOMContentLoaded", function() {
            const clgRadio = document.querySelector('input[name="clg"]:checked');
            if (clgRadio) {
                updateClassAndStreamOptions(clgRadio.value);
            }
        });

        function updateClassAndStreamOptions(clgType) {
            const classSelect = document.getElementById('class');
            const streamSelect = document.getElementById('stream');
            classSelect.innerHTML = '<option value="">Select Class</option>';
            streamSelect.innerHTML = '<option value="">Select Stream</option>';

            if (clgType === 'degree') {
                classSelect.innerHTML += ` <option value="fy">FY</option> <option value="sy">SY</option> <option value="ty">TY</option> `;
                streamSelect.innerHTML += ` <option value="self-finance">Self Finance</option> <option value="bcom">B.Com</option> <option value="ba">B.A</option> `;
            } else if (clgType === 'junior') {
                classSelect.innerHTML += ` <option value="fy">FY</option> <option value="sy">SY</option> `;
                streamSelect.innerHTML += ` <option value="science">Science</option> <option value="commerce">Commerce</option> <option value="arts">Arts</option> `;
            }
        }

        document.querySelectorAll('input[name="clg"]').forEach(radio => {
            radio.addEventListener('change', function() {
                updateClassAndStreamOptions(this.value);
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
