<!-- PAGES/USER/DASHBOARD.PHP -->
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

$userReservations = $reservation->getByUserId($_SESSION['user_id']);
$totalReservations = count($userReservations);
$activeReservations = count(array_filter($userReservations, fn($r) => $r['status'] === 'confirmed'));

$pageTitle = "User Dashboard - " . SITE_NAME;
$additionalCSS = "user.css";  // Loads UI/css/user.css
include '../../UI/components/Header.php';
include '../../UI/components/Navbar.php';
include '../../UI/components/Alert.php';
?>

<div class="dashboard-layout">
    <?php include '../../UI/components/Sidebar.php'; ?>
    
    <main class="dashboard-content">
        <h1 class="user-welcome-message">Welcome, <?php echo $_SESSION['user_name']; ?>!</h1>
        
        <div class="stats-grid">
            <div class="stat-card">
                <i class="fas fa-ticket-alt"></i>
                <div class="stat-info">
                    <h3><?php echo $totalReservations; ?></h3>
                    <p>Total Reservations</p>
                </div>
            </div>
            <div class="stat-card">
                <i class="fas fa-check-circle"></i>
                <div class="stat-info">
                    <h3><?php echo $activeReservations; ?></h3>
                    <p>Active Bookings</p>
                </div>
            </div>
        </div>
        
        <div class="recent-section">
            <h2>Recent Reservations</h2>
            <hr>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Bus No:</th>
                            <th>Bus</th>
                            <th>Route</th>
                            <th>Date</th>
                            <th>Seat No.</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($userReservations)): ?>
                            <tr><td colspan="5" class="text-center">No reservations found</td></tr>
                        <?php else: ?>
                            <?php foreach (array_slice($userReservations, 0, 5) as $res): ?>
                                <tr>
                                    <td><?php echo $res['bus_number']; ?></td>
                                    <td><?php echo $res['bus_name']; ?></td>
                                    <td><?php echo $res['route_from'] . ' → ' . $res['route_to']; ?></td>
                                    <td><?php echo formatDate($res['booking_date']); ?></td>
                                    <td><?php echo $res['seat_number']; ?></td>
                                    <td><span class="badge badge-<?php echo $res['status']; ?>"><?php echo ucfirst($res['status']); ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<?php include '../../UI/components/Footer.php'; ?>