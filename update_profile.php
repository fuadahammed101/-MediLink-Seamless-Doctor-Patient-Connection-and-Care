<?php
session_start();
include 'db_connect.php';

header('Content-Type: text/plain');

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    echo "Error: User not logged in."; // Or redirect
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $bio = trim($_POST['bio']); // Get bio

    // Update user's bio
    $sql = "UPDATE users SET bio = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    // 'si' means string (bio) and integer (user_id)
    $stmt->bind_param("si", $bio, $user_id);

    if ($stmt->execute()) {
        echo "success";
    } else {
        error_log("Error updating bio for user ID " . $user_id . ": " . $stmt->error);
        echo "Error updating bio. Please try again.";
    }

    $stmt->close();
    $conn->close();

} else {
    echo "Invalid request method.";
}
?>