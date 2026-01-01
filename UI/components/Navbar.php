<?php
// Ensure BASE_URL is defined
if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost/BusReservation');
}

// Ensure session functions are available
if (!function_exists('isLoggedIn')) {
    if (file_exists(__DIR__ . '/../../includes/session.php')) {
        require_once __DIR__ . '/../../includes/session.php';
    }
}
?>
<nav class="navbar">
    <div class="container">
        <a href="<?php echo BASE_URL; ?>/pages/public/home.php" class="logo">
            <i class="fas fa-bus"></i> <?php echo SITE_NAME; ?>
        </a>
        <ul class="nav-menu" id="nav-menu">
            <li><a href="<?php echo BASE_URL; ?>/pages/public/home.php">Home</a></li>
            <li><a href="<?php echo BASE_URL; ?>/pages/public/viewbus.php">View Buses</a></li>
            <li><a href="<?php echo BASE_URL; ?>/pages/public/about.php">About</a></li>
            <li><a href="<?php echo BASE_URL; ?>/pages/public/contact.php">Contact</a></li>
            
            <?php if (function_exists('isLoggedIn') && isLoggedIn()): ?>
                <li class="dropdown">
                    <a href="javascript:void(0)" class="dropbtn">
                        <i class="fas fa-user-circle"></i> <?php echo $_SESSION['user_name'] ?? 'User'; ?>
                    </a>
                    <div class="dropdown-content">
                        <?php if (function_exists('isAdmin') && isAdmin()): ?>
                            <a href="<?php echo BASE_URL; ?>/pages/admin/dashboard.php">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                            <a href="<?php echo BASE_URL; ?>/pages/admin/buses/index.php">
                                <i class="fas fa-bus"></i> Manage Buses
                            </a>
                            <a href="<?php echo BASE_URL; ?>/pages/admin/reservations/index.php">
                                <i class="fas fa-ticket-alt"></i> Reservations
                            </a>
                            <a href="<?php echo BASE_URL; ?>/pages/admin/users/index.php">
                                <i class="fas fa-users"></i> Users
                            </a>
                        <?php else: ?>
                            <a href="<?php echo BASE_URL; ?>/pages/user/dashboard.php">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                            <a href="<?php echo BASE_URL; ?>/pages/user/makereservation.php">
                                <i class="fas fa-plus-circle"></i> Book Ticket
                            </a>
                            <a href="<?php echo BASE_URL; ?>/pages/user/reservations.php">
                                <i class="fas fa-ticket-alt"></i> My Reservations
                            </a>
                            <a href="<?php echo BASE_URL; ?>/pages/user/profile.php">
                                <i class="fas fa-user"></i> Profile
                            </a>
                        <?php endif; ?>
                        <a href="<?php echo BASE_URL; ?>/controllers/AuthController.php?action=logout" style="color: #ef4444;">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </div>
                </li>
            <?php else: ?>
                <li><a href="<?php echo BASE_URL; ?>/pages/public/login.php" class="btn-login">Login</a></li>
                <li><a href="<?php echo BASE_URL; ?>/pages/public/register.php" class="btn-register">Register</a></li>
            <?php endif; ?>
        </ul>
        <button class="mobile-toggle" id="mobile-toggle">
            <i class="fas fa-bars"></i>
        </button>
    </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const mobileToggle = document.getElementById('mobile-toggle');
    const navMenu = document.getElementById('nav-menu');
    
    if (mobileToggle && navMenu) {
        mobileToggle.addEventListener('click', function() {
            navMenu.classList.toggle('active');
            const icon = this.querySelector('i');
            if (icon) {
                icon.classList.toggle('fa-bars');
                icon.classList.toggle('fa-times');
            }
        });
        
        // Close menu when clicking outside
        document.addEventListener('click', function(e) {
            if (!mobileToggle.contains(e.target) && !navMenu.contains(e.target)) {
                navMenu.classList.remove('active');
                const icon = mobileToggle.querySelector('i');
                if (icon) {
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                }
            }
        });
    }
    
    // Dropdown for desktop
    const dropdown = document.querySelector('.dropdown');
    if (dropdown) {
        dropdown.addEventListener('click', function(e) {
            if (window.innerWidth <= 768) {
                e.preventDefault();
                this.classList.toggle('active');
            }
        });
    }
});
</script>