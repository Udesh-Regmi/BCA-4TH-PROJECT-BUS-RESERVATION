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

function canEditReservationForUser($createdAt, $hours = 4) {
    if (empty($createdAt)) {
        return false;
    }

    $createdTimestamp = strtotime($createdAt);
    if ($createdTimestamp === false) {
        return false;
    }

    return time() <= strtotime("+{$hours} hours", $createdTimestamp);
}



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
                            <td colspan="10" class="text-center">No reservations found</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($userReservations as $res): ?>
                            <?php
                                $isActiveReservation = ($res['status'] === 'pending' || $res['status'] === 'confirmed');
                                $withinEditWindow = canEditReservationForUser($res['created_at'], 4);
                            ?>
                            <tr>
                                <td><?php echo (int) $res['id']; ?></td>

                                <td><?php echo htmlspecialchars($res['bus_number']); ?></td>
                                <td><?php echo htmlspecialchars($res['bus_name']); ?></td>
                                <td><?php echo htmlspecialchars($res['route_from']) . ' → ' . htmlspecialchars($res['route_to']); ?></td>
                                <td><?php echo formatDate($res['booking_date']); ?></td>
                                <td><?php echo htmlspecialchars($res['seat_number']); ?></td>
                                <td>Rs. <?php echo number_format($res['total_amount'], 2); ?></td>
                                <td><span
                                    class="badge badge-<?php echo htmlspecialchars($res['status']); ?>"><?php echo ucfirst(htmlspecialchars($res['status'])); ?></span>
                                </td>
                                <td><?php echo ucfirst(htmlspecialchars($res['payment_method'])); ?></td>
                                <td class="actions-cell">
                                    <?php if ($isActiveReservation && $withinEditWindow): ?>
                                        <a class="btn-action btn-edit"
                                            href="<?php echo BASE_URL; ?>/pages/user/edit_reservation.php?id=<?php echo (int) $res['id']; ?>">
                                            Edit
                                        </a>
                                    <?php endif; ?>

                                    <?php if ($isActiveReservation): ?>
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
                                    
                                   <a class="btn-print" href="<?php echo BASE_URL; ?>/pages/user/print.php?id=<?php echo $res['id']; ?>">Print</a>

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