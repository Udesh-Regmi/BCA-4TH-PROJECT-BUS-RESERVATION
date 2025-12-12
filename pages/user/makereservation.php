<?php
require_once '../../config/database.php';
require_once '../../config/constants.php';
require_once '../../includes/session.php';
require_once '../../includes/functions.php';
require_once '../../models/Bus.php';
require_once '../../models/Reservation.php';
require_once '../../middleware/auth.php';

$busId = $_GET['bus_id'] ?? null;

if (!$busId) {
    setAlert('Please select a bus first', 'danger');
    redirect(BASE_URL . '/pages/public/viewbus.php');
    exit();
}

$database = new Database();
$db = $database->getConnection();
$bus = new Bus($db);
$reservation = new Reservation($db);

$busData = $bus->getById($busId);

if (!$busData) {
    setAlert('Bus not found', 'danger');
    redirect(BASE_URL . '/pages/public/viewbus.php');
    exit();
}

$bookingDate = $_GET['date'] ?? date('Y-m-d');
$reservedSeats = $reservation->getReservedSeats($busId, $bookingDate);

$pageTitle = "Make Reservation - " . SITE_NAME;
$additionalCSS = "user.css";
include '../../UI/components/Header.php';
include '../../UI/components/Navbar.php';
include '../../UI/components/Alert.php';
?>

