<!-- API/RESERVATIONS.PHP -->
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
        
        if ($id) {
            $resData = $reservation->getById($id);
            if ($resData) {
                $response['success'] = true;
                $response['data'] = $resData;
            } else {
                $response['message'] = 'Reservation not found';
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
    
    // Check if this is a multiple seat reservation
    $action = $data['action'] ?? '';
    
    if ($action === 'create_multiple') {
        // Handle multiple seat reservation
        $busId = $data['bus_id'] ?? null;
        $bookingDate = $data['booking_date'] ?? null;
        $paymentMethod = $data['payment_method'] ?? 'cash';
        $transactionId = $data['transaction_id'] ?? null;
        $totalAmount = $data['amount'] ?? 0;
        $passengers = $data['seats'] ?? []; // Array of passengers with seat, name, phone
        
        if (!$busId || !$bookingDate || empty($passengers)) {
            $response['message'] = 'Missing required fields for multiple reservation';
            break;
        }
        
        // Extract seat numbers for availability check
        $seatNumbers = array_column($passengers, 'seat');
        
        // Validate seat availability
        if (!$reservation->areSeatsAvailable($busId, $seatNumbers, $bookingDate)) {
            $response['message'] = 'One or more seats are already reserved';
            break;
        }
        
        // Get bus data
        $busData = $bus->getById($busId);
        if (!$busData) {
            $response['message'] = 'Bus not found';
            break;
        }
        
        // Validate passenger data
        foreach ($passengers as $index => $passenger) {
            if (empty($passenger['name']) || empty($passenger['phone']) || empty($passenger['seat'])) {
                $response['message'] = "Missing information for passenger " . ($index + 1);
                break 2;
            }
        }
        
        // Create multiple reservations
        $createdIds = $reservation->createMultiple(
            $_SESSION['user_id'],
            $busId,
            $bookingDate,
            $passengers,
            $totalAmount,
            $paymentMethod,
            $transactionId,
            'pending' // or 'confirmed' based on payment method
        );
        
        if ($createdIds) {
            // Update available seats count
            $seatCount = count($passengers);
            if ($bus->updateSeats($busId, $seatCount)) {
                $response['success'] = true;
                $response['message'] = "Successfully reserved {$seatCount} seat(s)";
                $response['data'] = [
                    'reservation_ids' => $createdIds,
                    'seat_count' => $seatCount,
                    'total_amount' => $totalAmount
                ];
            } else {
                // If seat update fails, cancel the reservations
                $reservation->cancelMultiple($createdIds);
                $response['message'] = 'Failed to update bus seat availability';
            }
        } else {
            $response['message'] = 'Failed to create reservations';
        }
    } else {
        // Original single seat reservation logic
        $busId = $data['bus_id'] ?? null;
        $seatNumber = $data['seat_number'] ?? null;
        $bookingDate = $data['booking_date'] ?? null;
        
        // Validate seat availability
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
            'status' => 'confirmed'
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
        
        if ($id && $status) {
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
