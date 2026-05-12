<?php
session_start();
require_once __DIR__ . '/config/Database.php';
require_once __DIR__ . '/src/Models/Country.php';

$id = (int)($_GET['id'] ?? 0);

$db = (new Database())->getConnection();
$countryModel = new Country($db);
$country = $countryModel->getById($id);

if (!$country) {
    header('Location: index.php');
    exit;
}
?>

<?php include 'includes/header.php'; ?>

<div class="country-detail">
    <div class="country-hero">
        <?php if ($country['image_url']): ?>
            <img src="<?php echo htmlspecialchars($country['image_url']); ?>" 
                 alt="<?php echo htmlspecialchars($country['name']); ?>">
        <?php endif; ?>
        <h1><?php echo htmlspecialchars($country['name']); ?></h1>
    </div>
    
    <div class="country-content">
        <div class="country-meta">
            <div class="meta-item">
                <strong>Capital:</strong> 
                <?php echo htmlspecialchars($country['capital']); ?>
            </div>
            <div class="meta-item">
                <strong>Continent:</strong> 
                <?php echo htmlspecialchars($country['continent']); ?>
            </div>
            <div class="meta-item">
                <strong>Population:</strong> 
                <?php echo number_format($country['population']); ?>
            </div>
            <div class="meta-item">
                <strong>Language:</strong> 
                <?php echo htmlspecialchars($country['language']); ?>
            </div>
            <div class="meta-item">
                <strong>Currency:</strong> 
                <?php echo htmlspecialchars($country['currency']); ?>
            </div>
            <div class="meta-item">
                <strong>Best Season to Visit:</strong> 
                <?php echo htmlspecialchars($country['best_season']); ?>
            </div>
        </div>
        
        <div class="country-description">
            <h2>About <?php echo htmlspecialchars($country['name']); ?></h2>
            <p><?php echo nl2br(htmlspecialchars($country['description'])); ?></p>
        </div>
        
        <a href="index.php" class="btn-back">Back to Countries</a>
    </div>
</div>

<?php include 'includes/footer.php'; ?>