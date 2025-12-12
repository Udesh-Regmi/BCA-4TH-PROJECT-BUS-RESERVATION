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

            <!-- Top Left: Contact Info -->
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
                        <p>bus@admin.com (General Inquiries)</p>
                        <p>bus@admindhading.com (Dhading Branch)</p>
                        <p>bus@adminkathmandu.com (Kathmandu Branch)</p>
                    </div>
                    
                    <div>
                        <h3><i class="fas fa-clock"></i> Business Hours</h3>
                        <p>Monday - Friday: 6:00 AM - 9:00 PM</p>
                        <p>Saturday - Sunday: 7:00 AM - 8:00 PM</p>
                    </div>
                </div>
            </div>

            <!-- Top Right: Social Links -->
            <div class="social-links">
                <h3><i class="fas fa-share-alt"></i> Follow Us</h3>
                <p style="margin-bottom: 1.5rem; color: var(--gray);">Connect with us on social media for updates and promotions.</p>
                
                <ul>
                    <li>
                        <a href="#" target="_blank">
                            <i class="fab fa-facebook"></i> 
                            <span>Facebook</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" target="_blank">
                            <i class="fab fa-twitter"></i> 
                            <span>Twitter</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" target="_blank">
                            <i class="fab fa-instagram"></i> 
                            <span>Instagram</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" target="_blank">
                            <i class="fab fa-linkedin"></i> 
                            <span>LinkedIn</span>
                        </a>
                    </li>
                </ul>
                
                <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #eee;">
                    <h4 style="font-size: 1.1rem; margin-bottom: 0.75rem; color: var(--primary-color);">
                        <i class="fas fa-headset"></i> 24/7 Customer Support
                    </h4>
                    <p style="color: var(--gray);">Call us anytime for urgent bus ticket inquiries: +977 9801234567</p>
                </div>
            </div>

            <!-- Bottom Section: Maps Container -->
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
                        <p style="font-size: 0.9rem; color: var(--gray); margin-top: 0.5rem;">
                            <i class="fas fa-info-circle"></i> Bus Stop in Dhading
                        </p>
                    </div>
                    
                    <div class="map-item">
                        <h4><i class="fas fa-bus"></i> Kalanki Bus Stop</h4>
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3532.7805690337764!2d85.27856311007174!3d27.69317612601876!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39eb187a97f390b1%3A0xec3f47092df0d4ca!2sKalanki%2C%20Kathmandu%2044600%2C%20Nepal!5e0!3m2!1sen!2sus!4v1765123102755!5m2!1sen!2sus"
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                        <p style="font-size: 0.9rem; color: var(--gray); margin-top: 0.5rem;">
                            <i class="fas fa-info-circle"></i> Major bus stop in Kalanki area
                        </p>
                    </div>
                </div>
                
                <div class="map-info">
                    <p><i class="fas fa-info-circle"></i> Our bus stations are conveniently located in city centers with easy access to public transportation. Both locations offer ticket booking, passenger waiting areas, and customer service desks.</p>
                </div>
            </div>

        </div>
    </div>


<?php include '../../UI/components/Footer.php'; ?>