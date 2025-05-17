<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db_connect.php';

$user_id = $_SESSION['user_id'];

// Fetch user details
$user = null;
$sql_user = "SELECT name, email, bio FROM users WHERE id = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
if ($result_user->num_rows > 0) {
    $user = $result_user->fetch_assoc();
}
$stmt_user->close();

// Fetch user's appointments
$appointments = [];
$sql_appointments = "SELECT doctor_name, category, appointment_date, appointment_time, booked_at FROM appointments WHERE user_id = ? ORDER BY appointment_date DESC, appointment_time DESC";
$stmt_appointments = $conn->prepare($sql_appointments);
$stmt_appointments->bind_param("i", $user_id);
$stmt_appointments->execute();
$result_appointments = $stmt_appointments->get_result();
while ($row = $result_appointments->fetch_assoc()) {
    $appointments[] = $row;
}
$stmt_appointments->close();

// Fetch user's orders/purchases
$orders = [];
$sql_orders = "SELECT id, total_amount, order_date, status FROM orders WHERE user_id = ? ORDER BY order_date DESC";
$stmt_orders = $conn->prepare($sql_orders);
$stmt_orders->bind_param("i", $user_id);
$stmt_orders->execute();
$result_orders = $stmt_orders->get_result();
while ($row = $result_orders->fetch_assoc()) {
     // Fetch items for each order
     $order_items = [];
     $sql_items = "SELECT product_name, quantity, price_per_item FROM order_items WHERE order_id = ?";
     $stmt_items = $conn->prepare($sql_items);
     $stmt_items->bind_param("i", $row['id']);
     $stmt_items->execute();
     $result_items = $stmt_items->get_result();
     while($item_row = $result_items->fetch_assoc()) {
         $order_items[] = $item_row;
     }
     $stmt_items->close();
     $row['items'] = $order_items;
     $orders[] = $row;
}
$stmt_orders->close();

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MediLink - Profile</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    body {
        background: linear-gradient(120deg, #e8f5e9 0%, #b2dfdb 100%);
        min-height: 100vh;
    }
    main {
        flex: 1;
        padding: 2rem 1rem;
        display: flex;
        justify-content: center;
        align-items: flex-start;
    }
    .profile-container {
        max-width: 800px;
        width: 100%;
        background: #fff;
        padding: 2rem;
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(44,62,80,0.13);
    }
    .profile-container h2 {
        color: #2e7d32;
        text-align: center;
        margin-bottom: 1.5rem;
        font-size: 2rem;
        font-weight: 700;
    }
    .logout-link {
        display: block;
        width: 120px;
        margin: 2rem 0 0 2rem;
        text-align: center;
        background: #e53935;
        color: #fff;
        padding: 0.7rem 0;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        transition: background 0.2s;
        float: left;
    }
    .logout-link:hover {
        background: #b71c1c;
    }
    .profile-info, .profile-activities {
        margin-bottom: 2rem;
        padding: 1.5rem;
        background: #f1f8e9;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(67,160,71,0.06);
    }
     .profile-info h3, .profile-activities h3 {
        color: #388e3c;
        margin-top: 0;
        margin-bottom: 1rem;
        font-size: 1.3rem;
        border-bottom: 1px solid #c8e6c9;
        padding-bottom: 0.5rem;
    }
    .profile-info p {
        margin: 0.8em 0;
        font-size: 1.05rem;
        color: #333;
    }
    .profile-info p strong {
         color: #2e7d32;
    }
    .profile-info form {
        margin-top: 1.5rem;
        border-top: 1px solid #c8e6c9;
        padding-top: 1.5rem;
    }
     .profile-info form label {
        display: block;
        margin-bottom: 0.3rem;
        font-weight: 600;
        color: #388e3c;
    }
    .profile-info form input[type="text"],
    .profile-info form input[type="password"],
    .profile-info form textarea {
        width: 100%;
        padding: 0.6rem 0.8rem;
        margin-bottom: 1rem;
        border: 1.5px solid #bdbdbd;
        border-radius: 6px;
        font-size: 1rem;
        background: #fff;
        transition: border 0.2s, box-shadow 0.2s;
        box-sizing: border-box;
    }
     .profile-info form input:focus,
     .profile-info form textarea:focus {
        border-color: #43a047;
        outline: none;
        box-shadow: 0 2px 8px rgba(67,160,71,0.10);
    }
    .profile-info form textarea {
         min-height: 80px;
         resize: vertical;
    }
     .profile-info form button {
        padding: 0.6rem 1.2rem;
        background: #43a047;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 1em;
        cursor: pointer;
        transition: background 0.2s;
    }
    .profile-info form button:hover {
        background: #2e7d32;
    }
    .activity-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .activity-item {
        background: #fff;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
        box-shadow: 0 1px 6px rgba(0,0,0,0.05);
    }
    .activity-item strong {
        color: #2e7d32;
    }
    .activity-item p {
        margin: 0.5em 0;
        font-size: 0.95rem;
        color: #555;
    }
     .activity-item .order-items-list {
         margin-top: 0.8rem;
         padding-top: 0.8rem;
         border-top: 1px dashed #ccc;
         font-size: 0.9em;
     }
     .activity-item .order-items-list div {
         margin-bottom: 0.4em;
     }
     .activity-item .order-items-list span:first-child {
         color: #333;
     }
     .activity-item .order-items-list span:last-child {
         float: right;
         font-weight: bold;
         color: #388e3c;
     }
     .message-area {
         margin-top: 1rem;
         padding: 0.8rem;
         border-radius: 6px;
         font-weight: 500;
         text-align: center;
     }
     .message-success {
         background: #e8f5e9;
         color: #2e7d32;
     }
     .message-error {
         background: #ffebee;
         color: #e53935;
     }
  </style>
</head>
<body>
  <?php include 'header.php'; ?>

  <main>
    <section class="profile-container">
        <h2>Your Profile</h2>
      

        <?php if ($user): ?>
        <div class="profile-info">
            <h3>Basic Information</h3>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><strong>Bio:</strong> <?php echo htmlspecialchars($user['bio'] ?: 'Not provided'); ?></p>

            <h3>Update Profile</h3>
            <form id="updateProfileForm">
                <label for="bio">Bio:</label>
                <textarea id="bio" name="bio"><?php echo htmlspecialchars($user['bio']); ?></textarea>

                <button type="submit">Update Bio</button>
                 <div class="message-area" id="updateBioMsg" style="display:none;"></div>
            </form>

            <h3>Change Password</h3>
            <form id="changePasswordForm">
                <label for="oldPassword">Current Password:</label>
                <input type="password" id="oldPassword" name="old_password" required>

                <label for="newPassword">New Password:</label>
                <input type="password" id="newPassword" name="new_password" required minlength="6">

                <label for="confirmNewPassword">Confirm New Password:</label>
                <input type="password" id="confirmNewPassword" required>

                <button type="submit">Change Password</button>
                 <div class="message-area" id="changePasswordMsg" style="display:none;"></div>
            </form>
        </div>

        <div class="profile-activities">
            <h3>Your Appointments</h3>
            <ul class="activity-list">
                <?php if (!empty($appointments)): ?>
                    <?php foreach ($appointments as $appointment): ?>
                        <li class="activity-item">
                            <p><strong>Doctor:</strong> <?php echo htmlspecialchars($appointment['doctor_name']); ?></p>
                            <p><strong>Category:</strong> <?php echo htmlspecialchars($appointment['category']); ?></p>
                            <p><strong>Date:</strong> <?php echo htmlspecialchars($appointment['appointment_date']); ?></p>
                            <p><strong>Time:</strong> <?php echo htmlspecialchars($appointment['appointment_time']); ?></p>
                            <p><small>Booked on: <?php echo htmlspecialchars(date('Y-m-d H:i', strtotime($appointment['booked_at']))); ?></small></p>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li class="activity-item"><p>No appointments booked yet.</p></li>
                <?php endif; ?>
            </ul>

            <h3>Your Order History</h3>
             <ul class="activity-list">
                <?php if (!empty($orders)): ?>
                    <?php foreach ($orders as $order): ?>
                        <li class="activity-item">
                            <p><strong>Order ID:</strong> #<?php echo htmlspecialchars($order['id']); ?></p>
                            <p><strong>Total Amount:</strong> BDT <?php echo htmlspecialchars(number_format($order['total_amount'], 2)); ?></p>
                            <p><strong>Date:</strong> <?php echo htmlspecialchars(date('Y-m-d H:i', strtotime($order['order_date']))); ?></p>
                            <p><strong>Status:</strong> <?php echo htmlspecialchars($order['status']); ?></p>
                            <?php if (!empty($order['items'])): ?>
                            <div class="order-items-list">
                                <strong>Items:</strong>
                                <?php foreach ($order['items'] as $item): ?>
                                    <div>
                                        <span><?php echo htmlspecialchars($item['product_name']); ?> (x<?php echo htmlspecialchars($item['quantity']); ?>)</span>
                                        <span>BDT <?php echo htmlspecialchars(number_format($item['price_per_item'] * $item['quantity'], 2)); ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li class="activity-item"><p>No orders placed yet.</p></li>
                <?php endif; ?>
            </ul>
        </div>

        <?php else: ?>
             <p style="text-align: center; color: #e53935;">Could not retrieve user information.</p>
        <?php endif; ?>
        <a href="logout.php" class="logout-link" style="display:block;width: 120px;margin:2rem 0 0 2rem;text-align:center;background:#e53935;color:#fff;padding:0.7rem 0;border-radius:8px;text-decoration:none;font-weight:600;transition:background 0.2s;float:left;">Logout</a>

    </section>
  </main>

  <footer>
    <div style="text-align:center;margin-top:1rem;">
      © 2025 MediLink. All rights reserved.
    </div>
  </footer>

  <script>
    // JavaScript for handling profile update and password change forms via Fetch

    // Helper to show messages
    function showProfileMessage(elementId, message, isSuccess) {
        const msgElement = document.getElementById(elementId);
        msgElement.textContent = message;
        msgElement.className = 'message-area ' + (isSuccess ? 'message-success' : 'message-error');
        msgElement.style.display = 'block';
         setTimeout(() => { msgElement.style.display = 'none'; }, 5000); // Hide after 5 seconds
    }

    // Update Bio Form
    document.getElementById('updateProfileForm').onsubmit = function(e) {
        e.preventDefault();
        const bio = document.getElementById('bio').value;
        const msgElementId = 'updateBioMsg';

        const formData = new FormData();
        formData.append('bio', bio);

        fetch('update_profile.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.text())
        .then(msg => {
            msg = msg.trim();
            if (msg === 'success') {
                showProfileMessage(msgElementId, 'Bio updated successfully!', true);
                 // Update the displayed bio text without page reload
                document.querySelector('.profile-info p:nth-of-type(3)').innerHTML = '<strong>Bio:</strong> ' + (bio ? htmlspecialchars(bio) : 'Not provided');
            } else {
                showProfileMessage(msgElementId, msg || 'An error occurred.', false);
            }
        })
        .catch(err => {
            console.error('Fetch error:', err);
            showProfileMessage(msgElementId, 'Network error. Could not update bio.', false);
        });
    };

    // Change Password Form
    document.getElementById('changePasswordForm').onsubmit = function(e) {
        e.preventDefault();
        const oldPassword = document.getElementById('oldPassword').value;
        const newPassword = document.getElementById('newPassword').value;
        const confirmNewPassword = document.getElementById('confirmNewPassword').value;
         const msgElementId = 'changePasswordMsg';

        if (newPassword.length < 6) {
             showProfileMessage(msgElementId, 'New password must be at least 6 characters long.', false);
             return;
        }
        if (newPassword !== confirmNewPassword) {
             showProfileMessage(msgElementId, 'New passwords do not match.', false);
             return;
        }

        const formData = new FormData();
        formData.append('old_password', oldPassword);
        formData.append('new_password', newPassword);

         // Clear the form fields on successful attempt (security)
        document.getElementById('oldPassword').value = '';
        document.getElementById('newPassword').value = '';
        document.getElementById('confirmNewPassword').value = '';


        fetch('change_password.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.text())
        .then(msg => {
             msg = msg.trim();
            if (msg === 'success') {
                showProfileMessage(msgElementId, 'Password changed successfully!', true);
            } else {
                showProfileMessage(msgElementId, msg || 'An error occurred.', false);
            }
        })
        .catch(err => {
            console.error('Fetch error:', err);
            showProfileMessage(msgElementId, 'Network error. Could not change password.', false);
        });
    };

    // Simple function to escape HTML special characters (for displaying user input safely)
    function htmlspecialchars(str) {
       if (typeof str !== 'string') return str;
       return str.replace(/&/g, "&amp;")
                 .replace(/</g, "&lt;")
                 .replace(/>/g, "&gt;")
                 .replace(/"/g, "&quot;")
                 .replace(/'/g, "&#039;");
    }

  </script>
</body>
</html>