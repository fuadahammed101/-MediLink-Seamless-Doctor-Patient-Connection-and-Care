<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'db_connect.php';

// Initialize error messages
$login_error = '';
$register_error = '';

// Sanitize input function
function sanitize_input($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Debug function to log errors
function debug_log($message) {
    error_log($message);
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = sanitize_input($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $login_error = 'Invalid email format.';
        debug_log("Login error: Invalid email format for email: $email");
        header("Location: login.html?login_error=" . urlencode($login_error));
        exit;
    } else {
        $stmt = $conn->prepare("SELECT id, name, email, password FROM users WHERE email = ?");
        if (!$stmt) {
            debug_log("Prepare failed: " . $conn->error);
            die("Database error.");
        }
        $stmt->bind_param("s", $email);
        if (!$stmt->execute()) {
            debug_log("Execute failed: " . $stmt->error);
            die("Database error.");
        }
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                // Password is correct, set session
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email']
                ];
                header('Location: dashboard.php');
                exit;
            } else {
                $login_error = 'Invalid email or password.';
                debug_log("Login error: Password verification failed for email: $email");
                header("Location: login.html?login_error=" . urlencode($login_error));
                exit;
            }
        } else {
            $login_error = 'Invalid email or password.';
            debug_log("Login error: No user found with email: $email");
            header("Location: login.html?login_error=" . urlencode($login_error));
            exit;
        }
        $stmt->close();
    }
}

// Handle registration form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $name = sanitize_input($_POST['name'] ?? '');
    $email = sanitize_input($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $register_error = 'Invalid email format.';
        debug_log("Register error: Invalid email format for email: $email");
    } elseif ($password !== $confirm_password) {
        $register_error = 'Passwords do not match.';
        debug_log("Register error: Passwords do not match for email: $email");
    } elseif (strlen($password) < 6) {
        $register_error = 'Password must be at least 6 characters.';
        debug_log("Register error: Password too short for email: $email");
    } else {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        if (!$stmt) {
            debug_log("Prepare failed: " . $conn->error);
            die("Database error.");
        }
        $stmt->bind_param("s", $email);
        if (!$stmt->execute()) {
            debug_log("Execute failed: " . $stmt->error);
            die("Database error.");
        }
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $register_error = 'Email is already registered.';
            debug_log("Register error: Email already registered: $email");
        } else {
            $stmt->close();
            // Insert new user
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            if (!$stmt) {
                debug_log("Prepare failed: " . $conn->error);
                die("Database error.");
            }
            $stmt->bind_param("sss", $name, $email, $hashed_password);
            if ($stmt->execute()) {
                // Registration successful, redirect to login
                header('Location: index.php');
                exit;
            } else {
                $register_error = 'Registration failed. Please try again.';
                debug_log("Register error: Execute failed: " . $stmt->error);
            }
        }
        $stmt->close();
    }
}

$conn->close();
?>
