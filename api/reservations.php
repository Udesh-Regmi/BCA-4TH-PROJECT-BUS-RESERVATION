<?php
header('Content-Type: application/json');
require_once '../config/database.php';
require_once '../config/constants.php';
require_once '../includes/session.php';
require_once '../models/Reservation.php';
require_once '../models/Bus.php';

if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$database = new Database();
$db = $database->getConnection();
$reservation = new Reservation($db);
$bus = new Bus($db);

$method = $_SERVER['REQUEST_METHOD'];
$response = ['success' => false, 'message' => '', 'data' => null];

switch ($method) {
    case 'GET':
        $id = isset($_GET['id']) ? (int) $_GET['id'] : null;
        $userId = isset($_GET['user_id']) ? (int) $_GET['user_id'] : null;
        $busId = isset($_GET['bus_id']) ? (int) $_GET['bus_id'] : null;
        $date = isset($_GET['date']) ? sanitize($_GET['date']) : null;
        $transactionId = isset($_GET['transaction_id']) ? sanitize($_GET['transaction_id']) : null;

        if ($id) {
            $resData = $reservation->getById($id);
            if ($resData) {
                $response['success'] = true;
                $response['data'] = $resData;
            } else {
                http_response_code(404);
                $response['message'] = 'Reservation not found';
            }
        } elseif ($transactionId) {
            $reservations = $reservation->getAllByTransactionId($transactionId);
            if ($reservations) {
                $response['success'] = true;
                $response['data'] = $reservations;
            } else {
                http_response_code(404);
                $response['message'] = 'No reservations found';
            }
        } elseif ($busId && $date) {
            $response['success'] = true;
            $response['data'] = ['reserved_seats' => $reservation->getReservedSeats($busId, $date)];
        } elseif ($userId) {
            $response['success'] = true;
            $response['data'] = $reservation->getByUserId($userId);
        } elseif (isAdmin()) {
            $response['success'] = true;
            $response['data'] = $reservation->getAll();
        } else {
            $response['success'] = true;
            $response['data'] = $reservation->getByUserId($_SESSION['user_id']);
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data) {
            http_response_code(400);
            $response['message'] = 'Invalid JSON body';
            break;
        }

        $busId = isset($data['bus_id']) ? (int) $data['bus_id'] : null;
        $bookingDate = isset($data['booking_date']) ? sanitize($data['booking_date']) : null;

        if (isset($data['seat_numbers'])) {
            $seatNumbers = is_array($data['seat_numbers'])
                ? $data['seat_numbers']
                : array_map('intval', explode(',', $data['seat_numbers']));

            if (!$reservation->areSeatsAvailable($busId, $seatNumbers, $bookingDate)) {
                http_response_code(409);
                $response['message'] = 'One or more seats already reserved';
                break;
            }

            $busData = $bus->getById($busId);

            if (!$busData) {
                http_response_code(404);
                $response['message'] = 'Bus not found';
                break;
            }

            $transactionId = $data['transaction_id'] ?? 'TXN' . time() . rand(1000, 9999);

            $created = $reservation->createMultiple(
                $busId,
                $_SESSION['user_id'],
                $seatNumbers,
                $bookingDate,
                sanitize($data['passenger_name'] ?? ''),
                sanitize($data['passenger_phone'] ?? ''),
                $busData['price'],
                $transactionId,
                sanitize($data['payment_method'] ?? 'cash')
            );

            if ($created) {
                $bus->updateSeats($busId, count($seatNumbers));
                $response['success'] = true;
                $response['message'] = 'Reservations created successfully';
                $response['data'] = ['reservation_ids' => $created];
            } else {
                $response['message'] = 'Failed to create reservations';
            }
        } else {
            $seatNumber = isset($data['seat_number']) ? (int) $data['seat_number'] : null;
            $reservedSeats = $reservation->getReservedSeats($busId, $bookingDate);

            if (in_array($seatNumber, $reservedSeats)) {
                http_response_code(409);
                $response['message'] = 'Seat already reserved';
                break;
            }

            $busData = $bus->getById($busId);

            if (!$busData) {
                http_response_code(404);
                $response['message'] = 'Bus not found';
                break;
            }

            $reservationData = [
                'user_id'        => $_SESSION['user_id'],
                'bus_id'         => $busId,
                'seat_number'    => $seatNumber,
                'booking_date'   => $bookingDate,
                'passenger_name' => sanitize($data['passenger_name'] ?? ''),
                'passenger_phone'=> sanitize($data['passenger_phone'] ?? ''),
                'total_amount'   => $busData['price'],
                'status'         => 'confirmed',
                'payment_method' => sanitize($data['payment_method'] ?? 'cash'),
                'transaction_id' => $data['transaction_id'] ?? null,
            ];

            if ($reservation->createFromArray($reservationData)) {
                $bus->updateSeats($busId, 1);
                $response['success'] = true;
                $response['message'] = 'Reservation created successfully';
            } else {
                $response['message'] = 'Failed to create reservation';
            }
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data) {
            http_response_code(400);
            $response['message'] = 'Invalid JSON body';
            break;
        }

        $id = isset($data['id']) ? (int) $data['id'] : null;
        $status = isset($data['status']) ? sanitize($data['status']) : null;
        $transactionId = isset($data['transaction_id']) ? sanitize($data['transaction_id']) : null;

        if ($transactionId && $status) {
            if ($reservation->updateStatusByTransactionId($transactionId, $status)) {
                $response['success'] = true;
                $response['message'] = 'Reservations updated successfully';
            } else {
                $response['message'] = 'Failed to update reservations';
            }
        } elseif ($id && $status) {
            if ($reservation->updateStatus($id, $status)) {
                $response['success'] = true;
                $response['message'] = 'Reservation updated successfully';
            } else {
                $response['message'] = 'Failed to update reservation';
            }
        } else {
            http_response_code(400);
            $response['message'] = 'Missing required fields';
        }
        break;

    case 'DELETE':
        $id = isset($_GET['id']) ? (int) $_GET['id'] : null;
        $resData = $id ? $reservation->getById($id) : null;

        if (!$id || !$resData) {
            http_response_code(404);
            $response['message'] = 'Reservation not found';
            break;
        }

        if (!isAdmin() && $resData['user_id'] != $_SESSION['user_id']) {
            http_response_code(403);
            $response['message'] = 'Unauthorized';
            break;
        }

        if ($reservation->delete($id)) {
            $response['success'] = true;
            $response['message'] = 'Reservation deleted successfully';
        } else {
            $response['message'] = 'Failed to delete reservation';
        }
        break;

    default:
        http_response_code(405);
        $response['message'] = 'Method not allowed';
        break;
}

echo json_encode($response);
?>