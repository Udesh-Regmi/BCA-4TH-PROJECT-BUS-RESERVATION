<?php
require_once '../../config/database.php';
require_once '../../config/constants.php';
require_once '../../includes/session.php';
require_once '../../includes/functions.php';
require_once '../../models/Reservation.php';
require_once '../../middleware/auth.php';

$database = new Database();
$db = $database->getConnection();
$reservationModel = new Reservation($db);

$reservationId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if (!$reservationId) {
    setAlert('Reservation ID is required', 'danger');
    redirect(BASE_URL . '/pages/user/reservations.php');
}

$reservation = $reservationModel->getById($reservationId);
if (!$reservation || $reservation['user_id'] != $_SESSION['user_id']) {
    setAlert('Reservation not found', 'danger');
    redirect(BASE_URL . '/pages/user/reservations.php');
}

if ($reservation['status'] === 'cancelled') {
    setAlert('Cancelled reservations cannot be edited', 'warning');
    redirect(BASE_URL . '/pages/user/reservations.php');
}

$withinEditWindow = $reservationModel->canUserEditWithinHours($reservation['created_at'], 4);

$pageTitle = "Edit Reservation - " . SITE_NAME;
$additionalCSS = "user.css";
include '../../UI/components/Header.php';
include '../../UI/components/Navbar.php';
include '../../UI/components/Alert.php';
?>

<div class="dashboard-layout">
    <?php include '../../UI/components/Sidebar.php'; ?>

    <main class="dashboard-content">
        <h1>Edit Reservation</h1>

        <div class="form-card user-reservation-edit-card">
            <h2><?php echo htmlspecialchars($reservation['bus_name']); ?> (<?php echo htmlspecialchars($reservation['bus_number']); ?>)</h2>
            <p class="user-reservation-edit-route">
                <?php echo htmlspecialchars($reservation['route_from']); ?> → <?php echo htmlspecialchars($reservation['route_to']); ?>
            </p>

            <?php if (!$withinEditWindow): ?>
                <div class="alert alert-warning user-reservation-edit-warning">
                    You can update reservation details only within 4 hours after booking.
                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo BASE_URL; ?>/controllers/ReservationController.php">
                <input type="hidden" name="action" value="update_reservation">
                <input type="hidden" name="source" value="user">
                <input type="hidden" name="id" value="<?php echo (int) $reservation['id']; ?>">

                <div class="form-group">
                    <label for="booking_date"><i class="fas fa-calendar"></i> Travel Date</label>
                    <input
                        type="date"
                        id="booking_date"
                        name="booking_date"
                        value="<?php echo htmlspecialchars($reservation['booking_date']); ?>"
                        required
                        <?php echo !$withinEditWindow ? 'disabled' : ''; ?>
                    >
                </div>

                <div class="form-group">
                    <label for="seat_number"><i class="fas fa-chair"></i> Seat Number</label>
                    <input
                        type="number"
                        id="seat_number"
                        name="seat_number"
                        min="1"
                        max="<?php echo (int) $reservation['total_seats']; ?>"
                        value="<?php echo (int) $reservation['seat_number']; ?>"
                        required
                        <?php echo !$withinEditWindow ? 'disabled' : ''; ?>
                    >
                </div>

                <div class="form-group">
                    <label for="passenger_name"><i class="fas fa-user"></i> Passenger Name</label>
                    <input
                        type="text"
                        id="passenger_name"
                        name="passenger_name"
                        value="<?php echo htmlspecialchars($reservation['passenger_name']); ?>"
                        required
                        <?php echo !$withinEditWindow ? 'disabled' : ''; ?>
                    >
                </div>

                <div class="form-group">
                    <label for="passenger_phone"><i class="fas fa-phone"></i> Passenger Phone</label>
                    <input
                        type="text"
                        id="passenger_phone"
                        name="passenger_phone"
                        value="<?php echo htmlspecialchars($reservation['passenger_phone']); ?>"
                        required
                        <?php echo !$withinEditWindow ? 'disabled' : ''; ?>
                    >
                </div>

                <div class="form-actions">
                    <a href="<?php echo BASE_URL; ?>/pages/user/reservations.php" class="btn-secondary">Back</a>
                    <?php if ($withinEditWindow): ?>
                        <button type="submit" class="btn-submit">Update Reservation</button>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </main>
</div>

<?php include '../../UI/components/Footer.php'; ?>