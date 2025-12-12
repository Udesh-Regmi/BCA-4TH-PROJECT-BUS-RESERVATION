<?php
require_once '../../config/database.php';
require_once '../../config/constants.php';
require_once '../../includes/session.php';
require_once '../../models/Reservation.php';

$data = base64_decode($_GET['data'] ?? '');
$decodedData = json_decode($data, true);

if ($decodedData && $decodedData['status'] === 'COMPLETE') {
    $database = new Database();
    $db = $database->getConnection();
    $reservation = new Reservation($db);
    
    $pendingReservation = getPendingReservation();
    
    if ($pendingReservation) {
        $reservationId = $reservation->create(
            $pendingReservation['bus_id'],
            getUserId(),
            $pendingReservation['seat_number'],
            $pendingReservation['booking_date'],
            $pendingReservation['passenger_name'],
            $pendingReservation['passenger_phone'],
            $pendingReservation['amount'],
            $decodedData['transaction_code'],
            'esewa'
        );
        
        if ($reservationId) {
            clearPendingReservation();
            setAlert('Payment successful! Your reservation is confirmed.', 'success');
            redirect(BASE_URL . '/pages/user/reservations.php');
        }
    }
}

setAlert('Payment verification failed. Please contact support.', 'danger');
redirect(BASE_URL . '/pages/public/viewbus.php');