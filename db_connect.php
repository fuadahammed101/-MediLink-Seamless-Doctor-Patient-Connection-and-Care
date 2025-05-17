<?php
// Database credentials
$servername = "localhost"; // Usually "localhost" for XAMPP
$username = "root";     // Default XAMPP username
$password = "";         // Default XAMPP password (usually empty)
$dbname = "medilink_db"; // The database name you created

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    // Log error instead of displaying it directly in production
    error_log("Database connection failed: " . $conn->connect_error);
    die("Database connection failed. Please try again later.");
}
// Optional: Set charset to UTF-8
$conn->set_charset("utf8mb4");
?>