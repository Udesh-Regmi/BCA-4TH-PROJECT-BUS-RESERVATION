<!-- PAGES/PUBLIC/LOGIN.PHP -->
<?php
require_once '../../config/database.php';
require_once '../../config/constants.php';
require_once '../../includes/session.php';
require_once '../../includes/functions.php';

if (isLoggedIn()) {
    setAlert('Redirecting to dashboard','info');    
    redirect(isAdmin() ? BASE_URL . '/pages/admin/dashboard.php' : BASE_URL . '/pages/user/dashboard.php');
}

$pageTitle = "Login - " . SITE_NAME;
include '../../UI/components/Header.php';  // CSS linked here
include '../../UI/components/Navbar.php';
include '../../UI/components/Alert.php';
?>

<div class="auth-container">
    <div class="auth-card">
        <h2>Login to Your Account</h2>
        <form method="POST" action="<?php echo BASE_URL; ?>/controllers/AuthController.php">
            <input type="hidden" name="action" value="login">
            
            <div class="form-group">
                <label for="email"><i class="fas fa-envelope"></i> Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="password"><i class="fas fa-lock"></i> Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit" class="btn-submit">Login</button>
        </form>
        
        <p class="auth-switch">Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</div>

<?php include '../../UI/components/Footer.php'; ?>