<div class="dashboard-layout">
    <?php include '../../UI/components/Sidebar.php'; ?>

    <main class="dashboard-content">
        <h1>Make Reservation</h1>

        <div class="reservation-container">
            <div class="bus-info-card">
                <h2><?php echo htmlspecialchars($busData['bus_name']); ?></h2>
                <p class="bus-number"><?php echo htmlspecialchars($busData['bus_number']); ?></p>
                <div class="info-row">
                    <span><i class="fas fa-map-marker-alt"></i>
                        <?php echo htmlspecialchars($busData['route_from']); ?></span>
                    <span>→</span>
                    <span><i class="fas fa-map-marker-alt"></i>
                        <?php echo htmlspecialchars($busData['route_to']); ?></span>
                </div>
                <div class="info-row">
                    <span><i class="fas fa-clock"></i> <?php echo formatTime($busData['departure_time']); ?></span>
                    <br>
                    <span>Rs. <?php echo number_format($busData['price'], 2); ?></span>
                </div>
            </div>

            <form method="POST" id="reservationForm" class="reservation-form">
                <input type="hidden" name="bus_id" value="<?php echo $busId; ?>">

                <div class="form-group">
                    <label for="booking_date"><i class="fas fa-calendar"></i> Travel Date</label>
                    <input type="date" id="booking_date" name="booking_date" value="<?php echo $bookingDate; ?>"
                        min="<?php echo date('Y-m-d'); ?>" max="2026-12-30" required>
                </div>

                <div class="svg-bus-container">
                    <div class="bus-simulation-info">
                        <div class="info-item">
                            <i class="fas fa-bus"></i>
                            <span><?php echo htmlspecialchars($busData['bus_name']); ?> -
                                <?php echo htmlspecialchars($busData['bus_number']); ?></span>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-users"></i>
                            <span><?php echo $busData['total_seats']; ?> Seats Total</span>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-chair"></i>
                            <span><?php echo count($reservedSeats); ?> Reserved,
                                <?php echo $busData['total_seats'] - count($reservedSeats); ?> Available</span>
                        </div>
                    </div>

                    <h3 class="svg-title">
                        <i class="fas fa-map-signs"></i>
                        Choose Your Seat
                    </h3>
                    <div class="selected-seat-info">
                        <p class="svg-sub">Selected seat: <strong id="svg-seat-display">None selected</strong></p>
                        <div class="seat-position-info" id="seat-position-info">Click on an available seat to select
                        </div>
                    </div>

                    <div class="svg-wrapper" role="img" aria-label="Interactive bus seat map">
                        <svg id="bus-svg"
                            viewBox="0 0 500 <?php echo (200 + ceil($busData['total_seats'] / 4) * 70 + 80); ?>"
                            xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <linearGradient id="busBody" x1="0%" y1="0%" x2="0%" y2="100%">
                                    <stop offset="0%" style="stop-color:#f8fafc;stop-opacity:1" />
                                    <stop offset="100%" style="stop-color:#e2e8f0;stop-opacity:1" />
                                </linearGradient>
                                <linearGradient id="windowGlass" x1="0%" y1="0%" x2="0%" y2="100%">
                                    <stop offset="0%" style="stop-color:#dbeafe;stop-opacity:0.8" />
                                    <stop offset="100%" style="stop-color:#93c5fd;stop-opacity:0.6" />
                                </linearGradient>
                                <filter id="shadow">
                                    <feGaussianBlur in="SourceAlpha" stdDeviation="4" />
                                    <feOffset dx="3" dy="3" result="offsetblur" />
                                    <feComponentTransfer>
                                        <feFuncA type="linear" slope="0.3" />
                                    </feComponentTransfer>
                                    <feMerge>
                                        <feMergeNode />
                                        <feMergeNode in="SourceGraphic" />
                                    </feMerge>
                                </filter>
                            </defs>

                            <g filter="url(#shadow)">
                                <rect x="40" y="40" width="420" height="<?php echo (140 + ceil($busData['total_seats'] / 4) * 70 + 40); ?>"
                                    rx="30" ry="30" fill="url(#busBody)" stroke="#94a3b8" stroke-width="3" />
                                
                                <rect x="45" y="45" width="410" height="40" rx="25" ry="25" fill="url(#windowGlass)" 
                                    stroke="#3b82f6" stroke-width="2" />
                                
                                <ellipse cx="80" cy="70" rx="15" ry="10" fill="#fef08a" stroke="#ca8a04" stroke-width="2" />
                                <ellipse cx="420" cy="70" rx="15" ry="10" fill="#fef08a" stroke="#ca8a04" stroke-width="2" />
                                
                                <rect x="120" y="50" width="8" height="12" rx="4" fill="#fb923c" />
                                <rect x="372" y="50" width="8" height="12" rx="4" fill="#fb923c" />
                            </g>

                            <g transform="translate(350, 100)">
                                <rect x="0" y="0" width="95" height="55" rx="8" fill="#ffffff" stroke="#64748b" stroke-width="2" />
                                <text x="47.5" y="32" fill="#1e293b" font-size="14" text-anchor="middle" font-weight="bold">DRIVER</text>
                                <circle cx="12" cy="28" r="10" fill="#334155" stroke="#475569" stroke-width="2" />
                            </g>

                            <g transform="translate(60, 100)">
                                <rect x="0" y="0" width="80" height="55" rx="5" fill="#0ea5e9" stroke="#0284c7" stroke-width="2" />
                                <text x="40" y="32" fill="#ffffff" font-size="12" text-anchor="middle" font-weight="bold">ENTRY</text>
                                <rect x="2" y="2" width="76" height="51" rx="4" fill="none" stroke="#38bdf8" stroke-width="1" stroke-dasharray="3,3" />
                            </g>

                            <?php
                            $rows = ceil($busData['total_seats'] / 4);
                            for ($r = 0; $r < $rows; $r++):
                                $y = 175 + $r * 70;
                            ?>
                                <rect x="42" y="<?php echo $y - 10; ?>" width="30" height="40" rx="5" 
                                    fill="url(#windowGlass)" stroke="#60a5fa" stroke-width="1.5" />
                                <rect x="430" y="<?php echo $y - 10; ?>" width="30" height="40" rx="5" 
                                    fill="url(#windowGlass)" stroke="#60a5fa" stroke-width="1.5" />
                            <?php endfor; ?>

                            <?php for ($r = 0; $r < $rows; $r++): 
                                $y = 165 + $r * 70;
                            ?>
                                <rect x="200" y="<?php echo $y; ?>" width="100" height="60" fill="#e0e7ff" opacity="0.3" />
                                <line x1="250" y1="<?php echo $y; ?>" x2="250" y2="<?php echo $y + 60; ?>" 
                                    stroke="#c7d2fe" stroke-width="2" stroke-dasharray="5,5" />
                            <?php endfor; ?>

                            <g id="seats-group">
                                <?php
                                $leftPositions = [95, 155];
                                $rightPositions = [345, 405];
                                $start_y = 190;
                                $y_step = 70;

                                for ($seat = 1; $seat <= $busData['total_seats']; $seat++):
                                    $index = $seat - 1;
                                    $row = intdiv($index, 4);
                                    $col = $index % 4;

                                    $cx = ($col < 2) ? $leftPositions[$col] : $rightPositions[$col - 2];
                                    $position = ($col === 0 || $col === 3) ? 'Window' : 'Aisle';
                                    $cy = $start_y + $row * $y_step;
                                    $isReserved = in_array($seat, $reservedSeats);
                                ?>
                                    <g class="seat <?php echo $isReserved ? 'seat--reserved' : 'seat--available'; ?>"
                                        data-seat="<?php echo $seat; ?>" data-position="<?php echo $position; ?>"
                                        transform="translate(<?php echo $cx; ?>, <?php echo $cy; ?>)"
                                        tabindex="<?php echo $isReserved ? '-1' : '0'; ?>">
                                        
                                        <rect x="-20" y="-25" width="40" height="35" rx="8" 
                                            class="seat-rect <?php echo $isReserved ? 'taken' : ''; ?>" 
                                            stroke="#94a3b8" stroke-width="2" />
                                        <rect x="-18" y="8" width="36" height="8" rx="4" 
                                            fill="<?php echo $isReserved ? '#991b1b' : '#854d0e'; ?>" />
                                        
                                        <text x="0" y="-5" font-size="14" text-anchor="middle" font-weight="bold"
                                            fill="<?php echo $isReserved ? '#ffffff' : '#1e293b'; ?>">
                                            <?php echo $seat; ?>
                                        </text>
                                        
                                        <circle cx="0" cy="12" r="3"
                                            fill="<?php echo $isReserved ? '#fca5a5' : ($position === 'Window' ? '#3b82f6' : '#10b981'); ?>" />
                                    </g>
                                <?php endfor; ?>
                            </g>

                            <g transform="translate(40, <?php echo 175 + $rows * 70; ?>)">
                                <rect x="0" y="0" width="420" height="35" rx="8" fill="#f1f5f9" stroke="#cbd5e1" stroke-width="2" />
                                <text x="210" y="22" fill="#64748b" font-size="12" text-anchor="middle" font-weight="bold">
                                    LUGGAGE COMPARTMENT
                                </text>
                            </g>

                            <?php $rearY = 175 + $rows * 70 + 45; ?>
                            <ellipse cx="70" cy="<?php echo $rearY; ?>" rx="8" ry="6" fill="#dc2626" stroke="#7f1d1d" stroke-width="2" />
                            <ellipse cx="90" cy="<?php echo $rearY; ?>" rx="8" ry="6" fill="#fbbf24" stroke="#78350f" stroke-width="2" />
                            <ellipse cx="410" cy="<?php echo $rearY; ?>" rx="8" ry="6" fill="#fbbf24" stroke="#78350f" stroke-width="2" />
                            <ellipse cx="430" cy="<?php echo $rearY; ?>" rx="8" ry="6" fill="#dc2626" stroke="#7f1d1d" stroke-width="2" />
                            
                            
                        </svg>
                    </div>

                    <input type="hidden" id="seat_number" name="seat_number" value="" required>

                    <div class="svg-legend">
                        <span class="legend-item">
                            <span class="legend-swatch available-swatch"></span>
                            <span>Available</span>
                        </span>
                        <span class="legend-item">
                            <span class="legend-swatch selected-swatch"></span>
                            <span>Selected</span>
                        </span>
                        <span class="legend-item">
                            <span class="legend-swatch reserved-swatch"></span>
                            <span>Reserved</span>
                        </span>
                        <span class="legend-item">
                            <i class="fas fa-circle" style="color: #3b82f6; font-size: 12px;"></i>
                            <span>Window</span>
                        </span>
                        <span class="legend-item">
                            <i class="fas fa-circle" style="color: #10b981; font-size: 12px;"></i>
                            <span>Aisle</span>
                        </span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="passenger_name"><i class="fas fa-user"></i> Passenger Name</label>
                    <input type="text" id="passenger_name" name="passenger_name"
                        value="<?php echo htmlspecialchars($_SESSION['user_name']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="passenger_phone"><i class="fas fa-phone"></i> Contact Number</label>
