<?php
// includes/session.php

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Check if user is admin
 */
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

/**
 * Check if user is regular user
 */
function isUser() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'user';
}

/**
 * Require user to be logged in (redirect if not)
 */
function requireLogin() {
    if (!isLoggedIn()) {
        if (defined('BASE_URL')) {
            header("Location: " . BASE_URL . "/pages/public/login.php");
        } else {
            header("Location: /BusReservation/pages/public/login.php");
        }
        exit();
    }
}

/**
 * Require user to be admin (redirect if not)
 */
function requireAdmin() {
    requireLogin();
    if (!isAdmin()) {
        if (defined('BASE_URL')) {
            header("Location: " . BASE_URL . "/pages/public/login.php");
        } else {
            header("Location: /BusReservation/pages/public/login.php");
        }
        exit();
    }
}

/**
 * Get current user ID
 */
function getUserId() {
    return $_SESSION['user_id'] ?? null;
}

/**
 * Get current user name
 */
function getUserName() {
    return $_SESSION['user_name'] ?? 'Guest';
}

/**
 * Get current user email
 */
function getUserEmail() {
    return $_SESSION['user_email'] ?? '';
}

/**
 * Get current user role
 */
function getUserRole() {
    return $_SESSION['role'] ?? 'guest';
}


/**
 * Store pending reservation data for payment processing
 */
function setPendingReservation($data) {
    $_SESSION['pending_reservation'] = $data;
}

/**
 * Get pending reservation data
 */
function getPendingReservation() {
    return $_SESSION['pending_reservation'] ?? null;
}

/**
 * Clear pending reservation data
 */
function clearPendingReservation() {
    unset($_SESSION['pending_reservation']);
    unset($_SESSION['transaction_uuid']);
}

/**
 * Set transaction UUID for payment tracking
 */
function setTransactionUuid($uuid) {
    $_SESSION['transaction_uuid'] = $uuid;
}

/**
 * Get transaction UUID
 */
function getTransactionUuid() {
    return $_SESSION['transaction_uuid'] ?? null;
}

/**
 * Logout user
 */
function logout() {
    session_unset();
    session_destroy();
    if (defined('BASE_URL')) {
        header("Location: " . BASE_URL . "/pages/public/login.php");
    } else {
        header("Location: /BusReservation/pages/public/login.php");
    }
    exit();
}
?>