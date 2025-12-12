<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../models/Reservation.php';
require_once __DIR__ . '/../models/Bus.php';

// Check if user is logged in
if (!isLoggedIn()) {
    setAlert('Please login first', 'danger');
    redirect(BASE_URL . '/pages/public/login.php');
    exit();
}

$database = new Database();
$db = $database->getConnection();
$reservation = new Reservation($db);
$bus = new Bus($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Detect JSON request and decode into $input, otherwise use $_POST
    $contentType = $_SERVER['CONTENT_TYPE'] ?? ($_SERVER['HTTP_CONTENT_TYPE'] ?? '');
    $rawBody = file_get_contents('php://input');
    $input = $_POST;

    if (stripos($contentType, 'application/json') !== false && $rawBody) {
        $decoded = json_decode($rawBody, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            $input = $decoded;
        }
    }

    $action = $input['action'] ?? '';

    if ($action === 'create') {
        $busId = (int) ($input['bus_id'] ?? 0);
        $seatNumber = (int) ($input['seat_number'] ?? 0);
        $bookingDate = sanitize($input['booking_date'] ?? '');
        $passengerName = sanitize($input['passenger_name'] ?? '');
        $passengerPhone = sanitize($input['passenger_phone'] ?? '');
        $paymentMethod = sanitize($input['payment_method'] ?? 'cash');
        $status = sanitize($input['status'] ?? 'pending');
        $transactionId = sanitize($input['transaction_id'] ?? null);

        // Check if seat is already reserved
        $reservedSeats = $reservation->getReservedSeats($busId, $bookingDate);

        if (in_array($seatNumber, $reservedSeats)) {
            if (stripos($contentType, 'application/json') !== false) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Seat already reserved! Please select another seat.']);
                exit();
            } else {
                setAlert('Seat already reserved! Please select another seat.', 'danger');
                redirect(BASE_URL . '/pages/user/makereservation.php?bus_id=' . $busId);
                exit();
            }
        }

        // Get bus details
        $busData = $bus->getById($busId);

        if (!$busData) {
            if (stripos($contentType, 'application/json') !== false) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Bus not found']);
                exit();
            } else {
                setAlert('Bus not found', 'danger');
                redirect(BASE_URL . '/pages/public/viewbus.php');
                exit();
            }
        }

        // Create reservation data
        $data = [
            'user_id' => $_SESSION['user_id'],
            'bus_id' => $busId,
            'seat_number' => $seatNumber,
            'booking_date' => $bookingDate,
            'passenger_name' => $passengerName,
            'passenger_phone' => $passengerPhone,
            'total_amount' => $busData['price'],
            'status' => $status,
            'payment_method' => $paymentMethod,
            'transaction_id' => $transactionId,
        ];

        // Create reservation (use array helper that already exists)
        $created = $reservation->createFromArray($data);

        if ($created) {
            // Update available seats
            $bus->updateSeats($busId, 1);

            if (stripos($contentType, 'application/json') !== false) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'message' => 'Reservation successful']);
                exit();
            } else {
                setAlert('Reservation successful!', 'success');
                redirect(BASE_URL . '/pages/user/reservations.php');
                exit();
            }
        } else {
            if (stripos($contentType, 'application/json') !== false) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Reservation failed. Please try again.']);
                exit();
            } else {
                setAlert('Reservation failed. Please try again.', 'danger');
                redirect(BASE_URL . '/pages/user/makereservation.php?bus_id=' . $busId);
                exit();
            }
        }
    }

    if ($action === 'cancel') {
        $id = (int) ($input['id'] ?? 0);

        // Get reservation details
        $resData = $reservation->getById($id);

        if (!$resData) {
            setAlert('Reservation not found', 'danger');
        } else if ($resData['user_id'] != $_SESSION['user_id'] && !isAdmin()) {
            setAlert('Unauthorized action', 'danger');
        } else if ($reservation->updateStatus($id, 'cancelled')) {
            // Restore seat
            $bus->restoreSeats($resData['bus_id'], 1);
            setAlert('Reservation cancelled successfully', 'success');
        } else {
            setAlert('Failed to cancel reservation', 'danger');
        }

        if (isAdmin()) {
            redirect(BASE_URL . '/pages/admin/reservations/index.php');
        } else {
            redirect(BASE_URL . '/pages/user/reservations.php');
        }
        exit();
    }
    if ($action === 'delete') {
        $id = (int) ($input['id'] ?? 0);

        // Get reservation details
        $resData = $reservation->getById($id);

        if (!$resData) {
            setAlert('Reservation not found', 'danger');
            redirect(BASE_URL . '/pages/user/reservations.php');
            exit();


        } else if ($resData['user_id'] != $_SESSION['user_id'] && !isAdmin()) {
            setAlert('Unauthorized action', 'danger');
            redirect(BASE_URL . '/pages/user/reservations.php');
            exit();

        } else if (isAdmin() && $reservation->delete($id)) {
            setAlert('Reservation deleted successfully', 'success');
            redirect(BASE_URL . '/pages/admin/reservations/index.php');
            exit();

        } else {
            setAlert('Failed to delete reservation', 'danger');
            redirect(BASE_URL . '/pages/user/reservations.php');
            exit();

        }


        if (isAdmin()) {
            redirect(BASE_URL . '/pages/admin/reservations/index.php');
        } else {
            redirect(BASE_URL . '/pages/user/reservations.php');
        }
        exit();
    }
    if ($action === 'update_status' && isAdmin()) {
        $id = (int) ($input['id'] ?? 0);
        $status = sanitize($input['status'] ?? '');

        if ($reservation->updateStatus($id, $status)) {
            setAlert('Status updated successfully!', 'success');
        } else {
            setAlert('Failed to update status', 'danger');
        }
        redirect(BASE_URL . '/pages/admin/reservations/index.php');
        exit();
    }
} else {
    redirect(BASE_URL . '/index.php');
}
?>