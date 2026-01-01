<?php
require_once '../../config/constants.php';
require_once '../../includes/session.php';
$pageTitle = "Home - " . SITE_NAME;
$additionalCSS = "home.css";
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
        </div> <div class="feature-card">
            <h3 class="feature-title">Support</h3>
            <p class="feature-text">
                24/7 customer support to assist you before, during, and after your trip.
            </p>
      
    </section>


    <!-- COMPANY TIMELINE -->
    <section class="timeline-section">
        <h2 class="section-title">Our Journey</h2>

        <div class="timeline-container">       

            <div class="timeline-item">
                <h3 class="timeline-year">2020</h3>
                <p class="timeline-text">
                    Founded Dhading Bus Sewa with a vision to transform road travel.
                </p>
            </div>

            <div class="timeline-item">
                <h3 class="timeline-year">2025</h3>
                <p class="timeline-text">
                    Expanded to 50+ routes nationwide with a fleet of 100+ modern buses.
                </p>
            </div>
        </div>
    </section>


    <!-- TESTIMONIALS SECTION -->
    <section class="testimonial-section">
        <h2 class="section-title">What Our Customers Say</h2>

        <div class="testimonial-container">

            <div class="testimonial-card">
                <img src="https://scontent-lga3-1.xx.fbcdn.net/v/t39.30808-6/472208550_122201224232217651_7102832813924357452_n.jpg?_nc_cat=102&ccb=1-7&_nc_sid=833d8c&_nc_ohc=5c8yWcyYVRkQ7kNvwHiy2uY&_nc_oc=Adn0GYdlrxGQigMGAMrdsXMs9qMW5NrlvtDDJ_F1zyNC-EuDj0S7BV634egA1Ba8Hyc&_nc_zt=23&_nc_ht=scontent-lga3-1.xx&_nc_gid=Okdl5eOy98hMMLlFc-w-8w&oh=00_AfrmBexDKeCX0Qh_XbjWx3d7-6Tp3jSdJ1zf_zqJPz_KpA&oe=695BD842"
                    alt="Sital Dangaura">
                <p class="testimonial-text">
                    "The seats were super comfortable and the booking system was so easy to use!"
                </p>
                <h4 class="testimonial-author">Sital Dangaura</h4>
            </div>

            <div class="testimonial-card">
                <img src="https://scontent-lga3-3.xx.fbcdn.net/v/t39.30808-1/465049957_1069942874575905_2553374767244751445_n.jpg?stp=c0.0.579.579a_cp0_dst-jpg_s60x60_tt6&_nc_cat=110&ccb=1-7&_nc_sid=e99d92&_nc_ohc=1eU3OnCLngoQ7kNvwFppxoQ&_nc_oc=AdliBeCweSJqt9pumD6f27g_TKk7Av_OCpgbAW3gTBYtjr5sEzXLiEgNN6m1JId2yx8&_nc_zt=24&_nc_ht=scontent-lga3-3.xx&_nc_gid=AkzzxUtNimqiC8YvPDXE_Q&oh=00_AfpvTKFzXusOqhYMgYbbEyngTKvl6nbToQq98XmqS8etuQ&oe=695C0191"
                    alt="Nirjala Duwadi">
                <p class="testimonial-text">
                    "Great service, friendly staff and the bus arrived exactly on time."
                </p>
                <h4 class="testimonial-author">Nirjala Duwadi</h4>
            </div>

            <div class="testimonial-card">
                <img src="https://scontent-lga3-2.xx.fbcdn.net/v/t39.30808-1/539292853_1306427741221585_5599924508912078577_n.jpg?stp=dst-jpg_s200x200_tt6&_nc_cat=100&ccb=1-7&_nc_sid=e99d92&_nc_ohc=tzTlvwTV56sQ7kNvwFnpsjW&_nc_oc=AdlmTWNgdQx2x3JrM75REb2usQnpULvwH_OKDI5SHmo1I8PiB3WvkKj6qOp9PIIE88o&_nc_zt=24&_nc_ht=scontent-lga3-2.xx&_nc_gid=frE9tPffjdU3N3isCJqGoQ&oh=00_Afrh-caBOOB1BZjBLGkD3BP9jceO8tir8JyBFz1uPrjn3g&oe=695C017D" 
                    alt="Ranjit Nepal">
                <p class="testimonial-text">
                    "Highly reliable travel partner. I use them every month for my work trips."
                </p>
                <h4 class="testimonial-author">Ranjit Nepal</h4>
            </div>

        </div>
    </section>

</div>

<?php include '../../UI/components/Footer.php'; ?>