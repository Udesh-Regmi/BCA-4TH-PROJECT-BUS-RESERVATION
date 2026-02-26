<?php
require_once '../../../config/database.php';
require_once '../../../config/constants.php';
require_once '../../../includes/session.php';
require_once '../../../includes/functions.php';
require_once '../../../models/Reservation.php';
require_once '../../../middleware/admin.php';

$database = new Database();
$db = $database->getConnection();
$reservation = new Reservation($db);

$reservations = $reservation->getAll();

$pageTitle = "Manage Reservations - " . SITE_NAME;
$additionalCSS = "admin.css";
include '../../../UI/components/Header.php';
include '../../../UI/components/Navbar.php';
include '../../../UI/components/Alert.php';
?>

<div class="dashboard-layout">
    <?php include '../../../UI/components/Sidebar.php'; ?>

    <main class="dashboard-content">
        <h1>Manage Reservations</h1>

        <div class="admin-reservations">
            <div class="reservation-search">
                <input type="search" name="search-reservations" id="filter-reservations"
                    placeholder="Search reservations...">
            </div>

            <a href="<?php echo BASE_URL . '/pages/user/reservations.php'; ?>" class="btn btn-primary">
                My Reservations
            </a>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Bus</th>
                        <th>Route</th>
                        <th>Date</th>
                        <th>Seat</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($reservations)): ?>
                        <tr>
                            <td colspan="9" class="text-center">No reservations found</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($reservations as $res): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($res['id']); ?></td>
                                <td><?php echo htmlspecialchars($res['user_name']); ?></td>
                                <td><?php echo htmlspecialchars($res['bus_name']); ?></td>
                                <td><?php echo htmlspecialchars($res['route_from']) . ' → ' . htmlspecialchars($res['route_to']); ?></td>
                                <td><?php echo formatDate($res['booking_date']); ?></td>
                                <td><?php echo htmlspecialchars($res['seat_number']); ?></td>
                                <td>Rs. <?php echo number_format($res['total_amount'], 2); ?></td>
                                <td>
                                    <span class="badge badge-<?php echo htmlspecialchars($res['status']); ?>">
                                        <?php echo ucfirst(htmlspecialchars($res['status'])); ?>
                                    </span>
                                </td>
                                <td class="actions">
                                    <a href="view.php?id=<?php echo $res['id']; ?>" class="btn-sm btn-warning">View</a>

                                    <?php if ($res['status'] !== 'cancelled'): ?>
                                        <form method="POST" action="<?php echo BASE_URL; ?>/controllers/ReservationController.php"
                                            style="display:inline;">
                                            <input type="hidden" name="action" value="update_status">
                                            <input type="hidden" name="id" value="<?php echo $res['id']; ?>">
                                            <input type="hidden" name="status" value="cancelled">
                                            <button type="submit" class="btn-sm btn-danger btn-cancel"
                                                onclick="return confirm('Cancel this reservation?')">Cancel</button>
                                        </form>
                                    <?php endif; ?>

                                    <?php if ($res['status'] === 'cancelled' || $res['status'] === 'completed'): ?>
                                        <form method="POST" action="<?php echo BASE_URL; ?>/controllers/ReservationController.php"
                                            style="display:inline;">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?php echo $res['id']; ?>">
                                            <button type="submit" class="btn-sm btn-danger btn-delete"
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

<?php include '../../../UI/components/Footer.php'; ?>