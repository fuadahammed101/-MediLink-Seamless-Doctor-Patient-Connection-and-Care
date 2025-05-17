<?php
session_start();
if (!isset($_SESSION['user_id'])) { echo "Not logged in."; exit; }
include 'db_connect.php';

$user_id = $_SESSION['user_id'];
$old = $_POST['old_password'];
$new = $_POST['new_password'];

$stmt = $conn->prepare("SELECT password FROM users WHERE id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($hash);
$stmt->fetch();
$stmt->close();

if (!password_verify($old, $hash)) {
    echo "Old password incorrect."; exit;
}
if (strlen($new) < 6) {
    echo "New password too short."; exit;
}
$new_hash = password_hash($new, PASSWORD_DEFAULT);
$stmt = $conn->prepare("UPDATE users SET password=? WHERE id=?");
$stmt->bind_param("si", $new_hash, $user_id);
$stmt->execute();
$stmt->close();
$conn->close();
echo "success";
?>