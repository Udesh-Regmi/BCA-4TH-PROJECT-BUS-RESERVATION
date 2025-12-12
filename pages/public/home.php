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
    </section>


    <!-- COMPANY TIMELINE -->
    <section class="timeline-section">
        <h2 class="section-title">Our Journey</h2>

        <div class="timeline-container">

            <div class="timeline-item">
                <div class="timeline-dot"></div>
                <h3 class="timeline-year">2012</h3>
                <p class="timeline-text">
                    Founded with a mission to make bus travel easier and more accessible.
                </p>
            </div>

            <div class="timeline-item">
                <div class="timeline-dot"></div>
                <h3 class="timeline-year">2016</h3>
                <p class="timeline-text">
                    Expanded operations nationwide with a modern fleet upgrade.
                </p>
            </div>

            <div class="timeline-item">
                <div class="timeline-dot"></div>
                <h3 class="timeline-year">2020</h3>
                <p class="timeline-text">
                    Introduced online booking and digital ticket management.
                </p>
            </div>

            <div class="timeline-item">
                <div class="timeline-dot"></div>
                <h3 class="timeline-year">2024</h3>
                <p class="timeline-text">
                    Reached over 2 million successful seat reservations.
                </p>
            </div>
        </div>
    </section>


    <!-- TESTIMONIALS SECTION -->
    <section class="testimonial-section">
        <h2 class="section-title">What Our Customers Say</h2>

        <div class="testimonial-container">

            <div class="testimonial-card">
                <img src="https://t3.ftcdn.net/jpg/17/66/94/28/240_F_1766942879_e7DJKxitE3vUGBdn93OYhnXsCkLU3xm0.jpg"
                    alt="">

                <p class="testimonial-text">
                    “The seats were super comfortable and the booking system was so easy to use!”
                </p>

                <h4 class="testimonial-author">Aarav Sharma</h4>
            </div>

            <div class="testimonial-card">
                <img src="https://scontent-lga3-3.xx.fbcdn.net/v/t39.30808-1/465049957_1069942874575905_2553374767244751445_n.jpg?stp=c0.0.579.579a_dst-jpg_s100x100_tt6&_nc_cat=110&ccb=1-7&_nc_sid=e99d92&_nc_ohc=tZZ8nYPbjpYQ7kNvwFRvR1D&_nc_oc=AdnAw9s_-y96lXHnZxheJMB-8mu0PG1lmU-LM59s5l17pv24MTEehgHP1Js6oMQ1g18&_nc_zt=24&_nc_ht=scontent-lga3-3.xx&_nc_gid=F5eImQrzx7asjjDmRYfa7A&oh=00_AfmnCboELvlFs1Q6t3V2-Xccj2QBrauVUh7DAB3LWNo7LA&oe=693E9011"
                    alt="">
                <p class="testimonial-text">
                    “Great service, friendly staff and the bus arrived exactly on time.”
                </p>
                <h4 class="testimonial-author">Nirjala Duwadi</h4>
            </div>

            <div class="testimonial-card">
                <img src="https://t4.ftcdn.net/jpg/16/41/25/47/240_F_1641254744_POQ239xp8M3zfQBI400QGNbAtXyKPimh.jpg"
                    alt="">
                <p class="testimonial-text">
                    “Highly reliable travel partner. I use them every month for my work trips.”
                </p>
                <h4 class="testimonial-author">Ravi Chandra</h4>
            </div>

        </div>
    </section>

</div>

<?php include '../../UI/components/Footer.php'; ?>