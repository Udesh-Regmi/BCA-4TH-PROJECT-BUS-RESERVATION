<?php
require_once '../../config/database.php';
require_once '../../config/constants.php';
require_once '../../includes/session.php';
require_once '../../models/Reservation.php';
require_once '../../models/Bus.php';

$logFile = __DIR__ . '/../../logs/esewa-success.log';
if (!is_dir(__DIR__ . '/../../logs')) {
    mkdir(__DIR__ . '/../../logs', 0777, true);
}

function logDebug($message) {
    global $logFile;
    file_put_contents($logFile, date('Y-m-d H:i:s') . " - " . $message . "\n", FILE_APPEND);
}

logDebug("=== eSewa Success Callback Started ===");
logDebug("GET params: " . print_r($_GET, true));
logDebug("Session ID: " . session_id());
logDebug("User ID: " . getUserId());

$pageTitle = "Payment Processing - " . SITE_NAME;

$data = $_GET['data'] ?? '';
logDebug("Encoded data received: " . ($data ? 'Yes' : 'No'));

if (empty($data)) {
    logDebug("ERROR: No data parameter received");
    setAlert('Invalid payment response. Please contact support.', 'danger');
    redirect(BASE_URL . '/pages/public/viewbus.php');
    exit();
}

$decodedData = json_decode(base64_decode($data), true);
logDebug("Decoded payment data: " . print_r($decodedData, true));

if (!$decodedData) {
    logDebug("ERROR: Failed to decode payment data");
    setAlert('Invalid payment data. Please contact support.', 'danger');
    redirect(BASE_URL . '/pages/public/viewbus.php');
    exit();
}

if (!isset($decodedData['status']) || $decodedData['status'] !== 'COMPLETE') {
    logDebug("ERROR: Payment not completed. Status: " . ($decodedData['status'] ?? 'not set'));
    setAlert('Payment was not completed. Please try again.', 'danger');
    redirect(BASE_URL . '/pages/public/viewbus.php');
    exit();
}

logDebug("Payment status: COMPLETE");

$database = new Database();
$db = $database->getConnection();
$reservation = new Reservation($db);
$bus = new Bus($db);

$pendingReservation = getPendingReservation();
$transactionUuid = getTransactionUuid();

logDebug("Pending reservation from session: " . print_r($pendingReservation, true));
logDebug("Transaction UUID from session: " . $transactionUuid);

if (!$pendingReservation) {
    logDebug("ERROR: No pending reservation in session");
    setAlert('Reservation data not found. Please try booking again.', 'danger');
    redirect(BASE_URL . '/pages/public/viewbus.php');
    exit();
}

$responseUuid = $decodedData['transaction_uuid'] ?? '';
logDebug("Comparing UUIDs - Session: {$transactionUuid}, Response: {$responseUuid}");

if ($transactionUuid !== $responseUuid) {
    logDebug("ERROR: Transaction UUID mismatch");
    setAlert('Transaction verification failed. Please contact support with code: ' . $responseUuid, 'danger');
    redirect(BASE_URL . '/pages/public/viewbus.php');
    exit();
}

logDebug("Transaction UUID verified successfully");

$transactionCode = $decodedData['transaction_code'] ?? $responseUuid;
logDebug("Transaction code: " . $transactionCode);

