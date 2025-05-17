<?php
session_start();
require_once 'db_connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit;
}

$user_id = intval($_SESSION['user_id']);
$res = $conn->query("SELECT id, name, price, img, quantity, created_at FROM cart_items WHERE user_id=$user_id");
$cart = [];
while ($row = $res->fetch_assoc()) {
    $cart[] = [
        'name' => $row['name'],
        'price' => floatval($row['price']),
        'img' => $row['img'],
        'quantity' => intval($row['quantity']),
        'time' => strtotime($row['created_at']) * 1000 // Convert to milliseconds for JavaScript
    ];
}
echo json_encode($cart);
?>