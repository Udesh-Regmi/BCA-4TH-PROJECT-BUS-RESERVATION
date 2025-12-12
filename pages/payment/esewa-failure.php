<?php
require_once '../../config/database.php';
require_once '../../config/constants.php';
require_once '../../includes/session.php';

unset($_SESSION['pending_reservation']);
unset($_SESSION['transaction_uuid']);

setAlert('Payment failed or cancelled. Please try again.', 'danger');
redirect(BASE_URL . '/pages/public/viewbus.php');