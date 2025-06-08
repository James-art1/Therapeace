<?php
$servername = "localhost"; // or your database server
$username = "root"; // your database username
$password = ""; // your database password (default is empty for XAMPP)
$dbname = "login1"; // replace with your actual database name

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
