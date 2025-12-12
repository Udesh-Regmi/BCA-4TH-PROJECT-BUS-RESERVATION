<?php
require_once 'config/constants.php';
require_once 'includes/session.php';

echo "<h1>Navigation Debug</h1>";
echo "<h2>Configuration:</h2>";
echo "BASE_URL: " . BASE_URL . "<br>";
echo "Session Status: " . (session_status() === PHP_SESSION_ACTIVE ? "Active" : "Inactive") . "<br>";
echo "Logged In: " . (isLoggedIn() ? "YES" : "NO") . "<br>";

if (isLoggedIn()) {
    echo "User: " . $_SESSION['user_name'] . "<br>";
    echo "Role: " . $_SESSION['role'] . "<br>";
}

echo "<h2>Test Links:</h2>";
echo "<a href='" . BASE_URL . "/index.php'>Home</a><br>";
echo "<a href='" . BASE_URL . "/pages/public/login.php'>Login</a><br>";
echo "<a href='" . BASE_URL . "/pages/public/viewbus.php'>View Buses</a><br>";
?>