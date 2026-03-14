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
        <div class="nav-overlay" id="nav-overlay" aria-hidden="true"></div>

        <button class="nav-toggle" id="mobile-toggle" type="button" aria-label="Toggle menu" aria-expanded="false" aria-controls="nav-menu">
            <i class="fas fa-bars"></i>
        </button>
    </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const mobileToggle = document.getElementById('mobile-toggle');
    const navMenu = document.getElementById('nav-menu');
    const navOverlay = document.getElementById('nav-overlay');
    const dropdowns = document.querySelectorAll('.dropdown');

    if (!mobileToggle || !navMenu || !navOverlay) {
        return;
    }

    function closeAllDropdowns() {
        dropdowns.forEach(function(dropdown) {
            dropdown.classList.remove('active');
        });
    }

    function openMobileMenu() {
        navMenu.classList.add('active');
        navOverlay.classList.add('active');
        document.body.classList.add('nav-open');
        mobileToggle.setAttribute('aria-expanded', 'true');
        const icon = mobileToggle.querySelector('i');
        if (icon) {
            icon.classList.remove('fa-bars');
            icon.classList.add('fa-times');
        }
    }

    function closeMobileMenu() {
        navMenu.classList.remove('active');
        navOverlay.classList.remove('active');
        document.body.classList.remove('nav-open');
        mobileToggle.setAttribute('aria-expanded', 'false');
        const icon = mobileToggle.querySelector('i');
        if (icon) {
            icon.classList.remove('fa-times');
            icon.classList.add('fa-bars');
        }
        closeAllDropdowns();
    }

    mobileToggle.addEventListener('click', function(e) {
        e.stopPropagation();
        if (navMenu.classList.contains('active')) {
            closeMobileMenu();
        } else {
            openMobileMenu();
        }
    });

    navOverlay.addEventListener('click', closeMobileMenu);

    // Close mobile nav after selecting an actual route link (except account toggle).
    navMenu.querySelectorAll('a').forEach(function(link) {
        if (link.classList.contains('dropbtn')) {
            return;
        }
        link.addEventListener('click', function() {
            if (window.innerWidth < 992) {
                closeMobileMenu();
            }
        });
    });

    // Dropdown click behavior for both desktop and mobile.
    navMenu.querySelectorAll('.dropbtn').forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const parentDropdown = button.closest('.dropdown');
            if (!parentDropdown) {
                return;
            }

            const willOpen = !parentDropdown.classList.contains('active');
            closeAllDropdowns();
            if (willOpen) {
                parentDropdown.classList.add('active');
            }
        });
    });

    // Close dropdown when clicking outside navbar area.
    document.addEventListener('click', function(e) {
        const clickedInsideNav = navMenu.contains(e.target) || mobileToggle.contains(e.target);
        if (!clickedInsideNav) {
            closeAllDropdowns();
            if (window.innerWidth < 992) {
                closeMobileMenu();
            }
        }
    });

    // Keep state clean when switching breakpoints.
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 992) {
            closeMobileMenu();
        }
    });
});
</script>