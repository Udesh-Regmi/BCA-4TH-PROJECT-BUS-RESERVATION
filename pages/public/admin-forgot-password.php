<?php
require_once '../../config/constants.php';
require_once '../../includes/session.php';
require_once '../../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    setAlert('We are working on  forgot-password verification for a future release.', 'warning');
    redirect(BASE_URL . '/pages/public/admin-forgot-password.php');
}

$pageTitle = " Forgot Password  - " . SITE_NAME;

include '../../UI/components/Header.php';
include '../../UI/components/Navbar.php';
include '../../UI/components/Alert.php';
?>

<div class="auth-container">
    <div class="auth-card admin-forgot-card">
        <h2> Forgot Password </h2>
        <p class="admin-forgot-subtitle">
            This is a preview flow for the upcoming secure password recovery process.
        </p>

        <form method="POST" novalidate>
            <div class="form-group">
                <label for="admin_email"><i class="fas fa-envelope"></i>  Email</label>
                <input type="email" id="admin_email" name="admin_email" placeholder="admin@example.com" required>
            </div>

            <div class="form-group">
                <label for="verification_code"><i class="fas fa-shield-alt"></i> Verification Code</label>
                <input type="text" id="verification_code" name="verification_code" placeholder="Enter code" required>
            </div>

            <button type="submit" class="btn-submit">Verify Code </button>
        </form>

        <p class="auth-switch">
            Return to login?
            <a href="<?php echo BASE_URL; ?>/pages/public/login.php">Go back</a>
        </p>
    </div>
</div>

<?php include '../../UI/components/Footer.php'; ?>