<!-- PAGES/ADMIN/USERS/INDEX.PHP -->
<?php
require_once '../../../config/database.php';
require_once '../../../config/constants.php';
require_once '../../../includes/session.php';
require_once '../../../includes/functions.php';
require_once '../../../models/User.php';
require_once '../../../middleware/admin.php';

$database = new Database();
$db = $database->getConnection();
$user = new User($db);

$users = $user->getAll();

$pageTitle = "Manage Users - " . SITE_NAME;
$additionalCSS = "admin.css";
include '../../../UI/components/Header.php';
include '../../../UI/components/Navbar.php';
include '../../../UI/components/Alert.php';
?>

<div class="dashboard-layout">
    <?php include '../../../UI/components/Sidebar.php'; ?>
    
    <main class="dashboard-content">
        <h1>Manage Users</h1>
        
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Role</th>
                        <th>Joined</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($users)): ?>
                        <tr><td colspan="6" class="text-center">No users found</td></tr>
                    <?php else: ?>
                        <?php foreach ($users as $userItem): ?>
                            <tr>
                                <td><?php echo $userItem['id']; ?></td>
                                <td><?php echo $userItem['name']; ?></td>
                                <td><?php echo $userItem['email']; ?></td>
                                <td><?php echo $userItem['phone'] ?? 'N/A'; ?></td>
                                <td><span class="badge badge-<?php echo $userItem['role']; ?>"><?php echo ucfirst($userItem['role']); ?></span></td>
                                <td><?php echo formatDate($userItem['created_at']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

<?php include '../../../UI/components/Footer.php'; ?>
