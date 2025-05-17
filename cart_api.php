<?php
session_start();
require_once 'db_connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit;
}

$user_id = intval($_SESSION['user_id']);
$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['action'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
    exit;
}

$action = $data['action'];

if ($action === 'add' && isset($data['item'])) {
    try {
        $item = $data['item'];
        
        if (!isset($item['name']) || !isset($item['price']) || !isset($item['quantity'])) {
            echo json_encode(['success' => false, 'error' => 'Missing required item fields']);
            exit;
        }
        
        $name = $conn->real_escape_string($item['name']);
        $price = floatval($item['price']);
        $img = isset($item['img']) ? $conn->real_escape_string($item['img']) : '';
        $quantity = intval($item['quantity']);

        // Check if item exists
        $res = $conn->query("SELECT id, quantity FROM cart_items WHERE user_id=$user_id AND name='$name'");
        if ($row = $res->fetch_assoc()) {
            // Update quantity
            $newQty = $row['quantity'] + $quantity;
            $conn->query("UPDATE cart_items SET quantity=$newQty, price=$price, img='$img' WHERE id={$row['id']}");
        } else {
            // Insert new
            $conn->query("INSERT INTO cart_items (user_id, name, price, img, quantity) VALUES ($user_id, '$name', $price, '$img', $quantity)");
        }
        
        if ($conn->error) {
            echo json_encode(['success' => false, 'error' => 'Database error: ' . $conn->error]);
        } else {
            echo json_encode(['success' => true]);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => 'Exception: ' . $e->getMessage()]);
    }
    exit;
}

if ($action === 'delete' && isset($data['name'])) {
    try {
        $name = $conn->real_escape_string($data['name']);
        $result = $conn->query("DELETE FROM cart_items WHERE user_id=$user_id AND name='$name'");
        
        if ($conn->error) {
            echo json_encode(['success' => false, 'error' => 'Database error: ' . $conn->error]);
        } else if ($conn->affected_rows > 0) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Item not found']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => 'Exception: ' . $e->getMessage()]);
    }
    exit;
}

if ($action === 'update' && isset($data['name'], $data['quantity'])) {
    try {
        $name = $conn->real_escape_string($data['name']);
        $quantity = intval($data['quantity']);
        
        if ($quantity <= 0) {
            echo json_encode(['success' => false, 'error' => 'Quantity must be greater than 0']);
            exit;
        }
        
        $result = $conn->query("UPDATE cart_items SET quantity=$quantity WHERE user_id=$user_id AND name='$name'");
        
        if ($conn->error) {
            echo json_encode(['success' => false, 'error' => 'Database error: ' . $conn->error]);
        } else if ($conn->affected_rows > 0) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Item not found or no changes made']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => 'Exception: ' . $e->getMessage()]);
    }
    exit;
}

echo json_encode(['success' => false, 'error' => 'Unknown action']);
?>