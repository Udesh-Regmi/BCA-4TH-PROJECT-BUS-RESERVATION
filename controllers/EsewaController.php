<?php
require_once '../config/database.php';
require_once '../config/constants.php';
require_once '../includes/session.php';

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

if ($input['action'] === 'generate_signature') {
    $totalAmount = $input['total_amount'];
    $transactionUuid = $input['transaction_uuid'];
    $productCode = 'EPAYTEST';
    
    // Store reservation data in session for later use
    $_SESSION['pending_reservation'] = $input['reservation_data'];
    $_SESSION['transaction_uuid'] = $transactionUuid;
    
    // Generate signature using HMAC SHA256
    $secret = '8gBm/:&EnhH.1/q'; // eSewa test secret key
    $message = "total_amount={$totalAmount},transaction_uuid={$transactionUuid},product_code={$productCode}";
    $signature = base64_encode(hash_hmac('sha256', $message, $secret, true));
    
    echo json_encode(['signature' => $signature, 'transaction_uuid' => $transactionUuid]);
} else {
    echo json_encode(['error' => 'Invalid action']);
}