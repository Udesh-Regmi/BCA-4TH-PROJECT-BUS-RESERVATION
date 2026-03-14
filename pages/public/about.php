<!-- PAGES/PUBLIC/ABOUT.PHP -->
<?php
require_once '../../config/constants.php';
require_once '../../includes/session.php';
$pageTitle = "About Us - " . SITE_NAME;
include '../../UI/components/Header.php';
include '../../UI/components/Navbar.php';
?>

<section id="about-page" aria-labelledby="about-title">
    <div class="about-container">
        <header class="about-hero">
            <div>
                <h1 id="about-title">About <?php echo htmlspecialchars(SITE_NAME ?? 'Our Service'); ?></h1>
                <p class="lead">Making bus travel easier, safer, and faster with verified operators and reliable support.</p>
            </div>
            <div>
                <span class="about-badge">Trusted Bus Booking</span>
            </div>
        </header>

        <div class="about-main">
            <div class="about-text">
                <h2>Who we are</h2>
                <p>
                    <?php echo htmlspecialchars(SITE_NAME ?? 'Our Service'); ?> connects travellers with verified bus operators across multiple routes.
                    We provide real-time seat availability, secure reservations, and clear trip details.
                </p>

                <h2>Our mission</h2>
                <p>
                    We simplify travel with transparent schedules, easy booking, and dependable customer support.
                    Our goal is to make bus travel smooth, predictable, and accessible for everyone.
                </p>

                <div class="about-features" role="list">
                    <div class="feature" role="listitem">
                        <div>•</div>
                        <div>
                            <div>Real-time availability</div>
                            <small>See current seat status before confirming your booking.</small>
                        </div>
                    </div>
                    <div class="feature" role="listitem">
                        <div>•</div>
                        <div>
                            <div>Secure payments</div>
                            <small>Safe checkout with reliable payment workflows.</small>
                        </div>
                    </div>
                    <div class="feature" role="listitem">
                        <div>•</div>
                        <div>
                            <div>Reliable support</div>
                            <small>Help available for booking and trip-related issues.</small>
                        </div>
                    </div>
                </div>
            </div>

            <aside class="about-image" aria-hidden="false">
                <img src="<?php echo HOME_BANNER_IMG_URL; ?>"
                     alt="Service overview - <?php echo htmlspecialchars(SITE_NAME ?? 'Our Service'); ?>"
                     onerror="this.src='https://via.placeholder.com/640x480?text=Image+Not+Found';" />
            </aside>
        </div>

        <div class="about-panel">
            <div class="stats" aria-hidden="false">
                <div class="stat">
                    <b>5+</b>
                    <small>Routes served</small>
                </div>
                <div class="stat">
                    <b>1K+</b>
                    <small>Bookings processed</small>
                </div>
                <div class="stat">
                    <b>24/7</b>
                    <small>Customer support</small>
                </div>
            </div>

            <div class="cta-group" role="group" aria-label="Actions">
                <button class="btn btn-primary" id="btn-contact" type="button">Contact Support</button>
                <button class="btn btn-ghost" id="btn-read" type="button">Read more</button>
            </div>
        </div>

        <div class="more" id="more-section" aria-hidden="true">
            <p>
                We work with verified operators and focus on accurate schedules and fair pricing.
            </p>
            <p>
                Our booking flow is mobile friendly, and we continue improving speed, accessibility, and reliability.
            </p>
            <p>
                For partnership opportunities, contact our team through the contact page.
            </p>
        </div>
    </div>
</section>

<?php include '../../UI/components/Footer.php'; ?>

<script>
(function () {
    const readBtn = document.getElementById('btn-read');
    const more = document.getElementById('more-section');
    const contactBtn = document.getElementById('btn-contact');

    if (readBtn && more) {
        readBtn.addEventListener('click', function () {
            const open = more.classList.toggle('open');
            more.setAttribute('aria-hidden', String(!open));
            readBtn.textContent = open ? 'Show less' : 'Read more';
        });
    }

    if (contactBtn) {
        contactBtn.addEventListener('click', function () {
            window.location.href = '<?php echo BASE_URL; ?>/pages/public/contact.php';
        });
    }
})();
</script>
