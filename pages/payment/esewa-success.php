<?php
require_once '../../config/database.php';
require_once '../../config/constants.php';
require_once '../../includes/session.php';
require_once '../../models/Reservation.php';

// Create log file for debugging
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

// Get eSewa response
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

// Check payment status
if (!isset($decodedData['status']) || $decodedData['status'] !== 'COMPLETE') {
    logDebug("ERROR: Payment not completed. Status: " . ($decodedData['status'] ?? 'not set'));
    setAlert('Payment was not completed. Please try again.', 'danger');
    redirect(BASE_URL . '/pages/public/viewbus.php');
    exit();
}

logDebug("Payment status: COMPLETE");

// Initialize database
$database = new Database();
$db = $database->getConnection();
$reservation = new Reservation($db);

// Get pending reservation from session
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

// Verify transaction UUID matches
$responseUuid = $decodedData['transaction_uuid'] ?? '';
logDebug("Comparing UUIDs - Session: {$transactionUuid}, Response: {$responseUuid}");

if ($transactionUuid !== $responseUuid) {
    logDebug("ERROR: Transaction UUID mismatch");
    setAlert('Transaction verification failed. Please contact support with code: ' . $responseUuid, 'danger');
    redirect(BASE_URL . '/pages/public/viewbus.php');
    exit();
}

logDebug("Transaction UUID verified successfully");

// Check if seat is still available
$isAvailable = $reservation->isSeatAvailable(
    $pendingReservation['bus_id'],
    $pendingReservation['seat_number'],
    $pendingReservation['booking_date']
);

logDebug("Seat availability check: " . ($isAvailable ? 'Available' : 'Not available'));

if (!$isAvailable) {
    clearPendingReservation();
    logDebug("ERROR: Seat no longer available");
    setAlert('Sorry, this seat was just booked. Your payment will be refunded.', 'warning');
    redirect(BASE_URL . '/pages/public/viewbus.php');
    exit();
}

// Create reservation
$transactionCode = $decodedData['transaction_code'] ?? $responseUuid;
logDebug("Creating reservation with transaction code: " . $transactionCode);

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
    
    logDebug("Reservation creation result: " . ($reservationId ? "Success - ID: {$reservationId}" : "Failed"));
    
    if ($reservationId) {
        clearPendingReservation();
        logDebug("Reservation created successfully, redirecting to myreservations");
        setAlert('Payment successful! Your reservation is confirmed. Booking ID: ' . $reservationId, 'success');
        redirect(BASE_URL . '/pages/user/reservations.php');
        exit();
    } else {
        logDebug("ERROR: Reservation creation returned false/0");
        setAlert('Payment received but reservation failed. Contact support with transaction: ' . $transactionCode, 'danger');
        redirect(BASE_URL . '/pages/public/viewbus.php');
        exit();
    }


} catch (Exception $e) {
    logDebug("EXCEPTION during reservation creation: " . $e->getMessage());
    logDebug("Stack trace: " . $e->getTraceAsString());
    setAlert('An error occurred. Please contact support with transaction: ' . $transactionCode, 'danger');
    redirect(BASE_URL . '/pages/public/viewbus.php');
    exit();
}