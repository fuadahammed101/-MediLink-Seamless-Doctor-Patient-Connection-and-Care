<?php
session_start();
require_once 'db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?msg=" . urlencode("Please log in to complete your purchase"));
    exit();
}

// Check if we have the amount from URL
$amount = isset($_GET['amount']) ? floatval($_GET['amount']) : 0;
if ($amount <= 0) {
    header("Location: cart.php");
    exit();
}

// Get selected cart items from session if available
if (!isset($_SESSION['selected_items']) && !isset($_GET['selected_items'])) {
    // If no items are passed, fetch all cart items for this user
    $user_id = $_SESSION['user_id'];
    $cart_query = $conn->query("SELECT name, price, quantity FROM cart_items WHERE user_id=$user_id");
    $selected_items = [];
    
    while ($item = $cart_query->fetch_assoc()) {
        $selected_items[] = [
            'name' => $item['name'],
            'price' => floatval($item['price']),
            'quantity' => intval($item['quantity'])
        ];
    }
} else {
    // Use passed selected items (from URL or session)
    $selected_items = isset($_SESSION['selected_items']) ? 
        $_SESSION['selected_items'] : 
        json_decode(urldecode($_GET['selected_items']), true);
}

// If no items, redirect to cart
if (empty($selected_items)) {
    header("Location: cart.php");
    exit();
}

// Store selected items in session for use after payment
$_SESSION['selected_items'] = $selected_items;
$_SESSION['order_amount'] = $amount;

