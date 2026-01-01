<!-- PAGES/ADMIN/BUSES/CREATE.PHP -->
<?php
require_once '../../../config/database.php';
require_once '../../../config/constants.php';
require_once '../../../includes/session.php';
require_once '../../../includes/functions.php';
require_once '../../../middleware/admin.php';

$pageTitle = "Add Bus - " . SITE_NAME;
$additionalCSS = "admin.css";
include '../../../UI/components/Header.php';
include '../../../UI/components/Navbar.php';
include '../../../UI/components/Alert.php';
?>

<div class="dashboard-layout">
    <?php include '../../../UI/components/Sidebar.php'; ?>
    
    <main class="dashboard-content">
        <h1 >Add New Bus</h1>
        
        <div class="form-card">
            <form method="POST" action="<?php echo BASE_URL; ?>/controllers/BusController.php">
                <input type="hidden" name="action" value="create">
                <div class="form-row">
                    <div class="form-group">
                        <label for="bus_number">Bus Number</label>
                        <input type="text" id="bus_number" name="bus_number" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="bus_name">Bus Name</label>
                        <input type="text" id="bus_name" name="bus_name" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="route_from">Route From</label>
                        <input type="text" id="route_from" name="route_from" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="route_to">Route To</label>
                        <input type="text" id="route_to" name="route_to" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="departure_time">Departure Time</label>
                        <input type="time" id="departure_time" name="departure_time" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="arrival_time">Arrival Time</label>
                        <input type="time" id="arrival_time" name="arrival_time" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="total_seats">Total Seats</label>
                        <input type="number" id="total_seats" name="total_seats" min="1" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="price">Price (Rs)</label>
                        <input type="number" id="price" name="price" step="0.01" min="0" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status" required>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                  <div class="form-row">
                        <div class="form-group">
                        <label for="image_string">Image String</label>
                        <input type="text" id="image_string" name="image_string" value="<?php echo $busData['image_string']; ?>">
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn-submit-admin">Add Bus</button>
                    <a href="index.php" class="btn-secondary-admin">Cancel</a>
                </div>
            </form>
        </div>
    </main>
</div>

<?php include '../../../UI/components/Footer.php'; ?>