// Handle multiple seats
if (isset($pendingReservation['seat_numbers'])) {
    $seatNumbers = is_array($pendingReservation['seat_numbers']) 
        ? $pendingReservation['seat_numbers'] 
        : array_map('intval', explode(',', $pendingReservation['seat_numbers']));
    
    logDebug("Multiple seats reservation: " . implode(',', $seatNumbers));
    
    // Check if all seats are still available
    if (!$reservation->areSeatsAvailable($pendingReservation['bus_id'], $seatNumbers, $pendingReservation['booking_date'])) {
        clearPendingReservation();
        logDebug("ERROR: One or more seats no longer available");
        setAlert('Sorry, one or more seats were just booked. Your payment will be refunded.', 'warning');
        redirect(BASE_URL . '/pages/public/viewbus.php');
        exit();
    }
    
    // Get bus price
    $busData = $bus->getById($pendingReservation['bus_id']);
    if (!$busData) {
        logDebug("ERROR: Bus not found");
        setAlert('Bus information not found. Contact support with transaction: ' . $transactionCode, 'danger');
        redirect(BASE_URL . '/pages/public/viewbus.php');
        exit();
    }
    
    try {
        $reservationIds = $reservation->createMultiple(
            $pendingReservation['bus_id'],
            getUserId(),
            $seatNumbers,
            $pendingReservation['booking_date'],
            $pendingReservation['passenger_name'],
            $pendingReservation['passenger_phone'],
            $busData['price'],
            $transactionCode,
            'esewa'
        );
        
        logDebug("Multiple reservations result: " . print_r($reservationIds, true));
        
        if ($reservationIds && is_array($reservationIds)) {
            $bus->updateSeats($pendingReservation['bus_id'], count($seatNumbers));
            clearPendingReservation();
            logDebug("Multiple reservations created successfully");
            setAlert('Payment successful! Your ' . count($seatNumbers) . ' seat(s) are confirmed.', 'success');
            redirect(BASE_URL . '/pages/user/reservations.php');
            exit();
        } else {
            logDebug("ERROR: Multiple reservation creation failed");
            setAlert('Payment received but reservation failed. Contact support with transaction: ' . $transactionCode, 'danger');
            redirect(BASE_URL . '/pages/public/viewbus.php');
            exit();
        }
    } catch (Exception $e) {
        logDebug("EXCEPTION during multiple reservation creation: " . $e->getMessage());
        logDebug("Stack trace: " . $e->getTraceAsString());
        setAlert('An error occurred. Please contact support with transaction: ' . $transactionCode, 'danger');
        redirect(BASE_URL . '/pages/public/viewbus.php');
        exit();
    }
    
} else {
    // Single seat (legacy support)
    $isAvailable = $reservation->isSeatAvailable(
        $pendingReservation['bus_id'],
        $pendingReservation['seat_number'],
        $pendingReservation['booking_date']
    );
    
    logDebug("Single seat availability check: " . ($isAvailable ? 'Available' : 'Not available'));
    
    if (!$isAvailable) {
        clearPendingReservation();
        logDebug("ERROR: Seat no longer available");
        setAlert('Sorry, this seat was just booked. Your payment will be refunded.', 'warning');
        redirect(BASE_URL . '/pages/public/viewbus.php');
        exit();
    }
    
    try {
        $reservationId = $reservation->create(
            $pendingReservation['bus_id'],
            getUserId(),
            $pendingReservation['seat_number'],
            $pendingReservation['booking_date'],
            $pendingReservation['passenger_name'],
            $pendingReservation['passenger_phone'],
            $pendingReservation['amount'],
            $transactionCode,
            'esewa'
        );
        
        logDebug("Single reservation result: " . ($reservationId ? "Success - ID: {$reservationId}" : "Failed"));
        
        if ($reservationId) {
            $bus->updateSeats($pendingReservation['bus_id'], 1);
            clearPendingReservation();
            logDebug("Single reservation created successfully");
            setAlert('Payment successful! Your reservation is confirmed. Booking ID: ' . $reservationId, 'success');
            redirect(BASE_URL . '/pages/user/reservations.php');
            exit();
        } else {
            logDebug("ERROR: Single reservation creation failed");
            setAlert('Payment received but reservation failed. Contact support with transaction: ' . $transactionCode, 'danger');
            redirect(BASE_URL . '/pages/public/viewbus.php');
            exit();
        }
    } catch (Exception $e) {
        logDebug("EXCEPTION during single reservation creation: " . $e->getMessage());
        logDebug("Stack trace: " . $e->getTraceAsString());
        setAlert('An error occurred. Please contact support with transaction: ' . $transactionCode, 'danger');
        redirect(BASE_URL . '/pages/public/viewbus.php');
        exit();
    }
}
?>