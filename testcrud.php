<?php
require_once 'config/database.php';
require_once 'config/constants.php';
require_once 'includes/session.php';

echo "<h1>CRUD Test</h1>";

// Test login first
if (!isLoggedIn()) {
    echo "<p style='color:red;'>❌ Not logged in. Please <a href='pages/public/login.php'>login first</a></p>";
    exit();
}

echo "<h2>✅ Logged in as: " . $_SESSION['user_name'] . " (" . $_SESSION['role'] . ")</h2>";

// Test links
echo "<h3>Test These Links:</h3>";
echo "<ul>";
echo "<li><a href='pages/user/profile.php'>Update Profile</a></li>";
echo "<li><a href='pages/public/viewbus.php'>View Buses</a></li>";
echo "<li><a href='pages/user/reservations.php'>My Reservations</a></li>";

if (isAdmin()) {
    echo "<li><a href='pages/admin/buses/create.php'>Add Bus (Admin)</a></li>";
    echo "<li><a href='pages/admin/buses/index.php'>Manage Buses (Admin)</a></li>";
}

echo "</ul>";
?>