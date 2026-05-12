<?php
session_start();
require_once __DIR__ . '/../../src/Core/Session.php';

$session = new Session();

if (!$session->isLoggedIn()) {
    header('Location: ../login.php');
    exit;
}

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../src/Models/Country.php';

try {
    $db = (new Database())->getConnection();
    $countryModel = new Country($db);
    $countries = $countryModel->getAll();
} catch (Exception $e) {
    error_log("Admin countries index error: " . $e->getMessage());
    $countries = [];
}
?>

<?php include '../../includes/header.php'; ?>

<div class="admin-header">
    <div>
        <h1>Manage Countries</h1>
        <p>Welcome, <?php echo htmlspecialchars($session->get('username'), ENT_QUOTES, 'UTF-8'); ?></p>
    </div>
    <a href="create.php" class="btn btn-primary">+ Add New Country</a>
</div>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success">
        <?php 
            echo htmlspecialchars($_SESSION['success'], ENT_QUOTES, 'UTF-8');
            unset($_SESSION['success']);
        ?>
    </div>
<?php endif; ?>

<?php if (empty($countries)): ?>
    <div class="empty-state">
        <h3>No countries in the database yet</h3>
        <p>Start by adding your first country to the travel guide.</p>
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
                        <td>
                            <a href="/ProjektPHP/country.php?id=<?php echo $country['id']; ?>" target="_blank">
                                <?php echo htmlspecialchars($country['name'], ENT_QUOTES, 'UTF-8'); ?>
                            </a>
                        </td>
                        <td><?php echo htmlspecialchars($country['capital'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($country['continent'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo number_format($country['population']); ?></td>
                        <td><?php echo htmlspecialchars($country['best_season'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td class="actions">
                            <a href="edit.php?id=<?php echo $country['id']; ?>" class="btn btn-edit">Edit</a>
                            <form method="POST" action="delete.php" style="display: inline;" 
                                  onsubmit="return confirm('Are you sure you want to delete <?php echo htmlspecialchars($country['name'], ENT_QUOTES, 'UTF-8'); ?>?');">
                                <input type="hidden" name="id" value="<?php echo $country['id']; ?>">
                                <input type="hidden" name="csrf_token" value="<?php echo $session->generateCsrfToken(); ?>">
                                <button type="submit" class="btn btn-delete">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php include '../../includes/footer.php'; ?>