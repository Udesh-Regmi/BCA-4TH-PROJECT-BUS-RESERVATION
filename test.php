<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Configuration Test</h1>";

// Test 1: Check files exist
echo "<h2>1. File Existence Check:</h2>";
$files = [
    'config/constants.php',
    'config/database.php',
    'includes/session.php',
    'includes/functions.php',
    'UI/components/Navbar.php',
    'UI/css/style.css'
];

foreach ($files as $file) {
    if (file_exists($file)) {
        echo "✅ $file<br>";
    } else {
        echo "❌ $file <strong>MISSING</strong><br>";
    }
}

// Test 2: Load constants
echo "<h2>2. Constants Check:</h2>";
if (file_exists('config/constants.php')) {
    require_once 'config/constants.php';
    echo "✅ BASE_URL: " . (defined('BASE_URL') ? BASE_URL : 'NOT DEFINED') . "<br>";
    echo "✅ SITE_NAME: " . (defined('SITE_NAME') ? SITE_NAME : 'NOT DEFINED') . "<br>";
} else {
    echo "❌ constants.php not found<br>";
}

// Test 3: Load session functions
echo "<h2>3. Session Functions Check:</h2>";
if (file_exists('includes/session.php')) {
    require_once 'includes/session.php';
    if (function_exists('isLoggedIn')) {
        echo "✅ isLoggedIn() function exists<br>";
        echo "✅ User logged in: " . (isLoggedIn() ? "YES" : "NO") . "<br>";
    } else {
        echo "❌ isLoggedIn() function NOT found<br>";
    }
    
    if (function_exists('isAdmin')) {
        echo "✅ isAdmin() function exists<br>";
    } else {
        echo "❌ isAdmin() function NOT found<br>";
    }
} else {
    echo "❌ session.php not found<br>";
}

// Test 4: Load helper functions
echo "<h2>4. Helper Functions Check:</h2>";
if (file_exists('includes/functions.php')) {
    require_once 'includes/functions.php';
    if (function_exists('sanitize')) {
        echo "✅ sanitize() function exists<br>";
        echo "✅ Test: " . sanitize('<script>alert("test")</script>') . "<br>";
    } else {
        echo "❌ sanitize() function NOT found<br>";
    }
    
    if (function_exists('formatDate')) {
        echo "✅ formatDate() function exists<br>";
        echo "✅ Test: " . formatDate('2024-12-07') . "<br>";
    } else {
        echo "❌ formatDate() function NOT found<br>";
    }
} else {
    echo "❌ functions.php not found<br>";
}

// Test 5: Database connection
echo "<h2>5. Database Connection Check:</h2>";
if (file_exists('config/database.php')) {
    require_once 'config/database.php';
    try {
        $db = new Database();
        $conn = $db->getConnection();
        if ($conn) {
            echo "✅ Database connected successfully<br>";
            
            // Check if tables exist
            $tables = ['users', 'buses', 'reservations'];
            foreach ($tables as $table) {
                $stmt = $conn->prepare("SHOW TABLES LIKE '$table'");
                $stmt->execute();
                if ($stmt->rowCount() > 0) {
                    echo "✅ Table '$table' exists<br>";
                } else {
                    echo "❌ Table '$table' NOT found<br>";
                }
            }
        } else {
            echo "❌ Database connection failed<br>";
        }
    } catch (Exception $e) {
        echo "❌ Database error: " . $e->getMessage() . "<br>";
    }
} else {
    echo "❌ database.php not found<br>";
}

// Test 6: Check permissions
echo "<h2>6. File Permissions:</h2>";
$checkDirs = ['config', 'includes', 'UI', 'pages'];
foreach ($checkDirs as $dir) {
    if (is_readable($dir)) {
        echo "✅ $dir/ is readable<br>";
    } else {
        echo "❌ $dir/ is NOT readable<br>";
    }
}

echo "<h2>7. PHP Info:</h2>";
echo "✅ PHP Version: " . phpversion() . "<br>";
echo "✅ Server: " . $_SERVER['SERVER_SOFTWARE'] . "<br>";

echo "<hr>";
echo "<h3>Next Steps:</h3>";
echo "<ul>";
echo "<li><a href='pages/public/home.php'>Go to Home Page</a></li>";
echo "<li><a href='pages/public/login.php'>Go to Login</a></li>";
echo "<li><a href='pages/public/register.php'>Go to Register</a></li>";
echo "</ul>";
?>