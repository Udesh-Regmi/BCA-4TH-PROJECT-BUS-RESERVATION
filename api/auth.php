<!-- API/AUTH.PHP -->
<?php
header('Content-Type: application/json');
require_once '../config/database.php';
require_once '../config/constants.php';
require_once '../includes/session.php';
require_once '../models/User.php';

$database = new Database();
$db = $database->getConnection();
$user = new User($db);

$method = $_SERVER['REQUEST_METHOD'];
$response = ['success' => false, 'message' => '', 'data' => null];

switch ($method) {
    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        $action = $data['action'] ?? '';
        
        if ($action === 'login') {
            $email = $data['email'] ?? '';
            $password = $data['password'] ?? '';
            
            $userData = $user->login($email, $password);
            
            if ($userData) {
                $_SESSION['user_id'] = $userData['id'];
                $_SESSION['user_name'] = $userData['name'];
                $_SESSION['user_email'] = $userData['email'];
                $_SESSION['role'] = $userData['role'];
                
                $response['success'] = true;
                $response['message'] = 'Login successful';
                $response['data'] = [
                    'user' => [
                        'id' => $userData['id'],
                        'name' => $userData['name'],
                        'email' => $userData['email'],
                        'role' => $userData['role']
                    ],
                    'redirect' => $userData['role'] === 'admin' ? 
                        BASE_URL . '/pages/admin/dashboard.php' : 
                        BASE_URL . '/pages/user/dashboard.php'
                ];
            } else {
                $response['message'] = 'Invalid credentials';
            }
        } elseif ($action === 'register') {
            $name = $data['name'] ?? '';
            $email = $data['email'] ?? '';
            $password = $data['password'] ?? '';
            $phone = $data['phone'] ?? '';
            
            if ($user->create($name, $email, $password, $phone)) {
                $response['success'] = true;
                $response['message'] = 'Registration successful';
            } else {
                $response['message'] = 'Registration failed';
            }
        } elseif ($action === 'logout') {
            session_destroy();
            $response['success'] = true;
            $response['message'] = 'Logged out successfully';
        }
        break;
        
    case 'GET':
        if (isLoggedIn()) {
            $response['success'] = true;
            $response['data'] = [
                'user' => [
                    'id' => $_SESSION['user_id'],
                    'name' => $_SESSION['user_name'],
                    'email' => $_SESSION['user_email'],
                    'role' => $_SESSION['role']
                ]
            ];
        } else {
            $response['message'] = 'Not authenticated';
        }
        break;
}

echo json_encode($response);
?>