<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../src/Models/Country.php';

$db = (new Database())->getConnection();
$countryModel = new Country($db);
$countries = $countryModel->getAll();
?>

<?php include '../../includes/header.php'; ?>

<div class="admin-header">
    <h1>Manage Countries</h1>
    <a href="create.php" class="btn btn-primary">Add New Country</a>
</div>

<?php if (empty($countries)): ?>
    <div class="empty-state">
        <p>No countries in the database yet.</p>
        <a href="create.php" class="btn btn-primary">Add First Country</a>
    </div>
<?php else: ?>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Country</th>
                    <th>Capital</th>
                    <th>Continent</th>
                    <th>Population</th>
                    <th>Best Season</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($countries as $country): ?>
                    <tr>
                        <td><?php echo $country['id']; ?></td>
                        <td><?php echo htmlspecialchars($country['name']); ?></td>
                        <td><?php echo htmlspecialchars($country['capital']); ?></td>
                        <td><?php echo htmlspecialchars($country['continent']); ?></td>
                        <td><?php echo number_format($country['population']); ?></td>
                        <td><?php echo htmlspecialchars($country['best_season']); ?></td>
                        <td class="actions">
                            <a href="edit.php?id=<?php echo $country['id']; ?>" class="btn btn-edit">Edit</a>
                            <a href="delete.php?id=<?php echo $country['id']; ?>" 
                               class="btn btn-delete" 
                               onclick="return confirm('Delete <?php echo htmlspecialchars($country['name']); ?>?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php include '../../includes/footer.php'; ?>