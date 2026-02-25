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
        $id = isset($_GET['id']) ? (int) $_GET['id'] : null;
        $from = isset($_GET['from']) ? sanitize($_GET['from']) : null;
        $to = isset($_GET['to']) ? sanitize($_GET['to']) : null;

        if ($id) {
            $busData = $bus->getById($id);
            if ($busData) {
                $response['success'] = true;
                $response['data'] = $busData;
            } else {
                http_response_code(404);
                $response['message'] = 'Bus not found';
            }
        } elseif ($from && $to) {
            $response['success'] = true;
            $response['data'] = $bus->search($from, $to);
        } else {
            $response['success'] = true;
            $response['data'] = $bus->getAll('active');
        }
        break;

    case 'POST':
        if (!isAdmin()) {
            http_response_code(403);
            $response['message'] = 'Unauthorized';
            break;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data) {
            http_response_code(400);
            $response['message'] = 'Invalid JSON body';
            break;
        }

        if ($bus->create($data)) {
            $response['success'] = true;
            $response['message'] = 'Bus created successfully';
        } else {
            $response['message'] = 'Failed to create bus';
        }
        break;

    case 'PUT':
        if (!isAdmin()) {
            http_response_code(403);
            $response['message'] = 'Unauthorized';
            break;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data) {
            http_response_code(400);
            $response['message'] = 'Invalid JSON body';
            break;
        }

        $id = isset($data['id']) ? (int) $data['id'] : null;

        if ($id && $bus->update($id, $data)) {
            $response['success'] = true;
            $response['message'] = 'Bus updated successfully';
        } else {
            $response['message'] = 'Failed to update bus';
        }
        break;

    case 'DELETE':
        if (!isAdmin()) {
            http_response_code(403);
            $response['message'] = 'Unauthorized';
            break;
        }

        $id = isset($_GET['id']) ? (int) $_GET['id'] : null;

        if ($id && $bus->delete($id)) {
            $response['success'] = true;
            $response['message'] = 'Bus deleted successfully';
        } else {
            $response['message'] = 'Failed to delete bus';
        }
        break;

    default:
        http_response_code(405);
        $response['message'] = 'Method not allowed';
        break;
}

echo json_encode($response);
?>