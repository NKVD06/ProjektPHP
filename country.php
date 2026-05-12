<?php
session_start();
require_once __DIR__ . '/config/Database.php';
require_once __DIR__ . '/src/Models/Country.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header('Location: index.php');
    exit;
}

try {
    $db = (new Database())->getConnection();
    $countryModel = new Country($db);
    $country = $countryModel->getById($id);
    
    if (!$country) {
        header('Location: index.php');
        exit;
    }
} catch (Exception $e) {
    error_log("Country detail error: " . $e->getMessage());
    header('Location: index.php');
    exit;
}
?>

<?php include 'includes/header.php'; ?>

<div class="country-detail">
    <nav class="breadcrumb">
        <a href="index.php">Home</a> / 
        <a href="index.php?continent=<?php echo urlencode($country['continent']); ?>">
            <?php echo htmlspecialchars($country['continent'], ENT_QUOTES, 'UTF-8'); ?>
        </a> / 
        <span><?php echo htmlspecialchars($country['name'], ENT_QUOTES, 'UTF-8'); ?></span>
    </nav>
    
    <div class="country-hero">
        <?php if ($country['image_url']): ?>
            <img src="<?php echo htmlspecialchars($country['image_url'], ENT_QUOTES, 'UTF-8'); ?>" 
                 alt="<?php echo htmlspecialchars($country['name'], ENT_QUOTES, 'UTF-8'); ?>">
        <?php endif; ?>
        <h1><?php echo htmlspecialchars($country['name'], ENT_QUOTES, 'UTF-8'); ?></h1>
    </div>
    
    <div class="country-content">
        <div class="country-meta">
            <div class="meta-item">
                <strong>🏛️ Capital:</strong> 
                <?php echo htmlspecialchars($country['capital'], ENT_QUOTES, 'UTF-8'); ?>
            </div>
            <div class="meta-item">
                <strong>🌍 Continent:</strong> 
                <?php echo htmlspecialchars($country['continent'], ENT_QUOTES, 'UTF-8'); ?>
            </div>
            <div class="meta-item">
                <strong>👥 Population:</strong> 
                <?php echo number_format($country['population']); ?>
            </div>
            <div class="meta-item">
                <strong>🗣️ Language:</strong> 
                <?php echo htmlspecialchars($country['language'], ENT_QUOTES, 'UTF-8'); ?>
            </div>
            <div class="meta-item">
                <strong>💰 Currency:</strong> 
                <?php echo htmlspecialchars($country['currency'], ENT_QUOTES, 'UTF-8'); ?>
            </div>
            <?php if ($country['best_season']): ?>
                <div class="meta-item">
                    <strong>📅 Best Season:</strong> 
                    <?php echo htmlspecialchars($country['best_season'], ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="country-description">
            <h2>About <?php echo htmlspecialchars($country['name'], ENT_QUOTES, 'UTF-8'); ?></h2>
            <p><?php echo nl2br(htmlspecialchars($country['description'], ENT_QUOTES, 'UTF-8')); ?></p>
        </div>
        
        <div class="country-actions">
            <a href="index.php" class="btn-back">← Back to Countries</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="admin/countries/edit.php?id=<?php echo $country['id']; ?>" class="btn btn-edit">Edit Country</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>