<?php
require_once 'config/database.php';
require_once 'config/constants.php';
require_once 'includes/session.php';

echo "<h1>Session Debug</h1>";
echo "<pre>";
echo "Session ID: " . session_id() . "\n\n";
echo "All Session Data:\n";
print_r($_SESSION);
echo "\n\nPending Reservation: ";
print_r(getPendingReservation());
echo "\n\nTransaction UUID: ";
print_r(getTransactionUuid());
echo "</pre>";