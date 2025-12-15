<?php
require_once '../../config/database.php';
require_once '../../config/constants.php';
require_once '../../includes/session.php';

error_log("eSewa Failure - GET data: " . print_r($_GET, true));

clearPendingReservation();

setAlert('Payment failed or was cancelled. Please try again.', 'danger');
redirect(BASE_URL . '/pages/public/viewbus.php');