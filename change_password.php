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
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];

    // Basic validation for new password
    if (strlen($new_password) < 6) {
        echo "New password must be at least 6 characters long.";
        $conn->close();
        exit();
    }

    // Fetch current hashed password from database
    $sql_fetch_pwd = "SELECT password FROM users WHERE id = ?";
    $stmt_fetch_pwd = $conn->prepare($sql_fetch_pwd);
    $stmt_fetch_pwd->bind_param("i", $user_id);
    $stmt_fetch_pwd->execute();
    $result_fetch_pwd = $stmt_fetch_pwd->get_result();

    if ($result_fetch_pwd->num_rows == 1) {
        $row = $result_fetch_pwd->fetch_assoc();
        $current_hashed_password = $row['password'];

        // Verify old password
        if (password_verify($old_password, $current_hashed_password)) {
            // Old password is correct, hash the new password
            $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Update password in the database
            $sql_update_pwd = "UPDATE users SET password = ? WHERE id = ?";
            $stmt_update_pwd = $conn->prepare($sql_update_pwd);
            $stmt_update_pwd->bind_param("si", $new_hashed_password, $user_id);

            if ($stmt_update_pwd->execute()) {
                echo "success";
            } else {
                 error_log("Error updating password for user ID " . $user_id . ": " . $stmt_update_pwd->error);
                echo "Error changing password. Please try again.";
            }
            $stmt_update_pwd->close();

        } else {
            // Old password is incorrect
            echo "Incorrect current password.";
        }
    } else {
         error_log("User ID " . $user_id . " not found when changing password.");
        echo "Error: User not found."; // Should not happen if session is set
    }

    $stmt_fetch_pwd->close();
    $conn->close();

} else {
    echo "Invalid request method.";
}
?>