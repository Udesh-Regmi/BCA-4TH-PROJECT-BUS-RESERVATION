<!--  PAGES/ADMIN/BUSES/INDEX.PHP -->
<?php
require_once '../../../config/database.php';
require_once '../../../config/constants.php';
require_once '../../../includes/session.php';
require_once '../../../includes/functions.php';
require_once '../../../models/Bus.php';
require_once '../../../middleware/admin.php';

$database = new Database();
$db = $database->getConnection();
$bus = new Bus($db);

$buses = $bus->getAll();

$pageTitle = "Manage Buses - " . SITE_NAME;
$additionalCSS = "admin.css";
include '../../../UI/components/Header.php';
include '../../../UI/components/Navbar.php';
include '../../../UI/components/Alert.php';
?>

<div class="dashboard-layout">
    <?php include '../../../UI/components/Sidebar.php'; ?>
    
    <main class="dashboard-content">
        <div class="page-header">
            <h1>Manage Buses</h1>
            <a href="create.php" class="btn-primary-admin">Add New Bus</a>
        </div>
        
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Bus Number</th>
                        <th>Bus Name</th>
                        <th>Route</th>
                        <th>Departure</th>
                        <th>Arrival Time</th>
                        <th>Seats</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($buses)): ?>
                        <tr><td colspan="8" class="text-center">No buses found</td></tr>
                    <?php else: ?>
                        <?php foreach ($buses as $busItem): ?>
                            <tr>
                                <td><?php echo $busItem['bus_number']; ?></td>
                                <td><?php echo $busItem['bus_name']; ?></td>
                                <td><?php echo $busItem['route_from'] . ' → ' . $busItem['route_to']; ?></td>
                                <td><?php echo formatTime($busItem['departure_time']); ?></td>
                               <td><?php echo formatTime($busItem['arrival_time']); ?></td>

                                <td><?php echo   $busItem['total_seats']; ?></td>
                                <td>Rs<?php echo number_format($busItem['price'], 2); ?></td>
                                <td><span class="badge badge-<?php echo $busItem['status']; ?>"><?php echo ucfirst($busItem['status']); ?></span></td>
                                <td class="actions">
                                    <a href="edit.php?id=<?php echo $busItem['id']; ?>" class="btn-sm btn-warning">Edit</a>
                                    <form method="POST" action="<?php echo BASE_URL; ?>/controllers/BusController.php" style="display:inline;">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?php echo $busItem['id']; ?>">
                                        <button type="submit" class="btn-sm btn-danger" onclick="return confirm('Delete this bus?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

<?php include '../../../UI/components/Footer.php'; ?>