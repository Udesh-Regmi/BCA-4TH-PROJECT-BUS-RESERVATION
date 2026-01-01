<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../models/Reservation.php';
require_once __DIR__ . '/../models/Bus.php';

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

    if ($action === 'create_multiple') {
    $busId = (int) ($input['bus_id'] ?? 0);
    $seatNumbers = $input['seat_numbers'] ?? '';
    $bookingDate = sanitize($input['booking_date'] ?? '');
    $passengerName = sanitize($input['passenger_name'] ?? '');
    $passengerPhone = sanitize($input['passenger_phone'] ?? '');
    $paymentMethod = sanitize($input['payment_method'] ?? 'cash');
    $status = sanitize($input['status'] ?? 'pending');
    $transactionId = sanitize($input['transaction_id'] ?? null);

    $seatNumbersArray = array_map('intval', explode(',', $seatNumbers));

    if (empty($seatNumbersArray)) {
        if (stripos($contentType, 'application/json') !== false) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'No seats selected']);
            exit();
        } else {
            setAlert('No seats selected', 'danger');
            redirect(BASE_URL . '/pages/user/makereservation.php?bus_id=' . $busId);
            exit();
        }
    }

    if (!$reservation->areSeatsAvailable($busId, $seatNumbersArray, $bookingDate)) {
        if (stripos($contentType, 'application/json') !== false) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'One or more seats already reserved']);
            exit();
        } else {
            setAlert('One or more seats already reserved', 'danger');
            redirect(BASE_URL . '/pages/user/makereservation.php?bus_id=' . $busId);
            exit();
        }
    }

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

    try {
        $created = $reservation->createMultiple(
            $busId,
            $_SESSION['user_id'],
            $seatNumbersArray,
            $bookingDate,
            $passengerName,
            $passengerPhone,
            $busData['price'],
            $transactionId,
            $paymentMethod
        );

        if ($created) {
            $bus->updateSeats($busId, count($seatNumbersArray));

            if (stripos($contentType, 'application/json') !== false) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'message' => 'Reservations successful']);
                exit();
            } else {
                setAlert('Reservations successful!', 'success');
                redirect(BASE_URL . '/pages/user/reservations.php');
                exit();
            }
        } else {
            $errorMsg = 'Reservations failed - Check database';
            if (stripos($contentType, 'application/json') !== false) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => $errorMsg]);
                exit();
            } else {
                setAlert($errorMsg, 'danger');
                redirect(BASE_URL . '/pages/user/makereservation.php?bus_id=' . $busId);
                exit();
            }
        }
    } catch (Exception $e) {
        $errorMsg = 'Error: ' . $e->getMessage();
        if (stripos($contentType, 'application/json') !== false) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => $errorMsg]);
            exit();
        } else {
            setAlert($errorMsg, 'danger');
            redirect(BASE_URL . '/pages/user/makereservation.php?bus_id=' . $busId);
            exit();
        }
    }
}
    if ($action === 'create') {
        $busId = (int) ($input['bus_id'] ?? 0);
        $seatNumber = (int) ($input['seat_number'] ?? 0);
        $bookingDate = sanitize($input['booking_date'] ?? '');
        $passengerName = sanitize($input['passenger_name'] ?? '');
        $passengerPhone = sanitize($input['passenger_phone'] ?? '');
        $paymentMethod = sanitize($input['payment_method'] ?? 'cash');
        $status = sanitize($input['status'] ?? 'pending');
        $transactionId = sanitize($input['transaction_id'] ?? null);

        $reservedSeats = $reservation->getReservedSeats($busId, $bookingDate);

        if (in_array($seatNumber, $reservedSeats)) {
            if (stripos($contentType, 'application/json') !== false) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Seat already reserved']);
                exit();
            } else {
                setAlert('Seat already reserved', 'danger');
                redirect(BASE_URL . '/pages/user/makereservation.php?bus_id=' . $busId);
                exit();
            }
        }

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

        $created = $reservation->createFromArray($data);

        if ($created) {
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
                echo json_encode(['success' => false, 'message' => 'Reservation failed']);
                exit();
            } else {
                setAlert('Reservation failed', 'danger');
                redirect(BASE_URL . '/pages/user/makereservation.php?bus_id=' . $busId);
                exit();
            }
        }
    }

   // Replace the existing cancel action with this:
if ($action === 'cancel') {
    $id = (int) ($input['id'] ?? 0);
    $resData = $reservation->getById($id);

    if (!$resData) {
        setAlert('Reservation not found', 'danger');
    } else if ($resData['user_id'] != $_SESSION['user_id'] && !isAdmin()) {
        setAlert('Unauthorized action', 'danger');
    } else if ($reservation->updateStatus($id, 'cancelled')) {
        $restoreResult = $bus->restoreSeats($resData['bus_id'], 1);
        if (!$restoreResult) {
            setAlert('Reservation cancelled but seat restore failed', 'warning');
        } else {
            setAlert('Reservation cancelled successfully', 'success');
        }
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

if ($action === 'cancel_by_transaction') {
    $transactionId = sanitize($input['transaction_id'] ?? '');
    
    $reservations = $reservation->getAllByTransactionId($transactionId);
    
    if (!$reservations || count($reservations) === 0) {
        setAlert('Reservations not found', 'danger');
    } else {
        $firstRes = $reservations[0];
        
        if ($firstRes['user_id'] != $_SESSION['user_id'] && !isAdmin()) {
            setAlert('Unauthorized action', 'danger');
        } else if ($reservation->updateStatusByTransactionId($transactionId, 'cancelled')) {
            $bus->restoreSeats($firstRes['bus_id'], count($reservations));
            setAlert(count($reservations) . ' reservation(s) cancelled successfully', 'success');
        } else {
            setAlert('Failed to cancel reservations', 'danger');
        }
    }
    
    redirect(BASE_URL . '/pages/user/reservations.php');
    exit();
}

    if ($action === 'delete') {
        $id = (int) ($input['id'] ?? 0);
        $resData = $reservation->getById($id);

        if (!$resData) {
            setAlert('Reservation not found', 'danger');
        } else if ($resData['user_id'] != $_SESSION['user_id'] && !isAdmin()) {
            setAlert('Unauthorized action', 'danger');
        } else if (isAdmin() && $reservation->delete($id)) {
            setAlert('Reservation deleted successfully', 'success');
        } else {
            setAlert('Failed to delete reservation', 'danger');
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