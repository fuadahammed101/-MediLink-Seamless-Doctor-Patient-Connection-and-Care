<?php
session_start(); // Start the session

include 'db_connect.php';

header('Content-Type: text/plain'); // Or application/json if you want to return more structured data

// Redirect if not logged in (or decide if guest checkout is allowed)
if (!isset($_SESSION['user_id'])) {
    echo "Error: User not logged in."; // Or handle guest scenario
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    // Expecting JSON data of selected items from frontend
    $selected_items_json = $_POST['selected_items'] ?? '[]';
    $selected_items = json_decode($selected_items_json, true);

    if (empty($selected_items)) {
        echo "Error: No items selected.";
        $conn->close();
        exit();
    }

    $total_amount = 0;
    // Calculate total and basic validation of received data
    foreach ($selected_items as $item) {
        if (!isset($item['name'], $item['quantity'], $item['price']) || $item['quantity'] <= 0 || $item['price'] < 0) {
            echo "Error: Invalid item data received.";
            $conn->close();
            exit();
        }
        $total_amount += $item['price'] * $item['quantity'];
    }

    // Start a transaction for atomicity
    $conn->begin_transaction();
    $order_created = false;

    try {
        // 1. Insert into orders table
        $sql_order = "INSERT INTO orders (user_id, total_amount) VALUES (?, ?)";
        $stmt_order = $conn->prepare($sql_order);
        // 'id' means integer (user_id), 'd' means double/decimal (total_amount)
        $stmt_order->bind_param("id", $user_id, $total_amount);

        if (!$stmt_order->execute()) {
            throw new Exception("Error creating order: " . $stmt_order->error);
        }
        $order_id = $conn->insert_id;
        $stmt_order->close();

        // 2. Insert into order_items table
        $sql_item = "INSERT INTO order_items (order_id, product_name, quantity, price_per_item) VALUES (?, ?, ?, ?)";
        $stmt_item = $conn->prepare($sql_item);

        foreach ($selected_items as $item) {
             // Ensure product_id is also sent from frontend if you want to link to products table
             // $product_id = $item['id'] ?? NULL;
             $product_name = $item['name'];
             $quantity = $item['quantity'];
             $price_per_item = $item['price'];

            // 'isid' means integer (order_id), string (name), integer (quantity), double (price)
            if (!$stmt_item->bind_param("isid", $order_id, $product_name, $quantity, $price_per_item)) {
                 throw new Exception("Binding error: " . $stmt_item->error);
            }
            if (!$stmt_item->execute()) {
                throw new Exception("Error adding order item '" . $product_name . "': " . $stmt_item->error);
            }
        }
        $stmt_item->close();

        // If everything is successful, commit the transaction
        $conn->commit();
        $order_created = true;
        echo "success|" . $total_amount; // Respond with success and total amount

    } catch (Exception $e) {
        // If any query failed, rollback the transaction
        $conn->rollback();
        error_log("Transaction failed: " . $e->getMessage());
        echo "Error processing order. Please try again.";
    }

    $conn->close();

} else {
    echo "Invalid request method.";
}
?>