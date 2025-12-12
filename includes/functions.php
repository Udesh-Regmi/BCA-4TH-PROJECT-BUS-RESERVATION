<?php
// includes/functions.php

/**
 * Sanitize input data
 */
function sanitize($data) {
    if (is_array($data)) {
        foreach ($data as $key => $value) {
            $data[$key] = sanitize($value);
        }
        return $data;
    }
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}
/**
 *  
 *  
 *  
 * Redirect to URL
 */
function redirect($url) {
    if (!headers_sent()) {
        header("Location: " . $url);
        exit();
    } else {
        echo "<script>window.location.href='" . $url . "';</script>";
        exit();
    }
}

/**
 * Set alert message in session
 */
function setAlert($message, $type = 'success') {
    $_SESSION['alert'] = [
        'message' => $message,
        'type' => $type
    ];
}

/**
 * Get and clear alert message
 */
function getAlert() {
    if (isset($_SESSION['alert'])) {
        $alert = $_SESSION['alert'];
        unset($_SESSION['alert']);
        return $alert;
    }
    return null;
}

/**
 * Display alert message
 */
function displayAlert() {
    $alert = getAlert();
    if ($alert) {
        $type = htmlspecialchars($alert['type']);
        $message = htmlspecialchars($alert['message']);
        echo "<div class='alert alert-{$type}' id='alert'>{$message}</div>";
    }
}

/**
 * Format date for display
 */
function formatDate($date) {
    if (empty($date)) return 'N/A';
    return date('M d, Y', strtotime($date));
}

/**
 * Format time for display
 */
function formatTime($time) {
    if (empty($time)) return 'N/A';
    return date('h:i A', strtotime($time));
}

/**
 * Format datetime for display
 */
function formatDateTime($datetime) {
    if (empty($datetime)) return 'N/A';
    return date('M d, Y h:i A', strtotime($datetime));
}

/**
 * Format currency
 */
function formatCurrency($amount) {
    return '$' . number_format($amount, 2);
}

/**
 * Generate random string
 */
function generateRandomString($length = 10) {
    return bin2hex(random_bytes($length / 2));
}

/**
 * Validate email
 */
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate phone number
 */
function isValidPhone($phone) {
    return preg_match('/^[\d\s\-\+\(\)]{10,}$/', $phone);
}

/**
 * Check if string contains only letters
 */
function isAlpha($string) {
    return preg_match('/^[a-zA-Z\s]+$/', $string);
}

/**
 * Check if string is alphanumeric
 */
function isAlphaNumeric($string) {
    return preg_match('/^[a-zA-Z0-9\s]+$/', $string);
}

/**
 * Get file extension
 */
function getFileExtension($filename) {
    return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
}

/**
 * Debug print (development only)
 */
function dd($data) {
    echo '<pre>';
    print_r($data);
    echo '</pre>';
    die();
}

/**
 * Safe output
 */
function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
?>