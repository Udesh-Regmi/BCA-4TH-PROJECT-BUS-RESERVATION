<?php
if (!function_exists('isAdmin')) {
    require_once __DIR__ . '/../../includes/session.php';
}
if (!defined('BASE_URL')) {
    require_once __DIR__ . '/../../config/constants.php';
}
?>

<button class="sidebar-mobile-toggle" id="sidebar-mobile-toggle" type="button" aria-label="Toggle sidebar" aria-expanded="false" aria-controls="app-sidebar">
    <i class="fas fa-bars"></i>
</button>

<aside class="sidebar" id="app-sidebar" aria-label="Sidebar navigation">
    <div class="sidebar-header">
        <h3><?php echo (function_exists('isAdmin') && isAdmin()) ? 'Admin Panel' : 'User Panel'; ?></h3>
    </div>

    <ul class="sidebar-menu">
        <?php if (function_exists('isAdmin') && isAdmin()): ?>
            <li>
                <a href="<?php echo BASE_URL; ?>/pages/admin/dashboard.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : ''; ?>">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="<?php echo BASE_URL; ?>/pages/admin/buses/index.php" class="<?php echo strpos($_SERVER['PHP_SELF'], '/buses/') !== false ? 'active' : ''; ?>">
                    <i class="fas fa-bus"></i> Manage Buses
                </a>
            </li>
            <li>
                <a href="<?php echo BASE_URL; ?>/pages/admin/reservations/index.php" class="<?php echo strpos($_SERVER['PHP_SELF'], '/reservations/') !== false ? 'active' : ''; ?>">
                    <i class="fas fa-ticket-alt"></i> Reservations
                </a>
            </li>
            <li>
                <a href="<?php echo BASE_URL; ?>/pages/admin/users/index.php" class="<?php echo strpos($_SERVER['PHP_SELF'], '/users/') !== false ? 'active' : ''; ?>">
                    <i class="fas fa-users"></i> Users
                </a>
            </li>
            <li>
                <a href="<?php echo BASE_URL; ?>/pages/public/admin-forgot-password.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'admin-forgot-password.php' ? 'active' : ''; ?>">
                    <i class="fas fa-key"></i> Forgot Password 
                </a>
            </li>
        <?php else: ?>
            <li>
                <a href="<?php echo BASE_URL; ?>/pages/user/dashboard.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : ''; ?>">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="<?php echo BASE_URL; ?>/pages/public/viewbus.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'viewbus.php' ? 'active' : ''; ?>">
                    <i class="fas fa-search"></i> Search Buses
                </a>
            </li>
            <li>
                <a href="<?php echo BASE_URL; ?>/pages/user/reservations.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'reservations.php' ? 'active' : ''; ?>">
                    <i class="fas fa-ticket-alt"></i> My Reservations
                </a>
            </li>
            <li>
                <a href="<?php echo BASE_URL; ?>/pages/user/profile.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'profile.php' ? 'active' : ''; ?>">
                    <i class="fas fa-user"></i> Profile
                </a>
            </li>
        <?php endif; ?>
    </ul>
</aside>

<div class="sidebar-overlay" id="sidebar-overlay" aria-hidden="true"></div>

<script>
(function () {
    const layout = document.querySelector('.dashboard-layout');
    const toggle = document.getElementById('sidebar-mobile-toggle');
    const sidebar = document.getElementById('app-sidebar');
    const overlay = document.getElementById('sidebar-overlay');

    if (!layout || !toggle || !sidebar || !overlay) return;

    function openSidebar() {
        layout.classList.add('sidebar-open');
        toggle.setAttribute('aria-expanded', 'true');
        toggle.innerHTML = '<i class="fas fa-times"></i>';
    }

    function closeSidebar() {
        layout.classList.remove('sidebar-open');
        toggle.setAttribute('aria-expanded', 'false');
        toggle.innerHTML = '<i class="fas fa-bars"></i>';
    }

    toggle.addEventListener('click', function () {
        if (layout.classList.contains('sidebar-open')) {
            closeSidebar();
        } else {
            openSidebar();
        }
    });

    overlay.addEventListener('click', closeSidebar);

    sidebar.querySelectorAll('a').forEach(function (link) {
        link.addEventListener('click', function () {
            if (window.innerWidth < 992) {
                closeSidebar();
            }
        });
    });

    window.addEventListener('resize', function () {
        if (window.innerWidth >= 992) {
            closeSidebar();
        }
    });
})();
</script>
