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

      <!-- Header -->
      <header class="about-hero">
        <div>
          <h1 id="about-title">About <?php echo htmlspecialchars(SITE_NAME ?? 'Our Service'); ?></h1>
          <p class="lead">Making bus travel easier, safer, and faster — real-time seat bookings, verified operators, and round-the-clock support.</p>
        </div>
        <div>
          <!-- Small logo / badge (optional) -->
          <div style="background:linear-gradient(90deg,var(--accent),var(--brand)); color:white; padding:.6rem 1rem; border-radius:8px; font-weight:700;">
            Trusted Bus Booking
          </div>
        </div>
      </header>

      <!-- Main content -->
      <div class="about-main">

        <!-- Left: descriptive content -->
        <div class="about-text">
          <h2>Who we are</h2>
          <p>
            <?php echo htmlspecialchars(SITE_NAME ?? 'Our Service'); ?> connects travellers with verified bus operators across multiple routes.
            We provide a fast, secure booking experience with real-time seat availability and clear cancellation policies.
          </p>

          <h2>Our mission</h2>
          <p>
            To reduce travel friction by giving passengers transparent schedules, simple seat selection, and responsive customer support.
            We improve both short commutes and long-distance trips with dependable information and secure payments.
          </p>

          <div class="about-features" role="list">
            <div class="feature" role="listitem">
              <div style="width:10px;height:10px;border-radius:50%;background:var(--brand);margin-top:4px;"></div>
              <div>
                <div>Real-time seat availability</div>
                <small>Interactive previews so you never book a mystery seat.</small>
              </div>
            </div>

            <div class="feature" role="listitem">
              <div style="width:10px;height:10px;border-radius:50%;background:var(--brand);margin-top:4px;"></div>
              <div>
                <div>Secure payments</div>
                <small>Encrypted checkout and trusted payment partners.</small>
              </div>
            </div>

            <div class="feature" role="listitem">
              <div style="width:10px;height:10px;border-radius:50%;background:var(--brand);margin-top:4px;"></div>
              <div>
                <div>24/7 support</div>
                <small>Help when you need it — before, during, and after booking.</small>
              </div>
            </div>
          </div>
        </div>

        <!-- Right: image -->
        <aside class="about-image" aria-hidden="false">
          <img src="<?php echo HOME_BANNER_IMG_URL; ?>"
               alt="Team and service overview - <?php echo htmlspecialchars(SITE_NAME ?? 'Our Service'); ?>"
               onerror="this.src='https://via.placeholder.com/640x480?text=Image+Not+Found';" />
        </aside>

      </div>

      <!-- Panel with stats + CTA -->
      <div class="about-panel">
        <div class="stats" aria-hidden="false">
          <div class="stat">
            <b>12+</b>
            <small>Routes served</small>
          </div>
          <div class="stat">
            <b>50k+</b>
            <small>Bookings processed</small>
          </div>
          <div class="stat">
            <b>24/7</b>
            <small>Customer support</small>
          </div>
        </div>

        <div class="cta-group" role="group" aria-label="Actions">
          <button class="btn btn-primary" id="btn-contact">Contact Support</button>
          <button class="btn btn-ghost" id="btn-read">Read more</button>
        </div>
      </div>

      <!-- Collapsible "read more" area -->
      <div class="more" id="more-section" aria-hidden="true">
        <p>
          We partner with licensed and verified bus operators and run periodic checks to ensure service quality.
          Our platform aims to show accurate arrival and departure times, estimated journey lengths, and clear fare breakdowns.
        </p>
        <p>
          Accessibility is important to us. The booking flow is designed for keyboard navigation and mobile ease-of-use.
          If you need special assistance while booking, contact our support team and we will help arrange accommodations.
        </p>
        <p>
          Interested to partner with us as an operator? We offer a simple onboarding process and reporting dashboard to track trips, revenue, and customer feedback.
        </p>
      </div>

    </div>
  </section>


<?php include '../../UI/components/Footer.php'; ?>


  <!-- Minimal JS for interactivity -->
  <script>
    (function () {
      const readBtn = document.getElementById('btn-read');
      const more = document.getElementById('more-section');
      const contactBtn = document.getElementById('btn-contact');

      readBtn.addEventListener('click', function () {
        const open = more.classList.toggle('open');
        more.setAttribute('aria-hidden', String(!open));
        readBtn.textContent = open ? 'Show less' : 'Read more';
      });

      contactBtn.addEventListener('click', function () {
        // Use your site's contact route if available; fallback to mailto
        const contactUrl = '<?php echo defined("BASE_URL") ? BASE_URL . "/pages/public/contact.php" : "mailto:support@bus.com"; ?>';
        // If contactUrl begins with http or /, navigate; otherwise open mail client.
        if (/^(https?:|\/)/.test(contactUrl)) {
          window.location.href = contactUrl;
        } else {
          window.location.href = contactUrl;
        }
      });
    })();
  </script>