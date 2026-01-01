<!-- PAGES/USER/RESERVATION_HISTORY.PHP -->
<?php
require_once '../../config/database.php';
require_once '../../config/constants.php';
require_once '../../includes/session.php';
require_once '../../includes/functions.php';
require_once '../../models/Reservation.php';
require_once '../../middleware/auth.php';

$database = new Database();
$db = $database->getConnection();
$reservation = new Reservation($db);

$filter = $_GET['filter'] ?? 'all';
$userReservations = $reservation->getByUserId($_SESSION['user_id']);

if ($filter !== 'all') {
    $userReservations = array_filter($userReservations, function($res) use ($filter) {
        return $res['status'] === $filter;
    });
    setAlert('Your Reservation History Filtered', 'success');
}

$pageTitle = "Reservation History - " . SITE_NAME;
$additionalCSS = "user.css";
include '../../UI/components/Header.php';
include '../../UI/components/Navbar.php';
include '../../UI/components/Alert.php';
?>

<div class="dashboard-layout">
    <?php include '../../UI/components/Sidebar.php'; ?>
    
    <main class="dashboard-content">
        <h1>Reservation History</h1>
        
        <div class="filter-tabs" style="margin-bottom: 2rem;">
            <a href="?filter=all" class="filter-tab <?php echo $filter === 'all' ? 'active' : ''; ?>">All</a>
            <a href="?filter=confirmed" class="filter-tab <?php echo $filter === 'confirmed' ? 'active' : ''; ?>">Confirmed</a>
            <a href="?filter=cancelled" class="filter-tab <?php echo $filter === 'cancelled' ? 'active' : ''; ?>">Cancelled</a>
            <a href="?filter=pending" class="filter-tab <?php echo $filter === 'pending' ? 'active' : ''; ?>">Pending</a>
        </div>
        
        <div class="reservations-list">
            <?php if (empty($userReservations)): ?>
                <div class="no-data">No reservations found</div>
            <?php else: ?>
                <?php foreach ($userReservations as $res): ?>
                    <div class="reservation-card">
                        <div class="reservation-header">
                            <h3><?php echo $res['bus_name']; ?> (<?php echo $res['bus_number']; ?>)</h3>
                            <span class="badge badge-<?php echo $res['status']; ?>"><?php echo ucfirst($res['status']); ?></span>
                        </div>
                        <div class="reservation-details">
                            <div class="detail-row">
                                <span class="label">Route:</span>
                                <span class="value"><?php echo $res['route_from'] . ' → ' . $res['route_to']; ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Travel Date:</span>
                                <span class="value"><?php echo formatDate($res['booking_date']); ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Departure:</span>
                                <span class="value"><?php echo formatTime($res['departure_time']); ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Seat Number:</span>
                                <span class="value"><?php echo $res['seat_number']; ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Passenger:</span>
                                <span class="value"><?php echo $res['passenger_name']; ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Amount:</span>
                                <span class="value price">$<?php echo number_format($res['total_amount'], 2); ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Booked On:</span>
                                <span class="value"><?php echo formatDate($res['created_at']); ?></span>
                            </div>
                        </div>
                        <?php if ($res['status'] === 'confirmed'): ?>
                            <form method="POST" action="<?php echo BASE_URL; ?>/controllers/ReservationController.php" class="reservation-actions">
                                <input type="hidden" name="action" value="cancel">
                                <input type="hidden" name="id" value="<?php echo $res['id']; ?>">
                                <button type="submit" class="btn-danger" onclick="return confirm('Cancel this reservation?')">Cancel Booking</button>
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>
</div>

<?php include '../../UI/components/Footer.php'; ?>