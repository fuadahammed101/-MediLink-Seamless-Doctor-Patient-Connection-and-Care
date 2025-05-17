<?php
session_start();
require_once 'db_connect.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Get admin username
$admin_username = $_SESSION['admin_username'];

// Initialize variables
$appointments = [];
$orders = [];
$success_message = '';
$error_message = '';

// Process cancellation requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['cancel_appointment']) && isset($_POST['appointment_id'])) {
        $appointment_id = intval($_POST['appointment_id']);
        
        // Delete the appointment
        $stmt = $conn->prepare("DELETE FROM appointments WHERE id = ?");
        $stmt->bind_param("i", $appointment_id);
        
        if ($stmt->execute()) {
            $success_message = "Appointment #{$appointment_id} has been cancelled successfully.";
        } else {
            $error_message = "Error cancelling appointment: " . $conn->error;
        }
        
        $stmt->close();
    }
    
    if (isset($_POST['cancel_order']) && isset($_POST['order_id'])) {
        $order_id = intval($_POST['order_id']);
        
        // First, update order status to cancelled
        $stmt = $conn->prepare("UPDATE orders SET status = 'cancelled' WHERE id = ?");
        $stmt->bind_param("i", $order_id);
        
        if ($stmt->execute()) {
            $success_message = "Order #{$order_id} has been cancelled successfully.";
        } else {
            $error_message = "Error cancelling order: " . $conn->error;
        }
        
        $stmt->close();
    }
}

// Fetch all appointments
$appointments_query = "SELECT 
                        a.id, 
                        a.doctor_name, 
                        a.category, 
                        a.patient_name, 
                        a.patient_email, 
                        a.appointment_date, 
                        a.appointment_time, 
                        a.booked_at,
                        u.name as user_name
                      FROM 
                        appointments a
                      LEFT JOIN 
                        users u ON a.user_id = u.id
                      ORDER BY 
                        a.appointment_date DESC, 
                        a.appointment_time DESC";

$appointments_result = $conn->query($appointments_query);
if ($appointments_result) {
    while ($row = $appointments_result->fetch_assoc()) {
        $appointments[] = $row;
    }
    $appointments_result->free();
}

// Fetch all orders
$orders_query = "SELECT 
                  o.id, 
                  o.user_id, 
                  o.total_amount, 
                  o.order_date, 
                  o.status,
                  u.name as user_name,
                  u.email as user_email
                FROM 
                  orders o
                LEFT JOIN 
                  users u ON o.user_id = u.id
                ORDER BY 
                  o.order_date DESC";