// Process the payment form submission
$order_success = false;
$order_error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Start a transaction
        $conn->begin_transaction();
        
        // 1. Create the order
        $user_id = $_SESSION['user_id'];
        $total_amount = $_SESSION['order_amount'];
        
        $order_sql = "INSERT INTO orders (user_id, total_amount, status) VALUES (?, ?, 'processing')";
        $order_stmt = $conn->prepare($order_sql);
        $order_stmt->bind_param("id", $user_id, $total_amount);
        
        if (!$order_stmt->execute()) {
            throw new Exception("Error creating order: " . $order_stmt->error);
        }
        
        $order_id = $conn->insert_id;
        $order_stmt->close();
        
        // 2. Store order items
        $item_sql = "INSERT INTO order_items (order_id, product_name, quantity, price_per_item) VALUES (?, ?, ?, ?)";
        $item_stmt = $conn->prepare($item_sql);
        
        foreach ($_SESSION['selected_items'] as $item) {
            $product_name = $item['name'];
            $quantity = $item['quantity'];
            $price = $item['price'];
            
            $item_stmt->bind_param("isid", $order_id, $product_name, $quantity, $price);
            
            if (!$item_stmt->execute()) {
                throw new Exception("Error adding order item: " . $item_stmt->error);
            }
        }
        
        $item_stmt->close();
        
        // 3. Clear cart items (that were purchased)
        $names = [];
        foreach ($_SESSION['selected_items'] as $item) {
            $names[] = "'" . $conn->real_escape_string($item['name']) . "'";
        }
        
        if (!empty($names)) {
            $name_list = implode(',', $names);
            $conn->query("DELETE FROM cart_items WHERE user_id=$user_id AND name IN ($name_list)");
        }
        
        // Commit the transaction
        $conn->commit();
        $order_success = true;
        
        // Clear the selected items from session
        unset($_SESSION['selected_items']);
        unset($_SESSION['order_amount']);
        
    } catch (Exception $e) {
        // Rollback on error
        $conn->rollback();
        $order_error = $e->getMessage();
        error_log("Order error: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title> Payment - MediLink</title>
  <style>
    body {
      background: linear-gradient(120deg, #e0f7fa 0%, #a5d6a7 100%);
      min-height: 100vh;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .payment-container {
      background: #fff;
      padding: 2.5rem 2rem 2rem 2rem;
      border-radius: 18px;
      box-shadow: 0 8px 32px rgba(44, 62, 80, 0.15);
      max-width: 400px;
      width: 100%;
      margin: 2rem;
      text-align: center;
    }
    .payment-container h2 {
      color: #2e7d32;
      margin-bottom: 1.5rem;
      font-size: 2rem;
      font-weight: 700;
    }
    .payment-container label {
      display: block;
      margin-bottom: 0.5rem;
      color: #388e3c;
      font-weight: 600;
      text-align: left;
    }
    .payment-container input[type="text"], .payment-container input[type="number"] {
      width: 100%;
      padding: 0.75rem 1rem;
      margin-bottom: 1rem;
      border: 1px solid #ccc;
      border-radius: 4px;
      font-size: 1rem;
      box-sizing: border-box;
    }
    .payment-container input[readonly] {
      background: #f1f8e9;
      color: #757575;
      cursor: not-allowed;
    }
    .payment-container button {
      width: 100%;
      padding: 0.75rem 1rem;
      background: #2e7d32;
      color: white;
      border: none;
      border-radius: 4px;
      font-size: 1.1rem;
      cursor: pointer;
      transition: background-color 0.3s ease;
      margin-top: 1rem;
    }
    .payment-container button:hover {
      background: #27632a;
    }
    .success-message {
      color: #2e7d32;
      background: #e8f5e9;
      border-radius: 6px;
      padding: 1rem;
      margin-top: 1.5rem;
      font-size: 1.1rem;
    }
    .error-message {
      color: #c62828;
      background: #ffebee;
      border-radius: 6px;
      padding: 1rem;
      margin-top: 1.5rem;
      font-size: 1.1rem;
    }
  </style>
</head>
<body>
  <div class="payment-container">
    <?php if ($order_success): ?>
      <h2>Payment Successful!</h2>
      <div class="success-message">
        ✅ Thank you for your purchase!<br>
        Your order has been processed successfully.<br><br>
        <a href="profile.php" style="color:#2e7d32;font-weight:bold;text-decoration:underline;">View Your Orders</a><br>
        <a href="index.php" style="color:#2e7d32;text-decoration:underline;">Continue Shopping</a>
      </div>
    <?php elseif (!empty($order_error)): ?>
      <h2>Payment Error</h2>
      <div class="error-message">
        ❌ Sorry, there was an error processing your payment.<br>
        <?php echo htmlspecialchars($order_error); ?><br><br>
        <a href="cart.php" style="color:#c62828;text-decoration:underline;">Return to Cart</a>
      </div>
    <?php else: ?>
      <h2>Payment Details</h2>
      <form id="paymentForm" method="post" autocomplete="off">
        <label for="cardNumber">Card Number</label>
        <input type="text" id="cardNumber" name="cardNumber" maxlength="19" required placeholder="1234 5678 9012 3456">

        <label for="cardName">Cardholder Name</label>
        <input type="text" id="cardName" name="cardName" required placeholder="Your Name">

        <label for="expDate">Expiration Date</label>
        <input type="text" id="expDate" name="expDate" required placeholder="MM/YY">

        <label for="cvv">CVV</label>
        <input type="text" id="cvv" name="cvv" maxlength="3" required placeholder="123">

        <label for="amount">Amount (BDT)</label>
        <input type="number" id="amount" name="amount" value="<?php echo htmlspecialchars($amount); ?>" readonly>

        <button type="submit">Pay Now</button>
      </form>
    <?php endif; ?>
  </div>
  <script>
    // Format card number to add spaces
    const cardInput = document.getElementById('cardNumber');
    if (cardInput) {
      cardInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
        let formattedValue = '';
        for (let i = 0; i < value.length; i++) {
          if (i > 0 && i % 4 === 0) {
            formattedValue += ' ';
          }
          formattedValue += value[i];
        }
        e.target.value = formattedValue;
      });
    }
  </script>
</body>
</html>