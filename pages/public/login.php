<?php
// PAGES/PUBLIC/LOGIN.PHP

require_once '../../config/database.php';
require_once '../../config/constants.php';
require_once '../../includes/session.php';
require_once '../../includes/functions.php';

// Redirect if already logged in
if (isLoggedIn()) {
    setAlert('Redirecting to dashboard', 'info');
    redirect(
        isAdmin()
            ? BASE_URL . '/pages/admin/dashboard.php'
            : BASE_URL . '/pages/user/dashboard.php'
    );
}

$pageTitle = "Login - " . SITE_NAME;

include '../../UI/components/Header.php';
include '../../UI/components/Navbar.php';
include '../../UI/components/Alert.php';
?>

<div class="auth-container">
    <div class="auth-card">
        <h2>Login to Your Account</h2>

        <form method="POST" action="<?php echo BASE_URL; ?>/controllers/AuthController.php" novalidate>
            <input type="hidden" name="action" value="login">

            <!-- EMAIL -->
            <div class="form-group">
                <label for="email">
                    <i class="fas fa-envelope"></i> Email
                </label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    required
                    pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$"
                    title="Enter a valid email address"
                    autocomplete="email"
                >
            </div>

            <!-- PASSWORD -->
            <div class="form-group">
                <label for="password">
                    <i class="fas fa-lock"></i> Password
                </label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                    pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$"
                    title="Minimum 8 characters, with uppercase, lowercase, number, and special character"
                    autocomplete="current-password"
                >
            </div>

            <!-- SUBMIT -->
            <button type="submit" class="btn-submit">
                Login
            </button>
        </form>

        <p class="auth-switch">
            Don't have an account?
            <a href="register.php">Register here</a>
        </p>
    </div>
</div>

<?php include '../../UI/components/Footer.php'; ?>
