<?php
session_start();
require_once 'db_connect.php';

// Check if admin is already logged in
if (isset($_SESSION['admin_id'])) {
    header("Location: admin_dashboard.php");
    exit();
}

$error = '';

// Process login form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    // Admin credentials (hardcoded for now, you might want to store in database later)
    $admin_username = "admin";
    $admin_password = "admin123"; // In production, use password_hash and password_verify
    
    if ($username === $admin_username && $password === $admin_password) {
        // Set admin session
        $_SESSION['admin_id'] = 1;
        $_SESSION['admin_username'] = $username;
        
        // Redirect to dashboard
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $error = "Invalid username or password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediLink - Admin Login</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .admin-login-container {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 2.5rem;
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .admin-login-container h1 {
            color: #2e7d32;
            margin-bottom: 2rem;
            font-size: 1.8rem;
        }
        .admin-logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 1.5rem auto;
            background: #e8f5e9;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #2e7d32;
            font-size: 2rem;
            font-weight: bold;
        }
        .form-group {
            margin-bottom: 1.5rem;
            text-align: left;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #555;
            font-weight: 600;
        }
        .form-group input {
            width: 100%;
            padding: 0.8rem 1rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: border 0.3s;
        }
        .form-group input:focus {
            border-color: #4caf50;
            outline: none;
        }
        .admin-login-btn {
            width: 100%;
            padding: 0.8rem;
            background: #4caf50;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }
        .admin-login-btn:hover {
            background: #388e3c;
        }
        .error-message {
            background: #ffebee;
            color: #d32f2f;
            padding: 0.8rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            text-align: center;
            display: <?php echo !empty($error) ? 'block' : 'none'; ?>;
        }
        .back-link {
            display: inline-block;
            margin-top: 1.5rem;
            color: #555;
            text-decoration: none;
            font-size: 0.9rem;
        }
        .back-link:hover {
            color: #2e7d32;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="admin-login-container">
        <div class="admin-logo">A</div>
        <h1>Admin Login</h1>
        
        <div class="error-message" id="errorMsg">
            <?php echo $error; ?>
        </div>

        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit" class="admin-login-btn">Login</button>
        </form>
        
        <a href="index.php" class="back-link">← Back to MediLink</a>
    </div>

    <script>
        // Hide error message when user starts typing
        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('input', () => {
                document.getElementById('errorMsg').style.display = 'none';
            });
        });
    </script>
</body>
</html> 