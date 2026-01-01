<?php
require_once '../../config/database.php';
require_once '../../config/constants.php';
require_once '../../includes/session.php';
require_once '../../includes/functions.php';

if (isLoggedIn()) {
    setAlert('Redirecting to admin', 'info');
    redirect(isAdmin() ? BASE_URL . '/pages/admin/dashboard.php' : BASE_URL . '/pages/user/dashboard.php');
}

$pageTitle = "Register - " . SITE_NAME;
include '../../UI/components/Header.php';
include '../../UI/components/Navbar.php';
include '../../UI/components/Alert.php';
?>

<div class="auth-container">
    <div class="auth-card">
        <h2>Create New Account</h2>
        <form method="POST" action="<?php echo BASE_URL; ?>/controllers/AuthController.php" id="registerForm">
            <input type="hidden" name="action" value="register">

            <!-- FULL NAME -->
            <div class="form-group">
                <label for="name">
                    <i class="fas fa-user"></i> Full Name
                </label>
                <input type="text" id="name" name="name" required pattern="^[A-Za-z ]{3,50}$"
                    title="Name must be 3–50 characters and contain only letters and spaces">
            </div>

            <!-- EMAIL -->
            <div class="form-group">
                <label for="email">
                    <i class="fas fa-envelope"></i> Email
                </label>
                <input type="email" id="email" name="email" required
                    pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$" title="Enter a valid email address">
            </div>

            <!-- PHONE -->
            <div class="form-group">
                <label for="phone">
                    <i class="fas fa-phone"></i> Phone
                </label>
                <input type="tel" id="phone" name="phone" required pattern="^\+?[0-9]{10,15}$"
                    title="Enter a valid phone number (10–15 digits)">
            </div>

            <!-- PASSWORD -->
            <div class="form-group">
                <label for="password">
                    <i class="fas fa-lock"></i> Password
                </label>
                <input type="password" id="password" name="password" required
                    pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$"
                    title="Minimum 8 characters with uppercase, lowercase, number, and special character">
            </div>

            <!-- CONFIRM PASSWORD -->
            <div class="form-group">
                <label for="confirm_password">
                    <i class="fas fa-lock"></i> Confirm Password
                </label>
                <input type="password" id="confirm_password" name="confirm_password" required
                    pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$"
                    title="Passwords must match and follow the same rules">
            </div>

            <button type="submit" class="btn-submit">Register</button>
        </form>


        <p class="auth-switch">Already have an account? <a href="login.php">Login here</a></p>
    </div>
</div>

<?php include '../../UI/components/Footer.php'; ?>