<input 
  type="tel" 
  id="passenger_phone" 
  name="passenger_phone" 
  placeholder="1234567890" 
  pattern="[0-9]{10,13}" 
  required
>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-credit-card"></i> Payment Method</label>
                    <div class="payment-method-selector">
                        <label class="payment-option">
                            <input type="radio" name="payment_method" value="cash" checked>
                            <div class="payment-card">
                                <i class="fas fa-money-bill-wave"></i>
                                <span>Cash Payment</span>
                                <small>Pay at counter</small>
                            </div>
                        </label>
                        <label class="payment-option">
                            <input type="radio" name="payment_method" value="esewa">
                            <div class="payment-card">
                                <i class="fas fa-wallet"></i>
                                <span>eSewa</span>
                                <small>Pay online</small>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="total-amount">
                    <h3>Total Amount: Rs. <?php echo number_format($busData['price'], 2); ?></h3>
                </div>

                <button type="button" id="submitReservation" class="btn-submit">
             <span id="submitText">Confirm Reservation</span>
                </button>
            </form>
        </div>
    </main>
</div>

<form id="esewaForm" action="https://rc-epay.esewa.com.np/api/epay/main/v2/form" method="POST" style="display: none;">
    <input type="hidden" name="amount" id="esewa_amount">
    <input type="hidden" name="tax_amount" value="0">
    <input type="hidden" name="total_amount" id="esewa_total_amount">
    <input type="hidden" name="transaction_uuid" id="esewa_transaction_uuid">
    <input type="hidden" name="product_code" value="EPAYTEST">
    <input type="hidden" name="product_service_charge" value="0">
    <input type="hidden" name="product_delivery_charge" value="0">
    <input type="hidden" name="success_url" value="<?php echo BASE_URL; ?>/pages/payment/esewa-success.php">
    <input type="hidden" name="failure_url" value="<?php echo BASE_URL; ?>/pages/payment/esewa-failure.php">
    <input type="hidden" name="signed_field_names" value="total_amount,transaction_uuid,product_code">
    <input type="hidden" name="signature" id="esewa_signature">
