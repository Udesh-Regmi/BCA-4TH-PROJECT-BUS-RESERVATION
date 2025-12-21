<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../includes/session.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);
$action = $input['action'] ?? '';

if ($action === 'generate_signature') {
    $totalAmount = $input['total_amount'] ?? 0;
    $transactionUuid = $input['transaction_uuid'] ?? '';
    $productCode = 'EPAYTEST';
    
    // eSewa secret key (get from environment or config in production)
    $secretKey = '8gBm/:&EnhH.1/q';
    
    // Generate signature
    $message = "total_amount={$totalAmount},transaction_uuid={$transactionUuid},product_code={$productCode}";
    $signature = base64_encode(hash_hmac('sha256', $message, $secretKey, true));
    
    // Store reservation data in session
    if (isset($input['reservation_data'])) {
        $reservationData = $input['reservation_data'];
        
        // Handle both single and multiple seats
        if (isset($reservationData['seat_numbers'])) {
            // Multiple seats
            setPendingReservation([
                'bus_id' => $reservationData['bus_id'],
                'seat_numbers' => $reservationData['seat_numbers'],
                'booking_date' => $reservationData['booking_date'],
                'passenger_name' => $reservationData['passenger_name'],
                'passenger_phone' => $reservationData['passenger_phone'],
                'amount' => $reservationData['amount'],
                'payment_method' => 'esewa'
            ]);
        } else {
            // Single seat (legacy)
            setPendingReservation([
                'bus_id' => $reservationData['bus_id'],
                'seat_number' => $reservationData['seat_number'],
                'booking_date' => $reservationData['booking_date'],
                'passenger_name' => $reservationData['passenger_name'],
                'passenger_phone' => $reservationData['passenger_phone'],
                'amount' => $reservationData['amount'],
                'payment_method' => 'esewa'
            ]);
        }
        
        setTransactionUuid($transactionUuid);
    }
    
    echo json_encode([
        'success' => true,
        'signature' => $signature,
        'message' => $message
    ]);
    exit();
}

echo json_encode(['success' => false, 'message' => 'Invalid action']);
?>