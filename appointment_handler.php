<?php
session_start();

require_once 'db_connect.php';

if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

// Sanitize input function
function sanitize_input($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user']['id'];
    $name = sanitize_input($_POST['name'] ?? '');
    $email = sanitize_input($_POST['email'] ?? '');
    $category = sanitize_input($_POST['category'] ?? '');
    $date = sanitize_input($_POST['date'] ?? '');
    $time = sanitize_input($_POST['time'] ?? '');

    $error = '';

    if (empty($name) || empty($email) || empty($category) || empty($date) || empty($time)) {
        $error = "Please fill in all required fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        $error = "Invalid date format. Use YYYY-MM-DD.";
    } elseif (!preg_match('/^\d{2}:\d{2}(:\d{2})?$/', $time)) {
        $error = "Invalid time format. Use HH:MM or HH:MM:SS.";
    }

    if (empty($error)) {
        $stmt = $conn->prepare("INSERT INTO appointments (user_id, name, email, category, date, time) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssss", $user_id, $name, $email, $category, $date, $time);
        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
            header('Location: dashboard.php?appointment=success');
            exit;
        } else {
            $error = "Failed to book appointment. Please try again.";
        }
    }
} else {
    header('Location: index.php');
    exit;
}
?>
