<?php
// Ensure functions are available
if (!function_exists('isAdmin')) {
    require_once __DIR__ . '/../../includes/session.php';
}
if (!defined('BASE_URL')) {
    require_once __DIR__ . '/../../config/constants.php';
}
?>
<aside class="sidebar">
    <div class="sidebar-header">
        <h3><?php echo (function_exists('isAdmin') && isAdmin()) ? 'Admin Panel' : 'User Panel'; ?></h3>
    </div>
    <ul class="sidebar-menu">
        <?php if (function_exists('isAdmin') && isAdmin()): ?>
            <li><a href="<?php echo BASE_URL; ?>/pages/admin/dashboard.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : ''; ?>">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a></li>
            <li><a href="<?php echo BASE_URL; ?>/pages/admin/buses/index.php" class="<?php echo strpos($_SERVER['PHP_SELF'], '/buses/') !== false ? 'active' : ''; ?>">
                <i class="fas fa-bus"></i> Manage Buses
            </a></li>
            <li><a href="<?php echo BASE_URL; ?>/pages/admin/reservations/index.php" class="<?php echo strpos($_SERVER['PHP_SELF'], '/reservations/') !== false ? 'active' : ''; ?>">
                <i class="fas fa-ticket-alt"></i> Reservations
            </a></li>
            <li><a href="<?php echo BASE_URL; ?>/pages/admin/users/index.php" class="<?php echo strpos($_SERVER['PHP_SELF'], '/users/') !== false ? 'active' : ''; ?>">
                <i class="fas fa-users"></i> Users
            </a></li>
        <?php else: ?>
            <li><a href="<?php echo BASE_URL; ?>/pages/user/dashboard.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : ''; ?>">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a></li>
            <li><a href="<?php echo BASE_URL; ?>/pages/public/viewbus.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'viewbus.php' ? 'active' : ''; ?>">
                <i class="fas fa-search"></i> Search Buses
            </a></li>
            <li><a href="<?php echo BASE_URL; ?>/pages/user/reservations.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'reservations.php' ? 'active' : ''; ?>">
                <i class="fas fa-ticket-alt"></i> My Reservations
            </a></li>
            <li><a href="<?php echo BASE_URL; ?>/pages/user/profile.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'profile.php' ? 'active' : ''; ?>">
                <i class="fas fa-user"></i> Profile
            </a></li>
        <?php endif; ?>
    </ul>
</aside>
