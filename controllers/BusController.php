<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../models/Bus.php';

// Check if user is admin
if (!isLoggedIn() || !isAdmin()) {
    setAlert('Unauthorized access', 'danger');
    redirect(BASE_URL . '/index.php');
    exit();
}

$database = new Database();
$db = $database->getConnection();
$bus = new Bus($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'create') {
        $data = [
            'bus_number' => sanitize($_POST['bus_number']),
            'bus_name' => sanitize($_POST['bus_name']),
            'route_from' => sanitize($_POST['route_from']),
            'route_to' => sanitize($_POST['route_to']),
            'departure_time' => sanitize($_POST['departure_time']),
            'arrival_time' => sanitize($_POST['arrival_time']),
            'total_seats' => (int)$_POST['total_seats'],
            'available_seats' => (int)$_POST['total_seats'],
            'price' => (float)$_POST['price'],
            'status' => sanitize($_POST['status']),
            'image_string' => sanitize($_POST['image_string'] ?? '')
        ];
        
        if ($bus->create($data)) {
            setAlert('Bus added successfully!', 'success');
        } else {
            setAlert('Failed to add bus. Bus number may already exist.', 'danger');
        }
        redirect(BASE_URL . '/pages/admin/buses/index.php');
        
    }
    
    if ($action === 'update') {
        $id = (int)$_POST['id'];
        $data = [
            'bus_number' => sanitize($_POST['bus_number']),
            'bus_name' => sanitize($_POST['bus_name']),
            'route_from' => sanitize($_POST['route_from']),
            'route_to' => sanitize($_POST['route_to']),
            'departure_time' => sanitize($_POST['departure_time']),
            'arrival_time' => sanitize($_POST['arrival_time']),
            'total_seats' => (int)$_POST['total_seats'],
            'available_seats' => (int)$_POST['available_seats'],
            'price' => (float)$_POST['price'],
            'status' => sanitize($_POST['status']),
            'image_string' => sanitize($_POST['image_string'] ?? '')
        ];
        
        if ($bus->update($id, $data)) {
            setAlert('Bus updated successfully!', 'success');
        } else {
            setAlert('Failed to update bus', 'danger');
        }
        redirect(BASE_URL . '/pages/admin/buses/index.php');
    }
    
    if ($action === 'delete') {
        $id = (int)$_POST['id'];
        
        if ($bus->delete($id)) {
            setAlert('Bus deleted successfully!', 'success');
        } else {
            setAlert('Failed to delete bus. There may be existing reservations.', 'danger');
        }
        redirect(BASE_URL . '/pages/admin/buses/index.php');
    }
} else {
    redirect(BASE_URL . '/pages/admin/dashboard.php');
}
?>