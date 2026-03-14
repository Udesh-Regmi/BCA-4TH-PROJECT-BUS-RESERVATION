<?php
require_once '../../config/database.php';
require_once '../../config/constants.php';
require_once '../../includes/session.php';
require_once '../../includes/functions.php';
require_once '../../models/User.php';
require_once '../../middleware/auth.php';

$database = new Database();
$db = $database->getConnection();
$user = new User($db);

$userData = $user->getById($_SESSION['user_id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $phone = sanitize($_POST['phone']);
    
    if ($user->update($_SESSION['user_id'], $name, $email, $phone)) {
        $_SESSION['user_name'] = $name;
        $_SESSION['user_email'] = $email;
        setAlert('Profile updated successfully!', 'success');
        redirect(BASE_URL . '/pages/user/profile.php');
    } else {
        setAlert('Failed to update profile', 'danger');
    }
}

$pageTitle = "My Profile - " . SITE_NAME;
$additionalCSS = "user.css";
include '../../UI/components/Header.php';
include '../../UI/components/Navbar.php';
include '../../UI/components/Alert.php';
?>

<div class="dashboard-layout">
    <?php include '../../UI/components/Sidebar.php'; ?>
    
    <main class="dashboard-content">
        <h1>My Profile</h1>
        
        <div class="form-card">
            <h2>Update Profile Information</h2>
            <form method="POST">
                <div class="form-group">
                    <label for="name"><i class="fas fa-user"></i> Full Name</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($userData['name']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope"></i> Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($userData['email']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="phone"><i class="fas fa-phone"></i> Phone</label>
                    <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($userData['phone'] ?? ''); ?>" required>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-shield-alt"></i> Role</label>
                    <input type="text" value="<?php echo ucfirst($userData['role']); ?>" disabled>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-calendar"></i> Member Since</label>
                    <input type="text" value="<?php echo formatDate($userData['created_at']); ?>" disabled>
                </div>
                
                <button type="submit" class="btn-submit">Update Profile</button>
            </form>
        </div>

            <form method="POST" action="<?php echo BASE_URL; ?>/controllers/UserController.php" onsubmit="return confirm('Are you sure you want to delete your account? This cannot be undone.');">
                <input type="hidden" name="action" value="delete_account">
                <button type="submit" style="background: #dc3545; color: #ffffff; border: none;margin:2vw 0vw; padding: 0.75rem 1.75rem; border-radius: 5px; font-weight: 700; cursor: pointer; font-size: 1rem; letter-spacing: 0.3px;">
                    <i class="fas fa-trash-alt"></i> Delete My Account
                </button>
                            <p style="color: #666; margin-bottom: 1.25rem;"> This will permanently delete your account and all associated data. This action cannot be undone.</p>

            </form>
    </main>
</div>

<?php include '../../UI/components/Footer.php'; ?>