<?php
session_start();
require_once 'db_connect.php';
$msg = $_GET['msg'] ?? '';
?>

<?php
// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Handle AJAX login POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email address.";
        exit;
    }
    if (strlen($password) < 6) {
        echo "Password must be at least 6 characters.";
        exit;
    }

    $stmt = $conn->prepare("SELECT id, name, email, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $name, $email_db, $hashed_password);
        $stmt->fetch();
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['user_name'] = $name;
            $_SESSION['user_email'] = $email_db;
            echo "success";
        } else {
            echo "Incorrect password.";
        }
    } else {
        echo "No account found with that email.";
    }
    $stmt->close();
    $conn->close();
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>MediLink - Login</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    .form-container { max-width: 400px; margin: 3rem auto; background: #fff; border-radius: 16px; box-shadow: 0 8px 32px rgba(44,62,80,0.13); padding: 2rem; }
    .form-container h2 { color: #2e7d32; text-align: center; margin-bottom: 1.5rem; }
    .form-container input { width: 100%; padding: 0.8rem; margin-bottom: 1rem; border-radius: 8px; border: 1px solid #bdbdbd; }
    .form-container button { width: 100%; padding: 0.8rem; background: #43a047; color: #fff; border: none; border-radius: 8px; font-weight: 600; }
    .form-container .error-message { color: #e53935; text-align: center; margin-bottom: 1rem; display: none; }
  </style>
</head>
<body>
  <?php include 'header.php'; ?>
  <div class="form-container">
    <h2>Login to MediLink</h2>
    <?php if ($msg) echo "<p style='color:orange;'>$msg</p>"; ?>
    <form id="loginForm" method="post" autocomplete="off">
      <div class="error-message" id="loginError"></div>
      <input type="email" name="email" id="loginEmail" placeholder="Email" required>
      <input type="password" name="password" id="loginPassword" placeholder="Password" required>
      <button type="submit" id="loginBtn">Login</button>
    </form>
  </div>
  <script>
    document.getElementById('loginForm').onsubmit = function(e) {
      e.preventDefault();
      const email = document.getElementById('loginEmail').value.trim();
      const password = document.getElementById('loginPassword').value;
      const errorDiv = document.getElementById('loginError');
      const btn = document.getElementById('loginBtn');
      errorDiv.style.display = "none";
      btn.disabled = true;
      btn.textContent = "Logging in...";
      fetch('login.php', {
        method: 'POST',
        body: new FormData(this)
      })
      .then(res => res.text())
      .then(msg => {
        btn.disabled = false;
        btn.textContent = "Login";
        if (msg.trim() === "success") {
          // Send guest cart to server
          let guestCart = localStorage.getItem('cart');
          if (guestCart) {
            fetch('cart_merge.php', {
              method: 'POST',
              headers: {'Content-Type': 'application/json'},
              body: guestCart
            }).then(() => {
              localStorage.removeItem('cart');
              window.location.href = "index.php";
            });
          } else {
            window.location.href = "index.php";
          }
        } else {
          errorDiv.textContent = msg;
          errorDiv.style.display = "block";
        }
      })
      .catch(() => {
        btn.disabled = false;
        btn.textContent = "Login";
        errorDiv.textContent = "Server error. Try again later.";
        errorDiv.style.display = "block";
      });
    };
  </script>
</body>
</html>