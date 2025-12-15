<?php
require_once '../config/database.php';
require_once '../config/constants.php';
require_once '../includes/session.php';

// Enable error logging
ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

error_log("EsewaController - Input: " . print_r($input, true));

if (!isset($input['action'])) {
    echo json_encode(['error' => 'No action specified']);
    exit();
}

if ($input['action'] === 'generate_signature') {
    $totalAmount = $input['total_amount'] ?? 0;
    $transactionUuid = $input['transaction_uuid'] ?? '';
    $productCode = 'EPAYTEST';
    
    if (empty($totalAmount) || empty($transactionUuid)) {
        echo json_encode(['error' => 'Missing required parameters']);
        exit();
    }
    
    // Store reservation data in session using helper functions
    setPendingReservation($input['reservation_data']);
    setTransactionUuid($transactionUuid);
    
    error_log("Stored in session - Reservation: " . print_r($input['reservation_data'], true));
    error_log("Stored in session - UUID: " . $transactionUuid);
    
    // Generate signature using HMAC SHA256
    $secret = '8gBm/:&EnhH.1/q'; // eSewa test secret key
    $message = "total_amount={$totalAmount},transaction_uuid={$transactionUuid},product_code={$productCode}";
    $signature = base64_encode(hash_hmac('sha256', $message, $secret, true));
    
    error_log("Generated signature: " . $signature);
    
    echo json_encode([
        'success' => true,
        'signature' => $signature,
        'transaction_uuid' => $transactionUuid
    ]);
} else {
    echo json_encode(['error' => 'Invalid action']);
}