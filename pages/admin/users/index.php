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
                        <th>Role Actions</th>
                        <th>User Details</th>
                        <th>Delete User</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="9" class="text-center">No users found</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($users as $userItem): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($userItem['id']); ?></td>
                                <td><?php echo htmlspecialchars($userItem['name']); ?></td>
                                <td><?php echo htmlspecialchars($userItem['email']); ?></td>
                                <td><?php echo htmlspecialchars($userItem['phone'] ?? 'N/A'); ?></td>
                                <td><span
                                        class="badge badge-<?php echo htmlspecialchars($userItem['role']); ?>"><?php echo ucfirst(htmlspecialchars($userItem['role'])); ?></span>
                                </td>

                                <td><?php echo formatDate($userItem['created_at']); ?></td>
                                <td>
                                    <?php if ($userItem['role'] !== 'admin'): ?>
                                        <form method="POST" action="<?php echo BASE_URL; ?>/controllers/UserController.php"
                                            style="display: inline;">
                                            <input type="hidden" name="action" value="update_role">
                                            <input type="hidden" name="user_id" value="<?php echo (int) $userItem['id']; ?>">
                                            <button type="submit" class="btn btn-sm btn-warning">Update to Admin</button>
                                        </form>
                                    <?php else: ?>
                                        <span class="admin-user-static-label">Already Admin</span>
                                    <?php endif; ?>
                                </td>
                                <td><a href="manage.php?id=<?php echo (int) $userItem['id']; ?>" class="adminViewsUserButton">View
                                        Details</a></td>
                                <td>
                                    <?php if ((int) $userItem['id'] !== (int) $_SESSION['user_id'] && $userItem['role'] !== 'admin'): ?>
                                        <form method="POST" action="<?php echo BASE_URL; ?>/controllers/UserController.php"
                                            style="display:inline;">
                                            <input type="hidden" name="action" value="delete_user">
                                            <input type="hidden" name="user_id" value="<?php echo (int) $userItem['id']; ?>">
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Delete this user account permanently?')">Delete User</button>
                                        </form>
                                    <?php elseif ((int) $userItem['id'] === (int) $_SESSION['user_id']): ?>
                                        <span class="admin-user-static-label">Current Admin</span>
                                    <?php elseif ($userItem['role'] === 'admin'): ?>
                                        <span class="admin-user-static-label">Cannot Delete Admin</span>
                                    <?php endif; ?>
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