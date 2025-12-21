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
                <img src="https://scontent-lga3-2.xx.fbcdn.net/v/t39.30808-6/472208567_122201224238217651_4548090059175145620_n.jpg?_nc_cat=100&ccb=1-7&_nc_sid=833d8c&_nc_ohc=iBqVwqCjYLUQ7kNvwF-kdou&_nc_oc=Adl1Ug7nCqjuPI6RU3bqquwjvy8sx4L3uvm5uK3ol1KpfGhy1PlX4Gh51b4StQLVJKw&_nc_zt=23&_nc_ht=scontent-lga3-2.xx&_nc_gid=iNl5XGo-KR5tuWejTnWKWA&oh=00_Afns7YbLomGwI59mY5VL3FCzGuOP46jwg-1wTAoiaF9nPA&oe=694DF01C"
                    alt="Sital Dangaura">
                <p class="testimonial-text">
                    "The seats were super comfortable and the booking system was so easy to use!"
                </p>
                <h4 class="testimonial-author">Sital Dangaura</h4>
            </div>

            <div class="testimonial-card">
                <img src="https://scontent-lga3-2.xx.fbcdn.net/v/t39.30808-6/470684087_3030984227053565_5714013013169943091_n.jpg?stp=c0.140.460.460a_dst-jpg_s206x206_tt6&_nc_cat=105&ccb=1-7&_nc_sid=714c7a&_nc_ohc=Za4_09TcjXYQ7kNvwEEWtOM&_nc_oc=AdmkEsk61G0W8V_sQTLYtmm-TTRP4iVgrz2UzyjD02PeDsawl-Tg4FDzy2McJ_L3CKU&_nc_zt=23&_nc_ht=scontent-lga3-2.xx&_nc_gid=OsKWwXvkTjDd2gaFpU3iGA&oh=00_Afm3eVJDl-aO_fbCLUm-ZowE3coiFgOM-Sc3YQni_-rHKQ&oe=694DC1FA"
                    alt="Nirjala Duwadi">
                <p class="testimonial-text">
                    "Great service, friendly staff and the bus arrived exactly on time."
                </p>
                <h4 class="testimonial-author">Nirjala Duwadi</h4>
            </div>

            <div class="testimonial-card">
                <img src="https://scontent-lga3-1.xx.fbcdn.net/v/t39.30808-1/475762861_1133330438448740_3491977969054013567_n.jpg?stp=dst-jpg_s200x200_tt6&_nc_cat=102&ccb=1-7&_nc_sid=e99d92&_nc_ohc=l7c7ggg25isQ7kNvwEzAYxW&_nc_oc=AdkxkqFBgb2Z2GL4sMIbJ5gqXYddInxP4zdlwZFAvdM1TDChOZYjXkQeS9L5ZModYKU&_nc_zt=24&_nc_ht=scontent-lga3-1.xx&_nc_gid=hViV4vGge6cokzcyit41Kw&oh=00_AfmY1ck7-NgnVz_Z3s1lbYpiwckxBLA1sUDA1XsTEY0hlA&oe=694DBCC6"
                    alt="Garima Pandey">
                <p class="testimonial-text">
                    "Highly reliable travel partner. I use them every month for my work trips."
                </p>
                <h4 class="testimonial-author">Garima Pandey</h4>
            </div>

        </div>
    </section>

</div>

<?php include '../../UI/components/Footer.php'; ?>