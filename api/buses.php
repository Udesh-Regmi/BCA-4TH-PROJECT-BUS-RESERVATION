<!-- API/BUSES.PHP -->
<?php
header('Content-Type: application/json');
require_once '../config/database.php';
require_once '../config/constants.php';
require_once '../includes/session.php';
require_once '../models/Bus.php';

$database = new Database();
$db = $database->getConnection();
$bus = new Bus($db);

$method = $_SERVER['REQUEST_METHOD'];
$response = ['success' => false, 'message' => '', 'data' => null];

switch ($method) {
    case 'GET':
        $id = $_GET['id'] ?? null;
        $from = $_GET['from'] ?? null;
        $to = $_GET['to'] ?? null;
        
        if ($id) {
            $busData = $bus->getById($id);
            if ($busData) {
                $response['success'] = true;
                $response['data'] = $busData;
            } else {
                $response['message'] = 'Bus not found';
            }
        } elseif ($from && $to) {
            $buses = $bus->search($from, $to);
            $response['success'] = true;
            $response['data'] = $buses;
        } else {
            $buses = $bus->getAll('active');
            $response['success'] = true;
            $response['data'] = $buses;
        }
        break;
        
    case 'POST':
        if (!isAdmin()) {
            $response['message'] = 'Unauthorized';
            break;
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        
        if ($bus->create($data)) {
            $response['success'] = true;
            $response['message'] = 'Bus created successfully';
        } else {
            $response['message'] = 'Failed to create bus';
        }
        break;
        
    case 'PUT':
        if (!isAdmin()) {
            $response['message'] = 'Unauthorized';
            break;
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        $id = $data['id'] ?? null;
        
        if ($id && $bus->update($id, $data)) {
            $response['success'] = true;
            $response['message'] = 'Bus updated successfully';
        } else {
            $response['message'] = 'Failed to update bus';
        }
        break;
        
    case 'DELETE':
        if (!isAdmin()) {
            $response['message'] = 'Unauthorized';
            break;
        }
        
        $id = $_GET['id'] ?? null;
        
        if ($id && $bus->delete($id)) {
            $response['success'] = true;
            $response['message'] = 'Bus deleted successfully';
        } else {
            $response['message'] = 'Failed to delete bus';
        }
        break;
}

echo json_encode($response);
?>