$orders_result = $conn->query($orders_query);
if ($orders_result) {
    while ($row = $orders_result->fetch_assoc()) {
        // Also fetch order items
        $order_id = $row['id'];
        $items_query = "SELECT * FROM order_items WHERE order_id = $order_id";
        $items_result = $conn->query($items_query);
        
        $items = [];
        if ($items_result) {
            while ($item = $items_result->fetch_assoc()) {
                $items[] = $item;
            }
            $items_result->free();
        }
        
        $row['items'] = $items;
        $orders[] = $row;
    }
    $orders_result->free();
}

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediLink - Admin Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f9fafb;
            margin: 0;
            padding: 0;
        }
        
        .admin-header {
            background: #2e7d32;
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .admin-header h1 {
            margin: 0;
            font-size: 1.5rem;
        }
        
        .admin-header .admin-controls {
            display: flex;
            align-items: center;
        }
        
        .admin-header .admin-info {
            margin-right: 1.5rem;
            font-size: 0.95rem;
        }
        
        .logout-btn {
            padding: 0.5rem 1rem;
            background: #c62828;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 0.9rem;
            cursor: pointer;
            transition: background 0.3s;
        }
        
        .logout-btn:hover {
            background: #b71c1c;
        }
        
        .admin-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1.5rem;
        }
        
        .dashboard-tabs {
            display: flex;
            margin-bottom: 2rem;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .tab-btn {
            padding: 1rem 2rem;
            background: none;
            border: none;
            border-bottom: 3px solid transparent;
            font-size: 1.1rem;
            font-weight: 600;
            color: #757575;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .tab-btn.active {
            color: #2e7d32;
            border-bottom-color: #2e7d32;
        }
        
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
        }
        
        .dashboard-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .dashboard-card h2 {
            margin-top: 0;
            color: #2e7d32;
            font-size: 1.5rem;
            border-bottom: 1px solid #e0e0e0;
            padding-bottom: 0.8rem;
            margin-bottom: 1.5rem;
        }
        
        .item-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .item-table th, .item-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #eeeeee;
        }
        
        .item-table th {
            color: #616161;
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: uppercase;
        }
        
        .item-table tbody tr:hover {
            background-color: #f5f5f5;
        }
        
        .status-badge {
            display: inline-block;
            padding: 0.35rem 0.8rem;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .status-pending {
            background: #fff8e1;
            color: #ff8f00;
        }
        
        .status-processing {
            background: #e3f2fd;
            color: #1976d2;
        }
        
        .status-shipped {
            background: #e8f5e9;
            color: #2e7d32;
        }
        
        .status-delivered {
            background: #e8f5e9;
            color: #2e7d32;
        }
        
        .status-cancelled {
            background: #ffebee;
            color: #c62828;
        }
        
        .action-btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 4px;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .cancel-btn {
            background: #ffebee;
            color: #c62828;
        }
        
        .cancel-btn:hover {
            background: #c62828;
            color: white;
        }
        
        .view-btn {
            background: #e3f2fd;
            color: #1976d2;
        }
        
        .view-btn:hover {
            background: #1976d2;
            color: white;
        }
        
        .order-details {
            background: #f5f5f5;
            padding: 1rem;
            border-radius: 8px;
            margin-top: 0.5rem;
            display: none;
        }
        
        .order-items-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .order-items-list li {
            padding: 0.5rem 0;
            display: flex;
            justify-content: space-between;
            border-bottom: 1px dashed #e0e0e0;
        }
        
        .order-items-list li:last-child {
            border-bottom: none;
        }
        
        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }
        
        .alert-success {
            background: #e8f5e9;
            color: #2e7d32;
            border-left: 4px solid #2e7d32;
        }
        
        .alert-error {
            background: #ffebee;
            color: #c62828;
            border-left: 4px solid #c62828;
        }
        
        .no-data {
            text-align: center;
            padding: 2rem;
            color: #757575;
            font-style: italic;
        }
    </style>
