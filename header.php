<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$current_page = basename($_SERVER['PHP_SELF']);
?>
<script>
    window.userIsLoggedIn = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;
</script>
<nav class="navbar">
    <div class="logo">MediLink</div>
    <ul class="nav-links">
        <li><a href="index.php">Home</a></li>
        <li><a href="dims.php">DIMS</a></li>
        <li><a href="doctors.php">Doctors</a></li>
        <?php if (isset($_SESSION['user_id'])): ?>
            <li><a href="profile.php">Profile</a></li>
            
        <?php else: ?>
            <?php if ($current_page !== 'login.php'): ?>
                <li><a href="login.php">Login</a></li>
            <?php endif; ?>
            <?php if ($current_page !== 'signup.php'): ?>
                <li><a href="signup.php">Sign Up</a></li>
            <?php endif; ?>
        <?php endif; ?>
        <li><a href="about.php">About Us</a></li>
        <li><a href="contact.php">Contact</a></li>
        <li><a href="blog.php">Blog</a></li>
        <li><a href="admin_login.php" style="color:rgb(255, 255, 255); font-weight: bold;">Admin</a></li>
        <li class="cart-icon">
            <a href="cart.php" id="cart-link">
                Cart <span id="cart-count" style="background:#43a047;color:#fff;padding:2px 8px;border-radius:12px;">0</span>
            </a>
        </li>
    </ul>
</nav>