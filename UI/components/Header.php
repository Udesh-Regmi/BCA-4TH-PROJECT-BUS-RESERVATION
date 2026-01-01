<?php
// Ensure session and config are loaded
if (!defined('BASE_URL')) {
    require_once __DIR__ . '/../../config/constants.php';
}
if (!function_exists('isLoggedIn')) {
    require_once __DIR__ . '/../../includes/session.php';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Bus Reservation System - Book your bus tickets online">
    <title><?php echo $pageTitle ?? SITE_NAME; ?></title>
    
    <!-- Main CSS -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/UI/css/style.css">
    
    <!-- Additional CSS based on page type -->
    <?php if (isset($additionalCSS)): ?>
        <link rel="stylesheet" href="<?php echo BASE_URL; ?>/UI/css/<?php echo $additionalCSS; ?>">
    <?php endif; ?>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Favicon (optional) -->
    <link rel="icon" type="image/png" href="<?php echo BASE_URL; ?>/UI/images/favicon.png">
</head>
<body> 