</head>
<body>
    <header class="admin-header">
        <h1>MediLink Admin Dashboard</h1>
        <div class="admin-controls">
            <div class="admin-info">
                Welcome, <strong><?php echo htmlspecialchars($admin_username); ?></strong>
            </div>
            <a href="admin_logout.php" class="logout-btn">Logout</a>
        </div>
    </header>
    
    <div class="admin-container">
        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success">
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-error">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        
        <div class="dashboard-tabs">
            <button class="tab-btn active" data-tab="appointments">Appointments</button>
            <button class="tab-btn" data-tab="orders">Orders</button>
        </div>
        
        <div id="appointments" class="tab-content active">
            <div class="dashboard-card">
                <h2>Manage Appointments</h2>
                
                <?php if (empty($appointments)): ?>
                    <div class="no-data">No appointments found</div>
                <?php else: ?>
                    <table class="item-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Doctor</th>
                                <th>Patient</th>
                                <th>Date & Time</th>
                                <th>Booked On</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($appointments as $appointment): ?>
                                <tr>
                                    <td>#<?php echo $appointment['id']; ?></td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($appointment['doctor_name']); ?></strong>
                                        <div><?php echo htmlspecialchars($appointment['category']); ?></div>
                                    </td>
                                    <td>
                                        <div><?php echo htmlspecialchars($appointment['patient_name']); ?></div>
                                        <div><?php echo htmlspecialchars($appointment['patient_email']); ?></div>
                                        <?php if (!empty($appointment['user_name'])): ?>
                                            <small>User: <?php echo htmlspecialchars($appointment['user_name']); ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div><?php echo date('M d, Y', strtotime($appointment['appointment_date'])); ?></div>
                                        <div><?php echo date('h:i A', strtotime($appointment['appointment_time'])); ?></div>
                                    </td>
                                    <td><?php echo date('M d, Y h:i A', strtotime($appointment['booked_at'])); ?></td>
                                    <td>
                                        <form method="POST" onsubmit="return confirm('Are you sure you want to cancel this appointment?');">
                                            <input type="hidden" name="appointment_id" value="<?php echo $appointment['id']; ?>">
                                            <button type="submit" name="cancel_appointment" class="action-btn cancel-btn">Cancel</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
        
        <div id="orders" class="tab-content">
            <div class="dashboard-card">
                <h2>Manage Orders</h2>
                
                <?php if (empty($orders)): ?>
                    <div class="no-data">No orders found</div>
                <?php else: ?>
                    <table class="item-table">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td>#<?php echo $order['id']; ?></td>
                                    <td>
                                        <?php if (!empty($order['user_name'])): ?>
                                            <div><?php echo htmlspecialchars($order['user_name']); ?></div>
                                            <div><?php echo htmlspecialchars($order['user_email']); ?></div>
                                        <?php else: ?>
                                            <div>User ID: <?php echo $order['user_id']; ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td>BDT <?php echo number_format($order['total_amount'], 2); ?></td>
                                    <td><?php echo date('M d, Y h:i A', strtotime($order['order_date'])); ?></td>
                                    <td>
                                        <span class="status-badge status-<?php echo strtolower($order['status']); ?>">
                                            <?php echo ucfirst($order['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <button class="action-btn view-btn" onclick="toggleOrderDetails(<?php echo $order['id']; ?>)">View Items</button>
                                        
                                        <?php if ($order['status'] !== 'cancelled' && $order['status'] !== 'delivered'): ?>
                                            <form method="POST" style="margin-top: 0.5rem;" onsubmit="return confirm('Are you sure you want to cancel this order?');">
                                                <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                                <button type="submit" name="cancel_order" class="action-btn cancel-btn">Cancel Order</button>
                                            </form>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="6" class="order-details" id="order-details-<?php echo $order['id']; ?>">
                                        <h4>Order Items:</h4>
                                        <?php if (empty($order['items'])): ?>
                                            <p>No items found for this order.</p>
                                        <?php else: ?>
                                            <ul class="order-items-list">
                                                <?php foreach ($order['items'] as $item): ?>
                                                    <li>
                                                        <span>
                                                            <?php echo htmlspecialchars($item['product_name']); ?> 
                                                            x <?php echo $item['quantity']; ?>
                                                        </span>
                                                        <span>
                                                            BDT <?php echo number_format($item['price_per_item'] * $item['quantity'], 2); ?>
                                                        </span>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <script>
        // Tab Switching
        document.querySelectorAll('.tab-btn').forEach(button => {
            button.addEventListener('click', () => {
                // Remove active class from all tabs
                document.querySelectorAll('.tab-btn').forEach(btn => {
                    btn.classList.remove('active');
                });
                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.remove('active');
                });
                
                // Add active class to clicked tab
                button.classList.add('active');
                document.getElementById(button.getAttribute('data-tab')).classList.add('active');
            });
        });
        
        // Toggle Order Details
        function toggleOrderDetails(orderId) {
            const details = document.getElementById(`order-details-${orderId}`);
            if (details.style.display === 'table-cell') {
                details.style.display = 'none';
            } else {
                details.style.display = 'table-cell';
            }
        }
    </script>
</body>
</html> 