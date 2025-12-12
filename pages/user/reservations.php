<!-- PAGES/USER/RESERVATIONS.PHP -->
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



$pageTitle = "My Reservations - " . SITE_NAME;
$additionalCSS = "user.css";
include '../../UI/components/Header.php';
include '../../UI/components/Navbar.php';
include '../../UI/components/Alert.php';
?>

<div class="dashboard-layout">
    <?php include '../../UI/components/Sidebar.php'; ?>

    <main class="dashboard-content">
        <h1>My Reservations</h1>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Bus No:</th>
                        <th>Bus</th>
                        <th>Route</th>
                        <th>Date</th>
                        <th>Seat No. </th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Payment Method</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($userReservations)): ?>
                        <tr>
                            <td colspan="8" class="text-center">No reservations found</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($userReservations as $res): ?>
                            <tr>
                                <td><?php echo $res['id']; ?></td>
                                <td><?php echo $res['bus_number']; ?></td>
                                <td><?php echo $res['bus_name']; ?></td>
                                <td><?php echo $res['route_from'] . ' → ' . $res['route_to']; ?></td>
                                <td><?php echo formatDate($res['booking_date']); ?></td>
                                <td><?php echo $res['seat_number']; ?></td>
                                <td>Rs. <?php echo number_format($res['total_amount'], 2); ?></td>
                                <td><span
                                        class="badge badge-<?php echo $res['status']; ?>"><?php echo ucfirst($res['status']); ?></span>
                                </td>
                                <td><?php echo ucfirst($res['payment_method']); ?></td>
                                <td class="actions-cell">
                                    <?php if ($res['status'] === 'pending' || $res['status'] === 'confirmed'): ?>
                                        <form method="POST" action="<?php echo BASE_URL; ?>/controllers/ReservationController.php"
                                            style="display:inline;">
                                            <input type="hidden" name="action" value="cancel">
                                            <input type="hidden" name="id" value="<?php echo $res['id']; ?>">
                                            <button type="submit" class="btn-action btn-cancel"
                                                onclick="return confirm('Cancel this reservation?')">Cancel</button>
                                        </form>
                                    <?php endif; ?>

                                    <?php if ($res['status'] === 'cancelled'): ?>
                                        <form method="POST" action="<?php echo BASE_URL; ?>/controllers/ReservationController.php"
                                            style="display:inline;">
                                            <input type="hidden" name="action" value="delete_reservation_by_user">
                                            <input type="hidden" name="id" value="<?php echo $res['id']; ?>">
                                            <button type="submit" class="btn-action btn-delete"
                                                onclick="return confirm('Delete this reservation?')">Delete</button>
                                        </form>
                                    <?php endif; ?>
                                </td>

                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

<?php include '../../UI/components/Footer.php'; ?>