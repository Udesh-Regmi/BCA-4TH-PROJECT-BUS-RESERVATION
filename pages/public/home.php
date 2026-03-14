<?php
require_once '../../config/constants.php';
require_once '../../includes/session.php';
$pageTitle = "Home - " . SITE_NAME;
include '../../UI/components/Header.php';
include '../../UI/components/Navbar.php';
?>

<div class="homepage-wrapper">

    <!-- HERO SECTION -->
    <section class="hero-section">
        <div class="hero-text-content">
            <h1 class="hero-title">Your Journey Starts With <?php echo SITE_NAME; ?></h1>
            <p class="hero-subtitle">
                Experience reliable, affordable and comfortable travel across the country.
                Book seats instantly and enjoy stress-free travel with our modern fleet of premium buses.
            </p>
            <a href="<?= BASE_URL ?>/pages/public/viewbus.php" class="hero-primary-btn">Explore Buses</a>
        </div>

        <div class="hero-image-container">
            <img src="<?php echo HOME_BANNER_IMG_URL; ?>" alt="Luxury Bus" class="hero-banner-image" />
        </div>
    </section>


    <!-- ABOUT COMPANY SECTION -->
    <section class="company-section">
        <h2 class="section-title">Our Motto</h2>
        <p class="company-description">
            <?php echo SITE_NAME; ?> is dedicated to redefining road travel through innovation, comfort and
            dependability.
            With a strong commitment to safety and quality service, we ensure every journey is smooth, secure and
            memorable.
        </p>
    </section>


    <!-- SERVICE FEATURES -->
    <section class="service-features-section">
        <div class="feature-card">
            <h3 class="feature-title">Simple Online Booking</h3>
            <p class="feature-text">
                Find routes, choose your seat and book in just a few clicks.
            </p>
        </div>

        <div class="feature-card">
            <h3 class="feature-title">Premium Comfort</h3>
            <p class="feature-text">
                Spacious seats, AC coaches and modern interiors for a relaxing journey.
            </p>
        </div>

        <div class="feature-card">
            <h3 class="feature-title">Safety First</h3>
            <p class="feature-text">
                Experienced drivers, regular bus inspections and real-time monitoring.
            </p>
        </div>

        <div class="feature-card">
            <h3 class="feature-title">Secure Payments</h3>
            <p class="feature-text">
                Multiple trusted payment options with encrypted transactions.
            </p>
        </div>
         <div class="feature-card">
            <h3 class="feature-title">Easy Ticketing</h3>
            <p class="feature-text">
                Fast and hastle free online ticket booking and management.
            </p>
        </div>

        <div class="feature-card">
            <h3 class="feature-title">Support</h3>
            <p class="feature-text">
                24/7 customer support to assist you before, during, and after your trip.
            </p>
        </div>
    </section>


    <!-- COMPANY TIMELINE -->
    <section class="timeline-section">
        <h2 class="section-title">Our Journey</h2>

        <div class="timeline-container">       

            <div class="timeline-item">
                <h3 class="timeline-year">2025</h3>
                <p class="timeline-text">
                    Founded <?php echo SITE_NAME; ?> with a vision to revolutionize online bus ticketing and travel experience and expanded to 5+ routes nationwide with a fleet of 10+ modern buses
                </p>
            </div>



            <div class="timeline-item">
                <h3 class="timeline-year">2026</h3>
                <p class="timeline-text">
                   Goal to revolutionizing the bus industry with live payment gateway and  AI powered user details & route optimization and premium passenger services.
                </p>
            </div>

        </div>
    </section>

    <!-- CTA SECTION -->
    <section class="cta-section">
        <h2 class="cta-title">Ready to Plan Your Journey?</h2>
        <p class="cta-text">Book your ticket now and enjoy comfortable, affordable travel with our premium bus service across the country.</p>
        <a href="<?= BASE_URL ?>/pages/public/viewbus.php" class="cta-link">
            Explore Buses Now
            <span>→</span>
        </a>
    </section>


    <!-- TESTIMONIALS SECTION -->
    <section class="testimonial-section">
        <h2 class="section-title">What Our Customers Say</h2>

        <div class="testimonial-container">

            <div class="testimonial-card">
                <img src="https://c8r9b7bq24.ufs.sh/f/Z9wyzBEmJFbSJKaPGIXCczYUs8R5S4WE1KJ2NTAjmoyrHDqb"
                    alt="Sital Dangaura">
                <p class="testimonial-text">
                    "The seats were super comfortable and the booking system was so easy to use!"
                </p>
                <h4 class="testimonial-author">Sital Dangaura</h4>
            </div>

            <div class="testimonial-card">
                <img src="https://c8r9b7bq24.ufs.sh/f/Z9wyzBEmJFbSxULsMZjBpu6NqEiQcZj5Tb2YV3UM9KHWDlgz"
                    alt="Ranjana Khanal">
                <p class="testimonial-text">
                    "Great service, friendly staff and the bus arrived exactly on time."
                </p>
                <h4 class="testimonial-author">Ranjana Khanal</h4>
            </div>

            <div class="testimonial-card">
                <img src="https://scontent-lga3-3.xx.fbcdn.net/v/t39.30808-6/649580677_4456035597961172_162104780891670108_n.jpg?stp=dst-jpg_p526x296_tt6&_nc_cat=110&ccb=1-7&_nc_sid=c7cdda&_nc_eui2=AeFD2HA9uu2bVeJ1A2J2UIkILPTrIYuBci0s9Oshi4FyLS00N1DUFdoO0eLJF0JZqoxEEEAcmycI5sIN2gxwvLQv&_nc_ohc=ceN6lh6rBb4Q7kNvwFubphl&_nc_oc=AdnVB4DtT0Val7PgwVFCi4bxv12Qa8ls56bAiIVHtYiHJDXFfbievkyYqo3v-QYw-1g&_nc_zt=23&_nc_ht=scontent-lga3-3.xx&_nc_gid=-_qQXC5-3-_-QRH5Qc40YA&_nc_ss=8&oh=00_AfwuT5IDYPsfh0as4VRJU18FnxwcE5y4xuYaNrVPBkKGUw&oe=69B92B83" 
                    alt="Sabitri Pandit">
                <p class="testimonial-text">
                    "Highly reliable travel partner. I use them every month for my work trips."
                </p>
                <h4 class="testimonial-author">Sabitri Pandit</h4>
            </div>

        </div>
    </section>

</div>

<?php include '../../UI/components/Footer.php'; ?>