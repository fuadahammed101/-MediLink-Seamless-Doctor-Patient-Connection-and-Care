<?php
session_start();
require_once 'db_connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['count' => 0]);
    exit;
}

$user_id = intval($_SESSION['user_id']);
$res = $conn->query("SELECT SUM(quantity) as total FROM cart_items WHERE user_id=$user_id");
$row = $res->fetch_assoc();
$count = $row && $row['total'] ? intval($row['total']) : 0;
echo json_encode(['count' => $count]);
?>