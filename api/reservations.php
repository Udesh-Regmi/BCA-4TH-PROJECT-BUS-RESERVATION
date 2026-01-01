<?php
header('Content-Type: application/json');
require_once '../config/database.php';
require_once '../config/constants.php';
require_once '../includes/session.php';
require_once '../models/Reservation.php';
require_once '../models/Bus.php';

if (!isLoggedIn()) {
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
        $id = $_GET['id'] ?? null;
        $userId = $_GET['user_id'] ?? null;
        $busId = $_GET['bus_id'] ?? null;
        $date = $_GET['date'] ?? null;
        $transactionId = $_GET['transaction_id'] ?? null;
        
        if ($id) {
            $resData = $reservation->getById($id);
            if ($resData) {
                $response['success'] = true;
                $response['data'] = $resData;
            } else {
                $response['message'] = 'Reservation not found';
            }
        } elseif ($transactionId) {
            $reservations = $reservation->getAllByTransactionId($transactionId);
            if ($reservations) {
                $response['success'] = true;
                $response['data'] = $reservations;
            } else {
                $response['message'] = 'No reservations found';
            }
        } elseif ($busId && $date) {
            $reservedSeats = $reservation->getReservedSeats($busId, $date);
            $response['success'] = true;
            $response['data'] = ['reserved_seats' => $reservedSeats];
        } elseif ($userId) {
            $reservations = $reservation->getByUserId($userId);
            $response['success'] = true;
            $response['data'] = $reservations;
        } elseif (isAdmin()) {
            $reservations = $reservation->getAll();
            $response['success'] = true;
            $response['data'] = $reservations;
        } else {
            $reservations = $reservation->getByUserId($_SESSION['user_id']);
            $response['success'] = true;
            $response['data'] = $reservations;
        }
        break;
        
    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        
        $busId = $data['bus_id'] ?? null;
        $bookingDate = $data['booking_date'] ?? null;
        
        if (isset($data['seat_numbers'])) {
            $seatNumbers = is_array($data['seat_numbers']) 
                ? $data['seat_numbers'] 
                : array_map('intval', explode(',', $data['seat_numbers']));
            
            if (!$reservation->areSeatsAvailable($busId, $seatNumbers, $bookingDate)) {
                $response['message'] = 'One or more seats already reserved';
                break;
            }
            
            $busData = $bus->getById($busId);
            $transactionId = $data['transaction_id'] ?? 'TXN' . time() . rand(1000, 9999);
            
            $created = $reservation->createMultiple(
                $busId,
                $_SESSION['user_id'],
                $seatNumbers,
                $bookingDate,
                $data['passenger_name'],
                $data['passenger_phone'],
                $busData['price'],
                $transactionId,
                $data['payment_method'] ?? 'cash'
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
            $seatNumber = $data['seat_number'] ?? null;
            
            $reservedSeats = $reservation->getReservedSeats($busId, $bookingDate);
            
            if (in_array($seatNumber, $reservedSeats)) {
                $response['message'] = 'Seat already reserved';
                break;
            }
            
            $busData = $bus->getById($busId);
            
            $reservationData = [
                'user_id' => $_SESSION['user_id'],
                'bus_id' => $busId,
                'seat_number' => $seatNumber,
                'booking_date' => $bookingDate,
                'passenger_name' => $data['passenger_name'],
                'passenger_phone' => $data['passenger_phone'],
                'total_amount' => $busData['price'],
                'status' => 'confirmed',
                'payment_method' => $data['payment_method'] ?? 'cash',
                'transaction_id' => $data['transaction_id'] ?? null
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
        $id = $data['id'] ?? null;
        $status = $data['status'] ?? null;
        $transactionId = $data['transaction_id'] ?? null;
        
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
        }
        break;
        
    case 'DELETE':
        $id = $_GET['id'] ?? null;
        
        if ($id && (isAdmin() || $reservation->getById($id)['user_id'] == $_SESSION['user_id'])) {
            if ($reservation->delete($id)) {
                $response['success'] = true;
                $response['message'] = 'Reservation deleted successfully';
            } else {
                $response['message'] = 'Failed to delete reservation';
            }
        } else {
            $response['message'] = 'Unauthorized';
        }
        break;
}

echo json_encode($response);
?>