<!-- CONTROLLERS/USERCONTROLLER.PHP -->
<?php
require_once '../config/database.php';
require_once '../config/constants.php';
require_once '../includes/session.php';
require_once '../includes/functions.php';
require_once '../models/User.php';

requireLogin();

$database = new Database();
$db = $database->getConnection();
$user = new User($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'update_profile') {
        $name = sanitize($_POST['name']);
        $email = sanitize($_POST['email']);
        $phone = sanitize($_POST['phone']);

        if ($user->update($_SESSION['user_id'], $name, $email, $phone)) {
            $_SESSION['user_name'] = $name;
            $_SESSION['user_email'] = $email;
            setAlert('Profile updated successfully!', 'success');
        } else {
            setAlert('Failed to update profile', 'danger');
        }

        redirect(BASE_URL . '/pages/user/profile.php');
        exit();
    }

    if ($action === 'change_password') {
        $currentPassword = $_POST['current_password'];
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];

        if ($newPassword !== $confirmPassword) {
            setAlert('Passwords do not match', 'danger');
            redirect(BASE_URL . '/pages/user/profile.php');
            exit();
        }

        $userData = $user->getById($_SESSION['user_id']);

        if (password_verify($currentPassword, $userData['password'])) {
            if ($user->updatePassword($_SESSION['user_id'], $newPassword)) {
                setAlert('Password changed successfully!', 'success');
            } else {
                setAlert('Failed to update password', 'danger');
            }
        } else {
            setAlert('Current password is incorrect', 'danger');
        }

        redirect(BASE_URL . '/pages/user/profile.php');
        exit();
    }

    if ($action === 'update_role') {
        if (!isAdmin()) {
            setAlert('Unauthorized action', 'danger');
            redirect(BASE_URL . '/pages/user/profile.php');
            exit();
        }

        $userId = (int) $_POST['user_id'];

        if ($user->updateRole($userId)) {
            setAlert('User role updated successfully!', 'success');
        } else {
            setAlert('Failed to update user role', 'danger');
        }

        redirect(BASE_URL . '/pages/admin/users/index.php');
        exit();
    }
    if ($action === 'delete_user') {
        if (!isAdmin()) {
            setAlert('Unauthorized action', 'danger');
            redirect(BASE_URL . '/pages/user/profile.php');
            exit();
        }

        $userId = (int) $_POST['user_id'];

        if ($userId === (int) $_SESSION['user_id']) {
            setAlert('You cannot delete your own admin account from this panel', 'warning');
            redirect(BASE_URL . '/pages/admin/users/index.php');
            exit();
        }

        // Check if target user is an admin
        $targetUser = $user->getById($userId);
        if ($targetUser && $targetUser['role'] === 'admin') {
            setAlert('You cannot delete admin accounts. Only regular users can be deleted.', 'warning');
            redirect(BASE_URL . '/pages/admin/users/index.php');
            exit();
        }

        if ($user->delete($userId)) {
            setAlert('User deleted successfully!', 'success');
        } else {
            setAlert('Failed to delete user', 'danger');
        }

        redirect(BASE_URL . '/pages/admin/users/index.php');
        exit();
    }

    if ($action === 'delete_account') {
        $userId = (int) $_SESSION['user_id'];

        if ($user->delete($userId)) {
            session_destroy();
            setAlert('Account deleted successfully!', 'success');
            redirect(BASE_URL . '/pages/public/login.php');
        } else {
            setAlert('Failed to delete account', 'danger');
            redirect(BASE_URL . '/pages/user/profile.php');
        }
        exit();
    }
}
?>