<!-- PAGES/PUBLIC/CONTACT.PHP -->
<?php
require_once '../../config/constants.php';
require_once '../../includes/session.php';
$pageTitle = "Contact Us - " . SITE_NAME;
include '../../UI/components/Header.php';
include '../../UI/components/Navbar.php';
?>

<div class="contact-page-container">
    <div class="contact-container">
        <div class="contact-info">
            <h1 class="page-title">Contact Us</h1>

            <div class="contact-details">
                <div>
                    <h3><i class="fas fa-map-marker-alt"></i> Address</h3>
                    <p><strong>Dhading Branch:</strong> Koirale Chautari, Dhading, Nepal</p>
                    <p><strong>Kathmandu Branch:</strong> Main Bus Park, Kathmandu, Nepal</p>
                </div>

                <div>
                    <h3><i class="fas fa-phone"></i> Phone</h3>
                    <p>+977 9841234567 (Dhading Office)</p>
                    <p>+977 9847654321 (Kathmandu Office)</p>
                </div>

                <div>
                    <h3><i class="fas fa-envelope"></i> Email</h3>
                    <p>support@<?php echo str_replace(' ', '', strtolower(SITE_NAME)); ?>.com</p>
                    <p>booking@<?php echo str_replace(' ', '', strtolower(SITE_NAME)); ?>.com</p>
                </div>

                <div>
                    <h3><i class="fas fa-clock"></i> Business Hours</h3>
                    <p>Monday - Friday: 6:00 AM - 9:00 PM</p>
                    <p>Saturday - Sunday: 7:00 AM - 8:00 PM</p>
                </div>
            </div>
        </div>

        <div class="social-links">
            <h3><i class="fas fa-share-alt"></i> Follow Us</h3>
            <p>Connect with us for route updates and announcements.</p>

            <ul>
                <li>
                    <a href="https://www.facebook.com/udesh.regmi.3" target="_blank" rel="noopener noreferrer">
                        <i class="fab fa-facebook"></i>
                        <span>Facebook</span>
                    </a>
                </li>
                <li>
                    <a href="https://www.instagram.com/sngmsedai/" target="_blank" rel="noopener noreferrer">
                        <i class="fab fa-instagram"></i>
                        <span>Instagram</span>
                    </a>
                </li>
                <li>
                    <a href="https://www.linkedin.com/in/udesh-regmi/" target="_blank" rel="noopener noreferrer">
                        <i class="fab fa-linkedin"></i>
                        <span>LinkedIn</span>
                    </a>
                </li>
                <li>
                    <a href="https://x.com/" target="_blank" rel="noopener noreferrer">
                        <i class="fab fa-x-twitter"></i>
                        <span>X / Twitter</span>
                    </a>
                </li>
            </ul>

            <div class="map-info">
                <p><i class="fas fa-headset"></i> 24/7 Customer Support: +977 9801234567</p>
            </div>
        </div>

        <div class="map-container">
            <h3><i class="fas fa-map-marked-alt"></i> Our Locations</h3>

            <div class="maps-wrapper">
                <div class="map-item">
                    <h4><i class="fas fa-bus"></i> Dhading Bus Station</h4>
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1765.4418602812605!2d85.04902973771094!3d27.751732306033794!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39eb29002ce88d8f%3A0x1f564d9ba0ddcda4!2sKoirale%20Chautari!5e0!3m2!1sen!2sus!4v1765122385869!5m2!1sen!2sus"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                    <p>Bus Stop in Dhading</p>
                </div>

                <div class="map-item">
                    <h4><i class="fas fa-bus"></i> Gongabu Bus Park</h4>
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d12831.805213832033!2d85.30928154629831!3d27.7285672087317!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39eb18dc1d3ae109%3A0x18613d6dc3511853!2sGongabu%20Bus%20Park!5e0!3m2!1sen!2sus!4v1766327971533!5m2!1sen!2sus"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                    <p>Major bus stop in Kathmandu</p>
                </div>
            </div>

            <div class="map-info">
                <p><i class="fas fa-info-circle"></i> Our stations are centrally located and easy to reach via public transport.</p>
            </div>
        </div>
    </div>
</div>

<?php include '../../UI/components/Footer.php'; ?>
