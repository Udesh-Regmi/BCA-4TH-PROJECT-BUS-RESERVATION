<?php
require_once '../../../config/database.php';
require_once '../../../config/constants.php';
require_once '../../../includes/functions.php';
require_once '../../../models/Reservation.php';

// Get reservation ID
$reservationId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if (!$reservationId) {
 
    die("Invalid Ticket Request");
}

// Init DB
$database = new Database();
$db = $database->getConnection();
$reservationModel = new Reservation($db);

// Fetch reservation
$reservation = $reservationModel->getById($reservationId);

if (!$reservation) {
    die("Reservation not found");
}



// Escape for JavaScript

// Background and logo paths
$backgroundImage = "https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80";
$logoPath = "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTlYdel5zbQymqAKo2kRrBe2BblB9scLr3Lkw&s";

// Format dates
$departureDate = date('d M, Y', strtotime($reservation['departure_time']));
$departureTime = date('h:i A', strtotime($reservation['departure_time']));
$bookingDate = date('d M, Y h:i A', strtotime($reservation['created_at']));

// Calculate estimated arrival (add 4 hours)
$estimatedArrival = date('h:i A', strtotime($reservation['departure_time'] . ' + 4 hours'));
?>
<!DOCTYPE html>
<html>

<head>
    <title>Ticket #<?php echo $reservation['id']; ?> - <?php echo SITE_NAME; ?></title>
    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)),
                url('<?php echo $backgroundImage; ?>') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            position: relative;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg width="100" height="100" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg"><path d="M0,0 L100,0 L100,100 Z" fill="%2300596b" opacity="0.1"/></svg>');
            background-size: 200px;
            opacity: 0.3;
            z-index: -1;
        }

        .ticket-container {
            width: 100%;
            max-width: 950px;
            perspective: 1000px;
        }

        .ticket-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            position: relative;
            border: 1px solid rgba(0, 89, 107, 0.2);
            transform-style: preserve-3d;
            transition: transform 0.3s ease;
        }

        .ticket-card:hover {
            transform: translateY(-5px) rotateX(2deg);
        }

        .ticket-header {
            background: linear-gradient(90deg, #00596b 0%, #0081a7 100%);
            color: white;
            padding: 25px 40px;
            position: relative;
            overflow: hidden;
        }

        .ticket-header::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 200px;
            height: 200px;
            background: linear-gradient(45deg, transparent 30%, rgba(255, 255, 255, 0.1) 50%, transparent 70%);
            transform: rotate(45deg) translate(30px, -80px);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            z-index: 2;
        }

        .company-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .logo-container {
            width: 80px;
            height: 80px;
            background: white;
            border-radius: 12px;
            padding: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .logo-container img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .company-details h1 {
            font-family: 'Roboto Condensed', sans-serif;
            font-size: 28px;
            font-weight: 700;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }

        .company-details .tagline {
            font-size: 14px;
            opacity: 0.9;
            font-weight: 300;
        }

        .ticket-id {
            text-align: right;
        }

        .ticket-id h2 {
            font-family: 'Roboto Condensed', sans-serif;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .ticket-id .badge {
            background: rgba(143, 224, 89, 0.62);
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            letter-spacing: 0.5px;
        }

        .ticket-body {
            padding: 40px;
        }

        .passenger-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            border-left: 5px solid #00596b;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .section-title {
            font-family: 'Roboto Condensed', sans-serif;
            font-size: 18px;
            font-weight: 700;
            color: #00596b;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title::before {
            content: '';
            width: 30px;
            height: 3px;
            background: #00596b;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
            gap: 20px;
        }

        .info-item {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .info-label {
            font-size: 12px;
            color: #6c757d;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-value {
            font-size: 16px;
            font-weight: 600;
            color: #212529;
        }

        .info-value strong {
            color: #00596b;
        }

        .journey-section {
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            gap: 30px;
            align-items: center;
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid #e9ecef;
        }

        .location {
            text-align: center;
        }

        .location h3 {
            font-family: 'Roboto Condensed', sans-serif;
            font-size: 24px;
            color: #00596b;
            margin-bottom: 10px;
        }

        .location .time {
            font-size: 14px;
            color: #6c757d;
            font-weight: 500;
        }

        .journey-arrow {
            font-size: 32px;
            color: #0081a7;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }

        .details-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .detail-box {
            background: white;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease;
            border-top: 4px solid #00596b;
        }

        .detail-box:hover {
            transform: translateY(-5px);
        }

        .detail-label {
            font-size: 12px;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .detail-value {
            font-size: 20px;
            font-weight: 700;
            color: #00596b;
        }

        .payment-box {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
        }

        .payment-box .detail-label {
            color: rgba(255, 255, 255, 0.9);
        }

        .payment-box .detail-value {
            color: white;
        }

        .seat-display {
            background: linear-gradient(135deg, #287fe2ff  0%, #0081a7 100%);
            border-radius: 15px;
            padding: 25px;
            color: white;
            text-align: center;
            box-shadow: 0 8px 25px rgba(0, 89, 107, 0.3);
            position: relative;
            overflow: hidden;
        }

        .seat-display::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .seat-label {
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 2px;
            opacity: 0.9;
            margin-bottom: 10px;
            position: relative;
            z-index: 2;
        }

        .seat-number {
            font-family: 'Roboto Condensed', sans-serif;
            font-size: 72px;
            font-weight: 700;
            line-height: 1;
            margin: 15px 0;
            position: relative;
            z-index: 2;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .seat-type {
            font-size: 14px;
            opacity: 0.9;
            position: relative;
            z-index: 2;
        }


        .ticket-footer {
            padding: 25px 40px;
            background: #f8f9fa;
            border-top: 1px solid #e9ecef;
            text-align: center;
        }

        .footer-note {
            color: #6c757d;
            font-size: 13px;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .contact-info {
            display: flex;
            justify-content: center;
            gap: 30px;
            font-size: 13px;
            color: #495057;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .contact-item i {
            color: #00596b;
        }

        .print-section {
            text-align: center;
            margin-top: 30px;
        }

        .print-button {
            background: linear-gradient(90deg, #00596b 0%, #0081a7 100%);
            color: white;
            border: none;
            padding: 15px 40px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 6px 20px rgba(0, 89, 107, 0.3);
            letter-spacing: 1px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .print-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 89, 107, 0.4);
        }

        .print-button:active {
            transform: translateY(-1px);
        }

        .watermark {
            position: absolute;
            bottom: 20px;
            right: 20px;
            font-size: 12px;
            color: rgba(0, 0, 0, 0.1);
            font-weight: 700;
            letter-spacing: 2px;
            transform: rotate(-45deg);
            user-select: none;
        }



        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        @media print {
            body {
                background: white !important;
            }

            .ticket-container {
                max-width: 100%;
                box-shadow: none;
            }

            .print-section,
            .print-button {
                display: none;
            }

            .ticket-card {
                box-shadow: none;
                border: 2px solid #000;
            }

            .watermark {
                opacity: 0.3;
            }


        }

        @media (max-width: 768px) {
            .ticket-body {
                padding: 20px;
            }

            .journey-section {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .journey-arrow {
                transform: rotate(90deg);
            }

            .header-content {
                flex-direction: column;
                text-align: center;
                gap: 20px;
            }

            .company-info {
                flex-direction: column;
            }

            .details-section {
                grid-template-columns: 1fr;
            }

            .seat-display .seat-number {
                font-size: 48px;
            }
        }

        /* Important Notes Section - Right Side */
        .important-notes-sidebar {
            position: fixed;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            width: 280px;
            background: linear-gradient(135deg, #fff9e6 0%, #fff3cd 100%);
            border: 2px solid #ffc107;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 8px 25px rgba(255, 193, 7, 0.2);
            z-index: 1000;
            animation: slideInRight 0.5s ease-out;
        }

        .important-notes-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #ffc107;
        }

        .important-notes-header i {
            font-size: 18px;
            color: #856404;
        }

        .important-notes-header strong {
            color: #856404;
            font-size: 15px;
            font-weight: 700;
        }

        .important-notes-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .important-notes-list li {
            position: relative;
            padding-left: 25px;
            margin-bottom: 12px;
            font-size: 13px;
            color: #856404;
            line-height: 1.5;
        }

        .important-notes-list li:before {
            content: "•";
            position: absolute;
            left: 0;
            color: #ffc107;
            font-size: 20px;
            line-height: 1;
        }

        .important-notes-list li:last-child {
            margin-bottom: 0;
        }

        /* Close button for mobile */
        .notes-close-btn {
            display: none;
            position: absolute;
            top: 10px;
            right: 10px;
            background: none;
            border: none;
            color: #856404;
            cursor: pointer;
            font-size: 16px;
            padding: 5px;
        }

        /* Hover effect */
        .important-notes-sidebar:hover {
            transform: translateY(-50%) scale(1.02);
            box-shadow: 0 12px 30px rgba(255, 193, 7, 0.3);
            transition: all 0.3s ease;
        }

        /* Animation */
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(100px) translateY(-50%);
            }

            to {
                opacity: 1;
                transform: translateX(0) translateY(-50%);
            }
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .important-notes-sidebar {
                position: relative;
                right: auto;
                top: auto;
                transform: none;
                width: 100%;
                max-width: 400px;
                margin: 25px auto 0;
                animation: fadeIn 0.5s ease-out;
            }

            .important-notes-sidebar:hover {
                transform: scale(1.02);
            }

            .notes-close-btn {
                display: block;
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Optional: Collapsible version */
        .important-notes-collapsible {
            cursor: pointer;
            position: fixed;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            background: #ffc107;
            color: #856404;
            padding: 15px 10px;
            border-radius: 8px 0 0 8px;
            writing-mode: vertical-rl;
            text-orientation: mixed;
            font-weight: bold;
            box-shadow: -3px 0 15px rgba(0, 0, 0, 0.1);
            z-index: 999;
        }

        .important-notes-collapsible:hover {
            background: #ffca2c;
        }

        .notes-content {
            display: none;
            position: fixed;
            right: 50px;
            top: 50%;
            transform: translateY(-50%);
            width: 300px;
            background: linear-gradient(135deg, #fff9e6 0%, #fff3cd 100%);
            border: 2px solid #ffc107;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 8px 25px rgba(255, 193, 7, 0.2);
            z-index: 1000;
        }

        .notes-content.active {
            display: block;
            animation: slideInRight 0.3s ease-out;
        }

        .backButton {
            position: absolute;
            top: 0px;
            left: 0px;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 600;
            z-index: 10;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <div class="ticket-container">
        <div class="ticket-card">
            <a class="backButton" href=<?php echo BASE_URL . '/pages/public/home.php'; ?>> <i
                    class="fas fa-long-arrow-alt-left"></i>
            </a>
            <!-- Header -->
            <div class="ticket-header">
                <div class="header-content">
                    <div class="company-info">
                        <div class="logo-container">
                            <img src="<?php echo $logoPath; ?>" alt="Dhading Bus Sewa Logo">
                        </div>
                        <div class="company-details">
                            <h1><?php echo SITE_NAME; ?></h1>
                            <div class="tagline">Safe • Comfortable • Reliable</div>
                        </div>
                    </div>
                    <div class="ticket-id">
                        <h2>E-TICKET</h2>
                        <div class="badge">BOOKING CONFIRMED</div>
                    </div>
                </div>
            </div>

            <!-- Body -->
            <div class="ticket-body">
                <div class="ticket-id-value"><strong>#<?php echo $reservation['id']; ?></strong></div>

                <!-- Passenger Information -->
                <div class="passenger-section">
                    <div class="section-title">Passenger Information</div>

                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">Full Name</div>
                            <div class="info-value"><?php echo htmlspecialchars($reservation['passenger_name']); ?>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Contact Number</div>
                            <div class="info-value">+977
                                <?php echo htmlspecialchars($reservation['passenger_phone']); ?>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">Booking Date</div>
                            <div class="info-value"><?php echo $bookingDate; ?></div>
                        </div>
                    </div>
                </div>

                <!-- Journey Details -->
                <div class="journey-section">
                    <div class="location">
                        <h3><?php echo htmlspecialchars($reservation['route_from']); ?></h3>
                        <div class="time">Departure: <?php echo $departureTime; ?></div>
                        <div class="time"><?php echo $departureDate; ?></div>
                    </div>
                    <div class="journey-arrow">
                        <i class="fas fa-long-arrow-alt-right"></i>
                    </div>
                    <div class="location">
                        <h3><?php echo htmlspecialchars($reservation['route_to']); ?></h3>
                        <div class="time">Est. Arrival: <?php echo $estimatedArrival; ?></div>
                        <div class="time"><?php echo $departureDate; ?></div>


                    </div>
                </div>

                <!-- Details Grid -->
                <div class="details-section">
                    <div class="detail-box">
                        <div class="detail-label">Bus Details</div>
                        <div class="detail-value"><?php echo htmlspecialchars($reservation['bus_name']); ?></div>
                        <div style="font-size: 14px; color: #495057; margin-top: 5px;">
                            <?php echo htmlspecialchars($reservation['bus_number']); ?>
                        </div>
                    </div>

                    <div class="detail-box">
                        <div class="detail-label">Transaction ID</div>
                        <div class="detail-value" style="font-size: 14px;">
                            <?php echo htmlspecialchars($reservation['transaction_id']); ?>
                        </div>
                    </div>

                    <div class="detail-box">
                        <div class="detail-label">Payment Method</div>
                        <div class="detail-value"><?php echo htmlspecialchars($reservation['payment_method']); ?></div>
                        <div style="font-size: 14px; color: #cf1111ff; margin-top: 5px; font-weight: 600;">
                            <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($reservation['status']); ?>
                        </div>
                    </div>

                    <div class="detail-box payment-box">
                        <div class="detail-label">Total Amount </div>
                        <div class="detail-value">Rs. <?php echo number_format($reservation['total_amount'], 2); ?>
                        </div>
                    </div>
                </div>

                <!-- Seat  -->
                <div class="seat-display">
                    <div class="info" style="font-size: 14px; color: #eed232ff; margin-top: 5px; font-weight: 600;">
                        <?php echo htmlspecialchars($reservation['status']) === "pending" ? "Please Pay at the Counter before boarding the bus" : "Thank you for choosing Us"; ?>
                    </div>
                    <div class="seat-label">Your Seat Number</div>
                    <div class="seat-number"><?php echo htmlspecialchars($reservation['seat_number']); ?></div>
                </div>


                <div class="important-notes-collapsible" onclick="toggleNotes()">
                    <i class="fas fa-info-circle"></i> IMPORTANT NOTES
                </div>

                <div class="notes-content" id="notesContent">
                    <div class="important-notes-header">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Important Notes:</strong>
                    </div>
                    <ul class="important-notes-list">
                        <li>Arrive at boarding point 30 minutes before departure</li>
                        <li>Carry valid photo ID and this e-ticket</li>
                        <li>No refund for no-show or late arrival</li>
                        <li>Maximum 15kg luggage allowed per passenger</li>
                        <li>Face mask is mandatory throughout the journey</li>
                    </ul>
                    <button class="notes-close-btn" onclick="toggleNotes()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <script>
                    function toggleNotes() {
                        const notesContent = document.getElementById('notesContent');
                        notesContent.classList.toggle('active');
                    }
                </script>
            </div>

            <!-- Footer -->
            <div class="ticket-footer">
                <div class="footer-note">
                    <i class="fas fa-shield-alt"></i> This is a computer-generated ticket. No signature required.<br>
                    For cancellations or queries, contact our customer support.
                </div>
                <div class="contact-info">
                    <div class="contact-item">
                        <i class="fas fa-phone-alt"></i>
                        <span>Customer Support: +977 1-1234567</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <span>Email: support@dhadingbussewa.com</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Kathmandu, Nepal</span>
                    </div>
                </div>
            </div>

            <div class="watermark">DHADING BUS SEWA</div>
        </div>

        <!-- Print Button -->
        <div class="print-section">
            <button class="print-button" onclick="window.print()">
                <i class="fas fa-print"></i> PRINT TICKET
            </button>
            <button class="print-button" onclick="downloadTicket()"
                style="background: linear-gradient(90deg, #28a745 0%, #20c997 100%); margin-left: 15px;">
                <i class="fas fa-download"></i> DOWNLOAD
            </button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Add animation on load
            const ticket = document.querySelector('.ticket-card');
            ticket.style.opacity = '0';
            ticket.style.transform = 'translateY(20px)';

            setTimeout(() => {
                ticket.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                ticket.style.opacity = '1';
                ticket.style.transform = 'translateY(0)';
            }, 100);
        });

        // Download ticket as image
        function downloadTicket() {
            const ticketCard = document.querySelector('.ticket-card');
            const printButton = document.querySelector('.print-section');

            // Hide print button temporarily
            printButton.style.display = 'none';

            // Use html2canvas to capture ticket
            if (typeof html2canvas !== 'undefined') {
                html2canvas(ticketCard).then(canvas => {
                    const link = document.createElement('a');
                    link.download = 'Ticket-<?php echo $reservation['id']; ?>.png';
                    link.href = canvas.toDataURL('image/png');
                    link.click();
                    printButton.style.display = 'block';
                });
            } else {
                // Load html2canvas if not available
                const script = document.createElement('script');
                script.src = 'https://html2canvas.hertzen.com/dist/html2canvas.min.js';
                script.onload = function () {
                    html2canvas(ticketCard).then(canvas => {
                        const link = document.createElement('a');
                        link.download = 'Ticket-<?php echo $reservation['id']; ?>.png';
                        link.href = canvas.toDataURL('image/png');
                        link.click();
                        printButton.style.display = 'block';
                    });
                };
                document.head.appendChild(script);
                printButton.style.display = 'block';
            }
        }

        // Add keyboard shortcut for print (Ctrl+P)
        document.addEventListener('keydown', function (e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                e.preventDefault();
                window.print();
            }
        });
    </script>

    <!-- Optional: Add html2canvas for download functionality -->
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
</body>

</html>