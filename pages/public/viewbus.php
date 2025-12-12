<?php
require_once '../../config/database.php';
require_once '../../config/constants.php';
require_once '../../includes/session.php';
require_once '../../includes/functions.php';
require_once '../../models/Bus.php';

$database = new Database();
$db = $database->getConnection();
$bus = new Bus($db);

// Search functionality
$from = $_GET['from'] ?? '';
$to = $_GET['to'] ?? '';

if ($from && $to) {
    $buses = $bus->search($from, $to);
    setAlert('Search results for buses from ' . htmlspecialchars($from) . ' to ' . htmlspecialchars($to), 'success');
} else {
    $buses = $bus->getAll('active');
    setAlert('Showing all available buses', 'info');
}

$pageTitle = "View Buses - " . SITE_NAME;
include '../../UI/components/Header.php';
include '../../UI/components/Navbar.php';
include '../../UI/components/Alert.php';
?>

<div class="page-container">
    <div class="container">
        <h1 class="page-title">Available Buses</h1>
        
        <div class="search-box">
            <form method="GET" class="search-form">
                <input type="text" name="from" placeholder="From (e.g., New York)" value="<?php echo htmlspecialchars($from); ?>">
                <input type="text" name="to" placeholder="To (e.g., Boston)" value="<?php echo htmlspecialchars($to); ?>">
                <button type="submit" class="btn-primary"><i class="fas fa-search"></i> Search</button>
            </form>
        </div>
        
        <div class="bus-grid">
            <?php if (empty($buses)): ?>
                <p class="no-data">No buses available for this route</p>
            <?php else: ?>
                <?php foreach ($buses as $busItem): ?>
                    <div class="bus-card">
                        <div class="bus-image">
                            <img src="<?php echo htmlspecialchars($busItem['image_string']); ?>" alt="Bus Image">
                        </div>
                        <div class="bus-header">
                            <h3><?php echo htmlspecialchars($busItem['bus_name']); ?></h3>
                            <span class="bus-number"><?php echo htmlspecialchars($busItem['bus_number']); ?></span>
                        </div>
                        <div class="bus-route">
                            <div class="route-point">
                                <i class="fas fa-map-marker-alt"></i>
                                <span><?php echo htmlspecialchars($busItem['route_from']); ?></span>
                            </div>
                            <div class="route-arrow">→</div>
                            <div class="route-point">
                                <i class="fas fa-map-marker-alt"></i>
                                <span><?php echo htmlspecialchars($busItem['route_to']); ?></span>
                            </div>
                        </div>
                        <div class="bus-details">
                            <div class="detail-item">
                                <i class="fas fa-clock"></i>
                                <span><?php echo formatTime($busItem['departure_time']); ?></span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-chair"></i>
                                <span><?php echo $busItem['available_seats']; ?> seats</span>
                            </div>
                            <div class="detail-item price">
                                Rs. 
                                <span><?php echo number_format($busItem['price'], 2); ?></span>
                            </div>
                        </div>
                        <?php if (isLoggedIn() && !isAdmin()): ?>
                            <a href="<?php echo BASE_URL; ?>/pages/user/makereservation.php?bus_id=<?php echo $busItem['id']; ?>" class="btn-book">Book Now</a>
                        <?php elseif (!isLoggedIn()): ?>
                            <a href="<?php echo BASE_URL; ?>/pages/public/login.php" class="btn-book">Login to Book</a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../../UI/components/Footer.php'; ?>