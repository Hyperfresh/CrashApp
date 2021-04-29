<?php
$servername = "localhost";
$username = "root";
$db = "test";

// Create connection
$conn = mysqli_connect($servername, $username, null, $db);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
echo '<script>';
echo 'console.log("Database connected!")';
echo '</script>';
?> 