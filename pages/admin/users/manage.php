<!-- PAGES/ADMIN/USERS/MANAGE.PHP -->
<?php
require_once '../../../config/database.php';
require_once '../../../config/constants.php';
require_once '../../../includes/session.php';
require_once '../../../includes/functions.php';
require_once '../../../models/User.php';
require_once '../../../models/Reservation.php';
require_once '../../../middleware/admin.php';

$userId = $_GET['id'] ?? null;
if (!$userId) {
    redirect(BASE_URL . '/pages/admin/users/index.php');
}

$database = new Database();
$db = $database->getConnection();
$user = new User($db);
$reservation = new Reservation($db);

$userData = $user->getById($userId);
$userReservations = $reservation->getByUserId($userId);

if (!$userData) {
    redirect(BASE_URL . '/pages/admin/users/index.php');
}

$pageTitle = "User Details - " . SITE_NAME;
$additionalCSS = "admin.css";
include '../../../UI/components/Header.php';
include '../../../UI/components/Navbar.php';
include '../../../UI/components/Alert.php';
?>

<div class="dashboard-layout">
    <?php include '../../../UI/components/Sidebar.php'; ?>
    
    <main class="dashboard-content">
        <h1>User Details</h1>
        
        <div class="form-card">
            <h2><?php echo $userData['name']; ?></h2>
            
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 2rem; margin: 2rem 0;">
                <div>
                    <h3 style="margin-bottom: 1rem; color: var(--primary);">Personal Information</h3>
                    <p><strong>Email:</strong> <?php echo $userData['email']; ?></p>
                    <p><strong>Phone:</strong> <?php echo $userData['phone'] ?? 'N/A'; ?></p>
                    <p><strong>Role:</strong> <span class="badge badge-<?php echo $userData['role']; ?>"><?php echo ucfirst($userData['role']); ?></span> </p>
                    <p><strong>Member Since:</strong> <?php echo formatDate($userData['created_at']); ?></p>
                </div>
                
                <div>
                    <h3 style="margin-bottom: 1rem; color: var(--primary);">Statistics</h3>
                    <p><strong>Total Reservations:</strong> <?php echo count($userReservations); ?></p>
                    <p><strong>Active Bookings:</strong> <?php echo count(array_filter($userReservations, fn($r) => $r['status'] === 'confirmed')); ?></p>
                    <p><strong>Cancelled:</strong> <?php echo count(array_filter($userReservations, fn($r) => $r['status'] === 'cancelled')); ?></p>
                </div>
            </div>
            
            <h3 style="margin: 2rem 0 1rem;">Reservation History</h3>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Bus</th>
                            <th>Route</th>
                            <th>Date</th>
                            <th>Seat</th>
                            <th>Payment Method</th>
                            <th>Transaction ID</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($userReservations)): ?>
                            <tr><td colspan="9" class="text-center">No reservations found</td></tr>
                        <?php else: ?>
                            <?php foreach ($userReservations as $res): ?>
                                <tr>
                                    <td><?php echo $res['id']; ?></td>
                                    <td><?php echo $res['bus_name']; ?></td>
                                    <td><?php echo $res['route_from'] . ' → ' . $res['route_to']; ?></td>
                                    <td><?php echo formatDate($res['booking_date']); ?></td>
                                    <td><?php echo $res['seat_number']; ?></td>
                                    <td><?php echo $res['payment_method']?></td>
                                    <td><?php echo $res['transaction_id']?></td>
                                    <td>Rs <?php echo number_format($res['total_amount'], 2); ?></td>
                                    <td><span class="badge badge-<?php echo $res['status']; ?>"><?php echo ucfirst($res['status']); ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="form-actions" style="margin-top: 2rem;">
                <a href="index.php" class="btn-secondary">Back to Users</a>
            </div>
        </div>
    </main>
</div>

<?php include '../../../UI/components/Footer.php'; ?>
