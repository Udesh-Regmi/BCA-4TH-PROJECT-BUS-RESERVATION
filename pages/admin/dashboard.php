<!-- PAGES/ADMIN/DASHBOARD.PHP -->
<?php
require_once '../../config/database.php';
require_once '../../config/constants.php';
require_once '../../includes/session.php';
require_once '../../includes/functions.php';
require_once '../../models/Bus.php';
require_once '../../models/Reservation.php';
require_once '../../models/User.php';
require_once '../../middleware/admin.php';

$database = new Database();
$db = $database->getConnection();
$bus = new Bus($db);
$reservation = new Reservation($db);
$user = new User($db);

$totalBuses = count($bus->getAll());
$totalReservations = count($reservation->getAll());
$totalUsers = count($user->getAll());
$recentReservations = array_slice($reservation->getAll(), 0, 5);

$pageTitle = "Admin Dashboard - " . SITE_NAME;
$additionalCSS = "admin.css";
include '../../UI/components/Header.php';
include '../../UI/components/Navbar.php';
include '../../UI/components/Alert.php';
?>

<div class="dashboard-layout">
    <?php include '../../UI/components/Sidebar.php'; ?>

    <main class="dashboard-content">
        <h1>Admin Dashboard</h1>

        <div class="stats-grid">
            <div class="stat-card">
                <i class="fas fa-bus"></i>
                <div class="stat-info">
                    <h3><?php echo $totalBuses; ?></h3>
                    <p>Total Buses</p>
                </div>
            </div>
            <div class="stat-card">
                <i class="fas fa-ticket-alt"></i>
                <div class="stat-info">
                    <h3><?php echo $totalReservations; ?></h3>
                    <p>Total Reservations</p>
                </div>
            </div>
            <div class="stat-card">
                <i class="fas fa-users"></i>
                <div class="stat-info">
                    <h3><?php echo $totalUsers; ?></h3>
                    <p>Total Users</p>
                </div>
            </div>
        </div>

        <div class="recent-section">
            <h2>Recent Reservations</h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Passenger</th>
                            <th>Passenger Phone</th>
                            <th>Passenger Email</th>
                            <th>Bus</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Payment Method</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($recentReservations)): ?>
                            <tr>
                                <td colspan="9" class="text-center">No reservations yet</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($recentReservations as $res): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($res['id']); ?></td>
                                    <td><?php echo htmlspecialchars($res['user_name']); ?></td>
                                    <td><?php echo htmlspecialchars($res['passenger_phone']); ?></td>
                                    <td><?php echo htmlspecialchars($res['email']); ?></td>
                                    <td><?php echo htmlspecialchars($res['bus_name']); ?></td>
                                    <td><?php echo formatDate($res['booking_date']); ?></td>
                                    <td>Rs. <?php echo number_format($res['total_amount'], 2); ?></td>
                                    <td><?php echo htmlspecialchars($res['payment_method']); ?></td>
                                    <td>
                                        <span class="badge badge-<?php echo htmlspecialchars($res['status']); ?>">
                                            <?php echo ucfirst(htmlspecialchars($res['status'])); ?>
                                        </span>
                                    </td>
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