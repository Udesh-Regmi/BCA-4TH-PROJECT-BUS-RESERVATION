<?php
require_once '../../../config/database.php';
require_once '../../../config/constants.php';
require_once '../../../includes/session.php';
require_once '../../../includes/functions.php';
require_once '../../../models/Reservation.php';
require_once '../../../middleware/admin.php';

// Initialize DB
$database = new Database();
$db = $database->getConnection();
$reservationModel = new Reservation($db);

// Get reservation ID from URL
$reservationId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$reservationId) {
    redirect(BASE_URL . '/pages/admin/reservations/index.php');
    exit();
}

// Fetch reservation
$reservation = $reservationModel->getById($reservationId);

if (!$reservation) {
    redirect(BASE_URL . '/pages/admin/reservations/index.php');
    exit();
}

// Page meta
$pageTitle = "View Reservation - " . SITE_NAME;
$additionalCSS = "admin.css";

include '../../../UI/components/Header.php';
include '../../../UI/components/Navbar.php';
include '../../../UI/components/Alert.php';
?>

<div class="dashboard-layout">
    <?php include '../../../UI/components/Sidebar.php'; ?>

    <main class="dashboard-content">

        <h1>Reservation Details</h1>

        <div class="table-container">
            <table class="detail-table">
                <tr>
                    <th>Reservation ID</th>
                    <td><?php echo htmlspecialchars($reservation['id']); ?></td>
                </tr>

                <tr>
                    <th>Passenger Name</th>
                    <td><?php echo htmlspecialchars($reservation['passenger_name']); ?></td>
                </tr>

                <tr>
                    <th>Passenger Phone</th>
                    <td><?php echo htmlspecialchars($reservation['passenger_phone']); ?></td>
                </tr>

                <tr>
                    <th>User Account</th>
                    <td><?php echo htmlspecialchars($reservation['user_name']); ?> (<?php echo htmlspecialchars($reservation['email']); ?>)</td>
                </tr>

                <tr>
                    <th>Bus Name</th>
                    <td><?php echo htmlspecialchars($reservation['bus_name']); ?> (<?php echo htmlspecialchars($reservation['bus_number']); ?>)</td>
                </tr>

                <tr>
                    <th>Route</th>
                    <td><?php echo htmlspecialchars($reservation['route_from'] . " → " . $reservation['route_to']); ?></td>
                </tr>

                <tr>
                    <th>Departure Time</th>
                    <td><?php echo htmlspecialchars($reservation['departure_time']); ?></td>
                </tr>

                <tr>
                    <th>Arrival Time</th>
                    <td><?php echo htmlspecialchars($reservation['arrival_time']); ?></td>
                </tr>

                <tr>
                    <th>Seat</th>
                    <td><?php echo htmlspecialchars($reservation['seat_number']); ?></td>
                </tr>

                <tr>
                    <th>Booking Date</th>
                    <td><?php echo htmlspecialchars(formatDate($reservation['booking_date'])); ?></td>
                </tr>

                <tr>
                    <th>Total Amount</th>
                    <td>Rs. <?php echo htmlspecialchars(number_format($reservation['total_amount'], 2)); ?></td>
                </tr>

                <tr>
                    <th>Status</th>
                    <td>
                        <span class="badge badge-<?php echo htmlspecialchars($reservation['status']); ?>">
                            <?php echo ucfirst(htmlspecialchars($reservation['status'])); ?>
                        </span>
                    </td>
                </tr>

                <tr>
                    <th>Created At</th>
                    <td><?php echo htmlspecialchars(formatDateTime($reservation['created_at'])); ?></td>
                </tr>

                <tr>
                    <th>Updated At</th>
                    <td><?php echo htmlspecialchars(formatDateTime($reservation['updated_at'])); ?></td>
                </tr>

                <tr>
                    <th>Raw Data (JSON)</th>
                    <td>
                        <pre><?php echo htmlspecialchars(json_encode($reservation, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)); ?></pre>
                    </td>
                </tr>
            </table>
        </div>

        <div class="actions mt-20">
            <a href="index.php" class="btn-sm btn-secondary">← Back</a>
            <a href="print.php?id=<?php echo htmlspecialchars($reservation['id']); ?>" 
class="btn-sm btn-secondary"   target="_blank">
    Print Ticket
</a>


            <?php if ($reservation['status'] !== 'cancelled'): ?>
                <form method="POST" action="<?php echo BASE_URL; ?>/controllers/ReservationController.php" style="display:inline;">
                    <input type="hidden" name="action" value="cancel">
                    <input type="hidden" name="id" value="<?php echo h($reservation['id']); ?>">
                    <button type="submit" class="btn-sm btn-danger" onclick="return confirm('Cancel this reservation?')">Cancel</button>
                </form>
            <?php endif; ?>

     

        </div>

    </main>
</div>

<?php include '../../../UI/components/Footer.php'; ?>
