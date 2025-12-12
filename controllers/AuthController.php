<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../models/User.php';

$database = new Database();
$db = $database->getConnection();
$user = new User($db);

// Handle GET logout
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    redirect(BASE_URL . '/pages/public/home.php');
    exit();
}

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'login') {
        $email = sanitize($_POST['email']);
        $password = $_POST['password'];
        
        $userData = $user->login($email, $password);
        
        if ($userData) {
            $_SESSION['user_id'] = $userData['id'];
            $_SESSION['user_name'] = $userData['name'];
            $_SESSION['user_email'] = $userData['email'];
            $_SESSION['role'] = $userData['role'];
            
            setAlert('Login successful!', 'success');
            
            if ($userData['role'] === 'admin') {
                redirect(BASE_URL . '/pages/admin/dashboard.php');
            } else {
                redirect(BASE_URL . '/pages/user/dashboard.php');
            }
        } else {
            setAlert('Invalid email or password', 'danger');
            redirect(BASE_URL . '/pages/public/login.php');
        }
    }
    
    if ($action === 'register') {
        $name = sanitize($_POST['name']);
        $email = sanitize($_POST['email']);
        $password = $_POST['password'];
        $phone = sanitize($_POST['phone']);
        
        if ($user->create($name, $email, $password, $phone)) {
            setAlert('Registration successful! Please login.', 'success');
            redirect(BASE_URL . '/pages/public/login.php');
        } else {
            setAlert('Registration failed. Email may already exist.', 'danger');
            redirect(BASE_URL . '/pages/public/register.php');
        }
    }
    
    if ($action === 'logout') {
        session_destroy();
        redirect(BASE_URL . '/pages/public/home.php');
        
        exit();

    }
}
?>