</form>

<?php include '../../UI/components/Footer.php'; ?>

<style>

</style>

<script>
(function () {
    const svg = document.getElementById('bus-svg');
    const seatElems = svg.querySelectorAll('.seat--available');
    const seatInput = document.getElementById('seat_number');
    const seatDisplay = document.getElementById('svg-seat-display');
    const submitButton = document.getElementById('submitReservation');
    const submitText = document.getElementById('submitText');
    const paymentMethodInputs = document.querySelectorAll('input[name="payment_method"]');
    let current = null;

    function markSelected(gEl) {
        if (current) {
            current.querySelector('.seat-rect').classList.remove('selected');
            current.setAttribute('aria-pressed', 'false');
        }
        if (gEl) {
            gEl.querySelector('.seat-rect').classList.add('selected');
            gEl.setAttribute('aria-pressed', 'true');
            current = gEl;
        } else {
            current = null;
        }
    }

    seatElems.forEach(el => {
        el.addEventListener('click', () => {
            const seat = el.dataset.seat;
            const position = el.dataset.position;
            seatInput.value = seat;
            seatDisplay.textContent = `Seat ${seat} (${position})`;
            markSelected(el);
        });

        el.addEventListener('keydown', (ev) => {
            if (ev.key === 'Enter' || ev.key === ' ') {
                ev.preventDefault();
                el.click();
            }
        });
    });

    paymentMethodInputs.forEach(input => {
        input.addEventListener('change', function() {
            if (this.value === 'esewa') {
                submitText.innerHTML = '<i class="fas fa-wallet"></i> Pay with eSewa';
            } else {
                submitText.innerHTML = '<i class="fas fa-check-circle"></i> Confirm Reservation';
            }
        });
    });

    submitButton.addEventListener('click', function() {
        const seatNum = seatInput.value;
        const passengerName = document.getElementById('passenger_name').value;
        const passengerPhone = document.getElementById('passenger_phone').value;
        const bookingDate = document.getElementById('booking_date').value;
        const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;

        if (!seatNum) {
            alert('Please select a seat before proceeding.');

            return;
        }

        if (!passengerName || !passengerPhone || !bookingDate) {
            alert('Please fill all required fields.');
            return;
        }

        const reservationData = {
            bus_id: <?php echo $busId; ?>,
            seat_number: seatNum,
            passenger_name: passengerName,
            passenger_phone: passengerPhone,
            booking_date: bookingDate,
            amount: <?php echo $busData['price']; ?>,
            status: 'pending',
            payment_method: paymentMethod,
        };

        if (paymentMethod === 'esewa') {
            const transactionUuid = 'TXN' + Date.now() + Math.random().toString(36).substr(2, 9);
            const amount = <?php echo $busData['price']; ?>;

            document.getElementById('esewa_amount').value = amount;
            document.getElementById('esewa_total_amount').value = amount;
            document.getElementById('esewa_transaction_uuid').value = transactionUuid;

            fetch('<?php echo BASE_URL; ?>/controllers/EsewaController.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    action: 'generate_signature',
                    total_amount: amount,
                    status: 'confirmed',
                    transaction_uuid: transactionUuid,
                    reservation_data: reservationData
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.signature) {
                    document.getElementById('esewa_signature').value = data.signature;
                    document.getElementById('esewaForm').submit();
                } else {
                    alert('Error generating payment signature. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error initiating payment. Please try again.');
            });
        } else {
            // Cash payment - direct submission
            fetch('<?php echo BASE_URL; ?>/controllers/ReservationController.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    action: 'create',
                    ...reservationData,
                    transaction_id: 'CASH' + Date.now()
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Reservation confirmed! Please pay at the counter.');
                    window.location.href = '<?php echo BASE_URL; ?>/pages/user/reservations.php';
                } else {
                    alert(data.message || 'Error creating reservation. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error creating reservation. Please try again.');
            });
        }
    });

    svg.querySelectorAll('.seat--reserved').forEach(r => {
        r.setAttribute('aria-disabled', 'true');
    });
})();
</script>